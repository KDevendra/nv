@extends('layouts.app')
@section('title', 'Acre to Hectare - ZendoIndia')
@section('content')
    <style>
        /* ABOUT BANNER SECTION */
        .about-banner-section {
            position: relative;
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            /* Change image */
            background-size: cover;
            background-position: center;
            padding: 160px 0 80px;
            /* Top padding for overlap header */
            color: #fff;
        }

        /* Dark overlay */
        .about-banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        /* Container */
        .about-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        /* Left Content */
        .about-banner-left {
            max-width: 600px;
        }

        /* Heading */
        .about-banner-heading {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        /* Breadcrumb */
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

        /* Responsive */
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
        #apw-calcHubV2{ padding:40px 20px 16px; overflow:hidden; }
#apw-calcHubV2 .apw-calcHubV2__wrap{ max-width:1200px; margin:0 auto; }
#apw-calcHubV2 .apw-calcHubV2__layout{
  display:grid;
  grid-template-columns:minmax(0,7fr) minmax(0,3fr);
  gap:20px;
  align-items:start;
}

#apw-calcHubV2 .apw-calcHubV2__head{ text-align:left; margin-bottom:14px; padding:0 2px; }
#apw-calcHubV2 .apw-calcHubV2__title{
  color:#0b2c3d; font-size:35px!important;
  line-height:1.15; margin:0 0 8px; letter-spacing:.2px; font-weight:900;
}
#apw-calcHubV2 .apw-calcHubV2__sub{
  margin:0; color:rgba(11,44,61,.75);
  font-size:16.5px; line-height:1.6; max-width:760px;
}

#apw-calcHubV2 .apw-calcCardV2{
  position:relative;
  border-radius:22px;
  overflow:hidden;
  border:1px solid rgba(11,44,61,.18);

  background-image: url("https://zendoindia.com/new-home/zendo/assets/images/bg/form-b.jpg");
  background-size: cover;
  background-position: center;
  background-repeat:no-repeat;
}

/* Overlay using your color (#b39359) with opacity */
#apw-calcHubV2 .apw-calcCardV2::before{
  content:"";
  position:absolute;
  inset:0;
  background: rgb(11 44 61 / 63%);
  z-index:0;
}

/* Extra soft dark layer for premium readability (matches #0b2c3d) */
#apw-calcHubV2 .apw-calcCardV2::after{
  content:"";
  position:absolute;
  inset:0;
  background: linear-gradient(180deg, rgba(11,44,61,.20), rgba(11,44,61,.35));
  z-index:0;
}

/* Put all content above overlay */
#apw-calcHubV2 .apw-calcCardV2__grid,
#apw-calcHubV2 .apw-calcFormV2{
  position:relative;
  z-index:1;
}

#apw-calcHubV2 .apw-calcCardV2__grid{ display:block; }

#apw-calcHubV2 .apw-calcFormV2{ padding:22px 20px; }
#apw-calcHubV2 .apw-fieldV2{ margin-bottom:12px; }


#apw-calcHubV2 .apw-inputV2{
  width:100%;
  padding:18px 12px;
  border-radius:14px;

  border:1px solid rgba(255,255,255,.26);
  background: rgba(255,255,255,.14);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);

  color:#ffffff;
  font-size:15px;
  outline:none;
  transition:box-shadow .18s ease,border-color .18s ease, transform .18s ease, background .18s ease;
  min-width:0;
}

/* Placeholder (for safety) */
#apw-calcHubV2 .apw-inputV2::placeholder{ color: rgba(255,255,255,.72); }

/* Focus */
#apw-calcHubV2 .apw-inputV2:focus{
  border-color: rgba(255,255,255,.55);
  box-shadow: 0 0 0 4px rgba(11,44,61,.22);
  transform: translateY(-1px);
  background: rgba(255,255,255,.18);
}

