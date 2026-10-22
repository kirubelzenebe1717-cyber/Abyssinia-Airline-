# Abyssinia Airline (Fly Beyond Borders) 🇪🇹✈️

The official web portal for **Abyssinia Airline**, Ethiopia's premier flagship carrier proudly based at **Bole International Airport, Addis Ababa, Ethiopia**. Crafted using **PHP**, **Vanilla CSS**, **HTML5**, and **JavaScript**, backed by an embedded SQLite database.

---

## ✨ Features

1. **Universal Booking Console**:
   - **Trip Modes**: Round-trip, One-way, and Multi-city.
   - **Smart Airport Autocomplete**: 18+ domestic & international airports (Addis Ababa `ADD`, Dubai `DXB`, London Heathrow `LHR`, New York `JFK`, Paris `CDG`, Frankfurt `FRA`, Nairobi `NBO`, Rome `FCO`, Tokyo `NRT`, Lalibela `LLI`, Bahir Dar `BJR`, Gondar `GDQ`, etc.).
   - **One-click Airport Swap** with smooth 180° rotation.
   - **Cabin Classes**: Economy Class, Cloud Nine Business Suites, First Class.

2. **Flight Search & Booking Engine**:
   - Real-time flight schedule filtering (Non-stop / 1-stop, departure times, aircraft models).
   - Detailed fare cards for Airbus A350-900, Boeing 787-9 Dreamliner, Boeing 777-300ER, and Boeing 737 MAX 8.
   - Multi-step reservation flow:
     - **Step 1**: Passenger Details & Gourmet Meal Selection (Authentic Ethiopian Injera with Doro Wat, Vegetarian Feast, Halal, Kosher, Gluten-Free).
     - **Step 2**: **Interactive 2D Aircraft Seat Map** (Airbus A350 fuselage layout with Cloud Nine 1-2-1 lie-flat suites and Economy 3-3-3 seating).
     - **Step 3**: Baggage Add-ons & Travel Protection.
     - **Step 4**: Payment Simulation (Card, Telebirr, PayPal, CBE Birr).
     - **Step 5**: Instant Confirmed E-Ticket & 6-character PNR generation.

3. **Online Web Check-In & Digital Boarding Pass**:
   - Check-in by PNR and Last Name.
   - Generates an authentic perforated airline Boarding Pass:
     - Dynamic **2D Barcode Canvas** (PDF417 / Aztec format) and **QR Code**.
     - Flight number, seat, gate, terminal, boarding group (e.g. Group 1 Priority), and departure time.
     - Instant options to "Print Boarding Pass" or "Save to Mobile Wallet".

4. **Manage Booking Portal**:
   - Retrieve any booking using PNR reference.
   - Add extra 23kg checked bags.
   - Upgrade seat or cabin class to Cloud Nine Business.
   - Request in-flight meal changes or cancel reservation.

5. **Live Flight Radar & Telemetry Tracker**:
   - Track flights by flight number (e.g. `AB-101`, `AB-204`, `AB-500`) or route.
   - Animated flight progress indicator along route.
   - Real-time telemetry: Cruising Altitude (38,000 ft), Ground Speed (895 km/h / Mach 0.82), Gate, Terminal, Baggage Belt, and Destination Weather.

6. **Abyssinia Sheba Miles Loyalty Club**:
   - Tier overview: Silver, Gold, Platinum.
   - **Interactive Miles Calculator**: Instant calculation of miles earned and tier qualification points.

7. **Currency Switcher**:
   - Instant currency conversion across all flight fares and add-ons in **USD ($)**, **EUR (€)**, **GBP (£)**, **ETB (Br)**, and **AED (د.إ)**.

---

## 🚀 How to Run the Project

### Prerequisites
- PHP 8.0 or newer (e.g. via XAMPP at `C:\xampp\php\php.exe` or standard PHP binary).
- Any modern web browser.

### Start the Server
Run the built-in PHP development server in the project directory:

```powershell
# If php is in your PATH:
php -S localhost:8080

# Or with XAMPP PHP directly:
C:\xampp\php\php.exe -S 127.0.0.1:8080 -t .
```

Then visit:
```
http://127.0.0.1:8080/
```

---

## 📂 Project Structure

```
Abyssinia-Airline-/
│
├── index.php                 # Main airline portal & all user interfaces
├── db.php                    # SQLite database connection & auto-schema migration
├── README.md                 # Documentation
│
├── api/
│   ├── airports.php          # Airport search & autocomplete API
│   ├── flights.php           # Real-time flight search & pricing API
│   ├── book.php              # Flight reservation & PNR generator
│   ├── checkin.php           # Online check-in & boarding pass API
│   ├── manage.php            # Manage booking, baggage add-ons, upgrade API
│   ├── flight_status.php     # Live radar status & telemetry API
│   └── loyalty.php           # Sheba Miles loyalty calculator API
│
├── assets/
│   ├── css/
│   │   └── style.css         # Custom responsive stylesheet & luxury design system
│   ├── js/
│   │   └── app.js            # Interactive seat picker, booking wizard, radar, currency
│   └── images/
│       ├── hero-aircraft.jpg # Airbus A350 in Abyssinia royal livery over sunset clouds
│       ├── business-class.jpg# Cloud Nine luxury lie-flat suite cabin
│       ├── lounge-hub.jpg    # Bole International VIP Lounge with coffee ceremony
│       ├── lalibela.jpg      # Historic rock churches destination
│       └── dubai.jpg         # Dubai skyline destination
│
└── database/
    └── abyssinia_airlines.sqlite # SQLite relational database (auto-created on first run)
```

---

## 🎫 Sample Booking References to Test Right Away

- **`ABY-7842`** — Passenger: **Haile Selassie Bekele** (ADD → DXB, Cloud Nine Business, Seat `02A`)
- **`ABY-9125`** — Passenger: **Sarah Jenkins** (ADD → LHR, Economy Class, Seat `14K`)
