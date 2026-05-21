<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    public function edit()
    {
        $aboutPage = AboutPageSection::firstOrCreate(
            ['id' => 1],
            [
                'section_title' => 'Our Company',
                'section_subtitle' => 'Lorem Ipsum Conedosvtr',
                'is_active' => true,
            ]
        );

        return view('admin.about-page.edit', compact('aboutPage'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'section_title' => 'nullable|string|max:255',
            'section_subtitle' => 'nullable|string',
            'who_we_are_title' => 'nullable|string|max:255',
            'who_we_are_description' => 'nullable|string',
            'who_we_are_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mission_title' => 'nullable|string|max:255',
            'mission_description' => 'nullable|string',
            'mission_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vision_title' => 'nullable|string|max:255',
            'vision_description' => 'nullable|string',
            'vision_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'values_heading' => 'nullable|string|max:255',
            'values_who_we_are' => 'nullable|string',
            'values_who_we_are_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'values_mission' => 'nullable|string',
            'values_mission_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'values_vision' => 'nullable|string',
            'values_vision_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'values_teamwork' => 'nullable|string',
            'values_teamwork_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'team_section_title' => 'nullable|string|max:255',
            'team_section_heading' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Always update the single record (id=1), never insert a new one
        $aboutPage = AboutPageSection::firstOrNew(['id' => 1]);

        // Handle icon uploads
        if ($request->hasFile('who_we_are_icon')) {
            if ($aboutPage->who_we_are_icon) {
                Storage::disk('public')->delete($aboutPage->who_we_are_icon);
            }
            $validated['who_we_are_icon'] = $request->file('who_we_are_icon')->store('about-icons', 'public');
        }

        if ($request->hasFile('mission_icon')) {
            if ($aboutPage->mission_icon) {
                Storage::disk('public')->delete($aboutPage->mission_icon);
            }
            $validated['mission_icon'] = $request->file('mission_icon')->store('about-icons', 'public');
        }

        if ($request->hasFile('vision_icon')) {
            if ($aboutPage->vision_icon) {
                Storage::disk('public')->delete($aboutPage->vision_icon);
            }
            $validated['vision_icon'] = $request->file('vision_icon')->store('about-icons', 'public');
        }

        // Handle values tab image uploads (stored in public/about-values/)
        $valuesImageFields = [
            'values_who_we_are_image',
            'values_mission_image',
            'values_vision_image',
            'values_teamwork_image',
        ];

        $uploadDir = public_path('about-values');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        foreach ($valuesImageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file
                if ($aboutPage->$field && File::exists(public_path($aboutPage->$field))) {
                    File::delete(public_path($aboutPage->$field));
                }
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $validated[$field] = 'about-values/' . $filename;
            }
        }

        $validated['is_active'] = 1;

        $aboutPage->fill($validated);
        $aboutPage->save();

        return redirect()->route('admin.about-page.edit')
            ->with('success', 'About page updated successfully!');
    }
}
