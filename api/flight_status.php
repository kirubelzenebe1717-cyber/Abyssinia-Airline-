<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();

    $flightNumber = strtoupper(trim($_GET['flight_number'] ?? ''));
    $origin = strtoupper(trim($_GET['origin'] ?? ''));
    $destination = strtoupper(trim($_GET['destination'] ?? ''));

    $query = "
        SELECT f.*, 
               o.city as origin_city, o.country as origin_country, o.name as origin_airport,
               d.city as destination_city, d.country as destination_country, d.name as destination_airport
        FROM flights f
        JOIN airports o ON f.origin_code = o.code
        JOIN airports d ON f.destination_code = d.code
        WHERE 1=1
    ";
    $params = [];

    if (!empty($flightNumber)) {
        $query .= " AND (UPPER(f.flight_number) LIKE :num OR UPPER(f.flight_number) LIKE :numClean)";
        $params['num'] = '%' . $flightNumber . '%';
        $params['numClean'] = '%' . str_replace(['-', ' '], '', $flightNumber) . '%';
    } elseif (!empty($origin) && !empty($destination)) {
        $query .= " AND f.origin_code = :orig AND f.destination_code = :dest";
        $params['orig'] = $origin;
        $params['dest'] = $destination;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll();

    // Enrich with live telemetry simulation for flight radar
    foreach ($results as &$flight) {
        $status = $flight['status'];
        $progress = 0;
        $altitude = '0 ft';
        $speed = '0 km/h';
        $estRemaining = '0m';

        switch ($status) {
            case 'En Route':
                $progress = 68;
                $altitude = '38,000 ft';
                $speed = '895 km/h (Mach 0.82)';
                $estRemaining = '1h 45m';
                break;
            case 'Boarding':
                $progress = 15;
                $altitude = 'On Ground (Gate ' . $flight['gate'] . ')';
                $speed = '0 km/h';
                $estRemaining = 'Departs in 20 min';
                break;
            case 'Landed':
                $progress = 100;
                $altitude = 'Landed';
                $speed = '0 km/h';
                $estRemaining = 'Arrived';
                break;
            default: // Scheduled
                $progress = 0;
                $altitude = 'Scheduled';
                $speed = '0 km/h';
                $estRemaining = 'On Time';
                break;
        }

        $flight['telemetry'] = [
            'progress_percent' => $progress,
            'altitude' => $altitude,
            'ground_speed' => $speed,
            'remaining_time' => $estRemaining,
            'baggage_belt' => 'Belt ' . rand(1, 9),
            'weather_destination' => [
                'temp' => rand(22, 32) . '°C',
                'condition' => 'Clear Skies',
                'wind' => rand(8, 18) . ' km/h NE'
            ]
        ];
    }

    echo json_encode([
        'status' => 'success',
        'count' => count($results),
        'data' => $results
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
