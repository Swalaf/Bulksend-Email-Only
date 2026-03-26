<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_send_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->foreignId('smtp_account_id')->constrained()->onDelete('cascade');
            $table->string('message_id')->nullable();
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->string('subject')->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();

            $table->index(['campaign_id', 'status']);
            $table->index(['subscriber_id', 'campaign_id']);
            $table->index('message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_send_logs');
    }
};
