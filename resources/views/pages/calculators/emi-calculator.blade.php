@extends('layouts.app')
@section('title', 'EMI Calculator - ZendoIndia')
@section('content')
  <!--- banner section -->
    
    <style>
    /* ABOUT BANNER SECTION */
.about-banner-section {
    position: relative;
    background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg'); /* Change image */
    background-size: cover;
    background-position: center;
    padding: 160px 0 80px; /* Top padding for overlap header */
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
        font-size: 30px!important;
    }
    .about-breadcrumb {
        font-size: 14px;
    }
    .about-banner-section {
        padding: 130px 0 60px;
    }
}

</style>
<section class="about-banner-section">
    <div class="about-banner-overlay"></div>

    <div class="about-banner-container">
        <div class="about-banner-left">
            <h1 class="about-banner-heading">EMI Calculator</h1>

            <div class="about-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <p>EMI Calculator</p>
            </div>
        </div>
    </div>
</section>


<!--- calculator structue -->

<section id="apw-calcHubV2" class="apw-calcHubV2">
  <div class="apw-calcHubV2__wrap">

    <div class="apw-calcHubV2__layout">

      <!-- LEFT (70%) : Heading + Calculator -->
      <div class="apw-calcHubV2__main">

        <!-- Heading (shifted left) -->
        <div class="apw-calcHubV2__head">
          <h2 class="apw-calcHubV2__title">Calculate your monthly Home loan EMI

