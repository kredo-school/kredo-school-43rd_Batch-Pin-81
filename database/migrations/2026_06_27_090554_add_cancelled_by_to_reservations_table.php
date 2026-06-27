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
        Schema::table('reservations', function (Blueprint $table) {
            //  キャンセルした人を記録するカラムオブジェクトを追加
            // 'customer'（顧客自身によるキャンセル）, 'restaurant'（店舗によるDecline/Cancel）のどちらかが入るようにします
            $table->string('cancelled_by')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //  ロールバック（元に戻す）時のためにカラム削除オブジェクトを定義
            $table->dropColumn('cancelled_by');
        });
    }
};