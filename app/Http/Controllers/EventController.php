<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SiteSetting;
use App\Models\Banner;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'published')
            ->with('ticketTypes')
            ->latest()
            ->get();

         $banners = Banner::where('is_active', true)->latest()->get();

        $siteSettings = [
            'hero_badge' => SiteSetting::get('hero_badge', 'Platform tiket event Indonesia'),
            'hero_title' => SiteSetting::get('hero_title', "Temukan event favoritmu,\namankan tiketnya"),
            'hero_description' => SiteSetting::get('hero_description', 'Konser, workshop, meetup, dan banyak lagi. Pesan dalam hitungan menit — pembayaran aman via Midtrans.'),
            'hero_cta_text' => SiteSetting::get('hero_cta_text', 'Lihat Event'),
            'promo_banner_active' => SiteSetting::get('promo_banner_active', '0'),
            'promo_banner_text' => SiteSetting::get('promo_banner_text', ''),
            'support_whatsapp' => SiteSetting::get('support_whatsapp', ''),
        ];

        return view('events.index', compact('events', 'siteSettings', 'banners'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load(['ticketTypes' => fn ($q) => $q->where('is_active', true)]);

        return view('events.show', compact('event'));
    }
}
