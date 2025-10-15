<?php 

    require '../config/database.php';
    $q = $_GET['query'] ?? '';
    $stmt = $pdo->prepare("SELECT book_id, title FROM books WHERE title LIKE ?");
    $stmt->execute(["%$q%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON
    header('Content-Type: application/json');
    
    echo json_encode($results);

?>