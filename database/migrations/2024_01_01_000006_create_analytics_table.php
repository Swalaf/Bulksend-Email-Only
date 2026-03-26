<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type'); // opened, clicked, bounced, unsubscribed
            $table->string('url')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['campaign_id', 'event_type']);
            $table->index(['occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
