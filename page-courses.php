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
    'meta_query'  => [
        'relation' => 'OR',
        [
            'key'     => '_wdc_catalog_visible',
            'compare' => 'NOT EXISTS',
        ],
        [
            'key'     => '_wdc_catalog_visible',
            'value'   => '0',
            'compare' => '!=',
        ],
    ],
]);
$levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
$levels = is_wp_error($levels) ? [] : $levels;
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
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-courses-equipment-copy">.whaledive-courses .wd-gear-note{display:flex;gap:12px;justify-content:center;align-items:center;flex-wrap:wrap;max-width:820px;margin:0 auto 22px;padding:14px 18px;border-radius:18px;background:#eef8fb;border:1px solid rgba(0,91,122,.1);color:#5b7180}.whaledive-courses .wd-gear-note b{color:#06384d}.whaledive-courses #courseGrid > article[data-visible="1"]{display:flex!important}.whaledive-courses #courseGrid > article[data-visible="0"]{display:none!important}.whaledive-courses .wd-gear-finder{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:0 0 28px}.whaledive-courses .wd-gear-finder span,.whaledive-courses .wd-gear-finder a{min-height:42px;display:inline-flex;align-items:center;border-radius:999px;padding:0 14px;font-weight:800;font-size:13px}.whaledive-courses .wd-gear-finder span{background:#06384d;color:#fff}.whaledive-courses .wd-gear-finder a{background:#fff;color:#0b617c;border:1px solid rgba(11,97,124,.16);text-decoration:none}.whaledive-courses .wd-mini-btn{background:#06384d!important;color:#fff!important}.whaledive-courses .wd-mini-link{border-color:rgba(11,97,124,.24)!important;color:#0b617c!important}.whaledive-courses .wd-equip-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0}.whaledive-courses .wd-equip-visual.has-photo:before{background:linear-gradient(180deg,rgba(3,23,45,.05),rgba(3,23,45,.55));z-index:1}.whaledive-courses .wd-equip-visual.has-photo:after{z-index:1}.whaledive-courses .wd-equip-visual.has-photo .wd-equip-type{background:rgba(3,23,45,.62);backdrop-filter:blur(8px)}.whaledive-courses #courseFilters .wd-chip{background:#fff!important;color:#06384d!important;border:1px solid rgba(7,55,78,.18)!important}.whaledive-courses #courseFilters .wd-chip.active{background:#06384d!important;color:#fff!important;border-color:#06384d!important}.whaledive-courses #courseGrid.wd-page-grid{width:100%!important;max-width:none!important;margin-left:0!important;margin-right:0!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}.whaledive-courses .wd-course-card{border-radius:18px!important;overflow:hidden!important;background:#fff!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 14px 34px rgba(2,21,43,.07)!important;padding:0!important}.whaledive-courses .wd-course-card .wd-equip-visual{height:138px!important;min-height:0!important;border-radius:0!important;margin:0!important}.whaledive-courses .wd-course-card .wd-equip-type{top:10px!important;left:10px!important;padding:6px 9px!important;border-radius:999px!important;font-size:10px!important;letter-spacing:.08em!important}.whaledive-courses .wd-course-card .wd-equip-card-body{padding:14px!important;display:flex!important;flex-direction:column!important;min-height:238px!important}.whaledive-courses .wd-course-card .wd-course-meta{gap:6px!important;margin:0 0 4px!important}.whaledive-courses .wd-course-card .wd-course-meta span{padding:5px 8px!important;border-radius:999px!important;background:#eef8fb!important;color:#0b617c!important;font-size:10px!important;font-weight:900!important;line-height:1!important;letter-spacing:.02em!important}.whaledive-courses .wd-course-card h3{font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important}.whaledive-courses .wd-course-card .wd-equip-price{margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important}.whaledive-courses .wd-course-card .wd-price-label{display:block!important;font-size:11px!important;color:#6f7f8d!important;line-height:1.2!important;margin-bottom:4px!important}.whaledive-courses .wd-course-card .wd-price-amount{display:block!important;font-size:clamp(15px,1.2vw,17px)!important;font-weight:900!important;color:#06384d!important;line-height:1!important;white-space:nowrap!important;letter-spacing:-.02em!important}.whaledive-courses .wd-course-card .wd-equip-chips{gap:6px!important;margin:0 0 12px!important}.whaledive-courses .wd-course-card .wd-equip-chips span{padding:6px 8px!important;border-radius:999px!important;background:#f1fbff!important;color:#4f6575!important;font-size:10px!important;font-weight:800!important;line-height:1.1!important}.whaledive-courses .wd-course-card .wd-equip-actions{margin-top:8px!important;padding-top:0!important;border-top:0!important}.whaledive-courses .wd-course-card .wd-mini-link{min-height:34px!important;width:auto!important;padding:0 12px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.12)!important;color:#06384d!important}.whaledive-courses .wd-course-card .wd-mini-link:after{content:' →'}@media(max-width:980px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important}}@media(max-width:760px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}.whaledive-courses .wd-course-card .wd-equip-card-body{min-height:220px!important}}@media(max-width:540px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:1fr!important}.whaledive-courses .wd-course-card{display:grid!important;grid-template-columns:118px minmax(0,1fr)!important}.whaledive-courses .wd-course-card .wd-equip-visual{height:100%!important;min-height:178px!important}.whaledive-courses .wd-course-card .wd-equip-card-body{min-height:0!important;padding:12px!important}.whaledive-courses .wd-course-card h3{font-size:18px!important}}
.whaledive-courses #courseGrid .wd-course-card{min-height:0!important;height:auto!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-card-body{padding:14px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important}.whaledive-courses #courseGrid .wd-course-card h3{font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-desc{display:none!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-tags{gap:6px!important;margin:0 0 4px!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-tags span{padding:5px 8px!important;border-radius:999px!important;background:#eef8fb!important;color:#0b617c!important;font-size:10px!important;font-weight:900!important;line-height:1!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-price{margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-price-label{display:block!important;margin:0 0 4px!important;font-size:11px!important;line-height:1.2!important;color:#789!important}.whaledive-courses #courseGrid .wd-course-card .wd-price-amount{font-size:17px!important;line-height:1!important;color:#06384d!important;font-weight:900!important;white-space:nowrap!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-meta{gap:6px!important;margin:0 0 8px!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-meta span,.whaledive-courses #courseGrid .wd-course-card .wd-course-requirement span{padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-requirement{margin:0 0 12px!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-actions{margin-top:8px!important;padding-top:0!important;border-top:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-actions .wd-btn{min-height:34px!important;width:max-content!important;padding:0 12px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.12)!important;color:#06384d!important;box-shadow:none!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-badge{font-size:9px!important;padding:6px 8px!important;border-radius:999px!important}

