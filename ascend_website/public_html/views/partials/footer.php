<!-- Footer -->
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="/images/ascb-logo-transparent.png" alt="ASCB Logo" class="mb-3" style="max-height: 80px;">
                <p>Andres Soriano Colleges of Bislig envisions itself as a leading private educational institution...</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light text-decoration-none">Admissions</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Academic Programs</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Student Services</a></li>
                    <li><a href="/admin/login.php" class="text-light text-decoration-none">Staff Login</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contact Us</h5>
                <p>
                    <i data-lucide="map-pin"></i> Andres Soriano Ave, Mangagoy, Bislig City<br>
                    <i data-lucide="phone" class="me-2 mb-2"></i> (086) 853-2222<br>
                    <i data-lucide="mail" class="me-2 mb-2"></i> info@ascb.edu.ph<br>
                    <a href="https://www.facebook.com/AndresSorianoCollege" target="_blank" class="text-light text-decoration-none mt-2 d-inline-block">
                        <i data-lucide="facebook" class="me-2"></i> Andres Soriano College
                    </a>
                </p>
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

