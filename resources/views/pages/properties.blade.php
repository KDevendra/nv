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
            background: rgb(0 0 0 / 62%);
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
            top: 18px;
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
    @if ($carouselSection && $carouselSection->images && count($carouselSection->images) > 0)
        <section id="resi-listing-content">
            <div class="resi-lc-wrap">
                <div class="leftMedia">
                    <div class="carousel" data-carousel>
                        <div class="track" data-track>
                            @foreach ($carouselSection->images_urls as $index => $imageUrl)
                                <div class="slide">
                                    <img src="{{ $imageUrl }}" alt="{{ $carouselSection->title }} {{ $index + 1 }}"
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

    <!-- Builder Video Section -->
    @if ($selectedBuilder)
        @php
            $bYtId = null;
            if ($selectedBuilder->youtube_url) {
                preg_match(
                    '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                    $selectedBuilder->youtube_url,
                    $ym,
                );
                $bYtId = $ym[1] ?? null;
            }
            $hasVideo = $bYtId || $selectedBuilder->video_path;
        @endphp
        @if ($hasVideo)
            <section class="bvs-section">
                <div class="bvs-inner">
                    <div class="bvs-text">
                        <span class="bvs-badge">{{ $selectedBuilder->name }}</span>
                        <h2 class="bvs-heading">
                            {{ $selectedBuilder->description ?: 'Discover the vision behind every project.' }}</h2>
                        <div class="bvs-meta">
                            @if ($selectedBuilder->established_year)
                                <span class="bvs-chip">Est. {{ $selectedBuilder->established_year }}</span>
                            @endif
                            @if ($selectedBuilder->is_verified)
                                <span class="bvs-chip bvs-chip--blue">✓ Verified</span>
                            @endif
                        </div>
                        <div class="bvs-actions">
                            <a href="#enquiry" class="bvs-btn bvs-btn--gold">Enquire Now</a>
                        </div>
                    </div>
                    <div class="bvs-media">
                        @if ($bYtId)
                            <div class="bvs-player">
                                <iframe src="https://www.youtube.com/embed/{{ $bYtId }}?rel=0" allowfullscreen
                                    loading="lazy"></iframe>
                            </div>
                        @elseif($selectedBuilder->video_path)
                            <video controls class="bvs-player" style="height:auto;">
                                <source src="{{ asset('storage/' . $selectedBuilder->video_path) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                </div>
            </section>
            <style>
                .bvs-section {
                    background: linear-gradient(135deg, #0b2c3d 0%, #0f3d55 100%);
                    padding: 64px 24px
                }

                .bvs-inner {
                    max-width: 1160px;
                    margin: 0 auto;
                    display: grid;
                    grid-template-columns: 1fr 1.4fr;
                    gap: 48px;
                    align-items: center
                }

                .bvs-badge {
                    display: inline-block;
                    background: rgba(179, 147, 89, .2);
                    border: 1px solid rgba(179, 147, 89, .45);
                    color: #d4aa6a;
                    font-size: 11px;
                    font-weight: 700;
                    letter-spacing: .08em;
                    text-transform: uppercase;
                    padding: 5px 14px;
                    border-radius: 999px;
                    margin-bottom: 16px
                }

                .bvs-heading {
                    font-size: clamp(20px, 2.4vw, 30px);
                    font-weight: 800;
                    color: #fff;
                    line-height: 1.35;
                    margin: 0 0 20px
                }

                .bvs-meta {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    margin-bottom: 28px
                }

                .bvs-chip {
                    font-size: 12px;
                    font-weight: 600;
                    padding: 4px 14px;
                    border-radius: 999px;
                    background: rgba(255, 255, 255, .1);
                    color: rgba(255, 255, 255, .8);
                    border: 1px solid rgba(255, 255, 255, .15)
                }

                .bvs-chip--blue {
                    background: rgba(59, 130, 246, .15);
                    color: #93c5fd;
                    border-color: rgba(59, 130, 246, .3)
                }

                .bvs-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 12px
                }

                .bvs-btn {
                    text-decoration: none;
                    padding: 12px 24px;
                    border-radius: 999px;
                    font-size: 14px;
                    font-weight: 700;
                    transition: transform .15s, box-shadow .15s
                }

                .bvs-btn:hover {
                    transform: translateY(-2px)
                }

                .bvs-btn--gold {
                    background: #b39359;
                    color: #fff;
                    box-shadow: 0 8px 24px rgba(179, 147, 89, .35)
                }

                .bvs-btn--outline {
                    background: transparent;
                    color: #fff;
                    border: 1.5px solid rgba(255, 255, 255, .35)
                }

                .bvs-media {
                    width: 100%
                }

                .bvs-player {
                    width: 100%;
                    aspect-ratio: 16/9;
                    border-radius: 18px;
                    overflow: hidden;
                    box-shadow: 0 20px 50px rgba(0, 0, 0, .4);
                    display: block;
                    border: none
                }

                .bvs-player iframe {
                    width: 100%;
                    height: 100%;
                    border: none;
                    display: block
                }

                @media(max-width:860px) {
                    .bvs-inner {
                        grid-template-columns: 1fr;
                        gap: 32px
                    }

                    .bvs-media {
                        order: -1
                    }
                }
            </style>
        @endif
    @endif

    <!-- Property Listing Section -->
    <section id="apw-resiPage" class="apw-resiPage">
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
                            <p class="apw-filterSub">Quickly refine by budget, location & type.</p>
                        </div>

                        <button type="button" class="apw-filterReset"
                            onclick="window.location.href='{{ route('properties.index') }}'">
                            Reset
                            <span class="apw-btnSvg" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                    <path d="M20 6v6h-6" stroke="#fbf8f2" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M20 12a8 8 0 1 1-2.3-5.6" stroke="#fbf8f2" stroke-width="1.7"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('properties.index') }}" id="apw-resiFilterForm">
                        <!-- Preserve property_type_slug if present -->
                        @if (request('property_type_slug'))
                            <input type="hidden" name="property_type_slug" value="{{ request('property_type_slug') }}">
                        @endif

                        <!-- City -->
                        <div class="apw-field">
                            <label class="apw-label" for="city_id">City</label>
                            <div class="apw-selectWrap">
                                <select id="city_id" name="city_id" class="apw-select">
                                    <option value="">All Cities</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
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

                        <!-- Location -->
                        <div class="apw-field">
                            <label class="apw-label" for="location_id">Location</label>
                            <div class="apw-selectWrap">
                                <select id="location_id" name="location_id" class="apw-select">
                                    <option value="">All Locations</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}"
                                            {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
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
                            <label class="apw-label" for="property_type_id">Property Type</label>
                            <div class="apw-selectWrap">
                                <select id="property_type_id" name="property_type_id" class="apw-select">
                                    <option value="">All Types</option>
                                    @foreach ($propertyTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
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

                        <!-- BHK -->
                      <div class="apw-field" id="bhk-section" style="{{ $bhks->count() > 0 ? '' : 'display:none;' }}">

    <label class="apw-label">BHK</label>

    <div class="apw-chipRow" role="group" aria-label="BHK filter">

        <button type="button"
            class="apw-chip {{ !request('bhk_id') ? 'is-active' : '' }}"
            data-bhk="">
            All
        </button>

        @foreach($bhks as $bhk)
            <button type="button"
                class="apw-chip {{ request('bhk_id') == $bhk->id ? 'is-active' : '' }}"
                data-bhk="{{ $bhk->id }}">
                {{ $bhk->name }}
            </button>
        @endforeach

    </div>

    <input type="hidden"
        name="bhk_id"
        id="apw-bhkHidden"
        value="{{ request('bhk_id') }}">

</div>

                        <!-- Project Status -->
                        <div class="apw-field">
                            <label class="apw-label" for="project_status_id">Status</label>
                            <div class="apw-selectWrap">
                                <select id="project_status_id" name="project_status_id" class="apw-select">
                                    <option value="">Any Status</option>
                                    @foreach ($projectStatuses as $status)
                                        <option value="{{ $status->id }}"
                                            {{ request('project_status_id') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
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

                        <!-- Builder -->
                        <div class="apw-field">
                            <label class="apw-label" for="builder_id">Builder</label>
                            <div class="apw-selectWrap">
                                <select id="builder_id" name="builder_id" class="apw-select">
                                    <option value="">All Builders</option>
                                    @foreach ($builders as $builder)
                                        <option value="{{ $builder->id }}"
                                            {{ request('builder_id') == $builder->id ? 'selected' : '' }}>
                                            {{ $builder->name }}
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

                        <button type="submit" class="apw-filterApply">
                            Apply Filters
                            <span class="apw-btnSvg" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                    <path d="M10 7l5 5-5 5" stroke="#fbf8f2" stroke-width="1.9" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>

                        <p class="apw-filterNote">
                            Tip: Use "Location + Budget" for best matching results.
                        </p>
                    </form>
                </aside>

                <!-- Listings Area -->
                <main class="apw-resiListings" aria-label="Residential Listings">
                    <div class="apw-listTop">
                        <div class="apw-listTopLeft">
                            <h2 class="apw-listTitle">Featured Residential Listings</h2>
                            <p class="apw-listSub">Showing {{ $properties->count() }} of {{ $properties->total() }}
                                properties</p>
                        </div>

                        <div class="apw-listTopRight">
                            <div class="apw-searchWrap">
                                <span class="apw-searchSvg" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                                        <circle cx="11" cy="11" r="6.5" stroke="#b39359"
                                            stroke-width="1.7" />
                                        <path d="M16.2 16.2L21 21" stroke="#b39359" stroke-width="1.7"
                                            stroke-linecap="round" />
                                    </svg>
                                </span>
                                <input type="text" class="apw-search" placeholder="Search by project or location..."
                                    value="{{ request('search') }}"
                                    onchange="document.getElementById('apw-resiFilterForm').search.value = this.value; document.getElementById('apw-resiFilterForm').submit();">
                                <input type="hidden" name="search" form="apw-resiFilterForm"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Property Cards -->
                    @if ($properties->count() > 0)
                        <div class="apw-cardGrid">
                            @foreach ($properties as $property)
                                <article class="apw-card">
                                    <div class="apw-cardMedia"
                                        style="background-image:url('{{ $property->main_image_url ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=70' }}');">
                                        @if ($property->projectStatus)
                                            <span class="apw-tag"
                                                style="background: {{ $property->projectStatus->tag_color ?? 'rgba(11,44,61,0.78)' }};">
                                                {{ $property->projectStatus->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="apw-cardBody">
                                        <h3 class="apw-cardTitle">{{ $property->title }}</h3>
                                        <p class="apw-cardMeta">
                                            <span class="apw-miniSvg" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none">
                                                    <path d="M12 21s7-5.2 7-11A7 7 0 1 0 5 10c0 5.8 7 11 7 11z"
                                                        stroke="#b39359" stroke-width="1.7" />
                                                    <circle cx="12" cy="10" r="2.3" stroke="#b39359"
                                                        stroke-width="1.7" />
                                                </svg>
                                            </span>
                                            {{ $property->city->name ?? '' }} • {{ $property->bhk->name ?? '' }} •
                                            {{ $property->propertyType->name ?? '' }}
                                        </p>
                                        <div class="apw-cardRow">
                                            <div class="apw-price">
                                                <span class="apw-priceLabel">Starting</span>
                                                <span class="apw-priceVal">{{ $property->formatted_price }}</span>
                                            </div>
                                            <div class="apw-ctaRow">
                                                <a class="apw-btnOutline"
                                                    href="{{ route('properties.show', $property->slug) }}">View
                                                    Details</a>
                                            </div>
                                        </div>
                                        @if ($property->amenities->count() > 0)
                                            <div class="apw-amenities">
                                                @foreach ($property->amenities->take(3) as $amenity)
                                                    <span>{{ $amenity->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $properties->links() }}
                        </div>
                    @else
                        <div class="apw-empty">
                            <div class="apw-emptyBox">
                                <div class="apw-emptySvg" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none">
                                        <path d="M4 10.8l8-6 8 6V20a1.6 1.6 0 0 1-1.6 1.6H5.6A1.6 1.6 0 0 1 4 20v-9.2z"
                                            stroke="#b39359" stroke-width="1.7" stroke-linejoin="round" />
                                        <path d="M9.5 21.6V14h5v7.6" stroke="#b39359" stroke-width="1.7"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <h3>No matching properties found</h3>
                                <p>Try changing location, budget, or property type — or reset all filters.</p>
                                <button type="button" class="apw-filterApply"
                                    onclick="window.location.href='{{ route('properties.index') }}'">Reset
                                    Filters</button>
                            </div>
                        </div>
                    @endif

                    <!-- CTA Strip -->
                    <div class="apw-ctaStrip" id="enquiry">
                        <div class="apw-ctaLeft">
                            <h3>Need help shortlisting the right home?</h3>
                            <p>Share your requirement and we'll suggest best residential options in your preferred location.
                            </p>
                        </div>
                        <div class="apw-ctaRight">
                            <a class="apw-ctaBtn" href="#contact">Get a Call Back</a>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- Perspective Section -->
    @if ($perspectiveSection && $perspectiveSection->images && count($perspectiveSection->images) >= 4)
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
                        @foreach ($perspectiveSection->images_urls as $index => $imageUrl)
                            @if ($index < 4)
                                <a class="apw-propPerspective__card" href="#"
                                    aria-label="Open property image {{ $index + 1 }}">
                                    <img class="apw-propPerspective__img" src="{{ $imageUrl }}"
                                        alt="Property view {{ $index + 1 }}" loading="lazy" />
                                    <div class="apw-propPerspective__cap">
                                        <div class="apw-propPerspective__capTitle">Property View {{ $index + 1 }}</div>
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
        // Carousel functionality
        (function() {
            const root = document.querySelector("[data-carousel]");
            if (!root) return;

            const track = root.querySelector("[data-track]");
            const slides = Array.from(track.children);
            const prevBtn = root.querySelector("[data-prev]");
            const nextBtn = root.querySelector("[data-next]");
            const dotsWrap = root.querySelector("[data-dots]");

            let index = 0;
            let timer = null;

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
                const dots = dotsWrap.querySelectorAll(".dot");
                dots.forEach((d, i) => d.setAttribute("aria-current", i === index ? "true" : "false"));
            }

            function goTo(i) {
                index = (i + slides.length) % slides.length;
                track.style.transform = `translateX(-${index * 100}%)`;
                updateDots();
                restartAutoplay();
            }

            function next() {
                goTo(index + 1);
            }

            function prev() {
                goTo(index - 1);
            }

            function startAutoplay() {
                stopAutoplay();
                timer = setInterval(next, 3500);
            }

            function stopAutoplay() {
                if (timer) clearInterval(timer);
                timer = null;
            }

            function restartAutoplay() {
                startAutoplay();
            }

            nextBtn.addEventListener("click", next);
            prevBtn.addEventListener("click", prev);

            root.addEventListener("mouseenter", stopAutoplay);
            root.addEventListener("mouseleave", startAutoplay);

            let startX = 0,
                dx = 0,
                dragging = false;

            root.addEventListener("touchstart", (e) => {
                dragging = true;
                startX = e.touches[0].clientX;
                dx = 0;
                stopAutoplay();
            }, {
                passive: true
            });

            root.addEventListener("touchmove", (e) => {
                if (!dragging) return;
                dx = e.touches[0].clientX - startX;
            }, {
                passive: true
            });

            root.addEventListener("touchend", () => {
                dragging = false;
                if (Math.abs(dx) > 40) {
                    dx < 0 ? next() : prev();
                } else {
                    restartAutoplay();
                }
            });

            buildDots();
            goTo(0);
            startAutoplay();
        })();

        // BHK Chip Buttons
        document.querySelectorAll('.apw-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                // Remove active class from all chips
                document.querySelectorAll('.apw-chip').forEach(c => c.classList.remove('is-active'));

                // Add active class to clicked chip
                this.classList.add('is-active');

                // Update hidden input value
                const bhkValue = this.getAttribute('data-bhk');
                document.getElementById('apw-bhkHidden').value = bhkValue;

                // Submit form
                document.getElementById('apw-resiFilterForm').submit();
            });
        });

        // Update BHK filter when property type changes
document.getElementById('property_type_id').addEventListener('change', function () {

    const propertyTypeId = this.value;

    const bhkContainer = document.querySelector('.apw-chipRow');

    const bhkSection = document.getElementById('bhk-section');

    const bhkHiddenInput = document.getElementById('apw-bhkHidden');

    // Loading
    bhkContainer.innerHTML =
        '<span style="color:#b39359;">Loading...</span>';

    fetch('{{ route("api.bhks-by-property-type") }}?property_type_id=' + propertyTypeId)

        .then(response => response.json())

        .then(data => {

            // Clear old buttons
            bhkContainer.innerHTML = '';

            // Hide entire BHK section if no data
            if (!data.success || !data.bhks || data.bhks.length === 0) {

                bhkSection.style.display = 'none';

                bhkHiddenInput.value = '';

                return;
            }

            // Show BHK section
            bhkSection.style.display = 'block';

            // ===== ALL Button =====
            const allBtn = document.createElement('button');

            allBtn.type = 'button';

            allBtn.className = 'apw-chip is-active';

            allBtn.setAttribute('data-bhk', '');

            allBtn.textContent = 'All';

            allBtn.addEventListener('click', function () {

                document.querySelectorAll('.apw-chip')
                    .forEach(c => c.classList.remove('is-active'));

                this.classList.add('is-active');

                bhkHiddenInput.value = '';

                document.getElementById('apw-resiFilterForm').submit();
            });

            bhkContainer.appendChild(allBtn);

            // ===== Dynamic BHK Buttons =====
            data.bhks.forEach(bhk => {

                const btn = document.createElement('button');

                btn.type = 'button';

                btn.className = 'apw-chip';

                btn.setAttribute('data-bhk', bhk.id);

                btn.textContent = bhk.name;

                btn.addEventListener('click', function () {

                    document.querySelectorAll('.apw-chip')
                        .forEach(c => c.classList.remove('is-active'));

                    this.classList.add('is-active');

                    bhkHiddenInput.value = bhk.id;

                    document.getElementById('apw-resiFilterForm').submit();
                });

                bhkContainer.appendChild(btn);
            });

            // Reset hidden field
            bhkHiddenInput.value = '';
        })

        .catch(error => {

            console.error('Error fetching BHKs:', error);

            bhkSection.style.display = 'none';

            bhkContainer.innerHTML =
                '<span style="color:#dc2626;">Error loading BHKs</span>';
        });
});

        // Auto-submit form on filter change (except property_type_id which is handled above)
        document.querySelectorAll('#apw-resiFilterForm select').forEach(select => {
            if (select.id !== 'property_type_id') {
                select.addEventListener('change', function() {
                    document.getElementById('apw-resiFilterForm').submit();
                });
            }
        });
    </script>
@endsection
