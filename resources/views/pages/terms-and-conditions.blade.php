@extends('layouts.app')

@section('title', 'Terms & Conditions - ZendoIndia')

@section('content')
    <!-- BANNER -->
    <style>
        .terms-banner-section {
            position: relative;
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 160px 0 80px;
            color: #fff;
        }

        .terms-banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        .terms-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .terms-banner-left {
            max-width: 600px;
        }

        .terms-banner-heading {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .terms-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .terms-breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-breadcrumb span {
            color: #ffffff;
        }

        .terms-breadcrumb p {
            margin: 0;
            opacity: 0.8;
        }

        .terms-meta {
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.9;
        }

        @media (max-width: 767px) {
            .terms-banner-heading {
                font-size: 32px;
            }

            .terms-breadcrumb {
                font-size: 14px;
            }

            .terms-banner-section {
                padding: 130px 0 60px;
            }
        }
    </style>
    <section class="terms-banner-section">
        <div class="terms-banner-overlay"></div>
        <div class="terms-banner-container">
            <div class="terms-banner-left">
                <h1 class="terms-banner-heading">{{ $terms->title ?? 'Terms & Conditions' }}</h1>
                <div class="terms-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <p>Terms & Conditions</p>
                </div>
                @if($terms)
                    <div class="terms-meta">
                        @if($terms->last_updated)
                            <p>Last Updated: {{ $terms->last_updated->format('F d, Y') }}</p>
                        @endif
                        @if($terms->effective_date)
                            <p>Effective Date: {{ $terms->effective_date->format('F d, Y') }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="bg-pattern-white py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 privacy-content">
            @if($terms)
                {!! $terms->content !!}
            @else
                <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 text-center border border-gray-100">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-heading text-zendo-navy mb-4">Terms & Conditions Not Available</h2>
                    <p class="text-gray-600 font-body mb-6">
                        We're currently updating our terms and conditions. Please check back soon or contact us for more information.
                    </p>
                    <a href="{{ route('contact') }}" class="inline-block px-6 py-3 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                        Contact Us
                    </a>
                </div>
            @endif

            <!-- Back to Home -->
            <div class="text-center mt-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-zendo-gold hover:text-zendo-navy transition-colors font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </section>
@endsection
