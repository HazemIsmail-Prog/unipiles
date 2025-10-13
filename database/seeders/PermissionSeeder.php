<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // wanna create permession for actions [viewAll, create, update, delete] for each model

        $models = [
            'User',
            'Role',
            'Permission',
            'Title',
            'Company',
            'Project',
            'Document',
            'Quotation',
            'Employee',
            'AssetType',
            'Asset',
            'Document_Attachments',
            'Quotation_Attachments',
            'Employee_Attachments',
            'Asset_Attachments',
        ];

        $actions = [
            'view_all' => [
                'en' => 'View all :model',
                'ar' => 'عرض جميع :model',
            ],
            'create' => [
                'en' => 'Create :model',
                'ar' => 'إنشاء :model',
            ],
            'update' => [
                'en' => 'Update :model',
                'ar' => 'تعديل :model',
            ],
            'delete' => [
                'en' => 'Delete :model',
                'ar' => 'حذف :model',
            ],
        ];

        // Simple model name mappings for display in EN/AR
        $modelNames = [
            'User' => [
                'en' => 'User',
                'ar' => 'مستخدم',
            ],
            'Role' => [
                'en' => 'Role',
                'ar' => 'دور',
            ],
            'Permission' => [
                'en' => 'Permission',
                'ar' => 'إذن',
            ],
            'Title' => [
                'en' => 'Title',
                'ar' => 'مسمى وظيفي',
            ],
            'Company' => [
                'en' => 'Company',
                'ar' => 'شركة',
            ],
            'Project' => [
                'en' => 'Project',
                'ar' => 'مشروع',
            ],
            'Document' => [
                'en' => 'Document',
                'ar' => 'مستند',
            ],
            'Quotation' => [
                'en' => 'Quotation',
                'ar' => 'عرض سعر',
            ],
            'Employee' => [
                'en' => 'Employee',
                'ar' => 'موظف',
            ],
            'AssetType' => [
                'en' => 'Asset Type',
                'ar' => 'نوع أصل',
            ],
            'Asset' => [
                'en' => 'Asset',
                'ar' => 'أصل',
            ],
            'Document_Attachments' => [
                'en' => 'Document Attachments',
                'ar' => 'مرفقات المستندات',
            ],
            'Quotation_Attachments' => [
                'en' => 'Quotation Attachments',
                'ar' => 'مرفقات عروض الأسعار',
            ],
            'Employee_Attachments' => [
                'en' => 'Employee Attachments',
                'ar' => 'مرفقات الموظفين',
            ],
            'Asset_Attachments' => [
                'en' => 'Asset Attachments',
                'ar' => 'مرفقات الأصول',
            ],
        ];


        $permissions = [];

        foreach ($models as $model) {
            foreach ($actions as $action => $translations) {
                $permissions[] = [
                    'name' => strtolower($action) . '_' . strtolower($model),
                    'description_en' => str_replace(':model', $modelNames[$model]['en'], $translations['en']),
                    'description_ar' => str_replace(':model', $modelNames[$model]['ar'], $translations['ar']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        Permission::insert($permissions);
    }
}
