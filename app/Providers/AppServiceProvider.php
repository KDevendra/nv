<?php

namespace App\Providers;

use App\Models\PropertyInquiry;
use App\Observers\PropertyInquiryObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Register model observers
        PropertyInquiry::observe(PropertyInquiryObserver::class);

        // @canDo('properties.create') ... @endCanDo
        Blade::directive('canDo', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})): ?>";
        });

        Blade::directive('endCanDo', function () {
            return '<?php endif; ?>';
        });
    }
}
