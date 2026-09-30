<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['category', 'ticketTypes'])
            ->withCount('ticketTypes')
            ->latest()
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'status' => 'required|in:draft,published,finished,cancelled',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event dibuat.');
    }

    public function edit(Event $event)
    {
        $event->load('ticketTypes');
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'status' => 'required|in:draft,published,finished,cancelled',
            'ticket_bg_type' => 'nullable|in:gradient,image',
            'ticket_bg_color' => 'nullable|string|max:50',
            'ticket_bg_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'ticket_bg_url' => 'nullable|url|max:500',
        ]);

        $data['ticket_bg_type'] = $request->input('ticket_bg_type', 'gradient');
        $data['ticket_bg_color'] = $request->input('ticket_bg_color', 'indigo');

        if ($request->hasFile('ticket_bg_file')) {
            if ($event->ticket_bg_image && !str_starts_with($event->ticket_bg_image, 'http')) {
                Storage::disk('public')->delete($event->ticket_bg_image);
            }
            $data['ticket_bg_image'] = $request->file('ticket_bg_file')->store('ticket_backgrounds', 'public');
        } elseif ($request->filled('ticket_bg_url')) {
            $data['ticket_bg_image'] = $request->ticket_bg_url;
        }

        $event->update($data);

        return back()->with('success', 'Event dan background tiket berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event dihapus.');
    }

    public function storeTicketType(Request $request, Event $event)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        $event->ticketTypes()->create([
            ...$data,
            'is_active' => true,
        ]);

        return back()->with('success', 'Tipe tiket ditambahkan.');
    }

    public function destroyTicketType(Event $event, TicketType $ticketType)
    {
        if ($ticketType->event_id !== $event->id) {
            abort(404);
        }

        if ($ticketType->sold > 0) {
            return back()->with('error', 'Tidak bisa hapus: sudah ada yang terjual.');
        }

        $ticketType->delete();

        return back()->with('success', 'Tipe tiket dihapus.');
    }
}
