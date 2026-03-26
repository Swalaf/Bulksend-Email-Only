<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->string('hash')->unique();
            $table->integer('click_count')->default(0);
            $table->integer('unique_click_count')->default(0);
            $table->timestamps();

            $table->index('campaign_id');
            $table->index('hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_links');
    }
};
