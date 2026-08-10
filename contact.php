<?php
// ==================== Contact Page Logic ====================
// This page handles the contact form message flow without changing existing behavior.
$activePage = 'contact';
include 'config.php';

$message = '';
$messageType = '';

// ==================== Contact Form Handling ====================
// Validate the submitted contact details and show a simple success or error response.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $messageText = trim($_POST['message'] ?? '');

    if ($firstName !== '' && $lastName !== '' && $email !== '' && $phone !== '' && $subject !== '' && $messageText !== '') {
        $message = 'Thank you for contacting Quetta Services Hub. We have received your message and will get back to you shortly.';
        $messageType = 'success';
    } else {
        $message = 'Please complete all required fields before submitting your message.';
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Quetta Services Hub</title>
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

        .contact-page .hero-panel {
            padding: 42px;
            overflow: hidden;
        }

        .contact-page .hero-badge {
            background: rgba(255,255,255,0.16);
            color: white;
        }

        .contact-illustration {
            border-radius: 24px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.16);
            padding: 24px;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-illustration svg {
            width: 100%;
            max-width: 360px;
            height: auto;
        }

        .info-card,
        .why-card,
        .form-card,
        .location-card {
            background: #ffffff;
            border: 1px solid rgba(220,231,242,0.95);
            border-radius: 22px;
            box-shadow: 0 16px 36px rgba(11,31,58,0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .info-card:hover,
        .why-card:hover,
        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(11,31,58,0.12);
        }

        .info-card {
            padding: 22px;
            height: 100%;
        }

        .info-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(13,110,253,0.16), rgba(11,31,58,0.08));
            color: #0D6EFD;
            font-size: 1.1rem;
            margin-bottom: 12px;
        }

        .form-card {
            padding: 28px;
        }

        .form-control,
        .form-select,
        textarea {
            border-radius: 14px;
            border: 1px solid #dce7f2;
            padding: 12px 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #0D6EFD;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.14);
        }

        .input-group-text {
            background: #f4f8ff;
            border: 1px solid #dce7f2;
            color: #0B1F3A;
        }

        .why-card {
            padding: 22px;
            height: 100%;
        }

        .location-card {
            padding: 24px;
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
            border-color: rgba(13,110,253,0.2);
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
                        <li class="nav-item"><a class="nav-link" href="about.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fa-solid fa-list-check"></i> Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="book.php"><i class="fa-solid fa-calendar-check"></i> Book Service</a></li>
                        <li class="nav-item"><a class="nav-link active" href="contact.php"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_login.php"><i class="fa-solid fa-user-shield"></i> Admin</a></li>
                    </ul>
                    <a class="btn btn-contact" href="book.php"><i class="fa-solid fa-phone"></i> Contact Us</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="page-shell contact-page">
        <div class="page-card page-card--soft page-shell-card">
            <!-- ==================== Hero Section ==================== -->
            <section class="hero-panel hero-section fade-in-section hero-panel--spaced">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="icon-badge"><i class="fa-solid fa-bolt"></i></span>
                            <span class="hero-badge">We're here to help</span>
                        </div>
                        <h1>Contact Quetta Services Hub</h1>
                        <p>Have a question or need help choosing a home service? We're here to help. Reach out to our team and we'll guide you quickly and professionally.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="services.php" class="btn btn-primary"><i class="fa-solid fa-list-check"></i> View Services</a>
                            <a href="book.php" class="btn btn-secondary"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="contact-illustration">
                            <svg viewBox="0 0 512 420" role="img" aria-label="Illustration of customer support contact">
                                <rect x="80" y="90" width="352" height="250" rx="28" fill="#ffffff" opacity="0.96"></rect>
                                <rect x="120" y="140" width="272" height="116" rx="18" fill="#dfefff"></rect>
                                <rect x="150" y="170" width="96" height="10" rx="5" fill="#0B1F3A" opacity="0.8"></rect>
                                <rect x="150" y="192" width="140" height="10" rx="5" fill="#0B1F3A" opacity="0.5"></rect>
                                <circle cx="336" cy="196" r="44" fill="#0D6EFD"></circle>
                                <path d="M318 196c0-20 16-36 36-36s36 16 36 36" fill="none" stroke="#ffffff" stroke-width="10" stroke-linecap="round"></path>
                                <path d="M318 209c0-10 8-18 18-18h36c10 0 18 8 18 18" fill="none" stroke="#ffffff" stroke-width="10" stroke-linecap="round"></path>
                                <rect x="134" y="280" width="224" height="26" rx="13" fill="#0B1F3A"></rect>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ==================== Contact Information ==================== -->
            <section class="section-block fade-in-section">
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-card">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <h5 class="fw-bold mb-2">Phone</h5>
                            <p class="section-subtitle mb-0">+92 300 1234567</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-card">
                            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                            <h5 class="fw-bold mb-2">Email</h5>
                            <p class="section-subtitle mb-0">info@quettaserviceshub.com</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-card">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <h5 class="fw-bold mb-2">Location</h5>
                            <p class="section-subtitle mb-0">Quetta, Balochistan, Pakistan</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="info-card">
                            <div class="info-icon"><i class="fa-solid fa-headset"></i></div>
                            <h5 class="fw-bold mb-2">Support</h5>
                            <p class="section-subtitle mb-0">24/7 Customer Support</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-lg-7">
                        <div class="form-card">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="icon-badge"><i class="fa-solid fa-paper-plane"></i></span>
                                <h3 class="mb-0">Send Us a Message</h3>
                            </div>
                            <p class="section-subtitle">Fill out the form below and our team will get back to you shortly.</p>

                            <?php if ($message !== '') : ?>
                                <div class="alert <?= htmlspecialchars($messageType === 'success' ? 'alert-success' : 'alert-error') ?>">
                                    <?= htmlspecialchars($message) ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="first_name"><i class="fa-solid fa-user me-2"></i>First Name</label>
                                        <input id="first_name" name="first_name" type="text" class="form-control" placeholder="Enter your first name" required>
                                        <div class="invalid-feedback">Please enter your first name.</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="last_name"><i class="fa-solid fa-user me-2"></i>Last Name</label>
                                        <input id="last_name" name="last_name" type="text" class="form-control" placeholder="Enter your last name" required>
                                        <div class="invalid-feedback">Please enter your last name.</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="email"><i class="fa-solid fa-envelope me-2"></i>Email Address</label>
                                        <input id="email" name="email" type="email" class="form-control" placeholder="Enter your email" required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="phone"><i class="fa-solid fa-phone me-2"></i>Phone Number</label>
                                        <input id="phone" name="phone" type="tel" class="form-control" placeholder="Enter your phone number" required>
                                        <div class="invalid-feedback">Please enter your phone number.</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="subject"><i class="fa-solid fa-tag me-2"></i>Subject</label>
                                        <input id="subject" name="subject" type="text" class="form-control" placeholder="What do you need help with?" required>
                                        <div class="invalid-feedback">Please enter a subject.</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="message"><i class="fa-solid fa-comment-dots me-2"></i>Message</label>
                                        <textarea id="message" name="message" class="form-control" rows="5" placeholder="Write your message here" required></textarea>
                                        <div class="invalid-feedback">Please enter your message.</div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-4" type="submit"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="why-card mb-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="icon-badge"><i class="fa-solid fa-circle-question"></i></span>
                                <h3 class="mb-0">Why Contact Us?</h3>
                            </div>
                            <div class="d-grid gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1">Quick Response</h6>
                                    <p class="section-subtitle mb-0">We respond quickly to help you with booking and service questions.</p>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Helpful Support</h6>
                                    <p class="section-subtitle mb-0">Our team is ready to guide you through every step of the process.</p>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Trusted Services</h6>
                                    <p class="section-subtitle mb-0">We connect you with dependable home services across Quetta.</p>
                                </div>
                            </div>
                        </div>

                        <div class="location-card">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="icon-badge"><i class="fa-solid fa-map-location-dot"></i></span>
                                <h3 class="mb-0">Find Us in Quetta</h3>
                            </div>
                            <p class="section-subtitle mb-3">Quetta, Balochistan, Pakistan</p>
                            <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #f4f8ff, #eef6ff); border: 1px solid #dce7f2;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-location-dot text-primary"></i>
                                    <strong>Service Area</strong>
                                </div>
                                <p class="mb-0 section-subtitle">We support customers across Quetta and nearby areas with professional home service assistance.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="section-title">Frequently Asked Questions</div>
                <p class="section-subtitle">Helpful answers for common questions before you contact us.</p>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How can I book a service?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Visit the booking page and submit your request with your preferred service details.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Which areas do you cover?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">We support customers across Quetta and nearby areas within our service coverage.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How quickly will you respond?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">We aim to respond as quickly as possible and follow up with customers promptly.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Can I ask about service pricing?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes. You can contact us to ask about available services and pricing details.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Can I change my booking?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="faqFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Please contact us as soon as possible and we will assist you with your booking request.</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-block fade-in-section">
                <div class="cta-panel text-center">
                    <h3 class="mb-3">Need a Home Service?</h3>
                    <p class="mb-4">Explore our professional services and book the help you need today.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="services.php" class="btn btn-primary"><i class="fa-solid fa-list-check"></i> View Services</a>
                        <a href="book.php" class="btn btn-secondary"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
                    </div>
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
            $('.needs-validation').on('submit', function (event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(this).addClass('was-validated');
            });
        });
    </script>
</body>
</html>
