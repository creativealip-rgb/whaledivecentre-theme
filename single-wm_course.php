<?php
/**
 * Single Course — Whale Dive Centre
 */
while(have_posts()): the_post();
$price = get_post_meta(get_the_ID(), '_wm_price', true);
$duration = get_post_meta(get_the_ID(), '_wm_duration', true);
$max_students = get_post_meta(get_the_ID(), '_wm_max_students', true);
$prereqs = get_post_meta(get_the_ID(), '_wm_prerequisites', true);
$includes_text = get_post_meta(get_the_ID(), '_wm_includes', true);
$level = wp_get_post_terms(get_the_ID(), 'course_level');
$agency = wp_get_post_terms(get_the_ID(), 'course_agency');
$level_name = !empty($level) ? $level[0]->name : '';
$agency_name = !empty($agency) ? $agency[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
endwhile; rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-courses whaledive-single-course'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-compact-hero wd-courses-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/">Home</a> <span>/</span> <a href="/courses/">Courses</a> <span>/</span> <?php the_title(); ?></div>
        <?php if($agency_name): ?><span class="wd-kicker"><?php echo esc_html($agency_name); ?> Course</span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="wd-detail-meta">
          <?php if($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
          <?php if($duration): ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
          <?php if($price): ?><span class="wd-agency-badge">Rp <?php echo number_format((float)$price,0,',','.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="/contact/">Enroll Now</a>
          <a class="wd-btn alt" href="/courses/">All Courses</a>
        </div>
      </div>
      
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): ?>
          <?php the_content(); ?>
        <?php else: ?>
          <span class="wd-kicker">About this course</span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <p>This course is designed to build your skills and confidence underwater. Contact the crew for detailed scheduling and availability.</p>
        <?php endif; ?>
        <?php endwhile; ?>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if($price): ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label">Course fee</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if($includes_text): ?>
          <h4>What&rsquo;s Included</h4>
          <p class="wd-sidebar-includes"><?php echo esc_html($includes_text); ?></p>
          <?php endif; ?>
          <a class="wd-btn" href="/contact/" style="width:100%;text-align:center;margin-top:16px">Request Enrollment</a>
          <p class="wd-sidebar-note">Or <a href="/member-register/">create an account</a> to enroll from your dashboard.</p>
        </div>
      </aside>
    </div>
  </section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Ready when you are</span><h2 class="wd-title">Ask the crew for course availability.</h2><p class="wd-sub">Send your target certification, dates, and group size.</p><a class="wd-btn alt" href="/contact/">Request Course Plan</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>WhatsApp: +62 xxx xxxx xxxx</p><p>Bali, Indonesia</p><div class="wd-social"><a href="#" aria-label="Facebook">FB</a><a href="#" aria-label="Instagram">IG</a><a href="#" aria-label="YouTube">YT</a><a href="#" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener("DOMContentLoaded",function(){var b=document.querySelector(".wd-hamburger"),m=document.querySelector(".wd-menu");if(!b||!m)return;b.addEventListener("click",function(){var o=document.body.classList.toggle("wd-menu-open");b.setAttribute("aria-expanded",o?"true":"false")});m.querySelectorAll("a").forEach(function(a){a.addEventListener("click",function(){document.body.classList.remove("wd-menu-open");b.setAttribute("aria-expanded","false")})})});</script>
<?php wp_footer(); ?></body></html>