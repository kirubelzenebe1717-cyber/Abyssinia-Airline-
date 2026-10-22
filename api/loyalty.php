<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $origin = strtoupper(trim($_GET['origin'] ?? 'ADD'));
    $destination = strtoupper(trim($_GET['destination'] ?? 'DXB'));
    $cabinClass = trim($_GET['cabin_class'] ?? 'Economy Class');

    // Approximate distance table in nautical miles / statutory miles from ADD
    $distanceMap = [
        'DXB' => 1560,
        'LHR' => 3680,
        'JFK' => 7080,
        'CDG' => 3470,
        'FRA' => 3340,
        'NBO' => 720,
        'FCO' => 2780,
        'JNB' => 2520,
        'CAI' => 1540,
        'IST' => 2090,
        'NRT' => 6450,
        'IAD' => 7150,
        'LLI' => 210,
        'BJR' => 200,
        'GDQ' => 260,
        'DIR' => 220,
        'MQX' => 320
    ];

    $destKey = ($origin === 'ADD') ? $destination : $origin;
    $baseDistance = $distanceMap[$destKey] ?? 1800;

    // Multiplier based on cabin class
    $multiplier = 1.0;
    if (stripos($cabinClass, 'Business') !== false) {
        $multiplier = 1.75;
    } elseif (stripos($cabinClass, 'First') !== false) {
        $multiplier = 2.5;
    }

    $milesEarned = round($baseDistance * $multiplier);
    $tierPoints = round($milesEarned * 0.1);

    echo json_encode([
        'status' => 'success',
        'data' => [
            'route' => "$origin - $destination",
            'distance_miles' => $baseDistance,
            'cabin_class' => $cabinClass,
            'miles_earned' => $milesEarned,
            'tier_points_earned' => $tierPoints,
            'tiers' => [
                'Silver' => ['required_points' => 10000, 'lounge' => false, 'priority_checkin' => true, 'bonus_miles' => '25%'],
                'Gold' => ['required_points' => 25000, 'lounge' => true, 'priority_checkin' => true, 'bonus_miles' => '50%', 'extra_baggage' => '1 Piece (23kg)'],
                'Platinum' => ['required_points' => 50000, 'lounge' => true, 'priority_checkin' => true, 'bonus_miles' => '100%', 'extra_baggage' => '2 Pieces (32kg)', 'suite_upgrade' => true]
            ]
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
