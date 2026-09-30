<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CustomerDashboardSettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'hero_badge' => SiteSetting::get('hero_badge', 'Platform tiket event Indonesia'),
            'hero_title' => SiteSetting::get('hero_title', "Temukan event favoritmu,\namankan tiketnya"),
            'hero_description' => SiteSetting::get('hero_description', 'Konser, workshop, meetup, dan banyak lagi. Pesan dalam hitungan menit — pembayaran aman via Midtrans.'),
            'hero_cta_text' => SiteSetting::get('hero_cta_text', 'Lihat Event'),
            'promo_banner_active' => SiteSetting::get('promo_banner_active', '0'),
            'promo_banner_text' => SiteSetting::get('promo_banner_text', 'Gunakan kode promo PROMOEVENT untuk diskon spesial!'),
            'support_whatsapp' => SiteSetting::get('support_whatsapp', ''),
        ];

        return view('admin.settings.customer-dashboard', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_badge' => 'required|string|max:100',
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string|max:500',
            'hero_cta_text' => 'required|string|max:50',
            'promo_banner_text' => 'nullable|string|max:255',
            'support_whatsapp' => 'nullable|string|max:20',
        ]);

        SiteSetting::set('hero_badge', $data['hero_badge']);
        SiteSetting::set('hero_title', $data['hero_title']);
        SiteSetting::set('hero_description', $data['hero_description']);
        SiteSetting::set('hero_cta_text', $data['hero_cta_text']);
        SiteSetting::set('promo_banner_active', $request->has('promo_banner_active') ? '1' : '0');
        SiteSetting::set('promo_banner_text', $data['promo_banner_text'] ?? '');
        SiteSetting::set('support_whatsapp', $data['support_whatsapp'] ?? '');

        return back()->with('status', 'Tampilan dashboard customer berhasil diperbarui!');
    }
}