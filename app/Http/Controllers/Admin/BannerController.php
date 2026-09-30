<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:150',
            'link' => 'nullable|url|max:255',
            'image_source' => 'required|in:file,url',
            'banner_file' => 'required_if:image_source,file|nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'banner_url' => 'required_if:image_source,url|nullable|url|max:500',
        ]);

        $imagePath = null;

        if ($request->image_source === 'file' && $request->hasFile('banner_file')) {
            $imagePath = $request->file('banner_file')->store('banners', 'public');
        } elseif ($request->image_source === 'url' && $request->filled('banner_url')) {
            $imagePath = $request->banner_url;
        }

        if (!$imagePath) {
            return back()->with('error', 'Silakan pilih gambar dari file atau masukkan URL gambar.');
        }

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'is_active' => true,
        ]);

        return back()->with('success', 'Slide banner berhasil ditambahkan!');
    }

    public function destroy(Banner $banner)
    {
        // Hapus file fisik jika tersimpan di disk lokal
        if (!Str::startsWith($banner->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return back()->with('success', 'Slide banner berhasil dihapus.');
    }
}