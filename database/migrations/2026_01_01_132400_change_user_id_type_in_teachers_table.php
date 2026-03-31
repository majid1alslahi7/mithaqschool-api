<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Make the column unsigned and nullable
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            // Change the user_id column type back to string
            $table->string('user_id', 100)->nullable()->change();
        });
    }
};
//قم بادراج 2 صفوف على الاقل لكل جدول سييدر بحسب محتويات كل جدول