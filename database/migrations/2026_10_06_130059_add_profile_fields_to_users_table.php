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
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('license_number')->nullable()->after('country');
            $table->date('license_expiry')->nullable()->after('license_number');
            $table->string('avatar')->nullable()->after('license_expiry');
            $table->boolean('is_active')->default(true)->after('avatar');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'address', 'city', 'country',
                'license_number', 'license_expiry', 'avatar', 'is_active'
            ]);
        });
    }
};
