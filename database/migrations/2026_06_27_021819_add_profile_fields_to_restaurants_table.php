<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // 💡 既存の phone_number の後ろにSNSやウェブサイトを追加
            $table->string('website')->nullable()->after('phone_number');
            $table->string('instagram')->nullable()->after('website');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('twitter')->nullable()->after('facebook');

            // 💡 1グループの最大人数（capacity）と、予約枠の滞在時間（stay_duration）
            $table->integer('capacity')->nullable()->after('twitter');
            $table->integer('stay_duration')->default(120)->after('capacity'); // 初期値2時間

            // 💡 お友達が作った多次元の営業時間配列オブジェクトをそのまま保存する器
            $table->text('operating_hours')->nullable()->after('stay_duration');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'instagram',
                'facebook',
                'twitter',
                'capacity',
                'stay_duration',
                'operating_hours'
            ]);
        });
    }
};
