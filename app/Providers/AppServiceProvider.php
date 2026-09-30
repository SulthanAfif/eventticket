<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share pengaturan site ke layout customer (promo bar + WhatsApp)
        View::composer('layouts.app', function ($view) {
            try {
                $view->with('globalSiteSettings', [
                    'promo_banner_active' => SiteSetting::get('promo_banner_active', '0'),
                    'promo_banner_text' => SiteSetting::get('promo_banner_text', ''),
                    'support_whatsapp' => SiteSetting::get('support_whatsapp', ''),
                ]);
            } catch (\Throwable $e) {
                $view->with('globalSiteSettings', [
                    'promo_banner_active' => '0',
                    'promo_banner_text' => '',
                    'support_whatsapp' => '',
                ]);
            }
        });
    }
}
