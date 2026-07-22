@extends('layouts.app')
@section('title', 'Cent to Square Feet - ZendoIndia')
@section('content')
<style>
.about-banner-section{position:relative;background-image:url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');background-size:cover;background-position:center;padding:160px 0 80px;color:#fff}
.about-banner-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgb(0 0 0/62%)}
.about-banner-container{position:relative;max-width:1250px;margin:auto;padding:0 20px}
.about-banner-heading{font-size:48px;font-weight:700;margin-bottom:15px}
.about-breadcrumb{display:flex;align-items:center;gap:8px;font-size:16px}
.about-breadcrumb a{color:#fff;text-decoration:none;font-weight:500}
.about-breadcrumb p{margin:0;opacity:.8}
@media(max-width:767px){.about-banner-heading{font-size:32px}.about-banner-section{padding:130px 0 60px}}
</style>

<section class="about-banner-section">
    <div class="about-banner-overlay"></div>
    <div class="about-banner-container">
        <h1 class="about-banner-heading">Cent to Square Feet</h1>
        <div class="about-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>

            <p>Cent to Square Feet</p>
        </div>
    </div>
</section>


<section id="apw-calcHubV2" class="apw-calcHubV2">
  <div class="apw-calcHubV2__wrap">

    <div class="apw-calcHubV2__layout">

      <!-- LEFT (70%) : Heading + Calculator -->
      <div class="apw-calcHubV2__main">

        <!-- Heading -->
        <header class="apw-calcHubV2__head">
          <h2 class="apw-calcHubV2__title">Land Unit Calculator</h2>
          <p class="apw-calcHubV2__sub">
            Use this Cent to Square Feet converter to instantly calculate land area. Enter cents and see results with a visual graph.
          </p>
        </header>

        <!-- SINGLE CALCULATOR -->
        <div class="apw-calcCardV2">
          <div class="apw-calcCardV2__top">
            <h3 class="apw-calcCardV2__title">Cent to Square Feet Converter</h3>
            <p class="apw-calcCardV2__hint">
              Enter area in cents. Results update instantly.
            </p>
          </div>

          <div class="apw-calcCardV2__grid">
            <!-- Left: Inputs -->
            <form class="apw-calcFormV2" id="apwV2-centForm" novalidate>

              <div class="apw-fieldV2">
                <label class="apw-labelV2" for="apwV2-cent-value">
                  Area in Cents <span class="apw-reqV2">*</span>
                </label>
                <div class="apw-inputRowV2">
                  <input class="apw-inputV2" type="number" id="apwV2-cent-value" min="0" step="0.01" value="10" required />
                </div>
                <small class="apw-helpV2">Example: 5 cents</small>
              </div>

              <div class="apw-alertV2" id="apwV2-centAlert" aria-live="polite"></div>

              <div class="apw-actionsV2">
                <button type="button" class="apw-btnV2 apw-btnV2--ghost" id="apwV2-centReset">Reset</button>
                <a href="#apw-propertyCta" class="apw-btnV2 apw-btnV2--primary">Explore Properties</a>
              </div>
            </form>

            <!-- Right: Results -->
            <aside class="apw-calcResultsV2" aria-label="Cent to square feet results">
              <div class="apw-resultHeroV2">
                <span class="apw-resultHeroV2__label">Converted Area</span>
                <strong class="apw-resultHeroV2__value" id="apwV2-cent-sqftHero">0 sq ft</strong>
                <span class="apw-resultHeroV2__note">Instant conversion</span>
              </div>

              <div class="apw-resultGridV2">
                <div class="apw-resultItemV2">
                  <span>Cents Entered</span>
                  <strong id="apwV2-cent-centOut">0</strong>
                </div>
                <div class="apw-resultItemV2">
                  <span>Square Feet</span>
                  <strong id="apwV2-cent-sqftOut">0</strong>
                </div>
                <div class="apw-resultItemV2">
                  <span>Square Meter (approx)</span>
                  <strong id="apwV2-cent-sqmOut">0</strong>
                </div>
                <div class="apw-resultItemV2">
                  <span>Acres (approx)</span>
                  <strong id="apwV2-cent-acreOut">0</strong>
                </div>
              </div>

              <!-- Graph -->
              <div class="apw-graphV2" aria-label="Graph representation">
                <div class="apw-graphV2__head">
                  <span class="apw-graphV2__title">Visual Graph</span>
                  <span class="apw-graphV2__meta" id="apwV2-cent-graphMeta">Cent vs Sq Ft</span>
                </div>

                <div class="apw-barsV2">
                  <div class="apw-barV2">
                    <div class="apw-barV2__top">
                      <span class="apw-barV2__label">Cents</span>
                      <strong class="apw-barV2__val" id="apwV2-cent-barCentVal">0</strong>
                    </div>
                    <div class="apw-barV2__track">
                      <div class="apw-barV2__fill" id="apwV2-cent-barCent"></div>
                    </div>
                  </div>

                  <div class="apw-barV2">
                    <div class="apw-barV2__top">
                      <span class="apw-barV2__label">Square Feet</span>
                      <strong class="apw-barV2__val" id="apwV2-cent-barSqftVal">0</strong>
                    </div>
                    <div class="apw-barV2__track">
                      <div class="apw-barV2__fill" id="apwV2-cent-barSqft"></div>
                    </div>
                  </div>
                </div>
              </div>

              <p class="apw-disclaimerV2">
                * 1 Cent = 435.6 Square Feet (because 1 Acre = 100 Cents and 1 Acre = 43,560 sq ft).
              </p>
            </aside>
          </div>
        </div>
      </div>

      <!-- RIGHT (30%) : Sidebar calculators list -->
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
            <a class="apw-sideNavV2__item is-active" href="{{ route('calculators.cent-to-square-feet') }}" aria-current="page">
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

  <style>
    /* ========= COLORS =========
      Navy: #0b2c3d
      Gold: #b39359
      Offwhite: #fcfaf5
    =========================== */

    #apw-calcHubV2 {
      background: #fcfaf5;
      padding: 60px 16px;
      overflow: hidden;
    }

    #apw-calcHubV2 .apw-calcHubV2__wrap {
      max-width: 1200px;
      margin: 0 auto;
    }

    /* 70 / 30 layout */
    #apw-calcHubV2 .apw-calcHubV2__layout {
      display: grid;
      grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
      gap: 20px;
      align-items: start;
    }

    /* LEFT */
    #apw-calcHubV2 .apw-calcHubV2__head {
      text-align: left;
      margin-bottom: 14px;
      padding: 0 2px;
    }

    #apw-calcHubV2 .apw-calcHubV2__title {
      color: #0b2c3d;
      font-size: clamp(24px, 2.4vw, 36px);
      line-height: 1.2;
      margin: 0 0 8px;
      letter-spacing: .2px;
      font-weight: 900;
    }

    #apw-calcHubV2 .apw-calcHubV2__sub {
      margin: 0;
      color: rgba(11, 44, 61, .75);
      font-size: 17px;
      line-height: 1.6;
      max-width: 760px;
    }

    /* Calculator card */
    #apw-calcHubV2 .apw-calcCardV2 {
      background: #ffffff;
      border-radius: 20px;
      border: 1px solid rgba(11, 44, 61, .12);
      box-shadow: 0 14px 34px rgba(11, 44, 61, .08);
      overflow: hidden;
    }

    #apw-calcHubV2 .apw-calcCardV2__top {
      padding: 20px 18px;
      border-bottom: 1px solid rgba(11, 44, 61, .10);
      background: radial-gradient(circle at 10% 10%, rgba(179, 147, 89, .14), transparent 52%);
    }

    #apw-calcHubV2 .apw-calcCardV2__title {
      margin: 0 0 6px;
      color: #0b2c3d;
      font-size: 18px;
      font-weight: 900;
    }

    #apw-calcHubV2 .apw-calcCardV2__hint {
      margin: 0;
      color: rgba(11, 44, 61, .76);
      font-size: 14px;
      line-height: 1.6;
    }

    #apw-calcHubV2 .apw-calcCardV2__grid {
      display: grid;
      grid-template-columns: 6fr 4fr;
      gap: 0;
      align-items: stretch;
    }

    /* Form */
    #apw-calcHubV2 .apw-calcFormV2 {
      padding: 20px 18px;
    }

    #apw-calcHubV2 .apw-fieldV2 {
      margin-bottom: 16px;
    }

    #apw-calcHubV2 .apw-labelV2 {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 900;
      color: #0b2c3d;
      font-size: 13.5px;
      margin-bottom: 6px;
    }

    #apw-calcHubV2 .apw-reqV2 {
      color: #b39359;
      font-weight: 900;
    }

    #apw-calcHubV2 .apw-inputV2 {
      width: 100%;
      padding: 12px 12px;
      border-radius: 12px;
      border: 1px solid rgba(11, 44, 61, .18);
      background: #fcfaf5;
      color: #0b2c3d;
      font-size: 15px;
      outline: none;
      transition: box-shadow .18s ease, border-color .18s ease;
    }

    #apw-calcHubV2 .apw-inputV2:focus {
      border-color: rgba(179, 147, 89, .75);
      box-shadow: 0 0 0 3px rgba(179, 147, 89, .25);
    }

    #apw-calcHubV2 .apw-helpV2 {
      display: block;
      margin-top: 6px;
      color: rgba(11, 44, 61, .68);
      font-size: 12.5px;
      line-height: 1.4;
    }

    #apw-calcHubV2 .apw-alertV2 {
      min-height: 18px;
      font-size: 12.5px;
      color: #b39359;
      font-weight: 900;
      margin: 8px 0 2px;
    }

    #apw-calcHubV2 .apw-actionsV2 {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 14px;
    }

    #apw-calcHubV2 .apw-btnV2 {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 11px 14px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 900;
      font-size: 13.5px;
      cursor: pointer;
      border: 1px solid transparent;
      transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
      user-select: none;
      white-space: nowrap;
    }

    #apw-calcHubV2 .apw-btnV2:active {
      transform: translateY(1px);
    }

    #apw-calcHubV2 .apw-btnV2--primary {
      background: #0b2c3d;
      color: #fcfaf5;
      border-color: #0b2c3d;
      box-shadow: 0 14px 30px rgba(11, 44, 61, .18);
    }

    #apw-calcHubV2 .apw-btnV2--primary:hover {
      box-shadow: 0 18px 40px rgba(11, 44, 61, .22);
      transform: translateY(-1px);
    }

    #apw-calcHubV2 .apw-btnV2--ghost {
      background: rgba(179, 147, 89, .12);
      border-color: rgba(179, 147, 89, .40);
      color: #0b2c3d;
    }

    #apw-calcHubV2 .apw-btnV2--ghost:hover {
      background: rgba(179, 147, 89, .18);
      border-color: rgba(179, 147, 89, .55);
      transform: translateY(-1px);
    }

    /* Results */
    #apw-calcHubV2 .apw-calcResultsV2 {
      background: #0b2c3d;
      color: #fcfaf5;
      padding: 20px 18px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      gap: 12px;
    }

    #apw-calcHubV2 .apw-resultHeroV2 {
      padding: 16px;
      border-radius: 16px;
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .10);
    }

    #apw-calcHubV2 .apw-resultHeroV2__label {
      display: block;
      font-size: 12.5px;
      opacity: .88;
      margin-bottom: 6px;
    }

    #apw-calcHubV2 .apw-resultHeroV2__value {
      display: block;
      font-size: 26px;
      line-height: 1.1;
      color: white;
      font-weight: 900;
      letter-spacing: .2px;
    }

    #apw-calcHubV2 .apw-resultHeroV2__note {
      display: block;
      margin-top: 8px;
      font-size: 12px;
      opacity: .75;
    }

    #apw-calcHubV2 .apw-resultGridV2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    #apw-calcHubV2 .apw-resultItemV2 {
      padding: 12px;
      border-radius: 14px;
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .10);
    }

    #apw-calcHubV2 .apw-resultItemV2 span {
      display: block;
      font-size: 12.5px;
      opacity: .86;
      margin-bottom: 6px;
    }

    #apw-calcHubV2 .apw-resultItemV2 strong {
      font-size: 16px;
      color: #fcfaf5;
      font-weight: 900;
    }

    /* Graph */
    #apw-calcHubV2 .apw-graphV2 {
      padding: 14px;
      border-radius: 16px;
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .10);
    }

    #apw-calcHubV2 .apw-graphV2__head {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 10px;
    }

    #apw-calcHubV2 .apw-graphV2__title {
      font-size: 13px;
      font-weight: 900;
      letter-spacing: .2px;
      color: #fcfaf5;
    }

    #apw-calcHubV2 .apw-graphV2__meta {
      font-size: 11.5px;
      opacity: .78;
    }

    #apw-calcHubV2 .apw-barsV2 {
      display: grid;
      gap: 12px;
    }

    #apw-calcHubV2 .apw-barV2__top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 6px;
    }

    #apw-calcHubV2 .apw-barV2__label {
      font-size: 12px;
      opacity: .85;
      font-weight: 800;
    }

    #apw-calcHubV2 .apw-barV2__val {
      font-size: 12px;
      font-weight: 900;
      color: #fcfaf5;
    }

    #apw-calcHubV2 .apw-barV2__track {
      height: 10px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .12);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, .08);
    }

    #apw-calcHubV2 .apw-barV2__fill {
      height: 100%;
      width: 0%;
      border-radius: 999px;
      background: linear-gradient(90deg, rgba(179, 147, 89, .95), rgba(179, 147, 89, .55));
      transition: width .22s ease;
    }

    #apw-calcHubV2 .apw-disclaimerV2 {
      margin-top: 0;
      font-size: 12px;
      opacity: .75;
      line-height: 1.5;
    }

    /* Sidebar */
    #apw-calcHubV2 .apw-sideCardV2 {
      background: #ffffff;
      border-radius: 10px;
      border: 1px solid rgba(11, 44, 61, .12);
      box-shadow: 0 14px 34px rgba(11, 44, 61, .06);
      overflow: hidden;
      position: sticky;
      top: 18px;
    }

    #apw-calcHubV2 .apw-sideCardV2__top {
      padding: 18px 16px;
      border-bottom: 1px solid rgba(11, 44, 61, .10);
      background: radial-gradient(circle at 10% 10%, rgba(179, 147, 89, .12), transparent 55%);
    }

    #apw-calcHubV2 .apw-sideCardV2__title {
      margin: 0 0 6px;
      color: #0b2c3d;
      font-size: 25px;
      font-weight: 900;
      font-family: 'forum';
    }

    #apw-calcHubV2 .apw-sideCardV2__hint {
      margin: 0;
      color: rgba(11, 44, 61, .72);
      font-size: 13px;
      line-height: 1.5;
    }

    #apw-calcHubV2 .apw-sideNavV2 {
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    #apw-calcHubV2 .apw-sideNavV2__item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 12px;
      border-radius: 14px;
      background: #fcfaf5;
      border: 1px solid rgba(11, 44, 61, .14);
      text-decoration: none;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    #apw-calcHubV2 .apw-sideNavV2__item:hover {
      transform: translateY(-1px);
      border-color: rgba(179, 147, 89, .55);
      box-shadow: 0 12px 26px rgba(11, 44, 61, .08);
    }

    #apw-calcHubV2 .apw-sideNavV2__item.is-active {
      background: rgba(179, 147, 89, .12);
      border-color: rgba(179, 147, 89, .40);
    }

    #apw-calcHubV2 .apw-sideNavV2__text {
      font-weight: 900;
      color: #0b2c3d;
      font-size: 14px;
      letter-spacing: .2px;
    }

    /* Responsive */
    @media (max-width: 980px) {
      #apw-calcHubV2 .apw-calcHubV2__layout {
        grid-template-columns: 1fr;
      }

      #apw-calcHubV2 .apw-sideCardV2 {
        position: static;
      }
    }

    @media (max-width: 900px) {
      #apw-calcHubV2 .apw-calcCardV2__grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 640px) {
      #apw-calcHubV2 {
        padding: 46px 14px;
      }

      #apw-calcHubV2 .apw-resultGridV2 {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <!-- =======================
       SCRIPT (CENT -> SQ FT)
  ======================== -->
  <script>
    (function () {
      const root = document.getElementById("apw-calcHubV2");
      if (!root) return;

      // constants
      const SQFT_PER_CENT = 435.6;         // 1 cent = 1/100 acre, 1 acre = 43560 sq ft
      const SQM_PER_SQFT = 0.09290304;
      const ACRE_PER_CENT = 0.01;

      // helpers
      const fmt = (n, d = 2) => (isFinite(n) ? Number(n).toFixed(d) : "0");
      const fmtInt = (n) => (isFinite(n) ? Math.round(n).toLocaleString("en-IN") : "0");

      const ui = {
        cents: root.querySelector("#apwV2-cent-value"),
        alert: root.querySelector("#apwV2-centAlert"),
        reset: root.querySelector("#apwV2-centReset"),
        out: {
          sqftHero: root.querySelector("#apwV2-cent-sqftHero"),
          centOut: root.querySelector("#apwV2-cent-centOut"),
          sqftOut: root.querySelector("#apwV2-cent-sqftOut"),
          sqmOut: root.querySelector("#apwV2-cent-sqmOut"),
          acreOut: root.querySelector("#apwV2-cent-acreOut"),

          graphMeta: root.querySelector("#apwV2-cent-graphMeta"),
          barCent: root.querySelector("#apwV2-cent-barCent"),
          barSqft: root.querySelector("#apwV2-cent-barSqft"),
          barCentVal: root.querySelector("#apwV2-cent-barCentVal"),
          barSqftVal: root.querySelector("#apwV2-cent-barSqftVal"),
        }
      };

      function validate() {
        const cents = Number(ui.cents.value);
        if (!isFinite(cents) || cents < 0) return "Enter valid cents (0 or more).";
        return "";
      }

      function calculate() {
        const msg = validate();
        ui.alert.textContent = msg;

        const cents = Number(ui.cents.value || 0);

        if (msg) {
          ui.out.sqftHero.textContent = "0 sq ft";
          ui.out.centOut.textContent = "0";
          ui.out.sqftOut.textContent = "0";
          ui.out.sqmOut.textContent = "0";
          ui.out.acreOut.textContent = "0";
          ui.out.graphMeta.textContent = "Cent vs Sq Ft";

          ui.out.barCent.style.width = "0%";
          ui.out.barSqft.style.width = "0%";
          ui.out.barCentVal.textContent = "0";
          ui.out.barSqftVal.textContent = "0";
          return;
        }

        const sqft = cents * SQFT_PER_CENT;
        const sqm = sqft * SQM_PER_SQFT;
        const acre = cents * ACRE_PER_CENT;

        ui.out.sqftHero.textContent = `${fmtInt(sqft)} sq ft`;
        ui.out.centOut.textContent = `${fmt(cents, 2)} cent`;
        ui.out.sqftOut.textContent = fmtInt(sqft);
        ui.out.sqmOut.textContent = `${fmt(sqm, 2)} m²`;
        ui.out.acreOut.textContent = `${fmt(acre, 4)} acre`;

        // Graph normalization (visual only)
        const maxVal = Math.max(cents, sqft, 1e-6);
        const centPct = Math.min(100, (cents / maxVal) * 100);
        const sqftPct = Math.min(100, (sqft / maxVal) * 100);

        ui.out.barCent.style.width = centPct.toFixed(1) + "%";
        ui.out.barSqft.style.width = sqftPct.toFixed(1) + "%";
        ui.out.barCentVal.textContent = fmt(cents, 2);
        ui.out.barSqftVal.textContent = fmtInt(sqft);

        ui.out.graphMeta.textContent = `Cents (${fmt(cents, 2)}) vs Sq Ft (${fmtInt(sqft)})`;
      }

      ui.cents.addEventListener("input", calculate);

      ui.reset.addEventListener("click", () => {
        ui.cents.value = 10;
        calculate();
      });

      calculate();
    })();
  </script>
</section>

@endsection
