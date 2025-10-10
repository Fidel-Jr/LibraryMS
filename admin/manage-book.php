<?php

    require_once '../config/database.php';
    require_once '../models/Book.php';
    $book = new Book($pdo);
    $allBooks = $book->getAllBooks();
    if(isset($_GET["book_id"])){
        $existingBook = $book->getBook($_GET["book_id"]);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                "title" => $_POST['title'],
                "author" => $_POST['author'],
                "isbn" => $_POST['isbn'],
                "edition" => $_POST['edition'],
                "year_published" => $_POST['year_published']
            ];
            $updatedBook = $book->updateBook($_GET["book_id"], $data);
            if($updatedBook){
                header("Location: manage-book.php");
                exit;
            }
        }
        
       
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
            <h2 class="mb-3">Book Management</h2>

            <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Edtion</th>
                <th>Published</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

                foreach($allBooks as $book):
            
            ?>
            <tr>
                <td><?= $book["title"] ?></td>
                <td><?= $book["author"] ?></td>
                <td><?= $book["isbn"] ?></td>
                <td><?= $book["edition"] ?></td>
                <td><?= $book["year_published"] ?></td>
                <td>
                    <!-- <a href="edit-book.php?book_id=<?= $book["book_id"] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a> -->
                    <button 
                        class="btn btn-primary btn-sm edit-book"
                        data-id="<?= htmlspecialchars($book['book_id']) ?>"
                        data-title="<?= htmlspecialchars($book['title']) ?>"
                        data-author="<?= htmlspecialchars($book['author']) ?>"
                        data-isbn="<?= htmlspecialchars($book['isbn']) ?>"
                        data-edition="<?= htmlspecialchars($book['edition']) ?>"
                        data-year="<?= htmlspecialchars($book['year_published']) ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button 
                        class="btn btn-danger btn-sm view-book"
                        data-id="<?= htmlspecialchars($book['book_id']) ?>"
                        data-title="<?= htmlspecialchars($book['title']) ?>"
                        data-author="<?= htmlspecialchars($book['author']) ?>"
                        data-isbn="<?= htmlspecialchars($book['isbn']) ?>"
                        data-edition="<?= htmlspecialchars($book['edition']) ?>"
                        data-year="<?= htmlspecialchars($book['year_published']) ?>">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            <?php endforeach ?>
            
        </tbody>
        <tfoot>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Edition</th>
                <th>Published</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
            
        </div>
    </div>

    
    <!-- Edit Book Modal -->
    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
        <form id="editBookForm" method="POST" action="">
            <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
            <input type="hidden" id="editBookId" name="book_id">

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" id="editTitle" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" class="form-control" id="editAuthor" name="author" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" class="form-control" id="editISBN" name="isbn">
            </div>

            <div class="mb-3">
                <label class="form-label">Edition</label>
                <input type="text" class="form-control" id="editEdition" name="edition">
            </div>

            <div class="mb-3">
                <label class="form-label">Year Published</label>
                <input type="number" class="form-control" id="editYear" name="year_published">
            </div>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
        </div>
    </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteBookModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
            <h5 id="bookTitle" class="fw-bold text-danger"></h5>
            <p><strong>Author:</strong> <span id="bookAuthor"></span></p>
            <p><strong>ISBN:</strong> <span id="bookISBN"></span></p>
            <p><strong>Edition:</strong> <span id="bookEdition"></span></p>
            <p><strong>Year Published:</strong> <span id="bookYear"></span></p>
            <p class="text-muted">Are you sure you want to delete this book?</p>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button id="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
        </div>
    </div>
    </div>



    <script src="../assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');

        document.querySelectorAll(".view-book").forEach(button => {
            button.addEventListener("click", function() {
                const bookId = this.dataset.id;
                const title = this.dataset.title;
                const author = this.dataset.author;
                const isbn = this.dataset.isbn;
                const edition = this.dataset.edition;
                const year = this.dataset.year;

                // Fill modal with book details
                document.getElementById("bookTitle").textContent = title;
                document.getElementById("bookAuthor").textContent = author;
                document.getElementById("bookISBN").textContent = isbn;
                document.getElementById("bookEdition").textContent = edition;
                document.getElementById("bookYear").textContent = year;

                // Show modal
                const deleteModal = new bootstrap.Modal(document.getElementById("deleteBookModal"));
                deleteModal.show();

                // Confirm delete
                document.getElementById("confirmDelete").onclick = function() {
                if (confirm(`Are you sure you want to delete "${title}"?`)) {
                    window.location.href = `delete-book.php?book_id=${bookId}`;
                }
                };
            }); 
        });
        document.querySelectorAll(".edit-book").forEach(button => {
            button.addEventListener("click", function() {
                const bookId = this.dataset.id;
                const title = this.dataset.title;
                const author = this.dataset.author;
                const isbn = this.dataset.isbn;
                const edition = this.dataset.edition;
                const year = this.dataset.year;

                // Fill modal form
                document.getElementById("editBookId").value = bookId;
                document.getElementById("editTitle").value = title;
                document.getElementById("editAuthor").value = author;
                document.getElementById("editISBN").value = isbn;
                document.getElementById("editEdition").value = edition;
                document.getElementById("editYear").value = year;

                // Set form action dynamically
                document.getElementById("editBookForm").action = `manage-book.php?book_id=${bookId}`;

                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById("editBookModal"));
                editModal.show();
            });
        });

    </script>
    
</body>
</html>