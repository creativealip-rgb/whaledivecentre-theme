<?php
/**
 * Template Name: Whale Dive Courses
 */

// Get all courses from CPT
$all_courses = get_posts([
    'post_type'   => 'wm_course',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
]);

// Get filter terms
$levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
$theme_uri = get_stylesheet_directory_uri();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-courses'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero wd-courses-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker">Dive training pathway</span><h1>Courses built for calm, capable divers.</h1><p>From your first pool session to professional leadership, Whale Dive Centre keeps training small, practical, and safety-first.</p><div class="wd-actions"><a class="wd-btn" href="/contact/">Request Course Plan</a><a class="wd-btn alt" href="#course-pathway">View Pathway</a></div></div><aside class="wd-inner-card"><b>Good fit if you want</b><ul><li>Clear certification pathway</li><li>Small-group coaching</li><li>Gear guidance before class</li><li>Community after certification</li></ul></aside></div></section>

  <section id="course-pathway" class="wd-section wd-dark wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">Course pathway</span>
      <h2 class="wd-title">Choose your next underwater milestone</h2>
      <p class="wd-sub"><?php echo count($all_courses); ?> courses available — from first-time experiences to professional leadership.</p>

      <div class="wd-filter-bar" id="courseFilters">
        <button class="wd-chip active" data-filter="all">All Courses</button>
        <?php if (!empty($levels) && !is_wp_error($levels)): foreach ($levels as $level): ?>
          <button class="wd-chip" data-filter="level-<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <div class="wd-course-grid wd-page-grid" id="courseGrid">
        <?php foreach ($all_courses as $i => $course):
          $price = get_post_meta($course->ID, '_wm_price', true);
          $duration = get_post_meta($course->ID, '_wm_duration', true);
          $prereqs = get_post_meta($course->ID, '_wm_prerequisites', true);
          $includes_text = get_post_meta($course->ID, '_wm_includes', true);
          $max_students = get_post_meta($course->ID, '_wm_max_students', true);
          $level_terms = wp_get_post_terms($course->ID, 'course_level', ['fields' => 'all']);
          $agency_terms = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
          $level_slug = !empty($level_terms) ? $level_terms[0]->slug : '';
          $level_name = !empty($level_terms) ? $level_terms[0]->name : '';
          $agency_name = !empty($agency_terms) ? $agency_terms[0] : '';
          $excerpt = $course->post_excerpt ?: wp_trim_words($course->post_content, 18, '…');
          $permalink = get_permalink($course->ID);
        ?>
        <article class="wd-course-card wd-detail-card wd-course-decision-card" data-level="level-<?php echo esc_attr($level_slug); ?>">
          <div class="wd-course-card-head">
            <div class="wd-course-no"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
            <?php if ($agency_name): ?><span class="wd-course-agency"><?php echo esc_html($agency_name); ?></span><?php endif; ?>
          </div>
          <h3><?php echo esc_html($course->post_title); ?></h3>
          <div class="wd-course-meta wd-course-chips">
            <?php if ($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
            <?php if ($duration): ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
            <?php if ($max_students): ?><span>Max <?php echo esc_html($max_students); ?> divers</span><?php endif; ?>
          </div>
          <?php if ($excerpt): ?><p><?php echo esc_html($excerpt); ?></p><?php endif; ?>
          <div class="wd-course-fit">
            <b>Best for</b>
            <span><?php echo esc_html($level_name ? $level_name . ' divers ready for the next step.' : 'Divers who want a structured, instructor-led pathway.'); ?></span>
          </div>
          <?php if ($price): ?>
          <div class="wd-course-price">
            <span class="wd-price-label">Course fee from</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price, 0, ',', '.'); ?></span>
          </div>
          <?php endif; ?>
          <dl class="wd-course-quickfacts">
            <?php if ($prereqs): ?><dt>Prerequisite</dt><dd><?php echo esc_html($prereqs); ?></dd><?php endif; ?>
            <?php if ($includes_text): ?><dt>Includes</dt><dd><?php echo esc_html($includes_text); ?></dd><?php endif; ?>
          </dl>
          <div class="wd-course-actions"><a class="wd-mini-btn" href="/contact/">Request Plan</a><a class="wd-mini-link" href="<?php echo esc_url($permalink); ?>">View Details</a></div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wd-section white wd-course-standards"><div class="wd-shell"><span class="wd-kicker">Training standards</span><h2 class="wd-title">Every course is planned around readiness, not pressure.</h2><div class="wd-course-trust-grid"><article><b>Prerequisites checked</b><span>The crew confirms certification, comfort level, and schedule before recommending a path.</span></article><article><b>Small-group pacing</b><span>More time for questions, repeated skills, and calm debriefs after each session.</span></article><article><b>Gear guidance included</b><span>Fit, comfort, and setup support are part of the course planning process.</span></article></div></div></section>

  <section class="wd-section white"><div class="wd-shell wd-split"><div><span class="wd-kicker">How it works</span><h2 class="wd-title">Simple steps to start diving.</h2><p class="wd-sub left">Pick a course, share your dates, and the crew handles the rest — from gear to certification.</p></div><div class="wd-steps"><div><b>01</b><h3>Tell us your level</h3><p>Share certification, schedule, group size, and comfort level.</p></div><div><b>02</b><h3>Get your course plan</h3><p>The crew recommends schedule, prerequisites, and gear notes.</p></div><div><b>03</b><h3>Confirm and train</h3><p>Once confirmed, the course appears in your member dashboard.</p></div></div></div></section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Ready when you are</span><h2 class="wd-title">Ask the crew for course availability.</h2><p class="wd-sub">Send your target certification, dates, and group size. Whale Dive Centre will help map the right path.</p><a class="wd-btn alt" href="/contact/">Request Course Plan</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<?php wp_footer(); ?>
</body></html>

