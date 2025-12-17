<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('province_id')->nullable()->after('company_address');
            $table->string('province_name')->nullable()->after('province_id');
            $table->string('city_id')->nullable()->after('province_name');
            $table->string('city_name')->nullable()->after('city_id');
            $table->string('postal_code')->nullable()->after('city_name');
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'province_name', 'city_id', 'city_name', 'postal_code']);
        });
    }
};
