<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();
    $raw = file_get_contents('php://input');
    $json = $raw ? json_decode($raw, true) : [];
    $input = array_merge($_GET, $_POST, is_array($json) ? $json : []);

    $flightId = intval($input['flight_id'] ?? 1);
    $passengerName = trim($input['passenger_name'] ?? '');
    $passengerEmail = trim($input['passenger_email'] ?? '');
    $passengerPhone = trim($input['passenger_phone'] ?? '');
    $passportNumber = trim($input['passport_number'] ?? '');
    $cabinClass = trim($input['cabin_class'] ?? 'Economy Class');
    $seatNumber = trim($input['seat_number'] ?? '12A');
    $mealPreference = trim($input['meal_preference'] ?? 'Traditional Ethiopian (Injera & Wat)');
    $baggageCount = intval($input['baggage_count'] ?? 2);
    $totalAmount = floatval($input['total_amount'] ?? 420.00);
    $currency = trim($input['currency'] ?? 'USD');

    if (empty($passengerName) || empty($passengerEmail)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Passenger name and email are required.']);
        exit;
    }

    // Generate unique 6-character PNR e.g. ABY-4921
    $pnr = 'ABY-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));

    // Verify flight exists or fallback to flight 1
    $checkFlight = $pdo->prepare("SELECT * FROM flights WHERE id = ?");
    $checkFlight->execute([$flightId]);
    $flight = $checkFlight->fetch();

    if (!$flight) {
        // Pick first flight as fallback if dynamic
        $flight = $pdo->query("SELECT * FROM flights LIMIT 1")->fetch();
        $flightId = $flight['id'];
    }

    $stmt = $pdo->prepare("
        INSERT INTO bookings 
        (pnr, flight_id, passenger_name, passenger_email, passenger_phone, passport_number, cabin_class, seat_number, meal_preference, baggage_count, total_amount, currency, status, is_checked_in)
        VALUES 
        (:pnr, :flight_id, :passenger_name, :passenger_email, :passenger_phone, :passport_number, :cabin_class, :seat_number, :meal_preference, :baggage_count, :total_amount, :currency, 'Confirmed', 0)
    ");

    $stmt->execute([
        'pnr' => $pnr,
        'flight_id' => $flightId,
        'passenger_name' => $passengerName,
        'passenger_email' => $passengerEmail,
        'passenger_phone' => $passengerPhone ?: '+251 90 000 0000',
        'passport_number' => $passportNumber ?: 'ET' . rand(1000000, 9999999),
        'cabin_class' => $cabinClass,
        'seat_number' => $seatNumber,
        'meal_preference' => $mealPreference,
        'baggage_count' => $baggageCount,
        'total_amount' => $totalAmount,
        'currency' => $currency
    ]);

    // Retrieve created booking with flight and airport details
    $bookingQuery = $pdo->prepare("
        SELECT b.*, f.flight_number, f.departure_time, f.arrival_time, f.aircraft, f.gate, f.terminal,
               o.city as origin_city, o.country as origin_country, o.code as origin_code,
               d.city as destination_city, d.country as destination_country, d.code as destination_code
        FROM bookings b
        JOIN flights f ON b.flight_id = f.id
        JOIN airports o ON f.origin_code = o.code
        JOIN airports d ON f.destination_code = d.code
        WHERE b.pnr = ?
    ");
    $bookingQuery->execute([$pnr]);
    $confirmedBooking = $bookingQuery->fetch();

    echo json_encode([
        'status' => 'success',
        'message' => 'Flight booked successfully! Your e-ticket and boarding details are confirmed.',
        'data' => $confirmedBooking
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
