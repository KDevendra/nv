@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Edit Blog Post</h2>
                <a href="{{ route('admin.blogs.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('title') border-red-500 @enderror"
                    placeholder="Enter blog post title"
                    required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $blog->slug) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="auto-generated-from-title">
                <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                    <input type="text" name="author" id="author" value="{{ old('author', $blog->author) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                        placeholder="Author name">
                    @error('author')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="published_date" class="block text-sm font-medium text-gray-700 mb-2">Published Date</label>
                    <input type="date" name="published_date" id="published_date" 
                        value="{{ old('published_date', $blog->published_date ? $blog->published_date->format('Y-m-d') : '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    @error('published_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $blog->category) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                        placeholder="e.g., Real Estate, Market News">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags', $blog->tags) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                        placeholder="tag1, tag2, tag3">
                    <p class="mt-1 text-sm text-gray-500">Comma-separated tags</p>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="Brief summary of the blog post">{{ old('excerpt', $blog->excerpt) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Short description for preview (auto-generated if empty)</p>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                <textarea name="content" id="editor1" rows="12"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('content') border-red-500 @enderror"
                    placeholder="Write your blog post content here..."
                    required>{{ old('content', $blog->content) }}</textarea>
                <input type="file" id="wordUpload" accept=".doc,.docx,.txt" style="display: none;">
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                @if($blog->featured_image)
                    <div class="mb-3">
                        <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->featured_image_alt ?? $blog->title }}" 
                             class="h-48 w-auto rounded-lg border border-gray-200">
                    </div>
                @endif
                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    onchange="previewImage(event)">
                <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image</p>
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="imagePreview" class="mt-3 hidden">
                    <img src="" alt="Preview" class="h-48 w-auto rounded-lg border border-gray-200">
                </div>
            </div>

            <div>
                <label for="featured_image_alt" class="block text-sm font-medium text-gray-700 mb-2">Image Alt Tag (SEO)</label>
                <input type="text" name="featured_image_alt" id="featured_image_alt" value="{{ old('featured_image_alt', $blog->featured_image_alt) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="Describe the image for SEO & accessibility">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $blog->sort_order) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                </div>

                <div class="flex items-center pt-8">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}
                        class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">Featured Post</label>
                </div>

                <div class="flex items-center pt-8">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $blog->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.blogs.index') }}" 
                   class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Blog Post
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#editor1',
    height: 450,
    plugins: 'advlist lists link image table preview fullscreen charmap paste codesample code',
    toolbar: `bold italic underline | alignleft aligncenter alignright alignjustify | fontsizeselect | forecolor backcolor | numlist bullist | indent outdent | link image | increasefontsize decreasefontsize | print table | preview fullscreen | charmap code | importword exportpdf exportword`,
    
    // Enable automatic uploads of images
    automatic_uploads: true,
    
    // URL of our upload handler
    images_upload_url: '{{ route("admin.blogs.upload-image") }}',
    
    // Add CSRF token to upload requests
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '{{ route("admin.blogs.upload-image") }}');
        
        // Add CSRF token
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        
        xhr.onload = function() {
            var json;
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };
        
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    },
    
    // Allow file picker for images
    file_picker_types: 'image',
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype === 'image') {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            
            input.onchange = function() {
                var file = this.files[0];
                var reader = new FileReader();
                
                reader.onload = function() {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    
                    // Call the callback and populate the Title field with the file name
                    callback(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            
            input.click();
        }
    },
    
    setup: function(editor) {
        // Increase Font Size
        editor.ui.registry.addButton('increasefontsize', {
            text: 'A+',
            onAction: () => editor.execCommand('FontSize', false, 'larger')
        });
        
        // Decrease Font Size
        editor.ui.registry.addButton('decreasefontsize', {
            text: 'A-',
            onAction: () => editor.execCommand('FontSize', false, 'smaller')
        });
        
        // Import Word
        editor.ui.registry.addButton('importword', {
            text: 'Import Word',
            onAction: function() {
                document.getElementById('wordUpload').click();
            }
        });
        
        // Export PDF (Print dialog)
        editor.ui.registry.addButton('exportpdf', {
            text: 'Export PDF',
            onAction: () => editor.execCommand('mcePrint')
        });
        
        // Export Word
        editor.ui.registry.addButton('exportword', {
            text: 'Export Word',
            onAction: function() {
                let content = editor.getContent();
                let blob = new Blob([content], { type: 'application/msword' });
                let url = URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'document.doc';
                a.click();
                URL.revokeObjectURL(url);
            }
        });
    }
});

// Import Word file content
document.getElementById('wordUpload').addEventListener('change', function(e) {
    let file = e.target.files[0];
    if (!file) return;
    
    let reader = new FileReader();
    reader.onload = function(event) {
        tinymce.get('editor1').setContent(event.target.result);
    };
    reader.readAsText(file);
});

function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endsection
