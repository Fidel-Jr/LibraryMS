<?php 
    class Book {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function addBook($data) {
            try {
                $sql = "INSERT INTO books (title, author, isbn, edition, year_published) 
                        VALUES (:title, :author, :isbn, :edition, :year_published)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ":title" => $data['title'],
                    ":author" => $data['author'],
                    ":isbn" => $data['isbn'] ?? null,
                    ":edition" => $data['edition'] ?? null,
                    ":year_published" => $data['year_published'] ?? null
                ]);

                return true; // success
            } catch (PDOException $e) {
                // Log the error
                error_log("Book Add Error: " . $e->getMessage()); 
                return false;
            }
        }

         public function updateBook($book_id, $data) {
            try{
                $sql = "UPDATE books 
                    SET title = :title, author = :author, isbn = :isbn,
                        edition = :edition, year_published = :year_published
                    WHERE book_id = :book_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ":title" => $data['title'],
                    ":author" => $data['author'],
                    ":isbn" => $data['isbn'],
                    ":edition" => $data['edition'],
                    ":year_published" => $data['year_published'],
                    ":book_id" => $book_id
                ]);
                return true;
            } catch (PDOException $e){
                 return false;
            }
           
        }   
        
        public function deleteBook($book_id)
        {
            if (!isset($book_id)) {
                return false;
            }

            try {
                $sql = "DELETE FROM books WHERE book_id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $book_id]);
                return $stmt->rowCount() > 0; // true if something was deleted
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
                $sql = "SELECT * FROM books ORDER BY created_at DESC";
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Get Books Error: " . $e->getMessage());
                return [];
            }
        }
        
    }