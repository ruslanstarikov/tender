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
        Schema::table('customers', function (Blueprint $table) {
            // Name fields
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            
            // Australian address fields
            $table->string('street_number')->nullable()->after('phone');
            $table->string('street_name')->nullable()->after('street_number');
            $table->string('street_type')->nullable()->after('street_name'); // e.g., Street, Road, Avenue
            $table->string('suburb')->nullable()->after('street_type');
            $table->string('state', 3)->nullable()->after('suburb'); // NSW, VIC, QLD, etc.
            $table->string('postcode', 4)->nullable()->after('state');
            $table->string('unit_number')->nullable()->after('postcode'); // for apartments/units
            
            // Authentication fields
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('remember_token')->nullable()->after('password');
            $table->boolean('is_active')->default(true)->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            
            // Additional customer fields
            $table->date('date_of_birth')->nullable()->after('last_login_at');
            $table->text('notes')->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'street_number',
                'street_name',
                'street_type',
                'suburb',
                'state',
                'postcode',
                'unit_number',
                'email_verified_at',
                'remember_token',
                'is_active',
                'last_login_at',
                'date_of_birth',
                'notes'
            ]);
        });
    }
};
