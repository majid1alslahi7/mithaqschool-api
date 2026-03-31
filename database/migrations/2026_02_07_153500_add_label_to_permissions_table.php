<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames) || empty($tableNames['permissions'])) {
            throw new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        $tableName = $tableNames['permissions'];

        Schema::table($tableName, static function (Blueprint $table) use ($tableName) {
            if (!Schema::hasColumn($tableName, 'label')) {
                $table->string('label')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames) || empty($tableNames['permissions'])) {
            throw new Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        $tableName = $tableNames['permissions'];

        Schema::table($tableName, static function (Blueprint $table) use ($tableName) {
            if (Schema::hasColumn($tableName, 'label')) {
                $table->dropColumn('label');
            }
        });
    }
};
