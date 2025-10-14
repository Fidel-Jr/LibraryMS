<?php

    require_once '../config/database.php';
    require_once '../models/Book.php';
    $book = new Book($pdo);
    $is_success = false;
    $allBooks = $book->getAllBooks();

    if (isset($_GET["book_id"])) {
      $existingBook = $book->getBook($_GET["book_id"]);
      
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $data = [
              "book_id" => $_POST["book_id"],
              "title" => $_POST['title'],
              "author" => $_POST['author'],
              "isbn" => $_POST['isbn'],
              "catalog_id" => $_POST["catalog_id"],
              "year_published" => $_POST['year_published'],
              "status" => $_POST['status'],
              "shelf_location" => $_POST['shelf_location'],
              "category" => $_POST['category'],
              "condition" => $_POST['condition']
          ];

          $updatedBook = $book->updateBook($data);

          if ($updatedBook['success']) {
              header("Location: manage-book.php?success=1");
              exit;
          } else {
              $errors = $updatedBook['errors']; // Store validation errors for the form
          }
      }
    }

    if (isset($_GET["deleted_id"])) {
        $deleted = $book->deleteBook($_GET["deleted_id"]);

        if ($deleted) {
            header("Location: manage-book.php?deleted=1");
            exit;
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
            <div class="d-flex justify-content-between">
              <div>
                <h2 class="mb-3">Book Management</h2>
              </div>
              <div>
                <a href="add-book.php" class="btn" style="background-color: var(--primary-color); color: var(--primary-light);">+New Book</a>
              </div>
            </div>
            
            <?php  
              if (isset($_GET['success']) && $_GET['success'] == 1) {
                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                          <strong>Success!</strong> Book Updated Successfully.
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
              }
              if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <strong>Success!</strong> Book Deleted Successfully.
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
              }
            ?>
            <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Published</th>
                <th>Location</th>
                <th>Category</th>
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
                <td><?= $book["year_published"] ?></td>
                <td><?= $book["shelf_location"] ?></td>
                <td><?= $book["category"] ?></td>
                
                <td>
                    <!-- <a href="edit-book.php?book_id=<?= $book["book_id"] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a> -->
                   <button 
                        class="btn btn-sm edit-book" style="background-color: var(--primary-color); color: var(--primary-light);"
                        data-id="<?= htmlspecialchars($book['book_id']) ?>"
                        data-title="<?= htmlspecialchars($book['title']) ?>"
                        data-author="<?= htmlspecialchars($book['author']) ?>"
                        data-isbn="<?= htmlspecialchars($book['isbn']) ?>"
                        data-year="<?= htmlspecialchars($book['year_published']) ?>"
                        data-catalog-id="<?= htmlspecialchars($book['catalog_id']) ?>"
                        data-shelf_location="<?= htmlspecialchars($book['shelf_location']) ?>"
                        data-category="<?= htmlspecialchars($book['category']) ?>"
                        data-condition="<?= htmlspecialchars($book['book_condition']) ?>"
                        data-status="<?= htmlspecialchars($book['status']) ?>"
                    >
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button 
                        class="btn btn-danger btn-sm view-book"
                        data-id="<?= htmlspecialchars($book['book_id']) ?>"
                        data-title="<?= htmlspecialchars($book['title']) ?>"
                        data-author="<?= htmlspecialchars($book['author']) ?>"
                        data-isbn="<?= htmlspecialchars($book['isbn']) ?>"
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
                <th>Published</th>
                <th>Location</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
            
        </div>
    </div>

    
    <!-- Edit Book Modal -->
    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0">
          <form id="editBookForm" method="POST" action="">
            <div class="modal-header" style="background-color: var(--primary-color); color: var(--primary-light);">
              <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" id="editBookId" name="book_id">
              <input type="hidden" id="editCatalogId" name="catalog_id">

              <!-- Tabs --> 
              <ul class="nav nav-tabs mb-3" id="bookTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="book-info-tab" data-bs-toggle="tab" data-bs-target="#book-info" type="button" role="tab">Book Info</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="catalog-info-tab" data-bs-toggle="tab" data-bs-target="#catalog-info" type="button" role="tab">Catalog Info</button>
                </li>
              </ul>

              <!-- Tab Contents -->
              <div class="tab-content" id="bookTabContent">
                <!-- Book Info Tab -->
                <div class="tab-pane fade show active" id="book-info" role="tabpanel" aria-labelledby="book-info-tab">
                  <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" 
                          class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                          id="editTitle" 
                          name="title" 
                          value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    <div class="invalid-feedback">
                      <?php echo $errors['title'] ?? ''; ?>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" 
                          class="form-control <?php echo isset($errors['author']) ? 'is-invalid' : ''; ?>" 
                          id="editAuthor" 
                          name="author"
                          value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
                    <div class="invalid-feedback">
                      <?php echo $errors['author'] ?? ''; ?>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" 
                          class="form-control <?php echo isset($errors['isbn']) ? 'is-invalid' : ''; ?>" 
                          id="editISBN" 
                          name="isbn"
                          value="<?php echo htmlspecialchars($_POST['isbn'] ?? ''); ?>">
                    <div class="invalid-feedback">
                      <?php echo $errors['isbn'] ?? ''; ?>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Year Published</label>
                    <input type="number" 
                          class="form-control <?php echo isset($errors['year_published']) ? 'is-invalid' : ''; ?>" 
                          id="editYear" 
                          name="year_published"
                          value="<?php echo htmlspecialchars($_POST['year_published'] ?? ''); ?>">
                    <div class="invalid-feedback">
                      <?php echo $errors['year_published'] ?? ''; ?>
                    </div>
                  </div>
                </div>

                <!-- Catalog Info Tab -->
                <div class="tab-pane fade" id="catalog-info" role="tabpanel" aria-labelledby="catalog-info-tab">
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select <?php echo isset($errors['status']) ? 'is-invalid' : ''; ?>" 
                            id="editStatus" 
                            name="status">
                      <option value="">Select Status</option>
                      <option value="Available" <?php echo (($_POST['status'] ?? '') == 'Available') ? 'selected' : ''; ?>>Available</option>
                      <option value="Borrowed" <?php echo (($_POST['status'] ?? '') == 'Borrowed') ? 'selected' : ''; ?>>Borrowed</option>
                      <option value="Reserved" <?php echo (($_POST['status'] ?? '') == 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
                      <option value="Lost" <?php echo (($_POST['status'] ?? '') == 'Lost') ? 'selected' : ''; ?>>Lost</option>
                    </select>
                    <div class="invalid-feedback">
                      <?php echo $errors['status'] ?? ''; ?>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Shelf Location</label>
                    <input type="text" 
                          class="form-control <?php echo isset($errors['shelf_location']) ? 'is-invalid' : ''; ?>" 
                          id="editLocation" 
                          name="shelf_location"
                          value="<?php echo htmlspecialchars($_POST['shelf_location'] ?? ''); ?>">
                    <div class="invalid-feedback">
                      <?php echo $errors['shelf_location'] ?? ''; ?>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" 
                          class="form-control <?php echo isset($errors['category']) ? 'is-invalid' : ''; ?>" 
                          id="editCategory" 
                          name="category"
                          value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
                    <div class="invalid-feedback">
                      <?php echo $errors['category'] ?? ''; ?>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Condition</label>
                    <select class="form-select <?php echo isset($errors['condition']) ? 'is-invalid' : ''; ?>" 
                            id="editCondition" 
                            name="condition">
                      <option value="">Select Condition</option>
                      <option value="New" <?php echo (($_POST['condition'] ?? '') == 'New') ? 'selected' : ''; ?>>New</option>
                      <option value="Good" <?php echo (($_POST['condition'] ?? '') == 'Good') ? 'selected' : ''; ?>>Good</option>
                      <option value="Damaged" <?php echo (($_POST['condition'] ?? '') == 'Damaged') ? 'selected' : ''; ?>>Damaged</option>
                    </select>
                    <div class="invalid-feedback">
                      <?php echo $errors['condition'] ?? ''; ?>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn" style="background-color: var(--primary-color); color: var(--primary-light);">Save Changes</button>
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

    <?php if (!empty($errors)): ?>
      <script>
      document.addEventListener("DOMContentLoaded", function() {
          var editModal = new bootstrap.Modal(document.getElementById('editBookModal'));
          editModal.show();
      });
      </script>
    <?php endif; ?>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
                const year = this.dataset.year;

                // Fill modal with book details
                document.getElementById("bookTitle").textContent = title;
                document.getElementById("bookAuthor").textContent = author;
                document.getElementById("bookISBN").textContent = isbn;
                document.getElementById("bookYear").textContent = year;

                // Show modal
                const deleteModal = new bootstrap.Modal(document.getElementById("deleteBookModal"));
                deleteModal.show();

                // Confirm delete
                document.getElementById("confirmDelete").onclick = function() {
                if (confirm(`Are you sure you want to delete "${title}"?`)) {
                    window.location.href = `manage-book.php?deleted_id=${bookId}`;
                }
                };
            }); 
        });
        document.querySelectorAll(".edit-book").forEach(button => {
            button.addEventListener("click", function () {
                const bookId = this.dataset.id;
                const title = this.dataset.title;
                const author = this.dataset.author;
                const isbn = this.dataset.isbn;
                const year = this.dataset.year;

                // Fill book info fields
                document.getElementById("editBookId").value = bookId;
                document.getElementById("editTitle").value = title;
                document.getElementById("editAuthor").value = author;
                document.getElementById("editISBN").value = isbn;
                document.getElementById("editYear").value = year;

                // If catalog data exists, fill here (optional)
                document.getElementById("editCatalogId").value = this.dataset.catalogId;
                document.getElementById("editStatus").value = this.dataset.status || 'Available';
                document.getElementById("editLocation").value = this.dataset.shelf_location || '';
                document.getElementById("editCategory").value = this.dataset.category || '';
                document.getElementById("editCondition").value = this.dataset.condition || 'Good';

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