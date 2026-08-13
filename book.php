<?php
// ==================== Booking Page Logic ====================
// This page prepares the booking form and saves customer requests.
// It reads the selected service from the URL and uses the database to fill the form details.
$activePage = 'book';
include 'config.php';

// This helper finds the correct value in a database row.
// It is useful because some service tables may use slightly different column names.
function resolveServiceField(array $row, array $candidates) {
    foreach ($candidates as $candidate) {
        if (array_key_exists($candidate, $row) && $row[$candidate] !== null && $row[$candidate] !== '') {
            return $row[$candidate];
        }
    }

    return '';
}

function resolveImagePath($value) {
    if (empty($value)) {
        return 'https://via.placeholder.com/900x600?text=Quetta+Service';
    }

    if (preg_match('/^https?:\/\//i', $value)) {
        return $value;
    }

    return $value;
}

// Get the service ID from the URL when a user clicks "Book Now" from a service card.
// This value is used to display the chosen service before the booking form is sent.
$service_id = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;
$message = '';
$messageType = '';
$selectedService = null;
$selectedServiceName = '';
$selectedServicePrice = '';
$selectedServiceDescription = '';
$selectedServiceImage = 'https://via.placeholder.com/900x600?text=Quetta+Service';
$serviceKeyColumn = 'id';
$canBook = false;

if ($conn) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'services'");

    if ($tableCheck && $tableCheck->num_rows > 0) {
        $schemaResult = $conn->query('SHOW COLUMNS FROM services');
        $serviceColumns = [];

        if ($schemaResult) {
            while ($column = $schemaResult->fetch_assoc()) {
                $serviceColumns[] = strtolower((string)($column['Field'] ?? ''));
            }
        }

        foreach (['id', 'service_id'] as $candidate) {
            if (in_array($candidate, $serviceColumns, true)) {
                $serviceKeyColumn = $candidate;
                break;
            }
        }
    }
}

// If a service ID was passed in the URL, fetch that service from the database.
// The data is then shown in the booking form so the user can confirm it before submitting.
if ($service_id > 0 && $conn) {
    $stmt = $conn->prepare('SELECT * FROM services WHERE ' . $serviceKeyColumn . ' = ? LIMIT 1');
    $stmt->bind_param('i', $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $selectedService = $result->fetch_assoc();
        $selectedServiceName = (string)resolveServiceField($selectedService, ['name', 'service_name', 'title', 'service_title', 'service_type']);
        $selectedServicePrice = (string)resolveServiceField($selectedService, ['price', 'service_price', 'cost', 'amount']);
        $selectedServiceDescription = (string)resolveServiceField($selectedService, ['description', 'details', 'service_description', 'short_description', 'summary']);
        $selectedServiceImage = (string)resolveImagePath(resolveServiceField($selectedService, ['image', 'service_image', 'img', 'photo', 'thumbnail', 'service_photo']));
        $canBook = $service_id > 0 && $selectedServiceName !== '';
    } else {
        $message = 'Please select a valid service before booking.';
        $messageType = 'error';
    }

    $stmt->close();
}

if ($service_id <= 0) {
    $message = 'Please select a service before making a booking.';
    $messageType = 'error';
}

if ($conn) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'bookings'");
    if ($tableCheck && $tableCheck->num_rows === 0) {
        $conn->query(
            "CREATE TABLE bookings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                service_id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                phone VARCHAR(30) NOT NULL,
                address TEXT NOT NULL,
                booking_date DATE NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        );
    }
}

