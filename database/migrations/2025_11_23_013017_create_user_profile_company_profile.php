<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade');
            $table->string('job_title')->nullable();
            $table->text('company_address')->nullable();
            $table->string('job_sector')->nullable();
            $table->json('additional_primary_fields')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('company_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->enum('legal_entity_type', ['PT', 'CV', 'Fund', 'Org', 'UMKM'])->nullable();
            $table->string('nib_number')->nullable();
            $table->string('siup_number')->nullable();
            $table->string('tax_id_npwp')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('sector')->nullable();
            $table->integer('size_employees')->nullable();
            $table->year('year_founded')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
        Schema::dropIfExists('user_profiles');
    }
};
