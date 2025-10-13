<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement("
            INSERT INTO `asset_types` (`id`, `name_ar`, `name_en`, `created_at`, `updated_at`) VALUES
            (1, 'Geho Pumps', 'Geho Pumps', '2020-07-21 06:44:52', '2020-08-12 12:35:03'),
            (2, 'Generators', 'Generators', '2020-07-21 06:45:15', '2020-07-21 06:45:15'),
            (3, 'Cement Grouting Machine', 'Cement Grouting Machine', '2020-07-21 06:45:41', '2020-07-21 06:45:41'),
            (4, 'Machines', 'Machines', '2020-07-22 11:56:15', '2020-07-22 11:56:25'),
            (5, 'Oasis Pumps', 'Oasis Pumps', '2020-08-12 12:35:18', '2020-08-12 12:35:18'),
            (7, 'Vairsco Pumps', 'Vairsco Pumps', '2020-08-12 12:36:25', '2020-08-12 12:36:25'),
            (8, 'Operators', 'Operators', '2020-08-12 12:41:41', '2020-08-12 12:41:41'),
            (10, 'Vehicles', 'Vehicles', '2020-08-12 12:42:21', '2020-08-12 12:42:21'),
            (11, 'اجهزة مساحة', 'اجهزة مساحة', '2020-10-27 10:25:20', '2020-10-27 10:25:20'),
            (12, 'Pressure Gauge', 'Pressure Gauge', '2020-10-27 10:36:40', '2020-10-27 10:36:40'),
            (13, 'Deisel Tank', 'Deisel Tank', '2025-05-20 22:53:31', '2025-05-20 22:53:31');
        ");
    }
}
