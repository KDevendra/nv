<?php

namespace App\Providers;

use App\Models\SeoMeta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            // Skip if seoMeta is already set by the controller
            if ($view->offsetExists('seoMeta')) {
                return;
            }

            $seoMeta = $this->resolveSeoMeta();
            $view->with('seoMeta', $seoMeta);
        });
    }

    /**
     * Resolve SEO meta by trying multiple strategies:
     * 1. Match by route name (static pages)
     * 2. Match by URL path (dynamic pages like blog/slug, properties/slug)
     */
    private function resolveSeoMeta(): ?SeoMeta
    {
        // Strategy 1: Try route name mapping for static pages
        $pageKey = $this->resolveByRouteName();
        if ($pageKey) {
            $seo = SeoMeta::getForPage($pageKey);
            if ($seo) {
                return $seo;
            }
        }

        // Strategy 2: Try URL path as page_key (for dynamic pages)
        $path = trim(request()->path(), '/');
        if ($path) {
            $seo = SeoMeta::getForPage($path);
            if ($seo) {
                return $seo;
            }
        }

        return null;
    }

    /**
     * Resolve page key based on the route name.
     */
    private function resolveByRouteName(): ?string
    {
        $routeName = request()->route()?->getName();

        if (!$routeName) {
            return null;
        }

        $routeMap = [
            'home' => 'home',
            'about' => 'about',
            'contact' => 'contact',
            'properties.index' => 'properties',
            'blogs.index' => 'blogs',
            'privacy-policy' => 'privacy-policy',
            'terms-and-conditions' => 'terms-and-conditions',
            'calculators.acre-to-bigha' => 'calculators.acre-to-bigha',
            'calculators.acre-to-hectare' => 'calculators.acre-to-hectare',
            'calculators.emi-calculator' => 'calculators.emi-calculator',
            'calculators.length-calculator' => 'calculators.length-calculator',
            'calculators.acre-to-squaremeter' => 'calculators.acre-to-squaremeter',
            'calculators.cent-to-square-feet' => 'calculators.cent-to-square-feet',
            'calculators.cent-to-square-meter' => 'calculators.cent-to-square-meter',
            'calculators.cm-to-mm' => 'calculators.cm-to-mm',
            'calculators.cm-to-inches' => 'calculators.cm-to-inches',
            'calculators.ft-to-cm' => 'calculators.ft-to-cm',
            'calculators.ft-to-inches' => 'calculators.ft-to-inches',
            'calculators.ft-to-mm' => 'calculators.ft-to-mm',
        ];

        return $routeMap[$routeName] ?? null;
    }
}
