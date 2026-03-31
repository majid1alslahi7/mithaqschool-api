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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('period');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('period', 20)->default('الأولى')->after('day_of_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('period');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedTinyInteger('period')->default(1)->after('day_of_week');
        });
    }
};
