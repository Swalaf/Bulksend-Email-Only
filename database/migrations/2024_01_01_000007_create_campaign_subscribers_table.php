<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->string('tracking_token')->nullable()->unique();
            $table->string('message_id')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'subscriber_id']);
            $table->index('status');
            $table->index('tracking_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_subscribers');
    }
};
