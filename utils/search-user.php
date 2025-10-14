<?php
require '../config/database.php';
$q = $_GET['query'] ?? '';
$stmt = $pdo->prepare("SELECT user_id, full_name FROM users WHERE full_name LIKE ?");
$stmt->execute(["%$q%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
header('Content-Type: application/json');

echo json_encode($results);