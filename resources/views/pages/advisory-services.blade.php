@extends('layouts.app')

@section('title', 'ZENDO Advisory Services - Warehouse Decisions Made Smarter | ZendoIndia')

@section('content')

  <style>
    /* ==============================================================
     ZENDO ADVISORY SERVICES — LANDING PAGE
     Theme sampled from zendoindia.com:
     Gold #B39359 · Navy #0B2C3D · Cream #FBF8F2 · Light #F8F9FC
     Font: Jost
     ============================================================== */
    .zadv {
      --gold: #B39359;
      --gold-dark: #9A7A3E;
      --navy: #0B2C3D;
      --navy-2: #10394E;
      --cream: #FBF8F2;
      --light: #F8F9FC;
      --text: #5C6670;
      --white: #FFFFFF;
      --radius: 16px;
      --shadow: 0 14px 40px rgba(11, 44, 61, .10);
    }

    /* Scoped to this page only — safe inside site layout */
    .zadv,
    .zadv * {
      box-sizing: border-box
    }

    .zadv {
      font-family: inherit;
      /* uses site's font from layouts.app */
      color: var(--text);
      background: var(--white);
      line-height: 1.6;
      margin: 0;
    }

    .zadv h1,
    .zadv h2,
    .zadv h3,
    .zadv p,
    .zadv ul {
      margin: 0;
      padding: 0
    }

    .zadv-container {
      max-width: 1180px;
      margin: 0 auto;
      padding: 0 20px
    }

    h1,
    h2,
    h3 {
      color: var(--navy);
      font-weight: 700;
      line-height: 1.2
    }

    .zadv-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-family: inherit;
      font-size: 16px;
      font-weight: 600;
      padding: 14px 32px;
      border-radius: 50px;
      text-decoration: none;
      cursor: pointer;
      border: 2px solid transparent;
      transition: background .25s ease, color .25s ease, border-color .25s ease, transform .25s ease;
    }

    .zadv-btn-gold {
      background: var(--gold);
      color: var(--white)
    }

    .zadv-btn-gold:hover {
      background: var(--gold-dark);
      transform: translateY(-2px)
    }

    .zadv-btn-outline {
      border-color: rgba(255, 255, 255, .55);
      color: var(--white)
    }

    .zadv-btn-outline:hover {
      border-color: var(--gold);
      color: var(--gold)
    }

    .zadv-btn-whatsapp {
      background: #25D366;
      color: var(--white)
    }

    .zadv-btn-whatsapp:hover {
      background: #1EBE5A;
      transform: translateY(-2px)
    }

    .zadv-btn:focus-visible {
      outline: 3px solid var(--gold);
      outline-offset: 3px
    }

    .zadv-btn svg {
      transition: transform .25s ease
    }

    .zadv-btn:hover svg {
      transform: translateX(3px)
    }

    .zadv-eyebrow {
      display: inline-block;
      color: var(--gold);
      font-size: 14px;
      font-weight: 600;
      letter-spacing: .22em;
      text-transform: uppercase;
      margin-bottom: 14px;
    }

    /* ---------------- HERO ---------------- */
    .zadv-hero {
      background:
        radial-gradient(ellipse at 80% 20%, rgba(179, 147, 89, .14), transparent 55%),
        linear-gradient(160deg, var(--navy) 0%, var(--navy-2) 100%);
      color: var(--white);
      padding: 110px 0 120px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .zadv-hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background-image: linear-gradient(rgba(179, 147, 89, .05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(179, 147, 89, .05) 1px, transparent 1px);
      background-size: 64px 64px;
      pointer-events: none;
    }

    .zadv-hero .zadv-container {
      position: relative;
      z-index: 1
    }

    .zadv-hero .zadv-eyebrow {
      margin-bottom: 20px
    }

    .zadv-hero h1 {
      color: var(--white);
      font-size: clamp(34px, 5vw, 58px);
      max-width: 16ch;
      margin: 0 auto;
    }

    .zadv-hero h1 span {
      color: var(--gold)
    }

    .zadv-hero p {
      max-width: 640px;
      margin: 22px auto 0;
      font-size: 18px;
      color: #CBD6DD;
    }

    .zadv-hero-note {
      margin: 20px auto 0;
      color: var(--gold);
      font-size: 14px;
      letter-spacing: .03em;
      font-weight: 600;
    }

    .zadv-hero-actions {
      margin-top: 36px;
      display: flex;
      gap: 14px;
      justify-content: center;
      flex-wrap: wrap
    }

    /* ---------------- SERVICES ---------------- */
    .zadv-services {
      background: var(--light);
      padding: 90px 0
    }

    .zadv-section-head {
      text-align: center;
      max-width: 620px;
      margin: 0 auto 50px
    }

    .zadv-section-head p {
      margin-top: 12px;
      font-size: 17px
    }

    .zadv-section-head h2 {
      font-size: clamp(26px, 3.2vw, 38px)
    }

    .zadv-cards {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 28px
    }

    .zadv-card {
      background: var(--white);
      border: 1px solid #ECEFF3;
      border-top: 4px solid var(--gold);
      border-radius: var(--radius);
      padding: 38px 34px;
      box-shadow: var(--shadow);
      transition: transform .3s ease, box-shadow .3s ease;
    }

    .zadv-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 22px 55px rgba(11, 44, 61, .16)
    }

    .zadv-card-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 22px;
    }

    .zadv-card-icon--select {
      background: rgba(179, 147, 89, .14)
    }

    .zadv-card-icon--upgrade {
      background: rgba(62, 142, 65, .14)
    }

    .zadv-card h3 {
      font-size: 26px
    }

    .zadv-card .zadv-tagline {
      color: var(--gold);
      font-weight: 600;
      font-size: 15.5px;
      margin: 4px 0 14px;
    }

    .zadv-card>p {
      font-size: 15.5px
    }

    .zadv-benefits {
      list-style: none;
      margin-top: 20px
    }

    .zadv-benefits li {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding: 9px 0;
      font-size: 15.5px;
      color: #43505B;
    }

    .zadv-benefits li svg {
      flex: none;
      margin-top: 4px
    }

    /* ---------------- WHY CHOOSE ---------------- */
    .zadv-why {
      background: var(--cream);
      padding: 90px 0
    }

    .zadv-why-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px
    }

    .zadv-why-item {
      background: var(--white);
      border: 1px solid #F0EAE0;
      border-radius: var(--radius);
      padding: 32px 26px;
      text-align: center;
      transition: transform .3s ease, box-shadow .3s ease;
    }

    .zadv-why-item:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow)
    }

    .zadv-why-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 18px;
      border-radius: 50%;
      background: rgba(179, 147, 89, .13);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .zadv-why-item h3 {
      font-size: 18px;
      margin-bottom: 8px
    }

    .zadv-why-item p {
      font-size: 14.5px
    }

    /* ---------------- FINAL CTA ---------------- */
    .zadv-cta {
      background: linear-gradient(150deg, var(--navy) 0%, var(--navy-2) 100%);
      padding: 90px 0;
      text-align: center;
      color: var(--white);
      position: relative;
      overflow: hidden;
    }

    .zadv-cta::before {
      content: "";
      position: absolute;
      width: 420px;
      height: 420px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(179, 147, 89, .16), transparent 70%);
      top: -160px;
      right: -120px;
      pointer-events: none;
    }

    .zadv-cta h2 {
      color: var(--white);
      font-size: clamp(26px, 3.4vw, 40px)
    }

    .zadv-cta .zadv-phone {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      margin-top: 20px;
      color: var(--gold);
      font-size: clamp(22px, 3vw, 32px);
      font-weight: 700;
      letter-spacing: .04em;
      text-decoration: none;
    }

    .zadv-cta p.zadv-small {
      margin-top: 10px;
      color: #B9C6CE;
      font-size: 16px
    }

    .zadv-cta-actions {
      margin-top: 34px;
      display: flex;
      gap: 14px;
      justify-content: center;
      flex-wrap: wrap
    }

    /* ---------------- FOOTER STRIP ---------------- */
    .zadv-footnote {
      background: var(--navy);
      border-top: 1px solid rgba(255, 255, 255, .08);
      text-align: center;
      padding: 18px 16px;
      color: #8FA3AE;
      font-size: 14px;
    }

    .zadv-footnote a {
      color: var(--gold);
      text-decoration: none
    }

    @media (prefers-reduced-motion:reduce) {
      .zadv * {
        transition: none !important
      }
    }

    /* ==============================================================
     RESPONSIVE
     ============================================================== */
    @media (max-width:991px) {

      /* Tablet */
      .zadv-hero {
        padding: 84px 0 90px
      }

      .zadv-services,
      .zadv-why,
      .zadv-cta {
        padding: 70px 0
      }

      .zadv-cards {
        gap: 20px
      }

      .zadv-why-grid {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    @media (max-width:767px) {

      /* Mobile */
      .zadv-container {
        padding: 10px 20px;
      }

      .zadv-hero {
        padding: 64px 0 70px
      }

      .zadv-hero p {
        font-size: 16px
      }

      .zadv-services,
      .zadv-why,
      .zadv-cta {
        padding: 56px 0
      }

      .zadv-cards {
        grid-template-columns: 1fr
      }

      .zadv-card {
        padding: 28px 22px
      }

      .zadv-why-grid {
        grid-template-columns: 1fr;
        gap: 16px
      }

      .zadv-hero-actions .zadv-btn,
      .zadv-cta-actions .zadv-btn {
        width: 100%;
        max-width: 340px
      }
    }
  </style>

  <div class="zadv">

    <!-- ================= HERO ================= -->
    <section class="zadv-hero">
      <div class="zadv-container">
        <span class="zadv-eyebrow">ZENDO Advisory Services</span>
        <h1>Enter Any Market. <span>Expand With Confidence.</span></h1>
        <p>ZENDO advises businesses entering or expanding across Indian markets — delivering the right space, logistics,
          feasibility, and a fully costed plan through one dedicated team.</p>
        <p class="zadv-hero-note">&mdash; Advisory across India's key industrial markets.</p>
        <div class="zadv-hero-actions">
          <a href="#services" class="zadv-btn zadv-btn-gold">
            Explore Our Services
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M13 6l6 6-6 6" />
            </svg>
          </a>
          <a href="#contact" class="zadv-btn zadv-btn-outline">
            Speak to an Advisor
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M13 6l6 6-6 6" />
            </svg>
          </a>
        </div>
      </div>
    </section>

    <!-- ================= SERVICES ================= -->
    <section class="zadv-services" id="services">
      <div class="zadv-container">
        <div class="zadv-section-head">
          <span class="zadv-eyebrow">What We Offer</span>
          <h2>Two Advisory Tracks. One Growth Partner.</h2>
          <p>Whether you're setting up in a new market or optimising an existing operation, ZENDO has an advisory built
            for you.</p>
        </div>

        <div class="zadv-cards">

          <!-- ZENDO Select -->
          <article class="zadv-card">
            <div class="zadv-card-icon zadv-card-icon--select" aria-hidden="true">
              <svg width="30" height="30" viewBox="0 0 32 32" fill="none" stroke="#B39359" stroke-width="1.8"
                stroke-linejoin="round">
                <path d="M4 12 16 4l12 8v16H4V12Z" />
                <path d="M10 28V16h12v12M13 20h6" />
              </svg>
            </div>
            <h3>ZENDO Select</h3>
            <div class="zadv-tagline">Plan Right. Enter Right.</div>
            <p>For businesses entering new markets or expanding into new locations.</p>
            <ul class="zadv-benefits">
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#B39359" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Market &amp; location selection guidance</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#B39359" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Space sourcing across warehousing, commercial &amp; industrial</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#B39359" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> In-house vs 3PL logistics modelling with break-even analysis</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#B39359" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Feasibility read + fully costed setup and monthly plan</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#B39359" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> State incentives &amp; compliance guidance</li>
            </ul>
          </article>

          <!-- ZENDO Upgrade -->
          <article class="zadv-card">
            <div class="zadv-card-icon zadv-card-icon--upgrade" aria-hidden="true">
              <svg width="30" height="30" viewBox="0 0 32 32" fill="none" stroke="#3E8E41" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 26V18M13 26v-11M19 26V11" />
                <path d="M7 12 14 6l4 3 7-6M21 3h4v4" />
              </svg>
            </div>
            <h3>ZENDO Upgrade</h3>
            <div class="zadv-tagline">Optimise Space. Enhance Performance.</div>
            <p>For businesses with existing operations looking to improve performance and cost.</p>
            <ul class="zadv-benefits">
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#3E8E41" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Space utilisation &amp; layout efficiency review</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#3E8E41" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Measurable leasing cost reduction</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#3E8E41" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Rent benchmarking against live market data</li>
              <li><svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="#3E8E41" stroke-width="2.2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 10.5 8.5 15 16 6" />
                </svg> Renewal &amp; renegotiation advisory</li>
            </ul>
          </article>

        </div>
      </div>
    </section>

    <!-- ================= WHY CHOOSE ZENDO ================= -->
    <section class="zadv-why">
      <div class="zadv-container">
        <div class="zadv-section-head">
          <span class="zadv-eyebrow">Why Choose ZENDO?</span>
          <h2>Advisory You Can Rely On</h2>
        </div>

        <div class="zadv-why-grid">
          <div class="zadv-why-item">
            <div class="zadv-why-icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 32 32" fill="none" stroke="#B39359" stroke-width="1.8"
                stroke-linecap="round">
                <circle cx="14" cy="18" r="9" />
                <circle cx="14" cy="18" r="4" />
                <path d="M14 18 26 6M26 6h-5M26 6v5" />
              </svg>
            </div>
            <h3>Independent Advice</h3>
            <p>Unbiased guidance that puts your business first, never the landlord's.</p>
          </div>

          <div class="zadv-why-item">
            <div class="zadv-why-icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 32 32" fill="none" stroke="#B39359" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="16" cy="11" r="5" />
                <path d="M6 27c0-6 4.5-10 10-10s10 4 10 10" />
              </svg>
            </div>
            <h3>One Dedicated Team</h3>
            <p>A single point of contact directs every resource behind the scenes, private-client style.</p>
          </div>

          <div class="zadv-why-item">
            <div class="zadv-why-icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 32 32" fill="none" stroke="#B39359" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 26h24M6 26V14l6-4v16M12 26V10l8-5v21M20 26V12l6 4v10" />
              </svg>
            </div>
            <h3>Practical Industry Experience</h3>
            <p>Real, on-ground warehousing expertise across India's key markets.</p>
          </div>

          <div class="zadv-why-item">
            <div class="zadv-why-icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 32 32" fill="none" stroke="#B39359" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="16" cy="17" r="11" />
                <path d="M16 10v7l5 3M12 3h8" />
              </svg>
            </div>
            <h3>Faster Decision-Making</h3>
            <p>Clear, data-backed recommendations so you move quickly and confidently.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= FINAL CTA ================= -->
    <section class="zadv-cta" id="contact">
      <div class="zadv-container">
        <span class="zadv-eyebrow">Get Started</span>
        <h2>Speak to a ZENDO Advisor</h2>
        <a class="zadv-phone" href="tel:+917494010101" aria-label="Call 7494 01 01 01">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <path
              d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.6 2Z" />
          </svg>
          Call: 7494-01-01-01
        </a>
        <p class="zadv-small">Our advisors are available Monday to Saturday, 9 AM – 7 PM.</p>
        <div class="zadv-cta-actions">
          <a href="tel:+917494010101" class="zadv-btn zadv-btn-gold">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.6 2Z" />
            </svg>
            Call Now
          </a>
          <a href="mailto:info@zendoindia.com" target="_blank" rel="noopener" class="zadv-btn zadv-btn-whatsapp">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.6-6.1c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.3-.7.8-.8 1-.1.2-.3.2-.6.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4 0-.5.1-.7l.5-.6c.1-.2.1-.3 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-1 .9-1 2.2s1 2.6 1.1 2.8c.1.2 2 3.1 4.8 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.6-.3Z" />
            </svg>
            Email Us
          </a>
        </div>
      </div>
    </section>

    <!-- ================= FOOT NOTE ================= -->
    <div class="zadv-footnote">
      A premium advisory service by <a href="https://zendoindia.com">ZendoIndia</a> · Independent Advice · Market
      Intelligence · End-to-End Support
    </div>

  </div><!-- /.zadv -->

@endsection