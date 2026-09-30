<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $search = trim((string) $request->query('q', ''));

        $query = Event::where('status', 'published')
            ->with(['ticketTypes', 'category'])
            ->latest();

        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->get();

        $categories = Category::where('is_active', true)
            ->withCount(['publishedEvents'])
            ->orderBy('sort_order')
            ->orderBy('name')
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

        return view('events.index', compact(
            'events',
            'siteSettings',
            'banners',
            'categories',
            'categorySlug',
            'search'
        ));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load([
            'ticketTypes' => fn ($q) => $q->where('is_active', true),
            'category',
        ]);

        return view('events.show', compact('event'));
    }
}
