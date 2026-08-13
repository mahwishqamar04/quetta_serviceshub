<?php
// About page for Quetta Services Hub
// This page is a simple public information page.
// It loads the shared database connection and shows the about content without changing the app logic.
$activePage = 'about';
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Quetta Services Hub</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%230B1F3A'/%3E%3Cpath d='M20 44h24a2 2 0 0 0 2-2V27a2 2 0 0 0-.7-1.6l-12-11a2 2 0 0 0-2.6 0l-12 11A2 2 0 0 0 18 27v15a2 2 0 0 0 2 2Zm6-15h12v10H26Z' fill='%23FFFFFF'/%3E%3Cpath d='M30 18l6 5.5V22h-6v-4Z' fill='%230D6EFD'/%3E%3Cpath d='M25 38h14l-2 8H27Z' fill='%23FFFFFF'/%3E%3C/svg%3E">
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
            background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
        }

        .about-page .hero-panel {
            padding: 42px;
            overflow: hidden;
        }

        .about-page .hero-badge {
            background: rgba(255,255,255,0.16);
            color: white;
        }

        .about-illustration {
            border-radius: 24px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.16);
            padding: 24px;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-illustration svg {
            width: 100%;
            max-width: 360px;
            height: auto;
        }

        .about-image-card {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(11,31,58,0.12);
        }

        .about-image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 320px;
        }

        .about-card {
            background: #ffffff;
            border: 1px solid rgba(220,231,242,0.95);
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 16px 36px rgba(11,31,58,0.08);
            height: 100%;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(11,31,58,0.12);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(13,110,253,0.15), rgba(11,31,58,0.09));
            color: #0D6EFD;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }

        .stats-section {
            background: linear-gradient(135deg, #0B1F3A 0%, #102A43 100%);
            color: white;
            border-radius: 28px;
            padding: 36px 24px;
            box-shadow: 0 20px 45px rgba(11,31,58,0.16);
        }

        .stat-item {
            text-align: center;
            padding: 16px 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
        }

        .stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 0.95rem;
        }

        .step-card {
            position: relative;
            background: white;
            border: 1px solid rgba(220,231,242,0.95);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 14px 32px rgba(11,31,58,0.08);
            height: 100%;
        }

        .step-number {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0D6EFD, #102A43);
            color: white;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .cta-panel {
            border-radius: 30px;
            padding: 40px;
            background: linear-gradient(135deg, #0D6EFD 0%, #0B1F3A 100%);
            color: white;
            box-shadow: 0 20px 45px rgba(11,31,58,0.18);
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
                        <li class="nav-item"><a class="nav-link active" href="about.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fa-solid fa-list-check"></i> Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="book.php"><i class="fa-solid fa-calendar-check"></i> Book Service</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_login.php"><i class="fa-solid fa-user-shield"></i> Admin</a></li>
                    </ul>
                    <a class="btn btn-contact" href="book.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="page-shell about-page">
        <div class="page-card page-card--soft page-shell-card">
            <!-- ==================== Hero Section ==================== -->
            <section class="hero-panel hero-section fade-in-section hero-panel--spaced">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="icon-badge"><i class="fa-solid fa-bolt"></i></span>
                            <span class="hero-badge">Trusted Home Service Partner</span>
                        </div>
                        <h1>About Quetta Services Hub</h1>
                        <p>Trusted home services designed to make everyday life easier. We help customers find dependable solutions for repairs, maintenance, cleaning and professional household support.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="services.php" class="btn btn-primary"><i class="fa-solid fa-list-check"></i> Explore Services</a>
                            <a href="book.php" class="btn btn-secondary"><i class="fa-solid fa-calendar-check"></i> Book a Service</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="about-illustration">
                            <svg viewBox="0 0 512 420" role="img" aria-label="Illustration of a professional home service team">
                                <rect x="60" y="90" width="392" height="270" rx="28" fill="#ffffff" opacity="0.96"></rect>
                                <rect x="100" y="120" width="140" height="120" rx="18" fill="#dfefff"></rect>
                                <rect x="270" y="120" width="122" height="92" rx="18" fill="#0D6EFD"></rect>
                                <rect x="100" y="270" width="292" height="56" rx="14" fill="#0B1F3A"></rect>
                                <circle cx="200" cy="222" r="48" fill="#0D6EFD"></circle>
                                <circle cx="200" cy="222" r="28" fill="#ffffff"></circle>
                                <path d="M280 210c16 0 29 13 29 29v34h-58v-34c0-16 13-29 29-29z" fill="#ffffff"></path>
                                <path d="M262 180h36v24h-36z" fill="#0B1F3A"></path>
                                <path d="M248 164c0-28 22-50 50-50s50 22 50 50v12h-100v-12z" fill="#0D6EFD"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-6">
                        <div class="about-image-card">
                            <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1200&q=80" alt="Professional home service team">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="about-card">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="brand-mark brand-mark--about"><i class="fa-solid fa-hands-helping"></i></span>
                                <h2 class="mb-0">Your Trusted Home Service Partner in Quetta</h2>
                            </div>
                            <p class="section-subtitle">Quetta Services Hub connects customers with reliable home service solutions for repairs, maintenance, cleaning and other everyday household needs.</p>
                            <p class="section-subtitle">Our platform provides a simple and convenient way to explore services, compare options and submit bookings in just a few clicks.</p>
                            <a href="book.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Book a Service</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="about-card">
                    <div class="text-center mb-4">
                        <div class="icon-badge mb-3"><i class="fa-solid fa-bullseye"></i></div>
                        <h3 class="mb-2">Our Mission</h3>
                    </div>
                    <p class="section-subtitle text-center mb-0">Our mission is to make home services simple, reliable and accessible by providing customers with convenient booking options and trusted service solutions.</p>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="about-card">
                    <div class="text-center mb-4">
                        <div class="icon-badge mb-3"><i class="fa-solid fa-eye"></i></div>
                        <h3 class="mb-2">Our Vision</h3>
                    </div>
                    <p class="section-subtitle text-center mb-0">Our vision is to become a trusted digital home-services platform for customers across Quetta by combining technology, convenience and professional service.</p>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Why Choose Us</div>
                <p class="section-subtitle">A professional experience designed around convenience, trust and dependable support.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                            <h5 class="fw-bold mb-2">Verified Professionals</h5>
                            <p class="section-subtitle mb-0">Trusted experts committed to delivering quality service.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-clock"></i></div>
                            <h5 class="fw-bold mb-2">Fast Response</h5>
                            <p class="section-subtitle mb-0">Prompt booking support and quick follow-up for urgent needs.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-tags"></i></div>
                            <h5 class="fw-bold mb-2">Transparent Pricing</h5>
                            <p class="section-subtitle mb-0">Clear, straightforward pricing with no hidden surprises.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                            <h5 class="fw-bold mb-2">Easy Booking</h5>
                            <p class="section-subtitle mb-0">A simple booking flow that saves time and effort.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Our Values</div>
                <p class="section-subtitle">The principles that guide every interaction and service experience.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-handshake"></i></div>
                            <h5 class="fw-bold mb-2">Trust</h5>
                            <p class="section-subtitle mb-0">Building confidence through honesty and dependable support.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-award"></i></div>
                            <h5 class="fw-bold mb-2">Quality</h5>
                            <p class="section-subtitle mb-0">Delivering thoughtful service with attention to detail.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                            <h5 class="fw-bold mb-2">Reliability</h5>
                            <p class="section-subtitle mb-0">Consistent, dependable service when customers need it most.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="about-card">
                            <div class="feature-icon"><i class="fa-solid fa-heart"></i></div>
                            <h5 class="fw-bold mb-2">Customer Satisfaction</h5>
                            <p class="section-subtitle mb-0">Creating a smooth experience that exceeds expectations.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="stats-section">
                    <div class="row g-4">
                        <div class="col-6 col-lg-3">
                            <div class="stat-item">
                                <div class="stat-value" data-count="1000">0</div>
                                <div class="stat-label">Happy Customers</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-item">
                                <div class="stat-value" data-count="50">0</div>
                                <div class="stat-label">Professional Services</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-item">
                                <div class="stat-value" data-count="24">0</div>
                                <div class="stat-label">Booking Support</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-item">
                                <div class="stat-value" data-count="100">0</div>
                                <div class="stat-label">Customer Care</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">How It Works</div>
                <p class="section-subtitle">A simple four-step process from discovery to service completion.</p>
                <div class="row g-4 mt-1">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <h5 class="fw-bold mb-2">Explore Services</h5>
                            <p class="section-subtitle mb-0">Browse the available home services and choose the one that fits your need.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="step-card">
                            <div class="step-number">2</div>
                            <h5 class="fw-bold mb-2">Select a Service</h5>
                            <p class="section-subtitle mb-0">Pick the right service and review the details before booking.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="step-card">
                            <div class="step-number">3</div>
                            <h5 class="fw-bold mb-2">Submit Booking</h5>
                            <p class="section-subtitle mb-0">Fill in your details and send your booking request securely.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="step-card">
                            <div class="step-number">4</div>
                            <h5 class="fw-bold mb-2">Get Service</h5>
                            <p class="section-subtitle mb-0">We help connect you to a professional who completes the service.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="cta-panel text-center">
                    <h3 class="mb-3">Ready to Get Started?</h3>
                    <p class="mb-4">Find the right home service for your needs and book it in just a few simple steps.</p>
                    <a href="services.php" class="btn btn-primary"><i class="fa-solid fa-list-check"></i> Explore Services</a>
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
                        <a class="footer-link" href="about.php"><i class="fa-solid fa-chevron-right"></i> About Us</a>
                        <a class="footer-link" href="services.php"><i class="fa-solid fa-chevron-right"></i> Services</a>
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

    <script>
        $(function () {
            $('.stat-value').each(function () {
                const $this = $(this);
                const target = parseInt($this.data('count'), 10) || 0;
                let current = 0;
                const duration = 1400;
                const step = Math.max(1, Math.floor(target / (duration / 30)));

                const timer = setInterval(function () {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    $this.text(current + (target === 24 ? '+' : target === 100 ? '%' : ''));
                }, 30);
            });
        });
    </script>
</body>
</html>
