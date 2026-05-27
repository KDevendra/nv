<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OurClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OurClientController extends Controller
{
    public function index()
    {
        $clients = OurClient::ordered()->paginate(20);
        return view('admin.our-clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.our-clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadLogo($request->file('logo'));
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        OurClient::create($validated);

        return redirect()->route('admin.our-clients.index')
            ->with('success', 'Client added successfully!');
    }

    public function edit(OurClient $ourClient)
    {
        return view('admin.our-clients.edit', compact('ourClient'));
    }

    public function update(Request $request, OurClient $ourClient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($ourClient->logo && File::exists(public_path($ourClient->logo))) {
                File::delete(public_path($ourClient->logo));
            }
            $validated['logo'] = $this->uploadLogo($request->file('logo'));
        }

        $validated['is_active'] = $request->has('is_active');

        $ourClient->update($validated);

        return redirect()->route('admin.our-clients.index')
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(OurClient $ourClient)
    {
        if ($ourClient->logo && File::exists(public_path($ourClient->logo))) {
            File::delete(public_path($ourClient->logo));
        }

        $ourClient->delete();

        return redirect()->route('admin.our-clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    private function uploadLogo($file): string
    {
        $dir = public_path('uploads/clients');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $filename);

        return 'uploads/clients/' . $filename;
    }
}
