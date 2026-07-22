<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stamp Duty Calculator - Zendo India</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --cream:      #F7F3ED;
  --warm-white: #FDFAF6;
  --beige:      #EDE5D8;
  --beige-mid:  #D9CCBA;
  --gold:       #B8975A;<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stamp Duty Calculator - Zendo India</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --cream:      #F7F3ED;
  --warm-white: #FDFAF6;
  --beige:      #EDE5D8;
  --beige-mid:  #D9CCBA;
  --gold:       #B8975A;
  --gold-light: #D4B47A;
  --navy:       #0b2c3d;
  --brown:      #5C4A32;
  --text:       #3A3530;
  --muted:      #8A7E72;
  --border:     #DDD4C4;
  --radius:     4px;
  --radius-lg:  8px;
}

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--cream);
  color: var(--text);
  min-height: 100vh;
}

/* HEADER */
.header { background: var(--navy); padding: 0 32px; border-bottom: 1px solid rgba(184,151,90,0.3); }
.header-inner {
  max-width: 1200px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between; height: 68px;
}
.logo { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: var(--warm-white); text-decoration: none; letter-spacing: 0.04em; }
.logo span { color: var(--gold); }
.header-nav { display: flex; gap: 28px; }
.header-nav a { font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.5); text-decoration: none; letter-spacing: 0.06em; text-transform: uppercase; transition: color 0.2s; }
.header-nav a:hover { color: var(--gold-light); }
.header-cta { background: transparent; color: var(--gold); border: 1px solid var(--gold); padding: 9px 22px; border-radius: var(--radius); font-size: 12px; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s; }
.header-cta:hover { background: var(--gold); color: var(--navy); }

/* HERO */
.hero { background: var(--navy); padding: 48px 32px 72px; text-align: center; position: relative; overflow: hidden; }
.hero::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23B8975A' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.hero-eyebrow { font-size: 11px; font-weight: 500; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold); margin-bottom: 14px; position: relative; display: inline-flex; align-items: center; gap: 10px; }
.hero-eyebrow::before, .hero-eyebrow::after { content: ''; width: 40px; height: 1px; background: var(--gold); opacity: 0.5; }
.hero h1 { font-family: 'Cormorant Garamond', serif; font-size: 42px; font-weight: 400; color: var(--warm-white); letter-spacing: 0.02em; line-height: 1.15; position: relative; margin-bottom: 12px; }
.hero h1 em { font-style: italic; color: var(--gold-light); }
.hero-sub { font-size: 14px; color: rgba(255,255,255,0.45); max-width: 500px; margin: 0 auto; line-height: 1.7; position: relative; font-weight: 300; }

/* MAIN WRAPPER */
.main {
  max-width: 1200px;
  margin: -36px auto 48px;
  padding: 0 20px;
  position: relative;
  z-index: 2;
}

/* CARD */
.card {
  background: var(--warm-white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  box-shadow: 0 8px 40px rgba(11,44,61,0.12);
  overflow: hidden;
  margin-bottom: 24px;
}
.card-header {
  background: var(--navy);
  padding: 18px 28px;
  display: flex; align-items: center; gap: 12px;
  border-bottom: 2px solid var(--gold);
}
.card-header-icon { width: 36px; height: 36px; border-radius: 50%; background: rgba(184,151,90,0.15); border: 1px solid rgba(184,151,90,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.card-header h2 { font-family: 'Cormorant Garamond', serif; font-size: 18px; font-weight: 600; color: var(--warm-white); letter-spacing: 0.03em; }
.card-header p { font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 1px; font-weight: 300; }
.card-body { padding: 28px 32px; }

/* FORM GRID */
.form-row {
  display: grid;
  gap: 20px;
  margin-bottom: 20px;
}
.form-row.cols-2 { grid-template-columns: 1fr 1fr; }
.form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-row.cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

@media (max-width: 900px) {
  .form-row.cols-2, .form-row.cols-3, .form-row.cols-4 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .form-row.cols-2, .form-row.cols-3, .form-row.cols-4 { grid-template-columns: 1fr; }
  .hero h1 { font-size: 28px; }
  .header-nav { display: none; }
}

.form-group { display: flex; flex-direction: column; }
.form-label {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; font-weight: 600; color: var(--navy);
  letter-spacing: 0.04em; margin-bottom: 8px;
}
.form-label .lbl-icon { font-size: 13px; }
.form-label .required { color: #c0392b; font-size: 13px; }

.form-select, .form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  font-size: 14px;
  font-family: 'DM Sans', sans-serif;
  color: var(--text);
  background: #fff;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  -webkit-appearance: none; appearance: none;
  height: 48px;
}
.form-select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238A7E72' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 14px center;
  background-color: #fff; padding-right: 40px; cursor: pointer;
}
.form-select:focus, .form-input:focus {
  border-color: var(--navy);
  box-shadow: 0 0 0 3px rgba(11,44,61,0.08);
}
.form-input::placeholder { color: #b0a898; }

/* Prefix / suffix input */
.input-wrap { position: relative; display: flex; align-items: center; }
.input-prefix-abs { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 600; color: var(--muted); pointer-events: none; }
.input-suffix-abs { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 13px; font-weight: 500; color: var(--muted); pointer-events: none; }
.input-wrap .form-input { padding-left: 28px; }
.input-wrap .form-input.has-suffix { padding-right: 52px; }

/* LEASE FIELDS SECTION */
.lease-section {
  display: none;
  border: 1.5px solid rgba(184,151,90,0.35);
  border-radius: var(--radius-lg);
  padding: 22px 24px 18px;
  margin-bottom: 20px;
  background: rgba(184,151,90,0.04);
  position: relative;
}
.lease-section.visible { display: block; }
.lease-section-label {
  position: absolute;
  top: -11px; left: 16px;
  background: var(--warm-white);
  padding: 0 8px;
  font-size: 11px; font-weight: 600; color: var(--gold);
  letter-spacing: 0.1em; text-transform: uppercase;
}

/* CHECKBOX */
.checkbox-row { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.checkbox-row input[type="checkbox"] {
  width: 18px; height: 18px; accent-color: var(--navy);
  border: 1.5px solid var(--border); border-radius: 3px; cursor: pointer; flex-shrink: 0;
}
.checkbox-row label { font-size: 14px; color: var(--text); cursor: pointer; font-weight: 400; }

/* GENDER TIP */
.gender-tip {
  font-size: 11px; color: var(--gold);
  background: rgba(184,151,90,0.08); border: 1px solid rgba(184,151,90,0.2);
  border-radius: var(--radius); padding: 7px 12px; font-weight: 500;
  display: none; margin-top: 6px;
}
.gender-tip.show { display: block; }

/* ADVOCATE RANGE */
.advocate-range-wrap { margin-top: 6px; }
.advocate-range-wrap input[type="range"] {
  width: 100%; -webkit-appearance: none; height: 3px; border-radius: 100px; outline: none; cursor: pointer;
}
.advocate-range-wrap input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none; width: 20px; height: 20px; border-radius: 50%;
  background: #fff; border: 2px solid var(--gold);
  box-shadow: 0 2px 6px rgba(184,151,90,0.4); cursor: grab;
}
.advocate-range-wrap input[type="range"]::-moz-range-thumb {
  width: 20px; height: 20px; border-radius: 50%;
  background: #fff; border: 2px solid var(--gold);
  box-shadow: 0 2px 6px rgba(184,151,90,0.4); cursor: grab;
}
.advocate-labels { display: flex; justify-content: space-between; font-size: 10px; color: var(--muted); margin-top: 4px; }
.advocate-val { font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 600; color: var(--navy); margin-bottom: 4px; }

/* DIVIDER */
.divider { height: 1px; background: var(--beige); margin: 4px 0 24px; }

/* CALCULATE BTN */
.btn-row { display: flex; justify-content: center; }
.btn-calculate {
  background: var(--navy); color: var(--gold-light);
  border: 1px solid var(--gold); padding: 15px 56px;
  border-radius: 6px; font-size: 14px; font-weight: 600;
  font-family: 'DM Sans', sans-serif; cursor: pointer;
  letter-spacing: 0.1em; text-transform: uppercase;
  transition: all 0.2s; display: flex; align-items: center; gap: 10px;
}
.btn-calculate:hover { background: var(--gold); color: var(--navy); }
.btn-calculate:active { transform: scale(0.99); }

/* RESULTS GRID */
.results-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 24px;
}
@media (max-width: 700px) { .results-grid { grid-template-columns: 1fr; } }

.result-hidden { display: none; }

/* LEASE RESULTS CARD — full width */
.lease-results-card {
  background: var(--warm-white); border-radius: var(--radius-lg);
  border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(11,44,61,0.08);
  overflow: hidden; margin-bottom: 24px;
}
.lease-results-card .breakdown-header { background: var(--navy); padding: 14px 22px; border-bottom: 2px solid var(--gold); display: flex; align-items: center; gap: 10px; }
.lease-results-card .breakdown-header h3 { font-family: 'Cormorant Garamond', serif; font-size: 16px; font-weight: 600; color: var(--warm-white); }
.lease-results-body { padding: 20px 24px; }

/* Rent Schedule Table */
.schedule-table-wrap { overflow-x: auto; margin-top: 16px; }
.schedule-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.schedule-table thead tr { background: var(--navy); }
.schedule-table th { padding: 11px 16px; text-align: left; font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.55); letter-spacing: 0.08em; text-transform: uppercase; }
.schedule-table th:first-child { color: var(--gold-light); }
.schedule-table td { padding: 10px 16px; border-bottom: 1px solid var(--beige); color: var(--text); font-size: 13px; }
.schedule-table tbody tr:last-child td { border-bottom: none; }
.schedule-table tbody tr:hover td { background: #FBF5E9; }
.schedule-table td:first-child { font-weight: 600; color: var(--navy); }

/* Lease summary metrics */
.lease-metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px; }
@media (max-width: 700px) { .lease-metrics { grid-template-columns: 1fr 1fr; } }
.lease-metric-cell {
  background: var(--navy); border-radius: var(--radius);
  padding: 14px 16px; text-align: center;
  border: 1px solid rgba(184,151,90,0.2);
}
.lease-metric-label { font-size: 10px; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; font-weight: 500; }
.lease-metric-val { font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 600; color: var(--gold-light); line-height: 1.2; }
.lease-metric-sub { font-size: 10px; color: rgba(255,255,255,0.3); margin-top: 3px; }

/* breakdown card */
.breakdown-card {
  background: var(--warm-white); border-radius: var(--radius-lg);
  border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(11,44,61,0.08);
  overflow: hidden;
}
.breakdown-header {
  background: var(--navy); padding: 14px 22px;
  border-bottom: 2px solid var(--gold);
  display: flex; align-items: center; gap: 10px;
}
.breakdown-header h3 { font-family: 'Cormorant Garamond', serif; font-size: 16px; font-weight: 600; color: var(--warm-white); }
.breakdown-body { padding: 20px 22px; }

.rate-banner {
  background: var(--navy); border-radius: var(--radius);
  padding: 12px 16px; margin-bottom: 18px;
  display: flex; align-items: flex-start; gap: 8px;
}
.rate-banner-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); margin-top: 5px; flex-shrink: 0; }
.rate-banner p { font-size: 12px; color: rgba(255,255,255,0.65); line-height: 1.6; font-weight: 300; }
.rate-banner strong { color: var(--gold-light); font-weight: 600; }

