<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::ordered()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = ImageHelper::storeWebp($request->file('featured_image'), $validated['title'], 0, 'featured', 'blogs');
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                $oldPath = public_path($blog->featured_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $validated['featured_image'] = ImageHelper::storeWebp($request->file('featured_image'), $validated['title'], $blog->id, 'featured', 'blogs');
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->featured_image) {
            $path = public_path($blog->featured_image);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Upload image for TinyMCE editor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $dir = public_path('uploads/blogs/content');

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file->move($dir, $filename);

            return response()->json([
                'location' => asset('uploads/blogs/content/' . $filename)
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
