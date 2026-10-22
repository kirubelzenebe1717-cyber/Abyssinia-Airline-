<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();
    $raw = file_get_contents('php://input');
    $json = $raw ? json_decode($raw, true) : [];
    $input = array_merge($_GET, $_POST, is_array($json) ? $json : []);

    $pnr = strtoupper(trim($input['pnr'] ?? ''));
    $action = trim($input['action'] ?? 'view');

    if (empty($pnr)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'PNR reference code is required.']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT b.*, f.flight_number, f.departure_time, f.arrival_time, f.duration, f.aircraft, f.gate, f.terminal,
               o.city as origin_city, o.country as origin_country, o.code as origin_code, o.name as origin_airport,
               d.city as destination_city, d.country as destination_country, d.code as destination_code, d.name as destination_airport
        FROM bookings b
        JOIN flights f ON b.flight_id = f.id
        JOIN airports o ON f.origin_code = o.code
        JOIN airports d ON f.destination_code = d.code
        WHERE UPPER(b.pnr) = :pnr
    ");
    $stmt->execute(['pnr' => $pnr]);
    $booking = $stmt->fetch();

    if (!$booking) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Booking not found for reference ' . htmlspecialchars($pnr)]);
        exit;
    }

    // Handle updates
    if ($action === 'add_baggage') {
        $newCount = ($booking['baggage_count'] ?? 2) + 1;
        $newTotal = $booking['total_amount'] + 50.00;
        $pdo->prepare("UPDATE bookings SET baggage_count = ?, total_amount = ? WHERE pnr = ?")
            ->execute([$newCount, $newTotal, $pnr]);
        $booking['baggage_count'] = $newCount;
        $booking['total_amount'] = $newTotal;
        $message = "Extra 23kg checked bag added successfully (+$50).";
    } elseif ($action === 'update_meal') {
        $newMeal = trim($input['meal_preference'] ?? 'Traditional Ethiopian (Injera & Wat)');
        $pdo->prepare("UPDATE bookings SET meal_preference = ? WHERE pnr = ?")
            ->execute([$newMeal, $pnr]);
        $booking['meal_preference'] = $newMeal;
        $message = "Meal preference updated to: $newMeal.";
    } elseif ($action === 'change_seat') {
        $newSeat = trim($input['seat_number'] ?? '12A');
        $pdo->prepare("UPDATE bookings SET seat_number = ? WHERE pnr = ?")
            ->execute([$newSeat, $pnr]);
        $booking['seat_number'] = $newSeat;
        $message = "Seat successfully changed to $newSeat.";
    } elseif ($action === 'upgrade_cabin') {
        $upgradeCost = 450.00;
        $newClass = 'Cloud Nine Business';
        $newSeat = '03A';
        $newTotal = $booking['total_amount'] + $upgradeCost;
        $pdo->prepare("UPDATE bookings SET cabin_class = ?, seat_number = ?, total_amount = ? WHERE pnr = ?")
            ->execute([$newClass, $newSeat, $newTotal, $pnr]);
        $booking['cabin_class'] = $newClass;
        $booking['seat_number'] = $newSeat;
        $booking['total_amount'] = $newTotal;
        $message = "Congratulations! You have been upgraded to Cloud Nine Business Class.";
    } elseif ($action === 'cancel_booking') {
        $pdo->prepare("UPDATE bookings SET status = 'Cancelled' WHERE pnr = ?")
            ->execute([$pnr]);
        $booking['status'] = 'Cancelled';
        $message = "Your booking $pnr has been cancelled. Refund processing initiated.";
    } else {
        $message = "Booking details retrieved.";
    }

    echo json_encode([
        'status' => 'success',
        'message' => $message,
        'data' => $booking
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
