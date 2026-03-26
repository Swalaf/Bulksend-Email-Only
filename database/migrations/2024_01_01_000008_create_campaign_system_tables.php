<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Campaign Subscriber pivot with status
        Schema::create('campaign_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->foreignId('smtp_account_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'sending', 'sent', 'opened', 'clicked', 'bounced', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->text('bounce_reason')->nullable();
            $table->string('message_id')->nullable(); // Email message ID
            $table->string('tracking_token')->unique(); // For open/click tracking
            $table->integer('send_order')->default(0);
            $table->timestamps();
            $table->unique(['campaign_id', 'subscriber_id']);
            $table->index(['campaign_id', 'status']);
        });

        // Campaign Scheduling
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->nullable()->change();
            $table->timestamp('started_at')->nullable()->after('scheduled_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->integer('batch_size')->default(50)->after('completed_at');
            $table->integer('batch_delay')->default(1000)->after('batch_size'); // milliseconds
        });

        // A/B Testing
        Schema::create('campaign_ab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('variant_a_subject')->nullable();
            $table->string('variant_b_subject')->nullable();
            $table->text('variant_a_content')->nullable();
            $table->text('variant_b_content')->nullable();
            $table->integer('test_percentage')->default(20); // % of list to test
            $table->integer('winner_percentage')->default(50); // % to declare winner
            $table->enum('winner', ['a', 'b', 'none'])->default('none');
            $table->timestamp('test_started_at')->nullable();
            $table->timestamp('test_completed_at')->nullable();
            $table->timestamps();
        });

        // Sending Logs
        Schema::create('campaign_send_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('smtp_account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('message_id')->nullable();
            $table->string('smtp_response')->nullable();
            $table->enum('status', ['queued', 'sent', 'failed', 'bounced'])->default('queued');
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['campaign_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        // Email Templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('subject');
            $table->text('content');
            $table->text('html_content');
            $table->text('plain_text_content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_public')->default(false); // For marketplace
            $table->timestamps();
            $table->index(['user_id', 'is_public']);
        });

        // Campaign Duplicates
        Schema::create('campaign_duplicates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('new_campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->timestamps();
        });

        // Tracking Links
        Schema::create('campaign_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('url')->nullable(); // Original URL
            $table->string('tracking_url')->unique(); // Encoded tracking URL
            $table->string('label')->nullable(); // Link label
            $table->integer('click_count')->default(0);
            $table->timestamps();
        });

        // Link Clicks
        Schema::create('campaign_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_link_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_link_clicks');
        Schema::dropIfExists('campaign_links');
        Schema::dropIfExists('campaign_duplicates');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('campaign_send_logs');
        Schema::dropIfExists('campaign_ab_tests');
        Schema::dropIfExists('campaign_subscribers');
    }
};
