<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | ASCB' : 'Andres Soriano Colleges of Bislig | Quality Education in Bislig City'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description) : 'Andres Soriano Colleges of Bislig (ASCB) is a leading private educational institution in Bislig City offering basic, technical-vocational, and higher education programs.'; ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/ascb-logo.png">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css?v=1.0.2">
</head>
<body>

<header id="mainHeader" class="header-transparent">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-5">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo URLROOT; ?>/">
                <img src="/images/ascb-logo-transparent.png" alt="ASCB Logo" style="height: 60px;">
                <div class="ms-2 text-white lh-sm d-none d-md-block">
                    <strong class="d-block" style="font-size: 1.1rem; font-weight: 700;">Andres Soriano</strong>
                    <small class="d-block" style="font-size: 0.7rem; letter-spacing: 1px; opacity: 0.9;">COLLEGES OF BISLIG</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/about">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/admissions">Admissions</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/academics">Academics</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/scholarships">Scholarships</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/student-life">Student Life</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/policies">Policies</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo URLROOT; ?>/news">News</a></li>
                    <li class="nav-item ms-lg-3 me-2 d-flex align-items-center">
                        <a href="https://www.facebook.com/AndresSorianoCollege" target="_blank" rel="noopener noreferrer" class="nav-link text-white px-2" aria-label="Follow ASCB on Facebook (opens in new tab)">
                            <i data-lucide="facebook" aria-hidden="true" style="width:24px;height:24px;color:#1877F2;fill:#1877F2;"></i>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-light text-primary fw-bold px-4 py-2" style="border-radius: 8px;" href="<?php echo URLROOT; ?>/inquire">Inquire Now</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