.result-row { display: flex; justify-content: space-between; align-items: center; padding: 11px 0; border-bottom: 1px solid var(--beige); }
.result-row:last-of-type { border-bottom: none; }
.result-label { font-size: 12px; color: var(--muted); font-weight: 500; }
.result-value { font-size: 14px; font-weight: 600; color: var(--text); }
.result-value.gold { color: var(--gold); }
.result-value.navy { color: var(--navy); }

.total-box {
  background: var(--navy); border-radius: var(--radius-lg);
  padding: 18px 20px; margin-top: 16px;
  display: flex; justify-content: space-between; align-items: center;
  border: 1px solid rgba(184,151,90,0.25); position: relative; overflow: hidden;
}
.total-box::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: var(--gold); }
.total-label { font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.12em; }
.total-sub { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 3px; font-weight: 300; }
.total-value { font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 600; color: var(--gold-light); }

/* PIE */
.pie-section { display: flex; align-items: center; gap: 16px; margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--beige); }
.pie-legend { flex: 1; }
.pie-legend-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; font-size: 11px; }
.pie-legend-left { display: flex; align-items: center; gap: 7px; color: var(--muted); }
.pie-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pie-dot.navy { background: var(--navy); }
.pie-dot.gold { background: var(--gold); }
.pie-dot.brown { background: var(--brown); }
.pie-legend-val { font-weight: 600; color: var(--text); font-size: 11px; }

.disclaimer-box {
  background: #FEFBF3; border: 1px solid var(--beige-mid); border-left: 3px solid var(--gold);
  border-radius: var(--radius); padding: 10px 14px; margin-top: 16px;
  font-size: 11px; color: var(--muted); line-height: 1.6; font-weight: 300;
}

