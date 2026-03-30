<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Campaign Subscriber table already created in 2024_01_01_000007_create_campaign_subscribers_table.php

        // Campaign Scheduling - columns already exist from previous migrations
        // Schema::table('campaigns', function (Blueprint $table) {
        //     $table->timestamp('scheduled_at')->nullable()->change();
        //     $table->timestamp('started_at')->nullable()->after('scheduled_at');
        //     $table->timestamp('completed_at')->nullable()->after('started_at');
        //     $table->integer('batch_size')->default(50)->after('completed_at');
        //     $table->integer('batch_delay')->default(1000)->after('batch_size'); // milliseconds
        // });

        // A/B Testing table already created in 2024_01_01_000011_create_campaign_ab_tests_table.php

        // Sending Logs table already created in 2024_01_01_000008_create_campaign_send_logs_table.php

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

        // Tracking Links table already created in 2024_01_01_000009_create_campaign_links_table.php

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
