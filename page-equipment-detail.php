<?php
/**
 * Equipment detail route — Whale Dive Centre
 */
$slug = $GLOBALS['wd_equipment_slug'] ?? trim(basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH)), '/');
$theme_uri = get_stylesheet_directory_uri();
$gear = [
  'masks' => [
    'title' => 'Masks',
    'category' => 'Vision & fit',
    'image' => 'wdc-equipment-mask-real.png',
    'price' => '450.000',
    'fit' => 'Low-volume silicone skirt with a comfortable seal for training and travel dives.',
    'stock' => 'Ready for fitting',
    'sizes' => 'Standard / low-volume',
    'best' => 'New divers, pool sessions, and tropical reef dives.',
    'summary' => 'A good mask is the first piece of comfort equipment every diver notices. The crew helps you check seal, nose pocket comfort, strap angle, and field of view before you buy.',
  ],
  'wetsuits' => [
    'title' => 'Wetsuits',
    'category' => 'Thermal comfort',
    'image' => 'wdc-equipment-wetsuit-real.png',
    'price' => '1.850.000',
    'fit' => 'Flexible neoprene protection for longer, warmer, more relaxed dives.',
    'stock' => 'Size check required',
    'sizes' => 'XS - XXL',
    'best' => 'Training dives, boat trips, and repetitive dive days.',
    'summary' => 'The right wetsuit should feel snug without restricting breathing or shoulder movement. We help match thickness, cut, and size to local water conditions.',
  ],
  'bcd' => [
    'title' => 'BCD',
    'category' => 'Buoyancy control',
    'image' => 'wdc-equipment-bcd-real.png',
    'price' => '5.500.000',
    'fit' => 'Stable lift, simple pockets, and trim-friendly setup for calmer buoyancy.',
    'stock' => 'Ask crew availability',
    'sizes' => 'S - XL',
    'best' => 'Divers building better trim, comfort, and surface support.',
    'summary' => 'A BCD should support relaxed surface float, clean hose routing, and predictable buoyancy changes. The crew can help compare jacket and travel-style setups.',
  ],
  'regulators' => [
    'title' => 'Regulators',
    'category' => 'Breathing system',
    'image' => 'wdc-equipment-regulator-real.png',
    'price' => '6.750.000',
    'fit' => 'Smooth breathing with reliable first and second stage performance.',
    'stock' => 'Service history checked',
    'sizes' => 'DIN / Yoke options',
    'best' => 'Certified divers ready for personal life-support gear.',
    'summary' => 'Regulators are life-support equipment, so reliability, service access, and setup compatibility matter more than flashy specs. We help choose a safe long-term setup.',
  ],
  'fins' => [
    'title' => 'Fins',
    'category' => 'Propulsion',
    'image' => 'wdc-equipment-fins-real.png',
    'price' => '950.000',
    'fit' => 'Efficient blade response with foot-pocket comfort for less fatigue.',
    'stock' => 'Ready for fitting',
    'sizes' => 'S - XL',
    'best' => 'Skill practice, reef cruising, and current-aware diving.',
    'summary' => 'Fins should match your leg strength, boot type, and dive conditions. We check pocket fit and blade stiffness so your kick feels efficient, not tiring.',
  ],
  'dive-computers' => [
    'title' => 'Dive Computers',
    'category' => 'Dive safety',
    'image' => 'wdc-equipment-dive-computer-real.png',
    'price' => '4.800.000',
    'fit' => 'Clear depth, time, no-deco, and safety stop tracking on every dive.',
    'stock' => 'Ask model availability',
    'sizes' => 'Wrist unit',
    'best' => 'Divers tracking profiles, ascents, and repetitive dive safety.',
    'summary' => 'A dive computer makes every profile easier to monitor. We help set conservative alerts, explain no-deco limits, and match display style to your training level.',
  ],
];
$item = $gear[$slug] ?? $gear['masks'];
$image_url = $theme_uri . '/assets/' . $item['image'];

// Look up the real wm_equipment post to get stock and post ID
$equip_post = get_page_by_title($item['title'], OBJECT, 'wm_equipment');
$equip_id = $equip_post ? $equip_post->ID : 0;
$equip_stock = $equip_id ? (int) get_post_meta($equip_id, '_wm_stock', true) : -1; // -1 = unlimited/unknown
$is_out_of_stock = $equip_id && $equip_stock !== '' && $equip_stock <= 0;
$checkout_url = add_query_arg(['type' => 'equipment', 'item' => $item['title'], 'price' => preg_replace('/[^0-9]/', '', $item['price']), 'item_id' => $equip_id ?: (array_search($slug, array_keys($gear)) + 1)], '/direct-checkout/');
?>
<?php get_header(); ?>
<header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>WHALE DIVE CENTRE</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>
  <section class="wd-gear-detail-hero"><div class="wd-shell wd-gear-hero-grid"><div><div class="wd-gear-breadcrumb"><a href="/">Home</a> / <a href="/equipment/">Equipment</a> / <?php echo esc_html($item['title']); ?></div><span class="wd-kicker"><?php echo esc_html($item['category']); ?></span><h1><?php echo esc_html($item['title']); ?></h1><p><?php echo esc_html($item['fit']); ?></p><div class="wd-actions"><a class="wd-btn" href="<?php echo esc_url($checkout_url); ?>"<?php echo $is_out_of_stock ? ' style="opacity:.55;pointer-events:none;"' : ''; ?>><?php echo $is_out_of_stock ? 'Out of Stock' : 'Buy Now'; ?></a><a class="wd-btn alt" href="/equipment/">All Equipment</a></div></div><aside class="wd-gear-visual-card"><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['title']); ?>"></aside></div></section>
  <section class="wd-gear-detail-body"><div class="wd-shell wd-gear-content-grid"><div class="wd-gear-main"><span class="wd-kicker">About this gear</span><h2 class="wd-title"><?php echo esc_html($item['title']); ?></h2><p><?php echo esc_html($item['summary']); ?></p><div class="wd-gear-outcomes"><article><b>Fit first</b><span>We check comfort, size, and setup before recommending gear.</span></article><article><b>Dive-ready</b><span>Selected for training habits, simple use, and reliable performance.</span></article><article><b>Crew guidance</b><span>Ask about buy, rent, servicing, and care before checkout.</span></article></div></div><aside class="wd-gear-sidebar"><div class="wd-gear-price"><span>Buy price</span><strong>Rp <?php echo esc_html($item['price']); ?></strong></div><a class="wd-btn" href="<?php echo esc_url($checkout_url); ?>"<?php echo $is_out_of_stock ? ' style="opacity:.55;pointer-events:none;"' : ''; ?>><?php echo $is_out_of_stock ? 'Out of Stock' : 'Buy Now'; ?></a><a class="wd-btn alt" href="/equipment/">Back to Catalog</a></aside></div></section>
  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker">Need gear advice?</span><h2>Get fit guidance before you buy.</h2><p>Tell us your certification level, dive plans, and budget.</p><a class="wd-btn alt" href="/contact/">Ask the Crew</a></div></section>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script>
<?php get_footer(); ?>
