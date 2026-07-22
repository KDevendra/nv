@extends('layouts.app')
@section('title', 'Acre to Square Meter - ZendoIndia')
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
        <h1 class="about-banner-heading">Acre to Square Meter</h1>
        <div class="about-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>

            <p>Acre to Square Meter</p>
        </div>
    </div>
</section>

<section id="apw-calcHubV2" class="apw-calcHubV2">
  <div class="apw-calcHubV2__wrap">
    <header class="apw-calcHubV2__head">
      <h2 class="apw-calcHubV2__title">Acre to Square Meter Converter</h2>
      <p class="apw-calcHubV2__sub">
        Enter value, choose units, and convert instantly.
      </p>
    </header>

    <div class="apw-calcHubV2__layout">

      <!-- LEFT : Heading + Calculator -->
      <div class="apw-calcHubV2__main">

        <div class="apw-calcCardV2">
          <div class="apw-calcCardV2__grid">

            <form class="apw-calcFormV2" id="apwV2-unitForm" novalidate>

              <!-- FROM ROW -->
              <div class="apw-fieldV2">
                <div class="apw-rowV2">
                  <div class="apw-floatV2">
                    <input
                      class="apw-inputV2 apw-inputV2--num"
                      type="number"
                      id="apwV2-fromValue"
                      min="0"
                      step="0.0001"
                      value="1"
                      required
                      placeholder="Add your value here*"
                    />
                    <span class="apw-floatLabelV2">Value</span>
                  </div>

                  <div class="apw-selectWrapV2">
                    <select class="apw-inputV2 apw-inputV2--unit" id="apwV2-fromUnit" required>
                      <option value="acre" selected>Acre (ac)</option>
                      <option value="bigha">Bigha</option>
                      <option value="cent">Cent</option>
                      <option value="hectare">Hectare (ha)</option>
                      <option value="sqft">Square Feet (sq ft)</option>
                      <option value="sqm">Square Meter (sq m)</option>
                    </select>
                    <!-- BADGE ADDED (unit) -->
                    <span class="apw-floatLabelV2 apw-floatLabelV2--unit">From</span>
                  </div>
                </div>
              </div>

              <!-- SWAP -->
              <div class="apw-swapMidV2" aria-label="Swap units">
                <button class="apw-swapBtnV2" type="button" id="apwV2-swap" title="Swap From/To">
                  <span class="apw-swapIconV2" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M7 14V6m0 0-3 3M7 6l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 10v8m0 0 3-3m-3 3-3-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </span>
                </button>
              </div>

              <!-- TO ROW -->
              <div class="apw-fieldV2">
                <div class="apw-rowV2">
                  <div class="apw-floatV2">
                    <input
                      class="apw-inputV2 apw-inputV2--num"
                      type="text"
                      id="apwV2-toValue"
                      value="0"
                      readonly
                      placeholder=" "
                    />
                    <span class="apw-floatLabelV2">Value</span>
                  </div>

                  <div class="apw-selectWrapV2">
                    <select class="apw-inputV2 apw-inputV2--unit" id="apwV2-toUnit" required>
                      <option value="sqm" selected>Square Meter (sq m)</option>
                      <option value="acre">Acre (ac)</option>
                      <option value="bigha">Bigha</option>
                      <option value="cent">Cent</option>
                      <option value="hectare">Hectare (ha)</option>
                      <option value="sqft">Square Feet (sq ft)</option>
                    </select>
                    <!-- BADGE ADDED (unit) -->
                    <span class="apw-floatLabelV2 apw-floatLabelV2--unit">To</span>
                  </div>
                </div>

                <small class="apw-helpV2" id="apwV2-resultHint">Converted value will appear above</small>
              </div>

              <div class="apw-alertV2" id="apwV2-alert" aria-live="polite"></div>

              <div class="apw-actionsV2">
                <button type="button" class="apw-btnV2 apw-btnV2--primary" id="apwV2-convert">Convert</button>
                <button type="button" class="apw-btnV2 apw-btnV2--ghost" id="apwV2-reset">Reset</button>
              </div>

              <p class="apw-disclaimerV2 apw-disclaimerV2--light">
                *This tool is for informational purposes only.
              </p>
            </form>

          </div>
        </div>
      </div>

      <!-- RIGHT (Sidebar) -->
      <aside class="apw-calcHubV2__side" aria-label="Other calculators">
        <div class="apw-sideCardV2">
          <div class="apw-sideCardV2__top">
            <h3 class="apw-sideCardV2__title">Top Real Estate Calculators</h3>
            <p class="apw-sideCardV2__hint">Pick any calculator to explore</p>
          </div>

          <nav class="apw-sideNavV2">
            <a class="apw-sideNavV2__item is-active" href="{{ route('calculators.acre-to-squaremeter') }}" aria-current="page">
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
    (function () {
      const root = document.getElementById("apw-calcHubV2");
      if (!root) return;

      const fromValue = root.querySelector("#apwV2-fromValue");
      const fromUnit  = root.querySelector("#apwV2-fromUnit");
      const toValue   = root.querySelector("#apwV2-toValue");
      const toUnit    = root.querySelector("#apwV2-toUnit");
      const alertBox  = root.querySelector("#apwV2-alert");
      const btnConvert= root.querySelector("#apwV2-convert");
      const btnReset  = root.querySelector("#apwV2-reset");
      const btnSwap   = root.querySelector("#apwV2-swap");

      /* Base unit = Acre */
      const factorToAcre = {
        acre: 1,
        cent: 0.01,
        bigha: 0.25,               // approx
        hectare: 2.4710538147,
        sqft: 1 / 43560,
        sqm: 1 / 4046.8564224,
      };

      function format(n) {
        if (!isFinite(n)) return "0";
        return Number(n).toLocaleString("en-IN", { maximumFractionDigits: 6 });
      }

      function convert() {
        const val = Number(fromValue.value);
        if (!isFinite(val) || val < 0) {
          alertBox.textContent = "Please enter a valid value.";
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
        toUnit.value = "sqm";
        convert();
      });

      /* Initial Load → Acre to Square Meter */
      fromValue.value = 1;
      fromUnit.value = "acre";
      toUnit.value = "sqm";
      convert();
    })();
  </script>
</section>


@endsection
