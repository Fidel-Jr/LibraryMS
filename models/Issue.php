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

        public function renewBook($data) {
            $errors = [];
            $requiredFields = ['issued_id', 'new_due_date'];

            // 🧾 Validate required fields
            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // ❌ Return validation errors
            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors' => $errors
                ];
            }

            try {
                // ✅ Update due date for the issued book
                $sql = "UPDATE issued_books 
                        SET due_date = :new_due_date, renew_count = COALESCE(renew_count, 0) + 1, updated_at = NOW()
                        WHERE issued_id = :issued_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':new_due_date' => $data['new_due_date'],
                    ':issued_id' => $data['issued_id']
                ]);

                if ($stmt->rowCount() > 0) {
                    return [
                        'success' => true
                    ];
                } else {
                    return [
                        'success' => false,
                        'errors' => ["No matching record found to renew."]
                    ];
                }
            } catch (PDOException $e) {
                error_log("Renew Book Error: " . $e->getMessage());
                return [
                    'success' => false,
                    'errors' => ["Something went wrong while renewing."]
                ];
            }
        }


        public function returnBook($data) 
        {
            $errors = [];
            $requiredFields = ['issued_id', 'days_due', 'fine', 'fine_status'];

            // ✅ Validate required fields — allow 0 for numeric fields like fine and days_due
            foreach ($requiredFields as $field) {
                $value = $data[$field] ?? '';

                // Check for empty but allow numeric 0
                if ($value === '' || $value === null) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // ✅ If there are validation errors, return them
            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors' => $errors
                ];
            }

            try {
                // ✅ Insert return record
                $sql = "INSERT INTO returned_books (issued_id, days_due, fine, fine_status)
                        VALUES (:issued_id, :days_due, :fine, :fine_status)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ":issued_id" => $data['issued_id'],
                    ":days_due" => $data['days_due'],
                    ":fine" => $data['fine'],
                    ":fine_status" => $data['fine_status']
                ]);

                return [
                    'success' => true,
                    'errors' => []
                ];
            } catch (PDOException $e) {
                error_log("Return Book Error: " . $e->getMessage());
                return [
                    'success' => false,
                    'errors' => ['An unexpected error occurred. Please try again.']
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
                            ib.issued_id,
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

        public function getIssuedDetails($id) {
            try {
                $sql = "
                    SELECT 
                        ib.issued_id,
                        ib.user_id,
                        ib.book_id,
                        ib.created_at,
                        ib.due_date,
                        ib.status,
                        b.title,
                        b.author,
                        b.isbn,
                        c.category,
                        u.full_name,
                        u.phone,
                        u.email
                    FROM issued_books ib
                    JOIN books b ON ib.book_id = b.book_id
                    JOIN users u ON ib.user_id = u.user_id
                    JOIN catalog c ON c.book_id = b.book_id
                    WHERE ib.issued_id = :id
                    LIMIT 1";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                $issue = $stmt->fetch(PDO::FETCH_ASSOC);

                return $issue ?: null;
            } catch (PDOException $e) {
                error_log("Error in getIssuedDetails: " . $e->getMessage());
                return null;
            }
        }

    }