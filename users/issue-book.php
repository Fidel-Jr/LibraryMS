<?php
  require_once '../config/database.php';
  require_once '../models/Issue.php';
  session_start();

  $issued = new Issue($pdo);
  $allBorrowedBooks = $issued->getAllBorrowedBooks();

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["issue_btn"])) {
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

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["return_btn"])) {
    $data = [
      "issued_id" => $_POST['issued_id'] ?? '',
      "days_due" => $_POST['days_due'] ?? '',
      "fine" => $_POST['fine'] ?? '',
      "fine_status" => $_POST['fine_status'] ?? ''
    ];

    $result = $issued->returnBook($data);

    if ($result['success']) {
        $_SESSION['success'] = "✅ Book returned successfully!";
        header("Location: issue-book.php");
        exit;
    } else {
        $errors = $result['errors'];
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["renew_btn"])) {
    $data = [
        "issued_id" => $_POST['issued_id'] ?? '',
        "new_due_date" => $_POST['new_due_date'] ?? ''
    ];

    $result = $issued->renewBook($data);

    if ($result['success']) {
        $_SESSION['success'] = "♻️ Book renewed successfully!";
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
        <strong>Success!</strong> <?php echo $_SESSION['success'] ?>
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
          <?php foreach ($allBorrowedBooks as $book): ?>
            <tr>
              <td><?= $book["user_id"] ?></td>
              <td><?= $book["full_name"] ?></td>
              <td><?= $book["title"] ?></td>
              <td><?= $book["author"] ?></td> 
              <td><?= $book["isbn"] ?></td>
              <td><?= date('Y-m-d', strtotime($book["created_at"])) ?></td>
              <td><?= $book["due_date"] ?></td>
              <td><?= $book["status"] ?></td>
              <td class="" style="width: 85px;">
                <button class="btn btn-sm btn-return" data-id="<?= $book['issued_id'] ?>" 
                  style="background-color: var(--primary-color); color: var(--primary-light);">
                  <i class="fas fa-undo"></i> Return
                </button>
                <button class="btn btn-sm btn-renew" data-id="<?= $book['issued_id'] ?>" 
                  style="background-color: #198754; color: white;">
                  <i class="fas fa-sync"></i> Renew
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
        <!-- Header -->
        <div class="modal-header text-white" style="background-color: var(--primary-color);">
          <h5 class="modal-title">Issue Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <div class="row g-4">
            
            <!-- LEFT SIDE: Form Fields -->
            <div class="col-md-6">
              <div class="card border-0 shadow-sm p-3">
                <h6 class="mb-3 text-secondary fw-bold">Issue Details</h6>

                <!-- Select Book -->
                <div class="mb-3">
                  <label class="form-label fw-semibold">Select Book</label>
                  <select id="bookSelect" name="book_id"
                    class="form-select <?= in_array('Book id is required.', $errors ?? []) ? 'is-invalid' : '' ?>"></select>
                  <?php if (!empty($errors) && in_array('Book id is required.', $errors)): ?>
                    <div class="invalid-feedback">Book is required.</div>
                  <?php endif; ?>
                </div>

                <!-- Select User -->
                <div class="mb-3">
                  <label class="form-label fw-semibold">Select User</label>
                  <select id="userSelect" name="user_id"
                    class="form-select <?= in_array('User id is required.', $errors ?? []) ? 'is-invalid' : '' ?>"></select>
                  <?php if (!empty($errors) && in_array('User id is required.', $errors)): ?>
                    <div class="invalid-feedback">User is required.</div>
                  <?php endif; ?>
                </div>

                <!-- Due Date -->
                <div class="mb-3">
                  <label class="form-label fw-semibold">Due Date</label>
                  <input type="date" name="due_date" class="form-control <?= in_array('Due date is required.', $errors ?? []) ? 'is-invalid' : '' ?>" required>
                  <?php if (!empty($errors) && in_array('Due date is required.', $errors)): ?>
                    <div class="invalid-feedback">Due date is required.</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- RIGHT SIDE: Info Cards -->
            <div class="col-md-6">
              <div class="card border-0 shadow-sm mb-3 p-3" id="bookInfo">
                <h6 class="mb-3 text-secondary fw-bold">Book Information</h6>
                <p><strong>Author:</strong> <span id="bookAuthor" class="text-muted"></span></p>
                <p><strong>Category:</strong> <span id="bookCategory" class="text-muted"></span></p>
                <p><strong>Shelf Location:</strong> <span id="bookSL" class="text-muted"></span></p>
                <p><strong>Available Copies:</strong> <span id="bookCopies" class="text-muted"></span></p>
              </div>

              <div class="card border-0 shadow-sm p-3" id="userInfo">
                <h6 class="mb-3 text-secondary fw-bold">User Information</h6>
                <p><strong>Name:</strong> <span id="userId" class="text-muted"></span></p>
                <p><strong>Email:</strong> <span id="userEmail" class="text-muted"></span></p>
                <p><strong>Phone:</strong> <span id="userPhone" class="text-muted"></span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="issue_btn" class="btn" style="background-color: var(--primary-color); color: var(--primary-light);">
            Issue Book
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

    </div>
  </div>

  <!-- Return Book Modal -->
<div class="modal fade" id="returnBookModal" tabindex="-1" aria-labelledby="returnBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <form id="returnBookForm" method="POST" action="issue-book.php">
        
        <!-- Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-semibold">Return Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <div class="row g-4">
            
            <!-- Borrower Info -->
            <div class="col-md-6">
              <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-bold mb-3 text-primary">User Information</h6>
                <p><strong>Name:</strong> <span id="retUserName"></span></p>
                <p><strong>Email:</strong> <span id="retUserEmail"></span></p>
                <p><strong>Phone:</strong> <span id="retUserPhone"></span></p>
              </div>
            </div>

            <!-- Book Info -->
            <div class="col-md-6">
              <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-bold mb-3 text-primary">Book Information</h6>
                <p><strong>Title:</strong> <span id="retBookTitle"></span></p>
                <p><strong>Author:</strong> <span id="retBookAuthor"></span></p>
                <p><strong>ISBN:</strong> <span id="retBookISBN"></span></p>
                <p><strong>Category:</strong> <span id="retBookCategory"></span></p>
              </div>
            </div>

            <!-- Borrow Details -->
            <div class="col-12">
              <div class="card border-0 shadow-sm p-3">
                <h6 class="fw-bold mb-3 text-primary">Borrow Details</h6>
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Borrowed Date:</strong> <span id="retBorrowedDate"></span></p>
                    <p><strong>Due Date:</strong> <span id="retDueDate"></span></p>
                    <p><strong>Days Due:</strong> <span id="retDaysDue"></span></p>
                    <input type="hidden" name="days_due" id="daysDueInput">
                  </div>
                  <div class="col-md-6">
                    <p><strong>Status:</strong> <span id="retStatus" class="badge bg-secondary"></span></p>
                    <p><strong>Fine:</strong> $<span id="retFine">0</span></p>
                    <input type="hidden" name="fine" id="retFineInput">
                    <div class="mt-2">
                      <label for="fineStatus" class="form-label fw-semibold">Fine Status:</label>
                      <select name="fine_status" id="fineStatus" class="form-select">
                        <option selected value="No Fine">No Fine</option>
                        <option value="Unpaid">Unpaid</option>
                        <option value="Paid">Paid</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-0">
          <input type="hidden" name="issued_id" id="retIssueId">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="return_btn" class="btn btn-primary px-4">Confirm Return</button>
        </div>

      </form>
    </div>
  </div>
</div>


  <div class="modal fade" id="renewBookModal" tabindex="-1" aria-labelledby="renewBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form id="renewBookForm" method="POST" action="issue-book.php">
        <div class="modal-header text-white" style="background-color: #198754;">
          <h5 class="modal-title">Renew Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="issued_id" id="renewIssuedId">

          <!-- User Info -->
          <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body p-3">
              <h6 class="fw-bold text-secondary mb-2">Borrower Information</h6>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">User ID:</label>
                  <input type="text" id="renewUserId" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">Full Name:</label>
                  <input type="text" id="renewUserName" class="form-control form-control-sm" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Book Info -->
          <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body p-3">
              <h6 class="fw-bold text-secondary mb-2">Book Information</h6>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">Book Title:</label>
                  <input type="text" id="renewBookTitle" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">Author:</label>
                  <input type="text" id="renewBookAuthor" class="form-control form-control-sm" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">ISBN:</label>
                  <input type="text" id="renewBookISBN" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">Borrowed Date:</label>
                  <input type="text" id="renewBorrowedDate" class="form-control form-control-sm" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Renewal Section -->
          <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
              <h6 class="fw-bold text-secondary mb-2">Renewal Details</h6>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">Current Due Date:</label>
                  <input type="text" id="renewCurrentDue" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="form-label small mb-0">New Due Date:</label>
                  <input type="date" name="new_due_date" id="renewNewDue" class="form-control form-control-sm" required>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="renew_btn" class="btn btn-success">Confirm Renewal</button>
        </div>
      </form>
    </div>
  </div>
</div>




    <?php if (!empty($errors)): ?>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var myModal = new bootstrap.Modal(document.getElementById('issueBookModal'));
          myModal.show();
        });
      </script>
    <?php endif; ?>

  <!-- Bootstrap JS and Popper.js -->
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

        fetch('../helper/search-book.php?query=' + encodeURIComponent(query))
          .then(response => response.json())
          .then(data => {
            callback(data);
          });
      },
      onChange: function(value) {
        if (value) {
          fetch('../helper/get-book-info.php?id=' + value)
            .then(res => res.json())
            .then(data => {
              if (data) {
                document.getElementById('bookAuthor').textContent = data[0].author || '';
                document.getElementById('bookSL').textContent = data[0].shelf_location || '';
                document.getElementById('bookCategory').textContent = data[0].category || '';
                document.getElementById('bookCopies').textContent = data[0].available_copies || '';
              }
            })
            .catch(err => {
              console.error('Error fetching book info:', err);
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
        fetch('../helper/search-user.php?query=' + encodeURIComponent(query))
          .then(response => response.json())
          .then(callback)
          .catch(() => callback());
      },
      onChange: function(value) {
        if (value) {
          fetch('../helper/get-user-info.php?id=' + value)
            .then(res => res.json())
            .then(data => {
              document.getElementById('userId').textContent = data.user_id;
              document.getElementById('userEmail').textContent = data.email;
              document.getElementById('userPhone').textContent = data.phone;
            });
        }
      }
    });

    document.getElementById('bookSelect').addEventListener('change', function() {
      if (!this.value) {
        document.getElementById('bookAuthor').textContent = '';
        document.getElementById('bookSL').textContent = '';
        document.getElementById('bookCategory').textContent = '';
        document.getElementById('bookCopies').textContent = '';
      }
    });

    document.getElementById('userSelect').addEventListener('change', function() {
      if (!this.value) {
        document.getElementById('userId').textContent = '';
        document.getElementById('userEmail').textContent = '';
        document.getElementById('userPhone').textContent = '';
      }
    });
    // Handle Return Button Click
      document.querySelectorAll('.btn-return').forEach(btn => {
        btn.addEventListener('click', () => {
          const issueId = btn.dataset.id;
          // console.log(issueId);

          fetch('../helper/get-issued-info.php?id=' + issueId)
            .then(res => res.json())
            .then(data => {
              if (!data) return;
              console.log("hello")

              // Populate Book Info
              document.getElementById('retBookTitle').textContent = data.title;
              document.getElementById('retBookAuthor').textContent = data.author;
              document.getElementById('retBookISBN').textContent = data.isbn;
              document.getElementById('retBookCategory').textContent = data.category;

              // Populate User Info
              document.getElementById('retUserName').textContent = data.full_name;
              document.getElementById('retUserEmail').textContent = data.email;
              document.getElementById('retUserPhone').textContent = data.phone;

              // Borrow Details
              document.getElementById('retBorrowedDate').textContent = data.created_at.split(' ')[0];
              document.getElementById('retDueDate').textContent = data.due_date;
              document.getElementById('retStatus').textContent = data.status;
              document.getElementById('retIssueId').value = data.issued_id;

              // Calculate Fine
              const fine = calculateFine(data.due_date, data.returned_date || new Date());
              document.getElementById('retFine').textContent = fine;
              document.getElementById('retFineInput').value = fine;
              // document.getElementById('fineStatus').value = data.fine_status || 'Unpaid';

              // Show modal
              new bootstrap.Modal(document.getElementById('returnBookModal')).show();
            })
            .catch(err => console.error('Error fetching issue info:', err));
        });
      });

      // Fine calculation function ($2/day overdue)
      function calculateFine(dueDate, currentDate) {
        const due = new Date(dueDate);
        const now = new Date(currentDate);
        const diffTime = now - due;
        const daysOverdue = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        if(daysOverdue <= 0){
          document.getElementById('retDaysDue').textContent = 0
          document.getElementById('daysDueInput').value = 0
        }else{
          document.getElementById('retDaysDue').textContent = daysOverdue
          document.getElementById('daysDueInput').value = daysOverdue;
        }
        return daysOverdue > 0 ? daysOverdue * 2 : 0;
      }

      document.querySelectorAll('.btn-renew').forEach(btn => {
  btn.addEventListener('click', () => {
    const issueId = btn.dataset.id;

    fetch('../helper/get-issued-info.php?id=' + issueId)
      .then(res => res.json())
      .then(data => {
        if (!data) return;

        // Fill hidden fields
        document.getElementById('renewIssuedId').value = data.issued_id;

        // Borrower Info
        document.getElementById('renewUserId').value = data.user_id;
        document.getElementById('renewUserName').value = data.full_name;

        // Book Info
        document.getElementById('renewBookTitle').value = data.title;
        document.getElementById('renewBookAuthor').value = data.author;
        document.getElementById('renewBookISBN').value = data.isbn;
        document.getElementById('renewBorrowedDate').value = data.created_at.split(' ')[0];

        // Renewal Details
        document.getElementById('renewCurrentDue').value = data.due_date;
        document.getElementById('renewNewDue').min = data.due_date; // Prevent past or same day renewal

        new bootstrap.Modal(document.getElementById('renewBookModal')).show();
      });
  });
});


  </script>

</body>
</html>
