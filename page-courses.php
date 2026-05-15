<?php
/**
 * Template Name: Whale Dive Courses
 */
$all_courses = get_posts([
    'post_type'   => 'wm_course',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
]);
$levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
$theme_uri = get_stylesheet_directory_uri();
function wdc_course_image_url($title, $theme_uri) {
    $key = strtolower($title);
    $map = [
        'underwater photography' => 'wdc-course-underwater-photography-real-v2.jpg',
        'photography' => 'wdc-course-underwater-photography-real-v2.jpg',
        'deep diver' => 'wdc-course-deep-diver-real-v2.jpg',
        'deep' => 'wdc-course-deep-diver-real-v2.jpg',
        'nitrox' => 'wdc-course-nitrox-real-v2.jpg',
        'enriched air' => 'wdc-course-nitrox-real-v2.jpg',
        'discover' => 'wdc-course-discover-scuba-pexels.jpg',
        'advanced' => 'wdc-course-advanced-open-water-real.png',
        'rescue' => 'wdc-course-rescue-diver-real.png',
        'divemaster' => 'wdc-course-divemaster-real.png',
        'instructor' => 'wdc-course-instructor-course-real.png',
        'open water' => 'wdc-course-open-water-real.png',
    ];
    foreach ($map as $needle => $file) {
        if (strpos($key, $needle) !== false) {
            return $theme_uri . '/assets/' . $file;
        }
    }
    return '';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-courses-equipment-copy">.whaledive-courses .wd-gear-note{display:flex;gap:12px;justify-content:center;align-items:center;flex-wrap:wrap;max-width:820px;margin:0 auto 22px;padding:14px 18px;border-radius:18px;background:#eef8fb;border:1px solid rgba(0,91,122,.1);color:#5b7180}.whaledive-courses .wd-gear-note b{color:#06384d}.whaledive-courses .wd-gear-finder{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:0 0 28px}.whaledive-courses .wd-gear-finder span,.whaledive-courses .wd-gear-finder a{min-height:42px;display:inline-flex;align-items:center;border-radius:999px;padding:0 14px;font-weight:800;font-size:13px}.whaledive-courses .wd-gear-finder span{background:#06384d;color:#fff}.whaledive-courses .wd-gear-finder a{background:#fff;color:#0b617c;border:1px solid rgba(11,97,124,.16);text-decoration:none}.whaledive-courses .wd-mini-btn{background:#06384d!important;color:#fff!important}.whaledive-courses .wd-mini-link{border-color:rgba(11,97,124,.24)!important;color:#0b617c!important}.whaledive-courses .wd-equip-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0}.whaledive-courses .wd-equip-visual.has-photo:before{background:linear-gradient(180deg,rgba(3,23,45,.05),rgba(3,23,45,.55));z-index:1}.whaledive-courses .wd-equip-visual.has-photo:after{z-index:1}.whaledive-courses .wd-equip-visual.has-photo .wd-equip-type{background:rgba(3,23,45,.62);backdrop-filter:blur(8px)}</style></head>
<body <?php body_class('whaledive-inner whaledive-courses'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero wd-courses-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker">Dive training support</span><h1>Courses that fit your next dive, not just your calendar.</h1><p>Browse certification pathways, check pricing, and get crew guidance before choosing your next course.</p><div class="wd-actions"><a class="wd-btn" href="/contact/">Ask Course Plan</a><a class="wd-btn alt" href="#course-catalog">Browse Courses</a></div></div><aside class="wd-inner-card"><b>Training support covers</b><ul><li>Certification pathway checks</li><li>Small-group coaching guidance</li><li>Gear and schedule readiness</li><li>PADI / SSI / NAUI / TDI options</li></ul></aside></div></section>

  <section id="course-catalog" class="wd-section white wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">Course catalog</span>
      <h2 class="wd-title">Dive courses from trusted agencies</h2>
      <p class="wd-sub"><?php echo count($all_courses); ?> courses across <?php echo count($levels); ?> pathways.</p>
      <div id="courseFilters" class="wd-filter-bar">
        <button class="wd-chip active" data-filter="all">All Courses</button>
        <?php if(!empty($levels) && !is_wp_error($levels)): foreach($levels as $level): ?>
          <button class="wd-chip" data-filter="cat-<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <div id="courseGrid" class="wd-equipment-grid wd-page-grid">
        <?php foreach($all_courses as $course):
          $price = get_post_meta($course->ID, '_wm_price', true);
          $duration = get_post_meta($course->ID, '_wm_duration', true);
          $max_students = get_post_meta($course->ID, '_wm_max_students', true);
          $prereqs = get_post_meta($course->ID, '_wm_prerequisites', true);
          $level_terms = wp_get_post_terms($course->ID, 'course_level', ['fields' => 'all']);
          $agency_terms = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
          $level_slug = !empty($level_terms) ? $level_terms[0]->slug : '';
          $level_name = !empty($level_terms) ? $level_terms[0]->name : '';
          $agency_name = !empty($agency_terms) ? $agency_terms[0] : '';
          $permalink = get_permalink($course->ID);
          $use_case = $level_name ? 'Crew-guided ' . strtolower($level_name) . ' training for safer skills, confidence, and certification progress.' : 'Crew-guided dive training for safer skills, confidence, and certification progress.';
          $image_url = wdc_course_image_url($course->post_title, $theme_uri);
        ?>
        <article class="wd-equip-card wd-detail-card wd-shop-card wd-course-card" data-cat="cat-<?php echo esc_attr($level_slug); ?>">
          <div class="wd-equip-visual <?php echo $image_url ? 'has-photo' : ''; ?>" data-cat="<?php echo esc_attr($level_slug ?: 'course'); ?>">
            <?php if($image_url): ?><img class="wd-equip-photo" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($course->post_title); ?>" loading="lazy" onerror="this.closest('.wd-equip-visual').classList.remove('has-photo');this.remove();"><?php else: ?><span class="wd-equip-mark"><?php echo esc_html($level_name ? mb_substr($level_name, 0, 1) : 'C'); ?></span><?php endif; ?>
            <?php if($level_name): ?><span class="wd-equip-type"><?php echo esc_html($level_name); ?></span><?php endif; ?>
          </div>
          <div class="wd-equip-card-body">
            <div class="wd-course-meta wd-shop-meta">
              <?php if($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
              <?php if($agency_name): ?><span><?php echo esc_html($agency_name); ?></span><?php endif; ?>
            </div>
            <h3><?php echo esc_html($course->post_title); ?></h3>
            <p class="wd-equip-use"><?php echo esc_html($use_case); ?></p>
            <?php if($price): ?>
            <div class="wd-equip-price">
              <span class="wd-price-label">Course price · schedule on request</span>
              <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
            </div>
            <?php endif; ?>
            <div class="wd-equip-chips">
              <?php if($duration): ?><span><?php echo esc_html($duration); ?></span><?php endif; ?>
              <span><?php echo $max_students ? 'Max ' . esc_html($max_students) . ' divers' : 'Check availability'; ?></span>
              <?php if($prereqs): ?><span>Prereq: <?php echo esc_html($prereqs); ?></span><?php endif; ?>
            </div>
            <div class="wd-equip-actions">
              <a class="wd-mini-btn" href="/contact/">Request Plan</a>
              <a class="wd-mini-link" href="<?php echo esc_url($permalink); ?>">View Details</a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Need course advice?</span><h2 class="wd-title">The crew helps you choose the right path.</h2><p class="wd-sub">Tell us your certification level, target dates, and comfort goals — we recommend the course that fits.</p><a class="wd-btn alt" href="/contact/">Ask About Course Plan</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});var chips=document.querySelectorAll('#courseFilters .wd-chip');var cards=document.querySelectorAll('#courseGrid [data-cat]');chips.forEach(function(chip){chip.addEventListener('click',function(){var filter=chip.getAttribute('data-filter');chips.forEach(function(c){c.classList.remove('active')});chip.classList.add('active');cards.forEach(function(card){card.style.display=(filter==='all'||card.getAttribute('data-cat')===filter)?'':'none';});});});});</script><?php wp_footer(); ?>
</body></html>
