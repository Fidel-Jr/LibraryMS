<?php
require '../config/database.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT 
        b.author,
        c.shelf_location,
        c.copies AS available_copies,
        c.category AS category
    FROM books b
    INNER JOIN catalog c ON b.book_id = c.book_id
    WHERE b.book_id = ?");
$stmt->execute([$id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
header('Content-Type: application/json');

echo json_encode($results);