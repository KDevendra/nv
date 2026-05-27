<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SeoMetaController extends Controller
{
    public function index()
    {
        $seoMetas = SeoMeta::orderBy('page_key')->paginate(20);
        $pageOptions = SeoMeta::pageOptions();

        return view('admin.seo-metas.index', compact('seoMetas', 'pageOptions'));
    }

    public function create()
    {
        $pageOptions = SeoMeta::pageOptions();
        $existingKeys = SeoMeta::pluck('page_key')->toArray();

        // Filter out already used page keys
        $availablePages = array_diff_key($pageOptions, array_flip($existingKeys));

        return view('admin.seo-metas.create', compact('availablePages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_key' => 'required|string|max:255|unique:seo_metas,page_key',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'schema_markup' => 'nullable|string',
            'faq_schema' => 'nullable|string',
        ]);

        SeoMeta::create($validated);

        return redirect()->route('admin.seo-metas.index')
            ->with('success', 'SEO Meta created successfully.');
    }

    public function edit(SeoMeta $seoMeta)
    {
        $pageOptions = SeoMeta::pageOptions();

        return view('admin.seo-metas.edit', compact('seoMeta', 'pageOptions'));
    }

    public function update(Request $request, SeoMeta $seoMeta)
    {
        $validated = $request->validate([
            'page_key' => 'required|string|max:255|unique:seo_metas,page_key,' . $seoMeta->id,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'schema_markup' => 'nullable|string',
            'faq_schema' => 'nullable|string',
        ]);

        $seoMeta->update($validated);

        return redirect()->route('admin.seo-metas.index')
            ->with('success', 'SEO Meta updated successfully.');
    }

    public function destroy(SeoMeta $seoMeta)
    {
        // Prevent deletion of static page SEO entries
        if ($seoMeta->isStatic()) {
            return redirect()->route('admin.seo-metas.index')
                ->with('error', 'Cannot delete SEO meta for static pages. You can only edit them.');
        }

        $seoMeta->delete();

        return redirect()->route('admin.seo-metas.index')
            ->with('success', 'SEO Meta deleted successfully.');
    }
}
