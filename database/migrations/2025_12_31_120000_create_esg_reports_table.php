<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('esg_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->json('progress')->nullable(); // {"current_chapter": 1, "total_chapters": 7, "percentage": 15, "current_section": "..."}
            $table->json('chapter_content')->nullable(); // Store generated content per chapter
            $table->string('output_format')->default('docx'); // docx, pdf
            $table->string('file_path')->nullable(); // Final file path after generation
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esg_reports');
    }
};
