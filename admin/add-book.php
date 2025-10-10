<?php

    require_once '../config/database.php';
    require_once '../models/Book.php';
    $book = new Book($pdo);

    // Further code to handle adding a book would go here
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "title" => $_POST['title'],
        "author" => $_POST['author'],
        "isbn" => $_POST['isbn'],
        "edition" => $_POST['edition'],
        "year_published" => $_POST['year_published']
    ];
    $newBookAdded = $book->addBook($data);
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>


    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>
    <!-- Main Content -->
    <div id="content">
        <!-- Navbar -->
        <?php include '../includes/navbar.php'; ?>
        <!-- Page Content -->
        <div class="container-fluid mt-4 bg-white p-4 rounded shadow-sm">
            <h2 class="mb-3">Add New Book</h2>
            <?php
                if (isset($newBookAdded)) {
                    if (!$newBookAdded) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Failed!</strong> to add the book    .
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    }
                     else {
                        echo '<div class="alert alert-Success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> New book added.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    }
                }
            ?>
            <form action="add-book.php" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Book Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" required>
                </div>
                <div class="mb-3">
                    <label for="edition" class="form-label">Edition</label>
                    <input type="text" class="form-control" id="edition" name="edition" required>
                </div>
                <div class="mb-3">
                    <label for="year_published">Enter Year:</label>
                    <input type="number" class="form-control" id="year_published" name="year_published" min="1900" max="2100" placeholder="YYYY" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Book</button>
            </form>
        </div>
    </div>


    <script src="../assets/js/main.js"></script>
    
</body>
</html>