<?php
/**
 * Single Equipment — Whale Dive Centre
 */
while(have_posts()): the_post();
$price = get_post_meta(get_the_ID(), '_wm_price', true);
$stock = get_post_meta(get_the_ID(), '_wm_stock', true);
$sizes = get_post_meta(get_the_ID(), '_wm_sizes', true);
$category = wp_get_post_terms(get_the_ID(), 'equipment_category');
$brand = wp_get_post_terms(get_the_ID(), 'equipment_brand');
$cat_name = !empty($category) ? $category[0]->name : '';
$cat_slug = !empty($category) ? $category[0]->slug : '';
$brand_name = !empty($brand) ? $brand[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
function wdc_single_equipment_image_url($title, $cat_slug, $theme_uri) {
    $key = strtolower($title . ' ' . $cat_slug);
    $map = [
        'mask' => 'wdc-mask-compressed.png',
        'fin' => 'wdc-fins-compressed.png',
        'bcd' => 'wdc-bcd-compressed.png',
        'regulator' => 'wdc-regulators-compressed.png',
        'computer' => 'wdc-dive-computer-compressed.png',
        'wetsuit' => 'wdc-wetsuit-compressed.png',
    ];
    foreach ($map as $needle => $file) {
        if (strpos($key, $needle) !== false) {
            return $theme_uri . '/assets/' . $file;
        }
    }
    return '';
}
$equipment_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$equipment_image_url) {
    $equipment_image_url = wdc_single_equipment_image_url(get_the_title(), $cat_slug, $theme_uri);
}
endwhile; rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-single-equipment-photo">.wd-single-gear-visual.has-photo{min-height:360px;padding:0;overflow:hidden}.wd-single-gear-visual.has-photo img{width:100%;height:100%;min-height:360px;object-fit:cover;display:block}.wd-single-gear-visual.has-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(3,23,45,0),rgba(3,23,45,.28));pointer-events:none}</style></head>
<body <?php body_class('whaledive-inner whaledive-equipment whaledive-single-equip'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-compact-hero wd-equipment-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/">Home</a> <span>/</span> <a href="/equipment/">Equipment</a> <span>/</span> <?php the_title(); ?></div>
        <?php if($brand_name): ?><span class="wd-kicker"><?php echo esc_html($brand_name); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php if($equipment_image_url): ?>
        <div class="wd-single-gear-visual has-photo" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>">
          <img src="<?php echo esc_url($equipment_image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
        </div>
        <?php else: ?>
        <div class="wd-single-gear-visual" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>">
          <span><?php echo esc_html($cat_name ?: 'Dive Gear'); ?></span>
          <b><?php echo esc_html($brand_name ?: 'Whale Dive Centre'); ?></b>
          <small>Product photography will appear here when available.</small>
        </div>
        <?php endif; ?>

        <div class="wd-detail-meta">
          <?php if($cat_name): ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
          <?php if($brand_name): ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
          <?php if($price): ?><span class="wd-agency-badge">Rp <?php echo number_format((float)$price,0,',','.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <?php if (is_user_logged_in()): ?><button type="button" class="wd-btn wd-equipment-add-cart" data-item-id="<?php echo get_the_ID(); ?>">Add to Cart</button><?php else: ?><a class="wd-btn" href="/contact/">Check Availability</a><?php endif; ?>
          <a class="wd-btn alt" href="/equipment/">All Equipment</a>
        </div>
      </div>
      
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): the_content(); else: ?>
          <span class="wd-kicker">About this gear</span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <p>Quality dive gear selected by the Whale Dive Centre crew. Contact us for fit guidance, availability, and rental options.</p>
        <?php endif; endwhile; ?>
      <div class="wd-gear-service-grid"><article><b>Fit guidance</b><span>Ask the crew about mask seal, BCD size, exposure protection, and comfort before buying.</span></article><article><b>Training-ready setup</b><span>Recommended for course sessions, fun dives, and safer habit-building.</span></article><article><b>Availability check</b><span>Stock and sizing can change; confirm before checkout or pickup.</span></article></div>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if($price): ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label">Price</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if($sizes): ?><h4>Available Sizes</h4><p><?php echo esc_html($sizes); ?></p><?php endif; ?>
          <?php if (is_user_logged_in()): ?><button type="button" class="wd-btn wd-equipment-add-cart" data-item-id="<?php echo get_the_ID(); ?>" style="width:100%;text-align:center;margin-top:16px">Add to Cart</button><?php else: ?><a class="wd-btn" href="/contact/" style="width:100%;text-align:center;margin-top:16px">Check Availability</a><?php endif; ?>
        </div>
      </aside>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?><script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.wd-equipment-add-cart').forEach(function(btn){
    btn.addEventListener('click', function(){
      if (!window.wmCart || !wmCart.addToCart) { window.location.href = '/checkout/'; return; }
      var original = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Adding...';
      wmCart.addToCart('equipment', btn.getAttribute('data-item-id'), 1, {}).then(function(data){
        if (data && data.success) { window.location.href = '/checkout/'; return; }
        btn.disabled = false;
        btn.textContent = original;
      }).catch(function(){ btn.disabled = false; btn.textContent = original; });
    });
  });
});
</script>
</body></html>