/* STATE TABLE */
.table-section { max-width: 1200px; margin: 0 auto 48px; padding: 0 20px; }
.section-heading { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
.section-heading h3 { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: var(--navy); letter-spacing: 0.02em; white-space: nowrap; }
.section-heading-line { flex: 1; height: 1px; background: var(--border); }
.table-card { background: var(--warm-white); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; box-shadow: 0 4px 20px rgba(11,44,61,0.07); }
.state-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.state-table th { background: var(--navy); color: rgba(255,255,255,0.55); padding: 13px 18px; text-align: left; font-weight: 500; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; }
.state-table th:first-child { color: var(--gold-light); }
.state-table td { padding: 11px 18px; border-bottom: 1px solid var(--beige); vertical-align: middle; line-height: 1.5; }
.state-table tr:last-child td { border-bottom: none; }
.state-table tr:nth-child(even) td { background: #FDFAF6; }
.state-table tr:hover td { background: #FBF5E9; transition: background 0.12s; }
.state-name { font-weight: 600; color: var(--navy); }
.rate-pill { display: inline-block; background: var(--navy); color: var(--gold-light); padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; white-space: nowrap; }
.reg-pill { display: inline-block; background: var(--beige); color: var(--brown); padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 500; white-space: nowrap; }
.note-text { font-size: 11px; color: var(--muted); font-weight: 300; }
.gender-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 10px; color: var(--gold); background: rgba(184,151,90,0.1); border: 1px solid rgba(184,151,90,0.25); padding: 2px 7px; border-radius: 100px; font-weight: 600; margin-left: 4px; white-space: nowrap; }

.footer-note { max-width: 1200px; margin: 0 auto 36px; padding: 20px 20px 0; font-size: 11px; color: var(--muted); line-height: 1.8; border-top: 1px solid var(--border); font-weight: 300; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.animate-in { animation: fadeUp 0.3s ease; }
</style>
</head>
<body>

<!-- HEADER -->
<header class="header">
  <div class="header-inner">
    <a href="#" class="logo">Zendo<span>India</span></a>
    <nav class="header-nav">
      <a href="#">Projects</a>
      <a href="#">About</a>
      <a href="#">Blog</a>
      <a href="#">Contact</a>
    </nav>
    <button class="header-cta">Talk to Expert</button>
  </div>
</header>

<!-- HERO -->
<section class="hero">
  <div class="hero-eyebrow">Property Tool</div>
  <h1>Stamp Duty <em>Calculator</em></h1>
  <p class="hero-sub">Estimate stamp duty, registration charges & advocate fees across all Indian states. Rates vary by state and buyer gender.</p>
</section>

<!-- MAIN WRAPPER -->
<div class="main">

  <!-- INPUT CARD -->
  <div class="card">
    <div class="card-header">
      <div class="card-header-icon">🏛</div>
      <div>
        <h2>Property Details</h2>
        <p>Fill in details below to calculate all applicable charges</p>
      </div>
    </div>
    <div class="card-body">

      <!-- ROW 1: State + Transaction Type -->
      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">📍</span> State <span class="required">*</span></label>
          <select class="form-select" id="stateSelect" onchange="onStateChange()">
            <option value="">Select State</option>
            <option value="andhra">Andhra Pradesh</option>
            <option value="assam">Assam</option>
            <option value="bihar">Bihar</option>
            <option value="chhattisgarh">Chhattisgarh</option>
            <option value="delhi">Delhi</option>
            <option value="gujarat">Gujarat</option>
            <option value="haryana">Haryana</option>
            <option value="jharkhand">Jharkhand</option>
            <option value="karnataka">Karnataka</option>
            <option value="kerala">Kerala</option>
            <option value="madhyapradesh">Madhya Pradesh</option>
            <option value="maharashtra">Maharashtra</option>
            <option value="odisha">Odisha</option>
            <option value="punjab">Punjab</option>
            <option value="rajasthan">Rajasthan</option>
            <option value="tamilnadu">Tamil Nadu</option>
            <option value="telangana">Telangana</option>
            <option value="uttarpradesh">Uttar Pradesh</option>
            <option value="uttarakhand">Uttarakhand</option>
            <option value="westbengal">West Bengal</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">📋</span> Transaction Type <span class="required">*</span></label>
          <select class="form-select" id="txnType" onchange="onTxnChange()">
            <option value="">Select Transaction Type</option>
            <option value="sale" selected>Sale / Purchase</option>
            <option value="gift">Gift Deed</option>
            <option value="lease">Lease Agreement</option>
            <option value="mortgage">Mortgage / Loan</option>
          </select>
        </div>
      </div>

      <!-- ROW 2: Property Type + Property Value + Buyer Gender -->
      <div class="form-row cols-3">
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">🏢</span> Property Type</label>
          <select class="form-select" id="propType" onchange="calculate()">
            <option value="warehouse" selected>Warehouse</option>
            <option value="residential">Residential</option>
            <option value="commercial">Commercial</option>
            <option value="industrial">Industrial</option>
            <option value="agricultural">Agricultural Land</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">₹</span> Property Value (Rs.) <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-prefix-abs">₹</span>
            <input type="number" class="form-input" id="propValue" placeholder="Enter property value"
              min="100000" step="100000"
              oninput="updateSlider(); calculate()">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">👤</span> Buyer Gender</label>
          <select class="form-select" id="genderSelect" onchange="calculate()">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="joint">Joint (Male + Female)</option>
          </select>
          <div class="gender-tip" id="genderTip">⚡ Female buyers get a lower stamp duty rate in this state</div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════════════
           LEASE AGREEMENT FIELDS (visible only when txnType = lease)
           ═══════════════════════════════════════════════════════════ -->
      <div class="lease-section" id="leaseSection">
        <span class="lease-section-label">📄 Lease Agreement Details</span>

        <!-- Lease Row 1: Monthly Rent + Lease Period -->
        <div class="form-row cols-2" style="margin-bottom:16px;">
          <div class="form-group">
            <label class="form-label"><span class="lbl-icon">💰</span> Monthly Rent (₹) <span class="required">*</span></label>
            <div class="input-wrap">
              <span class="input-prefix-abs">₹</span>
              <input type="number" class="form-input" id="monthlyRent" placeholder="Enter monthly rent" min="0" oninput="calculate()">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label"><span class="lbl-icon">📅</span> Lease Period <span class="required">*</span></label>
            <div class="input-wrap">
              <input type="number" class="form-input has-suffix" id="leasePeriod" placeholder="Number of years" min="1" max="99" oninput="calculate()">
              <span class="input-suffix-abs">Yrs</span>
            </div>
          </div>
        </div>

        <!-- Lease Row 2: Escalation % + Escalation Frequency + Security Amount -->
        <div class="form-row cols-3" style="margin-bottom:0;">
          <div class="form-group">
            <label class="form-label"><span class="lbl-icon">📈</span> Rent Escalation (%)</label>
            <div class="input-wrap">
              <input type="number" class="form-input has-suffix" id="escalation" placeholder="e.g. 5" min="0" max="100" step="0.1" oninput="calculate()">
              <span class="input-suffix-abs">%</span>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label"><span class="lbl-icon">🔁</span> Escalation Frequency</label>
            <select class="form-select" id="escalationFreq" onchange="calculate()">
              <option value="annual">Annual (Every Year)</option>
              <option value="biennial">Biennial (Every 2 Years)</option>
              <option value="triennial">Triennial (Every 3 Years)</option>
              <option value="none">No Escalation</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label"><span class="lbl-icon">🔒</span> Security Deposit (₹)</label>
            <div class="input-wrap">
              <span class="input-prefix-abs">₹</span>
              <input type="number" class="form-input" id="securityAmount" placeholder="Enter security amount" min="0" oninput="calculate()">
            </div>
          </div>
        </div>
      </div>

      <!-- ROW 3: Advocate Fee + Property Value Slider -->
      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">⚖️</span> Advocate / Legal Fee</label>
          <div class="advocate-val" id="advocateDisplay">₹25,000</div>
          <div class="advocate-range-wrap">
            <input type="range" id="advocateSlider" min="10000" max="50000" step="1000" value="25000"
              oninput="updateAdvocate(); calculate()">
            <div class="advocate-labels"><span>₹10,000</span><span>₹50,000</span></div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">📊</span> Property Value Slider</label>
          <div class="advocate-val" id="propDisplay">—</div>
          <div class="advocate-range-wrap">
            <input type="range" id="propSlider" min="100000" max="100000000" step="100000" value="5000000"
              oninput="syncPropInput(this.value); calculate()">
            <div class="advocate-labels"><span>₹1 Lakh</span><span>₹10 Cr</span></div>
          </div>
        </div>
      </div>

      <!-- CHECKBOX -->
      <div class="checkbox-row">
        <input type="checkbox" id="firstTimeBuyer" onchange="calculate()">
        <label for="firstTimeBuyer">First-time property buyer <span style="font-size:11px;color:var(--muted);">(some states offer rebate)</span></label>
      </div>

      <div class="divider"></div>
      <div class="btn-row">
        <button class="btn-calculate" onclick="calculate()">
          <span>⊞</span> Calculate Stamp Duty
        </button>
      </div>
    </div>
  </div>

  <!-- RESULTS -->
  <div id="resultsSection" class="result-hidden">

    <!-- LEASE RESULTS (shown only for lease txn) -->
    <div id="leaseResultsCard" class="lease-results-card result-hidden">
      <div class="breakdown-header">
        <div class="card-header-icon" style="width:30px;height:30px;font-size:14px;">📋</div>
        <h3>Lease Summary &amp; Rent Schedule</h3>
      </div>
      <div class="lease-results-body">

        <!-- Key metrics row -->
        <div class="lease-metrics">
          <div class="lease-metric-cell">
            <div class="lease-metric-label">Annual Rent</div>
            <div class="lease-metric-val" id="lm-annual">—</div>
            <div class="lease-metric-sub">Year 1</div>
          </div>
          <div class="lease-metric-cell">
            <div class="lease-metric-label">Avg Annual Rent</div>
            <div class="lease-metric-val" id="lm-avg">—</div>
            <div class="lease-metric-sub">Over Lease Period</div>
          </div>
          <div class="lease-metric-cell">
            <div class="lease-metric-label">Total Rent</div>
            <div class="lease-metric-val" id="lm-total">—</div>
            <div class="lease-metric-sub">Full Lease Term</div>
          </div>
          <div class="lease-metric-cell">
            <div class="lease-metric-label">Security Deposit</div>
            <div class="lease-metric-val" id="lm-security">—</div>
            <div class="lease-metric-sub">Refundable</div>
          </div>
        </div>

        <!-- Rent Schedule Table -->
        <div class="schedule-table-wrap">
          <table class="schedule-table">
            <thead>
              <tr>
                <th>Year</th>
                <th>Annual Rent (₹)</th>
                <th>Escalation Applied</th>
                <th>Cumulative Rent (₹)</th>
              </tr>
            </thead>
            <tbody id="scheduleBody">
              <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:20px;font-size:13px;">Enter lease details above and click Calculate</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- CHARGES BREAKDOWN + VISUAL SUMMARY -->
    <div class="results-grid">

      <!-- Charges Breakdown -->
      <div class="breakdown-card">
        <div class="breakdown-header">
          <div class="card-header-icon" style="width:30px;height:30px;font-size:14px;">📊</div>
          <h3>Charges Breakdown</h3>
        </div>
        <div class="breakdown-body">
          <div class="rate-banner">
            <div class="rate-banner-dot"></div>
            <p id="rateInfoText">—</p>
          </div>
          <div class="result-row">
            <span class="result-label">Property Value</span>
            <span class="result-value navy" id="r-propValue">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Transaction Type</span>
            <span class="result-value" id="r-txnType">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Property Type</span>
            <span class="result-value" id="r-propType">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Stamp Duty Rate</span>
            <span class="result-value gold" id="r-rate">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Stamp Duty Amount</span>
            <span class="result-value" id="r-stampDuty">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Registration Charges</span>
            <span class="result-value" id="r-reg">—</span>
          </div>
          <!-- Security deposit row (lease only) -->
          <div class="result-row" id="r-securityRow" style="display:none;">
            <span class="result-label">Security Deposit</span>
            <span class="result-value" id="r-security">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Advocate / Legal Fee</span>
            <span class="result-value" id="r-advocate">—</span>
          </div>
          <div class="total-box">
            <div>
              <div class="total-label">Grand Total</div>
              <div class="total-sub" id="grandTotalSub">Stamp + Reg + Advocate</div>
            </div>
            <div class="total-value" id="r-grand">—</div>
          </div>
        </div>
      </div>

      <!-- Visual Summary -->
      <div class="breakdown-card">
        <div class="breakdown-header">
          <div class="card-header-icon" style="width:30px;height:30px;font-size:14px;">🥧</div>
          <h3>Cost Summary</h3>
        </div>
        <div class="breakdown-body">
          <div style="text-align:center; padding: 12px 0 4px;">
            <svg id="pieChart" width="140" height="140" viewBox="0 0 140 140"></svg>
          </div>
          <div class="pie-section" style="padding-top:16px; margin-top:0; border-top: 1px solid var(--beige);">
            <div class="pie-legend" style="width:100%;">
              <div class="pie-legend-row">
                <div class="pie-legend-left"><span class="pie-dot navy"></span> Stamp Duty</div>
                <span class="pie-legend-val" id="pie-stamp">—</span>
              </div>
              <div class="pie-legend-row">
                <div class="pie-legend-left"><span class="pie-dot gold"></span> Registration</div>
                <span class="pie-legend-val" id="pie-reg">—</span>
              </div>
              <div class="pie-legend-row" id="pie-securityRow" style="display:none;">
                <div class="pie-legend-left"><span class="pie-dot" style="background:#16a34a;"></span> Security Deposit</div>
                <span class="pie-legend-val" id="pie-security">—</span>
              </div>
              <div class="pie-legend-row">
                <div class="pie-legend-left"><span class="pie-dot brown"></span> Advocate Fee</div>
                <span class="pie-legend-val" id="pie-adv">—</span>
              </div>
            </div>
          </div>

          <!-- Savings box for female -->
          <div id="savingsBox" style="display:none; background:#f0fdf4; border:1px solid #86efac; border-left:3px solid #16a34a; border-radius:4px; padding:12px 14px; margin-top:16px;">
            <div style="font-size:11px; font-weight:600; color:#15803d; margin-bottom:4px;">💚 Female Buyer Savings</div>
            <div style="font-size:12px; color:#166534; font-weight:300; line-height:1.55;" id="savingsText">—</div>
          </div>

          <!-- First time buyer note -->
          <div id="firstTimBox" style="display:none; background:#eff6ff; border:1px solid #93c5fd; border-left:3px solid #3b82f6; border-radius:4px; padding:12px 14px; margin-top:12px;">
            <div style="font-size:11px; font-weight:600; color:#1d4ed8; margin-bottom:4px;">🏠 First-time Buyer Note</div>
            <div style="font-size:12px; color:#1e40af; font-weight:300; line-height:1.55;">Some states offer stamp duty rebates for first-time buyers under PMAY or state-specific housing schemes. Check with your local sub-registrar office for applicable exemptions.</div>
          </div>

          <div class="disclaimer-box" style="margin-top:16px;">
            Estimated values based on 2025–26 rates. Verify with a legal advisor before registration.
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- STATE TABLE -->
  <div class="table-section" style="padding:0; margin-bottom:48px;">
    <div class="section-heading">
      <h3>State-wise Stamp Duty Rates — 2025–26</h3>
      <div class="section-heading-line"></div>
    </div>
    <div class="table-card">
      <table class="state-table">
        <thead>
          <tr>
            <th>State</th>
            <th>Stamp Duty</th>
            <th>Registration</th>
            <th>Male / Female / Joint</th>
          </tr>
        </thead>
        <tbody>
          <tr><td class="state-name">Andhra Pradesh</td><td><span class="rate-pill">5%</span></td><td><span class="reg-pill">0.5%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Assam</td><td><span class="rate-pill">7.75–8.25%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 8.25% <span class="gender-badge">♀ 7.75%</span></td></tr>
          <tr><td class="state-name">Bihar</td><td><span class="rate-pill">6%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Chhattisgarh</td><td><span class="rate-pill">6–7%</span></td><td><span class="reg-pill">4%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 6%</span> Joint: 6.5%</td></tr>
          <tr><td class="state-name">Delhi</td><td><span class="rate-pill">4–6%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 6% <span class="gender-badge">♀ 4%</span> Joint: 5%</td></tr>
          <tr><td class="state-name">Gujarat</td><td><span class="rate-pill">3.9–4.9%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 4.9% <span class="gender-badge">♀ 3.9%</span></td></tr>
          <tr><td class="state-name">Haryana</td><td><span class="rate-pill">5–7%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 5%</span> Joint: 6%</td></tr>
          <tr><td class="state-name">Jharkhand</td><td><span class="rate-pill">4%</span></td><td><span class="reg-pill">3%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Karnataka</td><td><span class="rate-pill">2–5%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Slab: &lt;₹20L→2%, ₹21–35L→3%, &gt;₹35L→5%</td></tr>
          <tr><td class="state-name">Kerala</td><td><span class="rate-pill">8%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Madhya Pradesh</td><td><span class="rate-pill">7.5%</span></td><td><span class="reg-pill">3%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Maharashtra</td><td><span class="rate-pill">5–6%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 6% <span class="gender-badge">♀ 5%</span> Joint: 5.5%</td></tr>
          <tr><td class="state-name">Odisha</td><td><span class="rate-pill">4–5%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Male: 5% <span class="gender-badge">♀ 4%</span> Joint: 4.5%</td></tr>
          <tr><td class="state-name">Punjab</td><td><span class="rate-pill">5–7%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 5%</span> Joint: 6%</td></tr>
          <tr><td class="state-name">Rajasthan</td><td><span class="rate-pill">5–6%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 6% <span class="gender-badge">♀ 5%</span> Joint: 5.5%</td></tr>
          <tr><td class="state-name">Tamil Nadu</td><td><span class="rate-pill">7%</span></td><td><span class="reg-pill">4%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Telangana</td><td><span class="rate-pill">5%</span></td><td><span class="reg-pill">0.5%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Uttar Pradesh</td><td><span class="rate-pill">6–7%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 6%</span> Joint: 6.5%</td></tr>
          <tr><td class="state-name">Uttarakhand</td><td><span class="rate-pill">5%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">West Bengal</td><td><span class="rate-pill">7–8%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Slab: &lt;₹40L→7%, &gt;₹40L→8%</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- /main -->

<div class="footer-note">
  * Rates are indicative for 2025–26 and subject to change per state government notifications. Gender-based concessions apply in states marked above. Advocate fees are estimates (₹10,000–₹50,000) and vary by property complexity and lawyer. Lease stamp duty is calculated on average annual rent × lease period × applicable rate. Security deposit is shown separately and does not attract stamp duty. Zendo advises all buyers to verify applicable charges with a certified consultant or legal advisor before completing registration.
</div>

<script>
const stateData = {
  andhra:        { name:'Andhra Pradesh',   male:5,    female:5,    joint:5,    reg:0.5, genderVaries:false,
                   note:'<strong>Andhra Pradesh:</strong> Uniform 5% for all buyers. Registration: 0.5%.' },
  assam:         { name:'Assam',            male:8.25, female:7.75, joint:8,    reg:1,   genderVaries:true,
                   note:'<strong>Assam:</strong> Male 8.25% · <strong style="color:var(--gold-light)">Female 7.75%</strong>. Registration: 1%.' },
  bihar:         { name:'Bihar',            male:6,    female:6,    joint:6,    reg:2,   genderVaries:false,
                   note:'<strong>Bihar:</strong> Uniform 6% for all buyers. Registration: 2%.' },
  chhattisgarh:  { name:'Chhattisgarh',     male:7,    female:6,    joint:6.5,  reg:4,   genderVaries:true,
                   note:'<strong>Chhattisgarh:</strong> Male 7% · <strong style="color:var(--gold-light)">Female 6%</strong> · Joint 6.5%. Registration: 4%.' },
  delhi:         { name:'Delhi',            male:6,    female:4,    joint:5,    reg:1,   genderVaries:true,
                   note:'<strong>Delhi:</strong> Male 6% · <strong style="color:var(--gold-light)">Female 4%</strong> · Joint 5%. NDMC areas may vary. Registration: 1%.' },
  gujarat:       { name:'Gujarat',          male:4.9,  female:3.9,  joint:4.4,  reg:1,   genderVaries:true,
                   note:'<strong>Gujarat:</strong> Male 4.9% · <strong style="color:var(--gold-light)">Female 3.9%</strong> · Joint 4.4%. Registration: 1%.' },
  haryana:       { name:'Haryana',          male:7,    female:5,    joint:6,    reg:1,   genderVaries:true,
                   note:'<strong>Haryana (Urban):</strong> Male 7% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 6%. Rural rates lower. Registration: 1%.' },
  jharkhand:     { name:'Jharkhand',        male:4,    female:4,    joint:4,    reg:3,   genderVaries:false,
                   note:'<strong>Jharkhand:</strong> Uniform 4% for all buyers. Registration: 3%.' },
  karnataka:     { name:'Karnataka',        male:null, female:null, joint:null, reg:1,   genderVaries:false, slab:true,
                   note:'<strong>Karnataka (Slab-based):</strong> Below ₹20L → 2% · ₹21–35L → 3% · Above ₹35L → 5%. Registration: 1%.' },
  kerala:        { name:'Kerala',           male:8,    female:8,    joint:8,    reg:2,   genderVaries:false,
                   note:'<strong>Kerala:</strong> Uniform 8% for all buyers. Registration: 2%.' },
  madhyapradesh: { name:'Madhya Pradesh',   male:7.5,  female:7.5,  joint:7.5,  reg:3,   genderVaries:false,
                   note:'<strong>Madhya Pradesh:</strong> Uniform 7.5% for all buyers. Registration: 3%.' },
  maharashtra:   { name:'Maharashtra',      male:6,    female:5,    joint:5.5,  reg:1,   genderVaries:true,
                   note:'<strong>Maharashtra:</strong> Male 6% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 5.5%. Mumbai metro cess may apply. Registration: 1%.' },
  odisha:        { name:'Odisha',           male:5,    female:4,    joint:4.5,  reg:2,   genderVaries:true,
                   note:'<strong>Odisha:</strong> Male 5% · <strong style="color:var(--gold-light)">Female 4%</strong> · Joint 4.5%. Registration: 2%.' },
  punjab:        { name:'Punjab',           male:7,    female:5,    joint:6,    reg:1,   genderVaries:true,
                   note:'<strong>Punjab:</strong> Male 7% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 6%. Registration: 1%.' },
  rajasthan:     { name:'Rajasthan',        male:6,    female:5,    joint:5.5,  reg:1,   genderVaries:true,
                   note:'<strong>Rajasthan:</strong> Male 6% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 5.5%. Registration: 1%.' },
  tamilnadu:     { name:'Tamil Nadu',       male:7,    female:7,    joint:7,    reg:4,   genderVaries:false,
                   note:'<strong>Tamil Nadu:</strong> Uniform 7% for all buyers. Registration: 4%.' },
  telangana:     { name:'Telangana',        male:5,    female:5,    joint:5,    reg:0.5, genderVaries:false,
                   note:'<strong>Telangana:</strong> Uniform 5% for all buyers. Registration: 0.5%.' },
  uttarpradesh:  { name:'Uttar Pradesh',    male:7,    female:6,    joint:6.5,  reg:1,   genderVaries:true,
                   note:'<strong>Uttar Pradesh:</strong> Male 7% · <strong style="color:var(--gold-light)">Female 6%</strong> · Joint 6.5%. Gift deeds to blood relatives: ₹5,000 flat. Registration: 1%.' },
  uttarakhand:   { name:'Uttarakhand',      male:5,    female:5,    joint:5,    reg:2,   genderVaries:false,
                   note:'<strong>Uttarakhand:</strong> Uniform 5% for all buyers. Registration: 2%.' },
  westbengal:    { name:'West Bengal',      male:null, female:null, joint:null, reg:1,   genderVaries:false, slab:true,
                   note:'<strong>West Bengal:</strong> Up to ₹40L → 7% · Above ₹40L → 8%. Registration: 1%.' }
};

// Lease-specific stamp duty rates (% of avgAnnualRent × years)
const leaseStampRates = {
  andhra:1, assam:1, bihar:1, chhattisgarh:1,
  delhi:2, gujarat:1.5, haryana:2, jharkhand:1,
  karnataka:1, kerala:1, madhyapradesh:1, maharashtra:0.5,
  odisha:1, punjab:1, rajasthan:1, tamilnadu:1,
  telangana:1, uttarpradesh:2, uttarakhand:1, westbengal:1
};

const txnLabels = { sale:'Sale / Purchase', gift:'Gift Deed', lease:'Lease Agreement', mortgage:'Mortgage / Loan' };
const propLabels = { warehouse:'Warehouse', residential:'Residential', commercial:'Commercial', industrial:'Industrial', agricultural:'Agricultural Land' };

function formatINR(n) {
  if (n >= 10000000) return '₹' + (n/10000000).toFixed(2) + ' Cr';
  if (n >= 100000)   return '₹' + (n/100000).toFixed(2) + ' Lakh';
  return '₹' + Math.round(n).toLocaleString('en-IN');
}

function getKarnatakaRate(v) { return v<=2000000?2:v<=3500000?3:5; }
function getWestBengalRate(v) { return v<=4000000?7:8; }

function updateAdvocate() {
  const v = parseInt(document.getElementById('advocateSlider').value);
  document.getElementById('advocateDisplay').textContent = '₹' + v.toLocaleString('en-IN');
  const sl = document.getElementById('advocateSlider');
  const pct = ((v - 10000) / 40000) * 100;
  sl.style.background = `linear-gradient(to right,#B8975A 0%,#B8975A ${pct}%,#EDE5D8 ${pct}%,#EDE5D8 100%)`;
}

function updateSlider() {
  const v = parseFloat(document.getElementById('propValue').value) || 0;
  document.getElementById('propSlider').value = Math.min(v, 100000000);
  updatePropDisplay(v);
}

function syncPropInput(v) {
  document.getElementById('propValue').value = v;
  updatePropDisplay(parseFloat(v));
}

function updatePropDisplay(v) {
  document.getElementById('propDisplay').textContent = v ? formatINR(v) : '—';
  const sl = document.getElementById('propSlider');
  const pct = Math.min(100, ((v - sl.min) / (sl.max - sl.min)) * 100);
  sl.style.background = `linear-gradient(to right,#B8975A 0%,#B8975A ${pct}%,#EDE5D8 ${pct}%,#EDE5D8 100%)`;
}

function onStateChange() {
  const key = document.getElementById('stateSelect').value;
  const state = stateData[key];
  const tip = document.getElementById('genderTip');
  if (state && state.genderVaries) tip.classList.add('show');
  else tip.classList.remove('show');
  calculate();
}

function onTxnChange() {
  const txn = document.getElementById('txnType').value;
  const leaseSection = document.getElementById('leaseSection');
  if (txn === 'lease') {
    leaseSection.classList.add('visible');
  } else {
    leaseSection.classList.remove('visible');
  }
  calculate();
}

function buildRentSchedule(monthlyBase, leasePeriod, escalationPct, freqVal) {
  const freqMap = { annual:1, biennial:2, triennial:3, none:9999 };
  const freqYears = freqMap[freqVal] || 1;
  let annualBase = monthlyBase * 12;
  let cumulative = 0;
  let totalRent = 0;
  const rows = [];

  for (let y = 1; y <= leasePeriod; y++) {
    const escalated = (y > 1 && (y - 1) % freqYears === 0 && freqVal !== 'none');
    if (escalated) annualBase = annualBase * (1 + escalationPct / 100);
    cumulative += annualBase;
    totalRent += annualBase;
    rows.push({
      year: y,
      annual: Math.round(annualBase),
      cumulative: Math.round(cumulative),
      escalated
    });
  }
  return { rows, totalRent, avgAnnualRent: totalRent / leasePeriod };
}

function calculate() {
  const key     = document.getElementById('stateSelect').value;
  const propV   = parseFloat(document.getElementById('propValue').value) || 0;
  const gender  = document.getElementById('genderSelect').value;
  const txn     = document.getElementById('txnType').value;
  const pType   = document.getElementById('propType').value;
  const advFee  = parseInt(document.getElementById('advocateSlider').value);
  const firstTime = document.getElementById('firstTimeBuyer').checked;

  // Lease-specific inputs
  const isLease    = txn === 'lease';
  const monthlyRent= parseFloat(document.getElementById('monthlyRent').value) || 0;
  const leasePeriod= parseInt(document.getElementById('leasePeriod').value) || 0;
  const escalation = parseFloat(document.getElementById('escalation').value) || 0;
  const escalFreq  = document.getElementById('escalationFreq').value;
  const securityAmt= parseFloat(document.getElementById('securityAmount').value) || 0;

  if (!key || !txn) {
    document.getElementById('resultsSection').classList.add('result-hidden');
    return;
  }

  // For lease: require monthly rent & lease period
  if (isLease && (!monthlyRent || !leasePeriod)) {
    document.getElementById('resultsSection').classList.add('result-hidden');
    return;
  }

  // For non-lease: require property value
  if (!isLease && !propV) {
    document.getElementById('resultsSection').classList.add('result-hidden');
    return;
  }

  const state = stateData[key];
  let stampDuty, regCharge, rate, rateLabel;

  if (isLease) {
    // ── LEASE CALCULATION ──
    const schedule = buildRentSchedule(monthlyRent, leasePeriod, escalation, escalFreq);
    const leaseRate = leaseStampRates[key] ?? 1;
    // Stamp duty on lease = leaseRate% × avgAnnualRent × leasePeriod
    stampDuty = (leaseRate / 100) * schedule.avgAnnualRent * leasePeriod;
    // Registration on lease = 1% of first year annual rent (capped per state norms)
    regCharge = Math.min(schedule.rows[0].annual * 0.01, 100000);
    rate = leaseRate;
    rateLabel = leaseRate + '% of (Avg Annual Rent × Lease Period)';

    // Update lease result card
    const leaseCard = document.getElementById('leaseResultsCard');
    leaseCard.classList.remove('result-hidden');

    document.getElementById('lm-annual').textContent = formatINR(schedule.rows[0].annual);
    document.getElementById('lm-avg').textContent    = formatINR(schedule.avgAnnualRent);
    document.getElementById('lm-total').textContent  = formatINR(schedule.totalRent);
    document.getElementById('lm-security').textContent = securityAmt > 0 ? formatINR(securityAmt) : '—';

    // Render schedule table
    const tbody = document.getElementById('scheduleBody');
    tbody.innerHTML = schedule.rows.map(r => `
      <tr>
        <td>Year ${r.year}</td>
        <td>₹${r.annual.toLocaleString('en-IN')}</td>
        <td style="color:${r.escalated ? '#B8975A' : 'var(--muted)'}; font-size:12px;">
          ${r.escalated ? '📈 +' + escalation + '%' : (r.year === 1 ? 'Base rent' : 'No change')}
        </td>
        <td>₹${r.cumulative.toLocaleString('en-IN')}</td>
      </tr>
    `).join('');

    // Use avgAnnualRent × period as "property value" equivalent for display
    document.getElementById('r-propValue').textContent = formatINR(schedule.avgAnnualRent) + '/yr';

  } else {
    // ── SALE / GIFT / MORTGAGE CALCULATION ──
    document.getElementById('leaseResultsCard').classList.add('result-hidden');

    let baseRate;
    if (state.slab) {
      baseRate = key === 'karnataka' ? getKarnatakaRate(propV) : getWestBengalRate(propV);
    } else {
      baseRate = state[gender] ?? state.male;
    }

    const txnMultiplier = { sale:1, gift:0.5, lease:0.3, mortgage:0.1 }[txn] ?? 1;
    stampDuty = (propV * baseRate * txnMultiplier) / 100;
    regCharge = (propV * state.reg) / 100;
    rate = baseRate;
    rateLabel = baseRate + '%' + (txnMultiplier < 1 ? ' (×' + txnMultiplier + ' for ' + txnLabels[txn] + ')' : '');
    document.getElementById('r-propValue').textContent = formatINR(propV);
  }

  const hasSecurityDisplay = isLease && securityAmt > 0;
  const grand = stampDuty + regCharge + advFee;

  // Show / hide security rows
  document.getElementById('r-securityRow').style.display  = hasSecurityDisplay ? 'flex' : 'none';
  document.getElementById('pie-securityRow').style.display = hasSecurityDisplay ? 'flex' : 'none';

  // Update grand total sub-label
  document.getElementById('grandTotalSub').textContent = hasSecurityDisplay
    ? 'Stamp + Reg + Advocate (Security shown separately)'
    : 'Stamp + Reg + Advocate';

  // Show results section
  const sec = document.getElementById('resultsSection');
  sec.classList.remove('result-hidden');
  sec.classList.add('animate-in');
  setTimeout(() => sec.classList.remove('animate-in'), 350);

  // Rate info text
  document.getElementById('rateInfoText').innerHTML = state.note +
    (!isLease && txn !== 'sale' ? ` <br><span style="color:rgba(255,255,255,0.5)">Transaction modifier applied for ${txnLabels[txn]}.</span>` : '') +
    (isLease ? ` <br><span style="color:rgba(255,255,255,0.5)">Lease stamp duty rate: ${rate}% applied on average annual rent × lease period.</span>` : '');

  document.getElementById('r-txnType').textContent   = txnLabels[txn] || txn;
  document.getElementById('r-propType').textContent  = propLabels[pType] || pType;
  document.getElementById('r-rate').textContent      = rateLabel;
  document.getElementById('r-stampDuty').textContent = formatINR(stampDuty);
  document.getElementById('r-reg').textContent       = formatINR(regCharge) + ' (' + state.reg + (isLease ? '% of Year 1 rent' : '%') + ')';
  document.getElementById('r-advocate').textContent  = '₹' + advFee.toLocaleString('en-IN');
  document.getElementById('r-grand').textContent     = formatINR(grand);

  if (hasSecurityDisplay) {
    document.getElementById('r-security').textContent  = formatINR(securityAmt);
    document.getElementById('pie-security').textContent = formatINR(securityAmt);
  }

  // PIE chart
  const total4pie = stampDuty + regCharge + advFee + (hasSecurityDisplay ? securityAmt : 0);
  const sPct = Math.round((stampDuty / total4pie) * 100);
  const rPct = Math.round((regCharge / total4pie) * 100);
  const secPct = hasSecurityDisplay ? Math.round((securityAmt / total4pie) * 100) : 0;
  const aPct = 100 - sPct - rPct - secPct;

  document.getElementById('pie-stamp').textContent = formatINR(stampDuty) + ' (' + sPct + '%)';
  document.getElementById('pie-reg').textContent   = formatINR(regCharge) + ' (' + rPct + '%)';
  document.getElementById('pie-adv').textContent   = '₹' + advFee.toLocaleString('en-IN') + ' (' + aPct + '%)';

  drawPie(sPct, rPct, secPct, aPct, hasSecurityDisplay);

  // Female savings box
  const savBox = document.getElementById('savingsBox');
  if (!isLease && state.genderVaries && gender === 'female' && state.male !== state.female) {
    const txnMultiplier = { sale:1, gift:0.5, mortgage:0.1 }[txn] ?? 1;
    const maleDuty = (propV * state.male * txnMultiplier) / 100;
    const saved = maleDuty - stampDuty;
    document.getElementById('savingsText').textContent =
      `By registering as a female buyer in ${state.name}, you save approximately ${formatINR(saved)} on stamp duty compared to a male buyer.`;
    savBox.style.display = 'block';
  } else {
    savBox.style.display = 'none';
  }

  document.getElementById('firstTimBox').style.display = firstTime ? 'block' : 'none';
}

function drawPie(p1, p2, p3, p4, hasSecurity) {
  const svg = document.getElementById('pieChart');
  const cx = 70, cy = 70, r = 56;
  const colors = ['#0b2c3d', '#B8975A', '#16a34a', '#5C4A32'];
  const pcts = hasSecurity ? [p1, p2, p3, p4] : [p1, p2, p4];
  const cols  = hasSecurity ? colors : [colors[0], colors[1], colors[3]];
  let paths = '';
  let startAngle = -90;
  pcts.forEach((pct, i) => {
    if (pct <= 0) return;
    const sweep = (pct / 100) * 360;
    const endAngle = startAngle + (sweep >= 360 ? 359.99 : sweep);
    const s = startAngle * Math.PI / 180;
    const e = endAngle * Math.PI / 180;
    const x1 = cx + r * Math.cos(s), y1 = cy + r * Math.sin(s);
    const x2 = cx + r * Math.cos(e), y2 = cy + r * Math.sin(e);
    const lg = sweep > 180 ? 1 : 0;
    paths += `<path d="M${cx},${cy} L${x1.toFixed(1)},${y1.toFixed(1)} A${r},${r},0,${lg},1,${x2.toFixed(1)},${y2.toFixed(1)} Z" fill="${cols[i]}"/>`;
    startAngle = endAngle;
  });
  svg.innerHTML = paths + `<circle cx="${cx}" cy="${cy}" r="28" fill="#FDFAF6"/>`;
}

// Init
updateAdvocate();
updatePropDisplay(5000000);
</script>
</body>
</html>
  --gold-light: #D4B47A;
  --navy:       #0b2c3d;
  --brown:      #5C4A32;
  --text:       #3A3530;
  --muted:      #8A7E72;
  --border:     #DDD4C4;
  --radius:     4px;
  --radius-lg:  8px;
}

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--cream);
  color: var(--text);
  min-height: 100vh;
}

