
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stamp Duty Calculator</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --navy: #1a2a4a;
    --blue: #2563eb;
    --blue-light: #eff6ff;
    --green: #16a34a;
    --green-light: #f0fdf4;
    --green-border: #bbf7d0;
    --orange: #c2410c;
    --gold: #b45309;
    --gold-bg: #fffbeb;
    --gold-border: #fde68a;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --white: #ffffff;
    --radius: 10px;
    --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: #eef2f7;
    min-height: 100vh;
    color: var(--gray-800);
  }

  /* HEADER */
  .header {
    background: var(--navy);
    padding: 22px 40px;
    display: flex;
    align-items: center;
    gap: 18px;
  }
  .header-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.12);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
  }
  .header-text h1 {
    font-size: 2rem; font-weight: 700; color: #fff; letter-spacing: 0.02em;
  }
  .header-text p {
    color: rgba(255,255,255,0.7); font-size: 0.95rem; margin-top: 2px;
  }

  /* LAYOUT */
  .wrapper {
    max-width: 1100px;
    margin: 32px auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 24px;
    align-items: start;
  }

  /* CARD */
  .card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow);
  }

  /* LEFT PANEL - INPUTS */
  .inputs-card { padding: 28px 24px 24px; }
  .section-title {
    font-size: 0.78rem; font-weight: 700; letter-spacing: 0.1em;
    color: var(--blue); text-transform: uppercase; margin-bottom: 20px;
  }

  .field-group { margin-bottom: 18px; }
  .field-label {
    display: block; font-size: 0.88rem; font-weight: 600;
    color: var(--gray-700); margin-bottom: 8px;
  }

  /* Radio Group */
  .radio-group { display: flex; flex-direction: column; gap: 8px; }
  .radio-option {
    display: flex; align-items: center; gap: 10px;
    cursor: pointer; font-size: 0.9rem; color: var(--gray-700);
  }
  .radio-option input[type="radio"] { display: none; }
  .radio-custom {
    width: 18px; height: 18px; border-radius: 50%;
    border: 2px solid var(--gray-300);
    display: flex; align-items: center; justify-content: center;
    transition: border-color 0.2s;
    flex-shrink: 0;
  }
  .radio-option input:checked + .radio-custom {
    border-color: var(--blue);
  }
  .radio-option input:checked + .radio-custom::after {
    content: ''; display: block;
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--blue);
  }

  /* OR Divider */
  .or-divider {
    text-align: center; position: relative; margin: 12px 0;
    color: var(--gray-400); font-size: 0.8rem; font-weight: 600;
  }
  .or-divider::before, .or-divider::after {
    content: ''; position: absolute; top: 50%;
    width: 44%; height: 1px; background: var(--gray-200);
  }
  .or-divider::before { left: 0; }
  .or-divider::after { right: 0; }

  /* Input Fields */
  .input-wrap {
    position: relative; display: flex; align-items: center;
    border: 1.5px solid var(--gray-200); border-radius: 8px;
    background: var(--white); transition: border-color 0.2s;
    overflow: hidden;
  }
  .input-wrap:focus-within { border-color: var(--blue); }
  .input-prefix, .input-suffix {
    padding: 0 10px; color: var(--gray-400);
    font-size: 0.9rem; font-weight: 500;
    background: var(--gray-50); height: 44px;
    display: flex; align-items: center;
    border-right: 1px solid var(--gray-200);
    flex-shrink: 0;
  }
  .input-suffix { border-right: none; border-left: 1px solid var(--gray-200); }
  .input-wrap input, .input-wrap select {
    flex: 1; border: none; outline: none; height: 44px;
    padding: 0 12px; font-size: 0.9rem; color: var(--gray-700);
    font-family: 'DM Sans', sans-serif; background: transparent;
    width: 100%;
  }
  .input-wrap select { cursor: pointer; }
  .input-icon {
    padding: 0 10px; color: var(--gray-400); font-size: 0.95rem;
    display: flex; align-items: center;
  }

  .disabled-field .input-wrap { background: var(--gray-50); opacity: 0.6; pointer-events: none; }

  /* Calculate Button */
  .btn-calculate {
    width: 100%; padding: 14px;
    background: var(--blue); color: #fff;
    border: none; border-radius: 8px;
    font-size: 0.95rem; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-top: 24px; transition: background 0.2s, transform 0.1s;
    font-family: 'DM Sans', sans-serif;
  }
  .btn-calculate:hover { background: #1d4ed8; }
  .btn-calculate:active { transform: scale(0.99); }

  /* RIGHT PANEL */
  .results-col { display: flex; flex-direction: column; gap: 20px; }

  /* KEY RESULTS */
  .key-results-card {
    border: 1.5px solid var(--green-border);
    background: var(--green-light);
    padding: 22px 24px;
    border-radius: var(--radius);
  }
  .key-results-card .section-title { color: var(--green); margin-bottom: 18px; }

  .metrics-row {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 0;
    border: 1px solid var(--gray-200); border-radius: 8px;
    overflow: hidden; background: #fff; margin-bottom: 16px;
  }
  .metric-cell {
    padding: 16px 12px; text-align: center;
    border-right: 1px solid var(--gray-200);
  }
  .metric-cell:last-child { border-right: none; }
  .metric-label { font-size: 0.8rem; color: var(--gray-600); margin-bottom: 6px; }
  .metric-value { font-size: 1.4rem; font-weight: 700; color: var(--green); line-height: 1.2; }
  .metric-sub { font-size: 0.75rem; color: var(--gray-400); margin-top: 2px; }

  .rate-row {
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: 8px; padding: 14px 16px;
    display: flex; justify-content: space-between; align-items: center;
  }
  .rate-label { font-size: 0.88rem; color: var(--gray-600); }
  .rate-value { font-size: 0.95rem; font-weight: 700; color: var(--blue); }

  /* RENT SCHEDULE */
  .schedule-card { padding: 22px 24px; }
  .schedule-card .section-title { color: var(--navy); margin-bottom: 14px; }

  .schedule-table { width: 100%; border-collapse: collapse; }
  .schedule-table thead tr { background: var(--blue-light); }
  .schedule-table th {
    padding: 10px 14px; text-align: left;
    font-size: 0.8rem; font-weight: 600; color: var(--gray-600);
    letter-spacing: 0.02em;
  }
  .schedule-table td {
    padding: 11px 14px; font-size: 0.88rem; color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
  }
  .schedule-table tbody tr:last-child td { border-bottom: none; }
  .schedule-table tbody tr:hover { background: var(--gray-50); }
  .schedule-table td:first-child { font-weight: 600; color: var(--gray-800); }

  /* SUMMARY */
  .summary-card {
    background: var(--gold-bg); border: 1.5px solid var(--gold-border);
    border-radius: var(--radius); padding: 22px 24px;
  }
  .summary-card .section-title { color: var(--gold); margin-bottom: 14px; }
  .summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 9px 0; border-bottom: 1px solid var(--gold-border);
    font-size: 0.88rem;
  }
  .summary-row:last-child { border-bottom: none; }
  .summary-key { color: var(--gray-600); }
  .summary-val { font-weight: 600; color: var(--gray-800); }
  .summary-total {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 14px; padding-top: 12px;
    border-top: 2px solid var(--gold-border);
  }
  .summary-total-label { font-weight: 700; color: var(--orange); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.04em; }
  .summary-total-val { font-size: 1.3rem; font-weight: 800; color: var(--orange); }

  /* NOTE */
  .note-card {
    background: var(--blue-light); border: 1px solid #bfdbfe;
    border-radius: var(--radius); padding: 14px 18px;
    display: flex; gap: 12px; align-items: flex-start;
  }
  .note-icon { color: var(--blue); font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
  .note-text { font-size: 0.82rem; color: var(--gray-600); line-height: 1.6; }
  .note-text strong { color: var(--gray-700); display: block; margin-bottom: 2px; }

  /* Hidden state */
  .results-col.empty .key-results-card .metric-value,
  .results-col.empty .rate-value,
  .results-col.empty .summary-total-val { color: var(--gray-300); }

  @media (max-width: 768px) {
    .wrapper { grid-template-columns: 1fr; }
    .header { padding: 18px 20px; }
    .header-text h1 { font-size: 1.4rem; }
    .metrics-row { grid-template-columns: 1fr; }
    .metric-cell { border-right: none; border-bottom: 1px solid var(--gray-200); }
    .metric-cell:last-child { border-bottom: none; }
  }
</style>
</head>
<body>






<!-- HEADER -->
<div class="header">
  <div class="header-icon">📄</div>
  <div class="header-text">
    <h1>STAMP DUTY CALCULATOR</h1>
    <p>Calculate stamp duty for your rental agreement</p>
  </div>
</div>

<!-- MAIN -->
<div class="wrapper">

  <!-- LEFT: INPUTS -->
  <div class="card inputs-card">
    <div class="section-title">Inputs</div>

    <!-- Rent Input Method -->
    <div class="field-group">
      <label class="field-label">Rent Input Method</label>
      <div class="radio-group">
        <label class="radio-option">
          <input type="radio" name="rentMethod" value="monthly" checked onchange="toggleInputMethod(this)">
          <span class="radio-custom"></span>
          Monthly Rent
        </label>
        <label class="radio-option">
          <input type="radio" name="rentMethod" value="area" onchange="toggleInputMethod(this)">
          <span class="radio-custom"></span>
          Area &amp; Rent per Sq Ft
        </label>
      </div>
    </div>

    <!-- Monthly Rent -->
    <div class="field-group" id="monthlyGroup">
      <label class="field-label">Monthly Rent (₹)</label>
      <div class="input-wrap">
        <span class="input-prefix">₹</span>
        <input type="number" id="monthlyRent" placeholder="Enter monthly rent" min="0">
      </div>
    </div>

    <div class="or-divider">OR</div>

    <!-- Area -->
    <div class="field-group disabled-field" id="areaGroup">
      <label class="field-label">Area (Sq Ft)</label>
      <div class="input-wrap">
        <input type="number" id="area" placeholder="Enter area" min="0">
        <span class="input-suffix">Sq Ft</span>
      </div>
    </div>

    <!-- Rent per Sq Ft -->
    <div class="field-group disabled-field" id="rentSqftGroup">
      <label class="field-label">Rent per Sq Ft (₹)</label>
      <div class="input-wrap">
        <span class="input-prefix">₹</span>
        <input type="number" id="rentSqft" placeholder="Enter rent per sq ft" min="0">
      </div>
    </div>

    <!-- Lease Period -->
    <div class="field-group">
      <label class="field-label">Lease Period</label>
      <div class="input-wrap">
        <span class="input-icon">📅</span>
        <input type="number" id="leasePeriod" placeholder="Enter number of years" min="1" max="99">
        <span class="input-suffix">Years</span>
      </div>
    </div>

    <!-- Rent Escalation -->
    <div class="field-group">
      <label class="field-label">Rent Escalation (%)</label>
      <div class="input-wrap">
        <span class="input-icon">📈</span>
        <input type="number" id="escalation" placeholder="Enter escalation %" min="0" max="100" step="0.1">
        <span class="input-suffix">%</span>
      </div>
    </div>

    <!-- Escalation Frequency -->
    <div class="field-group">
      <label class="field-label">Escalation Frequency</label>
      <div class="input-wrap">
        <span class="input-icon">📅</span>
        <select id="escalationFreq">
          <option value="annual">Annual</option>
          <option value="biennial">Biennial (Every 2 Years)</option>
          <option value="triennial">Triennial (Every 3 Years)</option>
          <option value="none">No Escalation</option>
        </select>
      </div>
    </div>

    <!-- Location: State -->
    <div class="field-group">
      <label class="field-label">Location</label>
      <label class="field-label" style="font-weight:500;color:var(--gray-600);margin-bottom:6px;">State</label>
      <div class="input-wrap">
        <select id="stateSelect" onchange="populateDistricts()">
          <option value="">Select State</option>
          <option value="MH">Maharashtra</option>
          <option value="DL">Delhi</option>
          <option value="KA">Karnataka</option>
          <option value="TN">Tamil Nadu</option>
          <option value="UP">Uttar Pradesh</option>
          <option value="RJ">Rajasthan</option>
          <option value="GJ">Gujarat</option>
          <option value="WB">West Bengal</option>
          <option value="AP">Andhra Pradesh</option>
          <option value="TG">Telangana</option>
          <option value="HR">Haryana</option>
          <option value="PB">Punjab</option>
          <option value="MP">Madhya Pradesh</option>
          <option value="BR">Bihar</option>
          <option value="OR">Odisha</option>
          <option value="KL">Kerala</option>
          <option value="AS">Assam</option>
          <option value="JH">Jharkhand</option>
          <option value="CG">Chhattisgarh</option>
          <option value="UK">Uttarakhand</option>
        </select>
      </div>
    </div>

    <!-- District -->
    <div class="field-group">
      <label class="field-label" style="font-weight:500;color:var(--gray-600);margin-bottom:6px;">District</label>
      <div class="input-wrap">
        <select id="districtSelect">
          <option value="">Select District</option>
        </select>
      </div>
    </div>

    <button class="btn-calculate" onclick="calculate()">
      🖩 CALCULATE
    </button>
  </div>

  <!-- RIGHT: RESULTS -->
  <div class="results-col" id="resultsCol">

    <!-- Key Results -->
    <div class="key-results-card card">
      <div class="section-title">Key Results</div>
      <div class="metrics-row">
        <div class="metric-cell">
          <div class="metric-label">Annual Rent</div>
          <div class="metric-value" id="resAnnualRent">—</div>
        </div>
        <div class="metric-cell">
          <div class="metric-label">Average Rent</div>
          <div class="metric-value" id="resAvgRent">—</div>
          <div class="metric-sub" id="resAvgSub">(Per Year)</div>
        </div>
        <div class="metric-cell">
          <div class="metric-label">Stamp Duty</div>
          <div class="metric-value" id="resStampDuty">—</div>
        </div>
      </div>
      <div class="rate-row">
        <span class="rate-label">Stamp Duty Rate Applied</span>
        <span class="rate-value" id="resRate">—</span>
      </div>
    </div>

    <!-- Rent Schedule -->
    <div class="card schedule-card">
      <div class="section-title">Rent Schedule</div>
      <table class="schedule-table">
        <thead>
          <tr>
            <th>Year</th>
            <th>Annual Rent (₹)</th>
            <th>Cumulative Rent (₹)</th>
          </tr>
        </thead>
        <tbody id="scheduleBody">
          <tr><td colspan="3" style="text-align:center;color:var(--gray-400);padding:20px;font-size:0.85rem;">Enter inputs and click Calculate</td></tr>
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="summary-card">
      <div class="section-title">Summary</div>
      <div class="summary-row">
        <span class="summary-key">Lease Period</span>
        <span class="summary-val" id="sumLease">—</span>
      </div>
      <div class="summary-row">
        <span class="summary-key">Total Rent (Lease Period)</span>
        <span class="summary-val" id="sumTotal">—</span>
      </div>
      <div class="summary-row">
        <span class="summary-key">Average Annual Rent</span>
        <span class="summary-val" id="sumAvg">—</span>
      </div>
      <div class="summary-row">
        <span class="summary-key">Applicable Stamp Duty Rate</span>
        <span class="summary-val" id="sumRate">—</span>
      </div>
      <div class="summary-total">
        <span class="summary-total-label">Stamp Duty Payable</span>
        <span class="summary-total-val" id="sumDuty">—</span>
      </div>
    </div>

    <!-- Note -->
    <div class="note-card">
      <span class="note-icon">ℹ️</span>
      <div class="note-text">
        <strong>Note:</strong>
        Stamp duty rates vary by state and lease period. Please confirm the applicable rate with local authorities.
      </div>
    </div>

  </div>
</div>

<script>
// ─── District Data ───────────────────────────────────────────────
const districts = {
  MH: ['Mumbai','Pune','Nagpur','Thane','Nashik','Aurangabad','Solapur','Kolhapur','Nanded','Amravati'],
  DL: ['Central Delhi','East Delhi','New Delhi','North Delhi','North East Delhi','North West Delhi','Shahdara','South Delhi','South East Delhi','South West Delhi','West Delhi'],
  KA: ['Bengaluru Urban','Mysuru','Hubli-Dharwad','Mangaluru','Belagavi','Kalaburagi','Davanagere','Ballari','Vijayapura','Shivamogga'],
  TN: ['Chennai','Coimbatore','Madurai','Tiruchirappalli','Salem','Tirunelveli','Tiruppur','Vellore','Erode','Thoothukudi'],
  UP: ['Lucknow','Kanpur Nagar','Ghaziabad','Agra','Meerut','Varanasi','Allahabad','Bareilly','Aligarh','Moradabad'],
  RJ: ['Jaipur','Jodhpur','Kota','Bikaner','Ajmer','Udaipur','Bhilwara','Alwar','Sikar','Sriganganagar'],
  GJ: ['Ahmedabad','Surat','Vadodara','Rajkot','Bhavnagar','Jamnagar','Junagadh','Gandhinagar','Anand','Mehsana'],
  WB: ['Kolkata','Howrah','North 24 Parganas','South 24 Parganas','Purba Medinipur','Paschim Medinipur','Murshidabad','Nadia','Burdwan','Malda'],
  AP: ['Visakhapatnam','Vijayawada','Guntur','Nellore','Kurnool','Kadapa','Kakinada','Tirupati','Anantapur','Rajahmundry'],
  TG: ['Hyderabad','Rangareddy','Medchal-Malkajgiri','Sangareddy','Warangal Urban','Karimnagar','Nizamabad','Khammam','Nalgonda','Mahbubnagar'],
  HR: ['Gurugram','Faridabad','Ambala','Hisar','Rohtak','Karnal','Sonipat','Panipat','Panchkula','Yamunanagar'],
  PB: ['Ludhiana','Amritsar','Jalandhar','Patiala','Bathinda','Mohali','Hoshiarpur','Gurdaspur','Ferozepur','Moga'],
  MP: ['Bhopal','Indore','Jabalpur','Gwalior','Ujjain','Sagar','Ratlam','Satna','Rewa','Dewas'],
  BR: ['Patna','Gaya','Muzaffarpur','Bhagalpur','Darbhanga','Purnia','Ara','Begusarai','Katihar','Munger'],
  OR: ['Bhubaneswar','Cuttack','Rourkela','Berhampur','Sambalpur','Puri','Balasore','Bhadrak','Baripada','Jharsuguda'],
  KL: ['Thiruvananthapuram','Kochi','Kozhikode','Thrissur','Kollam','Malappuram','Kannur','Alappuzha','Palakkad','Kottayam'],
  AS: ['Guwahati','Dibrugarh','Silchar','Jorhat','Nagaon','Tinsukia','Tezpur','Bongaigaon','Dhubri','Karimganj'],
  JH: ['Ranchi','Jamshedpur','Dhanbad','Bokaro','Deoghar','Hazaribag','Giridih','Ramgarh','Chaibasa','Dumka'],
  CG: ['Raipur','Bhilai','Bilaspur','Durg','Korba','Rajnandgaon','Jagdalpur','Ambikapur','Raigarh','Dhamtari'],
  UK: ['Dehradun','Haridwar','Nainital','Udham Singh Nagar','Pauri Garhwal','Almora','Tehri Garhwal','Chamoli','Pithoragarh','Uttarkashi'],
};

// ─── Stamp Duty Rate Logic ────────────────────────────────────────
// Rates as % of average annual rent × lease period (avg rent method)
function getStampDutyRate(state, years) {
  const rates = {
    MH: years <= 1 ? 0.25 : years <= 3 ? 0.5 : years <= 10 ? 1 : 2,
    DL: years <= 5 ? 2 : years <= 10 ? 3 : 5,
    KA: years <= 1 ? 0.5 : years <= 10 ? 1 : 2,
    TN: years <= 1 ? 1 : years <= 3 ? 2 : 4,
    UP: years <= 1 ? 2 : years <= 5 ? 3 : 4,
    RJ: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    GJ: years <= 5 ? 1.5 : years <= 10 ? 2 : 3,
    WB: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    AP: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    TG: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    HR: years <= 5 ? 2 : years <= 10 ? 3 : 4,
    PB: years <= 5 ? 1 : years <= 10 ? 2 : 3,
    MP: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    BR: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    OR: years <= 5 ? 1 : years <= 10 ? 2 : 3,
    KL: years <= 1 ? 0.5 : years <= 5 ? 1 : 2,
    AS: years <= 5 ? 1 : 2,
    JH: years <= 5 ? 1 : 2,
    CG: years <= 5 ? 1 : 2,
    UK: years <= 5 ? 1 : 2,
  };
  return (rates[state] ?? 3);
}

// ─── Helpers ──────────────────────────────────────────────────────
function fmt(n) {
  return '₹' + Math.round(n).toLocaleString('en-IN');
}

function toggleInputMethod(radio) {
  const isMonthly = radio.value === 'monthly';
  document.getElementById('monthlyGroup').classList.toggle('disabled-field', !isMonthly);
  document.getElementById('areaGroup').classList.toggle('disabled-field', isMonthly);
  document.getElementById('rentSqftGroup').classList.toggle('disabled-field', isMonthly);
  const inputs = document.querySelectorAll('#monthlyGroup input, #areaGroup input, #rentSqftGroup input');
  inputs.forEach(i => i.disabled = false);
  if (isMonthly) {
    document.querySelector('#areaGroup input').disabled = true;
    document.querySelector('#rentSqftGroup input').disabled = true;
  } else {
    document.querySelector('#monthlyGroup input').disabled = true;
  }
}

function populateDistricts() {
  const state = document.getElementById('stateSelect').value;
  const sel = document.getElementById('districtSelect');
  sel.innerHTML = '<option value="">Select District</option>';
  (districts[state] || []).forEach(d => {
    const opt = document.createElement('option');
    opt.value = d; opt.textContent = d;
    sel.appendChild(opt);
  });
}

// ─── CALCULATE ────────────────────────────────────────────────────
function calculate() {
  const method = document.querySelector('input[name="rentMethod"]:checked').value;
  const leasePeriod = parseFloat(document.getElementById('leasePeriod').value);
  const escalationPct = parseFloat(document.getElementById('escalation').value) || 0;
  const freqVal = document.getElementById('escalationFreq').value;
  const state = document.getElementById('stateSelect').value;

  let monthlyBase = 0;
  if (method === 'monthly') {
    monthlyBase = parseFloat(document.getElementById('monthlyRent').value) || 0;
  } else {
    const area = parseFloat(document.getElementById('area').value) || 0;
    const rpsf = parseFloat(document.getElementById('rentSqft').value) || 0;
    monthlyBase = area * rpsf;
  }

  if (!monthlyBase || !leasePeriod) {
    alert('Please enter rent and lease period.');
    return;
  }

  const freqMap = { annual: 1, biennial: 2, triennial: 3, none: 9999 };
  const freqYears = freqMap[freqVal] || 1;

  // Build rent schedule
  let annualBase = monthlyBase * 12;
  let cumulative = 0;
  let totalRent = 0;
  const rows = [];

  for (let y = 1; y <= leasePeriod; y++) {
    // Apply escalation at frequency intervals (except year 1)
    if (y > 1 && (y - 1) % freqYears === 0) {
      annualBase = annualBase * (1 + escalationPct / 100);
    }
    cumulative += annualBase;
    totalRent += annualBase;
    rows.push({ year: y, annual: Math.round(annualBase), cumulative: Math.round(cumulative) });
  }

  const avgAnnualRent = totalRent / leasePeriod;
  const rate = state ? getStampDutyRate(state, leasePeriod) : 3;
  // Stamp duty = rate% × average annual rent × lease period
  const stampDuty = (rate / 100) * avgAnnualRent * leasePeriod;
  const baseAnnual = monthlyBase * 12;

  // Update Key Results
  document.getElementById('resAnnualRent').textContent = fmt(baseAnnual);
  document.getElementById('resAvgRent').textContent = fmt(avgAnnualRent);
  document.getElementById('resStampDuty').textContent = fmt(stampDuty);
  document.getElementById('resRate').textContent = rate.toFixed(2) + '%';

  // Update Schedule
  const tbody = document.getElementById('scheduleBody');
  tbody.innerHTML = rows.map(r => `
    <tr>
      <td>${r.year}</td>
      <td>${r.annual.toLocaleString('en-IN')}</td>
      <td>${r.cumulative.toLocaleString('en-IN')}</td>
    </tr>
  `).join('');

  // Update Summary
  document.getElementById('sumLease').textContent = leasePeriod + ' Year' + (leasePeriod > 1 ? 's' : '');
  document.getElementById('sumTotal').textContent = fmt(totalRent);
  document.getElementById('sumAvg').textContent = fmt(avgAnnualRent);
  document.getElementById('sumRate').textContent = rate.toFixed(2) + '%';
  document.getElementById('sumDuty').textContent = fmt(stampDuty);
}

// Init
toggleInputMethod(document.querySelector('input[name="rentMethod"]:checked'));
</script>
</body>
</html>