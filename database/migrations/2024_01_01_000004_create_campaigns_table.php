<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('smtp_account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('subject');
            $table->text('content')->nullable();
            $table->text('html_content')->nullable();
            $table->text('plain_text_content')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->integer('clicked_count')->default(0);
            $table->integer('bounced_count')->default(0);
            $table->integer('unsubscribed_count')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
