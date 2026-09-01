@extends('layouts.app')

@section('title', 'Properties - ZendoIndia')

@section('styles')
    <style>
        /* Banner Section */
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

        @media (max-width: 767px) {
            .about-banner-heading {
                font-size: 32px;
            }

            .about-banner-section {
                padding: 130px 0 60px;
            }
        }

        /* Carousel Section */
        #resi-listing-content {
            padding: 50px 16px;
        }

        .resi-lc-wrap {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 45% 55%;
            gap: 32px;
            align-items: center;
        }

        .leftMedia {
            width: min(980px, 100%);
        }

        .carousel {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 22px;
            overflow: hidden;
            background: #f3f4f6;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .track {
            height: 100%;
            display: flex;
            transition: transform .55s ease;
            will-change: transform;
        }

        .slide {
            min-width: 100%;
            height: 100%;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .45);
            background: rgba(0, 0, 0, .35);
            color: #fff;
            font-size: 28px;
            line-height: 1;
            display: grid;
            place-items: center;
            cursor: pointer;
            user-select: none;
            backdrop-filter: blur(6px);
        }

        .nav:hover {
            background: rgba(0, 0, 0, .5);
        }

        .nav.prev {
            left: 14px;
        }

        .nav.next {
            right: 14px;
        }

        .dots {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 12px;
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
            padding: 6px 10px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            border: 0;
            background: rgba(255, 255, 255, .6);
            cursor: pointer;
        }

        .dot[aria-current="true"] {
            width: 20px;
            background: rgba(255, 255, 255, .95);
        }

        @media (max-width: 768px) {
            .carousel {
                aspect-ratio: 16 / 11;
                border-radius: 18px;
            }

            .nav {
                width: 40px;
                height: 40px;
                font-size: 26px;
            }
        }

        @media (max-width: 480px) {
            .carousel {
                aspect-ratio: 4 / 3;
                border-radius: 16px;
            }

            .nav {
                width: 38px;
                height: 38px;
            }
        }

        .resi-lc-content {
            background: #ffffff;
            border-radius: 22px;
            padding: 28px 30px;
            border: 1px solid rgba(11, 44, 61, 0.12);
            box-shadow: 0 18px 40px rgba(11, 44, 61, 0.10);
        }

        .resi-lc-subtitle {
            display: inline-block;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0b2c3d;
            background: rgba(179, 147, 89, 0.18);
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(179, 147, 89, 0.35);
        }

        .resi-lc-title {
            margin: 0 0 12px;
            font-size: clamp(22px, 2.6vw, 32px);
            font-weight: 900;
            color: #0b2c3d;
            line-height: 1.0;
        }

        .resi-lc-text {
            margin: 0 0 18px;
            font-size: 15px;
            line-height: 1.7;
            color: rgba(11, 44, 61, 0.78);
        }

        .resi-lc-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .resi-lc-btn {
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 800;
            border: 1px solid rgba(11, 44, 61, 0.18);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .resi-lc-btn.primary {
            background: #b39359;
            color: white;
            box-shadow: 0 14px 30px rgba(179, 147, 89, 0.28);
        }

        .resi-lc-btn.secondary {
            background: #ffffff;
            color: #0b2c3d;
        }

        .resi-lc-btn:hover {
            transform: translateY(-1px);
        }

        @media(max-width:900px) {
            .resi-lc-wrap {
                grid-template-columns: 1fr;
            }
        }

        /* Property Listing Section */
        #apw-resiPage {
            padding: 20px 16px;
            color: #0b2c3d;
        }

        #apw-resiPage * {
            box-sizing: border-box;
        }

        .apw-resiWrap {
            max-width: 1240px;
            margin: 0 auto;
        }

        .apw-resiIntro {
            padding: 26px 22px;
            border-radius: 18px;
            background: radial-gradient(1200px 260px at 20% 0%, rgba(179, 147, 89, 0.22), transparent 60%), linear-gradient(180deg, rgba(11, 44, 61, 0.04), rgba(11, 44, 61, 0.00));
            border: 1px solid rgba(11, 44, 61, 0.10);
            box-shadow: 0 16px 42px rgba(11, 44, 61, 0.08);
            margin-bottom: 22px;
        }

        .apw-resiKicker {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(179, 147, 89, 0.12);
            border: 1px solid rgba(179, 147, 89, 0.25);
            color: #0b2c3d;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0 0 10px;
            font-size: 13px;
        }

        .apw-resiTitle {
            margin: 0 0 8px;
            font-size: clamp(24px, 3vw, 40px);
            line-height: 1.15;
            font-weight: 850;
            letter-spacing: -0.4px;
        }

        .apw-resiDesc {
            margin: 0 0 16px;
            font-size: 15.5px;
            color: rgba(11, 44, 61, 0.82);
            max-width: 820px;
        }

        .apw-resiBadges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .apw-resiBadge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid rgba(11, 44, 61, 0.10);
            box-shadow: 0 10px 24px rgba(11, 44, 61, 0.06);
            font-size: 13.5px;
            font-weight: 650;
        }

        .apw-resiGrid {
            display: grid;
            grid-template-columns: minmax(0, 360px) minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .apw-resiFilter {
            position: sticky;
            top: 85px;
            border-radius: 10px;
            background: #0b2c3d;
            color: #fbf8f2;
            padding: 18px;
            box-shadow: 0 18px 40px rgba(11, 44, 61, 0.18);
        }

        .apw-filterHead {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(251, 248, 242, 0.14);
            margin-bottom: 14px;
        }

        .apw-filterTitle {
            margin: 0;
            font-size: 30px !important;
            font-weight: 850;
            letter-spacing: -0.2px;
        }

        .apw-filterSub {
            margin: 6px 0 0;
            font-size: 12.5px;
            color: rgba(251, 248, 242, 0.82);
            line-height: 1.4;
        }

        .apw-filterReset {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid rgba(251, 248, 242, 0.22);
            background: rgba(251, 248, 242, 0.08);
            color: #fbf8f2;
            cursor: pointer;
            font-weight: 750;
            transition: transform .15s ease, background .15s ease;
            white-space: nowrap;
        }

        .apw-filterReset:hover {
            transform: translateY(-1px);
            background: rgba(251, 248, 242, 0.12);
        }

        .apw-field {
            margin-bottom: 14px;
        }

        .apw-label {
            display: block;
            font-size: 12.5px;
            font-weight: 800;
            margin-bottom: 8px;
            color: rgba(251, 248, 242, 0.92);
        }

        .apw-selectWrap {
            position: relative;
            width: 100%;
        }

        .apw-select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            border-radius: 14px;
            padding: 12px 42px 12px 14px;
            border: 1px solid rgba(251, 248, 242, 0.22);
            background: rgba(251, 248, 242, 0.08);
            color: #fbf8f2;
            outline: none;
        }

        .apw-select:focus {
            border-color: rgba(179, 147, 89, 0.75);
            box-shadow: 0 0 0 4px rgba(179, 147, 89, 0.18);
        }

        .apw-selectSvg {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            opacity: 0.95;
        }

        .apw-filterApply {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            cursor: pointer;
            border-radius: 14px;
            padding: 12px 14px;
            background: #b39359;
            color: white;
            font-weight: 600;
            letter-spacing: 0.2px;
            box-shadow: 0 16px 30px rgba(179, 147, 89, 0.25);
            transition: transform .15s ease, filter .15s ease;
        }

        .apw-filterApply:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        .apw-filterApply:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
            filter: none;
            box-shadow: none;
        }

        .apw-filterNote {
            margin: 12px 2px 0;
            font-size: 12.5px;
            color: rgba(251, 248, 242, 0.80);
            line-height: 1.5;
        }

        .apw-resiListings {
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(11, 44, 61, 0.10);
            box-shadow: 0 16px 40px rgba(11, 44, 61, 0.07);
            padding: 18px;
        }

        .apw-listTop {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
        }

        .apw-listTitle {
            margin: 0;
            font-size: 30px !important;
            font-weight: 900;
            letter-spacing: -0.2px;
        }

        .apw-listSub {
            margin: 6px 0 0;
            color: rgba(11, 44, 61, 0.70);
            font-size: 13px;
            font-weight: 650;
        }

        .apw-listTopRight {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .apw-searchWrap {
            position: relative;
            min-width: 250px;
            flex: 1;
        }

        .apw-search {
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(11, 44, 61, 0.14);
            background: #fff;
            padding: 12px 14px 12px 40px;
            outline: none;
            font-size: 17px;
            color: #0b2c3d;
        }

        .apw-search:focus {
            border-color: rgba(179, 147, 89, 0.75);
            box-shadow: 0 0 0 4px rgba(179, 147, 89, 0.16);
        }

        .apw-searchSvg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.95;
            pointer-events: none;
        }

        .apw-cardGrid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .apw-card {
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(11, 44, 61, 0.10);
            box-shadow: 0 14px 34px rgba(11, 44, 61, 0.08);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .apw-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 46px rgba(11, 44, 61, 0.14);
        }

        .apw-cardMedia {
            height: 168px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .apw-tagAlt {
            background: rgba(179, 147, 89, 0.90);
            color: white;
            border-color: rgba(11, 44, 61, 0.10);
        }

        .apw-tag {
            position: absolute;
            left: 12px;
            top: 12px;
            padding: 8px 10px;
            font-size: 12px;
            font-weight: 900;
            border-radius: 999px;
            background: rgba(11, 44, 61, 0.78);
            color: #fbf8f2;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .apw-cardBody {
            padding: 16px;
        }

        .apw-cardTitle {
            margin: 0 0 8px;
            font-size: 19px;
            font-weight: 900;
            color: #0b2c3d;
            letter-spacing: -0.2px;
        }

        .apw-cardMeta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0 0 12px;
            font-size: 13.5px;
            color: rgba(11, 44, 61, 0.70);
            font-weight: 650;
        }

        .apw-miniSvg {
            display: inline-flex;
            flex-shrink: 0;
        }

        .apw-cardRow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .apw-price {
            display: flex;
            flex-direction: column;
        }

        .apw-priceLabel {
            font-size: 11.5px;
            color: rgba(11, 44, 61, 0.60);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .apw-priceVal {
            display: block;
            font-size: 16px;
            font-weight: 950;
            color: #0b2c3d;
            letter-spacing: -0.1px;
        }

        .apw-btnOutline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 50px;
            border: 1px solid rgba(179, 147, 89, 0.60);
            color: white;
            text-decoration: none;
            /* font-weight: 900; */
            background: #b39359;
            transition: transform .15s ease, background .15s ease;
            white-space: nowrap;
            font-size: 15px;
        }

        .apw-btnOutline:hover {
            background: #b39359;
            color: white;
        }

        .apw-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .apw-amenities span {
            font-size: 12px;
            font-weight: 750;
            color: rgba(11, 44, 61, 0.78);
            padding: 7px 10px;
            border-radius: 999px;
            background: rgba(11, 44, 61, 0.05);
            border: 1px solid rgba(11, 44, 61, 0.08);
        }

        .apw-empty {
            margin-top: 14px;
        }

        .apw-emptyBox {
            border-radius: 18px;
            background: #fff;
            border: 1px dashed rgba(11, 44, 61, 0.18);
            padding: 26px 18px;
            text-align: center;
        }

        .apw-emptyBox h3 {
            margin: 10px 0 6px;
            font-weight: 950;
        }

        .apw-emptyBox p {
            margin: 0 auto 14px;
            max-width: 520px;
            color: rgba(11, 44, 61, 0.72);
        }

        .apw-ctaStrip {
            margin-top: 16px;
            border-radius: 18px;
            background: #0b2c3d;
            color: #fbf8f2;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 18px 42px rgba(11, 44, 61, 0.18);
        }

        .apw-ctaLeft h3 {
            margin: 0 0 6px;
            font-size: 23px;
            font-weight: 700;
        }

        .apw-ctaLeft p {
            margin: 0;
            color: rgba(251, 248, 242, 0.82);
            line-height: 1.5;
        }

        .apw-ctaBtn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 50px;
            background: #b39359;
            color: white;
            box-shadow: 0 16px 30px rgba(179, 147, 89, 0.25);
            white-space: nowrap;
            transition: transform .15s ease, filter .15s ease;
        }

        .apw-ctaBtn:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        /* BHK Chips */
        .apw-chipRow {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .apw-chip {
            border-radius: 999px;
            padding: 9px 12px;
            border: 1px solid rgba(251, 248, 242, 0.22);
            background: rgba(251, 248, 242, 0.08);
            color: #fbf8f2;
            cursor: pointer;
            font-weight: 750;
            font-size: 13px;
            transition: background .15s ease, transform .15s ease, border-color .15s ease;
        }

        .apw-chip:hover {
            transform: translateY(-1px);
            background: rgba(251, 248, 242, 0.12);
        }

        .apw-chip.is-active {
            background: rgba(179, 147, 89, 0.22);
            border-color: rgba(179, 147, 89, 0.70);
        }

        @media (max-width: 1100px) {
            .apw-cardGrid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .apw-resiGrid {
                grid-template-columns: minmax(0, 340px) minmax(0, 1fr);
            }
        }

        @media (max-width: 880px) {
            .apw-resiGrid {
                grid-template-columns: 1fr;
            }

            .apw-resiFilter {
                position: relative;
                top: 0;
            }

            .apw-cardGrid {
                grid-template-columns: 1fr;
            }

            .apw-listTop {
                flex-direction: column;
                align-items: flex-start;
            }

            .apw-searchWrap {
                min-width: 100%;
            }

            .apw-ctaStrip {
                flex-direction: column;
                align-items: flex-start;
            }

            .apw-ctaBtn {
                width: 100%;
            }
        }

        /* Perspective Section */
        :root {
            --apw-gold: #b39359;
            --apw-navy: #0b2c3d;
            --apw-cream: #fbf8f2;
        }

        #apw-propPerspective.apw-propPerspective {
            padding: 64px 16px;
            position: relative;
            overflow: hidden;
        }

        #apw-propPerspective .apw-propPerspective__wrap {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
            gap: 28px;
            align-items: start;
        }

        #apw-propPerspective .apw-propPerspective__badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: .2px;
            font-size: 13px;
            color: var(--apw-navy);
            background: rgba(179, 147, 89, 0.16);
            border: 1px solid rgba(179, 147, 89, 0.35);
        }

        #apw-propPerspective .apw-propPerspective__title {
            margin: 14px 0 10px;
            color: var(--apw-navy);
            font-size: clamp(24px, 2.2vw, 34px);
            line-height: 1.2;
            font-weight: 800;
        }

        #apw-propPerspective .apw-propPerspective__text {
            margin: 0 0 18px;
            color: rgba(11, 44, 61, 0.82);
            font-size: 15.5px;
            line-height: 1.75;
        }

        #apw-propPerspective .apw-propPerspective__list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 12px;
        }

        #apw-propPerspective .apw-propPerspective__item {
            display: grid;
            grid-template-columns: 18px 1fr;
            gap: 12px;
            padding: 14px 14px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid rgba(11, 44, 61, 0.08);
            box-shadow: 0 10px 26px rgba(11, 44, 61, 0.06);
        }

        #apw-propPerspective .apw-propPerspective__item strong {
            color: var(--apw-navy);
        }

        #apw-propPerspective .apw-propPerspective__itemText {
            color: rgba(11, 44, 61, 0.8);
            font-size: 14.8px;
            line-height: 1.55;
        }

        #apw-propPerspective .apw-propPerspective__tick {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            display: inline-block;
            margin-top: 2px;
            background: rgba(179, 147, 89, 0.20);
            border: 1px solid rgba(179, 147, 89, 0.55);
            position: relative;
            flex: 0 0 auto;
        }

        #apw-propPerspective .apw-propPerspective__tick::after {
            content: "";
            position: absolute;
            left: 5px;
            top: 3px;
            width: 6px;
            height: 10px;
            border-right: 2px solid var(--apw-navy);
            border-bottom: 2px solid var(--apw-navy);
            transform: rotate(40deg);
        }

        #apw-propPerspective .apw-propPerspective__ctaRow {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        #apw-propPerspective .apw-propPerspective__btn {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: .2px;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
            will-change: transform;
        }

        #apw-propPerspective .apw-propPerspective__btn--primary {
            background: var(--apw-navy);
            color: var(--apw-cream);
            box-shadow: 0 14px 30px rgba(11, 44, 61, 0.18);
        }

        #apw-propPerspective .apw-propPerspective__btn--primary:hover {
            transform: translateY(-2px);
        }

        #apw-propPerspective .apw-propPerspective__btn--ghost {
            background: transparent;
            color: var(--apw-navy);
            border: 1px solid rgba(11, 44, 61, 0.22);
        }

        #apw-propPerspective .apw-propPerspective__btn--ghost:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(11, 44, 61, 0.10);
        }

        #apw-propPerspective .apw-propPerspective__media {
            position: sticky;
            top: 90px;
        }

        #apw-propPerspective .apw-propPerspective__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        #apw-propPerspective .apw-propPerspective__card {
            position: relative;
            display: block;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(11, 44, 61, 0.10);
            box-shadow: 0 12px 30px rgba(11, 44, 61, 0.08);
            min-height: 170px;
            transform: translateZ(0);
        }

        #apw-propPerspective .apw-propPerspective__img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transform: scale(1.01);
            transition: transform .25s ease;
        }

        #apw-propPerspective .apw-propPerspective__card:hover .apw-propPerspective__img {
            transform: scale(1.06);
        }

        #apw-propPerspective .apw-propPerspective__cap {
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 10px;
            padding: 10px 10px;
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(11, 44, 61, 0.00), rgba(11, 44, 61, 0.82));
            color: var(--apw-cream);
        }

        #apw-propPerspective .apw-propPerspective__capTitle {
            font-weight: 900;
            font-size: 13.5px;
            letter-spacing: .2px;
        }

        #apw-propPerspective .apw-propPerspective__capSub {
            margin-top: 2px;
            font-size: 12.5px;
            opacity: .9;
        }

        @media (max-width: 980px) {
            #apw-propPerspective .apw-propPerspective__wrap {
                grid-template-columns: 1fr;
            }

            #apw-propPerspective .apw-propPerspective__media {
                position: static;
            }

            #apw-propPerspective .apw-propPerspective__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            #apw-propPerspective {
                padding: 46px 14px;
            }

            #apw-propPerspective .apw-propPerspective__grid {
                grid-template-columns: 1fr;
            }

            #apw-propPerspective .apw-propPerspective__item {
                padding: 12px 12px;
            }
        }

        /* Inquiry Form Section */
        #inquiry-section {
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        #inquiry-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(11, 44, 61, 0.7);
            z-index: 1;
        }

        #inquiry-section>div {
            position: relative;
            z-index: 2;
        }

        .inquiry-form-input {
            font-family: 'Nunito Sans', sans-serif;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            height: 3.5rem;
            font-size: 1.125rem;
        }

        .inquiry-form-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .inquiry-form-input:focus {
            outline: none;
            border-color: #B39359;
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Process Steps Section */
        .step-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease-in-out;
        }

        .step-card:hover {
            border-color: #B39359;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transform: translateY(-3px);
        }

        .step-number {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            background-color: #B39359;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.5rem;
            z-index: 10;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .apw-resiFilter select option {
            background-color: #0b2c3d !important;
            color: #fbf8f2 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Banner Section -->
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div>
                <h1 class="about-banner-heading">Properties</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <p>Properties</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Carousel Section -->
    @if ($carouselSection && $carouselSection->sectionImages->count() > 0)
        <section id="resi-listing-content">
            <div class="resi-lc-wrap">
                <div class="leftMedia">
                    <div class="carousel" data-carousel>
                        <div class="track" data-track>
                            @foreach ($carouselSection->images_with_alt ?? [] as $index => $image)
                                <div class="slide">
                                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?: $carouselSection->title . ' ' . ($index + 1) }}"
                                        loading="lazy" />
                                </div>
                            @endforeach
                        </div>
                        <button class="nav prev" type="button" aria-label="Previous image" data-prev>‹</button>
                        <button class="nav next" type="button" aria-label="Next image" data-next>›</button>
                        <div class="dots" aria-label="Carousel pagination" data-dots></div>
                    </div>
                </div>

                <div class="resi-lc-content">
                    <span class="resi-lc-subtitle">{{ $carouselSection->subtitle }}</span>
                    <h2 class="resi-lc-title">{{ $carouselSection->title }}</h2>
                    <p class="resi-lc-text">{{ $carouselSection->description }}</p>
                    <div class="resi-lc-actions">
                        <a href="{{ $carouselSection->button_link }}"
                            class="resi-lc-btn primary">{{ $carouselSection->button_text }}</a>
                        <a href="{{ $carouselSection->secondary_button_link }}"
                            class="resi-lc-btn secondary">{{ $carouselSection->secondary_button_text }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- Property Listing Section -->
    <section id="apw-resiPage" class="apw-resiPage" x-data="propertiesFilter()">
        <div class="apw-resiWrap">
            @if ($introSection)
                <div class="apw-resiIntro">
                    <p class="apw-resiKicker">{{ $introSection->kicker }}</p>
                    <h1 class="apw-resiTitle">{{ $introSection->title }}</h1>
                    <p class="apw-resiDesc">
                        {{ $introSection->description }}
                    </p>

                    @if ($introSection->badges && count($introSection->badges) > 0)
                        <div class="apw-resiBadges">
                            @foreach ($introSection->badges as $badge)
                                <div class="apw-resiBadge">
                                    <span class="apw-resiBadgeSvg" aria-hidden="true">
                                        @if ($loop->index == 0)
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                                <path
                                                    d="M12 2.5l7 3.2v6.4c0 5.2-3.3 8.8-7 9.9-3.7-1.1-7-4.7-7-9.9V5.7L12 2.5z"
                                                    stroke="#b39359" stroke-width="1.7" />
                                                <path d="M9.2 12l1.9 2 3.8-4.2" stroke="#b39359" stroke-width="1.7"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @elseif($loop->index == 1)
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                                <circle cx="12" cy="12" r="8.5" stroke="#b39359"
                                                    stroke-width="1.7" />
                                                <path d="M12 7.8v4.6l3.2 1.8" stroke="#b39359" stroke-width="1.7"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                                <path d="M12 21s7-5.2 7-11A7 7 0 1 0 5 10c0 5.8 7 11 7 11z" stroke="#b39359"
                                                    stroke-width="1.7" />
                                                <circle cx="12" cy="10" r="2.3" stroke="#b39359"
                                                    stroke-width="1.7" />
                                            </svg>
                                        @endif
                                    </span>
                                    {{ $badge }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <div class="apw-resiGrid">
                <!-- Filter Sidebar -->
                <aside class="apw-resiFilter" aria-label="Residential Filters">
                    <div class="apw-filterHead">
                        <div class="apw-filterTitleWrap">
                            <h2 class="apw-filterTitle">Filter</h2>
                            <p class="apw-filterSub">Quickly refine by location & type.</p>
                        </div>

                        <button type="button" class="apw-filterReset" @click.prevent="resetFilters()"
                            onclick="window.location.href='{{ route('properties.index') }}'">
                            Reset
                        </button>
                    </div>

                    <form method="GET" action="{{ route('properties.index') }}" id="apw-resiFilterForm" @submit.prevent="applyFilters()">
                        <!-- City -->
                        <div class="apw-field">
                            <label class="apw-label" for="city">City</label>
                            <div class="apw-selectWrap">
                                <select id="city" name="city" class="apw-select" x-model="city" @change="applyFilters()">
                                    <option value="">All Cities</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="apw-selectSvg" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                        <path d="M7 10l5 5 5-5" stroke="#b39359" stroke-width="1.8"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div class="apw-field">
                            <label class="apw-label" for="property_type_slug">Property Type</label>
                            <div class="apw-selectWrap">
                                <select id="property_type_slug" name="property_type_slug" class="apw-select" x-model="property_type_slug" @change="applyFilters()">
                                    <option value="">All Types</option>
                                    @foreach ($propertyTypeOptions as $type)
                                        <option value="{{ $type['key'] }}"
                                            {{ request('property_type_slug') === $type['key'] ? 'selected' : '' }}>
                                            {{ $type['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="apw-selectSvg" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                        <path d="M7 10l5 5 5-5" stroke="#b39359" stroke-width="1.8"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="apw-filterApply" :disabled="loading" @click.prevent="applyFilters()">
                            <span x-text="loading ? 'Applying...' : 'Apply Filters'">Apply Filters</span>
                        </button>
                    </form>
                </aside>

                <!-- Listings Area -->
                <main class="apw-resiListings" id="apw-results-container"
                    aria-label="Residential Listings"
                    @click="handleResultsClick($event)"
                    :class="{ 'opacity-50 pointer-events-none transition-opacity duration-200': loading }">
                    @include('pages.properties._results')
                </main>
            </div>
        </div>
    </section>
            </div>
        </div>
    </section>

    <!-- Perspective Section -->
    @if ($perspectiveSection && $perspectiveSection->sectionImages->count() > 0)
        <section id="apw-propPerspective" class="apw-propPerspective">
            <div class="apw-propPerspective__wrap">
                <div class="apw-propPerspective__content">
                    <span class="apw-propPerspective__badge">{{ $perspectiveSection->subtitle }}</span>

                    <h2 class="apw-propPerspective__title">{{ $perspectiveSection->title }}</h2>

                    <p class="apw-propPerspective__text">{{ $perspectiveSection->description }}</p>

                    @if ($perspectiveSection->features && count($perspectiveSection->features) > 0)
                        <ul class="apw-propPerspective__list">
                            @foreach ($perspectiveSection->features as $feature)
                                <li class="apw-propPerspective__item">
                                    <span class="apw-propPerspective__tick" aria-hidden="true"></span>
                                    <div class="apw-propPerspective__itemText">
                                        {!! $feature !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="apw-propPerspective__ctaRow">
                        <a class="apw-propPerspective__btn apw-propPerspective__btn--primary"
                            href="{{ $perspectiveSection->button_link }}">
                            {{ $perspectiveSection->button_text }}
                        </a>
                        <a class="apw-propPerspective__btn apw-propPerspective__btn--ghost"
                            href="{{ $perspectiveSection->secondary_button_link }}">
                            {{ $perspectiveSection->secondary_button_text }}
                        </a>
                    </div>
                </div>

                <div class="apw-propPerspective__media" aria-label="Property images">
                    <div class="apw-propPerspective__grid">
                        @foreach ($perspectiveSection->images_with_alt ?? [] as $index => $image)
                            @if ($index < 4)
                                <a class="apw-propPerspective__card" href="#"
                                    aria-label="{{ $image['alt'] ?: 'Open property image ' . ($index + 1) }}">
                                    <img class="apw-propPerspective__img" src="{{ $image['url'] }}"
                                        alt="{{ $image['alt'] ?: 'Property view ' . ($index + 1) }}" loading="lazy" />
                                    <div class="apw-propPerspective__cap">
                                        <div class="apw-propPerspective__capTitle">{{ $image['alt'] ?: 'Property View ' . ($index + 1) }}</div>
                                        <div class="apw-propPerspective__capSub">Premium quality</div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Inquiry Form Section -->
    <section id="inquiry-section" class="py-24 animate-on-scroll fade-in-up is-visible">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div>
                <span class="section-subheading-dark-bg">Get Inquiry</span>
                <h2 class="md:text-5xl font-heading text-white">Your Luxurious Escape Awaits — Reserve Today</h2>
                <p class="text-lg text-gray-300 font-body max-w-2xl mx-auto">
                    Step into a world of refined elegance and timeless comfort. Secure your unforgettable stay at our luxury
                    hotel – it's just an inquiry away.
                </p>
            </div>

            <div class="w-full max-w-6xl mx-auto mt-12">
                <form action="#" method="POST"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    <div>
                        <label for="name-2" class="sr-only">Name</label>
                        <input type="text" name="name-2" id="name-2" class="inquiry-form-input w-full"
                            placeholder="Name">
                    </div>
                    <div>
                        <label for="email-2" class="sr-only">Email</label>
                        <input type="email" name="email-2" id="email-2" class="inquiry-form-input w-full"
                            placeholder="Email">
                    </div>
                    <div>
                        <label for="phone-2" class="sr-only">Phone number</label>
                        <input type="tel" name="phone-2" id="phone-2" class="inquiry-form-input w-full"
                            placeholder="Phone number">
                    </div>
                    <div>
                        <label for="requirement-2" class="sr-only">Requirement</label>
                        <input type="text" name="requirement-2" id="requirement-2" class="inquiry-form-input w-full"
                            placeholder="Requirement">
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full h-[56px] px-6 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 btn-anim btn-dark-bg">
                            Get Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Process Steps Section -->
    <section class="bg-pattern-white py-24 animate-on-scroll fade-in-up is-visible">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="section-subheading">Our Process</span>
                <h2 class="font-heading text-zendo-navy">How We Work</h2>
                <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto">
                    We break down the complexities of real estate into simple, transparent steps.
                </p>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($workProcesses->count(), 4) }} gap-12 card-grid-container">
                @foreach ($workProcesses as $process)
                    <div class="relative text-center step-card card-item rounded-lg shadow-md p-8 pt-16">
                        <div class="step-number">{{ $process->step_number }}</div>
                        <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-3">{{ $process->title }}</h3>
                        <p class="text-gray-600 font-body leading-relaxed">
                            {{ $process->description }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('scripts')
<script>
    // ─── Carousel ────────────────────────────────────────────────────────────────
    (function () {
        const root = document.querySelector("[data-carousel]");
        if (!root) return;

        const track    = root.querySelector("[data-track]");
        const slides   = Array.from(track.children);
        const prevBtn  = root.querySelector("[data-prev]");
        const nextBtn  = root.querySelector("[data-next]");
        const dotsWrap = root.querySelector("[data-dots]");

        let index = 0, timer = null;

        function buildDots() {
            dotsWrap.innerHTML = "";
            slides.forEach((_, i) => {
                const b = document.createElement("button");
                b.className = "dot";
                b.type = "button";
                b.setAttribute("aria-label", `Go to slide ${i + 1}`);
                b.addEventListener("click", () => goTo(i));
                dotsWrap.appendChild(b);
            });
        }

        function updateDots() {
            dotsWrap.querySelectorAll(".dot")
                .forEach((d, i) => d.setAttribute("aria-current", i === index ? "true" : "false"));
        }

        function goTo(i) {
            index = (i + slides.length) % slides.length;
            track.style.transform = `translateX(-${index * 100}%)`;
            updateDots();
            restartAutoplay();
        }

        function next() { goTo(index + 1); }
        function prev() { goTo(index - 1); }

        function startAutoplay()   { stopAutoplay(); timer = setInterval(next, 3500); }
        function stopAutoplay()    { if (timer) clearInterval(timer); timer = null; }
        function restartAutoplay() { startAutoplay(); }

        nextBtn.addEventListener("click", next);
        prevBtn.addEventListener("click", prev);
        root.addEventListener("mouseenter", stopAutoplay);
        root.addEventListener("mouseleave", startAutoplay);

        let startX = 0, dx = 0, dragging = false;
        root.addEventListener("touchstart", e => { dragging = true; startX = e.touches[0].clientX; dx = 0; stopAutoplay(); }, { passive: true });
        root.addEventListener("touchmove",  e => { if (!dragging) return; dx = e.touches[0].clientX - startX; }, { passive: true });
        root.addEventListener("touchend",   () => { dragging = false; Math.abs(dx) > 40 ? (dx < 0 ? next() : prev()) : restartAutoplay(); });

        buildDots();
        goTo(0);
        startAutoplay();
    })();

    // ─── Alpine Properties Filter ───────────────────────────────────────────────
    function propertiesFilter() {
        const params = new URLSearchParams(window.location.search);
        return {
            city: params.get('city') || '',
            locality: params.get('locality') || '',
            property_type_slug: params.get('property_type_slug') || '',
            construction_status: params.get('construction_status') || '',
            builder: params.get('builder') || '',
            search: params.get('search') || '',
            page: params.get('page') || 1,
            loading: false,
            error: false,

            init() {
                window.addEventListener('popstate', () => {
                    this.syncFromUrl();
                    this.fetchResults(window.location.href, false);
                });
            },

            syncFromUrl() {
                const p = new URLSearchParams(window.location.search);
                this.city = p.get('city') || '';
                this.locality = p.get('locality') || '';
                this.property_type_slug = p.get('property_type_slug') || '';
                this.construction_status = p.get('construction_status') || '';
                this.builder = p.get('builder') || '';
                this.search = p.get('search') || '';
                this.page = p.get('page') || 1;
            },

            buildUrl(targetPage = null) {
                const url = new URL('{{ route("properties.index") }}', window.location.origin);
                if (this.city) url.searchParams.set('city', this.city);
                if (this.locality) url.searchParams.set('locality', this.locality);
                if (this.property_type_slug) url.searchParams.set('property_type_slug', this.property_type_slug);
                if (this.construction_status) url.searchParams.set('construction_status', this.construction_status);
                if (this.builder) url.searchParams.set('builder', this.builder);
                if (this.search) url.searchParams.set('search', this.search);

                const p = targetPage !== null ? targetPage : (this.page > 1 ? this.page : null);
                if (p && p > 1) {
                    url.searchParams.set('page', p);
                }
                return url.toString();
            },

            applyFilters() {
                this.page = 1;
                const url = this.buildUrl(1);
                this.fetchResults(url, true);
            },

            resetFilters() {
                this.city = '';
                this.locality = '';
                this.property_type_slug = '';
                this.construction_status = '';
                this.builder = '';
                this.search = '';
                this.page = 1;
                const url = '{{ route("properties.index") }}';
                this.fetchResults(url, true);
            },

            onCityChange() {
                this.locality = '';
                this.applyFilters();
            },

            handleResultsClick(e) {
                const link = e.target.closest('.apw-pagination-wrap a, .pagination a');
                if (link) {
                    e.preventDefault();
                    const url = link.getAttribute('href');
                    if (url) {
                        const linkUrl = new URL(url, window.location.origin);
                        const pageVal = linkUrl.searchParams.get('page') || 1;
                        this.page = pageVal;
                        this.fetchResults(url, true);
                    }
                }
            },

            fetchResults(url, shouldPushState = true) {
                if (this.loading) return;
                this.loading = true;
                this.error = false;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html, application/xhtml+xml'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    const container = document.getElementById('apw-results-container');
                    if (container) {
                        container.innerHTML = html;
                        if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                            window.Alpine.initTree(container);
                        }
                    }
                    if (shouldPushState) {
                        history.pushState(null, '', url);
                    }
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    this.loading = false;
                    this.error = true;
                });
            }
        };
    }
</script>
@endsection