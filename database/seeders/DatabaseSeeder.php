<?php

namespace Database\Seeders;

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
        // Ini akan otomatis generate permission untuk semua Resource (Product, Transaction, dll)
        // dan membuat role Super Admin sesuai config.
        $this->call(ShieldSeeder::class);

        // --- SETUP ROLE CUSTOM ---

        // A. Setup Role Karyawan (Kasir)
        $roleEmployee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // Beri akses ke Transaksi, Produk, Customer, dll (Sesuaikan kebutuhan)
        // Kita ambil permission yang mengandung kata kunci tertentu
        $employeePermissions = Permission::query()
            ->where('name', 'like', '%transaction%')
            ->orWhere('name', 'like', '%product%')
            ->orWhere('name', 'like', '%category%')
            ->orWhere('name', 'page_PosPage')
            ->orWhere('name', '_Dashboard')
            ->get();

        $roleEmployee->syncPermissions($employeePermissions);


        // B. Setup Role Petugas Pajak (Hanya Cash Flow)
        $roleTaxOfficer = Role::firstOrCreate(['name' => 'tax_officer', 'guard_name' => 'web']);

        // Ambil permission spesifik sesuai format Shield (perhatikan double colon ::)
        $taxPermissions = Permission::whereIn('name', [
            '_Dashboard',
            'view_any_cash::flow',
            'view_cash::flow',
            'view_any_product',
            'view_product',
        ])->get();

        $roleTaxOfficer->syncPermissions($taxPermissions);


        // --- SETUP USER & ASSIGN ROLE ---

        // 1. Owner (Super Admin)
        $userOwner = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Owner Toko',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        // Assign role Super Admin (nama role default Shield biasanya 'super_admin')
        $userOwner->assignRole('super_admin');


        // 2. Karyawan 1
        $userKaryawan1 = User::updateOrCreate(
            ['email' => 'kasirpagi@gmail.com'],
            [
                'name' => 'Kasir Pagi',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userKaryawan1->assignRole($roleEmployee);


        // 3. Karyawan 2
        $userKaryawan2 = User::updateOrCreate(
            ['email' => 'kasirsore@gmail.com'],
            [
                'name' => 'Kasir Sore',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userKaryawan2->assignRole($roleEmployee);

        $userKaryawan2 = User::updateOrCreate(
            ['email' => 'ishlahhh@gmail.com'],
            [
                'name' => 'Syifa',
                'password' => Hash::make('password'),
                'status'=> "non-active",
            ]
        );
        $userKaryawan2->assignRole($roleEmployee);

        // 4. Petugas Pajak
        $userPajak = User::updateOrCreate(
            ['email' => 'pajak@gmail.com'],
            [
                'name' => 'Petugas Pajak',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $userPajak->assignRole($roleTaxOfficer);


        // --- SEED DATA LAINNYA ---
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PaymentMethodSeeder::class
        ]);
    }
}
