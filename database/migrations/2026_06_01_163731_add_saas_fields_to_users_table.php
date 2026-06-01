<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('free')->after('password');
            $table->string('subscription_status')->default('active')->after('plan');
            $table->string('company_name')->nullable()->after('subscription_status');
            $table->string('company_document')->nullable()->after('company_name');
            $table->string('company_phone')->nullable()->after('company_document');
            $table->text('company_address')->nullable()->after('company_phone');
            $table->string('logo_path')->nullable()->after('company_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'subscription_status',
                'company_name',
                'company_document',
                'company_phone',
                'company_address',
                'logo_path',
            ]);
        });
    }
};
