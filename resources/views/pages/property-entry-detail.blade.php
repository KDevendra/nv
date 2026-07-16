@extends('layouts.app')

@section('title', ($entry->property_name ?? $entry->facility_type ?? 'Property') . ' - ' . ($entry->nearest_city ?? '') . ' - ZendoIndia')
@section('description', Str::limit($entry->name_full_address ?? $entry->remarks ?? '', 160))

@php
    // Field visibility helper function
    $canShowField = function($fieldKey) use ($fieldConfigs, $userHasSubmittedInquiry) {
        $config = $fieldConfigs->get($fieldKey);
        if (!$config) return true; // Show if no config exists (reverted back to true since all fields are now configured)
        
        // If user has submitted inquiry, show fields marked as show_after_verification
        if ($userHasSubmittedInquiry && $config->show_after_verification) {
            return true;
        }
        
        // Otherwise, only show fields marked as show_on_website
        return $config->show_on_website;
    };
    
    // Check if we should show inquiry prompt:
    // - Show to guests (not logged in)
    // - Show to authenticated users who haven't submitted inquiry
    $showInquiryPrompt = !$userHasSubmittedInquiry;
    
    // Count how many fields are hidden
    $hiddenFieldsCount = 0;
    foreach($fieldConfigs as $key => $config) {
        if (!$canShowField($key) && $config->show_after_verification) {
            $hiddenFieldsCount++;
        }
    }

    // dd($entry);
