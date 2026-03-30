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
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('diskon')->default(0)->after('total')->comment('Diskon untuk transaksi')->nullable();
            $table->integer('subtotal')->default(0)->after('diskon')->comment('Subtotal untuk transaksi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('diskon');
            $table->dropColumn('subtotal');
        });
    }
};
