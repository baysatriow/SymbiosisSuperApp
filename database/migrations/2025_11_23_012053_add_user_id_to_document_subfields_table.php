<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_subfields', function (Blueprint $table) {
            // 1. Tambahkan kolom baru terlebih dahulu
            $table->boolean('is_custom')->default(false)->after('description');
            $table->foreignId('user_id')->nullable()->after('is_custom')->constrained('users')->onDelete('cascade');

            // 2. FIX ERROR 1553: Hapus Foreign Key 'field_id' DULU sebelum menghapus index
            // Laravel otomatis mendeteksi nama FK: document_subfields_field_id_foreign
            $table->dropForeign(['field_id']);

            // 3. Hapus Unique Constraint (sekarang aman karena tidak ada FK yang mengikat)
            $table->dropUnique(['field_id', 'name']);

            // 4. Pasang kembali Foreign Key 'field_id'
            // Ini akan otomatis membuat index standard (non-unique) baru untuk field_id
            $table->foreign('field_id')
                  ->references('id')
                  ->on('document_fields')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('document_subfields', function (Blueprint $table) {
            // Rollback: Urutan dibalik

            // 1. Hapus FK saat ini
            $table->dropForeign(['field_id']);

            // 2. Kembalikan Unique Constraint (ini akan menjadi index buat FK)
            $table->unique(['field_id', 'name']);

            // 3. Pasang kembali FK
            $table->foreign('field_id')
                  ->references('id')
                  ->on('document_fields')
                  ->onDelete('cascade');

            // 4. Hapus kolom baru
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_custom']);
        });
    }
};
