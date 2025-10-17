<?php
    session_start();

    // ✅ Step 1: Check if user is logged in
    if (isset($_SESSION['is_logged_in'])) {

        // ✅ Step 2: Unset all session variables
        $_SESSION = [];

        // ✅ Step 3: Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // ✅ Step 4: Destroy the session
        session_destroy();
    }

    // ✅ Step 5: Redirect to login page
    header("Location: index.php");
    exit;
?>