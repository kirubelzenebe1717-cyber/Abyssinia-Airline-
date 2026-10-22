<?php
/**
 * Abyssinia Airlines - Flagship Web Portal
 * Built with PHP, CSS, HTML5, and JavaScript.
 */
require_once __DIR__ . '/db.php';
$pdo = Database::getConnection();

// Fetch initial popular destinations for quick cards
$destinations = [
    [
        'city' => 'Lalibela & Addis Ababa',
        'country' => 'Ethiopia',
        'code' => 'LLI',
        'price' => 95,
        'image' => 'assets/images/lalibela.jpg',
        'badge' => 'Cradle of Civilization',
        'desc' => 'Marvel at ancient monolithic churches carved from red volcanic rock.'
    ],
    [
        'city' => 'Dubai',
        'country' => 'United Arab Emirates',
        'code' => 'DXB',
        'price' => 420,
        'image' => 'assets/images/dubai.jpg',
        'badge' => 'Oasis of Luxury',
        'desc' => 'Futuristic architecture, world-class desert safaris, and pristine beaches.'
    ],
    [
        'city' => 'London Heathrow',
        'country' => 'United Kingdom',
        'code' => 'LHR',
        'price' => 680,
        'image' => 'assets/images/hero-aircraft.jpg',
        'badge' => 'Global Metropolis',
        'desc' => 'Historic royalty, West End theatre, and legendary museums.'
    ],
    [
        'city' => 'New York City',
        'country' => 'United States',
        'code' => 'JFK',
        'price' => 890,
        'image' => 'assets/images/business-class.jpg',
        'badge' => 'The City That Never Sleeps',
        'desc' => 'Iconic skylines, Broadway shows, and Michelin-starred dining.'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Abyssinia Airline | Fly Beyond Borders — Ethiopia's Flagship Carrier</title>
  <meta name="description" content="Welcome to Abyssinia Airline. Proudly based in Addis Ababa, Ethiopia at Bole International Airport. Experience world-class Ethiopian hospitality, Cloud Nine business suites, seamless online flight booking, check-in, live flight tracking, and Sheba Miles.">
  <meta name="keywords" content="Abyssinia Airline, Ethiopia Airlines, Fly Beyond Borders, Addis Ababa Bole Airport, Ethiopian Flights, Cloud Nine Business Class, Book Flight, Sheba Miles">

  <!-- Open Graph -->
  <meta property="og:title" content="Abyssinia Airline | Fly Beyond Borders">
  <meta property="og:description" content="Ethiopia's premier flagship carrier. Fly beyond borders with world-class Ethiopian hospitality, Cloud Nine suites, and global routes from Addis Ababa.">
  <meta property="og:type" content="website">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Custom Design System Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- ==========================================
       MAIN NAVIGATION BAR
       ========================================== -->
  <header class="main-nav" id="main-nav">
    <div class="ethiopian-tricolor-bar"></div>
    <div class="container nav-container">
      <!-- Brand Logo with User's Uploaded Official Emblem -->
      <a href="#hero" class="brand-logo" id="brand-logo" aria-label="Abyssinia Airline Homepage">
        <img src="assets/images/abyssinia-logo.png" alt="Abyssinia Airline — Fly Beyond Borders" class="brand-logo-img">
        <div class="brand-text">
          <span class="brand-title">ABYSSINIA <span>AIRLINE</span></span>
          <span class="brand-subtitle">FLY BEYOND BORDERS</span>
        </div>
        <span class="ethiopia-badge" style="margin-left: 8px;">
          <span class="ethiopia-flag-icon"></span> Based in Ethiopia
        </span>
      </a>

      <!-- Navigation Links -->
      <nav aria-label="Main Navigation">
        <ul class="nav-links">
          <li><a href="#booking-console" class="nav-link active" id="nav-book">Book</a></li>
          <li><a href="#flight-radar-section" class="nav-link" id="nav-status">Flight Status</a></li>
          <li><a href="#experience-section" class="nav-link" id="nav-experience">Cloud Nine</a></li>
          <li><a href="#destinations-section" class="nav-link" id="nav-destinations">Destinations</a></li>
          <li><a href="#loyalty-section" class="nav-link" id="nav-loyalty">Sheba Miles</a></li>
        </ul>
      </nav>

      <!-- Currency Switcher & Sheba Club CTA -->
      <div class="nav-actions">
        <div class="currency-select-wrap">
          <label for="currency-select" class="sr-only">Currency</label>
          <span>🌐</span>
          <select id="currency-select" aria-label="Select currency">
            <option value="ETB">ETB (Br) 🇪🇹</option>
            <option value="USD">USD ($)</option>
            <option value="EUR">EUR (€)</option>
            <option value="GBP">GBP (£)</option>
            <option value="AED">AED (د.إ)</option>
          </select>
        </div>

        <button type="button" class="btn-sheba" id="btn-sheba-login" onclick="document.getElementById('loyalty-section').scrollIntoView({behavior:'smooth'})">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
          Sheba Miles
        </button>

        <button type="button" class="menu-toggle" id="menu-toggle" aria-label="Toggle Navigation Menu">
          ☰
        </button>
      </div>
    </div>
  </header>

  <main>
    <!-- ==========================================
         HERO SECTION
         ========================================== -->
    <section class="hero-section" id="hero">
      <div class="hero-overlay"></div>
      <div class="container hero-content">
        <div class="hero-badge">
          <span class="ethiopia-flag-icon"></span>
          <span>Proudly Based in Addis Ababa, Ethiopia • Bole International Hub</span>
        </div>
        <h1 class="hero-title">
          Fly Beyond Borders with <br>
          <span class="highlight">Abyssinia Airline</span>
        </h1>
        <p class="hero-description">
          Ethiopia's premier flagship carrier connecting the Horn of Africa to 135+ destinations worldwide. Revel in authentic Ethiopian warmth, Cloud Nine lie-flat luxury suites, and timeless hospitality.
        </p>
      </div>
    </section>

    <!-- ==========================================
         UNIVERSAL BOOKING & ACTION CONSOLE
         ========================================== -->
    <section class="booking-console" id="booking-console">
      <div class="container">
        <div class="console-card">
          <!-- Console Tabs -->
          <div class="console-tabs" role="tablist">
            <button class="tab-btn active" data-tab="book" id="tab-btn-book" role="tab" aria-selected="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
              Book Flights
            </button>
            <button class="tab-btn" data-tab="manage" id="tab-btn-manage" role="tab" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="11" y2="16"/></svg>
              Manage Booking
            </button>
            <button class="tab-btn" data-tab="checkin" id="tab-btn-checkin" role="tab" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              Online Check-In
            </button>
            <button class="tab-btn" data-tab="status" id="tab-btn-status" role="tab" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
              Flight Status
            </button>
          </div>

          <!-- Console Panels -->
          <div class="console-body">
            <!-- TAB 1: BOOK FLIGHTS -->
            <div class="tab-panel active" id="tab-book">
              <form id="flight-search-form">
                <div class="trip-type-bar">
                  <div class="trip-options">
                    <label class="trip-radio-label">
                      <input type="radio" name="trip_type" value="round_trip" checked> Round Trip
                    </label>
                    <label class="trip-radio-label">
                      <input type="radio" name="trip_type" value="one_way"> One Way
                    </label>
                    <label class="trip-radio-label">
                      <input type="radio" name="trip_type" value="multi_city"> Multi-City
                    </label>
                  </div>
                  <select class="cabin-class-select" id="search-cabin-class" aria-label="Cabin Class">
                    <option value="Economy Class">Economy Class</option>
                    <option value="Cloud Nine Business">Cloud Nine Business</option>
                    <option value="First Class Suites">First Class Suites</option>
                  </select>
                </div>

                <div class="flight-form-grid">
                  <!-- Origin Airport -->
                  <div class="form-group">
                    <label for="search-origin" class="form-label">Departure From</label>
                    <input type="text" id="search-origin" class="form-input" placeholder="Select City or Airport" value="Addis Ababa (ADD)" data-code="ADD" autocomplete="off" required>
                    <div class="autocomplete-dropdown" id="origin-dropdown"></div>
                  </div>

                  <!-- Swap Airport Button -->
                  <button type="button" class="swap-btn" id="btn-swap-airports" aria-label="Swap origin and destination">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 16V4M7 4L3 8M7 4L11 8M17 8V20M17 20L21 16M17 20L13 16"/></svg>
                  </button>

                  <!-- Destination Airport -->
                  <div class="form-group">
                    <label for="search-dest" class="form-label">Arrival To</label>
                    <input type="text" id="search-dest" class="form-input" placeholder="Select City or Airport" value="Dubai (DXB)" data-code="DXB" autocomplete="off" required>
                    <div class="autocomplete-dropdown" id="dest-dropdown"></div>
                  </div>

                  <!-- Departure Date -->
                  <div class="form-group">
                    <label for="search-departure-date" class="form-label">Departure Date</label>
                    <input type="date" id="search-departure-date" class="form-input" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
                  </div>

                  <!-- Return Date -->
                  <div class="form-group">
                    <label for="search-return-date" class="form-label">Return Date</label>
                    <input type="date" id="search-return-date" class="form-input" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" min="<?= date('Y-m-d') ?>">
                  </div>

                  <!-- Search Flights CTA -->
                  <button type="submit" class="btn-search-flights" id="btn-search-submit">
                    Find Flights
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                  </button>
                </div>
              </form>
            </div>

            <!-- TAB 2: MANAGE BOOKING -->
            <div class="tab-panel" id="tab-manage">
              <form id="manage-booking-form">
                <div class="quick-form-grid">
                  <div class="form-group">
                    <label for="manage-pnr" class="form-label">Booking Reference (PNR)</label>
                    <input type="text" id="manage-pnr" class="form-input" placeholder="e.g. ABY-7842" required>
                  </div>
                  <div class="form-group">
                    <label for="manage-lastname" class="form-label">Passenger Last Name</label>
                    <input type="text" id="manage-lastname" class="form-input" placeholder="e.g. Bekele" required>
                  </div>
                  <button type="submit" class="btn-search-flights" id="btn-manage-submit">
                    Retrieve Booking
                  </button>
                </div>
                <div class="quick-hint">
                  💡 Need demo booking? Click to auto-fill: 
                  <strong class="sample-pnr-fill" data-target-input="manage-pnr">ABY-7842</strong> or 
                  <strong class="sample-pnr-fill" data-target-input="manage-pnr">ABY-9125</strong>
                </div>
              </form>
            </div>

            <!-- TAB 3: ONLINE CHECK-IN -->
            <div class="tab-panel" id="tab-checkin">
              <form id="checkin-form">
                <div class="quick-form-grid">
                  <div class="form-group">
                    <label for="checkin-pnr" class="form-label">Booking Reference (PNR)</label>
                    <input type="text" id="checkin-pnr" class="form-input" placeholder="e.g. ABY-7842" required>
                  </div>
                  <div class="form-group">
                    <label for="checkin-lastname" class="form-label">Passenger Last Name</label>
                    <input type="text" id="checkin-lastname" class="form-input" placeholder="e.g. Bekele or Jenkins">
                  </div>
                  <button type="submit" class="btn-search-flights" id="btn-checkin-submit">
                    Check In & Pass →
                  </button>
                </div>
                <div class="quick-hint">
                  ✨ Instant Check-In ready. Try sample PNR: 
                  <strong class="sample-pnr-fill" data-target-input="checkin-pnr">ABY-7842</strong> (Haile Selassie) or 
                  <strong class="sample-pnr-fill" data-target-input="checkin-pnr">ABY-9125</strong> (Sarah Jenkins).
                </div>
              </form>
            </div>

            <!-- TAB 4: FLIGHT STATUS -->
            <div class="tab-panel" id="tab-status">
              <form id="flight-status-form">
                <div class="quick-form-grid">
                  <div class="form-group">
                    <label for="status-flight-number" class="form-label">Flight Number</label>
                    <input type="text" id="status-flight-number" class="form-input" placeholder="e.g. AB-101, AB-204, AB-500" value="AB-101" required>
                  </div>
                  <div class="form-group">
                    <label for="status-date" class="form-label">Travel Date</label>
                    <input type="date" id="status-date" class="form-input" value="<?= date('Y-m-d') ?>">
                  </div>
                  <button type="submit" class="btn-search-flights" id="btn-status-submit">
                    Track Radar →
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==========================================
         LIVE FLIGHT SEARCH RESULTS SECTION
         ========================================== -->
    <section class="results-section" id="results-section">
      <div class="container">
        <div class="results-header">
          <div>
            <span class="section-subtitle">Real-Time Flight Availability</span>
            <h2 class="results-route-title" id="results-route-title">Addis Ababa (ADD) → Dubai (DXB)</h2>
          </div>
          <div class="results-filters">
            <button class="filter-btn active" id="filter-all">All Flights</button>
            <button class="filter-btn" id="filter-nonstop">Non-Stop Only</button>
            <button class="filter-btn" id="filter-cheapest">Lowest Fare</button>
          </div>
        </div>

        <div class="flight-cards-list" id="flight-cards-list">
          <!-- Populated dynamically via JS -->
        </div>
      </div>
    </section>

    <!-- ==========================================
         LIVE FLIGHT RADAR & TELEMETRY SECTION
         ========================================== -->
    <section class="radar-section" id="flight-radar-section">
      <div class="container">
        <div class="section-head">
          <span class="section-subtitle">Live In-Flight Telemetry</span>
          <h2 class="section-title">Radar Flight Tracker</h2>
          <p style="color: var(--text-muted); margin-top: 10px;">Monitor active Abyssinia Airline flights worldwide with real-time altitude, speed, and gate assignments.</p>
        </div>

        <div class="radar-telemetry-box">
          <div class="radar-status-bar">
            <div>
              <span style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800; color: #fff;" id="radar-flight-number">AB-101</span>
              <span style="margin-left: 10px; font-size: 0.9rem; color: var(--text-muted);" id="radar-aircraft">Airbus A350-900 (The Queen of Sheba)</span>
              <div style="font-size: 1.1rem; font-weight: 700; color: var(--gold-light); margin-top: 4px;" id="radar-route">Addis Ababa (ADD) → Dubai (DXB)</div>
            </div>
            <span class="radar-badge en-route" id="radar-status-badge">Boarding</span>
          </div>

          <!-- Radar Animated Progress Bar -->
          <div class="radar-progress-wrap">
            <div class="radar-track-line">
              <div class="radar-progress-fill" id="radar-progress-fill" style="width: 68%;"></div>
            </div>
            <div class="radar-aircraft-pin" id="radar-plane-pin" style="left: 68%;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
              </svg>
            </div>
          </div>

          <!-- Telemetry Metrics Grid -->
          <div class="radar-metrics-grid">
            <div class="metric-card">
              <div class="metric-label">Cruising Altitude</div>
              <div class="metric-value" id="radar-alt">38,000 ft</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Ground Speed</div>
              <div class="metric-value" id="radar-speed">895 km/h (Mach 0.82)</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Gate & Terminal</div>
              <div class="metric-value" id="radar-gate">Gate B14 (Terminal 2)</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Destination Weather</div>
              <div class="metric-value" id="radar-weather">28°C Clear Skies</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==========================================
         DESTINATIONS & SPECIAL FARES
         ========================================== -->
    <section class="destinations-section" id="destinations-section">
      <div class="container">
        <div class="section-head">
          <span class="section-subtitle">Discover The World</span>
          <h2 class="section-title">Popular Destinations & Exclusive Deals</h2>
          <p style="color: var(--text-muted); margin-top: 10px;">Fly seamlessly with convenient connections through Bole International Airport Addis Ababa.</p>
        </div>

        <div class="destinations-grid">
          <?php foreach ($destinations as $dest): ?>
            <div class="dest-card" onclick="window.prefillSearch('ADD', '<?= $dest['code'] ?>')">
              <img src="<?= htmlspecialchars($dest['image']) ?>" alt="<?= htmlspecialchars($dest['city']) ?>" class="dest-card-img" loading="lazy">
              <div class="dest-card-overlay">
                <span class="dest-badge"><?= htmlspecialchars($dest['badge']) ?></span>
                <h3 class="dest-name"><?= htmlspecialchars($dest['city']) ?></h3>
                <div class="dest-country"><?= htmlspecialchars($dest['country']) ?></div>
                <div class="dest-price-row">
                  <div>
                    <span style="font-size:0.75rem; color: #cbd5e1;">Fares from</span>
                    <div class="dest-price-val" data-usd="<?= $dest['price'] ?>">$<?= $dest['price'] ?></div>
                  </div>
                  <span class="btn-dest-book">Book Flight →</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ==========================================
         FLEET & CLOUD NINE EXPERIENCE SHOWCASE
         ========================================== -->
    <section class="experience-section" id="experience-section">
      <div class="container">
        <!-- Feature 1: Cloud Nine Business Class with Real Ethiopian Aircraft Cabin -->
        <div class="experience-grid">
          <div class="exp-img-wrap">
            <img src="assets/images/abyssinia-cabin.png" alt="Abyssinia Airline Cloud Nine Business Cabin — Our Heritage, Our Pride" loading="lazy">
          </div>
          <div class="exp-content">
            <span class="exp-badge">ኢትዮጵያ የኔ ኩራት • Our Heritage, Our Pride</span>
            <h2 class="exp-title">Cloud Nine Luxury Cabin & Ethiopian Heritage</h2>
            <p class="exp-desc">
              Experience the pinnacle of Ethiopian luxury. Our widebody aircraft feature handcrafted heritage bulkhead murals celebrating Ethiopia's historic castles and mountains, signature Ethiopian tricolor silk cushions, golden Abyssinia bird embroidery, and 180° lie-flat bed suites.
            </p>
            <ul class="exp-features">
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Ethiopian Heritage Bulkhead Murals
              </li>
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Ethiopian Flag Tricolor Silk Cushions
              </li>
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                180° Fully Lie-Flat Bed Suites
              </li>
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Bole VIP Lounge & Traditional Coffee
              </li>
            </ul>
          </div>
        </div>

        <!-- Feature 2: Bole Addis Ababa Hub & VIP Lounge -->
        <div class="experience-grid reverse">
          <div class="exp-img-wrap">
            <img src="assets/images/lounge-hub.jpg" alt="Addis Ababa Bole VIP Lounge & Traditional Coffee" loading="lazy">
          </div>
          <div class="exp-content">
            <span class="exp-badge">Addis Ababa Bole International Hub</span>
            <h2 class="exp-title">The Heart of African Aviation & Timeless Hospitality</h2>
            <p class="exp-desc">
              Relax in our signature Bole VIP Lounge featuring live traditional Ethiopian coffee ceremonies with fragrant frankincense, artisanal injera buffets, luxury shower suites, and panoramic views of departing A350 aircraft against the Ethiopian plateau sunset.
            </p>
            <ul class="exp-features">
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Ceremonial Clay Jebena Coffee
              </li>
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Private Snooze Cabins
              </li>
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Fast-Track Security Lane
              </li>
              <li class="exp-feature-item">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Family & Quiet Zones
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- ==========================================
         LOYALTY / SHEBA MILES CALCULATOR
         ========================================== -->
    <section class="loyalty-section" id="loyalty-section">
      <div class="container">
        <div class="loyalty-card">
          <div class="loyalty-grid">
            <div>
              <span class="exp-badge">Frequent Flyer Club</span>
              <h2 style="font-family: var(--font-display); font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 16px;">
                Abyssinia Sheba Miles
              </h2>
              <p style="color: #cbd5e1; font-size: 1.05rem; line-height: 1.7; margin-bottom: 24px;">
                Turn every journey into rewards. Earn valuable Sheba Miles towards award flights, cabin upgrades, extra baggage, and partner benefits across Star Alliance routes.
              </p>
              <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div style="background: rgba(255,255,255,0.06); padding: 12px 18px; border-radius: 8px; border: 1px solid var(--navy-border);">
                  <strong style="color: var(--gold-light);">Silver Tier</strong>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">10,000 Points • 25% Bonus Miles</div>
                </div>
                <div style="background: rgba(255,255,255,0.06); padding: 12px 18px; border-radius: 8px; border: 1px solid var(--navy-border);">
                  <strong style="color: var(--gold-light);">Gold Tier</strong>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">25,000 Points • Lounge + Extra 23kg</div>
                </div>
                <div style="background: rgba(255,255,255,0.06); padding: 12px 18px; border-radius: 8px; border: 1px solid var(--navy-border);">
                  <strong style="color: var(--gold-light);">Platinum Tier</strong>
                  <div style="font-size: 0.8rem; color: var(--text-muted);">50,000 Points • Suite Upgrade + 100%</div>
                </div>
              </div>
            </div>

            <!-- Interactive Miles Calculator -->
            <div class="calculator-box">
              <h3 style="font-family: var(--font-display); font-weight: 800; font-size: 1.2rem; color: #fff; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <span>🧮</span> Miles & Points Calculator
              </h3>
              <div class="calc-inputs-grid">
                <div>
                  <label for="calc-origin" class="form-label">From</label>
                  <select id="calc-origin" class="cabin-class-select" style="width: 100%;"></select>
                </div>
                <div>
                  <label for="calc-dest" class="form-label">To</label>
                  <select id="calc-dest" class="cabin-class-select" style="width: 100%;"></select>
                </div>
              </div>
              <div style="margin-bottom: 18px;">
                <label for="calc-class" class="form-label">Travel Class</label>
                <select id="calc-class" class="cabin-class-select" style="width: 100%;">
                  <option value="Economy Class">Economy (100% Miles)</option>
                  <option value="Cloud Nine Business">Cloud Nine Business (175% Miles)</option>
                  <option value="First Class">First Class (250% Miles)</option>
                </select>
              </div>
              <button type="button" class="btn-search-flights" id="btn-calc-miles" style="width: 100%; height: 48px; font-size: 0.95rem; margin-bottom: 20px;">
                Calculate Reward Miles
              </button>
              <div class="calc-results-box">
                <div>
                  <div class="calc-res-val" id="calc-miles-val">2,730</div>
                  <div class="calc-res-lbl">Miles Earned</div>
                </div>
                <div style="width: 1px; height: 35px; background: rgba(255,255,255,0.15);"></div>
                <div>
                  <div class="calc-res-val" id="calc-tier-val">273</div>
                  <div class="calc-res-lbl">Tier Qualifying Points</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ==========================================
       MODAL 1: FLIGHT BOOKING & SEAT PICKER
       ========================================== -->
  <div class="modal-overlay" id="booking-modal" role="dialog" aria-modal="true" aria-labelledby="booking-modal-title">
    <div class="modal-container">
      <div class="modal-header">
        <h2 class="modal-title" id="booking-modal-title">
          <span>✈️</span> Flight Reservation
        </h2>
        <button type="button" class="modal-close" id="modal-close-btn" aria-label="Close Modal">&times;</button>
      </div>

      <div class="modal-body">
        <!-- Steps Progress Bar -->
        <div class="booking-steps-bar">
          <div class="step-item active" data-step="1">
            <span class="step-num">1</span>
            <span class="step-label">Passenger</span>
          </div>
          <div class="step-item" data-step="2">
            <span class="step-num">2</span>
            <span class="step-label">Select Seat</span>
          </div>
          <div class="step-item" data-step="3">
            <span class="step-num">3</span>
            <span class="step-label">Add-ons</span>
          </div>
          <div class="step-item" data-step="4">
            <span class="step-num">4</span>
            <span class="step-label">Payment</span>
          </div>
          <div class="step-item" data-step="5">
            <span class="step-num">5</span>
            <span class="step-label">E-Ticket</span>
          </div>
        </div>

        <!-- Selected Flight Summary Banner -->
        <div style="background: rgba(255,255,255,0.06); padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--navy-border);">
          <div>
            <span style="font-family: var(--font-mono); font-weight: 800; color: var(--gold-light);" id="modal-flight-code">AB-101</span>
            <span style="color: #fff; font-weight: 700; margin-left: 10px;" id="modal-flight-route">Addis Ababa → Dubai</span>
          </div>
          <span style="background: rgba(230,180,34,0.15); color: var(--gold-light); padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700;" id="modal-cabin-class">Economy Class</span>
        </div>

        <!-- STEP 1: PASSENGER INFORMATION -->
        <div class="booking-step-content" id="step-content-1">
          <h3 style="color: #fff; margin-bottom: 16px;">Passenger Details</h3>
          <div class="passenger-form-grid">
            <div class="form-group full-width">
              <label for="pass-name" class="form-label">Full Name (as on Passport)</label>
              <input type="text" id="pass-name" class="form-input" placeholder="e.g. Haile Selassie Bekele" value="Haile Selassie Bekele" required>
            </div>
            <div class="form-group">
              <label for="pass-email" class="form-label">Email Address for E-Ticket</label>
              <input type="email" id="pass-email" class="form-input" placeholder="e.g. haile@abyssinia.com" value="haile.bekele@abyssinia.com" required>
            </div>
            <div class="form-group">
              <label for="pass-phone" class="form-label">Mobile Phone Number</label>
              <input type="tel" id="pass-phone" class="form-input" placeholder="+251 91 123 4567" value="+251 91 123 4567" required>
            </div>
            <div class="form-group">
              <label for="pass-passport" class="form-label">Passport / National ID Number</label>
              <input type="text" id="pass-passport" class="form-input" placeholder="e.g. ET8839201" value="ET8839201" required>
            </div>
            <div class="form-group">
              <label for="pass-meal" class="form-label">In-Flight Gourmet Meal</label>
              <select id="pass-meal" class="cabin-class-select" style="width: 100%; height: 38px;">
                <option value="Traditional Ethiopian (Injera & Wat)">Traditional Ethiopian (Injera & Wat with Doro Wat)</option>
                <option value="Vegetarian Feast (Ye'tsom Beyaynetu)">Vegetarian Feast (Ye'tsom Beyaynetu)</option>
                <option value="Halal Certified Special">Halal Certified Special</option>
                <option value="Kosher Certified">Kosher Certified</option>
                <option value="Gluten-Free Dietary">Gluten-Free Dietary</option>
              </select>
            </div>
          </div>
          <div style="display: flex; justify-content: flex-end;">
            <button type="button" class="btn-search-flights" id="btn-to-step-2" style="height: 50px;">
              Continue to Seat Selection →
            </button>
          </div>
        </div>

        <!-- STEP 2: INTERACTIVE AIRCRAFT SEAT MAP -->
        <div class="booking-step-content" id="step-content-2" style="display: none;">
          <h3 style="color: #fff; margin-bottom: 8px;">Select Your Seat Onboard</h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 20px;">Pick your preferred window, aisle, or Cloud Nine luxury suite.</p>

          <div class="seatmap-container">
            <div class="seatmap-legend">
              <div class="legend-item"><span class="legend-swatch available"></span> Available</div>
              <div class="legend-item"><span class="legend-swatch selected"></span> Selected</div>
              <div class="legend-item"><span class="legend-swatch occupied"></span> Occupied</div>
              <div class="legend-item"><span class="legend-swatch business"></span> Cloud Nine Suite</div>
            </div>

            <!-- Aircraft Fuselage Layout -->
            <div class="aircraft-fuselage">
              <div class="cockpit-nose">▲ Flight Deck (Airbus A350)</div>
              <div id="seatmap-fuselage-rows">
                <!-- Dynamically rendered by JS -->
              </div>
            </div>

            <div class="seat-selection-summary">
              <div>
                <span style="font-size: 0.8rem; color: var(--text-muted);">Assigned Seat:</span>
                <strong style="font-family: var(--font-mono); font-size: 1.2rem; color: var(--gold-light); margin-left: 8px;" id="summary-selected-seat">12A</strong>
              </div>
              <span style="color: var(--emerald-light); font-size: 0.85rem; font-weight: 700;">Standard Seat Included (Free)</span>
            </div>
          </div>

          <div style="display: flex; justify-content: space-between;">
            <button type="button" class="filter-btn btn-step-back" data-back-step="1">← Back</button>
            <button type="button" class="btn-search-flights" id="btn-to-step-3" style="height: 50px;">
              Confirm Seat & Add-ons →
            </button>
          </div>
        </div>

        <!-- STEP 3: BAGGAGE & ADD-ONS -->
        <div class="booking-step-content" id="step-content-3" style="display: none;">
          <h3 style="color: #fff; margin-bottom: 16px;">Customize Baggage & Comfort</h3>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div style="background: rgba(255,255,255,0.06); padding: 20px; border-radius: 12px; border: 1px solid var(--navy-border);">
              <h4 style="color: var(--gold-light); margin-bottom: 8px;">🧳 Checked Baggage Allowance</h4>
              <p style="font-size: 0.85rem; color: #cbd5e1; margin-bottom: 12px;">Standard ticket includes 2 pieces up to 23kg each.</p>
              <label for="extra-baggage-select" class="form-label">Add Extra 23kg Piece</label>
              <select id="extra-baggage-select" class="cabin-class-select" style="width: 100%;">
                <option value="0">Standard 2 Pieces (Included Free)</option>
                <option value="1">+1 Extra Piece 23kg (+$50)</option>
                <option value="2">+2 Extra Pieces (+$100)</option>
              </select>
            </div>
            <div style="background: rgba(255,255,255,0.06); padding: 20px; border-radius: 12px; border: 1px solid var(--navy-border);">
              <h4 style="color: var(--gold-light); margin-bottom: 8px;">🛡️ Travel Protection Insurance</h4>
              <p style="font-size: 0.85rem; color: #cbd5e1; margin-bottom: 12px;">Global medical coverage, flight cancellation protection & baggage loss.</p>
              <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #fff; font-size: 0.9rem;">
                <input type="checkbox" id="insurance-checkbox" checked style="width: 18px; height: 18px; accent-color: var(--gold-primary);">
                Comprehensive Travel Insurance (Complimentary on Cloud Nine)
              </label>
            </div>
          </div>

          <div style="display: flex; justify-content: space-between;">
            <button type="button" class="filter-btn btn-step-back" data-back-step="2">← Back</button>
            <button type="button" class="btn-search-flights" id="btn-to-step-4" style="height: 50px;">
              Proceed to Payment →
            </button>
          </div>
        </div>

        <!-- STEP 4: PAYMENT SIMULATION -->
        <div class="booking-step-content" id="step-content-4" style="display: none;">
          <h3 style="color: #fff; margin-bottom: 16px;">Select Payment Method</h3>
          <div class="payment-methods-grid">
            <div class="payment-method-card active" data-method="card">
              <div class="method-icon">💳</div>
              <div class="method-name">Credit / Debit</div>
            </div>
            <div class="payment-method-card" data-method="telebirr">
              <div class="method-icon">📱</div>
              <div class="method-name">Telebirr / CBE</div>
            </div>
            <div class="payment-method-card" data-method="paypal">
              <div class="method-icon">🌐</div>
              <div class="method-name">PayPal</div>
            </div>
            <div class="payment-method-card" data-method="applepay">
              <div class="method-icon">🍏</div>
              <div class="method-name">Apple Pay</div>
            </div>
          </div>

          <!-- Price Breakdown Table -->
          <div style="background: rgba(255,255,255,0.04); border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid var(--navy-border);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Base Airfare:</span>
              <strong style="color: #fff;" id="pay-base-fare">$420</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Taxes, Fees & Surcharges:</span>
              <strong style="color: #fff;" id="pay-taxes">$50</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
              <span style="color: var(--text-muted);">Extra Baggage:</span>
              <strong style="color: #fff;" id="pay-baggage">$0</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 12px; font-size: 1.25rem;">
              <span style="font-weight: 800; color: #fff;">Total Due:</span>
              <strong style="font-family: var(--font-display); font-weight: 800; color: var(--gold-light);" id="pay-grand-total">$470</strong>
            </div>
          </div>

          <div style="display: flex; justify-content: space-between;">
            <button type="button" class="filter-btn btn-step-back" data-back-step="3">← Back</button>
            <button type="button" class="btn-search-flights" id="btn-confirm-payment" style="height: 50px;">
              Pay & Confirm Reservation →
            </button>
          </div>
        </div>

        <!-- STEP 5: CONFIRMED E-TICKET -->
        <div class="booking-step-content" id="step-content-5" style="display: none; text-align: center; padding: 20px 0;">
          <div style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.2); border: 2px solid var(--emerald-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; color: var(--emerald-light);">
            ✓
          </div>
          <h2 style="font-family: var(--font-display); font-weight: 800; color: #fff; font-size: 1.8rem; margin-bottom: 8px;">
            Booking Confirmed!
          </h2>
          <p style="color: #cbd5e1; font-size: 0.95rem; margin-bottom: 24px;">Your e-ticket has been issued. Save your Booking Reference (PNR) below.</p>

          <div style="background: var(--navy-card); border: 2px dashed var(--gold-primary); border-radius: 14px; padding: 24px; max-width: 500px; margin: 0 auto 30px; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; align-items: center;">
              <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Booking Reference (PNR)</span>
              <span style="font-family: var(--font-mono); font-size: 1.6rem; font-weight: 900; color: var(--gold-light);" id="confirmed-pnr">ABY-7842</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Passenger:</span>
              <strong style="color: #fff;" id="confirmed-name">Haile Selassie Bekele</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Flight & Route:</span>
              <strong style="color: #fff;" id="confirmed-flight">AB-101 (ADD → DXB)</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Route Detail:</span>
              <strong style="color: #fff;" id="confirmed-route">Addis Ababa → Dubai</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Seat & Time:</span>
              <strong style="color: var(--gold-light);" id="confirmed-seat">12A (08:30 Departs)</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
              <span style="color: var(--text-muted);">Status:</span>
              <strong style="color: var(--emerald-light);" id="confirmed-time">Confirmed & Ticketed</strong>
            </div>
          </div>

          <div style="display: flex; justify-content: center; gap: 16px;">
            <button type="button" class="btn-sheba" id="btn-eticket-checkin">
              Check In & Get Boarding Pass Now →
            </button>
            <button type="button" class="filter-btn" onclick="window.print()">
              🖨️ Print E-Ticket
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       MODAL 2: DIGITAL BOARDING PASS
       ========================================== -->
  <div class="modal-overlay" id="boarding-pass-modal" role="dialog" aria-modal="true" aria-labelledby="bp-modal-title">
    <div class="modal-container" style="max-width: 760px; background: transparent; border: none; box-shadow: none;">
      <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <button type="button" class="modal-close" style="background: rgba(255,255,255,0.2);" aria-label="Close">&times;</button>
      </div>

      <div class="boarding-pass-wrap">
        <div class="boarding-pass-ticket">
          <!-- Pass Header with Official Logo -->
          <div class="pass-header">
            <div class="pass-brand">
              <img src="assets/images/abyssinia-logo.png" alt="Abyssinia Airline" style="height: 38px; width: auto; object-fit: contain;">
              <div>
                <div class="pass-brand-text">ABYSSINIA AIRLINE</div>
                <div style="font-size: 0.65rem; letter-spacing: 1px; color: var(--gold-light);">FLY BEYOND BORDERS • ETHIOPIA</div>
              </div>
            </div>
            <span class="pass-class-badge" id="bp-class">CLOUD NINE BUSINESS</span>
          </div>

          <!-- Pass Body -->
          <div class="pass-body">
            <!-- Route -->
            <div class="pass-route">
              <div class="pass-airport">
                <div class="pass-iata" id="bp-orig-code">ADD</div>
                <div class="pass-city" id="bp-orig-city">Addis Ababa</div>
              </div>

              <div class="pass-flight-path">
                <div style="font-family: var(--font-mono); font-weight: 800; font-size: 0.9rem; color: var(--text-dark-muted); margin-bottom: 4px;" id="bp-flight-num">AB-101</div>
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                </svg>
                <div style="font-size: 0.75rem; color: var(--emerald); font-weight: 700; margin-top: 4px;">NON-STOP</div>
              </div>

              <div class="pass-airport dest">
                <div class="pass-iata" id="bp-dest-code">DXB</div>
                <div class="pass-city" id="bp-dest-city">Dubai</div>
              </div>
            </div>

            <!-- Passenger and Seat Matrix -->
            <div class="pass-grid">
              <div class="pass-info-cell">
                <span class="pass-label">Passenger</span>
                <span class="pass-value" id="bp-name">Haile Bekele</span>
              </div>
              <div class="pass-info-cell">
                <span class="pass-label">Booking Ref</span>
                <span class="pass-value gold" id="bp-pnr">ABY-7842</span>
              </div>
              <div class="pass-info-cell">
                <span class="pass-label">Boarding Time</span>
                <span class="pass-value" id="bp-boarding-time">07:45</span>
              </div>
              <div class="pass-info-cell">
                <span class="pass-label">Seat</span>
                <span class="pass-value gold" id="bp-seat">02A</span>
              </div>

              <div class="pass-info-cell">
                <span class="pass-label">Gate</span>
                <span class="pass-value" id="bp-gate">B14</span>
              </div>
              <div class="pass-info-cell">
                <span class="pass-label">Terminal</span>
                <span class="pass-value" id="bp-terminal">Terminal 2</span>
              </div>
              <div class="pass-info-cell">
                <span class="pass-label">Boarding Group</span>
                <span class="pass-value" id="bp-group">GROUP 1</span>
              </div>
              <div class="pass-info-cell">
                <span class="pass-label">E-Ticket</span>
                <span class="pass-value" id="bp-eticket">071-29482103</span>
              </div>
            </div>
          </div>

          <!-- Perforation Notch -->
          <div class="pass-perforation">
            <div class="perforation-line"></div>
          </div>

          <!-- Barcode & QR Code Section -->
          <div class="pass-barcode-section">
            <div class="barcode-svg-wrap">
              <canvas id="bp-barcode-canvas" height="60" style="width: 100%; height: 60px;"></canvas>
            </div>
            <div class="qrcode-box">
              <canvas id="bp-qrcode-canvas" width="60" height="60"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div style="display: flex; justify-content: center; gap: 16px; margin-top: 24px;">
        <button type="button" class="btn-sheba" onclick="window.print()">
          🖨️ Print Boarding Pass
        </button>
        <button type="button" class="filter-btn" style="background: rgba(255,255,255,0.1); color: #fff;" onclick="window.showToast('Boarding pass saved to your mobile wallet!')">
          📲 Save to Apple / Google Wallet
        </button>
      </div>
    </div>
  </div>

  <!-- ==========================================
       MODAL 3: MANAGE BOOKING PORTAL
       ========================================== -->
  <div class="modal-overlay" id="manage-modal" role="dialog" aria-modal="true" aria-labelledby="manage-modal-title">
    <div class="modal-container">
      <div class="modal-header">
        <h2 class="modal-title" id="manage-modal-title">
          <span>🎫</span> Manage Your Booking
        </h2>
        <button type="button" class="modal-close" aria-label="Close Modal">&times;</button>
      </div>

      <div class="modal-body">
        <div style="background: var(--navy-card); border: 1px solid var(--navy-border); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 12px;">
            <div>
              <span style="font-size: 0.8rem; color: var(--text-muted);">PNR REFERENCE:</span>
              <span style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 800; color: var(--gold-light); margin-left: 8px;" id="mg-pnr">ABY-7842</span>
            </div>
            <div>
              <span style="font-size: 0.8rem; color: var(--text-muted);">STATUS:</span>
              <span style="color: var(--emerald-light); font-weight: 800; margin-left: 6px;" id="mg-status">Confirmed</span>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; font-size: 0.95rem;">
            <div><span style="color: var(--text-muted);">Passenger:</span> <strong id="mg-passenger" style="color: #fff;">Haile Bekele</strong></div>
            <div><span style="color: var(--text-muted);">Flight:</span> <strong id="mg-flight" style="color: #fff;">AB-101</strong></div>
            <div><span style="color: var(--text-muted);">Route:</span> <strong id="mg-route" style="color: #fff;">Addis Ababa → Dubai</strong></div>
            <div><span style="color: var(--text-muted);">Departure:</span> <strong id="mg-date" style="color: #fff;">08:30</strong></div>
            <div><span style="color: var(--text-muted);">Cabin Class:</span> <strong id="mg-class" style="color: var(--gold-light);">Cloud Nine Business</strong></div>
            <div><span style="color: var(--text-muted);">Seat:</span> <strong id="mg-seat" style="color: #fff;">02A</strong></div>
            <div><span style="color: var(--text-muted);">Meal Request:</span> <strong id="mg-meal" style="color: #fff;">Traditional Ethiopian</strong></div>
            <div><span style="color: var(--text-muted);">Baggage Allowance:</span> <strong id="mg-baggage" style="color: #fff;">3 Pieces (23kg)</strong></div>
          </div>
        </div>

        <h3 style="color: #fff; margin-bottom: 14px;">Available Modifications</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
          <button type="button" class="filter-btn" id="btn-mg-add-bag" style="padding: 14px; text-align: left; height: auto;">
            <div style="font-size: 1.2rem; margin-bottom: 4px;">🧳</div>
            <strong style="color: #fff;">Add Extra Bag</strong>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">+23kg Piece for $50</div>
          </button>

          <button type="button" class="filter-btn" id="btn-mg-upgrade" style="padding: 14px; text-align: left; height: auto;">
            <div style="font-size: 1.2rem; margin-bottom: 4px;">👑</div>
            <strong style="color: #fff;">Upgrade to Cloud Nine</strong>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Lie-flat suite + Lounge</div>
          </button>

          <button type="button" class="filter-btn" id="btn-mg-cancel" style="padding: 14px; text-align: left; height: auto; border-color: rgba(220,38,38,0.5);">
            <div style="font-size: 1.2rem; margin-bottom: 4px;">❌</div>
            <strong style="color: #ef4444;">Cancel & Refund</strong>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Request full or partial refund</div>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       FOOTER
       ========================================== -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand-logo" style="margin-bottom: 14px;">
            <img src="assets/images/abyssinia-logo.png" alt="Abyssinia Airline — Fly Beyond Borders" style="height: 60px; width: auto; object-fit: contain;">
            <div class="brand-text">
              <span class="brand-title">ABYSSINIA <span>AIRLINE</span></span>
              <span class="brand-subtitle">FLY BEYOND BORDERS</span>
            </div>
          </div>
          <div style="margin-bottom: 12px;">
            <span class="ethiopia-badge">
              <span class="ethiopia-flag-icon"></span> Based in Addis Ababa, Ethiopia 🇪🇹
            </span>
          </div>
          <p>
            Ethiopia's premier flagship carrier connecting travelers across 5 continents from Bole International Airport, Addis Ababa, Ethiopia. Rooted in millennia of Ethiopian heritage, African leadership, and timeless hospitality.
          </p>
        </div>

        <div>
          <h4 class="footer-col-title">Quick Booking</h4>
          <ul class="footer-links">
            <li><a href="#booking-console">Book Flights</a></li>
            <li><a href="#tab-manage" onclick="document.getElementById('tab-btn-manage').click(); document.getElementById('booking-console').scrollIntoView({behavior:'smooth'});">Manage Booking</a></li>
            <li><a href="#tab-checkin" onclick="document.getElementById('tab-btn-checkin').click(); document.getElementById('booking-console').scrollIntoView({behavior:'smooth'});">Online Check-In</a></li>
            <li><a href="#flight-radar-section">Flight Radar & Status</a></li>
            <li><a href="#destinations-section">Special Offers</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title">In-Flight & Fleet</h4>
          <ul class="footer-links">
            <li><a href="#experience-section">Cloud Nine Business</a></li>
            <li><a href="#experience-section">Bole VIP Lounge</a></li>
            <li><a href="#experience-section">Traditional Coffee Ceremony</a></li>
            <li><a href="#experience-section">Airbus A350 & B787 Fleet</a></li>
            <li><a href="#loyalty-section">Sheba Miles Rewards</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title">Customer Care</h4>
          <ul class="footer-links">
            <li><a href="tel:+251116656666">24/7 Global: +251 11 665 6666</a></li>
            <li><a href="mailto:care@abyssinia-airlines.com">care@abyssinia-airlines.com</a></li>
            <li><a href="#">Baggage Guidelines</a></li>
            <li><a href="#">Visa & Passport Rules</a></li>
            <li><a href="#">Privacy & Cookie Policy</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div>&copy; <?= date('Y') ?> Abyssinia Airline. All Rights Reserved. Flagship Carrier of Ethiopia 🇪🇹.</div>
        <div style="color: var(--gold-light);">Designed with PHP, CSS, HTML5 & JavaScript.</div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="assets/js/app.js"></script>
  <script>
    // Helper to jump from destination cards to flight search
    window.prefillSearch = function(orig, dest) {
      const origInput = document.getElementById('search-origin');
      const destInput = document.getElementById('search-dest');
      if (origInput && destInput) {
        origInput.dataset.code = orig;
        origInput.value = 'Addis Ababa (ADD)';
        destInput.dataset.code = dest;
        destInput.value = dest;
        document.getElementById('booking-console').scrollIntoView({ behavior: 'smooth' });
        document.getElementById('btn-search-submit').click();
      }
    };
  </script>
</body>
</html>
