<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('phone_number')->nullable()->after('guest_name');
            $table->string('booking_source')->default('online')->after('phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'phone_number', 'booking_source']);
        });
    }
};
