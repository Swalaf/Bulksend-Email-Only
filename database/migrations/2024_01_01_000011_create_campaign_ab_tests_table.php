<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_ab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('variant_a_subject')->nullable();
            $table->string('variant_b_subject')->nullable();
            $table->text('variant_a_html_content')->nullable();
            $table->text('variant_b_html_content')->nullable();
            $table->integer('variant_a_open_count')->default(0);
            $table->integer('variant_b_open_count')->default(0);
            $table->integer('variant_a_click_count')->default(0);
            $table->integer('variant_b_click_count')->default(0);
            $table->string('winner', 10)->nullable();
            $table->timestamp('test_started_at')->nullable();
            $table->timestamp('test_ended_at')->nullable();
            $table->integer('test_duration_hours')->default(24);
            $table->integer('sample_size_per_variant')->default(100);
            $table->timestamps();

            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_ab_tests');
    }
};
