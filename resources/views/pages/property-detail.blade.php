@extends('layouts.app')

@section('title', $property->title . ' - ZendoIndia')

@section('styles')
    <style>
        :root {
            --zendo-gold: #b39359;
            --zendo-navy: #0b2c3d;
            --zendo-bg: #fbf8f2;
            --zendo-blue: #013b7b;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            font-size: 1.125rem;
            line-height: 1.7;
            overflow-x: hidden;
        }

        /* Popup Modal Styles */
        .inquiry-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(11, 44, 61, 0.75);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(3px);
            overflow-y: auto;
        }

        .inquiry-popup-overlay.hidden {
            display: none;
        }

        .inquiry-popup-content {
            background: white;
            border-radius: 20px;
            max-width: 480px;
            width: 100%;
            padding: 25px 25px 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: slideUp 0.4s ease-out;
            margin: auto;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .inquiry-popup-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .inquiry-popup-icon {
            display: none;
        }

        .inquiry-popup-title {
            font-size: 25px !important;
            font-weight: 700;
            color: var(--zendo-navy);
            margin-bottom: 8px;
            font-family: 'Forum', cursive;
            line-height: 1.2;
        }

        .inquiry-popup-subtitle {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
        }

        .popup-form-group {
            margin-bottom: 12px;
        }

        .popup-form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--zendo-navy);
            margin-bottom: 6px;
        }

        .popup-form-input,
        .popup-form-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Nunito Sans', sans-serif;
        }

        .popup-form-input:focus,
        .popup-form-textarea:focus {
            outline: none;
            border-color: var(--zendo-gold);
            box-shadow: 0 0 0 3px rgba(179, 147, 89, 0.1);
        }

        .popup-form-textarea {
            min-height: 60px;
            resize: vertical;
        }

        .popup-submit-btn {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--zendo-gold), #9a7c4d);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(179, 147, 89, 0.3);
            margin-top: 8px;
        }

        .popup-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(179, 147, 89, 0.4);
        }

        .popup-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .popup-message {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            display: none;
        }

        .popup-message.success {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }

        .popup-message.error {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }

        .popup-privacy-text {
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            margin-top: 12px;
            line-height: 1.4;
        }

        .popup-privacy-text a {
            color: var(--zendo-gold);
            text-decoration: underline;
            transition: color 0.2s ease;
        }

        .popup-privacy-text a:hover {
            color: #9a7c4d;
        }

        .popup-loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @media (max-width: 640px) {
            .inquiry-popup-content {
                padding: 20px 18px 25px;
                max-width: 95%;
            }

            .inquiry-popup-title {
                font-size: 20px;
            }

            .inquiry-popup-subtitle {
                font-size: 12px;
            }

            .popup-form-input,
            .popup-form-textarea {
                padding: 9px 12px;
                font-size: 13px;
            }
        }

        h1,
        h2,
        h5,
        h6 {
            font-family: 'Forum', cursive;
            font-size: 3rem !important;
            font-weight: 400;
            line-height: 0.9166em;
            margin-top: 0.17em !important;
            margin-bottom: 0.17em !important;
        }

        .bg-pattern-white {
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle fill='%23FBF8F2' opacity='0.7' cx='10' cy='10' r='1.5'/%3E%3C/svg%3E");
            background-size: 15px 15px;
        }

        .about-banner-section {
            position: relative;
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 160px 0 80px;
            color: #fff;
        }

        .about-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        .about-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .about-banner-heading {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .about-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .about-breadcrumb a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }

        #sgdxp-page {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 15px 15px;
        }

        .sgdxp-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 24px;
        }

        .sgdxp-header-left {
            flex: 1 1 auto;
            min-width: 0;
        }

        .sgdxp-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        .sgdxp-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .sgdxp-badge-status {
            background: var(--zendo-gold);
            color: #fff;
        }

        .sgdxp-title {
            font-size: 42px !important;
            color: var(--zendo-navy);
            margin-bottom: 6px;
        }

        .sgdxp-location-line {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #555;
        }

        .sgdxp-header-right {
            flex: 0 0 auto;
            text-align: right;
        }

        .sgdxp-starting-price-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #777;
            margin-bottom: 4px;
        }

        .sgdxp-starting-price-value {
            font-size: 26px;
            color: var(--zendo-gold);
        }

        #sgdxp-main {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
            gap: 24px;
            align-items: stretch;
        }

        .sgdxp-image-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 14px 35px rgba(0, 0, 0, .07);
            line-height: 0;
        }

        .sgdxp-image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 300px;
            max-height: 480px;
            overflow: hidden;
            line-height: 0;
            border-radius: 18px;
        }

        .sgdxp-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sgdxp-contact-card {
            background: var(--zendo-navy);
            color: #f8f9fb;
            border-radius: 18px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .4);
        }

        .sgdxp-contact-card h2 {
            font-size: 22px;
            margin-bottom: 6px;
            color: #fff;
        }

        .sgdxp-contact-subtext {
            font-size: 13px;
            color: #d2d8e6;
            margin-bottom: 22px;
        }

        .sgdxp-contact-row {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
        }

        .sgdxp-contact-icon {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .06);
        }

        .sgdxp-contact-label {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            color: #f4e1bc;
            margin-bottom: 4px;
        }

        .sgdxp-contact-details {
            font-size: 14px;
            color: #e6edf8;
        }

        .sgdxp-call-number {
            font-size: 15px;
            font-weight: 600;
        }

        .sgdxp-request-btn {
            margin-top: auto;
            padding-top: 12px;
        }

        .sgdxp-request-btn button {
            width: 100%;
            border-radius: 999px;
            border: none;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            background: var(--zendo-gold);
            color: #fff;
            box-shadow: 0 14px 28px rgba(0, 0, 0, .25);
        }

        .sgdxp-request-btn button:hover {
            background: #a1814b;
            transform: translateY(-2px);
        }

        #sg2-section {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 16px 15px;
        }

        .sg2-row {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
            gap: 28px;
            align-items: flex-start;
        }

        .sg2-usp-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 14px 32px rgba(0, 0, 0, .06);
            padding: 18px 24px;
            margin-bottom: 28px;
        }

        .sg2-usp-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .sg2-usp-item-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--zendo-gold);
            margin-bottom: 4px;
            font-weight: 800;
        }

        .sg2-usp-item-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--zendo-blue);
        }

        .sg2-hr {
            height: 1px;
            border: none;
            background: #e2e6ed;
            margin-bottom: 18px;
        }

        .sg2-title-main {
            font-size: 32px !important;
            font-weight: 600;
            color: var(--zendo-navy);
            margin-bottom: 16px;
        }

        .sg2-overview-text {
            font-size: 17px;
            color: #444;
            margin-bottom: 24px;
        }

        .sg2-subtitle {
            font-size: 25px;
            font-weight: 600;
            color: #0b2c3d;
            margin-bottom: 12px;
            font-family: 'Forum';
        }

        .sg2-reasons {
            list-style: none;
            padding: 0;
            margin: 0 0 26px;
        }

        .sg2-reasons li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 17px;
            color: #222;
            margin-bottom: 8px;
        }

        .sg2-bullet-icon {
            flex: 0 0 auto;
            margin-top: 3px;
        }

        .sg2-form-card {
            background: var(--zendo-navy);
            color: #f6fbff;
            border-radius: 18px;
            padding: 26px 26px 30px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .4);
            position: sticky;
            top: 80px;
            height: fit-content;
            z-index: 10;
        }

        .sg2-form-title {
            font-size: 30px !important;
            margin-bottom: 6px;
        }

        .sg2-form-subtext {
            font-size: 14px;
            color: #d0deeb;
            margin-bottom: 22px;
        }

        .sg2-form-group {
            margin-bottom: 14px;
        }

        .sg2-input,
        .sg2-textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #234056;
            background: #123448;
            color: #fff;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .sg2-input::placeholder,
        .sg2-textarea::placeholder {
            color: #9fb3c5;
        }

        .sg2-input:focus,
        .sg2-textarea:focus {
            border-color: var(--zendo-gold);
            box-shadow: 0 0 0 1px rgba(179, 147, 89, .5);
        }

        .sg2-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .sg2-btn-wrap {
            margin-top: 18px;
        }

        .sg2-btn {
            width: 100%;
            border-radius: 999px;
            border: none;
            padding: 14px 18px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            background: var(--zendo-gold);
            color: #fff;
            box-shadow: 0 16px 34px rgba(0, 0, 0, .35);
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .sg2-btn:hover {
            background: #a1814b;
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
        }

        .apw-table-wrap {
            display: block;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            border-radius: 12px;
            background: var(--zendo-bg);
        }

        .apw-table {
            width: 100%;
            min-width: 720px;
            border-collapse: collapse;
            background: var(--zendo-bg);
            font-family: inherit;
        }

        .apw-table thead th {
            background: var(--zendo-navy);
            color: var(--zendo-bg);
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid var(--zendo-gold);
            white-space: nowrap;
        }

        .apw-table td {
            padding: 12px;
            border: 1px solid var(--zendo-gold);
            color: var(--zendo-navy);
            font-size: 14px;
            white-space: nowrap;
        }

        .apw-table tbody tr:nth-child(even) {
            background: rgba(179, 147, 89, .08);
        }

        .apw-table tbody tr:hover {
            background: rgba(179, 147, 89, .18);
            transition: .2s ease;
        }

        .apw-table td:first-child {
            font-weight: 600;
            text-align: center;
            width: 80px;
        }

        #sg-gallery-similar {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 16px 15px;
        }

        .sg-gs-row {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
            gap: 28px;
            align-items: flex-start;
        }

        .sg-gallery-box {
            background: #fff;
            padding: 5px;
            border-radius: 16px;
        }

        .sg-gallery-title {
            font-size: 32px !important;
            font-weight: 600;
            color: var(--zendo-blue);
            margin-bottom: 16px;
        }

        .sg-slider {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }

        .sg-slide {
            display: none;
            width: 100%;
        }

        .sg-slide img {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
        }

        .sg-prev,
        .sg-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff91;
            color: var(--zendo-blue);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 40px;
            z-index: 5;
            border: 2px solid #fff;
            padding-bottom: 5px;
        }

        .sg-prev {
            left: 10px;
        }

        .sg-next {
            right: 10px;
        }

        .sg-similar-box {
            background: var(--zendo-navy);
            color: #f6fbff;
            border-radius: 18px;
            padding: 26px 26px 30px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .4);
            margin-top: 14px;
        }

        .sg-similar-title {
            font-size: 32px !important;
            color: #fff;
            margin-bottom: 16px;
        }

        .sg-similar-card {
            background: var(--zendo-navy);
            border-radius: 16px;
            padding: 15px 0;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .08);
            margin-bottom: 20px;
        }

        .sg-similar-card img {
            width: 100%;
            border-radius: 14px;
            margin-bottom: 12px;
        }

        .sg-similar-name {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 14px;
            text-align: left;
        }

        .sg-similar-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            font-size: 14px;
        }

        .sg-similar-label {
            color: #fff;
            font-weight: 600;
            font-size: 13px;
        }

        .sg-badge {
            background: var(--zendo-gold);
            border: 1px solid var(--zendo-gold);
            color: #fff;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 4px;
        }

        #newRowFaqMap {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 16px;
        }

        .newRow {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
            gap: 28px;
            align-items: flex-start;
        }

        .nr-faq-title {
            font-size: 32px !important;
            font-weight: 600;
            color: var(--zendo-blue);
            margin-bottom: 16px;
        }

        .nr-faq-box {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .07);
        }

        .nr-faq-item {
            border-bottom: 1px solid #e5e8ef;
        }

        .nr-faq-item:last-child {
            border-bottom: none;
        }

        .nr-faq-item summary {
            padding: 14px 16px;
            cursor: pointer;
            font-size: 18px;
            color: var(--zendo-gold);
            list-style: none;
            position: relative;
            margin-bottom: 5px;
        }

        .nr-faq-item summary::-webkit-details-marker {
            display: none;
        }

        .nr-faq-item summary::after {
            content: "+";
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--zendo-gold);
        }

        .nr-faq-item[open] summary::after {
            content: "–";
        }

        .nr-faq-body {
            padding: 0 16px 16px;
            font-size: 16px;
            color: #444;
        }

        .nr-map-card {
            background: #fff;
            border-radius: 16px;
            padding: 0 10px 14px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
        }

        .nr-map-title {
            font-size: 32px !important;
            font-weight: 600;
            color: var(--zendo-blue);
            margin-bottom: 16px;
            font-family: 'Forum', cursive;
            padding-top: 5px;
        }

        .nr-map-address {
            font-size: 14px;
            margin-bottom: 12px;
            color: #222;
        }

        .nr-map-iframe {
            border: 2px solid #f8f9fa;
            border-radius: 14px;
            overflow: hidden;
        }

        .nr-map-iframe iframe {
            width: 100%;
            height: 260px;
            border: none;
        }

        #sg-mobile-sidebar-stack {
            display: none;
            max-width: 1200px;
            margin: 30px auto 0;
            padding: 0 16px 24px;
        }

        @media (max-width:992px) {
            .sgdxp-header-row {
                flex-direction: column;
            }

            #sgdxp-main {
                grid-template-columns: 1fr;
            }

            .sg2-row {
                grid-template-columns: 1fr;
            }

            .sg2-usp-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sg-gs-row {
                grid-template-columns: 1fr;
            }

            .newRow {
                grid-template-columns: 1fr;
            }

            #sg-mobile-sidebar-stack {
                display: block;
            }

            #sg-mobile-sidebar-stack .sg-mobile-stack-item {
                margin-top: 18px;
            }

            .sgdxp-contact-card,
            .sg2-form-card,
            .sg-similar-box,
            .nr-map-card {
                display: none !important;
            }

            #sg-mobile-sidebar-stack .sgdxp-contact-card {
                display: flex !important;
            }

            #sg-mobile-sidebar-stack .sg2-form-card {
                display: block !important;
                position: static !important;
                top: auto !important;
            }

            #sg-mobile-sidebar-stack .sg-similar-box {
                display: block !important;
            }

            #sg-mobile-sidebar-stack .nr-map-card {
                display: block !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Inquiry Popup Modal -->
    <div id="inquiry-popup-overlay" class="inquiry-popup-overlay hidden">
        <div class="inquiry-popup-content">
            <!-- Close Button -->
            <button type="button" id="popup-close-btn"
                style="position:absolute;top:12px;right:12px;width:32px;height:32px;border-radius:50%;background:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(239,68,68,0.3);transition:all 0.2s ease;z-index:10;"
                onmouseover="this.style.background='#dc2626';this.style.transform='scale(1.1)'"
                onmouseout="this.style.background='#ef4444';this.style.transform='scale(1)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"
                    stroke-linecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
            <div class="inquiry-popup-header">
                <h5 class="inquiry-popup-title">Interested in {{ $property->title }}?</h5>
                <p class="inquiry-popup-subtitle">Our expert team will provide you with floor plans, pricing, and a free
                    consultation. Fill in your details below.</p>
            </div>

            <!-- Success/Error Messages -->
            <div id="popup-success-message" class="popup-message success">
                Thank you! Redirecting you to the property details...
            </div>
            <div id="popup-error-message" class="popup-message error">
                Something went wrong. Please try again.
            </div>

            <!-- Popup Form -->
            <form id="popup-inquiry-form" action="{{ route('inquiries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">

                <div class="popup-form-group">
                    <label class="popup-form-label">Your Name *</label>
                    <input type="text" name="name" class="popup-form-input" placeholder="Enter your full name"
                        required>
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Phone Number *</label>
                    <input type="tel" name="phone" class="popup-form-input" placeholder="Enter your phone number"
                        required>
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Email Address</label>
                    <input type="email" name="email" class="popup-form-input" placeholder="Enter your email (optional)">
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Message</label>
                    <textarea name="message" class="popup-form-textarea" placeholder="Any specific requirements? (optional)"></textarea>
                </div>

                <button type="submit" class="popup-submit-btn" id="popup-submit-btn">
                    <span class="popup-btn-text">Get Property Details</span>
                    <span class="popup-btn-loading" style="display:none;">
                        <span class="popup-loading-spinner"></span>
                        Submitting...
                    </span>
                </button>

                <p class="popup-privacy-text">
                    🔒 Your information is safe with us. By submitting, you agree to our <a
                        href="{{ route('home') }}#privacy" target="_blank"
                        style="color: var(--zendo-gold); text-decoration: underline;">Privacy Policy</a> and <a
                        href="{{ route('terms-and-conditions') }}" target="_blank"
                        style="color: var(--zendo-gold); text-decoration: underline;">Terms &amp; Conditions</a>. Our team
                    will contact you for consultation within 24 hours.
                </p>
            </form>
        </div>
    </div>

    <!-- Request Callback Modal -->
    <div id="callback-modal-overlay" class="inquiry-popup-overlay hidden">
        <div class="inquiry-popup-content">
            <div class="inquiry-popup-header">
                <h5 class="inquiry-popup-title">Request a Callback</h5>
                <p class="inquiry-popup-subtitle">Share your details and our team will call you with floor plans, pricing
                    and exclusive offers.</p>
            </div>

            <!-- Success/Error Messages -->
            <div id="callback-modal-success-message" class="popup-message success">
                Thank you! We'll contact you shortly.
            </div>
            <div id="callback-modal-error-message" class="popup-message error">
                Something went wrong. Please try again.
            </div>

            <!-- Callback Form -->
            <form id="callback-modal-form" action="{{ route('inquiries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">

                <div class="popup-form-group">
                    <label class="popup-form-label">Your Name *</label>
                    <input type="text" name="name" class="popup-form-input" placeholder="Enter your full name"
                        required>
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Phone Number *</label>
                    <input type="tel" name="phone" class="popup-form-input" placeholder="Enter your phone number"
                        required>
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Email Address</label>
                    <input type="email" name="email" class="popup-form-input"
                        placeholder="Enter your email (optional)">
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Message</label>
                    <textarea name="message" class="popup-form-textarea" placeholder="I am interested in {{ $property->title }}..."></textarea>
                </div>

                <button type="submit" class="popup-submit-btn" id="callback-modal-submit-btn">
                    <span class="popup-btn-text">Submit Request</span>
                    <span class="popup-btn-loading" style="display:none;">
                        <span class="popup-loading-spinner"></span>
                        Submitting...
                    </span>
                </button>

                <button type="button" class="popup-submit-btn" id="callback-modal-close-btn"
                    style="background: #6b7280; margin-top: 10px;">
                    Close
                </button>
            </form>
        </div>
    </div>

    <!-- ABOUT BANNER -->
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div class="about-banner-left">
                <h1 class="about-banner-heading">{{ $property->title }}</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('properties.index') }}">Properties</a>
                    <span>/</span>
                    <p>{{ $property->title }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROPERTY HERO SECTION -->
    <div id="sgdxp-page">
        <div class="sgdxp-header-row">
            <div class="sgdxp-header-left">
                <div class="sgdxp-badges">
                    @if ($property->projectStatus)
                        <span class="sgdxp-badge sgdxp-badge-status">{{ $property->projectStatus->name }}</span>
                    @endif
                    @if ($property->is_featured)
                        <span class="sgdxp-badge sgdxp-badge-status">Featured</span>
                    @endif
                </div>
                <h1 class="sgdxp-title">{{ $property->title }}</h1>
                <div class="sgdxp-location-line">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                        <path fill="#b39359"
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                    </svg>
                    <span>
                        {{ $property->address }}
                        @isset($property->location)
                            , {{ $property->location->name }}
                        @endisset
                        @isset($property->city)
                            , {{ $property->city->name }}
                        @endisset
                    </span>

                </div>
            </div>

            <div class="sgdxp-header-right">
                <div class="sgdxp-starting-price-label">Starting Price</div>
                <div class="sgdxp-starting-price-value">{{ $property->formatted_price }}</div>
            </div>
        </div>

        <div id="sgdxp-main">
            <div class="sgdxp-image-card">
                <div class="sgdxp-image-wrapper">
                    @if ($property->images->count() > 0)
                        <img src="{{ asset($property->images->first()->image_path) }}"
                            alt="{{ $property->images->first()->alt_tag ?? $property->title }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=70"
                            alt="{{ $property->title }}">
                    @endif
                </div>
            </div>

            <aside class="sgdxp-contact-card">
                <div>
                    <h2>Get in Touch</h2>
                    <p class="sgdxp-contact-subtext">Contact us for more details, site visits, or pricing information.</p>

                    <div class="sgdxp-contact-section">
                        <div class="sgdxp-contact-row">
                            <div class="sgdxp-contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path fill="#b39359"
                                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Our Office</div>
                                <div class="sgdxp-contact-details">
                                    <a href="https://maps.google.com/?q=Tapasya+Corp+Heights+Tower+B+Sector+126+Noida" target="_blank" style="color:#e6edf8;text-decoration:none;">
                                        <p>Tapasya Corp Heights, Tower B,</p>
                                        <p>Sector 126, Noida,</p>
                                        <p>Uttar Pradesh 201303</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="sgdxp-contact-row">
                            <div class="sgdxp-contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path fill="#b39359"
                                        d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Email Us</div>
                                <div class="sgdxp-contact-details">
                                    <a href="mailto:info@zendoindia.com" style="color:#e6edf8;text-decoration:none;">info@zendoindia.com</a>
                                </div>
                            </div>
                        </div>

                        <div class="sgdxp-contact-row">
                            <div class="sgdxp-contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path fill="#b39359"
                                        d="M6.62 10.79a15.093 15.093 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.07 21 3 13.93 3 5c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.11.35.03.74-.24 1.02l-2.21 2.2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Call Us</div>
                                <div class="sgdxp-contact-details">
                                    <a href="tel:+917494010101" class="sgdxp-call-number" style="color:#e6edf8;text-decoration:none;">+91 74-94-01-01-01</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sgdxp-request-btn">
                    <button type="button" id="open-callback-modal-btn">Request Callback</button>
                </div>
            </aside>

        </div>
    </div>

    <!-- SECTION 2: USP CARD + OVERVIEW + SPECIFICATIONS -->
    <section id="sg2-section">
        <div class="sg2-row">
            <div>
                <!-- USP Card -->
                <div class="sg2-usp-card">
                    <div class="sg2-usp-grid">
                        <div>
                            <div class="sg2-usp-item-label">Type</div>
                            <div class="sg2-usp-item-value">{{ $property->propertyType->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Configuration</div>
                            <div class="sg2-usp-item-value">{{ $property->bhk->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Area</div>
                            <div class="sg2-usp-item-value">
                                {{ $property->carpet_area ? number_format($property->carpet_area) . ' Sq ft' : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Possession</div>
                            <div class="sg2-usp-item-value">
                                {{ $property->specifications->possession_date ?? 'On Request' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Property Overview -->
                <h2 class="sg2-title-main">Property Overview</h2>
                <hr class="sg2-hr">
                <p class="sg2-overview-text">
                    {{ $property->description }}
                </p>

                @if ($property->amenities->count() > 0)
                    <h3 class="sg2-subtitle">Top Reasons to Invest</h3>
                    <ul class="sg2-reasons">
                        @foreach ($property->amenities->take(5) as $amenity)
                            <li>
                                <span class="sg2-bullet-icon">
                                    <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                        <path fill="#b39359"
                                            d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                                    </svg>
                                </span>
                                <span>{{ $amenity->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <!-- Specifications Table -->
                @if ($property->specifications)
                    <h2 class="sg2-title-main">Specifications</h2>
                    <hr class="sg2-hr">

                    <div class="apw-table-wrap">
                        <table class="apw-table">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Attributes</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $srNo = 1; @endphp
                                @if ($property->specifications->rera_number)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>RERA Number</td>
                                        <td>{{ $property->specifications->rera_number }}</td>
                                    </tr>
                                @endif
                                @if ($property->specifications->total_floors)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>Total Floors</td>
                                        <td>{{ $property->specifications->total_floors }}</td>
                                    </tr>
                                @endif
                                @if ($property->specifications->floor_number)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>Floor Number</td>
                                        <td>{{ $property->specifications->floor_number }}</td>
                                    </tr>
                                @endif
                                @if ($property->specifications->facing)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>Facing</td>
                                        <td>{{ $property->specifications->facing }}</td>
                                    </tr>
                                @endif
                                @if ($property->specifications->furnishing_status)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>Furnishing Status</td>
                                        <td>{{ $property->specifications->furnishing_status }}</td>
                                    </tr>
                                @endif
                                @if ($property->specifications->parking)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>Parking</td>
                                        <td>{{ $property->specifications->parking }}</td>
                                    </tr>
                                @endif
                                @if ($property->specifications->possession_date)
                                    <tr>
                                        <td>{{ $srNo++ }}</td>
                                        <td>Possession Date</td>
                                        <td>{{ $property->specifications->possession_date }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($property->show_hidden_details && $property->hidden_details)
                    <div class="sg2-overview-text">
                        {!! $property->hidden_details !!}
                    </div>
                @endif
            </div>

            <!-- Request Callback Form -->
            <aside class="sg2-form-card">
                {{-- Video inside contact card --}}
                @if ($property->youtube_url || $property->video_path)
                    <div style="margin-top:18px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.1);">
                        <h3
                            style="font-size:15px;font-weight:600;color:#f4e1bc;margin:0 0 10px;text-transform:uppercase;letter-spacing:.05em;">
                            Property Video</h3>
                        @if ($property->youtube_url)
                            @php
                                preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $property->youtube_url, $pm);
                                $ytId = $pm[1] ?? null;
                            @endphp
                            @if ($ytId)
                                <div style="aspect-ratio:16/9;border-radius:12px;overflow:hidden;">
                                    <iframe style="width:100%;height:100%;border:0;"
                                        src="https://www.youtube.com/embed/{{ $ytId }}" allowfullscreen
                                        loading="lazy"></iframe>
                                </div>
                            @endif
                        @elseif($property->video_path)
                            <video controls style="width:100%;border-radius:12px;">
                                <source src="{{ asset('storage/' . $property->video_path) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                @endif
                <h2 class="sg2-form-title">Request a Callback</h2>
                <p class="sg2-form-subtext">Share your details and our team will call you with floor plans, pricing and
                    exclusive offers.</p>

                <!-- Success Message -->
                <div id="callback-success-message"
                    style="display:none;background:#10b981;color:#fff;padding:12px;border-radius:8px;margin-bottom:16px;font-size:14px;">
                    Thank you! We'll contact you shortly.
                </div>

                <!-- Error Message -->
                <div id="callback-error-message"
                    style="display:none;background:#ef4444;color:#fff;padding:12px;border-radius:8px;margin-bottom:16px;font-size:14px;">
                    Something went wrong. Please try again.
                </div>

                <form id="callback-form" action="{{ route('inquiries.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <div class="sg2-form-group">
                        <input type="text" name="name" class="sg2-input" placeholder="Your Name" required>
                    </div>
                    <div class="sg2-form-group">
                        <input type="tel" name="phone" class="sg2-input" placeholder="Phone Number" required>
                    </div>
                    <div class="sg2-form-group">
                        <input type="email" name="email" class="sg2-input" placeholder="Email">
                    </div>
                    <div class="sg2-form-group">
                        <textarea name="message" class="sg2-textarea" placeholder="I am interested in {{ $property->title }}..."></textarea>
                    </div>
                    <div class="sg2-btn-wrap">
                        <button type="submit" class="sg2-btn" id="callback-submit-btn">
                            <span class="btn-text">Get Best Price</span>
                            <span class="btn-loading" style="display:none;">
                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </div>
                </form>
            </aside>
        </div>
    </section>

    <!-- GALLERY + SIMILAR PROPERTIES -->
    <section id="sg-gallery-similar">
        <div class="sg-gs-row">
            <div class="sg-gallery-box">
                <h2 class="sg-gallery-title">Gallery</h2>
                <hr class="sg2-hr">

                <div class="sg-slider">
                    @if ($property->images->count() > 0)
                        @foreach ($property->images as $image)
                            <div class="sg-slide">
                                <img src="{{ asset($image->image_path) }}"
                                    alt="{{ $image->alt_tag ?? $property->title }}">
                            </div>
                        @endforeach
                    @else
                        <div class="sg-slide">
                            <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=70"
                                alt="{{ $property->title }}">
                        </div>
                    @endif

                    <div class="sg-prev" onclick="sgPlusSlides(-1)">‹</div>
                    <div class="sg-next" onclick="sgPlusSlides(1)">›</div>
                </div>
            </div>

            <div class="sg-similar-box">
                <h2 class="sg-similar-title">Similar Properties</h2>

                @forelse($similarProperties as $similar)
                    <div class="sg-similar-card">
                        @if ($similar->mainImage)
                            <img src="{{ asset($similar->mainImage->image_path) }}"
                                alt="{{ $similar->mainImage->alt_tag ?? $similar->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=400&q=70"
                                alt="{{ $similar->title }}">
                        @endif
                        <div class="sg-similar-name">{{ $similar->title }}</div>

                        <div class="sg-similar-info-grid">
                            <div>
                                <div class="sg-similar-label">Location</div>{{ $similar->city->name ?? 'N/A' }}
                            </div>
                            <div>
                                <div class="sg-similar-label">Type</div>{{ $similar->propertyType->name ?? 'N/A' }}
                            </div>
                            <div>
                                <div class="sg-similar-label">Price</div>{{ $similar->formatted_price }}
                            </div>
                            <div>
                                <div class="sg-similar-label">Area</div>
                                {{ $similar->carpet_area ? number_format($similar->carpet_area) . ' Sq ft' : 'N/A' }}
                            </div>
                        </div>

                        @if ($similar->propertyType)
                            <div class="sg-badge">{{ $similar->propertyType->name }}</div>
                        @endif
                    </div>
                @empty
                    <p style="color:#d0deeb;font-size:14px;">No similar properties found.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ + MAP -->
    <section id="newRowFaqMap">
        <div class="newRow">
            <div>
                <h2 class="nr-faq-title">Frequently Asked Questions</h2>
                <hr class="sg2-hr">
                <div class="nr-faq-box">
                    @forelse($property->faqs as $faq)
                        <details class="nr-faq-item">
                            <summary>{{ $faq->question }}</summary>
                            <div class="nr-faq-body">{{ $faq->answer }}</div>
                        </details>
                    @empty
                        <details class="nr-faq-item">
                            <summary>What is the location of the project?</summary>
                            <div class="nr-faq-body">
                                The project is located at {{ $property->address }}@isset($property->location)
                                , {{ $property->location->name }}
                                @endisset @isset($property->city)
                                , {{ $property->city->name }}
                            @endisset.
                        </div>
                    </details>
                    <details class="nr-faq-item">
                        <summary>Which configurations are available?</summary>
                        <div class="nr-faq-body">
                            {{ $property->bhk ? $property->bhk->name : 'Various configurations' }} are available in
                            this property.</div>
                    </details>
                    <details class="nr-faq-item">
                        <summary>When is the possession?</summary>
                        <div class="nr-faq-body">
                            {{ $property->specifications->possession_date ?? 'Please contact us for possession details.' }}
                        </div>
                    </details>
                    <details class="nr-faq-item">
                        <summary>Does the project include amenities?</summary>
                        <div class="nr-faq-body">Yes, it includes
                            {{ $property->amenities->pluck('name')->take(5)->implode(', ') }}{{ $property->amenities->count() > 5 ? ' and more' : '' }}.
                        </div>
                    </details>
                    <details class="nr-faq-item">
                        <summary>How do I request pricing or a site visit?</summary>
                        <div class="nr-faq-body">You can request a callback using the form on this page or contact our
                            sales team directly at +91 74-94-01-01-01.</div>
                    </details>
                @endforelse
            </div>
        </div>

        <aside class="nr-map-card">
            <h3 class="nr-map-title">Property Location</h3>
            <p class="nr-map-address">
                <strong>Address:</strong> {{ $property->address }}@isset($property->location)
                , {{ $property->location->name }}
                @endisset @isset($property->city)
                , {{ $property->city->name }}
            @endisset
        </p>
        <div class="nr-map-iframe">
            @if ($property->map_embed_code)
                <!-- Use custom embed code if available -->
                {!! $property->map_embed_code !!}
            @elseif($property->latitude && $property->longitude)
                <!-- Fallback: Use static Google Maps embed with coordinates -->
                <iframe
                    src="https://www.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&hl=es;z=14&output=embed"
                    width="100%" height="260" style="border:0;border-radius:14px;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            @else
                <!-- No map data available -->
                <div
                    style="width:100%;height:260px;border-radius:14px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                    <div style="text-align:center;">
                        <svg style="width:48px;height:48px;margin:0 auto 8px;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p>Map not available</p>
                    </div>
                </div>
            @endif
        </div>
    </aside>
</div>
</section>

<!-- Mobile Sidebar Stack -->
<div id="sg-mobile-sidebar-stack"></div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Popup Inquiry Form Logic
        const popupOverlay = document.getElementById('inquiry-popup-overlay');
        const popupForm = document.getElementById('popup-inquiry-form');
        const popupSubmitBtn = document.getElementById('popup-submit-btn');
        const popupBtnText = popupSubmitBtn.querySelector('.popup-btn-text');
        const popupBtnLoading = popupSubmitBtn.querySelector('.popup-btn-loading');
        const popupSuccessMsg = document.getElementById('popup-success-message');
        const popupErrorMsg = document.getElementById('popup-error-message');

        // Check if user has already submitted inquiry for this property
        const propertyId = '{{ $property->id }}';
        const storageKey = 'property_inquiry_submitted_' + propertyId;
        const hasSubmittedLocal = localStorage.getItem(storageKey);

        // Close popup button handler
        const popupCloseBtn = document.getElementById('popup-close-btn');
        if (popupCloseBtn) {
            popupCloseBtn.addEventListener('click', function() {
                popupOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
        }

        // Close popup when clicking overlay background
        popupOverlay.addEventListener('click', function(e) {
            if (e.target === popupOverlay) {
                popupOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // If already submitted locally, don't show popup
        if (hasSubmittedLocal) {
            console.log('Already submitted (localStorage)');
            return;
        }

        // Check with server if visitor has submitted inquiry
        fetch('{{ route('inquiries.checkSubmission') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    property_id: propertyId
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server check response:', data);
                if (data.success && data.has_submitted) {
                    // Store in localStorage to avoid future server checks
                    localStorage.setItem(storageKey, 'true');
                    console.log('Already submitted (server), hiding popup');
                    // Make sure popup stays hidden
                    popupOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                } else if (data.success && !data.has_submitted) {
                    // Show popup if user hasn't submitted inquiry yet (after 5s delay)
                    console.log('Not submitted yet, scheduling popup');
                    setTimeout(() => {
                        document.body.style.overflow = 'hidden';
                        popupOverlay.classList.remove('hidden');
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error checking submission:', error);
                // Don't show popup on error - better UX
                popupOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });

        // Handle popup form submission
        popupForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Store submission in localStorage IMMEDIATELY to prevent popup from showing again
            // even if page reloads or something goes wrong
            localStorage.setItem(storageKey, 'true');

            // Hide previous messages
            popupSuccessMsg.style.display = 'none';
            popupErrorMsg.style.display = 'none';

            // Show loading state
            popupBtnText.style.display = 'none';
            popupBtnLoading.style.display = 'inline-block';
            popupSubmitBtn.disabled = true;

            // Get form data
            const formData = new FormData(popupForm);
            // Add inquiry type for popup form
            formData.append('inquiry_type', 'popup_inquiry');

            // Submit via fetch
            fetch(popupForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    popupBtnText.style.display = 'inline-block';
                    popupBtnLoading.style.display = 'none';
                    popupSubmitBtn.disabled = false;

                    if (data.success) {
                        // Show success message
                        popupSuccessMsg.style.display = 'block';

                        // Close popup and allow scrolling after 2 seconds
                        setTimeout(() => {
                            popupOverlay.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }, 2000);
                    } else {
                        // Show error message
                        popupErrorMsg.textContent = data.message ||
                            'Something went wrong. Please try again.';
                        popupErrorMsg.style.display = 'block';

                        // If already submitted, don't allow retry
                        if (data.already_submitted) {
                            setTimeout(() => {
                                popupOverlay.classList.add('hidden');
                                document.body.style.overflow = 'auto';
                            }, 3000);
                        } else {
                            // Hide error message after 5 seconds
                            setTimeout(() => {
                                popupErrorMsg.style.display = 'none';
                            }, 5000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Reset button state
                    popupBtnText.style.display = 'inline-block';
                    popupBtnLoading.style.display = 'none';
                    popupSubmitBtn.disabled = false;

                    // Show error message
                    popupErrorMsg.textContent = 'Something went wrong. Please try again.';
                    popupErrorMsg.style.display = 'block';

                    // Hide error message after 5 seconds
                    setTimeout(() => {
                        popupErrorMsg.style.display = 'none';
                    }, 5000);
                });
        });

        // Callback Modal Logic - Simple and Direct
        console.log('Setting up callback modal...');

        const openCallbackModalBtn = document.getElementById('open-callback-modal-btn');
        const callbackModalOverlay = document.getElementById('callback-modal-overlay');

        console.log('Button found:', openCallbackModalBtn);
        console.log('Modal found:', callbackModalOverlay);

        // Open callback modal - ALWAYS attach this handler
        if (openCallbackModalBtn && callbackModalOverlay) {
            console.log('Attaching click handler to button');
            openCallbackModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Button clicked! Opening modal...');
                document.body.style.overflow = 'hidden';
                callbackModalOverlay.classList.remove('hidden');
            });
        } else {
            console.error('Button or modal not found!', {
                button: openCallbackModalBtn,
                modal: callbackModalOverlay
            });
        }

        const callbackModalForm = document.getElementById('callback-modal-form');
        const callbackModalSubmitBtn = document.getElementById('callback-modal-submit-btn');
        const callbackModalCloseBtn = document.getElementById('callback-modal-close-btn');
        const callbackModalSuccessMsg = document.getElementById('callback-modal-success-message');
        const callbackModalErrorMsg = document.getElementById('callback-modal-error-message');

        // Close callback modal
        if (callbackModalCloseBtn && callbackModalOverlay) {
            callbackModalCloseBtn.addEventListener('click', function() {
                callbackModalOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
                if (callbackModalForm) callbackModalForm.reset();
                if (callbackModalSuccessMsg) callbackModalSuccessMsg.style.display = 'none';
                if (callbackModalErrorMsg) callbackModalErrorMsg.style.display = 'none';
            });
        }

        // Close modal when clicking outside
        if (callbackModalOverlay) {
            callbackModalOverlay.addEventListener('click', function(e) {
                if (e.target === callbackModalOverlay) {
                    callbackModalOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    if (callbackModalForm) callbackModalForm.reset();
                    if (callbackModalSuccessMsg) callbackModalSuccessMsg.style.display = 'none';
                    if (callbackModalErrorMsg) callbackModalErrorMsg.style.display = 'none';
                }
            });
        }

        // Handle callback modal form submission
        if (callbackModalForm && callbackModalSubmitBtn) {
            const callbackModalBtnText = callbackModalSubmitBtn.querySelector('.popup-btn-text');
            const callbackModalBtnLoading = callbackModalSubmitBtn.querySelector('.popup-btn-loading');

            callbackModalForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Hide previous messages
                if (callbackModalSuccessMsg) callbackModalSuccessMsg.style.display = 'none';
                if (callbackModalErrorMsg) callbackModalErrorMsg.style.display = 'none';

                // Show loading state
                if (callbackModalBtnText) callbackModalBtnText.style.display = 'none';
                if (callbackModalBtnLoading) callbackModalBtnLoading.style.display = 'inline-block';
                callbackModalSubmitBtn.disabled = true;

                // Get form data
                const formData = new FormData(callbackModalForm);
                formData.append('inquiry_type', 'call_back');

                // Submit via fetch
                fetch(callbackModalForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button state
                        if (callbackModalBtnText) callbackModalBtnText.style.display =
                            'inline-block';
                        if (callbackModalBtnLoading) callbackModalBtnLoading.style.display = 'none';
                        callbackModalSubmitBtn.disabled = false;

                        if (data.success) {
                            // Show success message
                            if (callbackModalSuccessMsg) callbackModalSuccessMsg.style.display =
                                'block';

                            // Reset form
                            callbackModalForm.reset();

                            // Close modal after 2 seconds
                            setTimeout(() => {
                                if (callbackModalOverlay) callbackModalOverlay.classList
                                    .add('hidden');
                                document.body.style.overflow = 'auto';
                                if (callbackModalSuccessMsg) callbackModalSuccessMsg.style
                                    .display = 'none';
                            }, 2000);
                        } else {
                            // Show error message
                            if (callbackModalErrorMsg) {
                                callbackModalErrorMsg.textContent = data.message ||
                                    'Something went wrong. Please try again.';
                                callbackModalErrorMsg.style.display = 'block';
                            }

                            // Hide error message after 5 seconds
                            setTimeout(() => {
                                if (callbackModalErrorMsg) callbackModalErrorMsg.style
                                    .display = 'none';
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Reset button state
                        if (callbackModalBtnText) callbackModalBtnText.style.display =
                            'inline-block';
                        if (callbackModalBtnLoading) callbackModalBtnLoading.style.display = 'none';
                        callbackModalSubmitBtn.disabled = false;

                        // Show error message
                        if (callbackModalErrorMsg) {
                            callbackModalErrorMsg.textContent =
                                'Something went wrong. Please try again.';
                            callbackModalErrorMsg.style.display = 'block';
                        }

                        // Hide error message after 5 seconds
                        setTimeout(() => {
                            if (callbackModalErrorMsg) callbackModalErrorMsg.style.display =
                                'none';
                        }, 5000);
                    });
            });
        }


        // Gallery slider
        let sgIndex = 1;
        sgShowSlides(sgIndex);

        window.sgPlusSlides = function(n) {
            sgShowSlides(sgIndex += n);
        }

        function sgShowSlides(n) {
            const slides = document.getElementsByClassName("sg-slide");
            if (!slides.length) return;

            if (n > slides.length) sgIndex = 1;
            if (n < 1) sgIndex = slides.length;

            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[sgIndex - 1].style.display = "block";
        }

        // Mobile sidebar clones
        const bp = window.matchMedia("(max-width: 992px)");
        const stack = document.getElementById("sg-mobile-sidebar-stack");

        const sidebarSelectors = [{
                sel: ".sgdxp-contact-card"
            },
            {
                sel: ".sg2-form-card"
            },
            {
                sel: ".sg-similar-box"
            },
            {
                sel: ".nr-map-card"
            }
        ];

        function clearStack() {
            if (stack) stack.innerHTML = "";
        }

        function buildStack() {
            if (!stack) return;
            if (stack.querySelector("[data-sg-mobile-clone='1']")) return;

            clearStack();

            sidebarSelectors.forEach(item => {
                const original = document.querySelector(item.sel);
                if (!original) return;

                const clone = original.cloneNode(true);
                const wrap = document.createElement("div");
                wrap.className = "sg-mobile-stack-item";
                wrap.setAttribute("data-sg-mobile-clone", "1");
                wrap.appendChild(clone);
                stack.appendChild(wrap);
            });

            const stickyClone = stack.querySelector(".sg2-form-card");
            if (stickyClone) {
                stickyClone.style.position = "static";
                stickyClone.style.top = "auto";
            }

            // Re-attach AJAX handler to cloned form
            const clonedForm = stack.querySelector("#callback-form");
            if (clonedForm) {
                attachFormHandler(clonedForm);
            }

            // Re-attach callback modal button handler to cloned button
            const clonedCallbackBtn = stack.querySelector("#open-callback-modal-btn");
            if (clonedCallbackBtn) {
                console.log('Attaching handler to cloned button');
                clonedCallbackBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Cloned button clicked!');
                    document.body.style.overflow = 'hidden';
                    callbackModalOverlay.classList.remove('hidden');
                });
            }
        }

        function handle() {
            if (bp.matches) buildStack();
            else clearStack();
        }

        handle();
        if (bp.addEventListener) bp.addEventListener("change", handle);
        else bp.addListener(handle);

        // AJAX Form Submission
        function attachFormHandler(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = form.querySelector('#callback-submit-btn');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoading = submitBtn.querySelector('.btn-loading');
                const successMsg = form.parentElement.querySelector('#callback-success-message');
                const errorMsg = form.parentElement.querySelector('#callback-error-message');

                // Hide previous messages
                if (successMsg) successMsg.style.display = 'none';
                if (errorMsg) errorMsg.style.display = 'none';

                // Show loading state
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-block';
                submitBtn.disabled = true;

                // Get form data
                const formData = new FormData(form);

                // Submit via fetch
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button state
                        btnText.style.display = 'inline-block';
                        btnLoading.style.display = 'none';
                        submitBtn.disabled = false;

                        if (data.success) {
                            // Show success message
                            if (successMsg) {
                                successMsg.textContent = data.message ||
                                    'Thank you! We\'ll contact you shortly.';
                                successMsg.style.display = 'block';
                            }

                            // Reset form
                            form.reset();

                            // Hide success message after 5 seconds
                            setTimeout(() => {
                                if (successMsg) successMsg.style.display = 'none';
                            }, 5000);
                        } else {
                            // Show error message
                            if (errorMsg) {
                                errorMsg.textContent = data.message ||
                                    'Something went wrong. Please try again.';
                                errorMsg.style.display = 'block';
                            }

                            // Hide error message after 5 seconds
                            setTimeout(() => {
                                if (errorMsg) errorMsg.style.display = 'none';
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Reset button state
                        btnText.style.display = 'inline-block';
                        btnLoading.style.display = 'none';
                        submitBtn.disabled = false;

                        // Show error message
                        if (errorMsg) {
                            errorMsg.textContent = 'Something went wrong. Please try again.';
                            errorMsg.style.display = 'block';
                        }

                        // Hide error message after 5 seconds
                        setTimeout(() => {
                            if (errorMsg) errorMsg.style.display = 'none';
                        }, 5000);
                    });
            });
        }

        // Attach handler to original form
        const originalForm = document.querySelector("#callback-form");
        if (originalForm) {
            attachFormHandler(originalForm);
        }
    });
</script>

<style>
@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endsection
