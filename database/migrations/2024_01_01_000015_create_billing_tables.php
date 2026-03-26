<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Subscription Plans
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('billing_period')->default(30); // days
            $table->integer('max_emails')->default(0); // 0 = unlimited
            $table->integer('max_subscribers')->default(0);
            $table->integer('max_smtp_accounts')->default(0);
            $table->integer('max_campaigns')->default(0);
            $table->boolean('has_analytics')->default(false);
            $table->boolean('has_ai')->default(false);
            $table->boolean('has_api')->default(false);
            $table->boolean('has_white_label')->default(false);
            $table->boolean('has_priority_support')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // User Subscriptions
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('restrict');
            $table->string('status')->default('active'); // active, paused, cancelled, past_due
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancels_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });

        // Usage Tracking
        Schema::create('usage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('resource_type'); // emails, subscribers, smtp_accounts
            $table->integer('count')->default(0);
            $table->integer('limit')->default(0);
            $table->string('period'); // monthly, yearly
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();

            $table->unique(['user_id', 'resource_type', 'period_start']);
            $table->index(['user_id', 'period_start']);
        });

        // Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // card, bank
            $table->string('stripe_payment_method_id')->nullable();
            $table->string('last4', 4)->nullable();
            $table->string('brand')->nullable(); // visa, mastercard
            $table->integer('exp_month')->nullable();
            $table->integer('exp_year')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_valid')->default(true);
            $table->timestamps();

            $table->index('user_id');
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('stripe_invoice_id')->nullable();
            $table->string('number')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('draft'); // draft, open, paid, void, failed
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('invoice_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('line_items')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });

        // Transactions / Payments
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->string('stripe_payment_id')->nullable();
            $table->string('type'); // payment, refund, subscription, credit
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('fee', 10, 2)->default(0); // processing fee
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->string('payment_method')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });

        // Credits / Prepaid Emails
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount'); // positive = credit, negative = debit
            $table->string('type'); // purchase, usage, bonus, refund
            $table->string('description')->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('usage_records');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
    }
};
