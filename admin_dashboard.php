<?php
// ==================== Admin Dashboard Logic ====================
// This page is the main dashboard for admins.
// It checks whether the user is logged in before showing management features.
$activePage = 'dashboard';
session_start();
include 'config.php';

// If the admin is not logged in, redirect them back to the login page.
// This protects the admin dashboard from public access.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// This folder is used to store uploaded service images for the dashboard.
// If the directory does not exist, the code creates it automatically.
$uploadDir = __DIR__ . '/images';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$message = '';
$messageType = '';

// ==================== Add Service ====================
// This block handles the "Add Service" form in the dashboard.
// It reads the values from the form, uploads the image if one is selected, and inserts a new service into the services table.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_service') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . '/' . $fileName;
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

        if (in_array($_FILES['image']['type'], $allowedTypes, true) && move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $fileName;
        } else {
            $message = 'Image upload failed. Please use a valid image file.';
            $messageType = 'error';
        }
    }

    if ($message === '' && $name !== '' && $description !== '' && $price !== '') {
        $stmt = $conn->prepare('INSERT INTO services (name, description, price, image) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $description, $price, $imagePath);

        if ($stmt->execute()) {
            $message = 'Service added successfully.';
            $messageType = 'success';
        } else {
            $message = 'Unable to save service.';
            $messageType = 'error';
        }

        $stmt->close();
    } elseif ($message === '') {
        $message = 'Please fill in all service fields.';
        $messageType = 'error';
    }
}

// ==================== Delete Service ====================
// This block deletes a service by its ID from the database.
// It is triggered when an admin clicks the Delete button in the dashboard table.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_service') {
    $serviceId = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;

    if ($serviceId > 0) {
        $stmt = $conn->prepare('DELETE FROM services WHERE id = ?');
        $stmt->bind_param('i', $serviceId);

        if ($stmt->execute()) {
            $message = 'Service deleted successfully.';
            $messageType = 'success';
        } else {
            $message = 'Unable to delete service.';
            $messageType = 'error';
        }

        $stmt->close();
    }
}
// ==================== Delete Contact Message ====================
// Allows the admin to remove a contact form message.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) &&
    $_POST['action'] === 'delete_contact'
) {
    $contactId = isset($_POST['contact_id'])
        ? (int)$_POST['contact_id']
        : 0;

    if ($contactId > 0) {
        $stmt = $conn->prepare(
            'DELETE FROM contact_messages WHERE id = ?'
        );

        $stmt->bind_param('i', $contactId);

        if ($stmt->execute()) {
            $message = 'Contact message deleted successfully.';
            $messageType = 'success';
        } else {
            $message = 'Unable to delete contact message.';
            $messageType = 'error';
        }

        $stmt->close();
    }
}
// ==================== Delete Booking ====================
// This block allows the admin to delete a customer booking/message.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) &&
    $_POST['action'] === 'delete_booking'
) {
    $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;

    if ($bookingId > 0) {
        $stmt = $conn->prepare('DELETE FROM bookings WHERE id = ?');
        $stmt->bind_param('i', $bookingId);

        if ($stmt->execute()) {
            $message = 'Booking deleted successfully.';
            $messageType = 'success';
        } else {
            $message = 'Unable to delete booking.';
            $messageType = 'error';
        }

        $stmt->close();
    }
}
// ==================== Fetch Services and Bookings ====================
// The dashboard reads services and recent bookings from the database.
// These values are used to show counts and tables on the admin screen.
$services = [];
if ($conn) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'services'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $result = $conn->query('SELECT * FROM services ORDER BY id DESC');
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
    } else {
        $conn->query(
            "CREATE TABLE services (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT NOT NULL,
                price VARCHAR(50) NOT NULL,
                image VARCHAR(255) NOT NULL DEFAULT ''
            )"
        );
    }
}

$bookings = [];
if ($conn) {
    $bookingTableCheck = $conn->query("SHOW TABLES LIKE 'bookings'");
    if ($bookingTableCheck && $bookingTableCheck->num_rows > 0) {
        $bookingResult = $conn->query('SELECT * FROM bookings ORDER BY id DESC LIMIT 5');
        if ($bookingResult) {
            while ($bookingRow = $bookingResult->fetch_assoc()) {
                $bookings[] = $bookingRow;
            }
        }
    }
}

