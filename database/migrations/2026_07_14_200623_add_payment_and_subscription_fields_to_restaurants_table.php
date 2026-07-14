<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('operating_hours');
            $table->string('branch_code')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('branch_code');
            $table->string('account_holder_name')->nullable()->after('account_number');
            $table->string('subscription_plan')->default('basic')->after('account_holder_name');
        });
    }
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name',
                'branch_code',
                'account_number',
                'account_holder_name',
                'subscription_plan'
            ]);
        });
    }
};
