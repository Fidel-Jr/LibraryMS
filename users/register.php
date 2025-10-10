<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        .register-card {
            min-width: 400px;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(80, 80, 120, 0.08);
            background: #fff;
        }
        .register-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
            color: var(--secondary-text);
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(165, 124, 242, 0.15);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-dark);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: #888;
        }
        .register-footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .register-footer a:hover {
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
            .register-card {
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
        <div class="card p-4 register-card">
            <div class="register-title">Create Your Account</div>
            <form action="" method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Choose a username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>
            </form>
            <div class="register-footer">
                Already have an account? <a href="../index.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>