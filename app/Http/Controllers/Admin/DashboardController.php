<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pengaturan default jika admin belum pernah menyimpannya
        $defaultSettings = [
            'show_events' => true,
            'show_orders' => true,
            'show_revenue' => true,
            'show_tickets_sold' => true,
            'show_tickets_used' => true,
            'show_recent_orders' => true,
            'orders_limit' => 8,
            'revenue_target' => 50000000,
            'announcement' => 'Selamat datang di panel admin EventTicket! Pantau terus performa penjualan tiket Anda.',
            'show_announcement' => true,
        ];

        $settings = array_merge($defaultSettings, $user->dashboard_settings ?? []);

        $stats = [
            'events' => Event::count(),
            'published' => Event::where('status', 'published')->count(),
            'orders' => Order::where('status', 'paid')->count(),
            'revenue' => Order::where('status', 'paid')->sum('total_price'),
            'tickets_sold' => Ticket::count(),
            'tickets_used' => Ticket::where('is_used', true)->count(),
        ];

        // Hitung progres target omzet
        $targetRevenue = (float) ($settings['revenue_target'] ?? 0);
        $revenueProgress = $targetRevenue > 0 ? min(100, round(($stats['revenue'] / $targetRevenue) * 100, 1)) : 0;

        $limit = (int) ($settings['orders_limit'] ?? 8);
        $latestOrders = Order::with('user')
            ->latest()
            ->limit($limit)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'settings', 'revenueProgress'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'show_events' => 'nullable|boolean',
            'show_orders' => 'nullable|boolean',
            'show_revenue' => 'nullable|boolean',
            'show_tickets_sold' => 'nullable|boolean',
            'show_tickets_used' => 'nullable|boolean',
            'show_recent_orders' => 'nullable|boolean',
            'show_announcement' => 'nullable|boolean',
            'orders_limit' => 'required|integer|in:5,8,15',
            'revenue_target' => 'nullable|numeric|min:0',
            'announcement' => 'nullable|string|max:500',
        ]);

        $settings = [
            'show_events' => $request->has('show_events'),
            'show_orders' => $request->has('show_orders'),
            'show_revenue' => $request->has('show_revenue'),
            'show_tickets_sold' => $request->has('show_tickets_sold'),
            'show_tickets_used' => $request->has('show_tickets_used'),
            'show_recent_orders' => $request->has('show_recent_orders'),
            'show_announcement' => $request->has('show_announcement'),
            'orders_limit' => (int) $data['orders_limit'],
            'revenue_target' => (float) ($data['revenue_target'] ?? 0),
            'announcement' => $data['announcement'] ?? '',
        ];

        auth()->user()->update(['dashboard_settings' => $settings]);

        return back()->with('status', 'Pengaturan dashboard berhasil diperbarui!');
    }
}
