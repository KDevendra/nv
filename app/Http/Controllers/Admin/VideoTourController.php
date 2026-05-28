<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\VideoTourSection;
use Illuminate\Http\Request;

class VideoTourController extends Controller
{
    public function edit()
    {
        $videoTour = VideoTourSection::first() ?? new VideoTourSection([
            'badge_text' => 'Take a video tour',
            'title' => 'Watch the video for taking your decision easily.',
            'button_text' => 'View all',
            'button_link' => '#',
            'phone_number' => '+91 97323 00007',
        ]);

        return view('admin.video-tour.edit', compact('videoTour'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'badge_text' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'video_path' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thumbnail_alt' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'remove_video' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $videoTour = VideoTourSection::first();

        if (!$videoTour) {
            $videoTour = new VideoTourSection();
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($videoTour->thumbnail) {
                $oldPath = public_path($videoTour->thumbnail);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $validated['thumbnail'] = ImageHelper::storeWebp($request->file('thumbnail'), 'video-tour', 1, 'thumbnail', 'video-tour');
        }

        // Handle video upload
        if ($request->hasFile('video_path')) {
            if ($videoTour->video_path) {
                $oldPath = public_path($videoTour->video_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $file = $request->file('video_path');
            $filename = 'video-tour-' . time() . '.' . $file->getClientOriginalExtension();
            $dir = public_path('uploads/video-tour');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $file->move($dir, $filename);
            $validated['video_path'] = 'uploads/video-tour/' . $filename;
        }

        // Handle video removal
        if ($request->boolean('remove_video') && $videoTour->video_path) {
            $oldPath = public_path($videoTour->video_path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $validated['video_path'] = null;
        }

        // Remove file objects from validated data
        unset($validated['remove_video']);
        if (isset($validated['video_path']) && is_object($validated['video_path'])) {
            unset($validated['video_path']);
        }
        if (isset($validated['thumbnail']) && is_object($validated['thumbnail'])) {
            unset($validated['thumbnail']);
        }

        $videoTour->fill($validated);
        $videoTour->save();

        return redirect()->route('admin.video-tour.edit')
            ->with('success', 'Video tour section updated successfully.');
    }
}
