<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement("
                INSERT INTO `projects` (`id`, `name_ar`, `name_en`, `company_id`, `created_at`, `updated_at`) VALUES
                (1, 'قصر العدل', 'New Palace Of Justice', 1, '2021-11-13 11:16:31', '2021-11-24 17:22:15'),
                (2, 'برج العصفور', 'Al Asfour Tower', 2, '2021-11-13 11:16:32', '2021-11-24 17:22:57'),
                (3, 'الزور', 'Az-Zour', 3, '2021-11-13 11:16:32', '2021-11-24 17:22:00'),
                (4, 'مطار الكويت', 'Kuwait International Airport Cargo Tunnel', 4, '2021-11-13 11:16:32', '2021-11-24 17:23:21'),
                (5, 'برج الصوابر', 'New Sawaber Tower 5', 5, '2021-11-13 11:16:32', '2021-11-24 17:23:34'),
                (7, 'مجمع تجاري الضجيج', 'Construction of Commercial Plot No. 81 in Al-Dajeej', 6, '2021-11-13 11:16:32', '2021-11-24 17:25:50'),
                (8, 'مجمع تجاري الشويخ', 'AAW Commercial Complex in Shuwaikh', 7, '2021-11-13 11:16:32', '2021-11-24 17:26:43'),
                (9, 'تيليكس', 'Telex', 8, '2021-11-13 11:16:32', '2021-11-24 17:27:25'),
                (55, NULL, 'Egaila & Sabah Al-Ahmad Pump Station', 9, '2021-11-13 11:16:32', '2021-11-13 11:16:32'),
                (56, 'فيلا السرحان', 'Al-Sarhan Villa', 10, '2021-11-13 11:16:32', '2021-11-24 17:24:28'),
                (58, 'مجمع معرفي - بنيد القار', 'Marafie Complex in Bneid Al-Gar', 11, '2021-11-13 11:16:32', '2021-11-24 17:27:12'),
                (59, 'طريق النويصيب', 'RA/217 Construction of Nuwaiseeb Road', 12, '2021-11-13 11:16:32', '2021-11-24 17:28:08'),
                (60, 'ديوان الشيخ ناصر', 'Construction of Sheikh Nasser Diwan', 1, '2021-11-13 11:16:32', '2021-11-24 17:22:40'),
                (61, 'الدائري الأول', 'Construction, Execution, and Maintenance of First Ring Road', 1, '2021-11-13 11:16:32', '2021-11-24 17:25:20'),
                (62, NULL, 'Laying of Feed Pipelines for KOC to New Refinery', 3, '2021-11-13 11:16:32', '2021-11-13 11:16:32'),
                (63, 'فيلا خاصة - المسيلة', 'Private Villa - Messilah', 13, '2021-11-13 11:16:32', '2021-11-24 17:24:53'),
                (64, NULL, 'Construction of Pipelines & Trenches for Umm Al-Hayman WWTP', 9, '2021-11-13 11:16:32', '2021-11-13 11:16:32'),
                (65, 'برج الوزان', 'Al Wazzan Tower', 5, '2021-11-13 11:16:33', '2021-11-24 17:23:48'),
                (66, 'جامعة الخليج', 'Construction of New GUST Extension', 5, '2021-11-17 12:27:03', '2021-11-17 12:27:03'),
                (67, 'طريق الغوص', 'RA/265 Al-Ghouse Road', 12, '2021-11-18 07:06:27', '2021-11-24 17:27:49'),
                (68, 'برج الصقران الطبي', 'Al-Saqran Medical Tower', 5, '2021-11-22 13:10:58', '2021-11-24 17:24:07'),
                (69, 'مجمع تجاري بالجهراء', 'Commercial Plot in Jahra', 14, '2021-12-13 12:36:10', '2021-12-13 12:36:10'),
                (70, 'Umm Al-Hayman Wastewater', 'Umm Al-Hayman Wastewater', 15, '2021-12-28 13:02:06', '2021-12-28 13:02:06'),
                (71, 'قسيمة المنصورية قطعة (2)', 'Al Mansouriya Block (2)', 16, '2022-01-12 12:03:30', '2022-01-12 12:03:30'),
                (72, 'عقود عامة', 'General Contracts', 8, '2022-02-02 12:55:43', '2022-02-02 12:55:43'),
                (73, 'مستشفى شرق الجديدة', 'New Sharq Hospital', 5, '2022-02-08 11:06:58', '2022-02-08 11:06:58'),
                (75, 'New Storage Facility in Ardiya', 'New Storage Facility in Ardiya', 17, '2022-03-09 20:13:54', '2022-03-09 20:13:54'),
                (76, 'مصنع الهداية', 'Al-Hedaya Factory', 11, '2022-03-12 12:21:07', '2022-03-12 12:21:07'),
                (78, 'Residential Villa in Al-Salam', 'Residential Villa in Al-Salam', 18, '2022-04-06 18:44:38', '2022-04-06 18:44:38'),
                (79, 'Eng. Kevin', 'Eng. Kevin', 19, '2022-04-25 18:42:30', '2022-04-25 18:42:30'),
                (80, 'خزانات ام الهيمان', 'Reservoirs at Umm Al-Hayman Wastwater', 9, '2022-05-16 10:10:38', '2022-10-16 11:18:48'),
                (82, 'برج اليوسفي', 'Al-Yousifi Tower', 5, '2022-06-25 10:49:16', '2022-06-25 10:49:16'),
                (83, 'برج حصة المبارك', 'Hessa Al-Mubarak Discrict Tower', 20, '2022-08-09 09:22:16', '2022-08-09 09:22:16'),
                (84, 'Kuwait National School', 'Kuwait National School', 11, '2022-08-09 09:25:28', '2022-08-09 09:25:28'),
                (85, 'فواتير عامة', 'General Invoices', 8, '2022-11-05 11:05:06', '2022-11-05 11:05:06'),
                (86, 'PACE Studio Plot 164', 'PACE Studio Plot 164', 21, '2022-12-14 11:25:24', '2022-12-14 11:25:24'),
                (87, 'معرض هيونداي بالشويخ', 'Hyundai Showroom', 22, '2023-01-17 09:49:14', '2023-01-17 09:49:14'),
                (88, 'تامين', 'Insurance', 8, '2023-01-21 11:46:27', '2023-01-21 11:46:27'),
                (89, 'Al-Hamad Commercial Center in Sharq', 'Al-Hamad Commercial Center in Sharq', 23, '2023-02-10 21:18:38', '2023-02-10 21:18:38'),
                (90, 'Kuwait Cancer Center', 'Kuwait Cancer Center', 3, '2023-03-11 10:18:49', '2023-03-11 10:20:14'),
                (91, 'برج عفره', 'Afra Tower', 24, '2023-07-01 16:39:37', '2023-07-01 16:39:37'),
                (92, 'Institute of Banking Studies', 'Institute of Banking Studies', 25, '2023-07-01 16:53:54', '2023-07-01 16:53:54'),
                (93, 'هيئة اسواق المال', 'هيئة اسواق المال', 26, '2023-09-17 12:37:24', '2023-09-17 12:37:24'),
                (94, 'General Letters', 'General Letters', 8, '2023-10-01 12:47:11', '2023-10-01 12:47:11'),
                (95, 'خزانات صباح الاحمد', 'خزانات صباح الاحمد', 27, '2023-10-14 13:12:56', '2023-10-14 13:12:56'),
                (96, 'اكاديمية الشيخ علي الصباح العسكرية', 'Sheikh Ali Al-Sabah Military Academy', 1, '2023-11-06 19:01:41', '2023-11-06 19:01:41'),
                (97, 'General', 'General', 9, '2024-04-25 03:08:17', '2024-04-25 03:08:33'),
                (98, 'Water Transfer', 'Water Transfer', 9, '2024-04-25 03:10:58', '2024-04-25 03:10:58'),
                (99, 'Deep Wels', 'Deep Wels', 27, '2024-06-01 06:00:34', '2024-06-01 06:00:34'),
                (100, 'Abdullah Al-Ghanim Residential Villa in Yarmouk', 'Abdullah Al-Ghanim Residential Villa in Yarmouk', 28, '2024-06-01 06:20:16', '2024-06-01 06:20:16'),
                (101, 'Sara Plaza', 'Sara Plaza', 5, '2024-07-02 04:02:59', '2024-07-02 04:02:59'),
                (102, 'Child Genetic Diseases Center', 'Child Genetic Diseases Center', 11, '2024-07-02 04:07:26', '2024-07-02 04:07:26'),
                (103, 'Al-Khalijia Tower', 'Al-Khalijia Tower', 27, '2024-10-05 08:38:03', '2024-10-05 08:38:03'),
                (104, 'J3 Mall', 'J3 Mall', 29, '2024-10-17 05:49:04', '2024-10-17 05:49:04'),
                (105, 'Ashour Tower', 'Ashour Tower', 5, '2024-10-24 09:11:26', '2024-10-24 09:11:26'),
                (106, 'مجمع البوم', 'Al Boom Complex', 30, '2025-02-10 07:10:32', '2025-02-10 07:10:32'),
                (107, 'مركز سلطان ضمن نادي السالمية الرياضي', 'Sultan Center in Salmiya Sports Club', 31, '2025-04-04 09:53:16', '2025-04-04 09:53:16'),
                (108, 'Sabiya CCGT-2 Expansion', 'Sabiya CCGT-2 Expansion', 3, '2025-05-17 12:00:54', '2025-05-17 12:00:54'),
                (109, 'Al-Najeeb Villas in Messilah', 'Al-Najeeb Villas in Messilah', 32, '2025-06-14 04:38:53', '2025-06-14 04:38:53'),
                (110, 'South Sabah Al-Ahmed New City', 'South Sabah Al-Ahmed New City', 33, '2025-07-08 18:38:52', '2025-07-08 18:38:52'),
                (111, 'The 3S Industrial Complex KIA, Changan in Ahmadi', 'The 3S Industrial Complex KIA, Changan in Ahmadi', 34, '2025-09-13 10:30:19', '2025-09-13 10:30:19'),
                (112, 'J3 Mall - Al Ghanim International', 'J3 Mall - Al Ghanim International', 3, '2025-09-13 10:41:31', '2025-09-13 10:41:31');

        ");
    }
}
