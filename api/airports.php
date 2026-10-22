<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();
    $q = trim($_GET['q'] ?? '');

    if ($q !== '') {
        $stmt = $pdo->prepare("
            SELECT code, name, city, country 
            FROM airports 
            WHERE code LIKE :term OR city LIKE :term OR country LIKE :term OR name LIKE :term
            ORDER BY city ASC
            LIMIT 15
        ");
        $stmt->execute(['term' => "%$q%"]);
    } else {
        $stmt = $pdo->query("SELECT code, name, city, country FROM airports ORDER BY (code = 'ADD') DESC, city ASC");
    }

    $airports = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $airports]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
