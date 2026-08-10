<?php
// ==================== Admin Authentication ====================
// This page starts the session and checks the admin login details.
$activePage = 'login';
session_start();
include 'config.php';

$message = '';
$messageType = '';

// ==================== Login Form Handling ====================
// Validate the submitted admin credentials and start the session on success.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $valid = false;

    if ($conn) {
        $tableCheck = $conn->query("SHOW TABLES LIKE 'admins'");

        if ($tableCheck && $tableCheck->num_rows > 0) {
            $stmt = $conn->prepare('SELECT password FROM admins WHERE username = ? LIMIT 1');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();

                if (password_verify($password, $hashedPassword)) {
                    $valid = true;
                }
            }

            $stmt->close();
        }
    }

    if (!$valid && $username === 'admin' && $password === '1234') {
        $valid = true;
    }

    if ($valid) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: admin_dashboard.php');
        exit;
    }

    $message = 'Invalid username or password.';
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%230B1F3A'/%3E%3Cpath d='M20 44h24a2 2 0 0 0 2-2V27a2 2 0 0 0-.7-1.6l-12-11a2 2 0 0 0-2.6 0l-12 11A2 2 0 0 0 18 27v15a2 2 0 0 0 2 2Zm6-15h12v10H26Z' fill='%23FFFFFF'/%3E%3Cpath d='M30 18l6 5.5V22h-6v-4Z' fill='%230D6EFD'/%3E%3Cpath d='M25 38h14l-2 8H27Z' fill='%23FFFFFF'/%3E%3C/svg%3E">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="login-page">
    <!-- ==================== Login Page Layout ==================== -->
    <main class="login-shell" role="main">
        <div class="login-brand-panel">
            <div>
                <a class="navbar-brand" href="index.php" style="padding: 0; margin: 0;">
                    <span class="brand-mark"><i class="fa-solid fa-hands-helping"></i></span>
                    <span class="brand-text">
                        <span class="brand-name">Quetta Services Hub</span>
                        <span class="brand-tag">Trusted Home Solutions</span>
                    </span>
                </a>

                <h1>Secure control center for your service team.</h1>
                <p class="lead">Access the admin dashboard with a premium, distraction-free experience designed for fast management and confident decisions.</p>

                <div class="login-brand-list">
                    <div class="item"><i class="fa-solid fa-shield-halved"></i><span>Protected admin access</span></div>
                    <div class="item"><i class="fa-solid fa-bolt"></i><span>Fast service management</span></div>
                    <div class="item"><i class="fa-solid fa-sparkles"></i><span>Elegant experience</span></div>
                </div>
            </div>

            <div class="helper-text">Back to <a href="index.php" style="color: #ffffff; font-weight: 700;">main site</a></div>
        </div>

        <div class="login-card-panel">
            <div class="login-card">
                <div class="text-center mb-4">
                    <div class="brand-mark brand-mark--login mb-3"><i class="fa-solid fa-hands-helping"></i></div>
                    <h2 class="mb-2">Admin Login</h2>
                    <p class="helper-text mb-0">Secure access to your dashboard</p>
                </div>

                <?php if ($message !== '') : ?>
                    <div class="login-alert <?= htmlspecialchars($messageType === 'error' ? 'error' : '') ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input id="username" name="username" type="text" class="form-control" placeholder="Enter username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input id="password" name="password" type="password" class="form-control" placeholder="Enter password" required>
                    </div>

                    <button class="btn-submit" type="submit"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                </form>

                <p class="helper-text mt-3 mb-0 text-center">Default login: admin / 1234</p>
            </div>
        </div>
    </main>
</body>
</html>