.whaledive-courses #courseGrid .wd-course-card .wd-equip-chips{gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-chips span{padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions{margin-top:8px!important;padding-top:0!important;border-top:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link{min-height:38px!important;min-width:128px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.14)!important;color:#06384d!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link:after{content:' →'}

/* Final course catalog polish: 3-column balanced grid for 6 courses. */
.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:16px!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-card-body h3{min-height:42px!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link{min-width:112px!important;justify-content:center!important}@media(max-width:980px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}}@media(max-width:620px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:1fr!important}}
</style></head>
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

      <div id="courseGrid" class="wd-equipment-grid wd-page-grid" style="align-items:start!important;grid-auto-rows:auto!important;">
        <?php foreach($all_courses as $course):
          $price = get_post_meta($course->ID, '_wm_price', true);
          $duration = get_post_meta($course->ID, '_wm_duration', true);
          $max_students = get_post_meta($course->ID, '_wm_max_students', true);
          $prereqs = get_post_meta($course->ID, '_wm_prerequisites', true);
          $level_terms = wp_get_post_terms($course->ID, 'course_level', ['fields' => 'all']);
          $level_terms = is_wp_error($level_terms) ? [] : $level_terms;
          $agency_terms = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
          $agency_terms = is_wp_error($agency_terms) ? [] : $agency_terms;
          $level_slug = !empty($level_terms) ? $level_terms[0]->slug : '';
          $level_name = !empty($level_terms) ? $level_terms[0]->name : '';
          $agency_name = !empty($agency_terms) ? $agency_terms[0] : '';
          $permalink = home_url('/courses/' . $course->post_name . '/');
          $use_case = $level_name ? 'Crew-guided ' . strtolower($level_name) . ' training for safer skills, confidence, and certification progress.' : 'Crew-guided dive training for safer skills, confidence, and certification progress.';
          $image_url = get_the_post_thumbnail_url($course->ID, 'large') ?: wdc_course_image_url($course->post_title, $theme_uri);
        ?>
        <article class="wd-equip-card wd-detail-card wd-shop-card wd-course-card" data-href="<?php echo esc_url($permalink); ?>" onclick="if(!event.target.closest('a,button')){window.location.href=this.dataset.href;}" data-cat="cat-<?php echo esc_attr($level_slug); ?>" style="border-radius:18px!important;overflow:hidden!important;padding:0!important;background:#fff!important;box-shadow:0 14px 34px rgba(2,21,43,.07)!important;border:1px solid rgba(6,56,77,.08)!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;">
          <div class="wd-equip-visual <?php echo $image_url ? 'has-photo' : ''; ?>" data-course-level="<?php echo esc_attr($level_slug ?: 'course'); ?>" style="height:138px!important;min-height:0!important;border-radius:0!important;margin:0!important;">
            <?php if($image_url): ?><img class="wd-equip-photo" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($course->post_title); ?>" loading="lazy" onerror="this.closest('.wd-equip-visual').classList.remove('has-photo');this.remove();"><?php else: ?><span class="wd-equip-mark"><?php echo esc_html($level_name ? mb_substr($level_name, 0, 1) : 'C'); ?></span><?php endif; ?>
            <?php if($level_name): ?><span class="wd-equip-type"><?php echo esc_html($level_name); ?></span><?php endif; ?>
          </div>
          <div class="wd-equip-card-body" style="padding:14px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important;">
            <div class="wd-course-meta wd-shop-meta" style="gap:6px!important;margin:0 0 4px!important;">
              <?php if($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
              <?php if($agency_name): ?><span><?php echo esc_html($agency_name); ?></span><?php endif; ?>
            </div>
            <h3 style="font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important;"><?php echo esc_html($course->post_title); ?></h3>
            <?php if($price): ?>
            <div class="wd-equip-price" style="margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important;">
              <span class="wd-price-label" style="display:block!important;margin:0 0 4px!important;font-size:11px!important;line-height:1.2!important;color:#789!important;">Course price · schedule on request</span>
              <span class="wd-price-amount" style="display:block!important;font-size:17px!important;line-height:1!important;color:#06384d!important;font-weight:900!important;white-space:nowrap!important;">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
            </div>
            <?php endif; ?>
            <div class="wd-equip-chips" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
              <?php if($duration): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo esc_html($duration); ?></span><?php endif; ?>
              <span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo $max_students ? 'Max ' . esc_html($max_students) . ' divers' : 'Check availability'; ?></span>
              <?php if($prereqs): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;">Prereq: <?php echo esc_html($prereqs); ?></span><?php endif; ?>
            </div>
            <div class="wd-equip-actions" style="margin-top:8px!important;padding-top:0!important;border-top:0!important;">
              <a class="wd-mini-link" style="min-height:38px!important;min-width:128px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.14)!important;color:#06384d!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url($permalink); ?>">View Details</a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker">Need course advice?</span><h2>The crew helps you choose the right path.</h2><p>Tell us your certification level, target dates, and comfort goals — we recommend the course that fits.</p><a class="wd-btn alt" href="/contact/">Ask About Course Plan</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/contact/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});var chips=Array.prototype.slice.call(document.querySelectorAll('#courseFilters .wd-chip'));var cards=Array.prototype.slice.call(document.querySelectorAll('#courseGrid > article.wd-course-card[data-cat]'));function applyCourseFilter(filter){chips.forEach(function(c){c.classList.toggle('active',c.getAttribute('data-filter')===filter);});cards.forEach(function(card){var show=filter==='all'||card.getAttribute('data-cat')===filter;card.setAttribute('data-visible',show?'1':'0');card.setAttribute('aria-hidden',show?'false':'true');});}chips.forEach(function(chip){chip.addEventListener('click',function(e){e.preventDefault();applyCourseFilter(chip.getAttribute('data-filter')||'all');});});applyCourseFilter('all');document.querySelectorAll('[data-href]').forEach(function(card){card.style.cursor='pointer';card.addEventListener('click',function(e){if(e.target.closest('a,button'))return;window.location.href=card.getAttribute('data-href');});});});</script><?php wp_footer(); ?>
</body></html>
