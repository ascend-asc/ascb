/* ================================================
   ANDRES SORIANO COLLEGES OF BISLIG — script.js
   ================================================ */

'use strict';

// ============================================================
// 0. HERO SLIDER
// ============================================================
(function () {
  const slides  = document.querySelectorAll('.hero-slide');
  const dots    = document.querySelectorAll('.hero-dot');
  const prevBtn = document.getElementById('heroPrev');
  const nextBtn = document.getElementById('heroNext');
  const heroEl  = document.getElementById('hero');
  if (!slides.length) return;

  let current = 0;
  let timer;
  const INTERVAL = 5000;

  function goTo(index) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function startAuto() {
    timer = setInterval(() => goTo(current + 1), INTERVAL);
  }

  function stopAuto() {
    clearInterval(timer);
  }

  if (prevBtn) prevBtn.addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
  if (nextBtn) nextBtn.addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });

  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      stopAuto();
      goTo(+dot.dataset.index);
      startAuto();
    });
  });

  // Pause on hover
  if (heroEl) {
    heroEl.addEventListener('mouseenter', stopAuto);
    heroEl.addEventListener('mouseleave', startAuto);
  }

  startAuto();
})();

// ============================================================
// 1. NAVBAR — scroll-triggered style + active link tracking
// ============================================================
const navbar    = document.getElementById('navbar');
const navToggle = document.getElementById('nav-toggle');
const navLinks  = document.getElementById('nav-links');
const allNavLinks = document.querySelectorAll('.nav-link');
const sections  = document.querySelectorAll('section[id]');

function onScroll() {
  // Scrolled style
  if (window.scrollY > 40) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }

  // Active nav link
  let current = '';
  sections.forEach(sec => {
    const top = sec.offsetTop - 100;
    if (window.scrollY >= top) current = sec.getAttribute('id');
  });
  allNavLinks.forEach(link => {
    link.classList.toggle('active', link.getAttribute('href') === '#' + current);
  });

  // Back to top
  const btt = document.getElementById('backToTop');
  btt.classList.toggle('visible', window.scrollY > 500);

  // Reveal elements
  revealOnScroll();

  // Count up numbers when about section visible
  checkCounters();
}

// Hamburger toggle
navToggle.addEventListener('click', () => {
  const open = navLinks.classList.toggle('open');
  navToggle.classList.toggle('open', open);
  navToggle.setAttribute('aria-expanded', open);
});

// Close menu on nav-link click (mobile)
document.querySelectorAll('.nav-link, .nav-cta').forEach(link => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('open');
    navToggle.classList.remove('open');
    navToggle.setAttribute('aria-expanded', 'false');
  });
});

// ============================================================
// 2. REVEAL ON SCROLL (Intersection Observer)
// ============================================================
const reveals = document.querySelectorAll('.reveal');

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry, i) => {
    if (entry.isIntersecting) {
      // Stagger children in same parent
      const siblings = entry.target.parentElement.querySelectorAll('.reveal:not(.visible)');
      let delay = 0;
      siblings.forEach(sib => {
        if (sib === entry.target || sib.getBoundingClientRect().top < window.innerHeight) {
          setTimeout(() => sib.classList.add('visible'), delay);
          delay += 80;
        }
      });
      entry.target.classList.add('visible');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

reveals.forEach(el => revealObserver.observe(el));

function revealOnScroll() {
  // Fallback for older browsers
}

// ============================================================
// 3. MISSION / VISION TABS
// ============================================================
const tabBtns = document.querySelectorAll('.tab-btn');
tabBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const tab = btn.dataset.tab;
    tabBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    const target = document.getElementById('content-' + tab);
    if (target) {
      target.classList.remove('hidden');
      target.style.animation = 'none';
      target.offsetHeight; // reflow
      target.style.animation = 'fadeIn .35s ease';
    }
  });
});

// ============================================================
// 4. ACADEMICS — GRADE LEVEL TABS
// ============================================================
const gradeTabs = document.querySelectorAll('.grade-tab');
gradeTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    const grade = tab.dataset.grade;
    gradeTabs.forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
    tab.classList.add('active');
    tab.setAttribute('aria-selected', 'true');

    document.querySelectorAll('.programs-grid').forEach(grid => grid.classList.add('hidden'));
    const target = document.getElementById('grade-' + grade);
    if (target) {
      target.classList.remove('hidden');
      // Re-animate cards
      target.querySelectorAll('.program-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
          card.style.transition = 'opacity .4s ease, transform .4s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, i * 70);
      });
    }
  });
});

// ============================================================
// 5. COUNT-UP ANIMATION for statistics
// ============================================================
let countersStarted = false;

function animateCounter(el) {
  const target  = +el.dataset.target;
  const duration = 1800;
  const start   = performance.now();

  function update(now) {
    const elapsed = now - start;
    const progress = Math.min(elapsed / duration, 1);
    // Ease-out cubic
    const eased = 1 - Math.pow(1 - progress, 3);
    const value = Math.round(eased * target);
    el.textContent = value >= 1000 ? value.toLocaleString() : value;
    if (progress < 1) requestAnimationFrame(update);
  }
  requestAnimationFrame(update);
}

function checkCounters() {
  if (countersStarted) return;
  const aboutSection = document.getElementById('about');
  if (!aboutSection) return;
  const rect = aboutSection.getBoundingClientRect();
  if (rect.top < window.innerHeight * 0.8) {
    countersStarted = true;
    document.querySelectorAll('.count-up').forEach(el => animateCounter(el));
  }
}

// ============================================================
// 6. CONTACT FORM SUBMIT (demo)
// ============================================================
const form = document.getElementById('contact-form');
const successMsg = document.getElementById('form-success');

if (form) {
  form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Basic validation highlight
    let valid = true;
    form.querySelectorAll('[required]').forEach(field => {
      if (!field.value.trim()) {
        field.style.borderColor = '#ef4444';
        field.addEventListener('input', () => (field.style.borderColor = ''), { once: true });
        valid = false;
      }
    });
    if (!valid) return;

    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.querySelector('span').textContent = 'Sending…';

    // Simulate async send
    setTimeout(() => {
      form.reset();
      successMsg.classList.remove('hidden');
      btn.querySelector('span').textContent = 'Send Message';
      btn.disabled = false;
      setTimeout(() => successMsg.classList.add('hidden'), 5000);
    }, 1400);
  });
}

// ============================================================
// 7. BACK TO TOP
// ============================================================
document.getElementById('backToTop')?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ============================================================
// 8. SMOOTH SCROLL for nav links (ensure offset for sticky nav)
// ============================================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (!target) return;
    e.preventDefault();
    const offset = navbar.offsetHeight + 16;
    const top = target.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
  });
});

// ============================================================
// 9. KEYFRAME injection for fadeIn
// ============================================================
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }
`;
document.head.appendChild(style);

// ============================================================
// 10. INIT
// ============================================================
window.addEventListener('scroll', onScroll, { passive: true });
onScroll(); // run once on page load
