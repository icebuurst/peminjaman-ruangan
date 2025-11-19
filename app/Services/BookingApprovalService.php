<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Exception;

class BookingApprovalService
{
    /**
     * Attempt to approve booking via stored procedure.
     * Returns true on success, throws Exception on failure.
     */
    public function approve(int $bookingId): bool
    {
        try {
            DB::statement('CALL approve_booking(?)', [$bookingId]);
            return true;
        } catch (\Exception $e) {
            // propagate exception to caller for custom handling
            throw $e;
        }
    }
}