/* HEADER */
.header { background: var(--navy); padding: 0 32px; border-bottom: 1px solid rgba(184,151,90,0.3); }
.header-inner {
  max-width: 1200px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between; height: 68px;
}
.logo { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: var(--warm-white); text-decoration: none; letter-spacing: 0.04em; }
.logo span { color: var(--gold); }
.header-nav { display: flex; gap: 28px; }
.header-nav a { font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.5); text-decoration: none; letter-spacing: 0.06em; text-transform: uppercase; transition: color 0.2s; }
.header-nav a:hover { color: var(--gold-light); }
.header-cta { background: transparent; color: var(--gold); border: 1px solid var(--gold); padding: 9px 22px; border-radius: var(--radius); font-size: 12px; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s; }
.header-cta:hover { background: var(--gold); color: var(--navy); }

/* HERO */
.hero { background: var(--navy); padding: 48px 32px 72px; text-align: center; position: relative; overflow: hidden; }
.hero::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23B8975A' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.hero-eyebrow { font-size: 11px; font-weight: 500; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold); margin-bottom: 14px; position: relative; display: inline-flex; align-items: center; gap: 10px; }
.hero-eyebrow::before, .hero-eyebrow::after { content: ''; width: 40px; height: 1px; background: var(--gold); opacity: 0.5; }
.hero h1 { font-family: 'Cormorant Garamond', serif; font-size: 42px; font-weight: 400; color: var(--warm-white); letter-spacing: 0.02em; line-height: 1.15; position: relative; margin-bottom: 12px; }
.hero h1 em { font-style: italic; color: var(--gold-light); }
.hero-sub { font-size: 14px; color: rgba(255,255,255,0.45); max-width: 500px; margin: 0 auto; line-height: 1.7; position: relative; font-weight: 300; }