/* ===== Row alignment (same widths) ===== */
#apw-calcHubV2 .apw-rowV2{
  display:grid;
  grid-template-columns: 1fr 50%;
  gap:10px;
  align-items:stretch;
}
#apw-calcHubV2 .apw-selectWrapV2{ position:relative; }

/* Floating label */
#apw-calcHubV2 .apw-floatV2{ position:relative; min-width:0; }
#apw-calcHubV2 .apw-floatV2 .apw-inputV2--num{ padding-top:20px; }
#apw-calcHubV2 .apw-floatLabelV2{
  position:absolute;
  left:12px;
  top:50%;
  font-size:13px;
  font-weight:900;
  color: rgba(255,255,255,.86);
  pointer-events:none;
  padding:0 4px;
  border-radius:6px;
}
#apw-calcHubV2 .apw-floatV2 .apw-inputV2--num:focus + .apw-floatLabelV2,
#apw-calcHubV2 .apw-floatV2 .apw-inputV2--num:not(:placeholder-shown) + .apw-floatLabelV2{
  top:-10px;
  transform:none;
  font-size:13.5px;
  color:white;
  border: 1px solid rgba(255, 255, 255, .26);
  background: rgba(255, 255, 255, .14);
  backdrop-filter: blur(10px);
  width:17%;
  padding: 2px 10px;
  border-radius:8px;
  box-shadow:
    0 4px 14px rgba(0, 0, 0, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.18);
}

#apw-calcHubV2 .apw-floatV2 .apw-inputV2--num[readonly]{
  cursor:not-allowed;
  opacity:1;
  background: rgba(255,255,255,.12);
}

#apw-calcHubV2 select.apw-inputV2--unit{
  padding-right:44px;
  cursor:pointer;

  -webkit-appearance:none;
  -moz-appearance:none;
  appearance:none;

  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23ffffff' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-size:18px 18px;
  background-position: calc(100% - 14px) 50%;
}

/* Fix: option text readable */
#apw-calcHubV2 select.apw-inputV2--unit option{
  color:#0b2c3d;
  background:#ffffff;
}


#apw-calcHubV2 .apw-helpV2{
  display:block;
  margin-top:6px;
  color: rgba(255,255,255,.92);
  font-size:14px;
  line-height:1.4;
}

/* Swap */
#apw-calcHubV2 .apw-swapMidV2{ display:flex; justify-content:center; margin:15px 0; }
#apw-calcHubV2 .apw-swapBtnV2{
  width:46px; height:46px; border-radius:14px;
  border:1px solid rgba(255,255,255,.26);
  background: rgba(255,255,255,.14);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  cursor:pointer; color:#ffffff;
  display:inline-flex; align-items:center; justify-content:center;
  transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
  user-select:none;
}
#apw-calcHubV2 .apw-swapBtnV2:hover{ transform:translateY(-1px); box-shadow:0 14px 34px rgba(11,44,61,.18); background: rgba(255,255,255,.18); }
#apw-calcHubV2 .apw-swapBtnV2:active{ transform:translateY(1px); }

/* Alert */
#apw-calcHubV2 .apw-alertV2{
  font-size:12.5px;
  color:#ffffff;
  font-weight:900;
  margin:8px 0 2px;
  text-shadow: 0 2px 10px rgba(11,44,61,.25);
}

/* Buttons */
@media (min-width: 768px){
  #apw-calcHubV2 .apw-actionsV2{
    display: flex;
    gap: 10px;
  }

  #apw-calcHubV2 .apw-actionsV2 .apw-btnV2{
    flex: 1 1 50%;
    width: 50%;
  }
}

/* Mobile: full width buttons */
@media (max-width: 767px){
  #apw-calcHubV2 .apw-actionsV2 .apw-btnV2{
    width: 100%;
    margin-bottom:5px;
  }
}

