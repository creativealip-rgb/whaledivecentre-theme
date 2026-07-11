<?php
/**
 * Single Dive Site — Whale Dive Centre
 */
while(have_posts()): the_post();
$highlights = get_post_meta(get_the_ID(), '_wm_highlights', true);
$best_season = get_post_meta(get_the_ID(), '_wm_best_season', true);
$depth = get_post_meta(get_the_ID(), '_wm_depth_range', true);
$region = wp_get_post_terms(get_the_ID(), 'dive_region');
$difficulty = wp_get_post_terms(get_the_ID(), 'dive_difficulty');
$region_name = !empty($region) ? $region[0]->name : '';
$diff_name = !empty($difficulty) ? $difficulty[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
endwhile; rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-divesite'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-compact-hero wd-divesite-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/">Home</a> <span>/</span> <span>/</span> <?php the_title(); ?></div>
        <?php if($region_name): ?><span class="wd-kicker"><?php echo esc_html($region_name); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="wd-detail-meta">
          <?php if($diff_name): ?><span><?php echo esc_html($diff_name); ?></span><?php endif; ?>
          <?php if($depth): ?><span><?php echo esc_html($depth); ?></span><?php endif; ?>
          <?php if($best_season): ?><span><?php echo esc_html($best_season); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="/contact/">Plan a Dive Trip</a>
</div>
      </div>
      
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): the_content(); else: ?>
          <span class="wd-kicker">About this dive site</span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <?php if($highlights): ?><p><strong>Highlights:</strong> <?php echo esc_html($highlights); ?></p><?php endif; ?>
          <p>Contact the crew for trip scheduling, conditions, and group availability.</p>
        <?php endif; endwhile; ?>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <h3>Plan Your Dive</h3>
          <dl class="wd-info-list">
            <?php if($diff_name): ?><dt>Difficulty</dt><dd><?php echo esc_html($diff_name); ?></dd><?php endif; ?>
            <?php if($depth): ?><dt>Depth Range</dt><dd><?php echo esc_html($depth); ?></dd><?php endif; ?>
            <?php if($best_season): ?><dt>Best Season</dt><dd><?php echo esc_html($best_season); ?></dd><?php endif; ?>
            <?php if($region_name): ?><dt>Region</dt><dd><?php echo esc_html($region_name); ?></dd><?php endif; ?>
          </dl>
          <a class="wd-btn" href="/contact/" style="width:100%;text-align:center;margin-top:16px">Plan a Trip</a>
        </div>
      </aside>
    </div>
  </section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Explore more</span><h2 class="wd-title">Discover Bali&rsquo;s best underwater worlds.</h2><p class="wd-sub">From shipwrecks to manta encounters — the crew knows every site.</p>
</div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water-diver/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course-idc/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?></body></html>