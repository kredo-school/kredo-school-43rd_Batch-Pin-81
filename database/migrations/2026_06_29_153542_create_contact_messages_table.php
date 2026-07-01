<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade'); //どの問い合わせ（親）に紐づくか
            $table->unsignedBigInteger('user_id')->nullable(); //誰が発言したか（ユーザーID、またはAdminの場合はAdminのIDかnull）
            $table->text('message'); //メッセージ本文
            $table->json('attachments')->nullable(); //添付画像（JSON形式でパスの配列を保存）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
