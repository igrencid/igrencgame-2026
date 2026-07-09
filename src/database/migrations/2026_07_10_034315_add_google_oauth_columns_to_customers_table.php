<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('customers', 'google_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('google_id')->nullable()->unique()->after('email');
            });
        }

        if (! Schema::hasColumn('customers', 'email_verified_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }

        if (! Schema::hasColumn('customers', 'avatar_url')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('avatar_url')->nullable()->after('phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'google_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropUnique(['google_id']);
                $table->dropColumn('google_id');
            });
        }

        if (Schema::hasColumn('customers', 'email_verified_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }

        if (Schema::hasColumn('customers', 'avatar_url')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('avatar_url');
            });
        }
    }
};