</h2>
        </div>

        <!-- SINGLE CALCULATOR (Tabs removed) -->
        <div class="apw-calcCardV2">
          <div class="apw-calcCardV2__top">
            <h3 class="apw-calcCardV2__title">Home Loan EMI Calculator</h3>
           
          </div>

          <div class="apw-calcCardV2__grid">
            <!-- Left: Inputs -->
            <form class="apw-calcFormV2" id="apwV2-loanForm" novalidate>

              <div class="apw-fieldV2">
                <label class="apw-labelV2" for="apwV2-loan-price">
                  Property Price (₹) <span class="apw-reqV2">*</span>
                </label>
                <div class="apw-inputRowV2">
                  <input class="apw-inputV2" type="number" id="apwV2-loan-price" min="100000" step="10000"
                    value="5000000" required />
                </div>
                
                <!-- NEW: words -->
                <small class="apw-wordsV2" id="apwV2-loan-priceWords"></small>
              </div>

              <div class="apw-fieldV2">
                <label class="apw-labelV2" for="apwV2-loan-down">
                  Down Payment (%) <span class="apw-reqV2">*</span>
                </label>
                <div class="apw-dualV2">
                  <input class="apw-rangeV2" type="range" id="apwV2-loan-downRange" min="0" max="90" step="1"
                    value="20" aria-label="Down payment slider" />
                  <input class="apw-inputV2 apw-inputV2--sm" type="number" id="apwV2-loan-down" min="0" max="90"
                    step="1" value="20" required />
                </div>
                <small class="apw-helpV2">Higher down payment usually reduces EMI.</small>
                <!-- NEW: words -->
                <small class="apw-wordsV2" id="apwV2-loan-downWords"></small>
              </div>

              <div class="apw-fieldV2">
                <label class="apw-labelV2" for="apwV2-loan-rate">
                  Interest Rate (% p.a.) <span class="apw-reqV2">*</span>
                </label>
                <div class="apw-dualV2">
                  <input class="apw-rangeV2" type="range" id="apwV2-loan-rateRange" min="5" max="20" step="0.1"
                    value="8.5" aria-label="Interest rate slider" />
                  <input class="apw-inputV2 apw-inputV2--sm" type="number" id="apwV2-loan-rate" min="0.1" max="30"
                    step="0.1" value="8.5" required />
                </div>
                <small class="apw-helpV2">Bank rate may vary by profile.</small>
                <!-- NEW: words -->
                <small class="apw-wordsV2" id="apwV2-loan-rateWords"></small>
              </div>

              <div class="apw-fieldV2">
                <label class="apw-labelV2" for="apwV2-loan-years">
                  Loan Tenure (Years) <span class="apw-reqV2">*</span>
                </label>
                <div class="apw-dualV2">
                  <input class="apw-rangeV2" type="range" id="apwV2-loan-yearsRange" min="1" max="35" step="1"
                    value="20" aria-label="Tenure slider" />
                  <input class="apw-inputV2 apw-inputV2--sm" type="number" id="apwV2-loan-years" min="1" max="35"
                    step="1" value="20" required />
                </div>
                <small class="apw-helpV2">Longer tenure reduces EMI but increases total interest.</small>
                <!-- NEW: words -->
                <small class="apw-wordsV2" id="apwV2-loan-yearsWords"></small>
              </div>

              <div class="apw-alertV2" id="apwV2-loanAlert" aria-live="polite"></div>

              <div class="apw-actionsV2">
                <button type="button" class="apw-btnV2 apw-btnV2--ghost" id="apwV2-loanReset">Reset</button>
                <a href="#apw-propertyCta" class="apw-btnV2 apw-btnV2--primary">See Properties in This Budget</a>
              </div>
            </form>

            <!-- Right: Results -->
            <aside class="apw-calcResultsV2" aria-label="Home loan results">
              <div class="apw-resultHeroV2">
                <span class="apw-resultHeroV2__label">Estimated Monthly EMI</span>
                <strong class="apw-resultHeroV2__value" id="apwV2-loan-emi">₹0</strong>
                <!-- NEW: words -->
                <small class="apw-wordsV2 apw-wordsV2--onDark" id="apwV2-loan-emiWords"></small>
                <span class="apw-resultHeroV2__note">Indicative estimate</span>
              </div>

              <div class="apw-resultGridV2">
                <div class="apw-resultItemV2">
                  <span>Loan Amount</span>
                  <strong id="apwV2-loan-amount">₹0</strong>
                  <!-- NEW: words -->
                  <small class="apw-wordsV2 apw-wordsV2--onDark" id="apwV2-loan-amountWords"></small>
                </div>
                <div class="apw-resultItemV2">
                  <span>Down Payment</span>
                  <strong id="apwV2-loan-downAmt">₹0</strong>
                  <!-- NEW: words -->
                  <small class="apw-wordsV2 apw-wordsV2--onDark" id="apwV2-loan-downAmtWords"></small>
                </div>
                <div class="apw-resultItemV2">
                  <span>Total Interest</span>
                  <strong id="apwV2-loan-interest">₹0</strong>
                  <!-- NEW: words -->
                  <small class="apw-wordsV2 apw-wordsV2--onDark" id="apwV2-loan-interestWords"></small>
                </div>
                <div class="apw-resultItemV2">
                  <span>Total Payable</span>
                  <strong id="apwV2-loan-total">₹0</strong>
                  <!-- NEW: words -->
                  <small class="apw-wordsV2 apw-wordsV2--onDark" id="apwV2-loan-totalWords"></small>
                </div>
              </div>

              <p class="apw-disclaimerV2">
                * EMI is calculated using standard formula; final offer depends on bank fees and your credit profile.
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
            <a class="apw-sideNavV2__item is-active" href="{{ route('calculators.emi-calculator') }}" aria-current="page">
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
    #apw-calcHubV2 {
      background: #fcfaf5;
      padding: 60px 16px;
      overflow: hidden;
    }

    #apw-calcHubV2 .apw-calcHubV2__wrap {
      max-width: 1200px;
      margin: 0 auto;
    }

    #apw-calcHubV2 .apw-calcHubV2__layout {
      display: grid;
      grid-template-columns: minmax(0, 7fr) minmax(0, 3fr);
      gap: 20px;
      align-items: start;
    }

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

    #apw-calcHubV2 .apw-calcCardV2 {
      background: #ffffff;
      border-radius: 20px;
      border: 1px solid rgba(11, 44, 61, .12);
      box-shadow: 0 14px 34px rgba(11, 44, 61, .08);
      overflow: hidden;
    }

    #apw-calcHubV2 .apw-calcCardV2__top {
      padding: 15px 18px;
      border-bottom: 1px solid rgba(11, 44, 61, .10);
      background: radial-gradient(circle at 10% 10%, rgba(179, 147, 89, .14), transparent 52%);
    }

    #apw-calcHubV2 .apw-calcCardV2__title {
      margin: 0px;
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

    #apw-calcHubV2 .apw-inputV2--sm {
      max-width: 150px;
    }

    #apw-calcHubV2 .apw-helpV2 {
      display: block;
      margin-top: 6px;
      color: rgba(11, 44, 61, .68);
      font-size: 12.5px;
      line-height: 1.4;
    }

    /* NEW: number-to-words line */
    #apw-calcHubV2 .apw-wordsV2 {
      display: block;
      margin-top: 6px;
      font-size: 12.5px;
      line-height: 1.35;
      color: rgba(11, 44, 61, .78);
      font-weight: 800;
      letter-spacing: .1px;
    }

    #apw-calcHubV2 .apw-wordsV2--onDark {
      color: rgba(252, 250, 245, .88);
      font-weight: 800;
    }

    #apw-calcHubV2 .apw-dualV2 {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    #apw-calcHubV2 .apw-rangeV2 {
      flex: 1;
      accent-color: #b39359;
      width: 100%;
      cursor: pointer;
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

    #apw-calcHubV2 .apw-calcResultsV2 {
      background: #0b2c3d;
      color: #fcfaf5;
      padding: 20px 18px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }

    #apw-calcHubV2 .apw-resultHeroV2 {
      padding: 16px;
      border-radius: 16px;
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .10);
      margin-bottom: 14px;
    }

    #apw-calcHubV2 .apw-resultHeroV2__label {
      display: block;
      font-size: 12.5px;
      opacity: .88;
      margin-bottom: 6px;
    }

    #apw-calcHubV2 .apw-resultHeroV2__value {
      display: block;
      font-size: 28px;
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

    #apw-calcHubV2 .apw-disclaimerV2 {
      margin-top: 14px;
      font-size: 12px;
      opacity: .75;
      line-height: 1.5;
    }

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

      #apw-calcHubV2 .apw-inputV2--sm {
        max-width: 130px;
      }
    }

    @media (max-width: 640px) {
      #apw-calcHubV2 {
        padding: 15px 14px;
      }
      #apw-calcHubV2 .apw-calcHubV2__title {
      color: #0b2c3d;
      font-size: 18px !important;
      line-height: 1.2;
      margin: 0 0 8px;
      letter-spacing: .2px;
      font-weight: 900;
    }

      #apw-calcHubV2 .apw-dualV2 {
        flex-direction: column;
        align-items: stretch;
      }

      #apw-calcHubV2 .apw-inputV2--sm {
        max-width: 100%;
      }

      #apw-calcHubV2 .apw-resultGridV2 {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>

  <!-- =======================
       SCRIPT (ONLY HOME LOAN)
  ======================== -->
  <script>
    (function () {
      const root = document.getElementById("apw-calcHubV2");
      if (!root) return;

      // Format INR numeric
      const inr = (n) => "₹" + (isFinite(n) ? Math.round(n).toLocaleString("en-IN") : "0");

      /* ============================
         NUMBER -> WORDS (Indian)
      ============================ */
      const ones = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten",
        "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"];
      const tens = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];

      function twoDigitsToWords(n) {
        n = n | 0;
        if (n < 20) return ones[n];
        const t = (n / 10) | 0;
        const o = n % 10;
        return (tens[t] + (o ? " " + ones[o] : "")).trim();
      }

      function threeDigitsToWords(n) {
        n = n | 0;
        const h = (n / 100) | 0;
        const r = n % 100;
        let out = "";
        if (h) out += ones[h] + " hundred";
        if (r) out += (out ? " " : "") + twoDigitsToWords(r);
        return out.trim();
      }

      // Indian grouping: crore, lakh, thousand
      function numberToIndianWords(num) {
        if (!isFinite(num)) return "zero";
        num = Math.round(Math.abs(num));

        if (num === 0) return "zero";

        const crore = (num / 10000000) | 0;
        num = num % 10000000;

        const lakh = (num / 100000) | 0;
        num = num % 100000;

        const thousand = (num / 1000) | 0;
        num = num % 1000;

        const hundredPart = num; // 0..999

        let parts = [];
        if (crore) parts.push(threeDigitsToWords(crore) + " crore");
        if (lakh) parts.push(threeDigitsToWords(lakh) + " lakh");
        if (thousand) parts.push(threeDigitsToWords(thousand) + " thousand");
        if (hundredPart) parts.push(threeDigitsToWords(hundredPart));

        return parts.join(" ").replace(/\s+/g, " ").trim();
      }

      function numberToWordsSimple(val) {
        if (!isFinite(val)) return "zero";

        // handle decimals: 8.5 => "eight point five"
        const str = String(val);
        if (str.includes(".")) {
          const [a, b] = str.split(".");
          const intPart = numberToIndianWords(parseInt(a || "0", 10));
          const decDigits = (b || "").split("").map(d => ones[parseInt(d, 10)] || "zero").join(" ");
          return (intPart + " point " + decDigits).replace(/\s+/g, " ").trim();
        }
        return numberToIndianWords(parseInt(str || "0", 10));
      }

      function rupeesToWords(n) {
        if (!isFinite(n)) return "zero rupees";
        const rupees = Math.round(Math.abs(n));
        const w = numberToIndianWords(rupees);
        return (w === "zero" ? "zero rupees" : w + " rupees");
      }

      // Elements
      const loan = {
        price: root.querySelector("#apwV2-loan-price"),
        down: root.querySelector("#apwV2-loan-down"),
        downRange: root.querySelector("#apwV2-loan-downRange"),
        rate: root.querySelector("#apwV2-loan-rate"),
        rateRange: root.querySelector("#apwV2-loan-rateRange"),
        years: root.querySelector("#apwV2-loan-years"),
        yearsRange: root.querySelector("#apwV2-loan-yearsRange"),
        alert: root.querySelector("#apwV2-loanAlert"),
        reset: root.querySelector("#apwV2-loanReset"),

        words: {
          price: root.querySelector("#apwV2-loan-priceWords"),
          down: root.querySelector("#apwV2-loan-downWords"),
          rate: root.querySelector("#apwV2-loan-rateWords"),
          years: root.querySelector("#apwV2-loan-yearsWords"),
        },

        out: {
          emi: root.querySelector("#apwV2-loan-emi"),
          amount: root.querySelector("#apwV2-loan-amount"),
          downAmt: root.querySelector("#apwV2-loan-downAmt"),
          interest: root.querySelector("#apwV2-loan-interest"),
          total: root.querySelector("#apwV2-loan-total"),
        },

        outWords: {
          emi: root.querySelector("#apwV2-loan-emiWords"),
          amount: root.querySelector("#apwV2-loan-amountWords"),
          downAmt: root.querySelector("#apwV2-loan-downAmtWords"),
          interest: root.querySelector("#apwV2-loan-interestWords"),
          total: root.querySelector("#apwV2-loan-totalWords"),
        }
      };

      function loanValidate() {
        const price = +loan.price.value;
        const down = +loan.down.value;
        const rate = +loan.rate.value;
        const years = +loan.years.value;

        if (!price || price < 100000) return "Enter valid property price (min ₹1,00,000).";
        if (down < 0 || down > 90) return "Down payment should be between 0% and 90%.";
        if (!rate || rate <= 0 || rate > 30) return "Enter valid interest rate (0.1% to 30%).";
        if (!years || years < 1 || years > 35) return "Tenure should be between 1 and 35 years.";
        return "";
      }

      function updateInputWords() {
        const price = +loan.price.value;
        const down = +loan.down.value;
        const rate = loan.rate.value;  // keep decimal string
        const years = +loan.years.value;

        loan.words.price.textContent = price ? (rupeesToWords(price)) : "";
        loan.words.down.textContent = isFinite(down) ? (numberToWordsSimple(down) + " percent") : "";
        loan.words.rate.textContent = rate ? (numberToWordsSimple(rate) + " percent") : "";
        loan.words.years.textContent = years ? (numberToWordsSimple(years) + " years") : "";
      }

      function setZeroResults() {
        loan.out.emi.textContent = "₹0";
        loan.out.amount.textContent = "₹0";
        loan.out.downAmt.textContent = "₹0";
        loan.out.interest.textContent = "₹0";
        loan.out.total.textContent = "₹0";

        loan.outWords.emi.textContent = "zero rupees";
        loan.outWords.amount.textContent = "zero rupees";
        loan.outWords.downAmt.textContent = "zero rupees";
        loan.outWords.interest.textContent = "zero rupees";
        loan.outWords.total.textContent = "zero rupees";
      }

      function loanCalculate() {
        updateInputWords();

        const msg = loanValidate();
        loan.alert.textContent = msg;

        const price = +loan.price.value;
        const downPct = +loan.down.value;
        const annualRate = +loan.rate.value;
        const years = +loan.years.value;

        if (msg) {
          setZeroResults();
          return;
        }

        const downAmt = price * downPct / 100;
        const loanAmt = price - downAmt;

        const r = annualRate / 12 / 100;
        const n = years * 12;

        const emi = (loanAmt * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        const totalPay = emi * n;
        const totalInterest = totalPay - loanAmt;

        loan.out.emi.textContent = inr(emi);
        loan.out.amount.textContent = inr(loanAmt);
        loan.out.downAmt.textContent = inr(downAmt);
        loan.out.interest.textContent = inr(totalInterest);
        loan.out.total.textContent = inr(totalPay);

        // NEW: words for results (rounded rupees)
        loan.outWords.emi.textContent = rupeesToWords(emi);
        loan.outWords.amount.textContent = rupeesToWords(loanAmt);
        loan.outWords.downAmt.textContent = rupeesToWords(downAmt);
        loan.outWords.interest.textContent = rupeesToWords(totalInterest);
        loan.outWords.total.textContent = rupeesToWords(totalPay);
      }

      function bindDual(rangeEl, inputEl, cb) {
        rangeEl.addEventListener("input", () => {
          inputEl.value = rangeEl.value;
          cb();
        });
        inputEl.addEventListener("input", () => {
          rangeEl.value = inputEl.value || rangeEl.min;
          cb();
        });
      }

      bindDual(loan.downRange, loan.down, loanCalculate);
      bindDual(loan.rateRange, loan.rate, loanCalculate);
      bindDual(loan.yearsRange, loan.years, loanCalculate);
      loan.price.addEventListener("input", loanCalculate);

      loan.reset.addEventListener("click", () => {
        loan.price.value = 5000000;
        loan.down.value = 20; loan.downRange.value = 20;
        loan.rate.value = 8.5; loan.rateRange.value = 8.5;
        loan.years.value = 20; loan.yearsRange.value = 20;
        loanCalculate();
      });

      loanCalculate();
    })();
  </script>
</section>
@endsection
