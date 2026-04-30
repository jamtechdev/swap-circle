<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuretechPlatformTables extends Migration
{
    public function up()
    {
        Schema::create('it_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        Schema::create('it_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 120)->unique();
            $table->string('slug', 120)->unique();
            $table->timestamps();
        });

        Schema::create('it_role_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->primary(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on('it_roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('it_permissions')->onDelete('cascade');
        });

        Schema::create('it_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status', 20)->default('active')->index();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('it_user_role', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('it_users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('it_roles')->onDelete('cascade');
        });

        Schema::create('it_partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('partner_code', 50)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('base_url')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('it_partner_api_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('partner_id');
            $table->string('name', 100);
            $table->string('token_hash', 128)->unique();
            $table->string('token_prefix', 12)->index();
            $table->json('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('it_partners')->onDelete('cascade');
            $table->index(['partner_id', 'expires_at']);
        });

        Schema::create('it_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code', 50)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('currency', 10)->default('NGN')->index();
            $table->decimal('price', 12, 2)->default(0);
            $table->enum('cover_duration_rule', ['monthly', 'annual', 'both'])->default('both');
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
        });

        Schema::create('it_product_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->string('field_key', 120);
            $table->string('label', 150);
            $table->enum('field_type', ['text', 'number', 'date', 'dropdown', 'boolean', 'email', 'phone']);
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('options')->nullable();
            $table->json('rules')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('it_products')->onDelete('cascade');
            $table->unique(['product_id', 'field_key']);
            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('it_partner_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('product_id');
            $table->string('status', 20)->default('enabled')->index();
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('it_partners')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('it_products')->onDelete('cascade');
            $table->unique(['partner_id', 'product_id']);
        });

        Schema::create('it_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('external_customer_id', 80)->nullable()->index();
            $table->unsignedBigInteger('partner_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('first_name', 120)->index();
            $table->string('surname', 120)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->nullable()->index();
            $table->date('date_of_birth')->nullable()->index();
            $table->unsignedTinyInteger('age')->nullable()->index();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->index();
            $table->text('address')->nullable();
            $table->date('cover_start_date')->index();
            $table->date('cover_end_date')->index();
            $table->enum('cover_duration', ['monthly', 'annual'])->index();
            $table->date('customer_since')->index();
            $table->dateTime('last_payment_date')->nullable()->index();
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('it_partners')->onDelete('restrict');
            $table->foreign('product_id')->references('id')->on('it_products')->onDelete('restrict');
            $table->index(['partner_id', 'product_id', 'created_at'], 'it_cust_partner_product_created_idx');
        });

        Schema::create('it_customer_meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_field_id')->nullable();
            $table->string('meta_key', 120)->index();
            $table->text('meta_value')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('it_customers')->onDelete('cascade');
            $table->foreign('product_field_id')->references('id')->on('it_product_fields')->onDelete('set null');
            $table->index(['customer_id', 'meta_key']);
        });

        Schema::create('it_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('partner_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('external_payment_id', 120)->nullable()->index();
            $table->decimal('amount', 12, 2)->default(0)->index();
            $table->string('currency', 10)->index();
            $table->string('status', 30)->default('recorded')->index();
            $table->json('metadata')->nullable();
            $table->dateTime('paid_at')->nullable()->index();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('it_customers')->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('it_partners')->onDelete('restrict');
            $table->foreign('product_id')->references('id')->on('it_products')->onDelete('restrict');
        });

        Schema::create('it_audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('partner_id')->nullable()->index();
            $table->string('action', 120)->index();
            $table->string('entity_type', 120)->nullable()->index();
            $table->string('entity_id', 120)->nullable()->index();
            $table->string('ip_address', 64)->nullable()->index();
            $table->string('user_agent', 500)->nullable();
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('it_audit_logs');
        Schema::dropIfExists('it_payments');
        Schema::dropIfExists('it_customer_meta');
        Schema::dropIfExists('it_customers');
        Schema::dropIfExists('it_partner_product');
        Schema::dropIfExists('it_product_fields');
        Schema::dropIfExists('it_products');
        Schema::dropIfExists('it_partner_api_tokens');
        Schema::dropIfExists('it_partners');
        Schema::dropIfExists('it_user_role');
        Schema::dropIfExists('it_users');
        Schema::dropIfExists('it_role_permission');
        Schema::dropIfExists('it_permissions');
        Schema::dropIfExists('it_roles');
    }
}
