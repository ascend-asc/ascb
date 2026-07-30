<?php
// Fetch Active Hero Slides
$db->query('SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC, id DESC');
$slides = $db->resultSet();

// Fetch Institutional Identity
$db->query('SELECT * FROM site_identity LIMIT 1');
$identity = $db->single();
$cv = ['A'=>[], 'S'=>[], 'C'=>[], 'B'=>[]];
if ($identity && $identity->core_values) {
    $parsed = json_decode($identity->core_values, true);
    if(is_array($parsed)) $cv = array_merge($cv, $parsed);
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Hero Slider -->
<div class="swiper hero-slider">
    <div class="swiper-wrapper">
        <?php if(empty($slides)): ?>
            <!-- Fallback slide if none in DB -->
            <div class="swiper-slide" style="background: linear-gradient(135deg, #0B2F6B 0%, #1F4E9C 100%); display: flex; align-items: center; justify-content: center;">
                <div class="slide-content" style="position: relative; bottom: auto; left: auto; text-align: center;">
                    <h1 class="slide-title">ASCB, Ascending!</h1>
                    <p class="slide-subtitle">Welcome to Andres Soriano Colleges of Bislig.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach($slides as $slide): ?>
            <div class="swiper-slide" style="background-image: url('<?php echo URLROOT; ?>/<?php echo htmlspecialchars($slide->image_path); ?>');">
                <div class="slide-content">
                    <h1 class="slide-title"><?php echo htmlspecialchars($slide->title); ?></h1>
                    <?php if(!empty($slide->subtitle)): ?>
                        <p class="slide-subtitle"><?php echo htmlspecialchars($slide->subtitle); ?></p>
                    <?php endif; ?>
                    <?php if(!empty($slide->cta_label)): ?>
                        <a href="<?php echo htmlspecialchars($slide->cta_link); ?>" class="btn btn-cta btn-lg"><?php echo htmlspecialchars($slide->cta_label); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- Custom Inline Controls -->
    <div class="swiper-controls-container">
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>

<!-- Mission, Vision & Core Values Band -->
<section class="identity-band">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary"><?php echo htmlspecialchars($identity->motto ?? 'Our Institutional Identity'); ?></h2>
            <p class="lead text-muted">Guided by a commitment to excellence, inclusivity, and service.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 mb-4">
                <div class="identity-card">
                    <h4>Vision</h4>
                    <p><?php echo nl2br(htmlspecialchars($identity->vision ?? 'ASCB envisions itself as a leading private educational institution...')); ?></p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="identity-card">
                    <h4>Mission</h4>
                    <p><?php echo nl2br(htmlspecialchars($identity->mission ?? 'Guided by a commitment to excellence...')); ?></p>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-2">
            <?php foreach(['A', 'S', 'C', 'B'] as $letter): ?>
            <div class="col-md-3 text-center">
                <div class="identity-card text-center">
                    <div class="cv-badge-wrapper">
                        <div class="cv-badge">
                            <h1 class="display-4 text-warning fw-bold m-0"><?php echo $letter; ?></h1>
                        </div>
                    </div>
                    <h5 class="cv-title"><?php echo htmlspecialchars($cv[$letter]['label'] ?? ''); ?></h5>
                    <p class="small mt-2"><?php echo htmlspecialchars($cv[$letter]['desc'] ?? ''); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Initialize Swiper -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper(".hero-slider", {
            spaceBetween: 0,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    });
</script>

<!-- School History Section -->
<section class="py-5" style="background: #f8faff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 mb-3 d-inline-block" style="font-size:0.85rem; border-radius:20px;">Our Story</span>
                <h2 class="fw-bold text-dark mb-4" style="font-size:2rem; line-height:1.3;">About Andres Soriano<br>Colleges of Bislig</h2>
                <p class="text-muted mb-3" style="font-size:1.05rem; line-height:1.8;">
                    Andres Soriano Colleges of Bislig (ASCB) traces its roots back to <strong>1952</strong>, when civic-spirited citizens formed the <em>"South East Pacific Institute."</em>
                </p>
                <p class="text-muted mb-4" style="font-size:1.05rem; line-height:1.8;">
                    Over the decades it grew into Andres Soriano Institute (1954), Andres Soriano Junior College (1967), and finally <strong>Andres Soriano Colleges, Incorporated in 1971</strong>. Today, ASCB stands as a leading private educational institution offering basic education, technical-vocational, and higher education programs.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="text-center p-3 rounded-3 bg-white shadow-sm" style="min-width:100px;">
                        <div class="fw-bold text-primary" style="font-size:1.8rem;">1952</div>
                        <small class="text-muted">Founded</small>
                    </div>
                    <div class="text-center p-3 rounded-3 bg-white shadow-sm" style="min-width:100px;">
                        <div class="fw-bold text-primary" style="font-size:1.8rem;">70+</div>
                        <small class="text-muted">Years of Excellence</small>
                    </div>
                    <div class="text-center p-3 rounded-3 bg-white shadow-sm" style="min-width:100px;">
                        <div class="fw-bold text-primary" style="font-size:1.8rem;">2052</div>
                        <small class="text-muted">Centennial</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-white shadow-sm border-start border-4 border-primary">
                    <h5 class="fw-bold text-primary mb-3"><i data-lucide="quote" class="me-2" style="width:20px;height:20px;"></i> Our Motto</h5>
                    <blockquote class="mb-0">
                        <p class="fw-semibold fs-5 text-dark mb-2">"ASCB, Ascending!"</p>
                        <p class="text-muted" style="line-height:1.7;">Capturing the spirit of a dynamic and visionary institution continually striving for greater heights, honoring our legacy while embracing the future as we move toward our centennial in 2052.</p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accreditation Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-3 py-2 mb-3 d-inline-block" style="font-size:0.85rem; border-radius:20px;">Recognized & Accredited</span>
            <h2 class="fw-bold text-dark">Accreditation & Recognition</h2>
            <p class="text-muted lead">ASCB is officially recognized by the following government bodies.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm h-100 p-4 text-center" style="border-radius:16px;">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;">
                            <i data-lucide="graduation-cap" style="width:30px;height:30px;color:#0B2F6B;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">CHED Recognized</h5>
                    <p class="text-muted mb-0">Officially recognized by the <strong>Commission on Higher Education (CHED)</strong> as a Higher Education Institution offering undergraduate and graduate degree programs.</p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card border-0 shadow-sm h-100 p-4 text-center" style="border-radius:16px;">
                    <div class="mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;">
                            <i data-lucide="badge-check" style="width:30px;height:30px;color:#198754;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">TESDA Authorized</h5>
                    <p class="text-muted mb-0">Authorized by <strong>TESDA</strong> to provide nationally certified technical-vocational training programs aligned with industry standards.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

