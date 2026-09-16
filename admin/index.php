<?php

session_start();

require_once "../includes/functions.php";

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect("dashboard.php");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {

        global $conn;

        $stmt = $conn->prepare("
            SELECT id, name, email, password, role
            FROM admins
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin  = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {

            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_role'] = $admin['role'];

            redirect("dashboard.php");

        } else {
            $error = "Invalid email or password.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login - News Portal</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body class="admin-login-page">

    <div class="login-wrapper">

        <div class="login-card">

            <div class="login-logo">
                <i class="fa-solid fa-newspaper"></i>
                <span>NewsPortal Admin</span>
            </div>

            <h2 class="login-title">Welcome Back</h2>

            <p class="login-subtitle">Sign in to manage your news portal</p>


            <?php if (!empty($error)): ?>
                <div class="login-alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= clean($error); ?>
                </div>
            <?php endif; ?>


            <form method="POST" action="index.php" id="loginForm">

                <div class="form-group">

                    <label for="email">Email Address</label>

                    <div class="input-with-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="admin@news.com"
                            value="<?= clean($_POST['email'] ?? ''); ?>"
                            required
                        >
                    </div>

                </div>

                <div class="form-group">

                    <label for="password">Password</label>

                    <div class="input-with-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Enter your password"
                            required
                        >
                        <button
                            type="button"
                            class="toggle-pass"
                            onclick="togglePassword()"
                            id="togglePassBtn"
                        >
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                </div>

                <button
                    type="submit"
                    class="login-btn"
                    id="loginSubmitBtn"
                >
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sign In
                </button>

            </form>

            <div class="login-demo-hint">
                <i class="fa-solid fa-circle-info"></i>
                Demo: <strong>admin@news.com</strong> / <strong>password</strong>
            </div>

        </div>

    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pass.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

</html>
