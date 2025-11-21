<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use App\Models\JadwalReguler;
// Excel export removed; using PDF only

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'peminjam') {
            // Peminjam hanya lihat booking sendiri
            $bookings = Booking::with(['room', 'user'])
                ->where('id_user', $user->id)
                ->orderBy('tanggal_mulai', 'desc')
                ->get();
        } else {
            // Admin & Petugas lihat semua booking
            $bookings = Booking::with(['room', 'user'])
                ->orderBy('tanggal_mulai', 'desc')
                ->get();
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::orderBy('nama_room')->get();
        return view('bookings.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalize time inputs: accept H:i:s, H:i or h:i A (AM/PM)
        $normalizeTime = function ($value) {
            if (is_null($value) || $value === '') return $value;

            // If already H:i:s or H:i, try to parse directly
            try {
                $d = Carbon::createFromFormat('H:i:s', $value);
                return $d->format('H:i');
            } catch (\Exception $e) {
                // try H:i
                try {
                    $d = Carbon::createFromFormat('H:i', $value);
                    return $d->format('H:i');
                } catch (\Exception $e) {
                    // try AM/PM
                    try {
                        $d = Carbon::createFromFormat('h:i A', $value);
                        return $d->format('H:i');
                    } catch (\Exception $e) {
                        return $value; // leave for validator to handle
                    }
                }
            }
        };

        if ($request->has('jam_mulai')) {
            $request->merge(['jam_mulai' => $normalizeTime($request->input('jam_mulai'))]);
        }
        if ($request->has('jam_selesai')) {
            $request->merge(['jam_selesai' => $normalizeTime($request->input('jam_selesai'))]);
        }

        $validated = $request->validate([
            'id_room' => 'required|exists:room,id_room',
            'keperluan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $validated['id_user'] = Auth::id();
        $validated['status'] = 'pending'; // Default status

        // Use explicit transaction so we can control commit/rollback and logging
        // Before creating, ensure booking does not overlap a regular schedule for the same room
        // Iterate each date in the requested range and check JadwalReguler where hari matches
        $start = Carbon::parse($validated['tanggal_mulai']);
        $end = Carbon::parse($validated['tanggal_selesai']);
        $bookingStartTime = Carbon::createFromFormat('H:i', $validated['jam_mulai'])->format('H:i:s');
        $bookingEndTime = Carbon::createFromFormat('H:i', $validated['jam_selesai'])->format('H:i:s');

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // Map Carbon day name to Indonesian enum in jadwal_reguler
            $dayName = ucfirst($date->locale('id')->isoFormat('dddd'));
            // isoFormat('dddd') may return lowercase; ensure first letter uppercase to match enum (e.g., 'Senin')
            $dayName = mb_convert_case($dayName, MB_CASE_TITLE, 'UTF-8');

            $regs = JadwalReguler::where('id_room', $validated['id_room'])
                ->where('hari', $dayName)
                ->get();

            foreach ($regs as $reg) {
                $regStart = $reg->jam_mulai; // stored as string 'HH:MM:SS'
                $regEnd = $reg->jam_selesai;

                // overlap if not (regEnd <= bookingStart OR regStart >= bookingEnd)
                if (!(strtotime($regEnd) <= strtotime($bookingStartTime) || strtotime($regStart) >= strtotime($bookingEndTime))) {
                    // generate alternative suggestions and return with suggestions in session
                    $suggestionSvc = new \App\Services\BookingSuggestionService();
                    $alternatives = $suggestionSvc->suggest(
                        $validated['id_room'],
                        $validated['tanggal_mulai'],
                        $validated['tanggal_selesai'],
                        $validated['jam_mulai'],
                        $validated['jam_selesai'],
                        5, // limit
                        14, // searchDays
                        30 // stepMinutes
                    );

                    $message = 'Waktu bentrok dengan jadwal reguler pada ' . $date->format('Y-m-d');
                    if ($request->wantsJson()) {
                        $html = view('bookings._alternatives', ['alternatives' => $alternatives, 'booking' => null])->render();
                        return response()->json(['error' => $message, 'alternatives' => $alternatives, 'alternatives_html' => $html], 422);
                    }
                    return redirect()->back()->withInput()->with('error', $message)->with('alternatives', $alternatives);
                }
            }
        }

        DB::beginTransaction();
        try {
            // Create booking (application accepts submissions as pending)
            $booking = Booking::create($validated);

            // Create notification for admin/petugas
            $room = Room::find($validated['id_room']);
            $adminUsers = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get();
            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'booking_new',
                    'title' => 'Peminjaman Baru',
                    'message' => Auth::user()->name . ' mengajukan peminjaman ' . ($room->nama_room ?? ''),
                    'icon' => 'bi-calendar-plus',
                    'link' => route('bookings.index'),
                    'is_read' => false
                ]);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'booking_id' => $booking->id_booking, 'message' => 'Peminjaman berhasil diajukan']);
            }
        } catch (Exception $e) {
            DB::rollBack();

            // Specific overlap handling
            if ($e->getMessage() === 'overlap') {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Waktu bentrok dengan booking lain'], 422);
                }
                return redirect()->back()->withInput()->with('error', 'Waktu bentrok dengan booking lain');
            }

            // Log unexpected exception for debugging
            \Log::error('Booking store failed: ' . $e->getMessage(), [
                'user_id' => Auth::id() ?? null,
                'payload' => $validated,
            ]);

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan peminjaman');
        }

        return redirect()->route('bookings.index')->with('success', 'Peminjaman berhasil diajukan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['room', 'user'])->findOrFail($id);
        
        // Authorization check
        if (Auth::user()->role === 'peminjam' && $booking->id_user !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Only peminjam can edit their own pending bookings
        if (Auth::user()->role === 'peminjam' && 
            ($booking->id_user !== Auth::id() || $booking->status !== 'pending')) {
            abort(403, 'Unauthorized');
        }

        $rooms = Room::orderBy('nama_room')->get();
        return view('bookings.edit', compact('booking', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        // Only peminjam can edit their own pending bookings
        if (Auth::user()->role === 'peminjam' && 
            ($booking->id_user !== Auth::id() || $booking->status !== 'pending')) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'id_room' => 'required|exists:room,id_room',
            'keperluan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
            'status' => 'sometimes|in:pending,approved,rejected',
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Peminjaman berhasil diupdate');
    }

    /**
     * Reschedule a pending booking to a chosen alternative slot.
     */
    public function reschedule(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        // Only peminjam owner can reschedule their own pending bookings
        if (Auth::user()->role === 'peminjam' && ($booking->id_user !== Auth::id() || $booking->status !== 'pending')) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // before updating, ensure no conflict with jadwal_reguler for any date in range
        $start = Carbon::parse($validated['tanggal_mulai']);
        $end = Carbon::parse($validated['tanggal_selesai']);
        $bookingStartTime = Carbon::createFromFormat('H:i', $validated['jam_mulai'])->format('H:i:s');
        $bookingEndTime = Carbon::createFromFormat('H:i', $validated['jam_selesai'])->format('H:i:s');

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayName = mb_convert_case($date->locale('id')->isoFormat('dddd'), MB_CASE_TITLE, 'UTF-8');
            $regs = JadwalReguler::where('id_room', $booking->id_room)
                ->where('hari', $dayName)
                ->get();

            foreach ($regs as $reg) {
                $regStart = $reg->jam_mulai;
                $regEnd = $reg->jam_selesai;
                if (!(strtotime($regEnd) <= strtotime($bookingStartTime) || strtotime($regStart) >= strtotime($bookingEndTime))) {
                    $message = 'Waktu bentrok dengan jadwal reguler pada ' . $date->format('Y-m-d');
                    if ($request->wantsJson()) {
                        return response()->json(['error' => $message], 422);
                    }
                    return redirect()->back()->withInput()->with('error', $message);
                }
            }
        }

        DB::beginTransaction();
        try {
            $booking->tanggal_mulai = $validated['tanggal_mulai'];
            $booking->tanggal_selesai = $validated['tanggal_selesai'];
            $booking->jam_mulai = Carbon::createFromFormat('H:i', $validated['jam_mulai'])->format('H:i:s');
            $booking->jam_selesai = Carbon::createFromFormat('H:i', $validated['jam_selesai'])->format('H:i:s');
            $booking->save();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dijadwal ulang']);
            }
            return redirect()->route('bookings.index')->with('success', 'Peminjaman berhasil dijadwal ulang');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reschedule failed: ' . $e->getMessage(), ['booking_id' => $booking->id_booking]);
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal menyimpan perubahan'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan perubahan');
        }
    }

    /**
     * Cancel a pending booking (quick cancel).
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::user()->role === 'peminjam' && ($booking->id_user !== Auth::id() || $booking->status !== 'pending')) {
            if ($request->wantsJson()) return response()->json(['error' => 'Unauthorized'], 403);
            abort(403, 'Unauthorized');
        }

        try {
            $booking->delete();
            if ($request->wantsJson()) return response()->json(['success' => true]);
            return redirect()->route('bookings.index')->with('success', 'Peminjaman berhasil dibatalkan');
        } catch (\Exception $e) {
            \Log::error('Cancel booking failed: ' . $e->getMessage(), ['booking_id' => $booking->id_booking]);
            if ($request->wantsJson()) return response()->json(['error' => 'Gagal membatalkan peminjaman'], 500);
            return redirect()->back()->with('error', 'Gagal membatalkan peminjaman');
        }
    }

    /**
     * Update booking status (approve/reject)
     */
    public function updateStatus(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        // If approving, prefer executing DB stored procedure to enforce DB-side checks atomically
        if ($validated['status'] === 'approved') {
            // Application-level overlap check to provide clearer feedback before calling stored procedure
            $startDate = $booking->tanggal_mulai;
            $endDate = $booking->tanggal_selesai;
            $startTime = $booking->jam_mulai;
            $endTime = $booking->jam_selesai;

            // Find approved bookings that overlap this booking's time/window
            $conflicts = \DB::table('booking')
                ->select('id_booking','id_user','tanggal_mulai','jam_mulai','tanggal_selesai','jam_selesai','status')
                ->where('id_room', $booking->id_room)
                ->where('status', 'approved')
                ->whereRaw("NOT (CONCAT(tanggal_selesai,' ',jam_selesai) <= CONCAT(?, ' ', ?) OR CONCAT(tanggal_mulai,' ',jam_mulai) >= CONCAT(?, ' ', ?))", [$startDate, $startTime, $endDate, $endTime])
                ->get();

            if ($conflicts->isNotEmpty()) {
                // return details so admin can see what's blocking
                $message = 'Tidak dapat menyetujui: terdapat peminjaman lain yang sudah disetujui dan waktunya tumpang tindih.';
                if ($request->wantsJson()) {
                    return response()->json(['error' => $message, 'conflicts' => $conflicts], 422);
                }
                return redirect()->back()->with('error', $message)->with('conflicts', $conflicts);
            }

            // Also check jadwal_reguler conflicts for the booking's date range
            $start = \Carbon\Carbon::parse($booking->tanggal_mulai);
            $end = \Carbon\Carbon::parse($booking->tanggal_selesai);
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $dayName = mb_convert_case($date->locale('id')->isoFormat('dddd'), MB_CASE_TITLE, 'UTF-8');
                $regs = JadwalReguler::where('id_room', $booking->id_room)
                    ->where('hari', $dayName)
                    ->get();
                foreach ($regs as $reg) {
                    if (!(strtotime($reg->jam_selesai) <= strtotime($booking->jam_mulai) || strtotime($reg->jam_mulai) >= strtotime($booking->jam_selesai))) {
                        $message = 'Tidak dapat menyetujui: bentrok dengan jadwal reguler pada ' . $date->format('Y-m-d');
                        if ($request->wantsJson()) {
                            return response()->json(['error' => $message, 'reguler' => $reg], 422);
                        }
                        return redirect()->back()->with('error', $message)->with('reguler_conflict', $reg);
                    }
                }
            }

            // No obvious conflicts at app level — proceed with approval
            // Try using stored procedure first, fallback to direct update if procedure doesn't exist
            DB::beginTransaction();
            try {
                // Check if stored procedure exists
                $procedureExists = DB::select("
                    SELECT ROUTINE_NAME 
                    FROM information_schema.ROUTINES 
                    WHERE ROUTINE_SCHEMA = DATABASE() 
                    AND ROUTINE_NAME = 'approve_booking'
                ");
                
                if (!empty($procedureExists)) {
                    // Use stored procedure if available
                    $service = new \App\Services\BookingApprovalService();
                    $service->approve((int) $booking->id_booking);
                } else {
                    // Fallback to direct update if procedure doesn't exist
                    $booking->status = 'approved';
                    if (isset($validated['catatan'])) {
                        $booking->catatan = $validated['catatan'];
                    }
                    $booking->save();
                }
                
                DB::commit();

                // Create notification after successful approval
                Notification::create([
                    'user_id' => $booking->id_user,
                    'type' => 'booking_status',
                    'title' => 'Status Peminjaman',
                    'message' => 'Peminjaman ' . ($booking->room->nama_room ?? '') . ' telah disetujui',
                    'icon' => 'bi-check-circle-fill',
                    'link' => route('bookings.show', $booking->id_booking),
                    'is_read' => false
                ]);

                return redirect()->back()->with('success', 'Peminjaman disetujui');
            } catch (\Exception $e) {
                DB::rollBack();
                
                // Catch DB-level rejections
                if ($e->getMessage() === 'overlap' || str_contains($e->getMessage(), 'overlap') || str_contains($e->getMessage(), 'approve_failed')) {
                    return redirect()->back()->with('error', 'Waktu bentrok dengan booking lain (ditolak oleh DB)');
                }
                
                // Log detailed error information
                \Log::error('Booking approval failed', [
                    'booking_id' => $booking->id_booking,
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                ]);
                
                return redirect()->back()->with('error', 'Gagal approve: ' . $e->getMessage());
            }
        }

        // For non-approved status changes (rejected), keep the simple update
        $booking->update($validated);
        Notification::create([
            'user_id' => $booking->id_user,
            'type' => 'booking_status',
            'title' => 'Status Peminjaman',
            'message' => 'Peminjaman ' . ($booking->room->nama_room ?? '') . ' telah ditolak',
            'icon' => 'bi-x-circle-fill',
            'link' => route('bookings.show', $booking->id_booking),
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Peminjaman ditolak');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Only allow deletion of own pending bookings
        if (Auth::user()->role === 'peminjam' && 
            ($booking->id_user !== Auth::id() || $booking->status !== 'pending')) {
            abort(403, 'Unauthorized');
        }

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Peminjaman berhasil dihapus');
    }

    /**
     * Show laporan page with filters
     */
    public function laporan(Request $request)
    {
        // Only admin and petugas can access
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403, 'Unauthorized');
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $bookings = Booking::with(['user', 'room'])
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('bookings.laporan', compact('bookings', 'startDate', 'endDate'));
    }

    /**
     * Export bookings to Excel or PDF
     */
    public function export(Request $request)
    {
        // Only admin and petugas can export
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'nullable|in:excel,pdf'
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $format = $validated['format'] ?? 'excel';

        if ($format === 'pdf') {
            // Generate PDF using a blade view (guard if package not installed)
            $bookings = Booking::with(['user', 'room'])
                ->whereBetween('tanggal_mulai', [$startDate, $endDate])
                ->orderBy('tanggal_mulai', 'desc')
                ->get();

            // Check if DomPDF binding or class exists to avoid runtime error
            if (!app()->bound('dompdf.wrapper') && !class_exists(\Barryvdh\DomPDF\PDF::class)) {
                return redirect()->back()->with('error', 'PDF generator tidak tersedia. Install barryvdh/laravel-dompdf atau gunakan export Excel.');
            }

            // Prefer the common binding name if present
            if (app()->bound('dompdf.wrapper')) {
                $pdf = app('dompdf.wrapper');
            } else {
                $pdf = app()->make(\Barryvdh\DomPDF\PDF::class);
            }
            $pdf->loadView('bookings.pdf', compact('bookings', 'startDate', 'endDate'));

            $filename = 'Laporan_Peminjaman_' . date('Ymd_His') . '.pdf';
            return $pdf->download($filename);
        }

        $filename = 'Laporan_Peminjaman_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new BookingsExport($startDate, $endDate), $filename);
    }
}
