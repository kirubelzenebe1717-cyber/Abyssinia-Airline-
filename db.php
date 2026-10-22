<?php
/**
 * Abyssinia Airline - Database Connection & Auto-Migration
 * Flagship Carrier of Ethiopia (Based in Addis Ababa)
 * Uses SQLite PDO for zero-configuration, robust persistence.
 */

class Database {
    private static ?PDO $instance = null;
    private static string $dbFile = __DIR__ . '/database/abyssinia_airlines.sqlite';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dir = dirname(self::$dbFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $firstRun = !file_exists(self::$dbFile);

            self::$instance = new PDO('sqlite:' . self::$dbFile);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if ($firstRun || filesize(self::$dbFile) === 0) {
                self::migrateAndSeed(self::$instance);
            }
        }
        return self::$instance;
    }

    private static function migrateAndSeed(PDO $pdo): void {
        // Create Airports table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS airports (
                code TEXT PRIMARY KEY,
                name TEXT NOT NULL,
                city TEXT NOT NULL,
                country TEXT NOT NULL,
                timezone TEXT NOT NULL
            );
        ");

        // Create Flights table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS flights (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                flight_number TEXT UNIQUE NOT NULL,
                origin_code TEXT NOT NULL,
                destination_code TEXT NOT NULL,
                departure_time TEXT NOT NULL,
                arrival_time TEXT NOT NULL,
                duration TEXT NOT NULL,
                stops INTEGER DEFAULT 0,
                aircraft TEXT NOT NULL,
                price_economy REAL NOT NULL,
                price_business REAL NOT NULL,
                price_first REAL NOT NULL,
                status TEXT DEFAULT 'Scheduled',
                gate TEXT DEFAULT 'A1',
                terminal TEXT DEFAULT 'T2',
                FOREIGN KEY (origin_code) REFERENCES airports(code),
                FOREIGN KEY (destination_code) REFERENCES airports(code)
            );
        ");

        // Create Bookings table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS bookings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                pnr TEXT UNIQUE NOT NULL,
                flight_id INTEGER NOT NULL,
                passenger_name TEXT NOT NULL,
                passenger_email TEXT NOT NULL,
                passenger_phone TEXT NOT NULL,
                passport_number TEXT NOT NULL,
                cabin_class TEXT NOT NULL,
                seat_number TEXT NOT NULL,
                meal_preference TEXT DEFAULT 'Traditional Ethiopian (Injera & Wat)',
                baggage_count INTEGER DEFAULT 2,
                total_amount REAL NOT NULL,
                currency TEXT DEFAULT 'USD',
                status TEXT DEFAULT 'Confirmed',
                is_checked_in INTEGER DEFAULT 0,
                checked_in_at TEXT DEFAULT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (flight_id) REFERENCES flights(id)
            );
        ");

        // Populate standard international & domestic airports
        $airports = [
            ['ADD', 'Bole International Airport', 'Addis Ababa', 'Ethiopia', 'Africa/Addis_Ababa'],
            ['DXB', 'Dubai International Airport', 'Dubai', 'United Arab Emirates', 'Asia/Dubai'],
            ['LHR', 'Heathrow Airport', 'London', 'United Kingdom', 'Europe/London'],
            ['JFK', 'John F. Kennedy International', 'New York', 'United States', 'America/New_York'],
            ['CDG', 'Charles de Gaulle Airport', 'Paris', 'France', 'Europe/Paris'],
            ['FRA', 'Frankfurt am Main Airport', 'Frankfurt', 'Germany', 'Europe/Berlin'],
            ['NBO', 'Jomo Kenyatta International', 'Nairobi', 'Kenya', 'Africa/Nairobi'],
            ['FCO', 'Leonardo da Vinci Airport', 'Rome', 'Italy', 'Europe/Rome'],
            ['JNB', 'O. R. Tambo International', 'Johannesburg', 'South Africa', 'Africa/Johannesburg'],
            ['CAI', 'Cairo International Airport', 'Cairo', 'Egypt', 'Africa/Cairo'],
            ['IST', 'Istanbul Grand Airport', 'Istanbul', 'Turkey', 'Europe/Istanbul'],
            ['NRT', 'Narita International Airport', 'Tokyo', 'Japan', 'Asia/Tokyo'],
            ['IAD', 'Washington Dulles International', 'Washington D.C.', 'United States', 'America/New_York'],
            ['BJR', 'Bahar Dar Belay Zeleke Airport', 'Bahir Dar', 'Ethiopia', 'Africa/Addis_Ababa'],
            ['GDQ', 'Gondar Atse Tewodros Airport', 'Gondar', 'Ethiopia', 'Africa/Addis_Ababa'],
            ['LLI', 'Lalibela Historic Airport', 'Lalibela', 'Ethiopia', 'Africa/Addis_Ababa'],
            ['DIR', 'Aba Tenna Dejazmach Yilma Airport', 'Dire Dawa', 'Ethiopia', 'Africa/Addis_Ababa'],
            ['MQX', 'Alula Aba Nega Airport', 'Mekele', 'Ethiopia', 'Africa/Addis_Ababa']
        ];

        $stmt = $pdo->prepare("INSERT OR IGNORE INTO airports (code, name, city, country, timezone) VALUES (?, ?, ?, ?, ?)");
        foreach ($airports as $airport) {
            $stmt->execute($airport);
        }

        // Populate signature flights
        $flights = [
            ['AB-101', 'ADD', 'DXB', '08:30', '13:45', '4h 15m', 0, 'Airbus A350-900', 420.00, 1150.00, 2400.00, 'Boarding', 'B14', 'Terminal 2'],
            ['AB-102', 'DXB', 'ADD', '15:30', '18:45', '4h 15m', 0, 'Airbus A350-900', 440.00, 1200.00, 2500.00, 'Scheduled', 'A08', 'Terminal 3'],
            ['AB-204', 'ADD', 'LHR', '10:15', '16:40', '7h 25m', 0, 'Boeing 787-9 Dreamliner', 680.00, 1850.00, 3600.00, 'En Route', 'C04', 'Terminal 2'],
            ['AB-205', 'LHR', 'ADD', '21:00', '07:15 +1', '7h 15m', 0, 'Boeing 787-9 Dreamliner', 710.00, 1920.00, 3800.00, 'Scheduled', 'T2-Gate 19', 'Terminal 2'],
            ['AB-500', 'ADD', 'JFK', '23:30', '07:45 +1', '14h 15m', 0, 'Airbus A350-900', 890.00, 2750.00, 5200.00, 'Scheduled', 'B22', 'Terminal 2'],
            ['AB-501', 'JFK', 'ADD', '11:15', '07:30 +1', '13h 15m', 0, 'Airbus A350-900', 920.00, 2800.00, 5400.00, 'Scheduled', 'T4-Gate 26', 'Terminal 4'],
            ['AB-308', 'ADD', 'CDG', '01:20', '07:55', '7h 35m', 0, 'Boeing 787-9 Dreamliner', 650.00, 1780.00, 3450.00, 'Landed', 'B02', 'Terminal 2'],
            ['AB-309', 'CDG', 'ADD', '22:15', '06:35 +1', '7h 20m', 0, 'Boeing 787-9 Dreamliner', 670.00, 1810.00, 3500.00, 'Scheduled', 'Hall L-32', 'Terminal 2E'],
            ['AB-412', 'ADD', 'FRA', '23:55', '06:20 +1', '7h 25m', 0, 'Airbus A350-900', 640.00, 1740.00, 3300.00, 'Scheduled', 'B16', 'Terminal 2'],
            ['AB-610', 'ADD', 'NBO', '07:45', '09:55', '2h 10m', 0, 'Boeing 737 MAX 8', 210.00, 520.00, 950.00, 'Scheduled', 'A05', 'Terminal 1'],
            ['AB-611', 'NBO', 'ADD', '11:00', '13:10', '2h 10m', 0, 'Boeing 737 MAX 8', 210.00, 520.00, 950.00, 'Scheduled', 'Gate 04', 'Terminal 1A'],
            ['AB-702', 'ADD', 'FCO', '00:05', '05:40', '6h 35m', 0, 'Boeing 787-9 Dreamliner', 590.00, 1620.00, 3100.00, 'En Route', 'C11', 'Terminal 2'],
            ['AB-880', 'ADD', 'JNB', '09:00', '14:25', '5h 25m', 0, 'Airbus A350-900', 480.00, 1280.00, 2450.00, 'Scheduled', 'B07', 'Terminal 2'],
            ['AB-920', 'ADD', 'CAI', '16:00', '19:40', '3h 40m', 0, 'Boeing 737 MAX 8', 350.00, 890.00, 1600.00, 'Scheduled', 'A12', 'Terminal 2'],
            ['AB-950', 'ADD', 'NRT', '22:45', '18:50 +1', '13h 05m', 0, 'Airbus A350-900', 980.00, 2950.00, 5600.00, 'Scheduled', 'B20', 'Terminal 2'],
            ['AB-951', 'NRT', 'ADD', '20:30', '05:45 +1', '14h 15m', 0, 'Airbus A350-900', 1010.00, 3050.00, 5800.00, 'Scheduled', 'Gate 42', 'Terminal 1'],
            ['AB-050', 'ADD', 'LLI', '07:15', '08:25', '1h 10m', 0, 'Bombardier Q400', 95.00, 210.00, 350.00, 'Scheduled', 'D02', 'Terminal 1'],
            ['AB-051', 'LLI', 'ADD', '09:05', '10:15', '1h 10m', 0, 'Bombardier Q400', 95.00, 210.00, 350.00, 'Scheduled', 'Gate 1', 'Main Hall'],
            ['AB-060', 'ADD', 'BJR', '11:30', '12:35', '1h 05m', 0, 'Bombardier Q400', 85.00, 195.00, 320.00, 'Scheduled', 'D04', 'Terminal 1'],
            ['AB-070', 'ADD', 'GDQ', '14:20', '15:35', '1h 15m', 0, 'Bombardier Q400', 90.00, 205.00, 330.00, 'Scheduled', 'D05', 'Terminal 1']
        ];

        $stmt = $pdo->prepare("
            INSERT OR IGNORE INTO flights 
            (flight_number, origin_code, destination_code, departure_time, arrival_time, duration, stops, aircraft, price_economy, price_business, price_first, status, gate, terminal)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($flights as $flight) {
            $stmt->execute($flight);
        }

        // Seed initial realistic bookings for demonstration so Manage Booking and Check-in work instantly
        $seedBookings = [
            [
                'pnr' => 'ABY-7842',
                'flight_id' => 1, // AB-101 to Dubai
                'passenger_name' => 'Haile Selassie Bekele',
                'passenger_email' => 'haile.bekele@abyssinia.com',
                'passenger_phone' => '+251 91 123 4567',
                'passport_number' => 'ET8839201',
                'cabin_class' => 'Cloud Nine Business',
                'seat_number' => '02A',
                'meal_preference' => 'Traditional Ethiopian (Injera & Wat)',
                'baggage_count' => 3,
                'total_amount' => 1150.00,
                'currency' => 'USD',
                'status' => 'Confirmed',
                'is_checked_in' => 1,
                'checked_in_at' => date('Y-m-d H:i:s')
            ],
            [
                'pnr' => 'ABY-9125',
                'flight_id' => 3, // AB-204 to London
                'passenger_name' => 'Sarah Jenkins',
                'passenger_email' => 'sarah.j@travelworld.co.uk',
                'passenger_phone' => '+44 7700 900123',
                'passport_number' => 'UK5498120',
                'cabin_class' => 'Economy Class',
                'seat_number' => '14K',
                'meal_preference' => 'Vegetarian Feast',
                'baggage_count' => 2,
                'total_amount' => 680.00,
                'currency' => 'USD',
                'status' => 'Confirmed',
                'is_checked_in' => 0,
                'checked_in_at' => null
            ]
        ];

        $bookStmt = $pdo->prepare("
            INSERT OR IGNORE INTO bookings 
            (pnr, flight_id, passenger_name, passenger_email, passenger_phone, passport_number, cabin_class, seat_number, meal_preference, baggage_count, total_amount, currency, status, is_checked_in, checked_in_at)
            VALUES (:pnr, :flight_id, :passenger_name, :passenger_email, :passenger_phone, :passport_number, :cabin_class, :seat_number, :meal_preference, :baggage_count, :total_amount, :currency, :status, :is_checked_in, :checked_in_at)
        ");

        foreach ($seedBookings as $b) {
            $bookStmt->execute($b);
        }
    }
}
