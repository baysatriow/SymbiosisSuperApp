<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            // Menambahkan kolom ID untuk referensi API
            // Kolom 'city' dan 'province' (nama) sudah ada sebelumnya
            $table->string('province_id')->nullable()->after('address');
            $table->string('city_id')->nullable()->after('province_id');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'city_id']);
        });
    }
};