/* MAIN WRAPPER */
.main {
  max-width: 1200px;
  margin: -36px auto 48px;
  padding: 0 20px;
  position: relative;
  z-index: 2;
}

/* INPUT CARD — full width */
.card {
  background: var(--warm-white);
  border-radius: var(--radius-lg);
  border: 1px solid var(--border);
  box-shadow: 0 8px 40px rgba(11,44,61,0.12);
  overflow: hidden;
  margin-bottom: 24px;
}
.card-header {
  background: var(--navy);
  padding: 18px 28px;
  display: flex; align-items: center; gap: 12px;
  border-bottom: 2px solid var(--gold);
}
.card-header-icon { width: 36px; height: 36px; border-radius: 50%; background: rgba(184,151,90,0.15); border: 1px solid rgba(184,151,90,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.card-header h2 { font-family: 'Cormorant Garamond', serif; font-size: 18px; font-weight: 600; color: var(--warm-white); letter-spacing: 0.03em; }
.card-header p { font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 1px; font-weight: 300; }
.card-body { padding: 28px 32px; }

/* FORM GRID — mirrors screenshot layout */
.form-row {
  display: grid;
  gap: 20px;
  margin-bottom: 20px;
}
.form-row.cols-2 { grid-template-columns: 1fr 1fr; }
.form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-row.cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

@media (max-width: 900px) {
  .form-row.cols-2, .form-row.cols-3, .form-row.cols-4 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .form-row.cols-2, .form-row.cols-3, .form-row.cols-4 { grid-template-columns: 1fr; }
  .hero h1 { font-size: 28px; }
  .header-nav { display: none; }
}

.form-group { display: flex; flex-direction: column; }
.form-label {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; font-weight: 600; color: var(--navy);
  letter-spacing: 0.04em; margin-bottom: 8px;
}
.form-label .lbl-icon { font-size: 13px; }
.form-label .required { color: #c0392b; font-size: 13px; }

.form-select, .form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  font-size: 14px;
  font-family: 'DM Sans', sans-serif;
  color: var(--text);
  background: #fff;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  -webkit-appearance: none; appearance: none;
  height: 48px;
}
.form-select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238A7E72' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 14px center;
  background-color: #fff; padding-right: 40px; cursor: pointer;
}
.form-select:focus, .form-input:focus {
  border-color: var(--navy);
  box-shadow: 0 0 0 3px rgba(11,44,61,0.08);
}
.form-input::placeholder { color: #b0a898; }

/* Prefix input */
.input-wrap { position: relative; }
.input-prefix { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 600; color: var(--muted); pointer-events: none; }
.input-wrap .form-input { padding-left: 28px; }

/* CHECKBOX */
.checkbox-row { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.checkbox-row input[type="checkbox"] {
  width: 18px; height: 18px; accent-color: var(--navy);
  border: 1.5px solid var(--border); border-radius: 3px; cursor: pointer; flex-shrink: 0;
}
.checkbox-row label { font-size: 14px; color: var(--text); cursor: pointer; font-weight: 400; }

/* GENDER TIP */
.gender-tip {
  font-size: 11px; color: var(--gold);
  background: rgba(184,151,90,0.08); border: 1px solid rgba(184,151,90,0.2);
  border-radius: var(--radius); padding: 7px 12px; font-weight: 500;
  display: none; margin-top: 6px;
}
.gender-tip.show { display: block; }

/* ADVOCATE RANGE */
.advocate-range-wrap { margin-top: 6px; }
.advocate-range-wrap input[type="range"] {
  width: 100%; -webkit-appearance: none; height: 3px; border-radius: 100px; outline: none; cursor: pointer;
}
.advocate-range-wrap input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none; width: 20px; height: 20px; border-radius: 50%;
  background: #fff; border: 2px solid var(--gold);
  box-shadow: 0 2px 6px rgba(184,151,90,0.4); cursor: grab;
}
.advocate-range-wrap input[type="range"]::-moz-range-thumb {
  width: 20px; height: 20px; border-radius: 50%;
  background: #fff; border: 2px solid var(--gold);
  box-shadow: 0 2px 6px rgba(184,151,90,0.4); cursor: grab;
}
.advocate-labels { display: flex; justify-content: space-between; font-size: 10px; color: var(--muted); margin-top: 4px; }
.advocate-val { font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 600; color: var(--navy); margin-bottom: 4px; }

/* DIVIDER */
.divider { height: 1px; background: var(--beige); margin: 4px 0 24px; }

/* CALCULATE BTN */
.btn-row { display: flex; justify-content: center; }
.btn-calculate {
  background: var(--navy); color: var(--gold-light);
  border: 1px solid var(--gold); padding: 15px 56px;
  border-radius: 6px; font-size: 14px; font-weight: 600;
  font-family: 'DM Sans', sans-serif; cursor: pointer;
  letter-spacing: 0.1em; text-transform: uppercase;
  transition: all 0.2s; display: flex; align-items: center; gap: 10px;
}
.btn-calculate:hover { background: var(--gold); color: var(--navy); }
.btn-calculate:active { transform: scale(0.99); }

/* RESULTS CARD — 2 col grid */
.results-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 48px;
}
@media (max-width: 700px) { .results-grid { grid-template-columns: 1fr; } }

.result-hidden { display: none; }

/* Result breakdown card */
.breakdown-card {
  background: var(--warm-white); border-radius: var(--radius-lg);
  border: 1px solid var(--border); box-shadow: 0 4px 20px rgba(11,44,61,0.08);
  overflow: hidden;
}
.breakdown-header {
  background: var(--navy); padding: 14px 22px;
  border-bottom: 2px solid var(--gold);
  display: flex; align-items: center; gap: 10px;
}
.breakdown-header h3 { font-family: 'Cormorant Garamond', serif; font-size: 16px; font-weight: 600; color: var(--warm-white); }
.breakdown-body { padding: 20px 22px; }

.rate-banner {
  background: var(--navy); border-radius: var(--radius);
  padding: 12px 16px; margin-bottom: 18px;
  display: flex; align-items: flex-start; gap: 8px;
}
.rate-banner-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); margin-top: 5px; flex-shrink: 0; }
.rate-banner p { font-size: 12px; color: rgba(255,255,255,0.65); line-height: 1.6; font-weight: 300; }
.rate-banner strong { color: var(--gold-light); font-weight: 600; }

