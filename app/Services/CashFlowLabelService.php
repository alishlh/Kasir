<?php

namespace App\Services;

class CashFlowLabelService
{
    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';

    public static function getTypes(): array
    {
        return [
            self::TYPE_INCOME => 'Masuk',
            self::TYPE_EXPENSE => 'Keluar',
        ];
    }

    public static function getSources(): array
    {
        return [
            self::TYPE_INCOME => [
                'sales' => 'Penjualan',
                'restored_sales' => 'Restore transaksi',
                'customer_debt_payment' => 'Pembayaran Piutang',
                'initial_capital' => 'Modal Awal',
                'additional_capital' => 'Modal Tambahan',
                'other_income' => 'Pemasukan Lain-lain',
            ],
            self::TYPE_EXPENSE => [
                'purchase_stock' => 'Penambahan Stok Barang',
                'employee_salary' => 'Gaji Karyawan',
                'operational_cost' => 'Biaya Operasional',
                'withdrawal' => 'Penarikan oleh Owner',
                'equipment_maintenance' => 'Perawatan Alat',
                'refund' => 'Return Pelanggan',
                'electricity' => 'Listrik',
                'rent' => 'Sewa',
                'other_expense' => 'Pengeluaran Lain-lain',
            ],
        ];
    }

    public static function getTypeLabel(?string $type): ?string
    {
        return self::getTypes()[$type] ?? null;
    }

    public static function getSourceLabel(?string $type, ?string $source): ?string
    {
        return ($type && $source && isset(self::getSources()[$type][$source]))
            ? self::getSources()[$type][$source]
            : null;
    }

    public static function getSourceOptionsByType(?string $type): array
    {
        return self::getSources()[$type] ?? [];
    }
}
