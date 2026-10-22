<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();

    $origin = strtoupper(trim($_GET['origin'] ?? 'ADD'));
    $destination = strtoupper(trim($_GET['destination'] ?? 'DXB'));
    $departureDate = trim($_GET['departure_date'] ?? date('Y-m-d'));
    $cabinClass = trim($_GET['cabin_class'] ?? 'Economy Class');

    // Find direct flights
    $stmt = $pdo->prepare("
        SELECT f.*, 
               o.city as origin_city, o.country as origin_country, o.name as origin_airport_name,
               d.city as destination_city, d.country as destination_country, d.name as destination_airport_name
        FROM flights f
        JOIN airports o ON f.origin_code = o.code
        JOIN airports d ON f.destination_code = d.code
        WHERE f.origin_code = :origin AND f.destination_code = :dest
    ");
    $stmt->execute(['origin' => $origin, 'dest' => $destination]);
    $flights = $stmt->fetchAll();

    // If no direct flight found, check if we can connect via ADD (Addis Ababa Bole International Hub)
    if (empty($flights) && $origin !== 'ADD' && $destination !== 'ADD') {
        // Leg 1: Origin to ADD
        $stmt1 = $pdo->prepare("
            SELECT f.*, 
                   o.city as origin_city, o.country as origin_country, o.name as origin_airport_name,
                   d.city as destination_city, d.country as destination_country, d.name as destination_airport_name
            FROM flights f
            JOIN airports o ON f.origin_code = o.code
            JOIN airports d ON f.destination_code = d.code
            WHERE f.origin_code = :origin AND f.destination_code = 'ADD'
        ");
        $stmt1->execute(['origin' => $origin]);
        $leg1 = $stmt1->fetch();

        // Leg 2: ADD to Destination
        $stmt2 = $pdo->prepare("
            SELECT f.*, 
                   o.city as origin_city, o.country as origin_country, o.name as origin_airport_name,
                   d.city as destination_city, d.country as destination_country, d.name as destination_airport_name
            FROM flights f
            JOIN airports o ON f.origin_code = o.code
            JOIN airports d ON f.destination_code = d.code
            WHERE f.origin_code = 'ADD' AND f.destination_code = :dest
        ");
        $stmt2->execute(['dest' => $destination]);
        $leg2 = $stmt2->fetch();

        if ($leg1 && $leg2) {
            // Combined connecting flight
            $connectingFlight = [
                'id' => $leg1['id'],
                'flight_number' => $leg1['flight_number'] . ' / ' . $leg2['flight_number'],
                'origin_code' => $leg1['origin_code'],
                'destination_code' => $leg2['destination_code'],
                'origin_city' => $leg1['origin_city'],
                'origin_country' => $leg1['origin_country'],
                'origin_airport_name' => $leg1['origin_airport_name'],
                'destination_city' => $leg2['destination_city'],
                'destination_country' => $leg2['destination_country'],
                'destination_airport_name' => $leg2['destination_airport_name'],
                'departure_time' => $leg1['departure_time'],
                'arrival_time' => $leg2['arrival_time'],
                'duration' => '1 Stop via Addis Ababa (ADD)',
                'stops' => 1,
                'aircraft' => $leg1['aircraft'],
                'price_economy' => round(($leg1['price_economy'] + $leg2['price_economy']) * 0.85, 2),
                'price_business' => round(($leg1['price_business'] + $leg2['price_business']) * 0.85, 2),
                'price_first' => round(($leg1['price_first'] + $leg2['price_first']) * 0.85, 2),
                'status' => 'Scheduled',
                'gate' => $leg1['gate'],
                'terminal' => $leg1['terminal']
            ];
            $flights[] = $connectingFlight;
        }
    }

    // If still empty (e.g., custom search pair not yet in timetable), generate dynamic realistic codeshare Abyssinian flight
    if (empty($flights)) {
        $origAirport = $pdo->prepare("SELECT * FROM airports WHERE code = ?");
        $origAirport->execute([$origin]);
        $oData = $origAirport->fetch();

        $destAirport = $pdo->prepare("SELECT * FROM airports WHERE code = ?");
        $destAirport->execute([$destination]);
        $dData = $destAirport->fetch();

        if ($oData && $dData) {
            $num = rand(300, 799);
            $flights[] = [
                'id' => 9990 + rand(1, 99),
                'flight_number' => "AB-$num",
                'origin_code' => $origin,
                'destination_code' => $destination,
                'origin_city' => $oData['city'],
                'origin_country' => $oData['country'],
                'origin_airport_name' => $oData['name'],
                'destination_city' => $dData['city'],
                'destination_country' => $dData['country'],
                'destination_airport_name' => $dData['name'],
                'departure_time' => '09:40',
                'arrival_time' => '16:25',
                'duration' => '6h 45m',
                'stops' => 0,
                'aircraft' => 'Airbus A350-900',
                'price_economy' => 540.00,
                'price_business' => 1480.00,
                'price_first' => 2950.00,
                'status' => 'Scheduled',
                'gate' => 'B09',
                'terminal' => 'Terminal 2'
            ];
            // Also add an evening option
            $flights[] = [
                'id' => 9990 + rand(100, 199),
                'flight_number' => "AB-" . ($num + 1),
                'origin_code' => $origin,
                'destination_code' => $destination,
                'origin_city' => $oData['city'],
                'origin_country' => $oData['country'],
                'origin_airport_name' => $oData['name'],
                'destination_city' => $dData['city'],
                'destination_country' => $dData['country'],
                'destination_airport_name' => $dData['name'],
                'departure_time' => '21:15',
                'arrival_time' => '04:00 +1',
                'duration' => '6h 45m',
                'stops' => 0,
                'aircraft' => 'Boeing 787-9 Dreamliner',
                'price_economy' => 590.00,
                'price_business' => 1550.00,
                'price_first' => 3100.00,
                'status' => 'Scheduled',
                'gate' => 'B18',
                'terminal' => 'Terminal 2'
            ];
        }
    }

    echo json_encode([
        'status' => 'success',
        'query' => [
            'origin' => $origin,
            'destination' => $destination,
            'departure_date' => $departureDate,
            'cabin_class' => $cabinClass
        ],
        'count' => count($flights),
        'data' => $flights
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
