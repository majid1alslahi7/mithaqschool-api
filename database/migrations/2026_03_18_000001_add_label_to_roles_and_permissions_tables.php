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
        if (! Schema::hasColumn('permissions', 'label')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('label')->nullable()->after('name');
            });
        }

        if (! Schema::hasColumn('roles', 'label')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('label')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('permissions', 'label')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('label');
            });
        }

        if (Schema::hasColumn('roles', 'label')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('label');
            });
        }
    }
};