.result-row { display: flex; justify-content: space-between; align-items: center; padding: 11px 0; border-bottom: 1px solid var(--beige); }
.result-row:last-of-type { border-bottom: none; }
.result-label { font-size: 12px; color: var(--muted); font-weight: 500; }
.result-value { font-size: 14px; font-weight: 600; color: var(--text); }
.result-value.gold { color: var(--gold); }
.result-value.navy { color: var(--navy); }

.total-box {
  background: var(--navy); border-radius: var(--radius-lg);
  padding: 18px 20px; margin-top: 16px;
  display: flex; justify-content: space-between; align-items: center;
  border: 1px solid rgba(184,151,90,0.25); position: relative; overflow: hidden;
}
.total-box::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: var(--gold); }
.total-label { font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.12em; }
.total-sub { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 3px; font-weight: 300; }
.total-value { font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 600; color: var(--gold-light); }

/* PIE */
.pie-section { display: flex; align-items: center; gap: 16px; margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--beige); }
.pie-legend { flex: 1; }
.pie-legend-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; font-size: 11px; }
.pie-legend-left { display: flex; align-items: center; gap: 7px; color: var(--muted); }
.pie-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pie-dot.navy { background: var(--navy); }
.pie-dot.gold { background: var(--gold); }
.pie-dot.brown { background: var(--brown); }
.pie-dot.beige { background: var(--beige-mid); }
.pie-legend-val { font-weight: 600; color: var(--text); font-size: 11px; }

.disclaimer-box {
  background: #FEFBF3; border: 1px solid var(--beige-mid); border-left: 3px solid var(--gold);
  border-radius: var(--radius); padding: 10px 14px; margin-top: 16px;
  font-size: 11px; color: var(--muted); line-height: 1.6; font-weight: 300;
}

