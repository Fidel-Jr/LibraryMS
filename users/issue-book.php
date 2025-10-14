<?php
  require_once '../config/database.php';
  require_once '../models/Issue.php';
  session_start();
  $issued = new Issue($pdo);
  $allBorrowedBooks = $issued->getAllBorrowedBooks();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
          "user_id" => $_POST['user_id'] ?? '',
          "book_id" => $_POST['book_id'] ?? '',
          "due_date" => $_POST['due_date'] ?? '',
      ];

      $result = $issued->issueBook($data);

      if ($result['success']) {
          $_SESSION['success'] = "Book issued successfully!";
          header("Location: issue-book.php");
          exit;
      } else {
          $errors = $result['errors'];
      }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Issue Book</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <!-- Tom Select -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

  <style>
    .info-card {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      border: 1px solid #dee2e6;
    }
    label { font-weight: 500; }

  </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>
<div id="content">
  <?php include '../includes/navbar.php'; ?>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        <strong>Success!</strong> Book Issued successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <div class="container-fluid mt-4 bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between">
              <div>
                <h2 class="mb-3">Issued Books Management</h2>
              </div>
              <div>
                <button class="btn mb-3" id="openIssueBookModal" style="background-color: var(--primary-color); color: var(--primary-light);">
                  <i class="fas fa-book"></i> Issue Book
                </button>
              </div>
            </div>
    
    <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Borrowed Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

                foreach($allBorrowedBooks as $book):
            
            ?>
            <tr>
                <td><?= $book["user_id"] ?></td>
                <td><?= $book["full_name"] ?></td>
                <td><?= $book["title"] ?></td>
                <td><?= $book["author"] ?></td>
                <td><?= $book["isbn"] ?></td>
                <td><?= date('Y-m-d', strtotime($book["created_at"])) ?></td>
                <td><?= $book["due_date"] ?></td>
                <td><?= $book["status"] ?></td>
                
                <td>
                    <!-- <a href="edit-book.php?book_id=<?= $book["book_id"] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a> -->
                   <button 
                        class="btn btn-sm" style="background-color: var(--primary-color); color: var(--primary-light);"
                        
                    >
                        <i class="fas fa-edit"></i> Return
                    </button>
                    
                </td>
            </tr>
            <?php endforeach ?>
            
        </tbody>
        <tfoot>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Borrowed Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

    <!-- Issue Book Modal -->
    <div class="modal fade" id="issueBookModal" tabindex="-1" aria-labelledby="issueBookModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <form id="issueBookForm" method="POST" action="issue-book.php">
            <div class="modal-header text-white" style="background-color: var(--primary-color);">
              <h5 class="modal-title">Issue Book</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-4">

              <!-- Left: Form Inputs -->
              <div class="col-md-6">
                <!-- Book -->
                <div class="mb-3">
                  <label class="form-label">Book</label>
                  <select id="bookSelect" name="book_id"
                    class="form-select <?= in_array('Book id is required.', $errors ?? []) ? 'is-invalid' : '' ?>"></select>
                  <?php if (!empty($errors) && in_array('Book id is required.', $errors)): ?>
                    <div class="invalid-feedback">Book is required.</div>
                  <?php endif; ?>
                </div>

                <!-- User -->
                <div class="mb-3">
                  <label class="form-label">User</label>
                  <select id="userSelect" name="user_id"
                    class="form-select <?= in_array('User id is required.', $errors ?? []) ? 'is-invalid' : '' ?>"></select>
                  <?php if (!empty($errors) && in_array('User id is required.', $errors)): ?>
                    <div class="invalid-feedback">User is required.</div>
                  <?php endif; ?>
                </div>

                <!-- Due Date -->
                <div class="mb-3">
                  <label class="form-label">Due Date</label>
                  <input type="date" name="due_date"
                    class="form-control <?= in_array('Due date is required.', $errors ?? []) ? 'is-invalid' : '' ?>">
                  <?php if (!empty($errors) && in_array('Due date is required.', $errors)): ?>
                    <div class="invalid-feedback">Due date is required.</div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Right: Info Cards -->
              <div class="col-md-6">
                <div class="info-card mb-3" id="bookInfo">
                  <h6>Book Info</h6>
                  <p><strong>Author:</strong> <span id="bookAuthor"></span></p>
                  <p><strong>Shelf Location:</strong> <span id="bookSL"></span></p>
                  <p><strong>Category:</strong> <span id="bookCategory"></span></p>
                  <p><strong>Available Copies:</strong> <span id="bookCopies"></span></p>
                </div>

                <div class="info-card" id="studentInfo">
                  <h6>User Info</h6>
                  <p><strong>User ID:</strong> <span id="userId"></span></p>
                  <p><strong>Email:</strong> <span id="userEmail"></span></p>
                  <p><strong>Phone:</strong> <span id="userPhone"></span></p>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button href type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn" style="background-color: var(--primary-color); color: var(--primary-light);">Issue Book</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 

  if (!empty($errors)): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('issueBookModal'));
        myModal.show();
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
document.getElementById('openIssueBookModal').addEventListener('click', () => {
  new bootstrap.Modal(document.getElementById('issueBookModal')).show();
});

// Initialize Tom Select for Books
new TomSelect("#bookSelect", {
  valueField: 'book_id',
  labelField: 'title',
  searchField: 'title',
  load: function(query, callback) {
    if (!query.length) return callback();
    
    fetch('../utils/search-book.php?query=' + encodeURIComponent(query))
      .then(response => response.json())
      .then(data => {
        // console.log('AJAX response:', data); // <-- check response here
        callback(data);
      })
     
  },
  onChange: function(value) {
    if (value) {
      fetch('../utils/get-book-info.php?id=' + value)
        .then(res => res.json())
        .then(data => {
          console.log('Book info response:', data); // <-- check the response here

          if (data) {
            console.log(data[0].author)
            document.getElementById('bookAuthor').textContent = data[0].author || '';
            document.getElementById('bookSL').textContent = data[0].shelf_location || '';
            document.getElementById('bookCategory').textContent = data[0].category || '';
            document.getElementById('bookCopies').textContent = data[0].available_copies || '';
          }
        })
        .catch(err => {
          console.error('Error fetching book info:', err); // <-- check for errors
        });
    }
  }
});

// Initialize Tom Select for Students
new TomSelect("#userSelect", {
  valueField: 'user_id',
  labelField: 'full_name',
  searchField: 'full_name',
  load: function(query, callback) {
    if (!query.length) return callback();
    fetch('../utils/search-user.php?query=' + encodeURIComponent(query))
      .then(response => response.json())
      .then(callback)
      .catch(() => callback());
  },
  onChange: function(value) {
    if (value) {
      fetch('../utils/get-user-info.php?id=' + value)
        .then(res => res.json())
        .then(data => { console.log(data);
          document.getElementById('userId').textContent = data.user_id;
          document.getElementById('userEmail').textContent = data.email;
          document.getElementById('userPhone').textContent = data.phone;
        });
    }
  }
});
document.getElementById('bookSelect').addEventListener('change', function () {
  if (!this.value) {
    document.getElementById('bookAuthor').textContent = '';
    document.getElementById('bookSL').textContent = '';
    document.getElementById('bookCategory').textContent = '';
    document.getElementById('bookCopies').textContent = '';
  }
});

document.getElementById('userSelect').addEventListener('change', function () {
  if (!this.value) {
    document.getElementById('userId').textContent = '';
    document.getElementById('userEmail').textContent = '';
    document.getElementById('userPhone').textContent = '';
  }
});
</script>

</body>
</html>
