<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subfield_id')->constrained('document_subfields')->onDelete('restrict');

            $table->string('storage_path')->unique();
            $table->string('original_filename');
            $table->string('mime_type')->default('application/pdf');

            $table->unsignedBigInteger('size_bytes_original');
            $table->unsignedBigInteger('size_bytes_compressed')->nullable();
            $table->decimal('compression_ratio', 5, 2)->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->string('checksum_sha256')->unique();
            $table->enum('tier', ['green', 'amber', 'black'])->default('black');

            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'subfield_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
