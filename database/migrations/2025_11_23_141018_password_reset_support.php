<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kolom ENUM untuk mendukung 'password_reset'
        // Raw SQL digunakan karena Doctrine DBAL memiliki keterbatasan mengubah ENUM
        DB::statement("ALTER TABLE otp_codes MODIFY COLUMN purpose ENUM('register', 'login', 'password_reset') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE otp_codes MODIFY COLUMN purpose ENUM('register', 'login') NOT NULL");
    }
};
