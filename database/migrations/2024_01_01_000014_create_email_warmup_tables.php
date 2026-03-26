<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main warmup configuration per SMTP account
        Schema::create('email_warmups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smtp_account_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('inactive'); // inactive, active, paused, completed
            $table->integer('current_daily_limit')->default(10);
            $table->integer('target_daily_limit')->default(500);
            $table->integer('current_day')->default(0);
            $table->integer('total_days')->default(30);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('smtp_account_id');
            $table->index('status');
        });

        // Individual warmup emails sent
        Schema::create('warmup_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_warmup_id')->constrained()->onDelete('cascade');
            $table->foreignId('smtp_account_id')->constrained()->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('subject')->nullable();
            $table->string('type'); // engagement, reply, confirmation
            $table->string('status')->default('pending'); // pending, sent, delivered, opened, replied
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->integer('send_order')->nullable();
            $table->timestamps();

            $table->index(['email_warmup_id', 'status']);
            $table->index(['smtp_account_id', 'sent_at']);
        });

        // Warmup engagement logs
        Schema::create('warmup_engagement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warmup_email_id')->constrained()->onDelete('cascade');
            $table->foreignId('email_warmup_id')->constrained()->onDelete('cascade');
            $table->string('engagement_type'); // open, click, reply
            $table->timestamp('engaged_at');
            $table->timestamps();

            $table->index(['email_warmup_id', 'engagement_type']);
        });

        // Auto-reply simulations
        Schema::create('warmup_auto_replies', function (Blueprint $table) {
            $table->id();
            $table->string('email_pattern'); // e.g., noreply@, support@
            $table->string('reply_subject');
            $table->text('reply_body');
            $table->integer('response_delay_hours')->default(24);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Warmup daily stats
        Schema::create('warmup_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_warmup_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('emails_sent')->default(0);
            $table->integer('emails_delivered')->default(0);
            $table->integer('emails_opened')->default(0);
            $table->integer('emails_replied')->default(0);
            $table->integer('emails_clicked')->default(0);
            $table->integer('daily_limit')->default(0);
            $table->timestamps();

            $table->unique(['email_warmup_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warmup_daily_stats');
        Schema::dropIfExists('warmup_auto_replies');
        Schema::dropIfExists('warmup_engagement_logs');
        Schema::dropIfExists('warmup_emails');
        Schema::dropIfExists('email_warmups');
    }
};
