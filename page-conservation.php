<?php
/**
 * Template Name: Whale Dive Conservation
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-conservation'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>WHALE DIVE CENTRE</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero" style="min-height:480px;padding:150px 0 58px;color:#fff;background:linear-gradient(130deg,#021126 0%,#0a3d62 40%,#145374 100%)">
    <div class="wd-shell" style="max-width:720px;margin:0 auto;text-align:center">
      <span class="wd-kicker" style="color:#4CC8ED;font-weight:700;font-size:13px;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;display:block">Ocean Conservation</span>
      <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:48px;margin:0 0 16px">Diving with Purpose</h1>
      <p style="font-size:17px;color:rgba(255,255,255,.82);margin:0">Every dive is an opportunity to protect the ocean we love. Whale Dive Centre is committed to marine conservation through action, education, and community.</p>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px">
        <div style="text-align:center;padding:32px">
          <div style="font-size:48px;margin-bottom:16px">🪸</div>
          <h3 style="margin:0 0 12px;font-size:20px">Coral Restoration</h3>
          <p style="color:#64748b;line-height:1.6">We actively participate in coral reef restoration projects across Bali. Our divers help transplant coral fragments and monitor reef health at partner sites.</p>
        </div>
        <div style="text-align:center;padding:32px">
          <div style="font-size:48px;margin-bottom:16px">🏖️</div>
          <h3 style="margin:0 0 12px;font-size:20px">Beach & Ocean Cleanups</h3>
          <p style="color:#64748b;line-height:1.6">Monthly beach cleanup events and underwater debris removal dives. Our community has collected over 500kg of waste from Bali's coastline and dive sites.</p>
        </div>
        <div style="text-align:center;padding:32px">
          <div style="font-size:48px;margin-bottom:16px">🐠</div>
          <h3 style="margin:0 0 12px;font-size:20px">Marine Surveys</h3>
          <p style="color:#64748b;line-height:1.6">Our trained divers conduct fish population surveys and coral health assessments. Data is shared with marine research organizations for conservation planning.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="wd-section">
    <div class="wd-shell" style="max-width:800px;margin:0 auto">
      <div style="text-align:center;margin-bottom:40px">
        <span class="wd-kicker" style="color:#3B44AC;font-weight:700;font-size:13px;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;display:block">Our Impact</span>
        <h2 style="margin:0 0 12px">Conservation by the Numbers</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:24px;text-align:center">
        <div style="padding:24px;background:#f0f9ff;border-radius:16px">
          <div style="font-size:36px;font-weight:800;color:#0369a1">500+</div>
          <div style="color:#64748b;font-size:14px;margin-top:4px">KG Waste Collected</div>
        </div>
        <div style="padding:24px;background:#f0fdf4;border-radius:16px">
          <div style="font-size:36px;font-weight:800;color:#166534">200+</div>
          <div style="color:#64748b;font-size:14px;margin-top:4px">Coral Fragments Planted</div>
        </div>
        <div style="padding:24px;background:#fef3c7;border-radius:16px">
          <div style="font-size:36px;font-weight:800;color:#92400e">50+</div>
          <div style="color:#64748b;font-size:14px;margin-top:4px">Cleanup Events</div>
        </div>
        <div style="padding:24px;background:#ede9fe;border-radius:16px">
          <div style="font-size:36px;font-weight:800;color:#5b21b6">12</div>
          <div style="color:#64748b;font-size:14px;margin-top:4px">Dive Sites Monitored</div>
        </div>
      </div>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell" style="max-width:800px;margin:0 auto">
      <div style="text-align:center;margin-bottom:40px">
        <h2 style="margin:0 0 12px">How You Can Help</h2>
        <p style="color:#64748b">Join our conservation efforts as a member or volunteer diver.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px">
        <div style="padding:24px;border:2px solid #e2e8f0;border-radius:16px">
          <h3 style="margin:0 0 12px">🤿 Volunteer Dives</h3>
          <p style="color:#64748b;line-height:1.6;margin-bottom:16px">Join our monthly cleanup dives and coral monitoring sessions. Open to all certified divers.</p>
          <a href="/courses/" class="wd-btn" style="display:inline-block;padding:10px 24px;background:linear-gradient(135deg,#3B44AC,#004A98);color:#fff;border-radius:999px;text-decoration:none;font-weight:600">Get Certified</a>
        </div>
        <div style="padding:24px;border:2px solid #e2e8f0;border-radius:16px">
          <h3 style="margin:0 0 12px">🎓 Conservation Courses</h3>
          <p style="color:#64748b;line-height:1.6;margin-bottom:16px">Take specialty courses in underwater naturalist, coral identification, and marine conservation.</p>
          <a href="/courses/" class="wd-btn alt" style="display:inline-block;padding:10px 24px;border:2px solid #3B44AC;color:#3B44AC;border-radius:999px;text-decoration:none;font-weight:600">View Courses</a>
        </div>
      </div>
    </div>
  </section>

  <section class="wd-section wd-community wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">Get Involved</span>
      <h2 class="wd-title">Ready to make a difference?</h2>
      <p class="wd-sub">Contact us to learn about upcoming conservation events and volunteer opportunities.</p>
      <a class="wd-btn alt" href="/contact/">Contact Us</a>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body></html>