<?php
// ==================== Edit Service Logic ====================
// This page loads one service from the database so the admin can update its details.
$activePage = 'edit';
session_start();
include 'config.php';

// Check whether the admin is logged in before showing the edit form.
// If not, redirect to the login page for security.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$serviceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$messageType = '';

// ==================== Load Service for Editing ====================
// Read the selected service from the URL using its ID.
// The result is then used to pre-fill the form with the current values.
$service = null;
if ($serviceId > 0 && $conn) {
    $stmt = $conn->prepare('SELECT id, name, description, price, image FROM services WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $service = $result->fetch_assoc();
    }
    $stmt->close();
}

// ==================== Update Service ====================
// This block reads the edited values from the form and updates the matching service row in the database.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');

    if ($serviceId > 0 && $name !== '' && $description !== '' && $price !== '') {
        $stmt = $conn->prepare('UPDATE services SET name = ?, description = ?, price = ? WHERE id = ?');
        $stmt->bind_param('sssi', $name, $description, $price, $serviceId);

        if ($stmt->execute()) {
            $message = 'Service updated successfully.';
            $messageType = 'success';
            $service = ['id' => $serviceId, 'name' => $name, 'description' => $description, 'price' => $price, 'image' => $service['image'] ?? ''];
        } else {
            $message = 'Unable to update service.';
            $messageType = 'error';
        }

        $stmt->close();
    } else {
        $message = 'Please fill in all fields.';
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom" aria-label="Primary navigation">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand" href="index.php">
                <span class="brand-mark"><i class="fa-solid fa-hands-helping"></i></span>
                <span class="brand-text">
                    <span class="brand-name">Quetta Services Hub</span>
                    <span class="brand-tag">Trusted Home Solutions</span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-list-check"></i> Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="book.php"><i class="fa-solid fa-calendar-check"></i> Book</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'edit' ? 'active' : '' ?>" href="admin_dashboard.php"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>
                </ul>
                <a class="btn btn-contact" href="book.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
            </div>
        </div>
    </nav>
    </header>

    <!-- ==================== Edit Service Page ==================== -->
    <main class="page-shell">
        <div class="page-card page-card--soft page-shell-card page-shell-card--narrow">
            <div class="hero-panel hero-panel--compact">
                <div class="stack-sm">
                    <div class="icon-badge"><i class="fa-solid fa-pen-to-square"></i></div>
                    <h2>Edit Service</h2>
                    <p>Refine service details while preserving the current workflow.</p>
                </div>
            </div>

            <?php if ($message !== '') : ?>
                <div class="alert <?= htmlspecialchars($messageType === 'success' ? 'alert-success' : 'alert-error') ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="card-surface" style="padding: 24px;">
                <?php if ($service) : ?>
                    <form method="post" class="form-grid">
                        <input type="hidden" name="service_id" value="<?= (int)$service['id'] ?>">

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="field-group">
                                    <label class="form-label" for="name">Name</label>
                                    <input id="name" name="name" type="text" value="<?= htmlspecialchars($service['name']) ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="field-group">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea id="description" name="description" required><?= htmlspecialchars($service['description']) ?></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="field-group">
                                    <label class="form-label" for="price">Price</label>
                                    <input id="price" name="price" type="text" value="<?= htmlspecialchars($service['price']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="actions mt-3">
                            <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Update Service</button>
                            <a class="btn btn-secondary" href="admin_dashboard.php"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
                        </div>
                    </form>
                <?php else : ?>
                    <div class="empty-state">
                        <p class="mb-0">Service not found.</p>
                    </div>
                    <div style="margin-top: 16px;">
                        <a class="btn btn-primary" href="admin_dashboard.php"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer-premium" role="contentinfo">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="footer-title">Company Information</h5>
                    <p class="mb-2">Quetta Services Hub provides reliable, premium home solutions with professionalism and care.</p>
                    <div class="social-links mt-3">
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="footer-title">Quick Links</h5>
                    <div class="d-flex flex-column">
                        <a class="footer-link" href="index.php"><i class="fa-solid fa-chevron-right"></i> Home</a>
                        <a class="footer-link" href="book.php"><i class="fa-solid fa-chevron-right"></i> Book Service</a>
                        <a class="footer-link" href="admin_login.php"><i class="fa-solid fa-chevron-right"></i> Admin Login</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="footer-title">Services</h5>
                    <div class="d-flex flex-column">
                        <a class="footer-link" href="index.php"><i class="fa-solid fa-screwdriver-wrench"></i> Home Repairs</a>
                        <a class="footer-link" href="index.php"><i class="fa-solid fa-broom"></i> Cleaning</a>
                        <a class="footer-link" href="index.php"><i class="fa-solid fa-plug-circle-bolt"></i> Electrical</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="footer-title">Contact Information</h5>
                    <div class="footer-contact">
                        <p><i class="fa-solid fa-envelope"></i> info@quettaserviceshub.com</p>
                        <p><i class="fa-solid fa-phone"></i> +92 300 1234567</p>
                        <p><i class="fa-solid fa-location-dot"></i> Quetta, Balochistan, Pakistan</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span>© 2026 Quetta Services Hub. All rights reserved.</span>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top" aria-label="Back to top"><i class="fa-solid fa-arrow-up" aria-hidden="true"></i></a>
</body>
</html>
