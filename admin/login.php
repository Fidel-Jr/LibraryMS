<?php
require_once '../config/database.php';
require_once '../models/User.php';
session_start();

$user = new User($pdo);

$errors = [];
$success = "";

// ✅ Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["login_btn"])) {
    $result = $user->adminLogin($_POST);

    if ($result['success']) {
        header("Location: dashboard.php");
        exit;
    } else {
        $errors = $result['errors'] ?? [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, #f5f5f5 100%);
        }
        .login-card {
            min-width: 340px;
            max-width: 380px;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(80, 80, 120, 0.08);
            background: #fff;
        }
        .login-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.0rem;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(4, 4, 123, 0.15);
        }
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: #888;
        }
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .web-title-bar {
            width: 100%;
            padding: 1rem 2rem 0.5rem 2rem;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
        }
        .web-title-text {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--primary-light);
            letter-spacing: 1px;
        }
        @media (max-width: 500px) {
            .login-card {
                min-width: 100%;
                max-width: 100%;
                border-radius: 0;
                box-shadow: none;
            }
            .web-title-bar {
                padding: 1rem 1rem 0.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="web-title-bar">
        <span class="web-title-text">LibraryMS</span>
    </div>

    <div class="min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4 login-card">
            <div class="login-title">Library</div>
            <div class="login-title">Login as Admin</div>

            <!-- ✅ Show Errors -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control <?= in_array('Username is required.', $errors ?? []) ? 'is-invalid' : '' ?>"
                        placeholder="Enter your username" 
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control <?= in_array('Password is required.', $errors ?? []) ? 'is-invalid' : '' ?>"
                        placeholder="Enter your password">
                </div>

                <button type="submit" name="login_btn" class="btn btn-primary w-100 mt-3">Login</button>
            </form>

            <!-- <div class="login-footer">
                Don't have an account? <a href="users/register.php">Become A Member</a>
            </div> -->
        </div>
    </div>
</body>
</html>
