</main>
<!-- Footer -->
<footer class="bg-dark text-light py-5 mt-5" role="contentinfo">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="/images/ascb-logo-transparent.png" alt="Andres Soriano Colleges of Bislig Logo" class="mb-3" style="max-height: 80px;">
                <p>Andres Soriano Colleges of Bislig envisions itself as a leading private educational institution...</p>
            </div>
            <div class="col-md-4 mb-4">
                <h2 class="h5">Quick Links</h2>
                <ul class="list-unstyled">
                    <li><a href="<?php echo URLROOT; ?>/admissions" class="text-light text-decoration-none">Admissions</a></li>
                    <li><a href="<?php echo URLROOT; ?>/academics" class="text-light text-decoration-none">Academic Programs</a></li>
                    <li><a href="<?php echo URLROOT; ?>/student-life" class="text-light text-decoration-none">Student Services</a></li>
                    <li><a href="/admin/login.php" class="text-light text-decoration-none">Staff Login</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h2 class="h5">Contact Us</h2>
                <address style="font-style: normal;">
                    <p class="mb-1"><i data-lucide="map-pin" aria-hidden="true"></i> Andres Soriano Ave, Mangagoy, Bislig City</p>
                    <p class="mb-1"><i data-lucide="phone" aria-hidden="true" class="me-2"></i> <a href="tel:+6386853222" class="text-light text-decoration-none">(086) 853-2222</a></p>
                    <p class="mb-1"><i data-lucide="mail" aria-hidden="true" class="me-2"></i> <a href="mailto:info@ascb.edu.ph" class="text-light text-decoration-none">info@ascb.edu.ph</a></p>
                    <a href="https://www.facebook.com/AndresSorianoCollege" target="_blank" rel="noopener noreferrer" class="text-light text-decoration-none mt-2 d-inline-block" aria-label="Visit ASCB on Facebook (opens in new tab)">
                        <i data-lucide="facebook" aria-hidden="true" class="me-2"></i> Andres Soriano College
                    </a>
                </address>
            </div>
        </div>
        <hr class="mt-4 mb-3">
        <div class="text-center text-white-50">
            <small>&copy; <?php echo date('Y'); ?> Andres Soriano Colleges of Bislig. All rights reserved.</small>
        </div>
    </div>
</footer>

<!-- Vanilla JS for Sticky Header -->
<script>
    window.addEventListener('scroll', function() {
        const header = document.getElementById('mainHeader');
        if (window.scrollY > 60) {
            header.classList.remove('transparent');
            header.classList.add('solid');
        } else {
            header.classList.remove('solid');
            header.classList.add('transparent');
        }
    });
</script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>

