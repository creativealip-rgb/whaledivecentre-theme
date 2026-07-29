<?php
/**
 * Archive: Dive Sites — Whale Dive Centre
 */
$all_sites = get_posts([
    'post_type'   => 'wm_dive_site',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
]);
$regions = get_terms(['taxonomy' => 'dive_region', 'hide_empty' => true]);
$difficulties = get_terms(['taxonomy' => 'dive_difficulty', 'hide_empty' => true]);
$theme_uri = get_stylesheet_directory_uri();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-divesites'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-inner-hero wd-divesite-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker">Explore Bali</span><h1>Dive sites worth every breath.</h1><p>From legendary shipwrecks to manta ray encounters — discover Bali&rsquo;s best underwater worlds with the Whale Dive Centre crew.</p><div class="wd-actions"><a class="wd-btn" href="/contact/">Plan a Dive Trip</a><a class="wd-btn alt" href="#sites-list">Explore Sites</a></div></div><aside class="wd-inner-card"><b>Why dive with us</b><ul><li>Local crew, local knowledge</li><li>Small-group trips</li><li>Safety-first site briefings</li><li>All levels welcome</li></ul></aside></div></section>

  <!-- Map placeholder -->
  <section class="wd-section wd-map-section">
    <div class="wd-shell">
      <div class="wd-map-placeholder">
        <div class="wd-map-inner">
          <span class="wd-kicker">Bali dive map</span>
          <h3>Bali dive site guide</h3>
          <p>Our dive site guide is being curated by the Whale Dive Centre crew. For current recommendations, conditions, and trip planning, contact us directly.</p>
          <div class="wd-map-pins">
            <?php foreach($all_sites as $s): ?>
              <span class="wd-map-pin"><?php echo esc_html($s->post_title); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="sites-list" class="wd-section white wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">All dive sites</span>
      <h2 class="wd-title">Ask the crew for current dive site recommendations</h2>

      <div class="wd-filter-bar">
        <button class="wd-chip active" data-filter="all">All Sites</button>
        <?php if(!empty($regions) && !is_wp_error($regions)): foreach($regions as $reg): ?>
          <button class="wd-chip" data-filter="reg-<?php echo esc_attr($reg->slug); ?>"><?php echo esc_html($reg->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <div class="wd-sites-grid wd-page-grid">
        <?php foreach($all_sites as $site):
          $highlights = get_post_meta($site->ID, '_wm_highlights', true);
          $best_season = get_post_meta($site->ID, '_wm_best_season', true);
          $depth = get_post_meta($site->ID, '_wm_depth_range', true);
          $reg_terms = wp_get_post_terms($site->ID, 'dive_region', ['fields' => 'all']);
          $diff_terms = wp_get_post_terms($site->ID, 'dive_difficulty', ['fields' => 'names']);
          $reg_slug = !empty($reg_terms) ? $reg_terms[0]->slug : '';
          $diff_name = !empty($diff_terms) ? $diff_terms[0] : '';
          $permalink = get_permalink($site->ID);
          // Color gradient based on region
          $gradients = ['northeast-bali'=>'#0a3d62,#1abc9c','east-bali'=>'#0c2d48,#1abc9c','southeast-islands'=>'#145374,#0a3d62','northwest-bali'=>'#0a3d62,#2c3e50'];
          $grad = isset($gradients[$reg_slug]) ? $gradients[$reg_slug] : '#0a3d62,#145374';
        ?>
        <article class="wd-site-card" data-region="reg-<?php echo esc_attr($reg_slug); ?>">
          <div class="wd-site-img" style="background:linear-gradient(135deg,<?php echo $grad; ?>)">
            <?php if($diff_name): ?><span class="wd-site-badge"><?php echo esc_html($diff_name); ?></span><?php endif; ?>
          </div>
          <h3><?php echo esc_html($site->post_title); ?></h3>
          <?php if($highlights): ?><p><?php echo esc_html(wp_trim_words($highlights, 12, '...')); ?></p><?php endif; ?>
          <div class="wd-site-meta">
            <?php if($depth): ?><span><?php echo esc_html($depth); ?></span><?php endif; ?>
            <?php if($best_season): ?><span><?php echo esc_html($best_season); ?></span><?php endif; ?>
          </div>
          <a href="<?php echo esc_url($permalink); ?>" class="wd-site-link">Explore site &rarr;</a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Ready to explore?</span><h2 class="wd-title">Plan your next dive trip with the crew.</h2><p class="wd-sub">Tell us your dates, level, and preferred sites — we handle logistics, safety, and fun.</p><a class="wd-btn alt" href="/contact/">Plan a Trip</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water-diver/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course-idc/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var chips = document.querySelectorAll('.wd-chip');
  var cards = document.querySelectorAll('.wd-site-card');
  chips.forEach(function(chip) {
    chip.addEventListener('click', function() {
      chips.forEach(function(c) { c.classList.remove('active'); });
      chip.classList.add('active');
      var filter = chip.getAttribute('data-filter');
      cards.forEach(function(card) {
        card.style.display = (filter === 'all' || card.getAttribute('data-region') === filter) ? '' : 'none';
      });
    });
  });
});
</script>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?></body></html>