// ==================== Fetch Contact Messages ====================
// Gets the latest contact form messages for the admin dashboard.
$contactMessages = [];

if ($conn) {
    $contactTableCheck = $conn->query("SHOW TABLES LIKE 'contact_messages'");

    if ($contactTableCheck && $contactTableCheck->num_rows > 0) {
        $contactResult = $conn->query(
            'SELECT * FROM contact_messages ORDER BY id DESC LIMIT 10'
        );

        if ($contactResult) {
            while ($contactRow = $contactResult->fetch_assoc()) {
                $contactMessages[] = $contactRow;
            }
        }
    }
}

// ==================== Fetch Total Counts for Statistics ====================
// These counts are used in the stat cards at the top of the dashboard.
$totalBookingsCount = 0;
if ($conn) {
    $bookingCountCheck = $conn->query("SHOW TABLES LIKE 'bookings'");
    if ($bookingCountCheck && $bookingCountCheck->num_rows > 0) {
        $countResult = $conn->query('SELECT COUNT(*) AS total FROM bookings');
        if ($countResult && $countRow = $countResult->fetch_assoc()) {
            $totalBookingsCount = (int)($countRow['total'] ?? 0);
        }
    }
}

$totalContactCount = 0;
if ($conn) {
    $contactCountCheck = $conn->query("SHOW TABLES LIKE 'contact_messages'");
    if ($contactCountCheck && $contactCountCheck->num_rows > 0) {
        $contactCountResult = $conn->query('SELECT COUNT(*) AS total FROM contact_messages');
        if ($contactCountResult && $contactCountRow = $contactCountResult->fetch_assoc()) {
            $totalContactCount = (int)($contactCountRow['total'] ?? 0);
        }
    }
}

// ==================== Logout ====================
// This block ends the admin session and sends the user back to the login page.
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* ==================== Admin Dashboard Enhancements ==================== */

        /* --- Stat Cards --- */
        .admin-stat-card {
            position: relative;
            overflow: hidden;
            border-left: 4px solid transparent;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .admin-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(10, 35, 66, 0.12);
        }
        .admin-stat-card--services  { border-left-color: #0d6efd; }
        .admin-stat-card--bookings  { border-left-color: #198754; }
        .admin-stat-card--messages  { border-left-color: #6f42c1; }
        .admin-stat-card--status    { border-left-color: #f59e0b; }

        .admin-stat-card::after {
            content: '';
            position: absolute;
            top: -24px;
            right: -24px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.04);
            pointer-events: none;
        }
        .admin-stat-card h3 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.1;
        }
        .admin-stat-card p {
            font-size: 0.84rem;
            color: var(--muted);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 600;
        }

        /* --- Section Headers --- */
        .admin-section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
            margin-bottom: -4px;
        }
        .admin-section-header h5 {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
            font-weight: 700;
            margin: 0;
        }
        .admin-section-header hr {
            flex: 1;
            margin: 0;
            border: 0;
            border-top: 1px solid var(--border);
        }

        /* --- Admin Card --- */
        .admin-card .card-title h4 {
            display: flex;
            align-items: center;
        }

        /* --- Empty States --- */
        .empty-state {
            padding: 42px 24px;
            text-align: center;
        }
        .empty-state__icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(13,110,253,0.10), rgba(111,66,193,0.08));
            color: #0d6efd;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        /* --- Tables --- */
        .admin-table th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            font-weight: 700;
            padding: 12px 14px;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        .admin-table td {
            padding: 13px 14px;
            font-size: 0.9rem;
            vertical-align: middle;
        }
        .admin-table tbody tr {
            transition: background 0.15s ease;
        }
        .admin-table tbody tr:hover {
            background: rgba(13, 110, 253, 0.03);
        }

        /* --- Action Buttons --- */
        .action-btn {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 0.82rem;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        .admin-table .btn-outline-danger {
            border-color: rgba(220,53,69,0.25);
            color: #dc3545;
        }
        .admin-table .btn-outline-danger:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .admin-table .btn-outline-primary {
            border-color: rgba(13,110,253,0.25);
            color: #0d6efd;
        }
        .admin-table .btn-outline-primary:hover {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        /* --- Forms --- */
        .modern-form .form-control {
            padding: 11px 14px;
            font-size: 0.93rem;
        }
        .modern-form .form-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 6px;
        }
        .modern-form .btn-primary {
            padding: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 14px;
            letter-spacing: 0.02em;
        }

        /* --- Service Directory Image Thumbnails --- */
        .admin-table img {
            border: 1px solid var(--border);
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        }

        /* --- Alerts --- */
        .alert-success,
        .alert-error {
            border-radius: 14px;
            font-weight: 600;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
        }
        .alert-success {
            background: linear-gradient(135deg, rgba(25,135,84,0.10), rgba(25,135,84,0.05));
            color: #0f5132;
        }
        .alert-error {
            background: linear-gradient(135deg, rgba(220,53,69,0.10), rgba(220,53,69,0.05));
            color: #842029;
        }

        /* --- Sidebar --- */
        .admin-sidebar .nav-link {
            font-size: 0.92rem;
            padding: 10px 14px;
        }

        /* --- Badge Improvements --- */
        .badge-count {
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }

        /* --- Dashboard Footer --- */
        .admin-footer {
            text-align: center;
            padding: 18px 24px;
            font-size: 0.82rem;
            color: var(--muted);
            border-top: 1px solid var(--border);
            margin-top: auto;
        }
        .admin-footer a {
            color: var(--secondary);
            font-weight: 600;
            text-decoration: none;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .admin-stat-card h3 {
                font-size: 1.35rem;
            }
            .admin-table td,
            .admin-table th {
                padding: 10px 8px;
                font-size: 0.83rem;
            }
            .admin-card {
                padding: 16px;
            }
            .admin-topbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px 18px;
            }
            .admin-topbar .search-wrap {
                min-width: 100%;
            }
            .empty-state {
                padding: 28px 16px;
            }
        }
    </style>
