<?php
/**
 * Template Name: About Page
 */
$theme_uri = get_stylesheet_directory_uri();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-about'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <!-- HERO -->
  <section class="wd-inner-hero wd-about-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker">About Whale Dive Centre</span>
          <h1>Calm training. Better habits. Safer dives.</h1>
          <p>A Jakarta-based dive centre focused on professional scuba training, quality gear support, and an ocean-minded community.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- TEAM STANDARDS -->
  <section class="wd-section white wd-team-standards">
    <div class="wd-shell">
      <span class="wd-kicker">Training standards</span>
      <h2 class="wd-title">Crew-led standards for safer dive days</h2>
      <p class="wd-sub">Our team keeps training personal, practical, and condition-aware from the first briefing to the final debrief.</p>
      <div class="wd-about-crew-grid">
        <div class="wd-crew-card"><div class="wd-crew-icon">01</div><h3>Instructor-led progression</h3><span>Courses move at the diver's real comfort level, not just the calendar.</span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">02</div><h3>Equipment readiness</h3><span>Fit checks, setup walkthroughs, and gear questions happen before the dive.</span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">03</div><h3>Small-group attention</h3><span>More room for questions, repeated skills, and calm debriefs.</span></div>
        <div class="wd-crew-card"><div class="wd-crew-icon">04</div><h3>Condition-aware planning</h3><span>Weather, current, visibility, and diver readiness shape every recommendation.</span></div>
      </div>
    </div>
  </section>

  <!-- HOW WE WORK -->
  <section class="wd-section wd-dark">
    <div class="wd-shell">
      <span class="wd-kicker">How we work</span>
      <h2 class="wd-title">Calm briefings. Better habits. Safer dives.</h2>
      <p class="wd-sub">We keep the experience personal so divers can ask questions, repeat skills, and grow at the right pace.</p>
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
      <p class="wd-sub">Ask about courses, gear, scheduling, or anything else. The crew replies within 24 hours.</p>
      <div class="wd-contact-grid">
        <div class="wd-contact-cards">
          <div class="wd-contact-card"><strong>Email</strong><a href="mailto:info@whaledivecentre.com">info@whaledivecentre.com</a></div>
          <div class="wd-contact-card"><strong>Phone</strong><a href="tel:+622127939068">(021) 27939068</a></div>
          <div class="wd-contact-card"><strong>Location</strong><span>Jl. Tanah Kusir II No.3, RT.10/RW.9, Kby. Lama Sel., Kec. Kebayoran Lama, Kota Jakarta Selatan, DKI Jakarta 12240</span></div>
        </div>
        <form class="wd-contact-form" method="post">
          <label>Your Name<input type="text" name="your-name" placeholder="Your name" required></label>
          <label>WhatsApp / Email<input type="text" name="whatsapp" placeholder="WhatsApp or email" required></label>
          <label>What do you need?<select name="category"><option>Course inquiry</option><option>Equipment availability</option><option>General question</option></select></label>
          <label>Message<textarea name="message" rows="4" placeholder="Tell us what you need..."></textarea></label>
          <button type="submit" class="wd-btn">Send Inquiry</button>
        </form>
      </div>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>WhatsApp: <?php echo esc_html(get_option("wdc_whatsapp_number", "(021) 27939068")); ?></p><p>Jl. Tanah Kusir II No.3, RT.10/RW.9, Kby. Lama Sel., Kec. Kebayoran Lama, Kota Jakarta Selatan, DKI Jakarta 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
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
<?php wp_footer(); ?>
</body>
</html>