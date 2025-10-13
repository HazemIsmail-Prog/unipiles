<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO `companies` (`id`, `name_ar`, `name_en`, `created_at`, `updated_at`) VALUES
            (1, 'عبد المحسن الخرافي', 'Abdulmohsin AI-Kharafi', '2021-11-13 11:16:31', '2021-11-24 17:29:43'),
            (2, 'المستشارون العرب', 'Arab Consultants', '2021-11-13 11:16:31', '2021-11-24 17:29:58'),
            (3, NULL, 'Al Ghanim International', '2021-11-13 11:16:31', '2021-11-13 11:16:31'),
            (4, NULL, 'Al Ghanim & Jabbour', '2021-11-13 11:16:31', '2021-11-13 11:16:31'),
            (5, 'الامارات المتحدة للتجارة العامة والمقاولات', 'United Emirates General Trading & Contracting Co.', '2021-11-13 11:16:31', '2021-11-25 05:53:30'),
            (6, 'دار الكويت للهندسة', 'Kuwait Engineering Bureau', '2021-11-13 11:16:31', '2021-11-25 05:53:49'),
            (7, 'علي عبد الوهاب المطوع', 'Ali Abdul-Wahab Al-Mutawa Co.', '2021-11-13 11:16:31', '2021-11-25 05:54:12'),
            (8, 'يوني بايلز', 'Unipiles', '2021-11-13 11:16:31', '2021-11-25 05:54:26'),
            (9, 'كنار للتجارة العامة والمقاولات', 'Canar Trading & Contracting Co.', '2021-11-13 11:16:31', '2022-01-12 11:52:10'),
            (10, NULL, 'Al-Ahlia General Trading & Contracting Co.', '2021-11-13 11:16:31', '2021-11-13 11:16:31'),
            (11, NULL, 'Wara Construction Co.', '2021-11-13 11:16:31', '2021-11-13 11:16:31'),
            (12, 'المقاولون العرب الكويتية', 'Kuwait Arab Contractors', '2021-11-13 11:16:31', '2021-11-24 17:30:51'),
            (13, NULL, 'Al-Nasser & Al-Nashie Utd Co.', '2021-11-13 11:16:31', '2021-11-13 11:16:31'),
            (14, 'Salem M. Al-Nisf General Build', 'Salem M. Al-Nisf General Build', '2021-12-13 12:31:07', '2021-12-13 12:31:07'),
            (15, 'Al-Hassanain JGL Contracting of Roads, Sewers, and Bridges W.L.L.', 'Al-Hassanain JGL Contracting of Roads, Sewers, and Bridges W.L.L.', '2021-12-28 13:01:26', '2021-12-28 13:01:26'),
            (16, 'عبد الوهاب راشد احمد الهارون', 'Abdul Wahab Rashed Al Haroun', '2022-01-12 12:02:19', '2022-01-12 12:02:19'),
            (17, 'الدليل للتجارة العامة والمقاولات', 'Al-Daleel General Trading & Contracting Co.', '2022-03-09 20:13:06', '2022-03-09 20:13:06'),
            (18, 'First United General Trading & Contracting Co.', 'First United General Trading & Contracting Co.', '2022-04-06 18:44:03', '2022-04-06 18:44:03'),
            (19, 'Behbehani Motors Co.', 'Behbehani Motors Co.', '2022-04-25 18:42:10', '2022-04-25 18:42:10'),
            (20, 'الاحمدية', 'Ahmadiah Contracting & Trading Co.', '2022-08-09 09:20:55', '2022-08-09 09:20:55'),
            (21, 'PACE', 'PACE', '2022-12-14 11:24:54', '2022-12-14 11:24:54'),
            (22, 'Trevi Foundations Kuwait', 'Trevi Foundations Kuwait', '2023-02-01 12:01:05', '2023-02-01 12:01:05'),
            (23, 'Combined Group Contracting Co.', 'Combined Group Contracting Co.', '2023-02-10 21:18:06', '2023-02-10 21:18:06'),
            (24, 'حسين راشد حسين الغيص', 'حسين راشد حسين الغيص', '2023-07-01 16:39:05', '2023-07-01 16:39:05'),
            (25, 'سديم اعمار', 'Sadeem Imar JV', '2023-07-01 16:53:17', '2023-07-01 16:53:17'),
            (26, 'سيد حميد بهبهاني', 'Sayed Hamid Behbehani', '2023-09-17 12:36:42', '2023-09-17 12:36:42'),
            (27, 'فرست للتجارة العامة للمقاولات', 'First Group', '2023-10-14 13:12:33', '2024-03-11 16:03:34'),
            (28, 'Global Identity General Trading & Contracting Co.', 'Global Identity General Trading & Contracting Co.', '2024-06-01 06:19:19', '2024-06-01 06:19:19'),
            (29, 'Hydrotek Engineering Co.', 'Hydrotek Engineering Co.', '2024-10-17 05:48:41', '2024-10-17 05:48:41'),
            (30, 'انشاءات الخصوصية', 'Specialties Construction', '2025-02-10 07:07:23', '2025-02-10 07:09:49'),
            (31, 'مركز سلطان للتجارة والمقاولات العامة', 'Sultan Center General Trading & Contracting Co.', '2025-04-04 09:52:13', '2025-04-04 09:52:13'),
            (32, 'SEC Kuwait', 'SEC Kuwait', '2025-06-14 04:38:13', '2025-06-14 04:38:13'),
            (33, 'AVIC International', 'AVIC International', '2025-07-08 18:38:06', '2025-07-08 18:38:06'),
            (34, 'Dar Gulf Consult', 'Dar Gulf Consult', '2025-09-13 10:29:20', '2025-09-13 10:29:20')
        ");
    }
}
