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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            //  誰が（ユーザー）、どのお店を（レストラン）予約したか
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            
            //  どのテーブルを確保したか（最初は未指定や、席を自動割り当てしない場合は nullable にしておきます）
            $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null');
            
            $table->integer('num_of_people'); 
            $table->date('reservation_date'); 
            $table->time('reservation_time'); 
            $table->time('end_time')->nullable(); 
            
            //  予約のステータス（pending: 保留中, approved: 確定, canceled: キャンセル など）
            $table->string('status')->default('pending');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};