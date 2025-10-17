<?php 
    class User {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function register($data)
        {
            $errors = [];
            $requiredFields = ['full_name', 'email', 'username', 'password', 'confirm_password'];

            // ✅ Validate required fields
            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // ✅ Validate email format
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email address.";
            }

            // ✅ Validate password match
            if (($data['password'] ?? '') !== ($data['confirm_password'] ?? '')) {
                $errors[] = "Passwords do not match.";
            }

            // ✅ Validate password strength
            if (!empty($data['password']) && strlen($data['password']) < 6) {
                $errors[] = "Password must be at least 6 characters long.";
            }

            // ❌ Return early if validation fails
            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors' => $errors
                ];
            }

            try {
                // ✅ Check if username or email already exists
                $checkSql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
                $stmt = $this->pdo->prepare($checkSql);
                $stmt->execute([
                    ':username' => $data['username'],
                    ':email' => $data['email']
                ]);

                if ($stmt->fetchColumn() > 0) {
                    return [
                        'success' => false,
                        'errors' => ["Username or email already exists."]
                    ];
                }

                // ✅ Hash the password
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

                // ✅ Default role = User
                $role = $data['role'] ?? 'User';
                $phone = $data['phone'] ?? null;

                // ✅ Insert user into database
                $insertSql = "INSERT INTO users (full_name, username, email, phone, role, password)
                            VALUES (:full_name, :username, :email, :phone, :role, :password)";
                $insertStmt = $this->pdo->prepare($insertSql);
                $insertStmt->execute([
                    ':full_name' => trim($data['full_name']),
                    ':username' => trim($data['username']),
                    ':email' => trim($data['email']),
                    ':phone' => $phone,
                    ':role' => $role,
                    ':password' => $hashedPassword
                ]);

                return [
                    'success' => true,
                    'message' => "Registration successful!"
                ];

            } catch (PDOException $e) {
                error_log("Register Error: " . $e->getMessage());
                return [
                    'success' => false,
                    'errors' => ["Something went wrong. Please try again."]
                ];
            }
        }

        public function login($data) {
            $errors = [];

            // ✅ Step 1: Required field validation (consistent with register)
            $requiredFields = ['username', 'password'];
            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // Return early if validation fails
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            try {
                $username = trim($data['username']);
                $password = trim($data['password']);

                // ✅ Step 2: Check if user exists
                $sql = "SELECT user_id, full_name, username, password, email, role 
                        FROM users 
                        WHERE username = :username AND role = 'User'
                        LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // ✅ Step 3: Invalid username
                if (!$user) {
                    return ['success' => false, 'errors' => ["Invalid username or password."]];
                }

                // ✅ Step 4: Verify password
                if (!password_verify($password, $user['password'])) {
                    return ['success' => false, 'errors' => ["Invalid username or password."]];
                }

                // ✅ Step 5: Create session securely
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name']; // fixed key (was fullname before)
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'] ?? 'user'; // default role if not set
                $_SESSION['is_logged_in'] = true;

                return ['success' => true, 'message' => "Login successful!"];
            } 
            catch (PDOException $e) {
                return ['success' => false, 'errors' => ["Database error: " . $e->getMessage()]];
            }
        }

        public function adminLogin($data) {
            // Initialize error array
            $errors = [];
            $requiredFields = ['username', 'password'];

            // ✅ Validate required fields
            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                }
            }

            // If validation fails, return early
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            $username = trim($data['username']);
            $password = trim($data['password']);

            try {
                // ✅ Step 1: Fetch user
                $sql = "SELECT user_id, full_name, username, password, email, role 
                        FROM users 
                        WHERE username = :username 
                        LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // ✅ Step 2: Handle invalid username
                if (!$user) {
                    return ['success' => false, 'errors' => ["Invalid username or password."]];
                }

                // ✅ Step 3: Check role
                if (strtolower($user['role']) !== 'admin') {
                    return ['success' => false, 'errors' => ["Access denied. Only Admins can log in."]];
                }

                // ✅ Step 4: Verify password
                if (!password_verify($password, $user['password'])) {
                    return ['success' => false, 'errors' => ["Invalid username or password."]];
                }

                // ✅ Step 5: Create user session
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_logged_in'] = true;

                return ['success' => true, 'message' => "Login successful!"];
            } 
            catch (PDOException $e) {
                return ['success' => false, 'errors' => ["Database error: " . $e->getMessage()]];
            }
        }

        public function getUserInfo($id) {
            try {
                $sql = "SELECT user_id, full_name, username, email, phone, role 
                        FROM users 
                        WHERE user_id = :id 
                        LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    return ['success' => false, 'errors' => ['User not found.']];
                }

                return ['success' => true, 'data' => $user];
            } catch (PDOException $e) {
                return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
            }
        }

        public function updateUserInfo($data)
        {
            try {
                $userId = $data['user_id'] ?? null;
                if (!$userId) {
                    return ['success' => false, 'errors' => ['User ID is required.']];
                }

                // Fetch existing user
                $sql = "SELECT password FROM users WHERE user_id = :id LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $userId]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$existing) {
                    return ['success' => false, 'errors' => ['User not found.']];
                }

                // Prepare new values
                $full_name = trim($data['full_name'] ?? '');
                $username = trim($data['username'] ?? '');
                $email = trim($data['email'] ?? '');
                $phone = trim($data['phone'] ?? '');
                $current_password = trim($data['current_password'] ?? '');
                $new_password = trim($data['new_password'] ?? '');

                // Validate required fields
                $requiredFields = ['full_name', 'username', 'email'];
                $errors = [];
                foreach ($requiredFields as $field) {
                    if (empty($$field)) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
                    }
                }

                if (!empty($errors)) {
                    return ['success' => false, 'errors' => $errors];
                }

                // If password fields are provided, verify and update
                $passwordSql = '';
                $params = [
                    ':full_name' => $full_name,
                    ':username' => $username,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':id' => $userId,
                ];

                if (!empty($current_password) || !empty($new_password)) {
                    if (empty($current_password)) {
                        return ['success' => false, 'errors' => ['Current password is required.']];
                    }
                    if (empty($new_password)) {
                        return ['success' => false, 'errors' => ['New password is required.']];
                    }
                    if (strlen($new_password) < 6) {
                        return ['success' => false, 'errors' => ['New password must be at least 6 characters.']];
                    }
                    if (!password_verify($current_password, $existing['password'])) {
                        return ['success' => false, 'errors' => ['Incorrect current password.']];
                    }
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                    $passwordSql = ", password = :password";
                    $params[':password'] = $hashedPassword;
                }

                // Update query
                $updateSql = "
                    UPDATE users 
                    SET full_name = :full_name, username = :username, email = :email, phone = :phone
                    $passwordSql
                    WHERE user_id = :id
                ";

                $updateStmt = $this->pdo->prepare($updateSql);
                $updateStmt->execute($params);

                return ['success' => true, 'message' => 'Profile updated successfully!'];
            } catch (PDOException $e) {
                return ['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]];
            }
        }

        
}