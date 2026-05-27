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
        background: rgb(0 0 0 / 62%);
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
                <div class="blog-card bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow">
                    <a href="{{ route('blogs.show', $blog->slug) }}">
                        <div class="overflow-hidden">
                            <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->featured_image_alt ?? $blog->title }}"
                                 class="w-full h-56 object-cover hover:scale-110 transition-transform duration-300">
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
</section>
@endsection