/* STATE TABLE */
.table-section { max-width: 1200px; margin: 0 auto 48px; padding: 0 20px; }
.section-heading { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
.section-heading h3 { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: var(--navy); letter-spacing: 0.02em; white-space: nowrap; }
.section-heading-line { flex: 1; height: 1px; background: var(--border); }
.table-card { background: var(--warm-white); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; box-shadow: 0 4px 20px rgba(11,44,61,0.07); }
.state-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.state-table th { background: var(--navy); color: rgba(255,255,255,0.55); padding: 13px 18px; text-align: left; font-weight: 500; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; }
.state-table th:first-child { color: var(--gold-light); }
.state-table td { padding: 11px 18px; border-bottom: 1px solid var(--beige); vertical-align: middle; line-height: 1.5; }
.state-table tr:last-child td { border-bottom: none; }
.state-table tr:nth-child(even) td { background: #FDFAF6; }
.state-table tr:hover td { background: #FBF5E9; transition: background 0.12s; }
.state-name { font-weight: 600; color: var(--navy); }
.rate-pill { display: inline-block; background: var(--navy); color: var(--gold-light); padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; white-space: nowrap; }
.reg-pill { display: inline-block; background: var(--beige); color: var(--brown); padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 500; white-space: nowrap; }
.note-text { font-size: 11px; color: var(--muted); font-weight: 300; }
.gender-badge { display: inline-flex; align-items: center; gap: 3px; font-size: 10px; color: var(--gold); background: rgba(184,151,90,0.1); border: 1px solid rgba(184,151,90,0.25); padding: 2px 7px; border-radius: 100px; font-weight: 600; margin-left: 4px; white-space: nowrap; }

.footer-note { max-width: 1200px; margin: 0 auto 36px; padding: 20px 20px 0; font-size: 11px; color: var(--muted); line-height: 1.8; border-top: 1px solid var(--border); font-weight: 300; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.animate-in { animation: fadeUp 0.3s ease; }
</style>
</head>
<body>

<!-- HEADER -->
<header class="header">
  <div class="header-inner">
    <a href="#" class="logo">Zendo<span>India</span> </a>
    <nav class="header-nav">
      <a href="#">Projects</a>
      <a href="#">About</a>
      <a href="#">Blog</a>
      <a href="#">Contact</a>
    </nav>
    <button class="header-cta">Talk to Expert</button>
  </div>
</header>

<!-- HERO -->
<section class="hero">
  <div class="hero-eyebrow">Property Tool</div>
  <h1>Stamp Duty <em>Calculator</em></h1>
  <p class="hero-sub">Estimate stamp duty, registration charges & advocate fees across all Indian states. Rates vary by state and buyer gender.</p>
</section>

<!-- MAIN WRAPPER -->
<div class="main">

  <!-- INPUT CARD -->
  <div class="card">
    <div class="card-header">
      <div class="card-header-icon">🏛</div>
      <div>
        <h2>Property Details</h2>
        <p>Fill in details below to calculate all applicable charges</p>
      </div>
    </div>
    <div class="card-body">

      <!-- ROW 1: State + Transaction Type -->
      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">📍</span> State <span class="required">*</span></label>
          <select class="form-select" id="stateSelect" onchange="onStateChange()">
            <option value="">Select State</option>
            <option value="andhra">Andhra Pradesh</option>
            <option value="assam">Assam</option>
            <option value="bihar">Bihar</option>
            <option value="chhattisgarh">Chhattisgarh</option>
            <option value="delhi">Delhi</option>
            <option value="gujarat">Gujarat</option>
            <option value="haryana">Haryana</option>
            <option value="jharkhand">Jharkhand</option>
            <option value="karnataka">Karnataka</option>
            <option value="kerala">Kerala</option>
            <option value="madhyapradesh">Madhya Pradesh</option>
            <option value="maharashtra">Maharashtra</option>
            <option value="odisha">Odisha</option>
            <option value="punjab">Punjab</option>
            <option value="rajasthan">Rajasthan</option>
            <option value="tamilnadu">Tamil Nadu</option>
            <option value="telangana">Telangana</option>
            <option value="uttarpradesh">Uttar Pradesh</option>
            <option value="uttarakhand">Uttarakhand</option>
            <option value="westbengal">West Bengal</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">📋</span> Transaction Type <span class="required">*</span></label>
          <select class="form-select" id="txnType" onchange="calculate()">
            <option value="">Select Transaction Type</option>
            <option value="sale" selected>Sale / Purchase</option>
            <option value="gift">Gift Deed</option>
            <option value="lease">Lease Agreement</option>
            <option value="mortgage">Mortgage / Loan</option>
          </select>
        </div>
      </div>

      <!-- ROW 2: Property Type + Property Value + Buyer Gender -->
      <div class="form-row cols-3">
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">🏢</span> Property Type</label>
          <select class="form-select" id="propType" onchange="calculate()">
            <option value="warehouse" selected>Warehouse</option>
            <option value="residential">Residential</option>
            <option value="commercial">Commercial</option>
            <option value="industrial">Industrial</option>
            <option value="agricultural">Agricultural Land</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">₹</span> Property Value (Rs.) <span class="required">*</span></label>
          <div class="input-wrap">
            <span class="input-prefix">₹</span>
            <input type="number" class="form-input" id="propValue" placeholder="Enter property value"
              min="100000" step="100000"
              oninput="updateSlider(); calculate()">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">👤</span> Buyer Gender</label>
          <select class="form-select" id="genderSelect" onchange="calculate()">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="joint">Joint (Male + Female)</option>
          </select>
          <div class="gender-tip" id="genderTip">⚡ Female buyers get a lower stamp duty rate in this state</div>
        </div>
      </div>

      <!-- ROW 3: Advocate Fee -->
      <div class="form-row cols-2">
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">⚖️</span> Advocate / Legal Fee</label>
          <div class="advocate-val" id="advocateDisplay">₹25,000</div>
          <div class="advocate-range-wrap">
            <input type="range" id="advocateSlider" min="10000" max="50000" step="1000" value="25000"
              oninput="updateAdvocate(); calculate()">
            <div class="advocate-labels"><span>₹10,000</span><span>₹50,000</span></div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label"><span class="lbl-icon">📊</span> Property Value Slider</label>
          <div class="advocate-val" id="propDisplay">—</div>
          <div class="advocate-range-wrap">
            <input type="range" id="propSlider" min="100000" max="100000000" step="100000" value="5000000"
              oninput="syncPropInput(this.value); calculate()">
            <div class="advocate-labels"><span>₹1 Lakh</span><span>₹10 Cr</span></div>
          </div>
        </div>
      </div>

      <!-- CHECKBOX -->
      <div class="checkbox-row">
        <input type="checkbox" id="firstTimeBuyer" onchange="calculate()">
        <label for="firstTimeBuyer">First-time property buyer <span style="font-size:11px;color:var(--muted);">(some states offer rebate)</span></label>
      </div>

      <div class="divider"></div>
      <div class="btn-row">
        <button class="btn-calculate" onclick="calculate()">
          <span>⊞</span> Calculate Stamp Duty
        </button>
      </div>
    </div>
  </div>

  <!-- RESULTS -->
  <div id="resultsSection" class="result-hidden">
    <div class="results-grid">

      <!-- Charges Breakdown -->
      <div class="breakdown-card">
        <div class="breakdown-header">
          <div class="card-header-icon" style="width:30px;height:30px;font-size:14px;">📊</div>
          <h3>Charges Breakdown</h3>
        </div>
        <div class="breakdown-body">
          <div class="rate-banner">
            <div class="rate-banner-dot"></div>
            <p id="rateInfoText">—</p>
          </div>
          <div class="result-row">
            <span class="result-label">Property Value</span>
            <span class="result-value navy" id="r-propValue">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Transaction Type</span>
            <span class="result-value" id="r-txnType">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Property Type</span>
            <span class="result-value" id="r-propType">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Stamp Duty Rate</span>
            <span class="result-value gold" id="r-rate">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Stamp Duty Amount</span>
            <span class="result-value" id="r-stampDuty">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Registration Charges</span>
            <span class="result-value" id="r-reg">—</span>
          </div>
          <div class="result-row">
            <span class="result-label">Advocate / Legal Fee</span>
            <span class="result-value" id="r-advocate">—</span>
          </div>
          <div class="total-box">
            <div>
              <div class="total-label">Grand Total</div>
              <div class="total-sub">Stamp + Reg + Advocate</div>
            </div>
            <div class="total-value" id="r-grand">—</div>
          </div>
        </div>
      </div>

      <!-- Visual Summary -->
      <div class="breakdown-card">
        <div class="breakdown-header">
          <div class="card-header-icon" style="width:30px;height:30px;font-size:14px;">🥧</div>
          <h3>Cost Summary</h3>
        </div>
        <div class="breakdown-body">
          <div style="text-align:center; padding: 12px 0 4px;">
            <svg id="pieChart" width="140" height="140" viewBox="0 0 140 140"></svg>
          </div>
          <div class="pie-section" style="padding-top:16px; margin-top:0; border-top: 1px solid var(--beige);">
            <div class="pie-legend" style="width:100%;">
              <div class="pie-legend-row">
                <div class="pie-legend-left"><span class="pie-dot navy"></span> Stamp Duty</div>
                <span class="pie-legend-val" id="pie-stamp">—</span>
              </div>
              <div class="pie-legend-row">
                <div class="pie-legend-left"><span class="pie-dot gold"></span> Registration</div>
                <span class="pie-legend-val" id="pie-reg">—</span>
              </div>
              <div class="pie-legend-row">
                <div class="pie-legend-left"><span class="pie-dot brown"></span> Advocate Fee</div>
                <span class="pie-legend-val" id="pie-adv">—</span>
              </div>
            </div>
          </div>

          <!-- Savings box for female -->
          <div id="savingsBox" style="display:none; background:#f0fdf4; border:1px solid #86efac; border-left:3px solid #16a34a; border-radius:4px; padding:12px 14px; margin-top:16px;">
            <div style="font-size:11px; font-weight:600; color:#15803d; margin-bottom:4px;">💚 Female Buyer Savings</div>
            <div style="font-size:12px; color:#166534; font-weight:300; line-height:1.55;" id="savingsText">—</div>
          </div>

          <!-- First time buyer note -->
          <div id="firstTimBox" style="display:none; background:#eff6ff; border:1px solid #93c5fd; border-left:3px solid #3b82f6; border-radius:4px; padding:12px 14px; margin-top:12px;">
            <div style="font-size:11px; font-weight:600; color:#1d4ed8; margin-bottom:4px;">🏠 First-time Buyer Note</div>
            <div style="font-size:12px; color:#1e40af; font-weight:300; line-height:1.55;">Some states offer stamp duty rebates for first-time buyers under PMAY or state-specific housing schemes. Check with your local sub-registrar office for applicable exemptions.</div>
          </div>

          <div class="disclaimer-box" style="margin-top:16px;">
            Estimated values based on 2025–26 rates. Verify with a legal advisor before registration.
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- STATE TABLE -->
  <div class="table-section" style="padding:0; margin-bottom:48px;">
    <div class="section-heading">
      <h3>State-wise Stamp Duty Rates — 2025–26</h3>
      <div class="section-heading-line"></div>
    </div>
    <div class="table-card">
      <table class="state-table">
        <thead>
          <tr>
            <th>State</th>
            <th>Stamp Duty</th>
            <th>Registration</th>
            <th>Male / Female / Joint</th>
          </tr>
        </thead>
        <tbody>
          <tr><td class="state-name">Andhra Pradesh</td><td><span class="rate-pill">5%</span></td><td><span class="reg-pill">0.5%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Assam</td><td><span class="rate-pill">7.75–8.25%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 8.25% <span class="gender-badge">♀ 7.75%</span></td></tr>
          <tr><td class="state-name">Bihar</td><td><span class="rate-pill">6%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Chhattisgarh</td><td><span class="rate-pill">6–7%</span></td><td><span class="reg-pill">4%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 6%</span> Joint: 6.5%</td></tr>
          <tr><td class="state-name">Delhi</td><td><span class="rate-pill">4–6%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 6% <span class="gender-badge">♀ 4%</span> Joint: 5%</td></tr>
          <tr><td class="state-name">Gujarat</td><td><span class="rate-pill">3.9–4.9%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 4.9% <span class="gender-badge">♀ 3.9%</span></td></tr>
          <tr><td class="state-name">Haryana</td><td><span class="rate-pill">5–7%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 5%</span> Joint: 6%</td></tr>
          <tr><td class="state-name">Jharkhand</td><td><span class="rate-pill">4%</span></td><td><span class="reg-pill">3%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Karnataka</td><td><span class="rate-pill">2–5%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Slab: &lt;₹20L→2%, ₹21–35L→3%, &gt;₹35L→5%</td></tr>
          <tr><td class="state-name">Kerala</td><td><span class="rate-pill">8%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Madhya Pradesh</td><td><span class="rate-pill">7.5%</span></td><td><span class="reg-pill">3%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Maharashtra</td><td><span class="rate-pill">5–6%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 6% <span class="gender-badge">♀ 5%</span> Joint: 5.5%</td></tr>
          <tr><td class="state-name">Odisha</td><td><span class="rate-pill">4–5%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Male: 5% <span class="gender-badge">♀ 4%</span> Joint: 4.5%</td></tr>
          <tr><td class="state-name">Punjab</td><td><span class="rate-pill">5–7%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 5%</span> Joint: 6%</td></tr>
          <tr><td class="state-name">Rajasthan</td><td><span class="rate-pill">5–6%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 6% <span class="gender-badge">♀ 5%</span> Joint: 5.5%</td></tr>
          <tr><td class="state-name">Tamil Nadu</td><td><span class="rate-pill">7%</span></td><td><span class="reg-pill">4%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Telangana</td><td><span class="rate-pill">5%</span></td><td><span class="reg-pill">0.5%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">Uttar Pradesh</td><td><span class="rate-pill">6–7%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Male: 7% <span class="gender-badge">♀ 6%</span> Joint: 6.5%</td></tr>
          <tr><td class="state-name">Uttarakhand</td><td><span class="rate-pill">5%</span></td><td><span class="reg-pill">2%</span></td><td class="note-text">Uniform — no gender difference</td></tr>
          <tr><td class="state-name">West Bengal</td><td><span class="rate-pill">7–8%</span></td><td><span class="reg-pill">1%</span></td><td class="note-text">Slab: &lt;₹40L→7%, &gt;₹40L→8%</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- /main -->

<div class="footer-note">
  * Rates are indicative for 2025–26 and subject to change per state government notifications. Gender-based concessions apply in states marked above. Advocate fees are estimates (₹10,000–₹50,000) and vary by property complexity and lawyer. Zendo advises all buyers to verify applicable charges with a certified consultant or legal advisor before completing registration.
</div>

<script>
const stateData = {
  andhra:        { name:'Andhra Pradesh',   male:5,    female:5,    joint:5,    reg:0.5, genderVaries:false,
                   note:'<strong>Andhra Pradesh:</strong> Uniform 5% for all buyers. Registration: 0.5%.' },
  assam:         { name:'Assam',            male:8.25, female:7.75, joint:8,    reg:1,   genderVaries:true,
                   note:'<strong>Assam:</strong> Male 8.25% · <strong style="color:var(--gold-light)">Female 7.75%</strong>. Registration: 1%.' },
  bihar:         { name:'Bihar',            male:6,    female:6,    joint:6,    reg:2,   genderVaries:false,
                   note:'<strong>Bihar:</strong> Uniform 6% for all buyers. Registration: 2%.' },
  chhattisgarh:  { name:'Chhattisgarh',     male:7,    female:6,    joint:6.5,  reg:4,   genderVaries:true,
                   note:'<strong>Chhattisgarh:</strong> Male 7% · <strong style="color:var(--gold-light)">Female 6%</strong> · Joint 6.5%. Registration: 4%.' },
  delhi:         { name:'Delhi',            male:6,    female:4,    joint:5,    reg:1,   genderVaries:true,
                   note:'<strong>Delhi:</strong> Male 6% · <strong style="color:var(--gold-light)">Female 4%</strong> · Joint 5%. NDMC areas may vary. Registration: 1%.' },
  gujarat:       { name:'Gujarat',          male:4.9,  female:3.9,  joint:4.4,  reg:1,   genderVaries:true,
                   note:'<strong>Gujarat:</strong> Male 4.9% · <strong style="color:var(--gold-light)">Female 3.9%</strong> · Joint 4.4%. Registration: 1%.' },
  haryana:       { name:'Haryana',          male:7,    female:5,    joint:6,    reg:1,   genderVaries:true,
                   note:'<strong>Haryana (Urban):</strong> Male 7% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 6%. Rural rates lower. Registration: 1%.' },
  jharkhand:     { name:'Jharkhand',        male:4,    female:4,    joint:4,    reg:3,   genderVaries:false,
                   note:'<strong>Jharkhand:</strong> Uniform 4% for all buyers. Registration: 3%.' },
  karnataka:     { name:'Karnataka',        male:null, female:null, joint:null, reg:1,   genderVaries:false, slab:true,
                   note:'<strong>Karnataka (Slab-based):</strong> Below ₹20L → 2% · ₹21–35L → 3% · Above ₹35L → 5%. Registration: 1%.' },
  kerala:        { name:'Kerala',           male:8,    female:8,    joint:8,    reg:2,   genderVaries:false,
                   note:'<strong>Kerala:</strong> Uniform 8% for all buyers. Registration: 2%.' },
  madhyapradesh: { name:'Madhya Pradesh',   male:7.5,  female:7.5,  joint:7.5,  reg:3,   genderVaries:false,
                   note:'<strong>Madhya Pradesh:</strong> Uniform 7.5% for all buyers. Registration: 3%.' },
  maharashtra:   { name:'Maharashtra',      male:6,    female:5,    joint:5.5,  reg:1,   genderVaries:true,
                   note:'<strong>Maharashtra:</strong> Male 6% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 5.5%. Mumbai metro cess may apply. Registration: 1%.' },
  odisha:        { name:'Odisha',           male:5,    female:4,    joint:4.5,  reg:2,   genderVaries:true,
                   note:'<strong>Odisha:</strong> Male 5% · <strong style="color:var(--gold-light)">Female 4%</strong> · Joint 4.5%. Registration: 2%.' },
  punjab:        { name:'Punjab',           male:7,    female:5,    joint:6,    reg:1,   genderVaries:true,
                   note:'<strong>Punjab:</strong> Male 7% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 6%. Registration: 1%.' },
  rajasthan:     { name:'Rajasthan',        male:6,    female:5,    joint:5.5,  reg:1,   genderVaries:true,
                   note:'<strong>Rajasthan:</strong> Male 6% · <strong style="color:var(--gold-light)">Female 5%</strong> · Joint 5.5%. Registration: 1%.' },
  tamilnadu:     { name:'Tamil Nadu',       male:7,    female:7,    joint:7,    reg:4,   genderVaries:false,
                   note:'<strong>Tamil Nadu:</strong> Uniform 7% for all buyers. Registration: 4%.' },
  telangana:     { name:'Telangana',        male:5,    female:5,    joint:5,    reg:0.5, genderVaries:false,
                   note:'<strong>Telangana:</strong> Uniform 5% for all buyers. Registration: 0.5%.' },
  uttarpradesh:  { name:'Uttar Pradesh',    male:7,    female:6,    joint:6.5,  reg:1,   genderVaries:true,
                   note:'<strong>Uttar Pradesh:</strong> Male 7% · <strong style="color:var(--gold-light)">Female 6%</strong> · Joint 6.5%. Gift deeds to blood relatives: ₹5,000 flat. Registration: 1%.' },
  uttarakhand:   { name:'Uttarakhand',      male:5,    female:5,    joint:5,    reg:2,   genderVaries:false,
                   note:'<strong>Uttarakhand:</strong> Uniform 5% for all buyers. Registration: 2%.' },
  westbengal:    { name:'West Bengal',      male:null, female:null, joint:null, reg:1,   genderVaries:false, slab:true,
                   note:'<strong>West Bengal:</strong> Up to ₹40L → 7% · Above ₹40L → 8%. Registration: 1%.' }
};

const txnLabels = { sale:'Sale / Purchase', gift:'Gift Deed', lease:'Lease Agreement', mortgage:'Mortgage / Loan' };
const propLabels = { warehouse:'Warehouse', residential:'Residential', commercial:'Commercial', industrial:'Industrial', agricultural:'Agricultural Land' };

function formatINR(n) {
  if (n >= 10000000) return '₹' + (n/10000000).toFixed(2) + ' Cr';
  if (n >= 100000)   return '₹' + (n/100000).toFixed(2) + ' Lakh';
  return '₹' + n.toLocaleString('en-IN');
}

function getKarnatakaRate(v) { return v<=2000000?2:v<=3500000?3:5; }
function getWestBengalRate(v) { return v<=4000000?7:8; }

function updateAdvocate() {
  const v = parseInt(document.getElementById('advocateSlider').value);
  document.getElementById('advocateDisplay').textContent = '₹' + v.toLocaleString('en-IN');
  // update slider gradient
  const sl = document.getElementById('advocateSlider');
  const pct = ((v - 10000) / 40000) * 100;
  sl.style.background = `linear-gradient(to right,#B8975A 0%,#B8975A ${pct}%,#EDE5D8 ${pct}%,#EDE5D8 100%)`;
}

function updateSlider() {
  const v = parseFloat(document.getElementById('propValue').value) || 0;
  document.getElementById('propSlider').value = Math.min(v, 100000000);
  updatePropDisplay(v);
}

function syncPropInput(v) {
  document.getElementById('propValue').value = v;
  updatePropDisplay(parseFloat(v));
}

function updatePropDisplay(v) {
  document.getElementById('propDisplay').textContent = v ? formatINR(v) : '—';
  const sl = document.getElementById('propSlider');
  const pct = Math.min(100, ((v - sl.min) / (sl.max - sl.min)) * 100);
  sl.style.background = `linear-gradient(to right,#B8975A 0%,#B8975A ${pct}%,#EDE5D8 ${pct}%,#EDE5D8 100%)`;
}

function onStateChange() {
  const key = document.getElementById('stateSelect').value;
  const state = stateData[key];
  const tip = document.getElementById('genderTip');
  if (state && state.genderVaries) {
    tip.classList.add('show');
  } else {
    tip.classList.remove('show');
  }
  calculate();
}

function calculate() {
  const key    = document.getElementById('stateSelect').value;
  const propV  = parseFloat(document.getElementById('propValue').value) || 0;
  const gender = document.getElementById('genderSelect').value;
  const txn    = document.getElementById('txnType').value;
  const pType  = document.getElementById('propType').value;
  const advFee = parseInt(document.getElementById('advocateSlider').value);
  const firstTime = document.getElementById('firstTimeBuyer').checked;

  if (!key || !propV || !txn) {
    document.getElementById('resultsSection').classList.add('result-hidden');
    return;
  }

  const state = stateData[key];
  let rate;
  if (state.slab) {
    rate = key === 'karnataka' ? getKarnatakaRate(propV) : getWestBengalRate(propV);
  } else {
    rate = state[gender] ?? state.male;
  }

  // Transaction type modifier
  let txnMultiplier = 1;
  if (txn === 'gift') txnMultiplier = 0.5;
  else if (txn === 'lease') txnMultiplier = 0.3;
  else if (txn === 'mortgage') txnMultiplier = 0.1;

  const stampDuty = (propV * rate * txnMultiplier) / 100;
  const regCharge = (propV * state.reg) / 100;
  const grand = stampDuty + regCharge + advFee;

  const total3 = stampDuty + regCharge + advFee;
  const sPct = Math.round((stampDuty / total3) * 100);
  const rPct = Math.round((regCharge / total3) * 100);
  const aPct = 100 - sPct - rPct;

  // Show results
  const sec = document.getElementById('resultsSection');
  sec.classList.remove('result-hidden');
  sec.classList.add('animate-in');
  setTimeout(() => sec.classList.remove('animate-in'), 350);

  document.getElementById('rateInfoText').innerHTML = state.note +
    (txn !== 'sale' ? ` <br><span style="color:rgba(255,255,255,0.5)">Transaction modifier applied for ${txnLabels[txn]}.</span>` : '');
  document.getElementById('r-propValue').textContent  = formatINR(propV);
  document.getElementById('r-txnType').textContent    = txnLabels[txn] || txn;
  document.getElementById('r-propType').textContent   = propLabels[pType] || pType;
  document.getElementById('r-rate').textContent       = rate + '% ' + (txnMultiplier < 1 ? '(×' + txnMultiplier + ' for ' + txnLabels[txn] + ')' : '');
  document.getElementById('r-stampDuty').textContent  = formatINR(stampDuty);
  document.getElementById('r-reg').textContent        = formatINR(regCharge) + ' (' + state.reg + '%)';
  document.getElementById('r-advocate').textContent   = '₹' + advFee.toLocaleString('en-IN');
  document.getElementById('r-grand').textContent      = formatINR(grand);

  document.getElementById('pie-stamp').textContent = formatINR(stampDuty) + ' (' + sPct + '%)';
  document.getElementById('pie-reg').textContent   = formatINR(regCharge) + ' (' + rPct + '%)';
  document.getElementById('pie-adv').textContent   = '₹' + advFee.toLocaleString('en-IN') + ' (' + aPct + '%)';

  drawPie(sPct, rPct, aPct);

  // Savings box for female
  const savBox = document.getElementById('savingsBox');
  if (state.genderVaries && gender === 'female' && state.male !== state.female) {
    const maleDuty = (propV * state.male * txnMultiplier) / 100;
    const saved = maleDuty - stampDuty;
    document.getElementById('savingsText').textContent =
      `By registering as a female buyer in ${state.name}, you save approximately ${formatINR(saved)} on stamp duty compared to a male buyer.`;
    savBox.style.display = 'block';
  } else {
    savBox.style.display = 'none';
  }

  // First time buyer
  document.getElementById('firstTimBox').style.display = firstTime ? 'block' : 'none';
}

function drawPie(p1, p2, p3) {
  const svg = document.getElementById('pieChart');
  const cx = 70, cy = 70, r = 56;
  const colors = ['#0b2c3d', '#B8975A', '#5C4A32'];
  const pcts = [p1, p2, p3];
  let paths = '';
  let startAngle = -90;
  pcts.forEach((pct, i) => {
    const sweep = (pct / 100) * 360;
    if (sweep <= 0) return;
    const endAngle = startAngle + (sweep >= 360 ? 359.99 : sweep);
    const s = startAngle * Math.PI / 180;
    const e = endAngle * Math.PI / 180;
    const x1 = cx + r * Math.cos(s), y1 = cy + r * Math.sin(s);
    const x2 = cx + r * Math.cos(e), y2 = cy + r * Math.sin(e);
    const lg = sweep > 180 ? 1 : 0;
    paths += `<path d="M${cx},${cy} L${x1.toFixed(1)},${y1.toFixed(1)} A${r},${r},0,${lg},1,${x2.toFixed(1)},${y2.toFixed(1)} Z" fill="${colors[i]}"/>`;
    startAngle = endAngle;
  });
  svg.innerHTML = paths + `<circle cx="${cx}" cy="${cy}" r="28" fill="#FDFAF6"/>`;
}

// Init sliders
updateAdvocate();
updatePropDisplay(5000000);
</script>
</body>
</html>