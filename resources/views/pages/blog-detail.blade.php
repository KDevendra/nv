@extends('layouts.app')

@section('title', $blog->title . ' - ZendoIndia Blog')

@section('content')
    <!-- BLOG BANNER -->
    <style>
        .blog-banner-section {
            position: relative;
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 160px 0 80px;
            color: #fff;
        }

        .blog-banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        .blog-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .blog-banner-left {
            max-width: 600px;
        }

        .blog-banner-heading {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .blog-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .blog-breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .blog-breadcrumb span {
            color: #ffffff;
        }

        .blog-breadcrumb p {
            margin: 0;
            opacity: 0.8;
        }

        @media (max-width: 767px) {
            .blog-banner-heading {
                font-size: 32px;
            }

            .blog-breadcrumb {
                font-size: 14px;
            }

            .blog-banner-section {
                padding: 130px 0 60px;
            }
        }
    </style>
    <section class="blog-banner-section">
        <div class="blog-banner-overlay"></div>
        <div class="blog-banner-container">
            <div class="blog-banner-left">
                <h1 class="blog-banner-heading">{{ $blog->title }}</h1>
                <div class="blog-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('blogs.index') }}">Blog</a>
                    <span>/</span>
                    <p>{{ Str::limit($blog->title, 30) }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- BLOG CONTENT -->
    <section class="bg-pattern-white py-16">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left Column - Blog Content -->
                <div class="lg:col-span-2">
                    <!-- Featured Image -->
                    @if ($blog->featured_image && file_exists(public_path($blog->featured_image)))
                        <div class="mb-8">
                            <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->featured_image_alt ?? $blog->title }}"
                                class="w-full h-auto rounded-lg shadow-xl">
                        </div>
                    @else
                        <div class="mb-8 rounded-lg shadow-xl overflow-hidden">
                            <div class="w-full h-64 flex items-center justify-center p-8"
                                style="background: linear-gradient(135deg, #0b2c3d 0%, #1a4a5e 50%, #b39359 100%);">
                                <h2 class="text-white text-center font-heading text-2xl md:text-3xl leading-snug">
                                    {{ $blog->title }}
                                </h2>
                            </div>
                        </div>
                    @endif

                    <!-- Excerpt -->
                    @if ($blog->excerpt)
                        <div class="bg-zendo-light-bg border-l-4 border-zendo-gold p-6 rounded-lg mb-8">
                            <p class="text-lg text-gray-700 font-body italic leading-relaxed">
                                {{ $blog->excerpt }}
                            </p>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none">
                        <div class="text-gray-700 font-body leading-relaxed blog-content">
                            {!! $blog->content !!}
                        </div>
                    </div>

                    <!-- Tags -->
                    @if ($blog->tags)
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-semibold font-heading text-zendo-navy mb-4">Tags:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach (explode(',', $blog->tags) as $tag)
                                    <span
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-zendo-gold hover:text-white transition-colors cursor-pointer">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Share Section -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold font-heading text-zendo-navy mb-4">Share this article:</h3>
                        <div class="flex space-x-4">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($blog->title) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-blue-700 text-white rounded-full hover:bg-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . request()->url()) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Inquiry Form -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <!-- Recent Posts -->
                        @if (isset($recentBlogs) && $recentBlogs->count() > 0)
                            <div class="bg-white rounded-lg shadow-xl p-6 mb-6 border border-gray-100">
                                <h4 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Recent Posts</h4>
                                <div class="space-y-4">
                                    @foreach ($recentBlogs as $recent)
                                        <a href="{{ route('blogs.show', $recent->slug) }}" class="block group">
                                            <div class="flex gap-3 items-start">
                                                <div class="flex-shrink-0">
                                                    @if($recent->featured_image && file_exists(public_path($recent->featured_image)))
                                                        <img src="{{ $recent->featured_image_url }}"
                                                            alt="{{ $recent->featured_image_alt ?? $recent->title }}"
                                                            class="w-20 h-16 object-cover rounded-lg">
                                                    @else
                                                        <div class="w-20 h-16 rounded-lg flex items-center justify-center p-2"
                                                            style="background: linear-gradient(135deg, #0b2c3d, #b39359);">
                                                            <span class="text-white text-xs text-center leading-tight">{{ Str::limit($recent->title, 20) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-sm font-semibold font-heading text-zendo-navy group-hover:text-zendo-gold transition-colors line-clamp-2 leading-tight">
                                                        {{ $recent->title }}
                                                    </span>
                                                    <p class="text-xs text-gray-500 font-body mt-1">
                                                        {{ $recent->published_date ? $recent->published_date->format('M d, Y') : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        @if (!$loop->last)
                                            <div class="border-b border-gray-100"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Inquiry Form Card -->
                        <div class="bg-white rounded-lg shadow-xl p-8 border border-gray-100">
                            <h3 class="text-2xl font-heading text-zendo-navy font-bold mb-2">Get In Touch</h3>
                            <p class="text-gray-600 font-body text-sm mb-6">Have questions? We're here to help!</p>

                            <form id="blogInquiryForm" class="space-y-4">
                                @csrf

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name
                                        *</label>
                                    <input type="text" name="name" id="name" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone
                                        *</label>
                                    <input type="tel" name="phone" id="phone" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                </div>

                                <div>
                                    <label for="message"
                                        class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea name="message" id="message" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent resize-none"
                                        placeholder="Tell us about your requirements..."></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full px-6 py-3 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-lg">
                                    Send Inquiry
                                </button>

                                <!-- Response Message -->
                                <div id="blogInquiryMessage" class="mt-4 hidden">
                                    <div id="blogInquirySuccess"
                                        class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="blogInquirySuccessText"></span>
                                        </div>
                                    </div>
                                    <div id="blogInquiryError"
                                        class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="blogInquiryErrorText"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Related Posts -->
                        @if ($relatedBlogs->count() > 0)
                            <div class="bg-white rounded-lg shadow-xl p-6 mt-6 border border-gray-100">
                                <h4 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Related Articles</h4>
                                <div class="space-y-4">
                                    @foreach ($relatedBlogs as $relatedBlog)
                                        <a href="{{ route('blogs.show', $relatedBlog->slug) }}" class="block group">
                                            <div class="flex gap-3 items-start">
                                                <div class="flex-shrink-0">
                                                    @if($relatedBlog->featured_image && file_exists(public_path($relatedBlog->featured_image)))
                                                        <img src="{{ $relatedBlog->featured_image_url }}"
                                                            alt="{{ $relatedBlog->featured_image_alt ?? $relatedBlog->title }}"
                                                            class="w-24 h-20 object-cover rounded-lg">
                                                    @else
                                                        <div class="w-24 h-20 rounded-lg flex items-center justify-center p-2"
                                                            style="background: linear-gradient(135deg, #0b2c3d, #b39359);">
                                                            <span class="text-white text-xs text-center leading-tight">{{ Str::limit($relatedBlog->title, 25) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span
                                                        class="text-sm font-semibold font-heading text-zendo-navy group-hover:text-zendo-gold transition-colors line-clamp-2 mb-1 leading-tight">
                                                        {{ $relatedBlog->title }}
                                                    </span>
                                                    <p class="text-xs text-gray-500 font-body">
                                                        {{ $relatedBlog->published_date ? $relatedBlog->published_date->format('M d, Y') : 'Draft' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        @if (!$loop->last)
                                            <div class="border-b border-gray-100 my-4"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quick Links Card -->
                        <div class="bg-zendo-light-bg rounded-lg p-6 mt-6 border border-gray-100">
                            <h4 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Quick Links</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('properties.index') }}"
                                        class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        View All Properties
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('about') }}"
                                        class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        About Us
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}"
                                        class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Contact Us
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('blogs.index') }}"
                                        class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        All Blogs
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        .blog-content h1,
        .blog-content h2,
        .blog-content h3,
        .blog-content h4,
        .blog-content h5,
        .blog-content h6 {
            font-family: var(--font-heading);
            color: #0b2c3d;
            font-weight: 700;
            margin-top: 1.5em;
            margin-bottom: 0.75em;
        }

        .blog-content h1 {
            font-size: 2.25em;
        }

        .blog-content h2 {
            font-size: 1.875em;
        }

        .blog-content h3 {
            font-size: 1.5em;
        }

        .blog-content h4 {
            font-size: 1.25em;
        }

        .blog-content p {
            margin-bottom: 1.25em;
            line-height: 1.8;
        }

        .blog-content ul,
        .blog-content ol {
            margin-bottom: 1.25em;
            padding-left: 2em;
        }

        .blog-content li {
            margin-bottom: 0.5em;
        }

        .blog-content a {
            color: #b39359;
            text-decoration: underline;
        }

        .blog-content a:hover {
            color: #0b2c3d;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5em 0;
        }

        .blog-content blockquote {
            border-left: 4px solid #b39359;
            padding-left: 1.5em;
            margin: 1.5em 0;
            font-style: italic;
            color: #4b5563;
        }

        .blog-content code {
            background-color: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }

        .blog-content pre {
            background-color: #1f2937;
            color: #f9fafb;
            padding: 1em;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5em 0;
        }

        .blog-content pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
        }
    </style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Blog Inquiry Form Submission
    const blogInquiryForm = document.getElementById('blogInquiryForm');
    
    if (blogInquiryForm) {
        blogInquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            fetch('{{ route('inquiries.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToastMessage('success', data.message || 'Thank you for your inquiry! We will get back to you soon.');
                        this.reset();
                    } else {
                        let errorMsg = data.message || 'Something went wrong. Please try again.';
                        if (data.errors) {
                            errorMsg = Object.values(data.errors).flat().join('<br>');
                        }
                        showToastMessage('error', errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastMessage('error', 'Something went wrong. Please try again later.');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                });
        });
    }

    function showToastMessage(type, message) {
        // Remove existing toast
        const existingToast = document.querySelector('.inquiry-toast');
        if (existingToast) {
            existingToast.remove();
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `inquiry-toast fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-md ${
            type === 'success' 
                ? 'bg-green-50 border border-green-200 text-green-800' 
                : 'bg-red-50 border border-red-200 text-red-800'
        }`;
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${type === 'success' 
                        ? '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                        : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                    }
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }
});
</script>
@endsection
