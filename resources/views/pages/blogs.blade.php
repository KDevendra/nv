@extends('layouts.app')

@section('title', 'Blog & News - ZendoIndia')

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
            <h1 class="blog-banner-heading">Blog & News</h1>
            <div class="blog-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <p>Blog & News</p>
            </div>
        </div>
    </div>
</section>

<!-- BLOG LISTING -->
<section class="bg-pattern-white py-16">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left: Blog Grid (3 cols) -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($blogs as $blog)
                        <div class="blog-card bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow">
                            <a href="{{ route('blogs.show', $blog->slug) }}">
                                <div class="overflow-hidden relative">
                                    @if($blog->featured_image && file_exists(public_path($blog->featured_image)))
                                        <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->featured_image_alt ?? $blog->title }}"
                                             class="w-full h-56 object-cover hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-56 flex items-center justify-center p-6"
                                             style="background: linear-gradient(135deg, #0b2c3d 0%, #1a4a5e 50%, #b39359 100%);">
                                            <h4 class="text-white text-center font-heading text-xl leading-snug" style="font-size:20px !important;">
                                                {{ $blog->title }}
                                            </h4>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    @if($blog->category)
                                        <span class="inline-block px-3 py-1 bg-zendo-gold text-white text-xs font-semibold rounded-full mb-3">
                                            {{ $blog->category }}
                                        </span>
                                    @endif
                                    <h3 class="text-xl font-semibold font-heading text-zendo-navy hover:text-zendo-gold transition-colors mb-3">
                                        {{ $blog->title }}
                                    </h3>
                                    <p class="text-gray-600 font-body text-sm leading-relaxed mb-4 line-clamp-3">
                                        {{ $blog->excerpt }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-gray-500 font-body">
                                        <span>{{ $blog->published_date ? $blog->published_date->format('M d, Y') : 'Draft' }}</span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ number_format($blog->views) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-600 font-body text-lg">No blog posts available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($blogs->hasPages())
                    <div class="mt-12">
                        {{ $blogs->links() }}
                    </div>
                @endif
            </div>

            <!-- Right: Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Recent Posts -->
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                        <div class="bg-white rounded-lg shadow-xl p-6 border border-gray-100">
                            <h4 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Recent Posts</h4>
                            <div class="space-y-4">
                                @foreach($recentBlogs as $recent)
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
                                    @if(!$loop->last)
                                        <div class="border-b border-gray-100"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Links -->
                    <div class="bg-zendo-light-bg rounded-lg p-6 border border-gray-100">
                        <h4 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('properties.index') }}" class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    View All Properties
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}" class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}" class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Contact Us
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('calculators.emi-calculator') }}" class="flex items-center text-gray-700 hover:text-zendo-gold transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    EMI Calculator
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
