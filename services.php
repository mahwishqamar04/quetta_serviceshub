<?php
// ==================== Services Page Logic ====================
// This page shows the full services catalog for visitors.
// It reads the services table and prepares the data for the layout below.
$activePage = 'services';
include 'config.php';

// This helper tries several possible field names and returns the first valid value.
// This makes the page more flexible if the database column names change slightly.
function resolveField(array $row, array $candidates) {
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

// This helper picks a Bootstrap icon class based on the service type.
// It helps the UI look nicer without changing the actual data stored in the database.
function resolveIconClass(array $row) {
    $candidates = ['category', 'service_category', 'category_name', 'service_type', 'type', 'icon', 'category_icon', 'service_icon'];

    foreach ($candidates as $candidate) {
        if (array_key_exists($candidate, $row) && is_string($row[$candidate]) && trim($row[$candidate]) !== '') {
            $value = strtolower(trim($row[$candidate]));

            if (strpos($value, 'clean') !== false || strpos($value, 'sanit') !== false) {
                return 'bi-bucket';
            }
            if (strpos($value, 'elect') !== false || strpos($value, 'wiring') !== false) {
                return 'bi-lightning-charge';
            }
            if (strpos($value, 'plumb') !== false || strpos($value, 'water') !== false) {
                return 'bi-droplet';
            }
            if (strpos($value, 'paint') !== false || strpos($value, 'decor') !== false) {
                return 'bi-brush';
            }
            if (strpos($value, 'repair') !== false || strpos($value, 'maint') !== false) {
                return 'bi-tools';
            }
            if (strpos($value, 'home') !== false) {
                return 'bi-house-door';
            }

            if (preg_match('/^(fa|bi)-/i', $value)) {
                return $value;
            }
        }
    }

    return 'bi-tools';
}

// ==================== Fetch Services ====================
// Read all records from the services table and save them in a simple array.
// Each row is then used to display one service card in the catalog.
$services = [];

if ($conn) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'services'");

    if ($tableCheck && $tableCheck->num_rows > 0) {
        $query = 'SELECT * FROM services ORDER BY id DESC';
        $result = $conn->query($query);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $services[] = [
                    'id' => resolveField($row, ['id']),
                    'image' => resolveField($row, ['image', 'service_image', 'img', 'photo', 'thumbnail', 'service_photo']),
                    'name' => resolveField($row, ['name', 'title', 'service_name', 'service_title', 'service_type']),
                    'description' => resolveField($row, ['description', 'details', 'service_description', 'short_description', 'summary']),
                    'price' => resolveField($row, ['price', 'service_price', 'cost', 'amount']),
                    'icon' => resolveIconClass($row),
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
    <title>Our Services | Quetta Services Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #f7fbff 0%, #eef6ff 100%);
        }

        .services-page .hero-panel {
            padding: 42px;
            overflow: hidden;
        }

        .services-page .hero-badge {
            background: rgba(255,255,255,0.16);
            color: white;
        }

        .hero-illustration {
            border-radius: 24px;
            background: rgba(255,255,255,0.14);
            padding: 24px;
            border: 1px solid rgba(255,255,255,0.16);
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-illustration svg {
            width: 100%;
            max-width: 360px;
            height: auto;
        }

        .stats-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .stats-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.16);
            border-radius: 999px;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .service-card {
            position: relative;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(11, 31, 58, 0.16);
        }

        .service-card__body {
            padding: 22px;
        }

        .service-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(13,110,253,0.16), rgba(11,31,58,0.10));
            color: #0D6EFD;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }

        .service-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            background: #f3f8ff;
            color: #0B1F3A;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .feature-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid rgba(220,231,242,0.95);
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 12px 28px rgba(11,31,58,0.06);
            height: 100%;
        }

        .testimonial-card {
            background: #ffffff;
            border: 1px solid rgba(220,231,242,0.95);
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 12px 28px rgba(11,31,58,0.07);
            height: 100%;
        }

        .testimonial-card .stars {
            color: #f4b400;
            margin-bottom: 10px;
        }

        .cta-panel {
            border-radius: 30px;
            padding: 40px;
            background: linear-gradient(135deg, #0D6EFD 0%, #0B1F3A 100%);
            color: white;
            box-shadow: 0 20px 45px rgba(11,31,58,0.18);
        }

        .accordion-button:not(.collapsed) {
            background: #eef6ff;
            color: #0B1F3A;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(13, 110, 253, 0.2);
        }
    </style>
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
                        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="services.php"><i class="fa-solid fa-list-check"></i> Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="book.php"><i class="fa-solid fa-calendar-check"></i> Book</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_login.php"><i class="fa-solid fa-user-shield"></i> Admin</a></li>
                    </ul>
                    <a class="btn btn-contact" href="book.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="page-shell services-page">
        <div class="page-card page-card--soft page-shell-card">
            <section class="hero-panel hero-section fade-in-section hero-panel--spaced">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="icon-badge"><i class="fa-solid fa-bolt"></i></span>
                            <span class="hero-badge">Professional Home Solutions</span>
                        </div>
                        <h1>Our Professional Home Services</h1>
                        <p>We provide trusted, affordable and high-quality home services across Quetta. From essential repairs to premium care, our experts are ready to help.</p>

                        <div class="stats-list">
                            <span class="stats-pill"><i class="fa-solid fa-users"></i> 1000+ Happy Customers</span>
                            <span class="stats-pill"><i class="fa-solid fa-headset"></i> 24/7 Support</span>
                            <span class="stats-pill"><i class="fa-solid fa-shield-halved"></i> Verified Experts</span>
                            <span class="stats-pill"><i class="fa-solid fa-tags"></i> Affordable Pricing</span>
                        </div>

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="book.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Book a Service</a>
                            <a href="#services" class="btn btn-secondary"><i class="fa-solid fa-list-check"></i> Explore Services</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="hero-illustration">
                            <svg viewBox="0 0 512 420" role="img" aria-label="Illustration of a home service professional">
                                <rect x="70" y="60" width="372" height="300" rx="30" fill="#ffffff" opacity="0.95"></rect>
                                <rect x="110" y="110" width="180" height="130" rx="18" fill="#dfefff"></rect>
                                <rect x="310" y="110" width="92" height="92" rx="18" fill="#0D6EFD"></rect>
                                <rect x="110" y="260" width="292" height="60" rx="14" fill="#0B1F3A"></rect>
                                <circle cx="240" cy="215" r="54" fill="#0D6EFD"></circle>
                                <circle cx="240" cy="215" r="33" fill="#ffffff"></circle>
                                <rect x="210" y="260" width="60" height="48" rx="12" fill="#ffffff"></rect>
                                <path d="M330 202c18 0 32 14 32 32v42h-64v-42c0-18 14-32 32-32z" fill="#ffffff" opacity="0.95"></path>
                                <path d="M308 182h48v26h-48z" fill="#0B1F3A"></path>
                                <path d="M268 170c0-36 28-64 64-64s64 28 64 64v16h-128v-16z" fill="#0D6EFD"></path>
                                <rect x="140" y="140" width="120" height="14" rx="7" fill="#0B1F3A" opacity="0.75"></rect>
                                <rect x="140" y="168" width="94" height="12" rx="6" fill="#0B1F3A" opacity="0.55"></rect>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== Services Catalog ==================== -->
            <section class="section-block fade-in-section" id="services">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <div class="section-title">Our Services</div>
                        <p class="section-subtitle">Explore our complete range of trusted home solutions, each designed to be quick, professional, and dependable.</p>
                    </div>
                    <a href="book.php" class="btn btn-secondary"><i class="fa-solid fa-phone"></i> Book Now</a>
                </div>

                <?php if (!empty($services)) : ?>
                    <div class="row g-4">
                        <?php foreach ($services as $service) : ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="card service-card h-100 border-0 shadow-sm fade-in-section">
                                    <img src="images/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>" loading="lazy" decoding="async">
                                    <div class="service-card__body">
                                        <div class="service-icon"><i class="<?= htmlspecialchars($service['icon']) ?>"></i></div>
                                        <h3 class="service-card__title"><?= htmlspecialchars($service['name']) ?></h3>
                                        <p class="service-card__text"><?= htmlspecialchars($service['description']) ?></p>
                                        <div class="service-meta">
                                            <span>Starting From</span>
                                            <span>Rs. <?= htmlspecialchars($service['price']) ?></span>
                                        </div>
                                        <a href="book.php?service_id=<?= (int)($service['id'] ?? 0) ?>" class="btn btn-primary w-100"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="empty-state">
                        <p class="mb-0">No services are available right now.</p>
                    </div>
                <?php endif; ?>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Why Choose Quetta Services Hub</div>
                <p class="section-subtitle">A premium experience built around trust, convenience, and consistent results.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="service-icon"><i class="bi-person-check"></i></div>
                            <h5 class="fw-bold mb-2">Verified Professionals</h5>
                            <p class="section-subtitle mb-0">Skilled and trusted professionals for every visit.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="service-icon"><i class="bi-cash-stack"></i></div>
                            <h5 class="fw-bold mb-2">Affordable Prices</h5>
                            <p class="section-subtitle mb-0">Transparent rates designed to fit your budget.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="service-icon"><i class="bi-lightning-charge"></i></div>
                            <h5 class="fw-bold mb-2">Same Day Booking</h5>
                            <p class="section-subtitle mb-0">Fast scheduling and prompt response for urgent needs.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="service-icon"><i class="bi-shield-check"></i></div>
                            <h5 class="fw-bold mb-2">Trusted Service</h5>
                            <p class="section-subtitle mb-0">Reliable support from first inquiry to completion.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Customer Testimonials</div>
                <p class="section-subtitle">Real feedback from customers who trusted us for their home needs.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="testimonial-card">
                            <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="mb-3">“The team arrived on time and provided outstanding service. Everything felt professional and easy.”</p>
                            <strong>Farah Khan</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="testimonial-card">
                            <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="mb-3">“Great communication and fair pricing. I would definitely recommend them for home support.”</p>
                            <strong>Ali Raza</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="testimonial-card">
                            <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <p class="mb-3">“Professional, reliable, and friendly. The service experience was smooth from start to finish.”</p>
                            <strong>Samina Baloch</strong>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Frequently Asked Questions</div>
                <p class="section-subtitle">Everything you need to know before booking your next service.</p>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How do I book?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Choose a service, fill out the booking form, and our team will confirm your request promptly.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How much do services cost?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Prices vary by service type and scope. You can view the starting price on each service card.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Are technicians verified?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes. Every technician is verified and selected for professionalism and reliability.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Which areas do you cover?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We are proud to serve clients across Quetta and nearby areas with dependable support.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="cta-panel text-center">
                    <h3 class="mb-3">Need Immediate Home Service?</h3>
                    <p class="mb-4">Book now and let our team assist you with fast, trusted home care.</p>
                    <a href="book.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Book a Service</a>
                </div>
            </section>
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
                        <a class="footer-link" href="services.php"><i class="fa-solid fa-chevron-right"></i> Services</a>
                        <a class="footer-link" href="book.php"><i class="fa-solid fa-chevron-right"></i> Book Service</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="footer-title">Services</h5>
                    <div class="d-flex flex-column">
                        <a class="footer-link" href="services.php"><i class="fa-solid fa-screwdriver-wrench"></i> Home Repairs</a>
                        <a class="footer-link" href="services.php"><i class="fa-solid fa-broom"></i> Cleaning</a>
                        <a class="footer-link" href="services.php"><i class="fa-solid fa-plug-circle-bolt"></i> Electrical</a>
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