@endphp

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
        
        .locked-field-notice {
            background: linear-gradient(135deg, #0B2C3D 0%, #1a4a62 100%);
            border: 2px solid #B39359;
            border-radius: 12px;
            padding: 30px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(179, 147, 89, 0.2);
        }
        
        .locked-field-notice h3 {
            color: #B39359;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            font-family: 'Forum', cursive;
        }
        
        .locked-field-notice p {
            color: #e6edf8;
            margin-bottom: 20px;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .locked-field-notice button {
            background: linear-gradient(135deg, #B39359 0%, #8b7444 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(179, 147, 89, 0.3);
        }
        
        .locked-field-notice button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(179, 147, 89, 0.4);
            background: linear-gradient(135deg, #c4a566 0%, #9a8350 100%);
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

    <!-- Request Callback Modal -->
    <div id="callback-modal-overlay" class="inquiry-popup-overlay hidden">
        <div class="inquiry-popup-content">
            <button type="button" id="callback-modal-close-btn-x"
                style="position:absolute;top:12px;right:12px;width:32px;height:32px;border-radius:50%;background:#ef4444;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(239,68,68,0.3);transition:all 0.2s ease;z-index:10;"
                onmouseover="this.style.background='#dc2626';this.style.transform='scale(1.1)'"
                onmouseout="this.style.background='#ef4444';this.style.transform='scale(1)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"
                    stroke-linecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
            <div class="inquiry-popup-header">
                <h5 class="inquiry-popup-title">Request a Callback</h5>
                <p class="inquiry-popup-subtitle">Share your details and our team will call you with floor plans, pricing and exclusive offers.</p>
            </div>

            <div id="callback-modal-success-message" class="popup-message success">
                Thank you! We'll contact you shortly.
            </div>
            <div id="callback-modal-error-message" class="popup-message error">
                Something went wrong. Please try again.
            </div>

            <form id="callback-modal-form" action="{{ route('inquiries.store') }}" method="POST">
                @csrf
                <input type="hidden" name="property_entry_code" value="{{ $entry->code }}">

                <div class="popup-form-group">
                    <label class="popup-form-label">Your Name *</label>
                    <input type="text" name="name" class="popup-form-input" placeholder="Enter your full name" required>
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Phone Number *</label>
                    <input type="tel" name="phone" class="popup-form-input" placeholder="Enter your phone number" required>
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Email Address</label>
                    <input type="email" name="email" class="popup-form-input" placeholder="Enter your email (optional)">
                </div>

                <div class="popup-form-group">
                    <label class="popup-form-label">Message</label>
                    <textarea name="message" class="popup-form-textarea" placeholder="I am interested in {{ $entry->facility_type }} - {{ $entry->code }}..."></textarea>
                </div>

                <button type="submit" class="popup-submit-btn" id="callback-modal-submit-btn">
                    <span class="popup-btn-text">Submit Request</span>
                    <span class="popup-btn-loading" style="display:none;">
                        <span class="popup-loading-spinner"></span>
                        Submitting...
                    </span>
                </button>

                <button type="button" class="popup-submit-btn" id="callback-modal-close-btn" style="background: #6b7280; margin-top: 10px;">
                    Close
                </button>
            </form>
        </div>
    </div>

    <!-- BANNER -->
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div class="about-banner-left">
                <h1 class="about-banner-heading">{{ $entry->property_name ?? $entry->facility_type ?? 'Property Details' }}</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('properties.index') }}">Properties</a>
                    <span>/</span>
                    <p>{{ $entry->code }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROPERTY HERO SECTION -->
    <div id="sgdxp-page">
        <div class="sgdxp-header-row">
            <div class="sgdxp-header-left">
                <div class="sgdxp-badges">
                    @if($entry->deal_type)
                        <span class="sgdxp-badge sgdxp-badge-status">{{ $entry->deal_type }}</span>
                    @endif
                    <span class="sgdxp-badge sgdxp-badge-status">Verified</span>
                </div>
                <h1 class="sgdxp-title">{{ $entry->property_name ?? Str::limit($entry->name_full_address ?? $entry->facility_type, 80) }}</h1>
                <div class="sgdxp-location-line">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                        <path fill="#b39359" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                    </svg>
                    <span>{{ $entry->name_full_address }}</span>
                </div>
            </div>

            <div class="sgdxp-header-right">
                @if($entry->expected_rent)
                    <div class="sgdxp-starting-price-label">Expected Rent</div>
                    <div class="sgdxp-starting-price-value">₹{{ number_format($entry->expected_rent, 2) }}/sq ft/mo</div>
                @elseif($entry->expected_sale_price)
                    <div class="sgdxp-starting-price-label">Expected Sale Price</div>
                    <div class="sgdxp-starting-price-value">₹{{ number_format($entry->expected_sale_price / 100000, 2) }} Lac</div>
                @else
                    <div class="sgdxp-starting-price-label">Price</div>
                    <div class="sgdxp-starting-price-value">On Request</div>
                @endif
            </div>
        </div>

        <div id="sgdxp-main">
            <div class="sgdxp-image-card">
                <div class="sgdxp-image-wrapper">
                    @if($entry->photos->count() > 0)
                        <img src="{{ asset('images/property_photos/' . basename($entry->photos->first()->file_path)) }}" alt="{{ $entry->facility_type }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=70" alt="{{ $entry->facility_type }}">
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
                                    <path fill="#b39359" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
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
                                    <path fill="#b39359" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
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
                                    <path fill="#b39359" d="M6.62 10.79a15.093 15.093 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.07 21 3 13.93 3 5c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.11.35.03.74-.24 1.02l-2.21 2.2z" />
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
                            <div class="sg2-usp-item-value">{{ $entry->facility_type ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Area</div>
                            <div class="sg2-usp-item-value">
                                {{ $entry->available_area ? number_format($entry->available_area) . ' ' . str_replace('_', ' ', $entry->area_unit ?? 'sq ft') : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Clear Height</div>
                            <div class="sg2-usp-item-value">{{ $entry->clear_height_highest ? $entry->clear_height_highest . ' ft' : 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Possession</div>
                            <div class="sg2-usp-item-value">{{ $entry->available_from ? $entry->available_from->format('M Y') : 'On Request' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Property Overview -->
                <h2 class="sg2-title-main">Property Overview</h2>
                <hr class="sg2-hr">
                @if($entry->remarks)
                <p class="sg2-overview-text">{{ $entry->remarks }}</p>
                @else
                <p class="sg2-overview-text">Premium {{ $entry->facility_type }} available for {{ $entry->deal_type ?? 'lease/sale' }} in {{ $entry->nearest_city }}. Contact us for detailed specifications and site visit.</p>
                @endif

                <!-- Key Features -->

                @if($entry->dock_door_count || $entry->power_sanctioned_kva || $entry->fire_noc)
                <h3 class="sg2-subtitle">Top Reasons to Invest</h3>
                <ul class="sg2-reasons">
                    @if($entry->dock_door_count)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>{{ $entry->dock_door_count }} Dock Doors</span>
                    </li>
                    @endif
                    @if($entry->power_sanctioned_kva)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>{{ $entry->power_sanctioned_kva }} KVA Power</span>
                    </li>
                    @endif
                    @if($entry->fire_noc === 'Yes')
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>Fire NOC Approved</span>
                    </li>
                    @endif
                    @if($entry->nearest_highway)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>Excellent Connectivity - {{ $entry->nearest_highway }}</span>
                    </li>
                    @endif
                    @if($entry->water_source)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>{{ $entry->water_source }} Water Supply</span>
                    </li>
                    @endif
                </ul>
                @endif

                <!-- Specifications Table -->
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
                            @if($canShowField('facility_type'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Facility Type</td>
                                <td>{{ $entry->facility_type ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('property_name'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Property Name</td>
                                <td>{{ $entry->property_name ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('plot_area'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Plot Area</td>
                                <td>{{ $entry->plot_area ? number_format($entry->plot_area) . ' ' . str_replace('_', ' ', $entry->area_unit ?? 'sq ft') : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('built_up_area'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Built-up Area</td>
                                <td>{{ $entry->built_up_area ? number_format($entry->built_up_area) . ' ' . str_replace('_', ' ', $entry->area_unit ?? 'sq ft') : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('carpet_area'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Carpet Area</td>
                                <td>{{ $entry->carpet_area ? number_format($entry->carpet_area) . ' ' . str_replace('_', ' ', $entry->area_unit ?? 'sq ft') : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('available_area'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Available Area</td>
                                <td>{{ $entry->available_area ? number_format($entry->available_area) . ' ' . str_replace('_', ' ', $entry->area_unit ?? 'sq ft') : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('clear_height_highest'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Clear Height (Highest)</td>
                                <td>{{ $entry->clear_height_highest ? $entry->clear_height_highest . ' ft' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('clear_height_side'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Clear Height (Side)</td>
                                <td>{{ $entry->clear_height_side ? $entry->clear_height_side . ' ft' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('number_of_floors'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Number of Floors</td>
                                <td>{{ $entry->number_of_floors ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('dock_door_count'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Doors</td>
                                <td>{{ $entry->dock_door_count ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('dock_type'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Type</td>
                                <td>{{ $entry->dock_type ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('dock_height'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Height</td>
                                <td>{{ $entry->dock_height ? $entry->dock_height . ' ft' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('power_sanctioned_kva'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Power Sanctioned</td>
                                <td>{{ $entry->power_sanctioned_kva ?? 'N/A' }} KVA</td>
                            </tr>
                            @endif
                            @if($canShowField('discom_name'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>DISCOM</td>
                                <td>{{ $entry->discom_name ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('water_source'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Water Source</td>
                                <td>{{ $entry->water_source ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('fire_fighting_system'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Fire Fighting System</td>
                                <td>{{ $entry->fire_fighting_system ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('fire_noc'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Fire NOC</td>
                                <td>{{ $entry->fire_noc ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('pollution_noc'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Pollution NOC</td>
                                <td>{{ $entry->pollution_noc ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('occupancy_certificate'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Occupancy Certificate</td>
                                <td>{{ $entry->occupancy_certificate ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('no_of_offices'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Number of Offices</td>
                                <td>{{ $entry->no_of_offices ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            @if($canShowField('canteen'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Canteen</td>
                                <td>{{ $entry->canteen ? 'Yes' : ($entry->canteen === '0' || $entry->canteen === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('washrooms'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Washrooms</td>
                                <td>{{ $entry->washrooms ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('flooring_type'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Flooring Type</td>
                                <td>{{ $entry->flooring_type ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('tenure'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Tenure</td>
                                <td>{{ $entry->tenure ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('nearest_city'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest City</td>
                                <td>{{ $entry->nearest_city ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('nearest_highway'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Highway</td>
                                <td>{{ $entry->nearest_highway ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('nearest_railway_station'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Railway Station</td>
                                <td>{{ $entry->nearest_railway_station ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('nearest_airport'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Airport</td>
                                <td>{{ $entry->nearest_airport ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('expected_rent'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Expected Rent</td>
                                <td>{{ $entry->expected_rent ? '₹' . number_format($entry->expected_rent, 2) . ' /sq ft/month' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('expected_sale_price'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Expected Sale Price</td>
                                <td>{{ $entry->expected_sale_price ? '₹' . number_format($entry->expected_sale_price / 100000, 2) . ' Lac' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('security_deposit_months'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Security Deposit</td>
                                <td>{{ $entry->security_deposit_months ?? 'N/A' }} months</td>
                            </tr>
                            @endif
                            @if($canShowField('lock_in_years'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Lock-in Period</td>
                                <td>{{ $entry->lock_in_years ?? 'N/A' }} years</td>
                            </tr>
                            @endif
                            @if($canShowField('available_from'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Available From</td>
                                <td>{{ $entry->available_from ? $entry->available_from->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section A remaining fields --}}
                            @if($canShowField('name_full_address'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Full Address</td>
                                <td>{{ $entry->name_full_address ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('village'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Village</td>
                                <td>{{ $entry->village ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('tehsil'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Tehsil</td>
                                <td>{{ $entry->tehsil ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('district'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>District</td>
                                <td>{{ $entry->district ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('state'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>State</td>
                                <td>{{ $entry->state ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('country'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Country</td>
                                <td>{{ $entry->country ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('postal_address_pin'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>PIN Code</td>
                                <td>{{ $entry->postal_address_pin ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('owner_contact_name'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Owner Name</td>
                                <td>{{ $entry->owner_contact_name ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('owner_contact_phone'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Owner Contact Number</td>
                                <td>{{ $entry->owner_contact_phone ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('owner_email'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Owner E-mail</td>
                                <td>{{ $entry->owner_email ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section B remaining fields --}}
                            @if($canShowField('approved_land_use'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Approved Land Use</td>
                                <td>{{ $entry->approved_land_use ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('clu_conversion_status'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>CLU / Conversion Status</td>
                                <td>{{ $entry->clu_conversion_status ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('pollution_category'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Pollution Category</td>
                                <td>{{ $entry->pollution_category ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section C remaining fields --}}
                            @if($canShowField('shed_width'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Shed Width</td>
                                <td>{{ $entry->shed_width ? $entry->shed_width . ' ft' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('shed_length'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Shed Length</td>
                                <td>{{ $entry->shed_length ? $entry->shed_length . ' ft' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('fsi_far'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>FSI / FAR</td>
                                <td>{{ $entry->fsi_far ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section D — Dock, Exit & Width Details (combined) --}}
                            @if($canShowField('dock_front') || $canShowField('dock_left') || $canShowField('dock_right') || $canShowField('dock_back'))
                            @php $dockDoors = collect(['Front' => $entry->dock_front, 'Left' => $entry->dock_left, 'Right' => $entry->dock_right, 'Back' => $entry->dock_back])->filter(); @endphp
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Doors by Direction</td>
                                <td>{{ $dockDoors->isEmpty() ? 'N/A' : $dockDoors->map(fn($v, $k) => "$k: $v")->join(', ') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('dock_leveller_front') || $canShowField('dock_leveller_left') || $canShowField('dock_leveller_right') || $canShowField('dock_leveller_back'))
                            @php $dockLevellers = collect(['Front' => $entry->dock_leveller_front, 'Left' => $entry->dock_leveller_left, 'Right' => $entry->dock_leveller_right, 'Back' => $entry->dock_leveller_back])->filter(); @endphp
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Levellers by Direction</td>
                                <td>{{ $dockLevellers->isEmpty() ? 'N/A' : $dockLevellers->map(fn($v, $k) => "$k: $v")->join(', ') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('fire_exit_front') || $canShowField('fire_exit_left') || $canShowField('fire_exit_right') || $canShowField('fire_exit_back'))
                            @php $fireExits = collect(['Front' => $entry->fire_exit_front, 'Left' => $entry->fire_exit_left, 'Right' => $entry->fire_exit_right, 'Back' => $entry->fire_exit_back])->filter(); @endphp
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Fire Exit Doors by Direction</td>
                                <td>{{ $fireExits->isEmpty() ? 'N/A' : $fireExits->map(fn($v, $k) => "$k: $v")->join(', ') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('canopy_width_front') || $canShowField('canopy_width_left') || $canShowField('canopy_width_right') || $canShowField('canopy_width_back'))
                            @php $canopyWidths = collect(['Front' => $entry->canopy_width_front, 'Left' => $entry->canopy_width_left, 'Right' => $entry->canopy_width_right, 'Back' => $entry->canopy_width_back])->filter(); @endphp
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Canopy Width by Direction</td>
                                <td>{{ $canopyWidths->isEmpty() ? 'N/A' : $canopyWidths->map(fn($v, $k) => "$k: $v")->join(', ') . ' ft' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('canopy_length_front') || $canShowField('canopy_length_left') || $canShowField('canopy_length_right') || $canShowField('canopy_length_back'))
                            @php $canopyLengths = collect(['Front' => $entry->canopy_length_front, 'Left' => $entry->canopy_length_left, 'Right' => $entry->canopy_length_right, 'Back' => $entry->canopy_length_back])->filter(); @endphp
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Canopy Length by Direction</td>
                                <td>{{ $canopyLengths->isEmpty() ? 'N/A' : $canopyLengths->map(fn($v, $k) => "$k: $v")->join(', ') . ' ft' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('road_width_front') || $canShowField('road_width_left') || $canShowField('road_width_right') || $canShowField('road_width_back'))
                            @php $roadWidths = collect(['Front' => $entry->road_width_front, 'Left' => $entry->road_width_left, 'Right' => $entry->road_width_right, 'Back' => $entry->road_width_back])->filter(); @endphp
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Road Width by Direction</td>
                                <td>{{ $roadWidths->isEmpty() ? 'N/A' : $roadWidths->map(fn($v, $k) => "$k: $v")->join(', ') . ' ft' }}</td>
                            </tr>
                            @endif

                            {{-- Section E remaining fields --}}
                            @if($canShowField('canteen_size'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Canteen Size</td>
                                <td>{{ $entry->canteen_size ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('stp_plant'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>STP Plant</td>
                                <td>{{ $entry->stp_plant ? 'Yes' : ($entry->stp_plant === '0' || $entry->stp_plant === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('stp_capacity'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>STP Capacity</td>
                                <td>{{ $entry->stp_capacity ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('no_of_urinals'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>No. of Urinals</td>
                                <td>{{ $entry->no_of_urinals ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('no_of_closets'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>No. of Closets</td>
                                <td>{{ $entry->no_of_closets ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('female_washroom'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Female Washroom</td>
                                <td>{{ $entry->female_washroom ? 'Yes' : ($entry->female_washroom === '0' || $entry->female_washroom === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('driver_rest_room'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Driver Rest Room</td>
                                <td>{{ $entry->driver_rest_room ? 'Yes' : ($entry->driver_rest_room === '0' || $entry->driver_rest_room === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('mezzanine'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Mezzanine</td>
                                <td>{{ $entry->mezzanine ? 'Yes' : ($entry->mezzanine === '0' || $entry->mezzanine === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('mezzanine_size'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Mezzanine Size</td>
                                <td>{{ $entry->mezzanine_size ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('structure_type'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Structure Type</td>
                                <td>{{ $entry->structure_type ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('ventilation_lighting'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Ventilation & Lighting</td>
                                <td>{{ $entry->ventilation_lighting ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('insulation_roof'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Roof Insulation</td>
                                <td>{{ $entry->insulation_roof ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('insulation_side'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Side Insulation</td>
                                <td>{{ $entry->insulation_side ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('fire_sprinkler'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Fire Sprinkler</td>
                                <td>{{ $entry->fire_sprinkler ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('scrap_yard'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Scrap Yard</td>
                                <td>{{ $entry->scrap_yard ? 'Yes' : ($entry->scrap_yard === '0' || $entry->scrap_yard === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif
                            @if($canShowField('no_of_companies_same_premise'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>No. of Companies in Same Premise</td>
                                <td>{{ $entry->no_of_companies_same_premise ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('extension_possible'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Extension Possible</td>
                                <td>{{ $entry->extension_possible ? 'Yes' : ($entry->extension_possible === '0' || $entry->extension_possible === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif

                            {{-- Section F remaining fields --}}
                            @if($canShowField('truck_movement'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Truck Movement</td>
                                <td>{{ $entry->truck_movement ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('office_cabin_area'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Office / Cabin Area</td>
                                <td>{{ $entry->office_cabin_area ? $entry->office_cabin_area . ' sq ft' : 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section G remaining fields --}}
                            @if($canShowField('water_tank_capacity'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Water Tank Capacity</td>
                                <td>{{ $entry->water_tank_capacity ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('solar'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Solar</td>
                                <td>{{ $entry->solar ? 'Yes' : ($entry->solar === '0' || $entry->solar === 0 ? 'No' : 'N/A') }}</td>
                            </tr>
                            @endif

                            {{-- Section H remaining field --}}
                            @if($canShowField('deal_type'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Lease / Sale Status</td>
                                <td>{{ $entry->deal_type ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section I — Surroundings & Environment --}}
                            @if($canShowField('approach_road_width'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Approach Road Width</td>
                                <td>{{ $entry->approach_road_width ? $entry->approach_road_width . ' ft' : 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('top_neighbouring_companies'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Top Neighbouring Companies</td>
                                <td>{{ $entry->top_neighbouring_companies ?? 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($canShowField('flood_risk'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Flood / Water-Logging Risk</td>
                                <td>{{ $entry->flood_risk ?? 'N/A' }}</td>
                            </tr>
                            @endif

                            {{-- Section J — Health & Emergency Nearby --}}
                            @if($canShowField('nearest_hospital_km'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Hospital</td>
                                <td>{{ $entry->nearest_hospital_km ?? 'N/A' }} km</td>
                            </tr>
                            @endif
                            @if($canShowField('nearest_fire_station_km'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Fire Station</td>
                                <td>{{ $entry->nearest_fire_station_km ?? 'N/A' }} km</td>
                            </tr>
                            @endif
                            @if($canShowField('nearest_police_station_km'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Police Station</td>
                                <td>{{ $entry->nearest_police_station_km ?? 'N/A' }} km</td>
                            </tr>
                            @endif

                            {{-- Section L — General Remarks --}}
                            @if($canShowField('remarks'))
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Remarks / Observations</td>
                                <td>{{ $entry->remarks ?? 'N/A' }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    @if($showInquiryPrompt && $hiddenFieldsCount > 0)
                        <div class="locked-field-notice">
                            <h3>🔒 Submit an Inquiry to View {{ $hiddenFieldsCount }}+ Additional Details</h3>
                            @if(auth()->check())
                                <p>Submit an inquiry to unlock complete property specifications and details.</p>
                            @else
                                <p>Submit an inquiry to create your account and unlock complete property specifications and details.</p>
                            @endif
                            <button type="button" onclick="document.getElementById('callback-modal-overlay').classList.remove('hidden')">
                                @auth
                                    Submit Inquiry to View More
                                @else
                                    Submit Inquiry & Create Account
                                @endauth
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY -->
    @if($entry->photos->count() > 0)
    <section id="sg-gallery-similar">
        <div class="sg-gs-row">
            <div class="sg-gallery-box">
                <h2 class="sg-gallery-title">Gallery</h2>
                <hr class="sg2-hr">

                <div class="sg-slider">
                    @foreach($entry->photos as $photo)
                        <div class="sg-slide">
                            <img src="{{ asset('images/property_photos/' . basename($photo->file_path)) }}" alt="{{ $entry->facility_type }}">
                        </div>
                    @endforeach

                    <div class="sg-prev" onclick="sgPlusSlides(-1)">‹</div>
                    <div class="sg-next" onclick="sgPlusSlides(1)">›</div>
                </div>
            </div>
        </div>
    </section>
    @endif

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gallery Slider
    let slideIndex = 1;
    showSlides(slideIndex);
    function sgPlusSlides(n) { showSlides(slideIndex += n); }
    function showSlides(n) {
        let slides = document.getElementsByClassName("sg-slide");
        if (!slides.length) return;
        if (n > slides.length) slideIndex = 1;
        if (n < 1) slideIndex = slides.length;
        for (let i = 0; i < slides.length; i++) slides[i].style.display = "none";
        slides[slideIndex - 1].style.display = "block";
    }
    window.sgPlusSlides = sgPlusSlides;

    // Callback Modal
    const callbackBtn = document.getElementById('open-callback-modal-btn');
    const callbackOverlay = document.getElementById('callback-modal-overlay');
    const callbackCloseBtn = document.getElementById('callback-modal-close-btn');
    const callbackCloseBtnX = document.getElementById('callback-modal-close-btn-x');
    const callbackForm = document.getElementById('callback-modal-form');
    const callbackSubmitBtn = document.getElementById('callback-modal-submit-btn');

    if (callbackBtn) {
        callbackBtn.addEventListener('click', () => {
            callbackOverlay.classList.remove('hidden');
        });
    }

    if (callbackCloseBtn) {
        callbackCloseBtn.addEventListener('click', () => {
            callbackOverlay.classList.add('hidden');
        });
    }

    if (callbackCloseBtnX) {
        callbackCloseBtnX.addEventListener('click', () => {
            callbackOverlay.classList.add('hidden');
        });
    }

    if (callbackOverlay) {
        callbackOverlay.addEventListener('click', (e) => {
            if (e.target === callbackOverlay) callbackOverlay.classList.add('hidden');
        });
    }

    // Form submission
    if (callbackForm) {
        callbackForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btnText = callbackSubmitBtn.querySelector('.popup-btn-text');
            const btnLoading = callbackSubmitBtn.querySelector('.popup-btn-loading');
            
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            callbackSubmitBtn.disabled = true;

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    document.getElementById('callback-modal-success-message').style.display = 'block';
                    callbackForm.reset();
                    
                    // If user was created/logged in, reload page to show more fields
                    if (data.reload_required || data.user_created) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        setTimeout(() => {
                            callbackOverlay.classList.add('hidden');
                            document.getElementById('callback-modal-success-message').style.display = 'none';
                        }, 2000);
                    }
                } else {
                    document.getElementById('callback-modal-error-message').style.display = 'block';
                    document.getElementById('callback-modal-error-message').textContent = data.message || 'Something went wrong. Please try again.';
                }
            } catch (error) {
                document.getElementById('callback-modal-error-message').style.display = 'block';
            } finally {
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
                callbackSubmitBtn.disabled = false;
            }
        });
    }
});
</script>
@endsection
