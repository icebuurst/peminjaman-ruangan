<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\JadwalReguler;
use Carbon\Carbon;

class BookingSuggestionService
{
    /**
     * Suggest alternative slots for a requested booking time.
     * Returns array of suggestions: [ ['date' => 'Y-m-d', 'jam_mulai' => 'H:i', 'jam_selesai' => 'H:i'], ... ]
     *
     * Simple algorithm: scan next $searchDays days starting from $startDate, try slots every $stepMinutes
     * between $dayStart and $dayEnd, and return up to $limit non-conflicting slots.
     */
    public function suggest(int $idRoom, string $startDate, string $endDate, string $jamMulai, string $jamSelesai, int $limit = 5, int $searchDays = 14, int $stepMinutes = 30, string $dayStart = '08:00', string $dayEnd = '18:00', bool $includePending = false)
    {
        $results = [];

        $durationMinutes = $this->minutesBetween($jamMulai, $jamSelesai);
        if ($durationMinutes <= 0) {
            return $results;
        }

        $start = Carbon::parse($startDate);
        $searchEnd = Carbon::parse($startDate)->addDays($searchDays);

        // Preload approved bookings for the room in the search window
        $bookings = Booking::where('id_room', $idRoom)
            ->when(!$includePending, function($q) { return $q->where('status', 'approved'); })
            ->when($includePending, function($q) { return $q->whereIn('status', ['approved', 'pending']); })
            ->where(function ($q) use ($start, $searchEnd) {
                $q->whereBetween('tanggal_mulai', [$start->format('Y-m-d'), $searchEnd->format('Y-m-d')])
                  ->orWhereBetween('tanggal_selesai', [$start->format('Y-m-d'), $searchEnd->format('Y-m-d')])
                  ->orWhere(function ($q2) use ($start, $searchEnd) {
                      $q2->where('tanggal_mulai', '<=', $start->format('Y-m-d'))
                         ->where('tanggal_selesai', '>=', $searchEnd->format('Y-m-d'));
                  });
            })->get();

        // Group bookings by date for quick checks
        $bookingsByDate = [];
        foreach ($bookings as $b) {
            $from = Carbon::parse($b->tanggal_mulai);
            $to = Carbon::parse($b->tanggal_selesai);
            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                $date = $d->format('Y-m-d');
                $bookingsByDate[$date][] = [
                    'jam_mulai' => substr($b->jam_mulai, 0, 5),
                    'jam_selesai' => substr($b->jam_selesai, 0, 5),
                ];
            }
        }

        // Preload regular schedules for the room grouped by weekday name
        $regs = JadwalReguler::where('id_room', $idRoom)->get();
        $regsByWeekday = [];
        foreach ($regs as $r) {
            $weekday = $r->hari; // expecting values like 'Senin', 'Selasa', etc.
            $regsByWeekday[$weekday][] = [
                'jam_mulai' => substr($r->jam_mulai, 0, 5),
                'jam_selesai' => substr($r->jam_selesai, 0, 5),
            ];
        }

        // Iterate days and candidate slots
        for ($date = Carbon::parse($startDate)->copy(); $date->lte($searchEnd); $date->addDay()) {
            // stop when we have enough results
            if (count($results) >= $limit) break;

            $dayName = mb_convert_case($date->locale('id')->isoFormat('dddd'), MB_CASE_TITLE, 'UTF-8');

            // Build candidate start times from dayStart to dayEnd-step
            $slotStart = Carbon::parse($date->format('Y-m-d') . ' ' . $dayStart);
            $slotEndBoundary = Carbon::parse($date->format('Y-m-d') . ' ' . $dayEnd);

            while ($slotStart->copy()->addMinutes($durationMinutes)->lte($slotEndBoundary)) {
                if (count($results) >= $limit) break;

                $candidateStart = $slotStart->format('H:i');
                $candidateEnd = $slotStart->copy()->addMinutes($durationMinutes)->format('H:i');

                // Check against regs for this weekday
                $conflict = false;
                if (isset($regsByWeekday[$dayName])) {
                    foreach ($regsByWeekday[$dayName] as $reg) {
                        if ($this->timesOverlap($candidateStart, $candidateEnd, $reg['jam_mulai'], $reg['jam_selesai'])) {
                            $conflict = true; break;
                        }
                    }
                }

                if (!$conflict) {
                    // Check against approved bookings on this date
                    $dateKey = $date->format('Y-m-d');
                    if (isset($bookingsByDate[$dateKey])) {
                        foreach ($bookingsByDate[$dateKey] as $ab) {
                            if ($this->timesOverlap($candidateStart, $candidateEnd, $ab['jam_mulai'], $ab['jam_selesai'])) {
                                $conflict = true; break;
                            }
                        }
                    }
                }

                if (!$conflict) {
                    $results[] = [
                        'date' => $date->format('Y-m-d'),
                        'jam_mulai' => $candidateStart,
                        'jam_selesai' => $candidateEnd,
                    ];
                }

                $slotStart->addMinutes($stepMinutes);
            }
        }

        return $results;
    }

    private function minutesBetween(string $start, string $end): int
    {
        $s = Carbon::createFromFormat('H:i', $start);
        $e = Carbon::createFromFormat('H:i', $end);
        return $e->diffInMinutes($s);
    }

    private function timesOverlap(string $aStart, string $aEnd, string $bStart, string $bEnd): bool
    {
        $aS = strtotime($aStart);
        $aE = strtotime($aEnd);
        $bS = strtotime($bStart);
        $bE = strtotime($bEnd);
        return !($aE <= $bS || $aS >= $bE);
    }
}
