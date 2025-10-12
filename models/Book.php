<?php 
    class Book {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function addBook($data)
        {
            $errors = [];

            // Validate required fields
            $requiredFields = ['title', 'author', 'isbn', 'year_published', 'shelf_location', 'category', 'condition', 'status'];
            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // ✅ Additional specific validations
            if (!empty($data['year_published']) && !is_numeric($data['year_published'])) {
                $errors['year_published'] = "Year Published must be a number.";
            }

            // If errors found, return them immediately
            if (!empty($errors)) {
                return [
                    "success" => false,
                    "errors" => $errors
                ];
            }

            try {
                // Start transaction
                $this->pdo->beginTransaction();

                // Insert into books table
                $sqlBook = "INSERT INTO books (title, author, isbn, year_published)
                            VALUES (:title, :author, :isbn, :year_published)";
                $stmtBook = $this->pdo->prepare($sqlBook);
                $stmtBook->execute([
                    ":title" => $data['title'],
                    ":author" => $data['author'],
                    ":isbn" => $data['isbn'] ?? null,
                    ":year_published" => $data['year_published'] ?? null
                ]);

                // Get book_id
                $bookId = $this->pdo->lastInsertId();

                // Insert into catalog table
                $sqlCatalog = "INSERT INTO catalog (book_id, shelf_location, category, book_condition, status)
                            VALUES (:book_id, :shelf_location, :category, :condition, :status)";
                $stmtCatalog = $this->pdo->prepare($sqlCatalog);
                $stmtCatalog->execute([
                    ":book_id" => $bookId,
                    ":shelf_location" => $data['shelf_location'] ?? null,
                    ":category" => $data['category'] ?? null,
                    ":condition" => $data['condition'],
                    ":status" => $data['status']
                ]);

                // Commit if both succeed
                $this->pdo->commit();
                return [
                    "success" => true,
                    "errors" => []
                ];

            } catch (PDOException $e) {
                // Rollback on error
                $this->pdo->rollBack();
                error_log("Add Book with Catalog Error: " . $e->getMessage());
                return [
                    "success" => false,
                    "errors" => ["Database error: " . $e->getMessage()]
                ];
            }
        }



        public function updateBook($data) {
            $errors = [];

            // ✅ Validate required fields
            $requiredFields = [
                'title', 'author', 'isbn', 'year_published',
                'shelf_location', 'category', 'condition', 'status'
            ];

            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // ✅ Additional specific validations
            if (!empty($data['year_published']) && !is_numeric($data['year_published'])) {
                $errors['year_published'] = "Year Published must be a number.";
            }

            if (!empty($errors)) {
                return [
                    "success" => false,
                    "errors" => $errors
                ];
            }

            try {
                // ✅ Begin transaction
                $this->pdo->beginTransaction();

                // Update books table
                $sqlBook = "UPDATE books 
                            SET title = :title, 
                                author = :author, 
                                isbn = :isbn, 
                                year_published = :year_published
                            WHERE book_id = :book_id";
                $stmtBook = $this->pdo->prepare($sqlBook);
                $stmtBook->execute([
                    ':title' => $data['title'],
                    ':author' => $data['author'],
                    ':isbn' => $data['isbn'],
                    ':year_published' => $data['year_published'],
                    ':book_id' => $data['book_id']
                ]);

                // Update catalog table
                $sqlCatalog = "UPDATE catalog 
                            SET shelf_location = :shelf_location,
                                category = :category,
                                status = :status,
                                book_condition = :condition
                            WHERE catalog_id = :catalog_id";
                $stmtCatalog = $this->pdo->prepare($sqlCatalog);
                $stmtCatalog->execute([
                    ':shelf_location' => $data['shelf_location'],
                    ':category' => $data['category'],
                    ':status' => $data['status'],
                    ':condition' => $data['condition'],
                    ':catalog_id' => $data['catalog_id']
                ]);

                $this->pdo->commit();
                return ["success" => true];

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                error_log("Update Book Error: " . $e->getMessage());
                return [
                    "success" => false,
                    "errors" => ["database" => "Failed to update book: " . $e->getMessage()]
                ];
            }
        }

        
        public function deleteBook($book_id)
        {
            if (!isset($book_id)) {
                return false;
            }

            try {
                $sql = "UPDATE books SET is_archived = 1 WHERE book_id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $book_id]);
                return true;
            } catch (PDOException $e) {
                error_log("Delete Book Error: " . $e->getMessage());
                return false;
            }
        }

        public function getBook($book_id){
            if (empty($book_id)) {
                return [];
            }
            try {
                $sql = "SELECT * FROM books WHERE book_id = :book_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ":book_id" => $book_id
                ]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Get Books Error: " . $e->getMessage());
                return [];
            }
        }

        public function getAllBooks() {
            try {
                $sql = "SELECT 
                            b.book_id,
                            b.title,
                            b.author,
                            b.isbn,
                            b.year_published,
                            c.catalog_id,
                            c.shelf_location,
                            c.category,
                            c.book_condition,
                            c.status
                        FROM books b
                        INNER JOIN catalog c 
                            ON b.book_id = c.book_id
                        WHERE b.is_archived = 0
                        ORDER BY b.created_at DESC";
                
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Get Books Error: " . $e->getMessage());
                return [];
            }
        }

        
    }