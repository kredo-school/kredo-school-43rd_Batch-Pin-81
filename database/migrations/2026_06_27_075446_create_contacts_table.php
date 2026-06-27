<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 👤 誰が送信したか（一般ユーザー、店舗オーナー、またはAdmin）
            $table->unsignedBigInteger('restaurant_id')->nullable(); // 🏢 もし「店舗として」のお問い合わせの場合、どの店舗からのものか記録
            $table->unsignedBigInteger('parent_id')->nullable();  // 💬 チャットの親メッセージID（スレッド識別用）
            $table->string('title')->nullable();
            $table->text('message');
            $table->string('status')->default('open'); // 🚦 対応ステータス（デフォルトは 'open'。Adminが返信したら 'replied' に更新）
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
