@extends('layouts.admin')

@section('title', 'Add SEO Meta')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-heading text-zendo-navy font-semibold">Add SEO Meta</h2>
                <p class="text-gray-600 mt-1 text-sm">Configure meta tags for a page</p>
            </div>
            <a href="{{ route('admin.seo-metas.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors self-start sm:self-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>

        <form action="{{ route('admin.seo-metas.store') }}" method="POST" class="bg-white rounded-lg shadow-lg p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf

            <!-- Page Key -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Page *</label>
                <input type="text" name="page_key" value="{{ old('page_key') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="e.g. home, about, blog/my-post-slug, properties/my-property-slug"
                    list="page_key_options">
                <datalist id="page_key_options">
                    @foreach($availablePages as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </datalist>
                <p class="mt-1 text-sm text-gray-500">Enter the URL path (without domain). For static pages use keys like <code class="bg-gray-100 px-1 rounded">home</code>, <code class="bg-gray-100 px-1 rounded">about</code>. For dynamic pages use the full path like <code class="bg-gray-100 px-1 rounded">blog/my-post-slug</code> or <code class="bg-gray-100 px-1 rounded">properties/my-property-slug</code>.</p>
                @error('page_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="Page title for search engines (50-60 characters recommended)" maxlength="255">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="Page description for search engines (150-160 characters recommended)" maxlength="500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keywords -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                <input type="text" name="keywords" value="{{ old('keywords') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="Comma-separated keywords" maxlength="500">
                @error('keywords')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Open Graph Section -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Open Graph (Social Sharing)</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">OG Title</label>
                        <input type="text" name="og_title" value="{{ old('og_title') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                            placeholder="Title shown when shared on social media" maxlength="255">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">OG Description</label>
                        <textarea name="og_description" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                            placeholder="Description shown when shared on social media" maxlength="500">{{ old('og_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">OG Image URL</label>
                        <input type="text" name="og_image" value="{{ old('og_image') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                            placeholder="https://example.com/image.jpg" maxlength="500">
                    </div>
                </div>
            </div>

            <!-- Schema Markup Section -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Schema Markup (Structured Data)</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Schema Markup (JSON-LD)</label>
                        <textarea name="schema_markup" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent font-mono text-sm"
                            placeholder='{"@@context":"https://schema.org","@@type":"WebPage",...}'>{{ old('schema_markup') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Paste valid JSON-LD structured data markup.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">FAQ Schema (JSON-LD)</label>
                        <textarea name="faq_schema" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent font-mono text-sm"
                            placeholder='{"@@context":"https://schema.org","@@type":"FAQPage",...}'>{{ old('faq_schema') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Paste valid FAQ schema JSON-LD markup.</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save SEO Meta
                </button>
            </div>
        </form>
    </div>
@endsection
