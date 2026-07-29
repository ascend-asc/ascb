<?php
/**
 * index.php — Main Page
 * ======================================
 * ASCB Website — Andres Soriano Colleges of Bislig
 *
 * Includes config.php for the database connection, then queries the
 * `events` table to power the News & Events section dynamically.
 */
require_once 'config.php';

// ─── Fetch all events ordered by date (newest first) ──────────────────────
$events = [];
$sql = "SELECT id, title, description, event_date, image
           FROM events
           ORDER BY event_date DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $events[] = $row;
  }
}
// ──────────────────────────────────────────────────────────────────────────
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Andres Soriano Colleges of Bislig</title>
  <meta name="description"
    content="Andres Soriano Colleges of Bislig – Shaping futures through quality education in Bislig City, Surigao del Sur, Philippines." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;600&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

  <!-- STICKY NAV -->
  <header id="navbar" class="navbar">
    <div class="nav-inner">
      <a href="#hero" class="nav-logo" aria-label="ASCB Home">
        <div class="nav-crest">
          <img src="img/ascb.png" alt="ASCB Logo" class="nav-logo-img" />
        </div>
        <span class="nav-brand">Andres Soriano Colleges of Bislig</span>
      </a>
      <nav class="nav-links" id="nav-links">
        <a href="#about" class="nav-link">About</a>
        <a href="#academics" class="nav-link">Academics</a>
        <a href="#news" class="nav-link">News &amp; Events</a>
        <a href="#contact" class="nav-link">Contact</a>
        <a href="#contact" class="nav-cta">Enroll Now</a>
      </nav>
      <button class="nav-toggle" id="nav-toggle" aria-label="Toggle navigation">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- HERO SLIDER -->
  <section id="hero" class="hero">
    <!-- Slides -->
    <div class="hero-slides" id="heroSlides">
      <div class="hero-slide active" style="background-image:url('img/hero-slide/ascb.jpg')"></div>
      <div class="hero-slide"
        style="background-image:url('img/hero-slide/66786245_10157542599657716_4039434716326133760_n.jpg')"></div>
      <div class="hero-slide"
        style="background-image:url('img/hero-slide/67116241_10157542607997716_9176474279632437248_n.jpg')"></div>
    </div>
    <div class="hero-overlay"></div>

    <!-- Content -->
    <div class="hero-content">
      <div class="hero-logo-wrap">
        <img src="img/ascb.png" alt="Andres Soriano Colleges of Bislig Logo" class="hero-logo-img" />
      </div>
      <h1 class="hero-title">Andres Soriano<br /><span>Colleges of Bislig</span></h1>
      <p class="hero-tagline">Shaping futures through quality education, strong values, and excellence.</p>
      <div class="hero-actions">
        <a href="#academics" class="btn btn-primary">Explore Programs</a>
        <a href="#about" class="btn btn-outline">Our Story</a>
      </div>
    </div>

    <!-- Prev / Next arrows -->
    <button class="hero-arrow hero-prev" id="heroPrev" aria-label="Previous slide">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button class="hero-arrow hero-next" id="heroNext" aria-label="Next slide">
      <i class="fa-solid fa-chevron-right"></i>
    </button>

    <!-- Dots navigation -->
    <div class="hero-dots" id="heroDots" aria-label="Slide indicators">
      <button class="hero-dot active" data-index="0" aria-label="Slide 1"></button>
      <button class="hero-dot" data-index="1" aria-label="Slide 2"></button>
      <button class="hero-dot" data-index="2" aria-label="Slide 3"></button>
    </div>

    <div class="hero-scroll-hint" aria-hidden="true">
      <span>Scroll</span>
      <div class="scroll-arrow"></div>
    </div>
  </section>

  <!-- ABOUT -->
  <section id="about" class="section about">
    <div class="container">
      <div class="section-label reveal">Our Heritage</div>
      <h2 class="section-title reveal">About the College</h2>
      <div class="about-grid">
        <div class="about-text reveal">
          <h3 class="about-subtitle">Our Story</h3>
          <p>In 1952, Civic Spirited Citizens formed a nucleus to establish a school named <strong>"South East Pacific
              Institute"</strong>. This was changed to <strong>Andres Soriano Institute</strong> in 1954, <strong>Andres
              Soriano Junior College</strong> in 1967, and renamed <strong>Andres Soriano Colleges,
              Incorporated</strong> in 1971; and finally registered with the SEC on June 17, 1971.</p>
          <p>On July 19, 2014, a devastating fire struck the institution. However, united under the spirit of <em>"From
              the Ashes, We Rise,"</em> the ASCB community rebuilt the school&mdash;remembering the loss, honoring the
            strength, and celebrating the rise.</p>
          <p>Rooted in the values of integrity, service, and excellence, ASCB has continuously evolved into a leading
            educational institution in Bislig City and Surigao del Sur, preparing students for national and global
            challenges.</p>
          <div class="mission-vision-tabs">
            <button class="tab-btn active" data-tab="mission">Mission</button>
            <button class="tab-btn" data-tab="vision">Vision</button>
          </div>
          <div class="tab-content" id="content-mission">
            <p class="tab-text"><em>"Guided by a commitment to excellence, inclusivity, and service, Andres Soriano
                Colleges of Bislig provides holistic, accessible, and quality basic, technical-vocational, and higher
                education programs that cultivate lifelong learning, critical thinking, and innovation; uphold
                integrity, social responsibility, and cultural heritage; equip graduates with 21st-century competencies
                for local and global relevance; and strengthen linkages with industry, government, and civil society to
                advance sustainable development."</em></p>
          </div>
          <div class="tab-content hidden" id="content-vision">
            <p class="tab-text"><em>"ASCB envisions itself as a leading private educational institution in the region
                and beyond, fostering an empowering and transformative education that develops globally competent,
                values-driven, and socially engaged individuals."</em></p>
          </div>
        </div>
        <div class="about-highlights reveal">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
            <div class="stat-number">1952</div>
            <div class="stat-label">Year Founded</div>
          </div>
          <img src="img/history.jpg" alt="ASCB - From the Ashes, We Rise"
            style="width: 100%; border-radius: 12px; margin-top: 1.5rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);" />
        </div>
      </div>
  </section>

  <div class="seal-divider" aria-hidden="true">
    <div class="divider-line"></div>
    <div class="divider-seal">✦</div>
    <div class="divider-line"></div>
  </div>

  <!-- ACADEMICS -->
  <section id="academics" class="section academics">
    <div class="container">
      <div class="section-label reveal">Programs of Study</div>
      <h2 class="section-title reveal">Academics</h2>
      <p class="section-desc reveal">Offering a comprehensive range of programs designed to meet the demands of a
        rapidly evolving world.</p>
      <div class="grade-tabs reveal" role="tablist">
        <button class="grade-tab active" data-grade="college">College</button>
        <button class="grade-tab" data-grade="shs">Senior High School</button>
        <button class="grade-tab" data-grade="basic">Basic Education</button>
      </div>
      <div class="programs-grid" id="grade-college">
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#4081B2;"><i class="fa-solid fa-chalkboard-user"></i></div>
          <h3>College of Teacher Education (CTE)</h3>
          <p>Training future educators with modern pedagogical approaches and inclusive teaching strategies for holistic
            student development.</p>
          <div class="program-tags"><span>Education</span><span>Teaching</span><span>Curriculum</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#C9A227;"><i class="fa-solid fa-scale-balanced"></i></div>
          <h3>College of Criminal Justice Education (CCJE)</h3>
          <p>Equipping students with comprehensive knowledge in criminal justice, law enforcement, and forensic science
            for public service.</p>
          <div class="program-tags"><span>Law</span><span>Forensics</span><span>Public Safety</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#162659;"><i class="fa-solid fa-laptop-code"></i></div>
          <h3>College of Computer Education (CCE)</h3>
          <p>Develop cutting-edge software solutions, systems, and networks. Covers programming, databases,
            cybersecurity, and AI fundamentals.</p>
          <div class="program-tags"><span>Programming</span><span>Networking</span><span>AI</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#4081B2;"><i class="fa-solid fa-briefcase"></i></div>
          <h3>College of Business &amp; Accountancy Education (CBAE)</h3>
          <p>Preparing future business leaders and accountants with skills in management, finance, marketing, and
            entrepreneurship.</p>
          <div class="program-tags"><span>Management</span><span>Finance</span><span>Accounting</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#C9A227;"><i class="fa-solid fa-drafting-compass"></i></div>
          <h3>College of Arts &amp; Engineering (CAE)</h3>
          <p>Cultivating critical thinkers and developing engineering solutions through rigorous academic and practical
            training.</p>
          <div class="program-tags"><span>Arts</span><span>Engineering</span><span>Design</span></div>
        </div>
      </div>
      <div class="programs-grid hidden" id="grade-shs">
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#4081B2;"><i class="fa-solid fa-atom"></i></div>
          <div class="program-badge track">STEM Track</div>
          <h3>Science, Technology, Engineering &amp; Math</h3>
          <p>Rigorous preparation for college programs in engineering, medicine, IT, and natural sciences through
            inquiry-based learning.</p>
          <div class="program-tags"><span>Physics</span><span>Calculus</span><span>Chemistry</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#C9A227;"><i class="fa-solid fa-coins"></i></div>
          <div class="program-badge track">ABM Track</div>
          <h3>Accountancy, Business &amp; Management</h3>
          <p>Foundational studies in accounting, business operations, entrepreneurship, and economics for future
            business leaders.</p>
          <div class="program-tags"><span>Accounting</span><span>Business</span><span>Economics</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#C9A227;"><i class="fa-solid fa-comments"></i></div>
          <div class="program-badge track">ASSH Track</div>
          <h3>Arts, Social Sciences &amp; Humanities</h3>
          <p>Ideal for future Teachers, Public Servants, and Social Leaders who are passionate about communication,
            governance, and the humanities.</p>
          <div class="program-tags"><span>Humanities</span><span>Governance</span><span>Communication</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#C9A227;"><i class="fa-solid fa-code"></i></div>
          <div class="program-badge track">TECHPRO Track</div>
          <h3>Technical Professional</h3>
          <p>For students aiming to become ICT Professionals and Skilled Technicians, with TESDA-aligned training such
            as Computer Programming, Systems Servicing, and Web Development.</p>
          <div class="program-tags"><span>ICT</span><span>TESDA</span><span>Programming</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#162659;"><i class="fa-solid fa-chart-line"></i></div>
          <div class="program-badge track">BE Track</div>
          <h3>Business &amp; Entrepreneurship</h3>
          <p>Perfect for future Accountants, Business Leaders, and Entrepreneurs who want to learn accounting,
            marketing, finance, and business management.</p>
          <div class="program-tags"><span>Business</span><span>Accounting</span><span>Marketing</span></div>
        </div>
      </div>
      <div class="programs-grid hidden" id="grade-basic">
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#4081B2;"><i class="fa-solid fa-child"></i></div>
          <div class="program-badge">K–6</div>
          <h3>Elementary Department</h3>
          <p>Nurturing foundational literacy, numeracy, and character development in a safe, engaging, and
            values-centered environment.</p>
          <div class="program-tags"><span>K–6</span><span>Literacy</span><span>Values Formation</span></div>
        </div>
        <div class="program-card reveal">
          <div class="program-icon" style="--icon-color:#C9A227;"><i class="fa-solid fa-school"></i></div>
          <div class="program-badge">Grades 7–10</div>
          <h3>Junior High School</h3>
          <p>A comprehensive Junior High School curriculum aligned with the K–12 program, developing critical thinking
            and life skills.</p>
          <div class="program-tags"><span>Grades 7–10</span><span>K–12</span><span>Critical Thinking</span></div>
        </div>
      </div>
    </div>
    </div>
  </section>

  <div class="seal-divider" aria-hidden="true">
    <div class="divider-line"></div>
    <div class="divider-seal">✦</div>
    <div class="divider-line"></div>
  </div>

  <!-- ================================================================
       NEWS & EVENTS — Dynamically powered by the MySQL `events` table
       ================================================================ -->
  <section id="news" class="section news-events">
    <div class="container">
      <div class="section-label reveal">Bulletin Board</div>
      <h2 class="section-title reveal">News &amp; Events</h2>
      <p class="section-desc reveal">Stay connected with the latest happenings and upcoming activities at ASCB.</p>

      <?php if (empty($events)): ?>
        <!-- ── Empty state: shown when no events exist in the database ── -->
        <div class="events-empty reveal">
          <i class="fa-solid fa-calendar-xmark"></i>
          <p>No news or events to display at the moment. Check back soon!</p>
        </div>

      <?php else: ?>
        <!-- ── Featured + Timeline layout (mirrors original design) ── -->
        <div class="news-layout">

          <?php
          // The FIRST event becomes the large "featured" card on the left
          $featured = $events[0];
          $featuredDate = !empty($featured['event_date'])
            ? date('M d, Y', strtotime($featured['event_date']))
            : '';
          ?>
          <div class="news-featured reveal">
            <?php if (!empty($featured['image'])): ?>
              <!-- Event image (filename stored in DB, file lives in img/news&events/) -->
              <img src="img/news&events/<?= htmlspecialchars($featured['image']) ?>"
                alt="<?= htmlspecialchars($featured['title']) ?>" class="news-featured-img" />
            <?php endif; ?>
            <div class="news-eyebrow">
              <?php if ($featuredDate): ?>
                <span class="mono-date"><?= htmlspecialchars($featuredDate) ?></span>
              <?php endif; ?>
              <span class="news-tag announcement">Latest</span>
            </div>
            <h3 class="news-featured-title"><?= htmlspecialchars($featured['title']) ?></h3>
            <p class="news-featured-body"><?= nl2br(htmlspecialchars($featured['description'] ?? '')) ?></p>
            <div class="news-meta"><i class="fa-solid fa-user-pen"></i> ASCB Communications Office</div>
          </div>

          <!-- Remaining events displayed as timeline items on the right -->
          <div class="news-timeline reveal">
            <?php
            // Loop through events AFTER the first one for the timeline
            $timelineEvents = array_slice($events, 1, 5); // show up to 5 in timeline
            foreach ($timelineEvents as $ev):
              $evDate = !empty($ev['event_date'])
                ? date('M d, Y', strtotime($ev['event_date']))
                : '';
              ?>
              <article class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                  <?php if ($evDate): ?>
                    <span class="mono-date"><?= htmlspecialchars($evDate) ?></span>
                  <?php endif; ?>
                  <span class="news-tag event">Event</span>
                  <h4><?= htmlspecialchars($ev['title']) ?></h4>
                  <p><?= htmlspecialchars(mb_strimwidth($ev['description'] ?? '', 0, 160, '…')) ?></p>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

        </div><!-- /.news-layout -->

        <!-- ── Card grid: shows ALL events with optional image ── -->
        <?php if (count($events) > 0): ?>
          <div class="events-db-grid reveal">
            <?php foreach ($events as $ev):
              $evDate = !empty($ev['event_date'])
                ? date('F j, Y', strtotime($ev['event_date']))
                : '';
              ?>
              <article class="event-db-card">
                <?php if (!empty($ev['image'])): ?>
                  <img src="img/news&events/<?= htmlspecialchars($ev['image']) ?>" alt="<?= htmlspecialchars($ev['title']) ?>"
                    class="event-db-img" />
                <?php else: ?>
                  <!-- Placeholder shown when no image is set for the event -->
                  <div class="event-db-img-placeholder">
                    <i class="fa-solid fa-calendar-star"></i>
                  </div>
                <?php endif; ?>
                <div class="event-db-body">
                  <?php if ($evDate): ?>
                    <span class="event-db-date"><i class="fa-solid fa-calendar-days"></i>
                      <?= htmlspecialchars($evDate) ?></span>
                  <?php endif; ?>
                  <h3 class="event-db-title"><?= htmlspecialchars($ev['title']) ?></h3>
                  <p class="event-db-desc"><?= nl2br(htmlspecialchars($ev['description'] ?? '')) ?></p>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      <?php endif; ?>
      <!-- ── End dynamic News & Events ── -->

    </div>
  </section>

  <div class="seal-divider" aria-hidden="true">
    <div class="divider-line"></div>
    <div class="divider-seal">✦</div>
    <div class="divider-line"></div>
  </div>

  <!-- CONTACT -->
  <section id="contact" class="section contact">
    <div class="container">
      <div class="section-label reveal">Get in Touch</div>
      <h2 class="section-title reveal">Contact Us</h2>
      <p class="section-desc reveal">We'd love to hear from you. Reach out for admissions inquiries, academic concerns,
        or general information.</p>
      <div class="contact-grid">
        <div class="contact-info reveal">
          <div class="contact-info-block">
            <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <h4>Address</h4>
              <p>Andres Sorino Avenue, Mangagoy<br />Bislig City, Surigao del Sur 8311</p>
            </div>
          </div>
          <div class="contact-info-block">
            <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
            <div>
              <h4>Phone</h4>
              <p>(086) 853-2001 / (086) 853-2002</p>
            </div>
          </div>
          <div class="contact-info-block">
            <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <h4>Email</h4>
              <p>info@ascb.edu.ph<br />admissions@ascb.edu.ph</p>
            </div>
          </div>
          <div class="contact-info-block">
            <div class="contact-icon"><i class="fa-solid fa-clock"></i></div>
            <div>
              <h4>Office Hours</h4>
              <p>Mon–Fri: 8:00 AM – 5:00 PM<br />Saturday: 8:00 AM – 12:00 PM</p>
            </div>
          </div>
          <div class="map-embed">
            <iframe title="ASCB Location Map"
              src="https://maps.google.com/maps?q=Bislig+City+Surigao+del+Sur&t=&z=13&ie=UTF8&iwloc=&output=embed"
              width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </div>
        <div class="contact-form-wrap reveal">
          <form class="contact-form" id="contact-form" novalidate>
            <h3 class="form-title">Send us a Message</h3>
            <div class="form-row">
              <div class="form-group">
                <label for="contact-name">Full Name</label>
                <input type="text" id="contact-name" name="name" placeholder="" required />
              </div>
              <div class="form-group">
                <label for="contact-email">Email Address</label>
                <input type="email" id="contact-email" name="email" placeholder="" required />
              </div>
            </div>
            <div class="form-group">
              <label for="contact-subject">Subject</label>
              <select id="contact-subject" name="subject">
                <option value="">Select a topic…</option>
                <option>Admissions Inquiry</option>
                <option>Academic Concern</option>
                <option>Scholarship Information</option>
                <option>Alumni Affairs</option>
                <option>Others</option>
              </select>
            </div>
            <div class="form-group">
              <label for="contact-message">Message</label>
              <textarea id="contact-message" name="message" rows="5" placeholder="Write your message here…"
                required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-full" id="submit-btn">
              <span>Send Message</span>
              <i class="fa-solid fa-paper-plane"></i>
            </button>
            <div class="form-success hidden" id="form-success">
              <i class="fa-solid fa-circle-check"></i>
              <span>Thank you! We'll get back to you within 1–2 business days.</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-top">
      <div class="container footer-top-inner">
        <div class="footer-brand">
          <div class="footer-crest"><img src="img/ascb.png" alt="ASCB Logo" /></div>
          <div>
            <h3 class="footer-name">Andres Soriano<br />Colleges of Bislig</h3>
            <p class="footer-tagline">Veritas · Excellentia</p>
          </div>
        </div>
        <div class="footer-links-block">
          <h4 class="footer-links-title">Quick Links</h4>
          <ul class="footer-links">
            <li><a href="#about">About ASCB</a></li>
            <li><a href="#academics">Academics</a></li>
            <li><a href="#news">News &amp; Events</a></li>
            <li><a href="#contact">Contact Us</a></li>
          </ul>
        </div>
        <div class="footer-links-block">
          <h4 class="footer-links-title">Offices</h4>
          <ul class="footer-links">
            <li><a href="#">Registrar's Office</a></li>
            <li><a href="#">Admissions Office</a></li>
            <li><a href="#">Student Affairs</a></li>
            <li><a href="#">Alumni Relations</a></li>
          </ul>
        </div>
        <div class="footer-social-block">
          <h4 class="footer-links-title">Follow Us</h4>
          <div class="social-links">
            <a href="https://www.facebook.com/AndresSorianoCollege" class="social-link" aria-label="Facebook"
              target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="social-link" aria-label="Twitter/X"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#" class="social-link" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
            <a href="#" class="social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <p class="footer-copy">&copy; <?= date('Y') ?> Andres Soriano Colleges of Bislig. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Use</a>
          <a href="#">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>

  <button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fa-solid fa-chevron-up"></i>
  </button>

  <script src="script.js"></script>
</body>

</html>
<?php
// Close the database connection when the page is done rendering
$conn->close();
?>