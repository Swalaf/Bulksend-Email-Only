<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_duplicates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('new_campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['original_campaign_id', 'new_campaign_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_duplicates');
    }
};
