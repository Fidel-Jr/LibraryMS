<?php 
    class Book {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function addBook($data) {
            try {
                $sql = "INSERT INTO books (title, author, isbn, edition, publisher, year_published) 
                        VALUES (:title, :author, :isbn, :edition, :publisher, :year_published)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ":title" => $data['title'],
                    ":author" => $data['author'],
                    ":isbn" => $data['isbn'] ?? null,
                    ":edition" => $data['edition'] ?? null,
                    ":publisher" => $data['publisher'] ?? null,
                    ":year_published" => $data['year_published'] ?? null
                ]);

                return true; // success
            } catch (PDOException $e) {
                // Log the error
                error_log("Book Add Error: " . $e->getMessage()); 
                return false;
            }
        }
        
    }