<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Columns being indexed here currently have no index besides the
     * primary key, so every WHERE/JOIN on them (job_order_id lookups
     * across 7k-22k row tables, status filters on AJAX datatables,
     * job_orders.date/plate_number lookups) is a full table scan.
     */
    private array $indexes = [
        'job_orders' => [
            'job_orders_status_index' => ['status'],
            'job_orders_date_index' => ['date'],
            'job_orders_plate_number_index' => ['plate_number'],
            'job_orders_car_id_index' => ['car_id'],
        ],
        'job_orders_packages' => [
            'job_orders_packages_job_order_id_index' => ['job_order_id'],
            'job_orders_packages_package_id_index' => ['package_id'],
        ],
        'job_orders_package_manual_items' => [
            'job_orders_package_manual_items_job_order_id_index' => ['job_order_id'],
        ],
        'job_orders_labors' => [
            'job_orders_labors_job_order_id_index' => ['job_order_id'],
        ],
        'job_orders_part_services' => [
            'job_orders_part_services_job_order_id_index' => ['job_order_id'],
            'job_orders_part_services_part_id_index' => ['part_id'],
        ],
        'job_orders_part_service_options' => [
            'job_orders_part_service_options_status_index' => ['status'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $tableName => $tableIndexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $tableIndexes) {
                foreach ($tableIndexes as $indexName => $columns) {
                    if (! $this->indexExists($tableName, $indexName)) {
                        $table->index($columns, $indexName);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $tableName => $tableIndexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $tableIndexes) {
                foreach (array_keys($tableIndexes) as $indexName) {
                    if ($this->indexExists($tableName, $indexName)) {
                        $table->dropIndex($indexName);
                    }
                }
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $result = DB::select(
            'SHOW INDEX FROM ' . $table . ' WHERE Key_name = ?',
            [$indexName]
        );

        return count($result) > 0;
    }
};
