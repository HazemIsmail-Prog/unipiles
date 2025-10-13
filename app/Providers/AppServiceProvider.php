<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Document;
use App\Models\Quotation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::preventLazyLoading();
        Relation::enforceMorphMap([
            'employee' => Employee::class,
            'asset' => Asset::class,
            'document' => Document::class,
            'quotation' => Quotation::class,
        ]);
    }
}
