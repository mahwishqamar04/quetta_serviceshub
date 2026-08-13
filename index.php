<?php
// ==================== Home Page Logic ====================
// This is the main public homepage of the website.
// It loads the shared database connection and fetches service data to show on the home screen.
$activePage = 'home';
include 'config.php';

// This helper checks a database row for the first matching field name.
// It is useful when column names are not always exactly the same.
function resolveField(array $row, array $candidates) {
    foreach ($candidates as $candidate) {
        if (array_key_exists($candidate, $row) && $row[$candidate] !== null && $row[$candidate] !== '') {
            return $row[$candidate];
        }
    }

    return '';
}

// This helper gives a default image URL if the service image is missing.
// It helps the page keep a clean layout even when no image is stored in the database.
function resolveImagePath($value) {
    if (empty($value)) {
        return 'https://via.placeholder.com/600x360?text=Quetta+Service';
    }

    if (preg_match('/^https?:\/\//i', $value)) {
        return $value;
    }

    return $value;
}

// ==================== Fetch Services ====================
// This section checks whether the services table exists and reads all service records.
// The records are saved into an array so the HTML can loop through them and show them as cards.
$services = [];

if ($conn) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'services'");

    if ($tableCheck && $tableCheck->num_rows > 0) {
        $schemaResult = $conn->query('SHOW COLUMNS FROM services');
        $columns = [];

        if ($schemaResult) {
            while ($column = $schemaResult->fetch_assoc()) {
                $columns[] = $column['Field'];
            }
        }

        // Run a MySQL query to get all services from the database.
        // The newest services are shown first because the query orders by id descending.
        $query = 'SELECT * FROM services ORDER BY id DESC';
        $result = $conn->query($query);

        if ($result) {
            // Loop through each service row and store only the fields needed for the homepage.
            while ($row = $result->fetch_assoc()) {
                $services[] = [
                    'id' => resolveField($row, ['id']),
                    'image' => resolveField($row, ['image', 'service_image', 'img', 'photo', 'thumbnail', 'service_photo']),
                    'name' => resolveField($row, ['name', 'title', 'service_name', 'service_title', 'service_type']),
                    'description' => resolveField($row, ['description', 'details', 'service_description', 'short_description', 'summary']),
                    'price' => resolveField($row, ['price', 'service_price', 'cost', 'amount']),
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quetta Home Services Hub</title>
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
<body>
    <!-- ==================== Header / Navigation ==================== -->
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
                    <li class="nav-item"><a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php"><i class="fa-solid fa-users"></i> About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services"><i class="fa-solid fa-list-check"></i> Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="book.php"><i class="fa-solid fa-calendar-check"></i> Book Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_login.php"><i class="fa-solid fa-user-shield"></i> Admin</a></li>
                </ul>
                <a class="btn btn-contact" href="contact.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
            </div>
        </div>
    </nav>
    </header>

    <!-- ==================== Hero Section ==================== -->
    <!-- This is the main welcome area shown at the top of the homepage. -->
    <main class="page-shell">
        <div class="page-card page-card--soft page-shell-card">
            <section class="hero-panel hero-section fade-in-section hero-panel--spaced">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="icon-badge"><i class="fa-solid fa-bolt"></i></span>
                            <span class="hero-badge">Trusted Home Services in Quetta</span>
                        </div>
                        <h1>Fast, dependable home services for every part of your home.</h1>
                        <p>From repairs and maintenance to cleaning and booking support, we make it simple to get professional help when you need it most.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="book.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Book a Service</a>
                            <a href="#services" class="btn btn-secondary"><i class="fa-solid fa-list-check"></i> Explore Services</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-value">24/7</div>
                                    <div class="stat-label">Booking Support</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-value">100%</div>
                                    <div class="stat-label">Reliable Care</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-value">5★</div>
                                    <div class="stat-label">Customer Rating</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="stat-box">
                                    <div class="stat-value">Fast</div>
                                    <div class="stat-label">On-Time Visits</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== Services Section ==================== -->
            <!-- This section loops through the services array and displays each service as a card. -->
            <section class="section-block fade-in-section" id="services">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <div class="section-title">Featured Services</div>
                        <p class="section-subtitle">Browse our curated selection and book the service that fits your home needs best.</p>
                    </div>
                    <a href="book.php" class="btn btn-secondary"><i class="fa-solid fa-phone"></i> Get Started</a>
                </div>

                <?php if (!empty($services)) : ?>
                    <!-- If services exist, show them in Bootstrap cards. -->
                    <div class="row g-4">
                        <?php foreach ($services as $service) : ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="card service-card h-100 border-0 shadow-sm fade-in-section">
                                    <img src="images/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>" loading="lazy" decoding="async">
                                    <div class="service-card__body">
                                        <h3 class="service-card__title"><?= htmlspecialchars($service['name']) ?></h3>
                                        <p class="service-card__text"><?= htmlspecialchars($service['description']) ?></p>
                                        <div class="price-pill">
                                            <span>Starting at</span>
                                            <span>Rs. <?= htmlspecialchars($service['price']) ?></span>
                                        </div>
                                        <a href="book.php?service_id=<?= (int)($service['id'] ?? 0) ?>" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <!-- If no services are in the database, show a friendly empty state. -->
                    <div class="empty-state">
                        <p class="mb-0">No services are available right now.</p>
                    </div>
                <?php endif; ?>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Why Choose Us</div>
                <p class="section-subtitle">We believe premium service should feel effortless, transparent, and dependable.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                            <h5 class="fw-bold mb-2">Verified Professionals</h5>
                            <p class="section-subtitle mb-0">Reliable experts you can trust for every visit.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="feature-icon"><i class="fa-solid fa-clock"></i></div>
                            <h5 class="fw-bold mb-2">Fast Scheduling</h5>
                            <p class="section-subtitle mb-0">Quick appointments with flexible booking options.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="feature-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                            <h5 class="fw-bold mb-2">Transparent Pricing</h5>
                            <p class="section-subtitle mb-0">Clear rates and upfront estimates every time.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                            <h5 class="fw-bold mb-2">Support Anytime</h5>
                            <p class="section-subtitle mb-0">Friendly support for questions before and after booking.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Working Process</div>
                <p class="section-subtitle">A smooth experience from first contact to final confirmation.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="step-number">1</div>
                            <h5 class="fw-bold mb-2">Choose a Service</h5>
                            <p class="section-subtitle mb-0">Select the service you need from our curated options.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="step-number">2</div>
                            <h5 class="fw-bold mb-2">Fill Your Details</h5>
                            <p class="section-subtitle mb-0">Share your contact and location information securely.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="step-number">3</div>
                            <h5 class="fw-bold mb-2">Confirm Booking</h5>
                            <p class="section-subtitle mb-0">We confirm availability and guide your next steps.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card-surface p-4 h-100">
                            <div class="step-number">4</div>
                            <h5 class="fw-bold mb-2">Enjoy the Service</h5>
                            <p class="section-subtitle mb-0">Our team arrives promptly and delivers quality results.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Customer Reviews</div>
                <p class="section-subtitle">What our clients say about working with us.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="review-card">
                            <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="mb-3">“The booking process was smooth and the team arrived right on time. Excellent service.”</p>
                            <strong>Farah Khan</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="review-card">
                            <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="mb-3">“Professional, clean, and respectful. I would definitely recommend them to others.”</p>
                            <strong>Ali Raza</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="review-card">
                            <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></div>
                            <p class="mb-3">“The support team made everything easy, and the service was top quality.”</p>
                            <strong>Nida Baloch</strong>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cta-panel fade-in-section">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <h3 class="mb-2">Ready to experience premium home services?</h3>
                        <p class="mb-0">Book today and let our trusted professionals handle the rest with care and speed.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="book.php" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Book Now</a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- ==================== Footer ==================== -->
    <!-- This footer appears on every page and gives quick links and contact information. -->
    <footer class="footer-premium" role="contentinfo">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="footer-title">Company Information</h5>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="brand-mark brand-mark--footer"><i class="fa-solid fa-hands-helping"></i></span>
                        <span class="fw-semibold">Quetta Services Hub</span>
                    </div>
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

    <!-- This button scrolls the page back to the top when clicked. -->
    <a href="#" class="back-to-top" aria-label="Back to top"><i class="fa-solid fa-arrow-up" aria-hidden="true"></i></a>

    <script>
        // This JavaScript adds a small delay to each section so the homepage animations appear smoothly.
        document.querySelectorAll('.fade-in-section').forEach(function (section, index) {
            section.style.animationDelay = (index * 80) + 'ms';
        });
    </script>
</body>
</html>
