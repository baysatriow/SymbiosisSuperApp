<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geoportals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title'); // Nama Lokasi / Perusahaan
            $table->text('description')->nullable();

            // Menyimpan Array Koordinat: [[lat, lng], [lat, lng], ...]
            $table->json('coordinates');

            // Menyimpan Data Fluid: {"Luas": "1 Ha", "Bahan Galian": "Nikel", ...}
            $table->json('properties')->nullable();

            // Tipe: Polygon (Area) atau Point (Titik)
            $table->enum('type', ['polygon', 'point'])->default('polygon');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geoportals');
    }
};
