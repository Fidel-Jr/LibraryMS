    // MANAGE BOOK JS FUNCTIONS

    new DataTable('#manage-book-table-example');

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


    // ISSUE BOOK JS FUNCTIONS
    new DataTable('#issue-book-example');

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