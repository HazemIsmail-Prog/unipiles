<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');


Route::middleware(['auth', 'language'])->group(function () {
    

    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::post('users', 'store');
        Route::put('users/{user}', 'update');
        Route::get('users/locale', 'updateLocale')->name('users.updateLocale');
        Route::delete('users/{user}', 'destroy');
        Route::post('users/upload', 'upload');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('roles.index');
        Route::post('roles', 'store');
        Route::put('roles/{role}', 'update');
        Route::delete('roles/{role}', 'destroy');
    });

    Route::controller(PermissionController::class)->group(function () {
        Route::get('permissions', 'index')->name('permissions.index');
        Route::post('permissions', 'store');
        Route::put('permissions/{permission}', 'update');
        Route::delete('permissions/{permission}', 'destroy');
    });

    Route::controller(TitleController::class)->group(function () {
        Route::get('titles', 'index')->name('titles.index');
        Route::post('titles', 'store');
        Route::put('titles/{title}', 'update');
        Route::delete('titles/{title}', 'destroy');
    });

    Route::controller(CompanyController::class)->group(function () {
        Route::get('companies', 'index')->name('companies.index');
        Route::post('companies', 'store');
        Route::put('companies/{company}', 'update');
        Route::delete('companies/{company}', 'destroy');
    });

    Route::controller(ProjectController::class)->group(function () {
        Route::get('projects', 'index')->name('projects.index');
        Route::post('projects', 'store');
        Route::put('projects/{project}', 'update');
        Route::delete('projects/{project}', 'destroy');
    });

    Route::controller(DocumentController::class)->group(function () {
        Route::get('documents', 'index')->name('documents.index');
        Route::post('documents', 'store');
        Route::put('documents/{document}', 'update');
        Route::delete('documents/{document}', 'destroy');
    });

    Route::controller(QuotationController::class)->group(function () {
        Route::get('quotations', 'index')->name('quotations.index');
        Route::post('quotations', 'store');
        Route::put('quotations/{quotation}', 'update');
        Route::delete('quotations/{quotation}', 'destroy');
    });

    // assets
    Route::controller(AssetController::class)->group(function () {
        Route::get('assets', 'index')->name('assets.index');
        Route::post('assets', 'store');
        Route::put('assets/{asset}', 'update');
        Route::delete('assets/{asset}', 'destroy');
    });
    
    // asset types
    Route::controller(AssetTypeController::class)->group(function () {
        Route::get('asset-types', 'index')->name('asset-types.index');
        Route::post('asset-types', 'store');
        Route::put('asset-types/{assetType}', 'update');
        Route::delete('asset-types/{assetType}', 'destroy');
    });


    // employees
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('employees', 'index')->name('employees.index');
        Route::post('employees', 'store');
        Route::put('employees/{employee}', 'update');
        Route::delete('employees/{employee}', 'destroy');
    });

    // attachments
    Route::controller(AttachmentController::class)->group(function () {
        Route::get('update-attachments-path', 'updatePath');
        Route::get('attachments', 'index')->name('attachments.index');
        Route::post('attachments', 'store');
        Route::put('attachments/{attachment}', 'update');
        Route::delete('attachments/{attachment}', 'destroy');
        Route::get('attachments/{encrypted_id}', 'view')->name('attachments.view');
    });

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