</head>
<body class="admin-dashboard-body">
    <div class="admin-dashboard-shell" role="main">
        <!-- ==================== Admin Sidebar ==================== -->
        <aside class="admin-sidebar" aria-label="Admin sidebar">
            <div>
                <div class="sidebar-brand mb-4">
                    <span class="brand-mark"><i class="fa-solid fa-hands-helping"></i></span>
                    <div>
                        <div class="brand-title">Quetta Services Hub</div>
                        <div class="brand-subtitle">Admin Control</div>
                    </div>
                </div>

                <nav class="d-flex flex-column gap-2">
                    <a class="nav-link active" href="admin_dashboard.php"><i class="fa-solid fa-gauge-high"></i> Overview</a>
                    <a class="nav-link" href="admin_dashboard.php#serviceTable">
    <i class="fa-solid fa-pen"></i> Manage Services
</a>
                    <a class="nav-link" href="book.php"><i class="fa-solid fa-calendar-check"></i> Booking Page</a>
                    <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Public Site</a>
                </nav>
            </div>

            <div class="sidebar-footer">
                <p class="mb-2">Need a quick view of live bookings?</p>
                <a class="btn btn-outline-light btn-sm" href="book.php"><i class="fa-solid fa-arrow-up-right-from-square"></i> Open Booking Form</a>
            </div>
        </aside>

        <!-- ==================== Admin Content ==================== -->
        <main class="admin-content" role="main">
            <header class="admin-topbar">
                <div>
                    <p class="section-subtitle mb-1">Welcome back</p>
                    <h2 class="mb-0">Dashboard Overview</h2>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-stretch gap-2">
                    <div class="search-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="serviceSearch" placeholder="Search services">
                    </div>
                    <form method="post">
                        <input type="hidden" name="logout" value="1">
                        <button class="btn btn-outline-primary" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                    </form>
                </div>
            </header>

            <?php if ($message !== '') : ?>
                <div class="alert <?= htmlspecialchars($messageType === 'success' ? 'alert-success' : 'alert-error') ?>">
                    <i class="fa-solid <?= $messageType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <!-- ==================== Statistics Cards ==================== -->
            <section class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card admin-stat-card--services h-100">
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(13,110,253,0.16), rgba(13,110,253,0.08));">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <h3><?= count($services) ?></h3>
                            <p>Total Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card admin-stat-card--bookings h-100">
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(25,135,84,0.16), rgba(25,135,84,0.08));">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3><?= $totalBookingsCount ?></h3>
                            <p>Total Bookings</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card admin-stat-card--messages h-100">
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(111,66,193,0.16), rgba(111,66,193,0.08));">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h3><?= $totalContactCount ?></h3>
                            <p>Contact Messages</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card admin-stat-card--status h-100">
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245,158,11,0.16), rgba(245,158,11,0.08));">
                            <i class="fa-solid fa-signal"></i>
                        </div>
                        <div>
                            <h3><?= count($services) > 0 ? 'Live' : 'Draft' ?></h3>
                            <p>Catalog Status</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== Bookings & Add Service ==================== -->
            <div class="admin-section-header">
                <h5><i class="fa-solid fa-grid-2 me-1"></i> Management</h5>
                <hr>
            </div>

            <section class="row g-4">
                <div class="col-12 col-xl-7">
                    <div class="admin-card h-100">
                        <div class="card-title">
                            <div>
                                <h4><i class="fa-solid fa-calendar-check me-2 text-muted"></i>Recent Bookings</h4>
                                <p class="card-subtitle">Latest client requests at a glance</p>
                            </div>
                            <span class="badge bg-primary badge-count"><?= count($bookings) ?> New</span>
                        </div>

                        <?php if (!empty($bookings)) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle admin-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Date</th>
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking) : ?>
                                            <tr>
                                                <td><?= (int)($booking['id'] ?? 0) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['name'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['phone'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['address'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['booking_date'] ?? '')) ?></td>
                                                <td>
                                                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                                        <input type="hidden" name="action" value="delete_booking">
                                                        <input type="hidden" name="booking_id" value="<?= (int)($booking['id'] ?? 0) ?>">
                                                        <button class="btn action-btn btn-outline-danger" type="submit" title="Delete Booking">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="empty-state">
                                <div class="empty-state__icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                                <p class="mb-1 fw-semibold">No bookings yet</p>
                                <p class="mb-0 text-muted small">Bookings submitted by customers will appear here.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="admin-card h-100">
                        <div class="card-title">
                            <div>
                                <h4><i class="fa-solid fa-plus-circle me-2 text-muted"></i>Add New Service</h4>
                                <p class="card-subtitle">Create a polished service entry with imagery and pricing</p>
                            </div>
                        </div>

                        <form method="post" enctype="multipart/form-data" class="modern-form">
                            <input type="hidden" name="action" value="add_service">

                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" name="name" type="text" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="price">Price</label>
                                <input class="form-control" id="price" name="price" type="text" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="image">Image</label>
                                <input class="form-control" id="image" name="image" type="file" accept="image/*">
                            </div>

                            <button class="btn btn-primary w-100" type="submit"><i class="fa-solid fa-plus"></i> Add Service</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- ==================== Service Directory ==================== -->
            <div class="admin-section-header">
                <h5><i class="fa-solid fa-folder-open me-1"></i> Services</h5>
                <hr>
            </div>

            <section class="admin-card">
                <div class="card-title">
                    <div>
                        <h4><i class="fa-solid fa-list-ul me-2 text-muted"></i>Service Directory</h4>
                        <p class="card-subtitle">Review, edit, and remove services from one workspace</p>
                    </div>
                    <span class="badge bg-primary badge-count"><?= count($services) ?> Total</span>
                </div>

                <?php if (!empty($services)) : ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle admin-table" id="serviceTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service) : ?>
                                    <tr>
                                        <td><?= (int)$service['id'] ?></td>
                                        <td><?= htmlspecialchars($service['name']) ?></td>
                                        <td><?= htmlspecialchars($service['description']) ?></td>
                                        <td><?= htmlspecialchars($service['price']) ?></td>
                                        <td>
                                            <?php
