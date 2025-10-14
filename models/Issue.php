<?php
    class Issue {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function issueBook($data) {
            
                $errors = [];
                $requiredFields = ['book_id', 'user_id', 'due_date'];

                // 🧾 Validate required fields
                foreach ($requiredFields as $field) {
                    if (empty(trim($data[$field] ?? ''))) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                    }
                }

                // ❌ If there are errors, return them instead of inserting
                if (!empty($errors)) {
                    return [
                        'success' => false,
                        'errors' => $errors
                    ];
                }
                try {
                    // ✅ If validation passed → insert record
                    $sql = "INSERT INTO issued_books (book_id, user_id, due_date)
                            VALUES (:book_id, :user_id, :due_date)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([
                        ":book_id" => $data['book_id'],
                        ":user_id" => $data['user_id'],
                        ":due_date" => $data['due_date'],
                    ]);

                    return [
                        'success' => true
                    ];
                } catch (PDOException $e) {
                    error_log("Issue Book Error: " . $e->getMessage());
                    return [
                        'success' => false,
                        'errors' => ["Something went wrong. Please try again."]
                    ];
                }
        }


        public function getAllBorrowedBooks(){

            try {
                $sql = "SELECT
                            b.book_id,
                            b.title,
                            b.author,
                            b.isbn,
                            u.user_id,
                            u.full_name,
                            ib.created_at,
                            ib.due_date,
                            ib.status
                        FROM issued_books ib
                        INNER JOIN books b 
                            ON ib.book_id = b.book_id
                        INNER JOIN users u
                            ON ib.user_id = u.user_id
                        WHERE ib.status = 'Borrowed'
                        ORDER BY b.created_at DESC";
                
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Get All Borrowed Books Error: " . $e->getMessage());
                return [];
            }

        }


    }