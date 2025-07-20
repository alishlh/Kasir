<?php

namespace App\Services;

class InventoryLabelService
{
    // Jenis inventory
    public const TYPES = [
        'in' => 'Masuk',
        'out' => 'Keluar',
        'adjustment' => 'Penyesuaian',
    ];

    // Daftar source berdasarkan type
    public const SOURCES = [
        'in' => [
            'purchase_stock' => 'Penambahan Stock',
        ],
        'out' => [
            'damaged' => 'Barang Rusak',
            'expired' => 'Kedaluwarsa',
        ],
        'adjustment' => [
            'stock_opname' => 'Stok Opname',
        ],
    ];

    /**
     * Ambil semua tipe
     */
    public static function getTypes(): array
    {
        return self::TYPES;
    }

    /**
     * Ambil label dari tipe
     */
    public static function getTypeLabel(string $type): ?string
    {
        return self::TYPES[$type] ?? null;
    }

    /**
     * Ambil semua source berdasarkan type
     */
    public static function getSources(?string $type = null): array
    {
        return $type && isset(self::SOURCES[$type])
            ? self::SOURCES[$type]
            : collect(self::SOURCES)->collapse()->toArray();
    }

    /**
     * Ambil label source berdasarkan type dan key source
     */
    public static function getSourceLabel(?string $type, string $source): ?string
    {
        return self::SOURCES[$type][$source] ?? null;
    }

    
    /**
     * Ambil option dari source berdasarkan type
     */
    public static function getSourceOptionsByType(?string $type): array
    {
        return self::getSources($type) ?? [];
    }
}
