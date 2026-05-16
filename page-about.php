<?php
/**
 * Template Name: About Page
 */
$theme_uri = get_stylesheet_directory_uri();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-about-ux-pass">.wd-crew-proof{padding-top:56px!important}.wd-instructor-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-top:30px}.wd-instructor-grid article{padding:24px;border-radius:28px;background:linear-gradient(180deg,#fff,#eef8fb);border:1px solid rgba(0,91,122,.1);box-shadow:0 16px 38px rgba(2,32,46,.07)}.wd-instructor-grid div{width:74px;height:74px;border-radius:26px;display:grid;place-items:center;margin-bottom:18px;background:linear-gradient(135deg,#06384d,#08a7c7);color:#fff;font-size:28px;font-weight:900}.wd-instructor-grid h3{margin:0 0 8px;color:#06384d}.wd-instructor-grid b{display:block;margin-bottom:10px;color:#0b617c}.wd-instructor-grid span{color:#5b7180;line-height:1.65}.whaledive-about .wd-sub{max-width:720px}.wd-contact-form small{display:block;margin-top:7px;color:#64748b;font-size:12px;line-height:1.45;text-transform:none;letter-spacing:0}.wd-form-privacy{margin:0;color:#64748b;font-size:13px;line-height:1.5}.whaledive-about .wd-contact-grid{margin-top:30px!important}.whaledive-about #contact-form .wd-sub{margin-bottom:0!important}.wd-contact-card{display:flex;flex-direction:column;gap:8px}.wd-contact-card strong{color:#06384d;font-size:13px;letter-spacing:.08em;text-transform:uppercase}.wd-contact-card span,.wd-contact-card a{line-height:1.55}.wd-contact-card a{display:inline-flex;color:#0b617c;font-weight:800}.wd-map-link{width:max-content;margin-top:4px!important;padding:9px 13px;border-radius:999px;background:#f3fbff;border:1px solid rgba(6,56,77,.14);text-decoration:none}.wd-menu a[data-nav="about"]{color:#06384d;background:rgba(8,167,199,.12)}@media(max-width:800px){.wd-instructor-grid{grid-template-columns:1fr}}</style></head>
<body <?php body_class('whaledive-inner whaledive-about'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><a href="/about/" data-nav="about">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <!-- HERO -->
  <section class="wd-inner-hero wd-about-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker">About Whale Dive Centre</span>
          <h1>Calm training. Better habits. Safer dives.</h1>
          <p>A Jakarta-based dive centre focused on professional scuba training, quality gear support, and an ocean-minded community.</p><div class="wd-actions"><a class="wd-btn" href="#contact-form">Start Inquiry</a><a class="wd-btn alt" href="/courses/">View Courses</a></div>
        </div>
      </div>
    </div>
  </section>

  <!-- TEAM STANDARDS -->
  <section class="wd-section white wd-team-standards">
    <div class="wd-shell">
      <span class="wd-kicker">Training standards</span>
      <h2 class="wd-title">Crew-led standards for safer dive days</h2>
      <div class="wd-about-crew-grid">
        <div class="wd-crew-card"><div class="wd-crew-icon">01</div><h3>Instructor-led progression</h3><span>Courses move at the diver's real comfort level, not just the calendar.</span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">02</div><h3>Equipment readiness</h3><span>Fit checks, setup walkthroughs, and gear questions happen before the dive.</span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">03</div><h3>Small-group attention</h3><span>More room for questions, repeated skills, and calm debriefs.</span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">04</div><h3>Condition-aware planning</h3><span>Weather, current, visibility, and diver readiness shape every recommendation.</span></div>
      </div>
    </div>
  </section>

  <section class="wd-section white wd-crew-proof"><div class="wd-shell"><span class="wd-kicker">Meet the crew</span><h2 class="wd-title">Certified, patient, and focused on calm progression.</h2><div class="wd-instructor-grid"><article><div>I</div><h3>Instructor Team</h3><b>PADI / SSI pathway</b><span>Small-group pacing, beginner confidence, and calm skill repetition.</span></article><article><div>S</div><h3>Safety Support</h3><b>Rescue-aware dive planning</b><span>Briefings, buddy awareness, equipment checks, and debrief habits.</span></article><article><div>G</div><h3>Gear Specialist</h3><b>Fit and setup guidance</b><span>Mask fit, BCD sizing, regulator setup, and dive computer basics.</span></article></div></div></section>

  <!-- HOW WE WORK -->
  <section class="wd-section wd-dark">
    <div class="wd-shell">
      <span class="wd-kicker">How we work</span>
      <h2 class="wd-title">Calm briefings. Better habits. Safer dives.</h2>
      <div class="wd-safety-grid"><article><b>Pre-dive briefing</b><span>Objectives, signals, limits, buddy plan, and exit plan are reviewed before each session.</span></article><article><b>Equipment checks</b><span>Fit, air delivery, weights, computer settings, and comfort are checked before entering the water.</span></article><article><b>Emergency readiness</b><span>Training decisions include conservative limits, first-aid awareness, and no-rush calls around conditions.</span></article></div><div class="wd-steps">
        <div class="wd-step"><span>01</span><h3>Before the dive</h3><p>Clear plan, gear check, site conditions, and skill expectations.</p></div>
        <div class="wd-step"><span>02</span><h3>During the dive</h3><p>Small-group awareness, relaxed pacing, and safety-first decisions.</p></div>
        <div class="wd-step"><span>03</span><h3>After the dive</h3><p>Debrief, next-step coaching, and recommendations for training or gear.</p></div>
      </div>
    </div>
  </section>

  <!-- GET IN TOUCH -->
  <section class="wd-section white" id="contact-form">
    <div class="wd-shell">
      <span class="wd-kicker">Get in touch</span>
      <h2 class="wd-title">Start the conversation</h2>
      <div class="wd-contact-grid">
        <div class="wd-contact-cards">
<div class="wd-contact-card"><strong>Email</strong><a href="mailto:info@whaledivecentre.com">info@whaledivecentre.com</a></div>
          <div class="wd-contact-card"><strong>Phone</strong><a href="tel:+622127939068">(021) 27939068</a></div>
          <div class="wd-contact-card"><strong>Business Hours</strong><span>Monday - Saturday, 09:00 - 18:00 WIB. Course and trip schedules are confirmed by appointment.</span></div>
          <div class="wd-contact-card"><strong>Location</strong><span>Jl. Tanah Kusir II No.3, RT.10/RW.9, Kebayoran Lama Selatan, Jakarta Selatan 12240</span><a class="wd-map-link" href="https://www.google.com/maps/search/?api=1&query=Jl.%20Tanah%20Kusir%20II%20No.3%20Jakarta%20Selatan" target="_blank" rel="noopener">Open in Google Maps</a></div>
        </div>
        <form class="wd-contact-form" method="post">
          <label>Your Name<input type="text" name="your-name" placeholder="Your name" required></label>
          <label>Email<input type="email" name="email" placeholder="you@example.com"><small>Use email if you prefer a written reply.</small></label>
          <label>WhatsApp Number<input type="tel" name="whatsapp" placeholder="+62..." required><small>Required so our crew can reply within 24 hours.</small></label>
          <label>What do you need?<select name="category"><option>Course inquiry</option><option>Equipment availability</option><option>General question</option></select></label>
          <label>Message<textarea name="message" rows="4" placeholder="Tell us what you need..."></textarea></label>
          <p class="wd-form-privacy">We only use your contact details to reply to this inquiry.</p><button type="submit" class="wd-btn">Send Inquiry</button>
        </form>
      </div>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>WhatsApp: <?php echo esc_html(get_option("wdc_whatsapp_number", "(021) 27939068")); ?></p><p>Jl. Tanah Kusir II No.3, RT.10/RW.9, Kby. Lama Sel., Kec. Kebayoran Lama, Kota Jakarta Selatan, DKI Jakarta 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var p = new URLSearchParams(window.location.search);
  var name = p.get('name');
  if(name){
    var nameInput = document.querySelector('input[name="your-name"]');
    if(nameInput) nameInput.value = name;
    var waInput = document.querySelector('input[name="whatsapp"]');
    if(waInput && p.get('whatsapp')) waInput.value = p.get('whatsapp');
    var emailInput = document.querySelector('input[name="email"]');
    if(emailInput && p.get('email')) emailInput.value = p.get('email');
    var msgInput = document.querySelector('textarea');
    if(msgInput){
      var msg = 'Course inquiry from homepage:\n';
      if(p.get('cert')) msg += 'Certification: ' + p.get('cert') + '\n';
      if(p.get('schedule')) msg += 'Schedule: ' + p.get('schedule') + '\n';
      if(p.get('group')) msg += 'Group: ' + p.get('group');
      msgInput.value = msg;
    }
    document.getElementById('contact-form')?.scrollIntoView({behavior:'smooth'});
  }
});
</script>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body>
</html>