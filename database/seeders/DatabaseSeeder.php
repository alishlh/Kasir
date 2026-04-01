<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan ShieldSeeder
        $this->call(ShieldSeeder::class);

        // --- SETUP ROLE CUSTOM ---

        // A. Setup Role Karyawan (Kasir)
        $roleEmployee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        $employeePermissions = Permission::query()
            ->where('name', 'like', '%transaction%')
            ->orWhere('name', 'like', '%product%')
            ->orWhere('name', 'like', '%category%')
            ->orWhere('name', '_PosPage')
            ->orWhere('name', '_Dashboard')
            ->get();

        $roleEmployee->syncPermissions($employeePermissions);


        // B. Setup Role Petugas Pajak (Hanya Cash Flow)
        $roleTaxOfficer = Role::firstOrCreate(['name' => 'tax_officer', 'guard_name' => 'web']);

        $taxPermissions = Permission::whereIn('name', [
            '_Dashboard',
            'view_any_cash::flow',
            'view_cash::flow',
            'view_any_product',
            'view_product',
        ])->get();

        $roleTaxOfficer->syncPermissions($taxPermissions);


        // --- SETUP STORES ---
        $storePusat = Store::create([
            'name' => 'Apotek Pusat',
            'slug' => 'apotek-pusat',
            'address' => 'Jl. Pahlawan No. 123, Kota Sejahtera, 14045',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $storeCabang = Store::create([
            'name' => 'Apotek Cabang 1',
            'slug' => 'apotek-cabang-1',
            'address' => 'Jl. Merdeka No. 45, Kota Makmur, 15067',
            'phone' => '089876543210',
            'is_active' => true,
        ]);


        // --- SETUP USER & ASSIGN ROLE ---

        // 1. Owner (Super Admin) - akses ke semua toko
        $userOwner = User::updateOrCreate(
            ['email' => 'owner@apotek.com'],
            [
                'name' => 'Owner Toko',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userOwner->assignRole('super_admin');
        $userOwner->stores()->sync([$storePusat->id, $storeCabang->id]);

        // 2. Karyawan 1 - Apotek Pusat
        $userKaryawan1 = User::updateOrCreate(
            ['email' => 'kasir.pusat1@apotek.com'],
            [
                'name' => 'Kasir Pusat 1',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userKaryawan1->assignRole($roleEmployee);
        $userKaryawan1->stores()->sync([$storePusat->id]);

        // 3. Karyawan 2 - Apotek Pusat
        $userKaryawan2 = User::updateOrCreate(
            ['email' => 'kasir.pusat2@apotek.com'],
            [
                'name' => 'Kasir Pusat 2',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => "non-active",
            ]
        );
        $userKaryawan2->assignRole($roleEmployee);
        $userKaryawan2->stores()->sync([$storePusat->id]);

        // 4. Karyawan 3 - Apotek Cabang 1
        $userSyifa = User::updateOrCreate(
            ['email' => 'kasir.cabang1@apotek.com'],
            [
                'name' => 'Kasir Cabang 1',
                'password' => Hash::make('password'),
                'status' => "active",
            ]
        );
        $userSyifa->assignRole($roleEmployee);
        $userSyifa->stores()->sync([$storeCabang->id]);

        // 5. Petugas Pajak - akses ke semua toko
        $userPajak = User::updateOrCreate(
            ['email' => 'pajak@apotek.com'],
            [
                'name' => 'Petugas Pajak',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userPajak->assignRole($roleTaxOfficer);
        $userPajak->stores()->sync([$storePusat->id, $storeCabang->id]);


        // --- SEED DATA LAINNYA (sekarang store-aware) ---
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PaymentMethodSeeder::class,
            TransactionSeeder::class
        ]);
    }
}
