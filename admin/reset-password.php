<?php
/**
 * Admin Password Reset Script
 * Run this once, then DELETE this file for security.
 * URL: http://localhost/news-portal/admin/reset-password.php
 */

require_once "../config/database.php";

$newPassword = "admin123";
$hash        = password_hash($newPassword, PASSWORD_BCRYPT);

// Update or insert admin
$check = $conn->query("SELECT id FROM admins LIMIT 1");

if ($check && $check->num_rows > 0) {
    // Update existing
    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = 1");
    $stmt->bind_param("s", $hash);
    $stmt->execute();
    $action = "updated";
} else {
    // Insert new admin
    $name  = "Super Admin";
    $email = "admin@news.com";
    $role  = "superadmin";
    $stmt  = $conn->prepare("INSERT INTO admins (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hash, $role);
    $stmt->execute();
    $action = "created";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 60px auto; background: #f4f4f4; }
        .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        .success { color: #16a34a; font-size: 18px; font-weight: bold; margin-bottom: 12px; }
        code { background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-size: 15px; }
        .warning { margin-top: 20px; padding: 14px; background: #fff7ed; border: 1px solid #f59e0b; border-radius: 6px; font-size: 13px; color: #92400e; }
        a { display: inline-block; margin-top: 16px; background: #d71920; color: white; padding: 10px 22px; border-radius: 6px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <div class="success">✅ Admin password <?= $action; ?> successfully!</div>

        <p><strong>Login Details:</strong></p>
        <p>Email: <code>admin@news.com</code></p>
        <p>Password: <code>admin123</code></p>

        <div class="warning">
            ⚠️ <strong>Security Warning:</strong> Delete this file after logging in!<br>
            <code>c:\xampp\htdocs\news-portal\admin\reset-password.php</code>
        </div>

        <a href="index.php">Go to Admin Login →</a>
    </div>
</body>
</html>
