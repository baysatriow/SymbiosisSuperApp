<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_fields', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('document_subfields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('document_fields')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('required')->default(true);
            $table->integer('max_size_mb')->default(10);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['field_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_subfields');
        Schema::dropIfExists('document_fields');
    }
};
