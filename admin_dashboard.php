<?php
// ==================== Admin Dashboard Logic ====================
// This page protects the dashboard and handles service and booking management.
$activePage = 'dashboard';
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$uploadDir = __DIR__ . '/images';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$message = '';
$messageType = '';

// ==================== Add Service ====================
// Create a new service entry and optionally upload an image.
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
            $imagePath = 'images/' . $fileName;
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
// Remove an existing service using its ID.
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

// ==================== Fetch Services and Bookings ====================
// Load the services catalog and recent bookings for the dashboard view.
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

// ==================== Logout ====================
// Destroy the admin session and redirect back to the login page.
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
                    <a class="nav-link" href="edit_service.php"><i class="fa-solid fa-pen"></i> Manage Services</a>
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
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <section class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card h-100">
                        <div class="stat-icon"><i class="fa-solid fa-list-check"></i></div>
                        <div>
                            <h3><?= count($services) ?></h3>
                            <p>Total Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card h-100">
                        <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <div>
                            <h3><?= count($bookings) ?></h3>
                            <p>Recent Bookings</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card h-100">
                        <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
                        <div>
                            <h3><?= count($services) > 0 ? 'Live' : 'Draft' ?></h3>
                            <p>Catalog Status</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card h-100">
                        <div class="stat-icon"><i class="fa-solid fa-bolt"></i></div>
                        <div>
                            <h3>24/7</h3>
                            <p>Support Ready</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="row g-4">
                <div class="col-12 col-xl-7">
                    <div class="admin-card h-100">
                        <div class="card-title">
                            <div>
                                <h4>Recent Bookings</h4>
                                <p class="card-subtitle">Latest client requests at a glance</p>
                            </div>
                            <span class="badge bg-primary">Live</span>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking) : ?>
                                            <tr>
                                                <td><?= (int)($booking['id'] ?? 0) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['name'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['phone'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['address'] ?? '')) ?></td>
                                                <td><?= htmlspecialchars((string)($booking['date'] ?? '')) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="empty-state">No bookings have been submitted yet.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="admin-card h-100">
                        <div class="card-title">
                            <div>
                                <h4>Add New Service</h4>
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

            <section class="admin-card">
                <div class="card-title">
                    <div>
                        <h4>Service Directory</h4>
                        <p class="card-subtitle">Review, edit, and remove services from one workspace</p>
                    </div>
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
                                            <?php if (!empty($service['image'])) : ?>
                                                <img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>" loading="lazy" decoding="async">
                                            <?php else : ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a class="btn btn-sm btn-outline-primary" href="edit_service.php?id=<?= (int)$service['id'] ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                                                <form method="post" onsubmit="return confirm('Delete this service?');">
                                                    <input type="hidden" name="action" value="delete_service">
                                                    <input type="hidden" name="service_id" value="<?= (int)$service['id'] ?>">
                                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="empty-state">No services found.</div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- ==================== Dashboard Search Script ==================== -->
    <script>
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
