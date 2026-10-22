<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();
    $raw = file_get_contents('php://input');
    $json = $raw ? json_decode($raw, true) : [];
    $input = array_merge($_GET, $_POST, is_array($json) ? $json : []);

    $pnr = strtoupper(trim($input['pnr'] ?? ''));
    $lastName = trim($input['last_name'] ?? '');
    $newSeat = trim($input['seat_number'] ?? '');
    $action = trim($input['action'] ?? 'retrieve'); // 'retrieve' or 'complete_checkin'

    if (empty($pnr)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Booking reference (PNR) is required.']);
        exit;
    }

    // Query booking
    $sql = "
        SELECT b.*, f.flight_number, f.departure_time, f.arrival_time, f.duration, f.aircraft, f.gate, f.terminal,
               o.city as origin_city, o.country as origin_country, o.code as origin_code, o.name as origin_airport,
               d.city as destination_city, d.country as destination_country, d.code as destination_code, d.name as destination_airport
        FROM bookings b
        JOIN flights f ON b.flight_id = f.id
        JOIN airports o ON f.origin_code = o.code
        JOIN airports d ON f.destination_code = d.code
        WHERE UPPER(b.pnr) = :pnr
    ";

    $params = ['pnr' => $pnr];
    if (!empty($lastName)) {
        $sql .= " AND UPPER(b.passenger_name) LIKE :lastName";
        $params['lastName'] = '%' . strtoupper($lastName) . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $booking = $stmt->fetch();

    if (!$booking) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No booking found matching reference ' . htmlspecialchars($pnr) . '. Please verify your PNR and last name.']);
        exit;
    }

    // If action is complete_checkin
    if ($action === 'complete_checkin' || isset($input['confirm'])) {
        $updateSql = "UPDATE bookings SET is_checked_in = 1, checked_in_at = datetime('now')";
        $updateParams = ['pnr' => $pnr];

        if (!empty($newSeat)) {
            $updateSql .= ", seat_number = :seat";
            $updateParams['seat'] = $newSeat;
            $booking['seat_number'] = $newSeat;
        }

        $updateSql .= " WHERE UPPER(pnr) = :pnr";
        $pdo->prepare($updateSql)->execute($updateParams);
        $booking['is_checked_in'] = 1;
        $booking['checked_in_at'] = date('Y-m-d H:i:s');
    }

    // Compute Boarding Pass details
    $depParts = explode(':', $booking['departure_time']);
    $depHour = intval($depParts[0] ?? 8);
    $depMin = intval(explode(' ', $depParts[1] ?? '30')[0]);
    // Boarding time is 45 mins prior to departure
    $boardingTimeTotal = ($depHour * 60 + $depMin) - 45;
    if ($boardingTimeTotal < 0) $boardingTimeTotal += 1440;
    $boardingHour = str_pad(floor($boardingTimeTotal / 60), 2, '0', STR_PAD_LEFT);
    $boardingMinute = str_pad($boardingTimeTotal % 60, 2, '0', STR_PAD_LEFT);
    $boardingTime = "$boardingHour:$boardingMinute";

    // Boarding group based on cabin class
    $boardingGroup = (stripos($booking['cabin_class'], 'Business') !== false || stripos($booking['cabin_class'], 'First') !== false) ? 'GROUP 1 (Priority)' : 'GROUP 3';

    // Digital Barcode String
    $barcodePayload = sprintf("M1%s/%s %s %s%s %s%s 101Y%s", 
        substr(strtoupper(str_replace(' ', '', $booking['passenger_name'])), 0, 15),
        $booking['pnr'],
        $booking['origin_code'] . $booking['destination_code'],
        'AB',
        str_pad(preg_replace('/[^0-9]/', '', $booking['flight_number']), 4, '0', STR_PAD_LEFT),
        date('z'),
        $booking['seat_number'],
        $boardingGroup
    );

    $booking['boarding_pass'] = [
        'boarding_time' => $boardingTime,
        'boarding_group' => $boardingGroup,
        'gate' => $booking['gate'] ?: 'B12',
        'terminal' => $booking['terminal'] ?: 'Terminal 2',
        'barcode_payload' => $barcodePayload,
        'eticket_number' => '071-' . rand(2100000000, 2999999999),
        'sequence_number' => str_pad(rand(1, 180), 3, '0', STR_PAD_LEFT),
        'fast_track' => stripos($booking['cabin_class'], 'Business') !== false ? 'YES' : 'STANDARD'
    ];

    echo json_encode([
        'status' => 'success',
        'data' => $booking
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
