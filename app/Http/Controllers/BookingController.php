<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $booking = Booking::create($validated);
        
        // Create notification for admin/petugas
        $room = Room::find($validated['id_room']);
        $adminUsers = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get();
        
        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'booking_new',
                'title' => 'Peminjaman Baru',
                'message' => Auth::user()->name . ' mengajukan peminjaman ' . $room->nama_room,
                'icon' => 'bi-calendar-plus',
                'link' => route('bookings.index'),
                'is_read' => false
            ]);
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
     * Update booking status (approve/reject)
     */
    public function updateStatus(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        $booking->update($validated);
        
        // Create notification for the user who made the booking
        $statusText = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';
        $statusIcon = $validated['status'] === 'approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        
        Notification::create([
            'user_id' => $booking->id_user,
            'type' => 'booking_status',
            'title' => 'Status Peminjaman',
            'message' => 'Peminjaman ' . $booking->room->nama_room . ' telah ' . $statusText,
            'icon' => $statusIcon,
            'link' => route('bookings.show', $booking->id_booking),
            'is_read' => false
        ]);

        $message = $validated['status'] === 'approved' ? 'Peminjaman disetujui' : 'Peminjaman ditolak';
        return redirect()->back()->with('success', $message);
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
     * Export bookings to Excel
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
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $filename = 'Laporan_Peminjaman_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new BookingsExport($startDate, $endDate), $filename);
    }
}
