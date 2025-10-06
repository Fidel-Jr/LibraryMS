<?php

    // require_once '../config/database.php';
    require_once '../models/Book.php';
    // $book = new Book($pdo);

    // Further code to handle adding a book would go here


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
            <h2>Add New Book</h2>
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
                    <label for="published_date" class="form-label
">Published Date</label>
                    <input type="date" class="form-control" id="published_date" name="published_date" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Book</button>
            </form>
        </div>
    </div>


    <script src="../assets/js/main.js"></script>
    
</body>
</html>