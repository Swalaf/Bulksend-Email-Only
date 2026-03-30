<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vendor Profiles
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('shop_name')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Platform commission %
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->decimal('pending_earnings', 12, 2)->default(0);
            $table->timestamps();
        });

        // Marketplace SMTP Listings
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendor_profiles')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('host');
            $table->integer('port')->default(587);
            $table->string('encryption')->default('tls');
            $table->string('from_address');
            $table->string('from_name')->nullable();
            
            // Pricing
            $table->enum('pricing_type', ['per_email', 'subscription'])->default('per_email');
            $table->decimal('price_per_email', 8, 4)->nullable();
            $table->decimal('monthly_subscription', 10, 2)->nullable();
            $table->integer('free_emails')->default(0);
            $table->integer('included_emails')->nullable(); // For subscriptions
            
            // Limits
            $table->integer('daily_limit')->default(1000);
            $table->integer('monthly_limit')->default(30000);
            
            // Features
            $table->json('features')->nullable(); // SPF, DKIM, dedicated IP, etc.
            $table->string('thumbnail')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'pending', 'active', 'rejected', 'suspended'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('purchase_count')->default(0);
            
            $table->timestamps();
        });

        // User Purchases/Subscriptions
        Schema::create('marketplace_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendor_profiles')->onDelete('cascade');
            
            // Purchase Details
            $table->enum('type', ['one_time', 'subscription'])->default('one_time');
            $table->decimal('amount', 10, 2);
            $table->decimal('vendor_amount', 10, 2); // After commission
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('commission_rate', 5, 2);
            
            // Credits
            $table->integer('emails_credit')->default(0);
            $table->integer('emails_used')->default(0);
            
            // Subscription
            $table->boolean('is_subscription')->default(false);
            $table->date('subscription_start')->nullable();
            $table->date('subscription_end')->nullable();
            $table->boolean('subscription_active')->default(false);
            $table->string('stripe_subscription_id')->nullable();
            
            // Payment
            $table->string('stripe_payment_id')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('purchased_at')->useCurrent();
            
            $table->timestamps();
        });

        // User's SMTP from marketplace (copy of listing)
        Schema::create('marketplace_smtp_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->constrained('marketplace_purchases')->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->string('name');
            $table->string('host');
            $table->integer('port');
            $table->string('encryption');
            $table->string('from_address');
            $table->string('from_name')->nullable();
            $table->text('username'); // Encrypted
            $table->text('password'); // Encrypted
            $table->integer('daily_limit');
            $table->integer('monthly_limit');
            $table->integer('emails_sent_today')->default(0);
            $table->integer('emails_sent_month')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Transactions / Payouts
        Schema::create('marketplace_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendor_profiles')->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained('marketplace_purchases')->onDelete('set null');
            $table->enum('type', ['sale', 'commission', 'payout', 'refund'])->default('sale');
            $table->decimal('amount', 10, 2);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->string('stripe_transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Vendor Payouts
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendor_profiles')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->string('stripe_transfer_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Marketplace Favorites
        Schema::create('marketplace_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('listing_id')->constrained('marketplace_listings')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_favorites');
        Schema::dropIfExists('vendor_payouts');
        Schema::dropIfExists('marketplace_transactions');
        Schema::dropIfExists('marketplace_smtp_accounts');
        Schema::dropIfExists('marketplace_purchases');
        Schema::dropIfExists('marketplace_listings');
        Schema::dropIfExists('vendor_profiles');
    }
};
