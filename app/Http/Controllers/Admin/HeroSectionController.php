<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HeroSectionController extends Controller
{
    public function index(): View
    {
        $heroSections = HeroSection::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.hero-sections.index', compact('heroSections'));
    }

    public function create(): View
    {
        return view('admin.hero-sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'highlight_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_path' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle video upload to public folder
        if ($request->hasFile('video_path')) {
            $file = $request->file('video_path');
            $filename = 'hero-video-' . time() . '.' . $file->getClientOriginalExtension();
            $dir = public_path('uploads/hero/videos');
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $file->move($dir, $filename);
            $validated['video_path'] = 'uploads/hero/videos/' . $filename;
        }

        // Handle poster image upload
        if ($request->hasFile('poster_image')) {
            $validated['poster_image'] = ImageHelper::storeWebp($request->file('poster_image'), $validated['title'], 0, 'poster', 'hero/posters');
        }

        HeroSection::create($validated);

        return redirect()->route('admin.hero-sections.index')
            ->with('success', 'Hero Section created successfully.');
    }

    public function show(HeroSection $heroSection): View
    {
        return view('admin.hero-sections.show', compact('heroSection'));
    }

    public function edit(HeroSection $heroSection): View
    {
        return view('admin.hero-sections.edit', compact('heroSection'));
    }

    public function update(Request $request, HeroSection $heroSection): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'highlight_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_path' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_video' => 'nullable|boolean',
            'remove_poster' => 'nullable|boolean',
            'status' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle video removal
        if ($request->remove_video && $heroSection->video_path) {
            $oldPath = public_path($heroSection->video_path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $validated['video_path'] = null;
        }

        // Handle new video upload
        if ($request->hasFile('video_path')) {
            // Delete old video
            if ($heroSection->video_path) {
                $oldPath = public_path($heroSection->video_path);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $file = $request->file('video_path');
            $filename = 'hero-video-' . $heroSection->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $dir = public_path('uploads/hero/videos');
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $file->move($dir, $filename);
            $validated['video_path'] = 'uploads/hero/videos/' . $filename;
        }

        // Handle poster removal
        if ($request->remove_poster && $heroSection->poster_image) {
            $oldPath = public_path($heroSection->poster_image);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $validated['poster_image'] = null;
        }

        // Handle new poster upload
        if ($request->hasFile('poster_image')) {
            if ($heroSection->poster_image) {
                $oldPath = public_path($heroSection->poster_image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $validated['poster_image'] = ImageHelper::storeWebp($request->file('poster_image'), $validated['title'], $heroSection->id, 'poster', 'hero/posters');
        }

        $heroSection->update($validated);

        return redirect()->route('admin.hero-sections.index')
            ->with('success', 'Hero Section updated successfully.');
    }

    public function destroy(HeroSection $heroSection): RedirectResponse
    {
        if ($heroSection->video_path) {
            $path = public_path($heroSection->video_path);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        if ($heroSection->poster_image) {
            $path = public_path($heroSection->poster_image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $heroSection->delete();

        return redirect()->route('admin.hero-sections.index')
            ->with('success', 'Hero Section deleted successfully.');
    }

    public function toggleStatus(HeroSection $heroSection): RedirectResponse
    {
        $heroSection->update(['status' => !$heroSection->status]);

        $status = $heroSection->status ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Hero Section {$status} successfully.");
    }
}