$imageFile = trim((string)($service['image'] ?? ''));

if ($imageFile !== '') {
    // Only keep the filename if database contains images/filename
    $imageFile = basename($imageFile);
    $imageSrc = 'images/' . $imageFile;

    // Check whether the image actually exists
    $imageFullPath = __DIR__ . '/images/' . $imageFile;
}
?>

<?php if ($imageFile !== '' && file_exists($imageFullPath)) : ?>
    <img 
        src="<?= htmlspecialchars($imageSrc) ?>" 
        alt="<?= htmlspecialchars($service['name']) ?>" 
        loading="lazy" 
        decoding="async"
        style="width:80px; height:60px; object-fit:cover; border-radius:8px;"
    >
<?php else : ?>
    <span class="text-muted">No image</span>
<?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a class="btn action-btn btn-outline-primary" href="edit_service.php?id=<?= (int)$service['id'] ?>" title="Edit Service"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Delete this service?');">
                                                    <input type="hidden" name="action" value="delete_service">
                                                    <input type="hidden" name="service_id" value="<?= (int)$service['id'] ?>">
                                                    <button class="btn action-btn btn-outline-danger" type="submit" title="Delete Service"><i class="fa-solid fa-trash-can"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="empty-state">
                        <div class="empty-state__icon"><i class="fa-solid fa-list-check"></i></div>
                        <p class="mb-1 fw-semibold">No services added yet</p>
                        <p class="mb-0 text-muted small">Use the form above to add your first service entry.</p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- ==================== Contact Messages ==================== -->
            <div class="admin-section-header">
                <h5><i class="fa-solid fa-envelope me-1"></i> Communication</h5>
                <hr>
            </div>

            <section class="admin-card">
                <div class="card-title">
                    <div>
                        <h4><i class="fa-solid fa-inbox me-2 text-muted"></i>Contact Messages</h4>
                        <p class="card-subtitle">Messages received from the contact form</p>
                    </div>
                    <span class="badge bg-primary badge-count">
                        <?= count($contactMessages) ?> <?= count($contactMessages) === 1 ? 'Message' : 'Messages' ?>
                    </span>
                </div>

                <?php if (!empty($contactMessages)) : ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle admin-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contactMessages as $contact) : ?>
                                    <tr>
                                        <td><?= (int)($contact['id'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars(($contact['first_name'] ?? '') . ' ' . ($contact['last_name'] ?? '')) ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars((string)($contact['email'] ?? '')) ?>" class="text-decoration-none"><?= htmlspecialchars((string)($contact['email'] ?? '')) ?></a></td>
                                        <td><?= htmlspecialchars((string)($contact['phone'] ?? '')) ?></td>
                                        <td><span class="fw-semibold"><?= htmlspecialchars((string)($contact['subject'] ?? '')) ?></span></td>
                                        <td><span class="text-muted" style="max-width: 200px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: bottom;"><?= htmlspecialchars((string)($contact['message'] ?? '')) ?></span></td>
                                        <td><small class="text-muted"><?= htmlspecialchars((string)($contact['created_at'] ?? '')) ?></small></td>
                                        <td>
                                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                <input type="hidden" name="action" value="delete_contact">
                                                <input type="hidden" name="contact_id" value="<?= (int)($contact['id'] ?? 0) ?>">
                                                <button class="btn action-btn btn-outline-danger" type="submit" title="Delete Message">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="empty-state">
                        <div class="empty-state__icon"><i class="fa-solid fa-envelope-open"></i></div>
                        <p class="mb-1 fw-semibold">No messages yet</p>
                        <p class="mb-0 text-muted small">Contact form submissions will appear here.</p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- ==================== Dashboard Footer ==================== -->
            <footer class="admin-footer">
                <i class="fa-solid fa-hands-helping me-1"></i>
                &copy; <?= date('Y') ?> <a href="index.php">Quetta Services Hub</a> &mdash; Admin Dashboard. All rights reserved.
            </footer>
        </main>
    </div>

    <!-- ==================== Dashboard Search Script ==================== -->
    <script>
        // This JavaScript filters the service table while the admin types in the search box.
        // It keeps the dashboard easier to use when many services are listed.
        const searchInput = document.getElementById('serviceSearch');
        const serviceTable = document.getElementById('serviceTable');

        if (searchInput && serviceTable) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                const rows = serviceTable.querySelectorAll('tbody tr');

                rows.forEach(function (row) {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    </script>
</body>
</html>
