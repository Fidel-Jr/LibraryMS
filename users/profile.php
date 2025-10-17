<?php
    require_once '../config/database.php';
    require_once '../models/User.php';
    session_start();

    $user = new User($pdo);
    $errors = [];
    $old = [];

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit;
    }

    $userId = $_SESSION['user_id'];
    $userData = $user->getUserInfo($userId);
    $old = $userData['data'] ?? [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'user_id' => $userId,
            'full_name' => $_POST['full_name'] ?? '',
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'current_password' => $_POST['current_password'] ?? '',
            'new_password' => $_POST['new_password'] ?? '',
        ];

        $result = $user->updateUserInfo($data);

        if ($result['success']) {
            $success = "Profile updated successfully!";
            $userData = $user->getUserInfo($userId);
            $old = $userData['data'];
        } else {
            $errors = $result['errors'];
            $old = $data; // retain user inputs
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include "../includes/sidebar.php" ?>
    <div id="content">
        <!-- Navbar -->
        <?php include '../includes/navbar.php'; ?>
        <div class="container-fluid mt-4 bg-white p-4 rounded shadow-sm">
            <h2 class="mb-3">Profile</h2>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (!empty($errors) && !is_array($errors[0])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <?= htmlspecialchars(is_array($errors) ? implode('<br>', $errors) : $errors) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form action="profile.php" method="POST">
                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name"
                            class="form-control <?= in_array('Full name is required.', $errors) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
                    <?php if (in_array('Full name is required.', $errors)): ?>
                        <div class="invalid-feedback">Full name is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username"
                            class="form-control <?= in_array('Username is required.', $errors) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                    <?php if (in_array('Username is required.', $errors)): ?>
                        <div class="invalid-feedback">Username is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                            class="form-control <?= in_array('Email is required.', $errors) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    <?php if (in_array('Email is required.', $errors)): ?>
                        <div class="invalid-feedback">Email is required.</div>
                    <?php elseif (in_array('Invalid email format.', $errors)): ?>
                        <div class="invalid-feedback">Invalid email format.</div>
                    <?php endif; ?>
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" 
                            class="form-control <?= in_array('Phone number is required.', $errors) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    <?php if (in_array('Phone number is required.', $errors)): ?>
                        <div class="invalid-feedback">Phone number is required.</div>
                    <?php endif; ?>
                </div>

                <!-- Current Password -->
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password"
                        class="form-control <?= (in_array('Current password is required.', $errors) || in_array('Incorrect current password.', $errors)) ? 'is-invalid' : '' ?>">
                    <?php if (in_array('Current password is required.', $errors)): ?>
                        <div class="invalid-feedback">Current password is required.</div>
                    <?php elseif (in_array('Incorrect current password.', $errors)): ?>
                        <div class="invalid-feedback">Incorrect current password.</div>
                    <?php endif; ?>
                </div>


                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password"
                        class="form-control <?= (in_array('New password is required.', $errors) || in_array('New password must be at least 6 characters.', $errors)) ? 'is-invalid' : '' ?>">
                    <?php if (in_array('New password is required.', $errors)): ?>
                        <div class="invalid-feedback">New password is required.</div>
                    <?php elseif (in_array('New password must be at least 6 characters.', $errors)): ?>
                        <div class="invalid-feedback">New password must be at least 6 characters.</div>
                    <?php endif; ?>
                </div>


                <!-- Submit Button -->
                <div class="">
                    <button type="submit" name="update_btn" class="btn btn-primary px-4">Update Info</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>

</body>
</html>