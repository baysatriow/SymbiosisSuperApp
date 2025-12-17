<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('chat_sessions')->onDelete('cascade');
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->longText('content');
            $table->integer('token_count')->default(0);
            $table->timestamps();

            $table->index(['session_id', 'created_at']);
        });

        Schema::create('chat_session_documents', function (Blueprint $table) {
            $table->foreignId('session_id')->constrained('chat_sessions')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');

            $table->primary(['session_id', 'document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_session_documents');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
