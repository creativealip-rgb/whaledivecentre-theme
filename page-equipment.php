<?php
/**
 * Template Name: Whale Dive Equipment
 */
$all_items = get_posts([
    'post_type'   => 'wm_equipment',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
]);
$categories = get_terms(['taxonomy' => 'equipment_category', 'hide_empty' => true]);
$brands = get_terms(['taxonomy' => 'equipment_brand', 'hide_empty' => true]);
$theme_uri = get_stylesheet_directory_uri();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-equipment'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero wd-equipment-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker">Scuba gear support</span><h1>Equipment that fits your dive, not just your cart.</h1><p>Browse gear by category, check pricing, and get crew guidance before buying or renting.</p><div class="wd-actions"><a class="wd-btn" href="/contact/">Ask Availability</a><a class="wd-btn alt" href="#equipment-catalog">Browse Gear</a></div></div><aside class="wd-inner-card"><b>Gear support covers</b><ul><li>Fit and comfort checks</li><li>Training-ready recommendations</li><li>Buy or rent guidance</li><li>Trusted brands only</li></ul></aside></div></section>

  <section id="equipment-catalog" class="wd-section white wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">Equipment catalog</span>
      <h2 class="wd-title">Dive gear from trusted brands</h2>
      <p class="wd-sub"><?php echo count($all_items); ?> products across <?php echo count($categories); ?> categories.</p>

      <div id="equipFilters" class="wd-filter-bar">
        <button class="wd-chip active" data-filter="all">All Gear</button>
        <?php if(!empty($categories) && !is_wp_error($categories)): foreach($categories as $cat): ?>
          <button class="wd-chip" data-filter="cat-<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <div id="equipGrid" class="wd-equipment-grid wd-page-grid">
        <?php foreach($all_items as $item):
          $price = get_post_meta($item->ID, '_wm_price', true);
          $stock = get_post_meta($item->ID, '_wm_stock', true);
          $sizes = get_post_meta($item->ID, '_wm_sizes', true);
          $cat_terms = wp_get_post_terms($item->ID, 'equipment_category', ['fields' => 'all']);
          $brand_terms = wp_get_post_terms($item->ID, 'equipment_brand', ['fields' => 'names']);
          $cat_slug = !empty($cat_terms) ? $cat_terms[0]->slug : '';
          $cat_name = !empty($cat_terms) ? $cat_terms[0]->name : '';
          $brand_name = !empty($brand_terms) ? $brand_terms[0] : '';
          $permalink = get_permalink($item->ID);
        ?>
        <article class="wd-equip-card wd-detail-card" data-cat="cat-<?php echo esc_attr($cat_slug); ?>">
          <div class="wd-equip-icon"><?php echo esc_html(mb_substr($cat_name, 0, 1)); ?></div>
          <h3><?php echo esc_html($item->post_title); ?></h3>
          <div class="wd-course-meta">
            <?php if($cat_name): ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
            <?php if($brand_name): ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
          </div>
          <?php if($price): ?>
          <div class="wd-equip-price">
            <span class="wd-price-label">Price</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if($sizes): ?><p class="wd-equip-sizes">Sizes: <?php echo esc_html($sizes); ?></p><?php endif; ?>
          <?php if($stock): ?><p class="wd-equip-stock"><?php echo esc_html($stock); ?> in stock</p><?php endif; ?>
          <a href="<?php echo esc_url($permalink); ?>">View details &rarr;</a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Need gear advice?</span><h2 class="wd-title">The crew helps you find the right fit.</h2><p class="wd-sub">Tell us your certification level, dive plans, and budget — we recommend gear that works.</p><a class="wd-btn alt" href="/contact/">Ask the Crew</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>WhatsApp: <?php echo esc_html(get_option("wdc_whatsapp_number", "@whaledivecentre.id")); ?></p><p>Bali dive crew — base details available on inquiry</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<?php wp_footer(); ?>
</body></html>
