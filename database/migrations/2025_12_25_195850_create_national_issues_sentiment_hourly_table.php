<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('national_issues_sentiment_hourly', function (Blueprint $table) {
            $table->id();
            $table->string('data_id')->unique();
            $table->string('platform');
            $table->dateTime('timestamp');
            $table->string('author_name');
            $table->text('content_text');
            $table->text('cleaned_text')->nullable();
            $table->float('sentiment_score');
            $table->string('sentiment_label');
            $table->string('category_issue');
            $table->integer('engagement_count')->default(0);
            $table->string('location')->nullable();
            $table->text('url_source');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_issues_sentiment_hourly');
    }
};