// ==================== Booking Submission ====================
// When the user submits the booking form, this block reads the POST values.
// It validates the fields and then saves the booking information into the bookings table.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $bookingDate = trim($_POST['date'] ?? '');

    if ($service_id > 0 && $name !== '' && $phone !== '' && $address !== '' && $bookingDate !== '') {
        $stmt = $conn->prepare('INSERT INTO bookings (service_id, name, phone, address, booking_date) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('issss', $service_id, $name, $phone, $address, $bookingDate);

        if ($stmt->execute()) {
            $message = 'Booking submitted successfully. We will contact you shortly.';
            $messageType = 'success';
        } else {
            $message = 'Unable to save booking. Please try again.';
            $messageType = 'error';
        }

        $stmt->close();
    } else {
        $message = $service_id > 0 ? 'Please fill all fields correctly.' : 'Please select a valid service before booking.';
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        body {
            background: #f5f8fc;
        }

        .booking-page-shell {
            padding: 24px 0 40px;
        }

        .booking-hero {
            background: linear-gradient(135deg, #0B1F3A 0%, #0D6EFD 100%);
            border-radius: 24px;
            padding: 28px 32px;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            box-shadow: 0 18px 40px rgba(11, 31, 58, 0.14);
        }

        .booking-hero h1 {
            margin: 6px 0 8px;
            font-size: clamp(1.7rem, 2.2vw, 2.3rem);
            font-weight: 700;
        }

        .booking-hero p {
            margin: 0;
            color: rgba(255,255,255,0.9);
            max-width: 700px;
        }

        .booking-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.16);
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .booking-hero__badge {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .booking-alert {
            border-radius: 14px;
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 10px 24px rgba(11, 31, 58, 0.06);
        }

        .booking-side-card,
        .booking-form-card {
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 16px 36px rgba(11, 31, 58, 0.08);
            border: 1px solid rgba(220, 231, 242, 0.95);
        }

        .selected-service-card {
            overflow: hidden;
        }

        .selected-service-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .selected-service-card__body {
            padding: 24px;
        }

        .section-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(13,110,253,0.12), rgba(111,66,193,0.14));
            color: #0D6EFD;
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .selected-service-card h3,
        .booking-form-card h3 {
            margin: 12px 0 8px;
            color: #0B1F3A;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .selected-service-card p,
        .booking-form-card p {
            color: #58708e;
            margin-bottom: 0;
            line-height: 1.7;
        }

        .selected-price {
            margin-top: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #f5f8fc;
            color: #0B1F3A;
            font-weight: 700;
            border: 1px solid #dfe9f5;
        }

        .feature-list {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #f8fbff;
            color: #0B1F3A;
            font-weight: 600;
            border: 1px solid #e8f0f8;
        }

        .feature-item i {
            color: #6F42C1;
        }

        .booking-form-card {
            padding: 30px;
        }

        .booking-form-card__meta {
            margin-bottom: 20px;
        }

        .booking-form-card__meta p {
            margin-top: 4px;
        }

        .booking-form-card .form-control {
            min-height: 50px;
            border-radius: 14px;
            border: 1px solid #dce7f2;
            padding: 14px 16px;
            font-size: 0.97rem;
            color: #0B1F3A;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .booking-form-card .form-control:focus {
            border-color: #0D6EFD;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.16);
        }

        .booking-form-card .input-group-text {
            background: #f5f8fc;
            border: 1px solid #dce7f2;
            color: #0D6EFD;
            border-radius: 14px 0 0 14px;
            min-width: 48px;
            justify-content: center;
        }

        .booking-form-card .form-control,
        .booking-form-card textarea.form-control {
            border-radius: 0 14px 14px 0;
        }

        .booking-form-card textarea.form-control {
            min-height: 120px;
            border-radius: 14px;
        }

        .booking-submit {
            width: 100%;
            margin-top: 8px;
            border: none;
            border-radius: 14px;
            padding: 14px 18px;
            background: linear-gradient(135deg, #0D6EFD 0%, #6F42C1 100%);
            color: #ffffff;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.18);
        }

        .booking-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(13, 110, 253, 0.22);
            color: #ffffff;
        }

        .booking-empty {
            padding: 24px;
            border-radius: 20px;
            background: #f8fbff;
            border: 1px dashed #cfdce8;
            text-align: center;
        }

        .booking-empty__icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(13,110,253,0.14), rgba(111,66,193,0.12));
            color: #0D6EFD;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        @media (max-width: 991.98px) {
            .booking-hero {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 767.98px) {
            .booking-page-shell {
                padding: 16px 0 32px;
            }

            .booking-hero,
            .booking-form-card {
                padding: 22px;
            }

            .selected-service-card__body {
                padding: 20px;
            }
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'book' ? 'active' : '' ?>" href="book.php"><i class="fa-solid fa-calendar-check"></i> Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_login.php"><i class="fa-solid fa-user-shield"></i> Admin</a></li>
                </ul>
                <a class="btn btn-contact" href="book.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
            </div>
        </div>
    </nav>
    </header>

    <!-- ==================== Booking Form Section ==================== -->
    <main class="page-shell booking-page-shell">
        <div class="container">
            <div class="booking-hero">
                <div>
                    <div class="booking-chip"><i class="fa-solid fa-calendar-check"></i> Premium Booking Experience</div>
                    <h1>Book Your Home Service</h1>
                    <p>Choose your service, provide your details, and we'll take care of the rest.</p>
                </div>
                <div class="booking-hero__badge">Fast • Trusted • Easy</div>
            </div>

            <?php if ($message !== '') : ?>
                <div class="alert <?= htmlspecialchars($messageType === 'success' ? 'alert-success' : 'alert-danger') ?> booking-alert" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fa-solid <?= htmlspecialchars($messageType === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation') ?>"></i>
                        <div>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($messageType === 'success' ? 'Booking submitted successfully!' : 'Booking notice') ?>
                            </div>
                            <div class="small">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-5">
                    <div class="selected-service-card booking-side-card">
                        <?php if ($selectedService && $selectedServiceName !== '') : ?>
                            <img src="<?= htmlspecialchars($selectedServiceImage) ?>" alt="<?= htmlspecialchars($selectedServiceName) ?>" loading="lazy">
                            <div class="selected-service-card__body">
                                <div class="section-pill"><i class="fa-solid fa-check-circle"></i> Your Selected Service</div>
                                <h3><?= htmlspecialchars($selectedServiceName) ?></h3>
                                <p><?= htmlspecialchars($selectedServiceDescription !== '' ? $selectedServiceDescription : 'Professional service tailored to your needs.') ?></p>
                                <div class="selected-price">Starting from Rs. <?= htmlspecialchars($selectedServicePrice !== '' ? $selectedServicePrice : '0') ?></div>
                            </div>
                        <?php else : ?>
                            <div class="selected-service-card__body">
                                <div class="section-pill"><i class="fa-solid fa-list-check"></i> Service Selection</div>
                                <h3>Please select a service</h3>
                                <p>Choose a service from our catalog to continue with your booking request.</p>
                                <a class="btn btn-secondary mt-3" href="services.php"><i class="fa-solid fa-list-check"></i> Browse Services</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($selectedService && $selectedServiceName !== '') : ?>
                        <div class="booking-side-card mt-4 p-4">
                            <h3 class="h5 mb-3">Why Book With Us?</h3>
                            <div class="feature-list">
                                <div class="feature-item"><i class="fa-solid fa-bolt"></i><span>Fast Response</span></div>
                                <div class="feature-item"><i class="fa-solid fa-shield-halved"></i><span>Trusted Professionals</span></div>
                                <div class="feature-item"><i class="fa-solid fa-tag"></i><span>Transparent Pricing</span></div>
                                <div class="feature-item"><i class="fa-solid fa-calendar-check"></i><span>Easy Booking</span></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-lg-7">
                    <?php if ($canBook) : ?>
                        <div class="booking-form-card">
                            <div class="booking-form-card__meta">
                                <h3>Booking Details</h3>
                                <p>Enter your information and preferred service date.</p>
                            </div>

                            <form method="post" class="needs-validation" novalidate>
                                <input type="hidden" name="service_id" value="<?= (int)$service_id ?>">

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="name" class="form-label fw-semibold text-dark">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input id="name" name="name" type="text" class="form-control" placeholder="Your full name" required>
                                        </div>
                                        <div class="invalid-feedback">Please enter your name.</div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="phone" class="form-label fw-semibold text-dark">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                            <input id="phone" name="phone" type="tel" class="form-control" placeholder="Phone number" required>
                                        </div>
                                        <div class="invalid-feedback">Please enter your phone number.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold text-dark">Address</label>
                                        <textarea id="address" name="address" class="form-control" placeholder="Your address" required></textarea>
                                        <div class="invalid-feedback">Please enter your address.</div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="date" class="form-label fw-semibold text-dark">Preferred Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                            <input id="date" name="date" type="date" class="form-control" required>
                                        </div>
                                        <div class="invalid-feedback">Please choose a date.</div>
                                    </div>
                                </div>

                                <button class="booking-submit" type="submit"><i class="fa-solid fa-paper-plane me-2"></i> Confirm Booking</button>
                            </form>
                        </div>
                    <?php else : ?>
                        <div class="booking-form-card">
                            <div class="booking-form-card__meta">
                                <h3>Booking Details</h3>
                                <p>Please select a service before making a booking.</p>
                            </div>
                            <div class="booking-empty">
                                <div class="booking-empty__icon"><i class="fa-solid fa-calendar-plus"></i></div>
                                <p class="mb-3">Choose a service from our catalog to unlock the booking form.</p>
                                <a class="btn btn-primary" href="services.php"><i class="fa-solid fa-list-check"></i> Browse Services</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
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
