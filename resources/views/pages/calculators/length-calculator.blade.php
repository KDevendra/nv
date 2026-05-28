@extends('layouts.app')
@section('title', 'Length Calculator - ZendoIndia')
@section('content')
    <style>
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        .about-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .about-banner-left {
            max-width: 600px;
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
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .about-breadcrumb span {
            color: #ffffff;
        }

        .about-breadcrumb p {
            margin: 0;
            opacity: 0.8;
        }

        @media (max-width: 767px) {
            .about-banner-heading {
                font-size: 32px;
            }

            .about-breadcrumb {
                font-size: 14px;
            }

            .about-banner-section {
                padding: 100px 0 50px;
            }
        }
    </style>
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div class="about-banner-left">
                <h1 class="about-banner-heading">Length Calculator</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <p>Length Calculator</p>
                </div>
            </div>
        </div>
    </section>
    <style>
        #apw-lenCalcV2 {
            padding: 40px 20px 16px;
            overflow: hidden;
        }

        #apw-lenCalcV2 .apw-calcHubV2__wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        #apw-lenCalcV2 .apw-calcHubV2__layout {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
            gap: 20px;
            align-items: start;
        }

        #apw-lenCalcV2 .apw-calcHubV2__head {
            text-align: left;
            margin-bottom: 14px;
            padding: 0 2px;
        }

        #apw-lenCalcV2 .apw-calcHubV2__title {
            color: #0b2c3d;
            font-size: 35px !important;
            line-height: 1.15;
            margin: 0 0 8px;
            letter-spacing: .2px;
            font-weight: 900;
        }

        #apw-lenCalcV2 .apw-calcHubV2__sub {
            margin: 0;
            color: rgba(11, 44, 61, .75);
            font-size: 16.5px;
            line-height: 1.6;
            max-width: 760px;
        }

        #apw-lenCalcV2 .apw-calcCardV2 {
            position: relative;
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid rgba(11, 44, 61, .18);
            background-image: url("https://zendoindia.com/new-home/zendo/assets/images/bg/form-b.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        #apw-lenCalcV2 .apw-calcCardV2::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgb(11 44 61 / 63%);
            z-index: 0;
        }

        #apw-lenCalcV2 .apw-calcCardV2::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(11, 44, 61, .20), rgba(11, 44, 61, .35));
            z-index: 0;
        }

        #apw-lenCalcV2 .apw-calcCardV2__grid,
        #apw-lenCalcV2 .apw-calcFormV2 {
            position: relative;
            z-index: 1;
        }

        #apw-lenCalcV2 .apw-calcCardV2__grid {
            display: block;
        }

        #apw-lenCalcV2 .apw-calcFormV2 {
            padding: 22px 20px;
        }

        #apw-lenCalcV2 .apw-fieldV2 {
            margin-bottom: 12px;
        }

        #apw-lenCalcV2 .apw-inputV2 {
            width: 100%;
            padding: 18px 12px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .26);
            background: rgba(255, 255, 255, .14);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #ffffff;
            font-size: 15px;
            outline: none;
            transition: box-shadow .18s ease, border-color .18s ease, transform .18s ease, background .18s ease;
            min-width: 0;
        }

        #apw-lenCalcV2 .apw-inputV2::placeholder {
            color: rgba(255, 255, 255, .72);
        }

        #apw-lenCalcV2 .apw-inputV2:focus {
            border-color: rgba(255, 255, 255, .55);
            box-shadow: 0 0 0 4px rgba(11, 44, 61, .22);
            transform: translateY(-1px);
            background: rgba(255, 255, 255, .18);
        }

        #apw-lenCalcV2 .apw-rowV2 {
            display: grid;
            grid-template-columns: 1fr 50%;
            gap: 10px;
            align-items: stretch;
        }

        #apw-lenCalcV2 .apw-selectWrapV2 {
            position: relative;
        }

        #apw-lenCalcV2 .apw-floatV2 {
            position: relative;
            min-width: 0;
        }

        #apw-lenCalcV2 .apw-floatV2 .apw-inputV2--num {
            padding-top: 20px;
        }

        #apw-lenCalcV2 .apw-floatLabelV2 {
            position: absolute;
            left: 12px;
            top: 50%;
            font-size: 13px;
            font-weight: 900;
            color: rgba(255, 255, 255, .86);
            pointer-events: none;
            padding: 0 4px;
            border-radius: 6px;
        }

        #apw-lenCalcV2 .apw-floatV2 .apw-inputV2--num:focus+.apw-floatLabelV2,
        #apw-lenCalcV2 .apw-floatV2 .apw-inputV2--num:not(:placeholder-shown)+.apw-floatLabelV2 {
            top: -10px;
            transform: none;
            font-size: 13.5px;
            color: white;
            border: 1px solid rgba(255, 255, 255, .26);
            background: rgba(255, 255, 255, .14);
            backdrop-filter: blur(10px);
            width: 17%;
            padding: 2px 10px;
            border-radius: 8px;
            box-shadow:
                0 4px 14px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
        }

        #apw-lenCalcV2 .apw-floatV2 .apw-inputV2--num[readonly] {
            cursor: not-allowed;
            opacity: 1;
            background: rgba(255, 255, 255, .12);
        }

        #apw-lenCalcV2 select.apw-inputV2--unit {
            padding-right: 44px;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23ffffff' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: 18px 18px;
            background-position: calc(100% - 14px) 50%;
        }

        #apw-lenCalcV2 select.apw-inputV2--unit option {
            color: #0b2c3d;
            background: #ffffff;
        }

        #apw-lenCalcV2 .apw-helpV2 {
            display: block;
            margin-top: 6px;
            color: rgba(255, 255, 255, .92);
            font-size: 12.5px;
            line-height: 1.4;
        }

        #apw-lenCalcV2 .apw-swapMidV2 {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }

        #apw-lenCalcV2 .apw-swapBtnV2 {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .26);
            background: rgba(255, 255, 255, .14);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            cursor: pointer;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
            user-select: none;
        }

        #apw-lenCalcV2 .apw-swapBtnV2:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 34px rgba(11, 44, 61, .18);
            background: rgba(255, 255, 255, .18);
        }

        #apw-lenCalcV2 .apw-swapBtnV2:active {
            transform: translateY(1px);
        }

        #apw-lenCalcV2 .apw-alertV2 {
            font-size: 12.5px;
            color: #ffffff;
            font-weight: 900;
            margin: 8px 0 2px;
            text-shadow: 0 2px 10px rgba(11, 44, 61, .25);
        }

        @media (min-width: 768px) {
            #apw-lenCalcV2 .apw-actionsV2 {
                display: flex;
                gap: 10px;
            }

            #apw-lenCalcV2 .apw-actionsV2 .apw-btnV2 {
                flex: 1 1 50%;
                width: 50%;
            }
        }

        @media (max-width: 767px) {
            #apw-lenCalcV2 .apw-actionsV2 .apw-btnV2 {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        #apw-lenCalcV2 .apw-btnV2 {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: 900;
            font-size: 15px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
            user-select: none;
            white-space: nowrap;
        }

        #apw-lenCalcV2 .apw-btnV2:active {
            transform: translateY(1px);
        }

        #apw-lenCalcV2 .apw-btnV2--primary {
            background: #b39359;
            color: #ffffff;
            border-color: rgba(255, 255, 255, .28);
            box-shadow: 0 14px 30px rgba(11, 44, 61, .20);
        }

        #apw-lenCalcV2 .apw-btnV2--primary:hover {
            box-shadow: 0 18px 42px rgba(11, 44, 61, .28);
            transform: translateY(-1px);
        }

        #apw-lenCalcV2 .apw-btnV2--ghost {
            background: rgba(255, 255, 255, .14);
            color: #ffffff;
            border-color: rgba(255, 255, 255, .32);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        #apw-lenCalcV2 .apw-btnV2--ghost:hover {
            background: rgba(255, 255, 255, .18);
            transform: translateY(-1px);
        }

        #apw-lenCalcV2 .apw-disclaimerV2 {
            margin-top: 0;
            font-size: 12px;
            opacity: 1;
            line-height: 1.5;
        }

        #apw-lenCalcV2 .apw-disclaimerV2--light {
            color: rgba(255, 255, 255, .90);
            margin-top: 12px;
        }

        #apw-lenCalcV2 .apw-sideCardV2 {
            background: #fff;
            border-radius: 12px;
            border: 1px solid rgba(11, 44, 61, .12);
            box-shadow: 0 14px 34px rgba(11, 44, 61, .06);
            overflow: hidden;
            position: sticky;
            top: 18px;
        }

        #apw-lenCalcV2 .apw-sideCardV2__top {
            padding: 18px 16px;
            border-bottom: 1px solid rgba(11, 44, 61, .10);
            background: radial-gradient(circle at 10% 10%, rgba(179, 147, 89, .12), transparent 55%);
        }

        #apw-lenCalcV2 .apw-sideCardV2__title {
            margin: 0 0 6px;
            color: #0b2c3d;
            font-size: 22px;
            font-weight: 900;
            font-family: 'forum';
        }

        #apw-lenCalcV2 .apw-sideCardV2__hint {
            margin: 0;
            color: rgba(11, 44, 61, .72);
            font-size: 13px;
            line-height: 1.5;
        }

        #apw-lenCalcV2 .apw-sideNavV2 {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        #apw-lenCalcV2 .apw-sideNavV2__item {
            display: flex;
            align-items: center;
            padding: 12px 12px;
            border-radius: 14px;
            background: #fcfaf5;
            border: 1px solid rgba(11, 44, 61, .14);
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        #apw-lenCalcV2 .apw-sideNavV2__item:hover {
            transform: translateY(-1px);
            border-color: rgba(179, 147, 89, .55);
            box-shadow: 0 12px 26px rgba(11, 44, 61, .08);
        }

        #apw-lenCalcV2 .apw-sideNavV2__item.is-active {
            background: rgba(179, 147, 89, .12);
            border-color: rgba(179, 147, 89, .40);
        }

        #apw-lenCalcV2 .apw-sideNavV2__text {
            font-weight: 900;
            color: #0b2c3d;
            font-size: 14px;
            letter-spacing: .2px;
        }

        #apw-lenCalcV2 .apw-selectWrapV2 .apw-floatLabelV2--unit {
            top: -10px;
            transform: none;
            font-size: 13.5px;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, .26);
            background: rgba(255, 255, 255, .14);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 2px 10px;
            border-radius: 8px;
            width: 17%;
            box-shadow:
                0 4px 14px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
        }

        @media (max-width: 980px) {
            #apw-lenCalcV2 .apw-calcHubV2__layout {
                grid-template-columns: 1fr;
            }

            #apw-lenCalcV2 .apw-sideCardV2 {
                position: static;
            }
        }

        @media (max-width: 640px) {
            #apw-lenCalcV2 {
                padding: 18px 14px;
            }

            #apw-lenCalcV2 .apw-calcHubV2__sub {
                font-size: 14.5px;
            }

            #apw-lenCalcV2 .apw-calcFormV2 {
                padding: 28px 10px;
            }

            #apw-lenCalcV2 .apw-rowV2 {
                grid-template-columns: 1fr 150px;
                gap: 8px;
            }

            #apw-lenCalcV2 .apw-inputV2 {
                padding: 17px 10px;
                font-size: 14.5px;
            }

            #apw-lenCalcV2 .apw-floatV2 .apw-inputV2--num {
                padding-top: 18px;
            }

            #apw-lenCalcV2 .apw-calcHubV2__title {
                color: #0b2c3d;
                font-size: 25px !important;
                line-height: 1.15;
                margin: 0 0 8px;
                letter-spacing: .2px;
                font-weight: 900;
            }

            #apw-lenCalcV2 .apw-floatV2 .apw-inputV2--num:not(:placeholder-shown)+.apw-floatLabelV2,
            #apw-lenCalcV2 .apw-selectWrapV2 .apw-floatLabelV2--unit {
                width: 40% !important;
            }

            #apw-lenCalcV2 .apw-calcHubV2__side {
                display: none;
            }
        }
    </style>
    <section id="apw-lenCalcV2" class="apw-calcHubV2">
        <div class="apw-calcHubV2__wrap">
            <div class="apw-calcHubV2__head">
                <h2 class="apw-calcHubV2__title">Length Unit Converter</h2>
                <p class="apw-calcHubV2__sub">Enter value, choose units, and convert instantly.</p>
            </div>
            <div class="apw-calcHubV2__layout">
                <div class="apw-calcHubV2__main">
                    <div class="apw-calcCardV2">
                        <div class="apw-calcCardV2__grid">
                            <form class="apw-calcFormV2" id="apwLenV2-unitForm" novalidate>
                                <div class="apw-fieldV2">
                                    <div class="apw-rowV2">
                                        <div class="apw-floatV2">
                                            <input class="apw-inputV2 apw-inputV2--num" type="number"
                                                id="apwLenV2-fromValue" min="0" step="0.000001" value="1"
                                                required placeholder="Add your value here*" />
                                            <span class="apw-floatLabelV2">Value</span>
                                            <small class="apw-helpV2" id="apwLenV2-fromWords">In words: One</small>
                                        </div>
                                        <div class="apw-selectWrapV2">
                                            <select class="apw-inputV2 apw-inputV2--unit" id="apwLenV2-fromUnit" required>
                                                <option value="m">Meter (m)</option>
                                                <option value="cm">Centimeter (cm)</option>
                                                <option value="mm">Millimeter (mm)</option>
                                                <option value="km">Kilometer (km)</option>
                                                <option value="inch">Inch (in)</option>
                                                <option value="ft">Foot (ft)</option>
                                                <option value="yd">Yard (yd)</option>
                                                <option value="mile">Mile (mi)</option>
                                                <option value="nmi">Nautical Mile (nmi)</option>
                                                <option value="gaj">Gaj</option>
                                                <option value="haath">Haath</option>
                                                <option value="danda">Danda</option>
                                                <option value="chain">Chain</option>
                                                <option value="furlong">Furlong</option>
                                                <option value="micron">Micrometer (µm)</option>
                                                <option value="nm">Nanometer (nm)</option>
                                            </select>
                                            <span class="apw-floatLabelV2 apw-floatLabelV2--unit">From</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="apw-swapMidV2" aria-label="Swap units">
                                    <button class="apw-swapBtnV2" type="button" id="apwLenV2-swap" title="Swap From/To">
                                        <span aria-hidden="true">
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M7 14V6m0 0-3 3M7 6l3 3" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M17 10v8m0 0 3-3m-3 3-3-3" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                                <div class="apw-fieldV2">
                                    <div class="apw-rowV2">
                                        <div class="apw-floatV2">
                                            <input class="apw-inputV2 apw-inputV2--num" type="text" id="apwLenV2-toValue"
                                                value="0" readonly placeholder=" " />
                                            <span class="apw-floatLabelV2">Value</span>
                                            <small class="apw-helpV2" id="apwLenV2-toWords">In words: Zero</small>
                                        </div>
                                        <div class="apw-selectWrapV2">
                                            <select class="apw-inputV2 apw-inputV2--unit" id="apwLenV2-toUnit" required>
                                                <option value="cm">Centimeter (cm)</option>
                                                <option value="m">Meter (m)</option>
                                                <option value="mm">Millimeter (mm)</option>
                                                <option value="km">Kilometer (km)</option>
                                                <option value="inch">Inch (in)</option>
                                                <option value="ft">Foot (ft)</option>
                                                <option value="yd">Yard (yd)</option>
                                                <option value="mile">Mile (mi)</option>
                                                <option value="nmi">Nautical Mile (nmi)</option>
                                                <option value="gaj">Gaj</option>
                                                <option value="haath">Haath</option>
                                                <option value="danda">Danda</option>
                                                <option value="chain">Chain</option>
                                                <option value="furlong">Furlong</option>
                                                <option value="micron">Micrometer (µm)</option>
                                                <option value="nm">Nanometer (nm)</option>
                                            </select>
                                            <span class="apw-floatLabelV2 apw-floatLabelV2--unit">To</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="apw-alertV2" id="apwLenV2-alert" aria-live="polite"></div>
                                <div class="apw-actionsV2">
                                    <button type="button" class="apw-btnV2 apw-btnV2--primary"
                                        id="apwLenV2-convert">Convert</button>
                                    <button type="button" class="apw-btnV2 apw-btnV2--ghost"
                                        id="apwLenV2-reset">Reset</button>
                                </div>
                                <p class="apw-disclaimerV2 apw-disclaimerV2--light">
                                    *This tool is for informational purposes only.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
                <aside class="apw-calcHubV2__side" aria-label="Other calculators">
                    <div class="apw-sideCardV2">
                        <div class="apw-sideCardV2__top">
                            <h3 class="apw-sideCardV2__title">Top Real Estate Calculators</h3>
                            <p class="apw-sideCardV2__hint">Pick any calculator to explore</p>
                        </div>
                        <nav class="apw-sideNavV2">
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.acre-to-squaremeter') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 9h6v6H9V9z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Acre to Square Meter</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.acre-to-hectare') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 7h10v10H7V7z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Acre to Hectare</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.acre-to-bigha') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8 8h8v8H8V8z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Acre to Bigha</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.cent-to-square-feet') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6 6h12v12H6V6z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Cent to Square Feet</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.cent-to-square-meter') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5 5h14v14H5V5z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Cent to Square Meter</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.cm-to-inches') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 12h10" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 17h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">CM to Inches</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.cm-to-mm') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 12h12" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 17h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">CM to MM</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.ft-to-cm') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 12h14" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 17h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Feet to CM</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.ft-to-inches') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 12h8" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 17h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Feet to Inches</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.ft-to-mm') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 12h6" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M4 17h16" stroke="#b39359" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Feet to MM</span>
                            </a>
                            <a class="apw-sideNavV2__item is-active" href="{{ route('calculators.length-calculator') }}" aria-current="page">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 14H3l1.5-2h15l1.5 2z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4.5 12L6 10h12l1.5 2" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Length Calculator</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.emi-calculator') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 10.5L12 3l9 7.5" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6 10.5V21h12V10.5" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10 21v-6h4v6" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">EMI Calculator</span>
                            </a>
                        </nav>
                    </div>
                </aside>
            </div>
        </div>
        <script>
            (function() {
                const root = document.getElementById("apw-lenCalcV2");
                if (!root) return;

                const fromValue = root.querySelector("#apwLenV2-fromValue");
                const fromUnit = root.querySelector("#apwLenV2-fromUnit");
                const toValue = root.querySelector("#apwLenV2-toValue");
                const toUnit = root.querySelector("#apwLenV2-toUnit");
                const alertBox = root.querySelector("#apwLenV2-alert");
                const btnConvert = root.querySelector("#apwLenV2-convert");
                const btnReset = root.querySelector("#apwLenV2-reset");
                const btnSwap = root.querySelector("#apwLenV2-swap");

                const fromWordsEl = root.querySelector("#apwLenV2-fromWords");
                const toWordsEl = root.querySelector("#apwLenV2-toWords");



                const factorToMeter = {


                    m: 1,
                    cm: 0.01,
                    mm: 0.001,
                    km: 1000,



                    inch: 0.0254,
                    ft: 0.3048,
                    yd: 0.9144,
                    mile: 1609.344,



                    nmi: 1852,



                    gaj: 0.9144,

                    haath: 0.4572,

                    danda: 3.048,

                    chain: 20.1168,

                    furlong: 201.168,




                    micron: 0.000001,
                    nm: 0.000000001
                };

                function format(n) {
                    if (!isFinite(n)) return "0";
                    return Number(n).toLocaleString("en-IN", {
                        maximumFractionDigits: 8
                    });
                }



                function numberToWordsIndian(num) {
                    if (!isFinite(num)) return "Zero";
                    if (num === 0) return "Zero";

                    const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten",
                        "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                        "Nineteen"
                    ];
                    const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

                    function twoDigits(n) {
                        if (n < 20) return ones[n];
                        return (tens[Math.floor(n / 10)] + (n % 10 ? " " + ones[n % 10] : "")).trim();
                    }

                    function threeDigits(n) {
                        let str = "";
                        if (n >= 100) {
                            str += ones[Math.floor(n / 100)] + " Hundred";
                            n = n % 100;
                            if (n) str += " ";
                        }
                        if (n) str += twoDigits(n);
                        return str.trim();
                    }

                    const negative = num < 0;
                    num = Math.abs(num);

                    const fixed = Number(num.toFixed(8));
                    const parts = fixed.toString().split(".");
                    const intPart = parseInt(parts[0], 10);
                    const decPart = parts[1] ? parts[1].replace(/0+$/, '') : "";

                    let n = intPart;
                    let words = [];

                    const crore = Math.floor(n / 10000000);
                    n %= 10000000;
                    const lakh = Math.floor(n / 100000);
                    n %= 100000;
                    const thousand = Math.floor(n / 1000);
                    n %= 1000;

                    if (crore) words.push(threeDigits(crore) + " Crore");
                    if (lakh) words.push(threeDigits(lakh) + " Lakh");
                    if (thousand) words.push(threeDigits(thousand) + " Thousand");
                    if (n) words.push(threeDigits(n));

                    let out = words.join(" ").trim() || "Zero";

                    if (decPart) {
                        const digitWords = decPart.split("").map(d => ones[Number(d)] || "Zero").join(" ");
                        out += " Point " + digitWords;
                    }
                    if (negative) out = "Minus " + out;
                    return out;
                }

                function convert() {
                    const val = Number(fromValue.value);
                    if (!isFinite(val) || val < 0) {
                        alertBox.textContent = "Please enter a valid value.";
                        toValue.value = "0";
                        if (fromWordsEl) fromWordsEl.textContent = "In words: -";
                        if (toWordsEl) toWordsEl.textContent = "In words: -";
                        return;
                    }
                    alertBox.textContent = "";

                    const from = fromUnit.value;
                    const to = toUnit.value;

                    const meters = val * (factorToMeter[from] || 1);
                    const result = meters / (factorToMeter[to] || 1);

                    toValue.value = format(result);

                    if (fromWordsEl) fromWordsEl.textContent = "In words: " + numberToWordsIndian(val);
                    if (toWordsEl) toWordsEl.textContent = "In words: " + numberToWordsIndian(result);
                }

                function swapUnits() {
                    const temp = fromUnit.value;
                    fromUnit.value = toUnit.value;
                    toUnit.value = temp;
                    convert();
                }

                btnConvert.addEventListener("click", convert);
                btnSwap.addEventListener("click", swapUnits);
                fromValue.addEventListener("input", convert);
                fromUnit.addEventListener("change", convert);
                toUnit.addEventListener("change", convert);

                btnReset.addEventListener("click", () => {
                    fromValue.value = 1;
                    fromUnit.value = "m";
                    toUnit.value = "cm";
                    convert();
                });



                fromValue.value = 1;
                fromUnit.value = "m";
                toUnit.value = "cm";
                convert();
            })();
        </script>
    </section>
    <style>
        #apw-acreBighaSec {
            padding: 34px 16px;
        }

        #apw-acreBighaSec .apw-acreBigha__container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: left;
        }

        #apw-acreBighaSec .apw-acreBigha__block {
            margin-bottom: 22px;
        }

        #apw-acreBighaSec .apw-acreBigha__title {
            margin: 0 0 10px 0;
            font-size: 35px !important;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        #apw-acreBighaSec .apw-acreBigha__text {
            margin: 0;
            font-size: 16px;
            line-height: 1.75;
            color: #353434;
        }

        #apw-acreBighaSec .apw-acreBigha__list {
            padding: 0;
        }

        #apw-acreBighaSec .apw-acreBigha__list li {
            margin: 8px 0;
            line-height: 1.6;
            font-size: 17px;
            color: #353434;
        }

        #apw-acreBighaSec .apw-land-table-wrap {
            max-width: 1200px;
            margin: 18px 0 0 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #apw-acreBighaSec .apw-land-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            min-width: 520px;
        }

        #apw-acreBighaSec .apw-land-table thead {
            background: #0b2c3d;
        }

        #apw-acreBighaSec .apw-land-table thead th {
            color: #ffffff;
            padding: 14px 16px;
            text-align: left;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
        }

        #apw-acreBighaSec .apw-land-table tbody td {
            padding: 13px 16px;
            font-size: 14px;
            color: #333333;
            border-bottom: 1px solid #e6e6e6;
            vertical-align: top;
        }

        #apw-acreBighaSec .apw-land-table tbody tr:nth-child(even) {
            background-color: #f7f9fc;
        }

        #apw-acreBighaSec .apw-land-table tbody tr:hover {
            background-color: #fbf8f2;
        }

        #apw-acreBighaSec .apw-land-table tbody td:last-child {
            font-weight: 600;
            color: #013b7b;
        }

        @media (max-width: 600px) {
            #apw-acreBighaSec {
                padding: 26px 14px;
            }

            #apw-acreBighaSec .apw-acreBigha__title {
                font-size: 28px !important;
            }

            #apw-acreBighaSec .apw-acreBigha__text {
                font-size: 15px;
            }

            #apw-acreBighaSec .apw-land-table {
                min-width: 0;
            }

            #apw-acreBighaSec .apw-land-table thead {
                display: none;
            }

            #apw-acreBighaSec .apw-land-table,
            #apw-acreBighaSec .apw-land-table tbody,
            #apw-acreBighaSec .apw-land-table tr,
            #apw-acreBighaSec .apw-land-table td {
                display: block;
                width: 100%;
            }

            #apw-acreBighaSec .apw-land-table tr {
                margin-bottom: 15px;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
                overflow: hidden;
            }

            #apw-acreBighaSec .apw-land-table td {
                text-align: right;
                padding: 12px 14px;
                padding-left: 52%;
                position: relative;
                border-bottom: 1px solid #eee;
            }

            #apw-acreBighaSec .apw-land-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                font-weight: 700;
                color: #555;
                text-align: left;
                max-width: 48%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            #apw-acreBighaSec .apw-land-table td:last-child {
                border-bottom: 0;
            }
        }

        .value {
            font-size: 16px;
            line-height: 1.75;
            color: #353434;
        }
    </style>
@endsection
