<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'products',
            'categories',
            'transactions',
            'cash_flows',
            'inventories',
            'payment_methods',
            'reports',
            'settings',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('store_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'products',
            'categories',
            'transactions',
            'cash_flows',
            'inventories',
            'payment_methods',
            'reports',
            'settings',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('store_id');
            });
        }
    }
};
