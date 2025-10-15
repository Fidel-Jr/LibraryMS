<?php
require '../config/database.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT user_id, email, phone FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$results = $stmt->fetch(PDO::FETCH_ASSOC);

// Return JSON
header('Content-Type: application/json');

echo json_encode($results);