#apw-calcHubV2 .apw-btnV2{
  display:inline-flex; align-items:center; justify-content:center;
  padding:12px 16px;
  border-radius:14px;
  font-weight:900;
  font-size:15px;
  cursor:pointer;
  border:1px solid transparent;
  transition:transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
  user-select:none;
  white-space:nowrap;
}
#apw-calcHubV2 .apw-btnV2:active{ transform:translateY(1px); }

#apw-calcHubV2 .apw-btnV2--primary{
  background:#b39359;
  color:#ffffff;
  border-color: rgba(255,255,255,.28);
  box-shadow:0 14px 30px rgba(11,44,61,.20);
}
#apw-calcHubV2 .apw-btnV2--primary:hover{ box-shadow:0 18px 42px rgba(11,44,61,.28); transform:translateY(-1px); }

/* Ghost also white UI */
#apw-calcHubV2 .apw-btnV2--ghost{
  background: rgba(255,255,255,.14);
  color:#ffffff;
  border-color: rgba(255,255,255,.32);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}
#apw-calcHubV2 .apw-btnV2--ghost:hover{
  background: rgba(255,255,255,.18);
  transform:translateY(-1px);
}

/* Disclaimer should be white */
#apw-calcHubV2 .apw-disclaimerV2{ margin-top:0; font-size:12px; opacity:1; line-height:1.5; }
#apw-calcHubV2 .apw-disclaimerV2--light{ color: rgba(255,255,255,.90); margin-top:12px; }

/* Sidebar */
#apw-calcHubV2 .apw-sideCardV2{
  background:#fff; border-radius:12px; border:1px solid rgba(11,44,61,.12);
  box-shadow:0 14px 34px rgba(11,44,61,.06);
  overflow:hidden; position:sticky; top:18px;
}
#apw-calcHubV2 .apw-sideCardV2__top{
  padding:18px 16px; border-bottom:1px solid rgba(11,44,61,.10);
  background:radial-gradient(circle at 10% 10%, rgba(179,147,89,.12), transparent 55%);
}
#apw-calcHubV2 .apw-sideCardV2__title{ margin:0 0 6px; color:#0b2c3d; font-size:22px; font-weight:900;font-family:'forum'; }
#apw-calcHubV2 .apw-sideCardV2__hint{ margin:0; color:rgba(11,44,61,.72); font-size:13px; line-height:1.5; }
#apw-calcHubV2 .apw-sideNavV2{ padding:12px; display:flex; flex-direction:column; gap:10px; }
#apw-calcHubV2 .apw-sideNavV2__item{
  display:flex; align-items:center;
  padding:12px 12px; border-radius:14px;
  background:#fcfaf5; border:1px solid rgba(11,44,61,.14);
  text-decoration:none;
  transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
#apw-calcHubV2 .apw-sideNavV2__item:hover{
  transform:translateY(-1px);
  border-color:rgba(179,147,89,.55);
  box-shadow:0 12px 26px rgba(11,44,61,.08);
}
#apw-calcHubV2 .apw-sideNavV2__item.is-active{ background:rgba(179,147,89,.12); border-color:rgba(179,147,89,.40); }
#apw-calcHubV2 .apw-sideNavV2__text{ font-weight:900; color:#0b2c3d; font-size:14px; letter-spacing:.2px; }

#apw-calcHubV2 .apw-selectWrapV2 .apw-floatLabelV2--unit{
  top:-10px;
  transform:none;
  font-size:13.5px;
  color:#ffffff;
  border:1px solid rgba(255,255,255,.26);
  background: rgba(255,255,255,.14);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding:2px 10px;
  border-radius:8px;
  width:17%;
  box-shadow:
    0 4px 14px rgba(0, 0, 0, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.18);
}

