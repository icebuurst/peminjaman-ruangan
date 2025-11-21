<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Timestamp when user confirmed the booking
            $table->timestamp('confirmed_at')->nullable()->after('status');
            
            // Deadline for user to confirm (12 hours after approval)
            $table->timestamp('confirmation_deadline')->nullable()->after('confirmed_at');
            
            // Track last reminder sent to admin/petugas
            $table->timestamp('last_reminder_sent_at')->nullable()->after('confirmation_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'confirmation_deadline', 'last_reminder_sent_at']);
        });
    }
};
