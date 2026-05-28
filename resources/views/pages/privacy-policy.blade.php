@extends('layouts.app')

@section('title', 'Privacy Policy - ZendoIndia')

@section('content')
    <!-- PRIVACY POLICY BANNER -->
    <style>
        .privacy-banner-section {
            position: relative;
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 160px 0 80px;
            color: #fff;
        }

        .privacy-banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        .privacy-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .privacy-banner-left {
            max-width: 600px;
        }

        .privacy-banner-heading {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .privacy-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .privacy-breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .privacy-breadcrumb span {
            color: #ffffff;
        }

        .privacy-breadcrumb p {
            margin: 0;
            opacity: 0.8;
        }

        .privacy-meta {
            margin-top: 15px;
            font-size: 14px;
            opacity: 0.9;
        }

        @media (max-width: 767px) {
            .privacy-banner-heading {
                font-size: 32px;
            }

            .privacy-breadcrumb {
                font-size: 14px;
            }

            .privacy-banner-section {
                padding: 130px 0 60px;
            }
        }
    </style>
    <section class="privacy-banner-section">
        <div class="privacy-banner-overlay"></div>
        <div class="privacy-banner-container">
            <div class="privacy-banner-left">
                <h1 class="privacy-banner-heading">{{ $policy->title ?? 'Privacy Policy' }}</h1>
                <div class="privacy-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <p>Privacy Policy</p>
                </div>
                @if($policy)
                    <div class="privacy-meta">
                        @if($policy->last_updated)
                            <p>Last Updated: {{ $policy->last_updated->format('F d, Y') }}</p>
                        @endif
                        @if($policy->effective_date)
                            <p>Effective Date: {{ $policy->effective_date->format('F d, Y') }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- PRIVACY POLICY CONTENT -->
    <section class="bg-pattern-white py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 privacy-content">
            @if($policy)
                {!! $policy->content !!}
            @else
                <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 text-center border border-gray-100">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-heading text-zendo-navy mb-4">Privacy Policy Not Available</h2>
                    <p class="text-gray-600 font-body mb-6">
                        We're currently updating our privacy policy. Please check back soon or contact us for more information.
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