/* Responsive */
@media (max-width: 980px){
  #apw-calcHubV2 .apw-calcHubV2__layout{ grid-template-columns:1fr; }
  #apw-calcHubV2 .apw-sideCardV2{ position:static; }
}
@media (max-width: 640px){
  #apw-calcHubV2{ padding:18px 14px; }
  #apw-calcHubV2 .apw-calcHubV2__sub{ font-size:14.5px; }
  #apw-calcHubV2 .apw-calcFormV2{ padding:28px 10px; }
  #apw-calcHubV2 .apw-rowV2{ grid-template-columns: 1fr 150px; gap:8px;}
  #apw-calcHubV2 .apw-inputV2{ padding:17px 10px; font-size:14.5px; }
  #apw-calcHubV2 .apw-floatV2 .apw-inputV2--num{ padding-top:18px; }
  #apw-calcHubV2 .apw-calcHubV2__side{ display:none; }

  #apw-calcHubV2 .apw-floatV2 .apw-inputV2--num:not(:placeholder-shown) + .apw-floatLabelV2{
    top:-10px;
    transform:none;
    font-size:13px;
    color:white;
    border: 1px solid white;
    background: rgba(255, 255, 255, .14);
    backdrop-filter: blur(10px);
    padding: 2px 10px;
    border: 1px solid rgba(255, 255, 255, .26);
    width:40%;
    border-radius:8px;
    box-shadow:
      0 4px 14px rgba(0, 0, 0, 0.35),
      inset 0 1px 0 rgba(255, 255, 255, 0.18);
  }

  #apw-calcHubV2 .apw-selectWrapV2 .apw-floatLabelV2--unit{
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
    width: 40%!important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.18);
  }

  #apw-calcHubV2 .apw-calcHubV2__title{
    color:#0b2c3d; font-size:25px!important;
    line-height:1.15; margin:0 0 8px; letter-spacing:.2px; font-weight:900;
  }
}

    </style>
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div class="about-banner-left">
                <h1 class="about-banner-heading">Acre to Hectare</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <p>Acre to Hectare</p>
                </div>
            </div>
        </div>
    </section>
    <section id="apw-calcHubV2" class="apw-calcHubV2">
        <div class="apw-calcHubV2__wrap">
            <div class="apw-calcHubV2__head">
                <h2 class="apw-calcHubV2__title">Acre to Hectare Converter</h2>
                <p class="apw-calcHubV2__sub">Enter value, choose units, and convert instantly.</p>
            </div>
            <div class="apw-calcHubV2__layout">
                <div class="apw-calcHubV2__main">
                    <div class="apw-calcCardV2">
                        <div class="apw-calcCardV2__grid">
                            <form class="apw-calcFormV2" id="apwV2-unitForm" novalidate>
                                <div class="apw-fieldV2">
                                    <div class="apw-rowV2">
                                        <div class="apw-floatV2">
                                            <input class="apw-inputV2 apw-inputV2--num" type="number" id="apwV2-fromValue"
                                                min="0" step="0.0001" value="1" required
                                                placeholder="Add your value here*" />
                                            <span class="apw-floatLabelV2">Value</span>
                                        </div>
                                        <div class="apw-selectWrapV2">
                                            <select class="apw-inputV2 apw-inputV2--unit" id="apwV2-fromUnit" required>
                                                <option value="acre" selected>Acre (ac)</option>
                                                <option value="hectare">Hectare (ha)</option>
                                                <option value="sqm">Square Meter (sq m)</option>
                                                <option value="sqkm">Square Kilometer (sq km)</option>
                                                <option value="sqft">Square Foot (sq ft)</option>
                                                <option value="sqyd">Square Yard (Gaj) (sq yd)</option>
                                                <option value="bigha">Bigha</option>
                                                <option value="biswa">Biswa</option>
                                                <option value="biswansi">Biswansi</option>
                                                <option value="killa">Killa</option>
                                                <option value="marla">Marla</option>
                                                <option value="kanal">Kanal</option>
                                                <option value="nali">Nali</option>
                                                <option value="guntha">Guntha / Gunta</option>
                                                <option value="are">Aare (Are)</option>
                                                <option value="cent">Cent</option>
                                                <option value="ground">Ground</option>
                                                <option value="ankanam">Ankanam</option>
                                                <option value="kuzhi">Kuzhi</option>
                                                <option value="ma">Ma</option>
                                                <option value="kani">Kani</option>
                                                <option value="veli">Veli / Veeli</option>
                                                <option value="katha">Katha</option>
                                                <option value="lessa">Lessa</option>
                                                <option value="dhur">Dhur</option>
                                                <option value="decimal">Decimal</option>
                                                <option value="satak">Satak</option>
                                                <option value="chhatak">Chhatak (Chatak)</option>
                                                <option value="cottah">Cottah (Katha - 720 sq ft)</option>
                                                <option value="ganda">Ganda</option>
                                                <option value="vigha">Vigha (Vigha/Viga)</option>
                                                <option value="paisa">Paisa</option>
                                                <option value="sqkaram">Karam (Square Karam)</option>
                                            </select>
                                            <span class="apw-floatLabelV2 apw-floatLabelV2--unit">From</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="apw-swapMidV2" aria-label="Swap units">
                                    <button class="apw-swapBtnV2" type="button" id="apwV2-swap" title="Swap From/To">
                                        <span class="apw-swapIconV2" aria-hidden="true">
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
                                            <input class="apw-inputV2 apw-inputV2--num" type="text" id="apwV2-toValue"
                                                value="0" readonly placeholder=" " />
                                            <span class="apw-floatLabelV2">Value</span>
                                        </div>
                                        <div class="apw-selectWrapV2">
                                            <select class="apw-inputV2 apw-inputV2--unit" id="apwV2-toUnit" required>
                                                <option value="hectare" selected>Hectare (ha)</option>
                                                <option value="acre">Acre (ac)</option>
                                                <option value="sqm">Square Meter (sq m)</option>
                                                <option value="sqkm">Square Kilometer (sq km)</option>
                                                <option value="sqft">Square Foot (sq ft)</option>
                                                <option value="sqyd">Square Yard (Gaj) (sq yd)</option>
                                                <option value="bigha">Bigha</option>
                                                <option value="biswa">Biswa</option>
                                                <option value="biswansi">Biswansi</option>
                                                <option value="killa">Killa</option>
                                                <option value="marla">Marla</option>
                                                <option value="kanal">Kanal</option>
                                                <option value="nali">Nali</option>
                                                <option value="guntha">Guntha / Gunta</option>
                                                <option value="are">Aare (Are)</option>
                                                <option value="cent">Cent</option>
                                                <option value="ground">Ground</option>
                                                <option value="ankanam">Ankanam</option>
                                                <option value="kuzhi">Kuzhi</option>
                                                <option value="ma">Ma</option>
                                                <option value="kani">Kani</option>
                                                <option value="veli">Veli / Veeli</option>
                                                <option value="katha">Katha</option>
                                                <option value="lessa">Lessa</option>
                                                <option value="dhur">Dhur</option>
                                                <option value="decimal">Decimal</option>
                                                <option value="satak">Satak</option>
                                                <option value="chhatak">Chhatak (Chatak)</option>
                                                <option value="cottah">Cottah (Katha - 720 sq ft)</option>
                                                <option value="ganda">Ganda</option>
                                                <option value="vigha">Vigha (Vigha/Viga)</option>
                                                <option value="paisa">Paisa</option>
                                                <option value="sqkaram">Karam (Square Karam)</option>
                                            </select>
                                            <span class="apw-floatLabelV2 apw-floatLabelV2--unit">To</span>
                                        </div>
                                    </div>
                                    <small class="apw-helpV2" id="apwV2-resultHint">Converted value will appear
                                        above</small>
                                </div>
                                <div class="apw-alertV2" id="apwV2-alert" aria-live="polite"></div>
                                <div class="apw-actionsV2">
                                    <button type="button" class="apw-btnV2 apw-btnV2--primary"
                                        id="apwV2-convert">Convert</button>
                                    <button type="button" class="apw-btnV2 apw-btnV2--ghost"
                                        id="apwV2-reset">Reset</button>
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
                            <a class="apw-sideNavV2__item is-active" href="{{ route('calculators.acre-to-hectare') }}" aria-current="page">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 7h10v10H7V7z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Acre to Hectare</span>
                            </a>
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.acre-to-squaremeter') }}">
                                <span class="apw-sideNavV2__icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 3h18v18H3V3z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 9h6v6H9V9z" stroke="#b39359" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="apw-sideNavV2__text">Acre to Square Meter</span>
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
                            <a class="apw-sideNavV2__item" href="{{ route('calculators.length-calculator') }}">
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
                const root = document.getElementById("apw-calcHubV2");
                if (!root) return;

                const fromValue = root.querySelector("#apwV2-fromValue");
                const fromUnit = root.querySelector("#apwV2-fromUnit");
                const toValue = root.querySelector("#apwV2-toValue");
                const toUnit = root.querySelector("#apwV2-toUnit");
                const alertBox = root.querySelector("#apwV2-alert");
                const btnConvert = root.querySelector("#apwV2-convert");
                const btnReset = root.querySelector("#apwV2-reset");
                const btnSwap = root.querySelector("#apwV2-swap");

                /* Base unit = Acre */
                const factorToAcre = {


                    acre: 1,
                    hectare: 2.4710538147,



                    sqm: 1 / 4046.8564224,
                    sqkm: 247.105381467,

                    sqft: 1 / 43560,
                    sqyd: 1 / 4840,

                    gaj: 1 / 4840,




                    cent: 0.01,

                    ground: 2400 / 43560,

                    are: 100 / 4046.8564224,




                    marla: 1 / 160,

                    kanal: 1 / 8,

                    killa: 1,




                    nali: 2160 / 43560,




                    guntha: 1 / 40,

                    gunta: 1 / 40,




                    ankanam: 72 / 43560,




                    kuzhi: 144 / 43560,

                    ma: 1 / 3,

                    kani: 1.32,

                    veli: 6.17,

                    veeli: 6.17,

                    veeli2: 6.17,




                    katha: 1361 / 43560,

                    cottah: 720 / 43560,

                    lessa: (1361 / 43560) / 20,

                    dhur: 0.000082644628099,

                    chhatak: 45 / 43560,

                    decimal: 0.01,

                    satak: 0.01,

                    ganda: 864 / 43560,




                    biswa: 0.03125,
                    biswansi: 544.5 / 43560,
                    vasansi: 544.5 / 43560,


                    bigha: 0.25,
                    vigha: 0.25,


                    paisa: 0.00196424471992654,


                    sqkaram: 30.25 / 43560
                };

                function format(n) {
                    if (!isFinite(n)) return "0";
                    return Number(n).toLocaleString("en-IN", {
                        maximumFractionDigits: 6
                    });
                }

                function convert() {
                    const val = Number(fromValue.value);
                    if (!isFinite(val) || val < 0) {
                        alertBox.textContent = "Please enter a valid value.";
                        toValue.value = "0";
                        return;
                    }



                    if (!factorToAcre[fromUnit.value] || !factorToAcre[toUnit.value]) {
                        alertBox.textContent = "Selected unit conversion is not available.";
                        toValue.value = "0";
                        return;
                    }

                    alertBox.textContent = "";
                    const acres = val * factorToAcre[fromUnit.value];
                    const result = acres / factorToAcre[toUnit.value];
                    toValue.value = format(result);
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
                    fromUnit.value = "acre";
                    toUnit.value = "hectare";
                    convert();
                });

                /* Initial Load → Acre to Hectare */
                fromValue.value = 1;
                fromUnit.value = "acre";
                toUnit.value = "hectare";
                convert();
            })();
        </script>
    </section>
@endsection
