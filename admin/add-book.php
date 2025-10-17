<?php

require_once '../config/database.php';
require_once '../models/Book.php';
$book = new Book($pdo);

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "title" => $_POST['title'] ?? '',
        "author" => $_POST['author'] ?? '',
        "isbn" => $_POST['isbn'] ?? '',
        "year_published" => $_POST['year_published'] ?? '',
        "shelf_location" => $_POST['shelf_location'] ?? '',
        "category" => $_POST['category'] ?? '',
        "condition" => $_POST['condition'] ?? '',
        "status" => $_POST['status'] ?? '',
        "copies" => $_POST['copies'] ?? ''
    ];

    $old = $data; // store old values for redisplay
    $newBookAdded = $book->addBook($data);

    if (!$newBookAdded['success']) {
        $errors = $newBookAdded['errors'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
        }
    </style>
</head>
<body>

    <?php include '../includes/sidebar.php'; ?>

    <div id="content">
        <?php include '../includes/navbar.php'; ?>

        <div class="container-fluid mt-4 bg-white p-4 rounded shadow-sm">
            <div class="d-flex justify-content-between">
              <div>
                <h2 class="mb-3">Add New Book</h2>
              </div>
              <div>
                <a href="manage-book.php" class="btn" style="background-color: var(--primary-color); color: var(--primary-light);">Manage Book</a>
              </div>
            </div>

            <?php
            if (isset($newBookAdded)) {
                if ($newBookAdded['success']) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> New book added successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                } elseif (!empty($errors)) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> Please insert all the required data below.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
            }
            ?>

            <form action="add-book.php" method="POST">
                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Book Title</label>
                    <input type="text" class="form-control <?= in_array('Title is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
                    <?php if (in_array('Title is required.', $errors)): ?>
                        <div class="invalid-feedback">Title is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Author -->
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control <?= in_array('Author is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="author" name="author" value="<?= htmlspecialchars($old['author'] ?? '') ?>">
                    <?php if (in_array('Author is required.', $errors)): ?>
                        <div class="invalid-feedback">Author is required.</div>
                    <?php endif; ?>
                </div>

                <!-- ISBN -->
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN</label>
                    <input type="text" class="form-control <?= in_array('Isbn is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="isbn" name="isbn" value="<?= htmlspecialchars($old['isbn'] ?? '') ?>">
                    <?php if (in_array('Isbn is required.', $errors)): ?>
                        <div class="invalid-feedback">ISBN is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Year -->
                <div class="mb-3">
                    <label for="year_published" class="form-label">Year Published</label>
                    <input type="number" class="form-control <?= in_array('Year published is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="year_published" name="year_published" min="1900" max="2100" placeholder="YYYY"
                        value="<?= htmlspecialchars($old['year_published'] ?? '') ?>">
                    <?php if (in_array('Year published is required.', $errors)): ?>
                        <div class="invalid-feedback">Year Published is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Shelf -->
                <div class="mb-3">
                    <label for="shelf_location" class="form-label">Shelf Location</label>
                    <input type="text" class="form-control <?= in_array('Shelf location is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="shelf_location" name="shelf_location" value="<?= htmlspecialchars($old['shelf_location'] ?? '') ?>">
                    <?php if (in_array('Shelf location is required.', $errors)): ?>
                        <div class="invalid-feedback">Shelf Location is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control <?= in_array('Category is required.', $errors) ? 'is-invalid' : '' ?>" id="category" name="category"
                        value="<?= htmlspecialchars($old['category'] ?? '') ?>">
                    <?php if (in_array('Author is required.', $errors)): ?>
                        <div class="invalid-feedback">Category is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Condition -->
                <div class="mb-3">
                    <label class="form-label">Condition</label>
                    <select class="form-select <?= in_array('Condition is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="condition" name="condition">
                        <option value="">Select Condition</option>
                        <option value="New" <?= (isset($old['condition']) && $old['condition'] === 'New') ? 'selected' : '' ?>>New</option>
                        <option value="Good" <?= (isset($old['condition']) && $old['condition'] === 'Good') ? 'selected' : '' ?>>Good</option>
                        <option value="Damaged" <?= (isset($old['condition']) && $old['condition'] === 'Damaged') ? 'selected' : '' ?>>Damaged</option>
                    </select>
                    <?php if (in_array('Condition is required.', $errors)): ?>
                        <div class="invalid-feedback">Condition is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select <?= in_array('Status is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="status" name="status">
                        <option value="">Select Status</option>
                        <option value="Available" <?= (isset($old['status']) && $old['status'] === 'Available') ? 'selected' : '' ?>>Available</option>
                        <option value="Borrowed" <?= (isset($old['status']) && $old['status'] === 'Borrowed') ? 'selected' : '' ?>>Borrowed</option>
                        <option value="Reserved" <?= (isset($old['status']) && $old['status'] === 'Reserved') ? 'selected' : '' ?>>Reserved</option>
                        <option value="Lost" <?= (isset($old['status']) && $old['status'] === 'Lost') ? 'selected' : '' ?>>Lost</option>
                    </select>
                    <?php if (in_array('Status is required.', $errors)): ?>
                        <div class="invalid-feedback">Status is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Copies -->
                <div class="mb-3">
                    <label for="copies" class="form-label">Number of Copies</label>
                    <input type="number" class="form-control <?= in_array('Number of copies is required.', $errors) ? 'is-invalid' : '' ?>"
                        id="copies" name="copies" placeholder="Copies"
                        value="<?= htmlspecialchars($old['copies'] ?? '') ?>">
                    <?php if (in_array('Number of copies is required.', $errors)): ?>
                        <div class="invalid-feedback">Number of Copies is required.</div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn" style="background-color: var(--primary-color); color: var(--primary-light);">Add Book</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
