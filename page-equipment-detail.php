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
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style>
.wd-gear-detail-hero{min-height:610px;padding:132px 0 66px;background:radial-gradient(circle at 72% 20%,rgba(150,218,234,.28),transparent 34%),linear-gradient(135deg,#021126 0%,#06384d 52%,#0b617c 100%);color:#fff;position:relative;overflow:hidden}.wd-gear-detail-hero:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(2,17,38,.08),rgba(2,17,38,.28));pointer-events:none}.wd-gear-hero-grid{position:relative;z-index:1;display:grid;grid-template-columns:minmax(0,640px) 430px;gap:58px;align-items:center}.wd-gear-detail-hero h1{font-size:clamp(48px,6vw,76px);line-height:.98;margin:12px 0 16px;letter-spacing:-.055em;color:#fff}.wd-gear-detail-hero p{font-size:18px;line-height:1.68;color:rgba(255,255,255,.82);max-width:600px}.wd-gear-breadcrumb{margin-bottom:22px}.wd-gear-breadcrumb,.wd-gear-breadcrumb a{color:rgba(255,255,255,.78);font-size:13px;font-weight:800;text-decoration:none}.wd-gear-visual-card{position:relative;min-height:430px;border-radius:32px;background:radial-gradient(circle at 50% 44%,rgba(150,218,234,.32),rgba(255,255,255,.94) 58%,#eef8fb 100%);border:1px solid rgba(255,255,255,.34);box-shadow:0 28px 80px rgba(0,0,0,.26);overflow:hidden}.wd-gear-visual-card img{position:absolute;left:28px;right:28px;top:32px;bottom:32px;width:calc(100% - 56px);height:calc(100% - 64px);object-fit:contain;filter:drop-shadow(0 24px 38px rgba(2,17,38,.26));transform:scale(1.08)}.wd-gear-snapshot{position:absolute;left:18px;right:18px;bottom:18px;padding:18px;border-radius:22px;background:rgba(2,17,38,.34);border:1px solid rgba(255,255,255,.36);backdrop-filter:blur(14px) saturate(1.25);color:#fff}.wd-gear-snapshot span{display:block;font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.72)}.wd-gear-snapshot b{display:block;margin:5px 0 10px;font-size:21px;letter-spacing:-.03em}.wd-gear-snapshot ul{margin:0;padding:0;list-style:none;display:grid;gap:8px}.wd-gear-snapshot li{padding:8px 10px;border-radius:13px;background:rgba(255,255,255,.075);border:1px solid rgba(255,255,255,.11);font-size:13px;color:rgba(255,255,255,.88)}.wd-gear-detail-body{padding:56px 0 60px;background:#fff}.wd-gear-content-grid{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:34px;align-items:start}.wd-gear-main>.wd-kicker{display:inline-flex;width:max-content;padding:7px 13px;border-radius:999px;background:rgba(8,167,199,.12);color:#0b617c;border:1px solid rgba(8,167,199,.2);margin-bottom:14px}.wd-gear-main>.wd-title{position:relative;font-size:clamp(32px,3.8vw,46px);line-height:1.05;letter-spacing:-.035em;margin:0 0 16px;color:#06384d}.wd-gear-main>.wd-title:after{content:"";display:block;width:72px;height:4px;margin-top:14px;border-radius:999px;background:linear-gradient(90deg,#08a7c7,#96daea)}.wd-gear-main p{max-width:690px;font-size:16px;line-height:1.68;color:#425466}.wd-gear-outcomes{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:24px}.wd-gear-outcomes article{padding:18px;border-radius:18px;background:#f7fbfd;border:1px solid rgba(8,167,199,.14)}.wd-gear-outcomes b{display:block;margin-bottom:7px;color:#06384d}.wd-gear-outcomes span{display:block;color:#607684;font-size:14px;line-height:1.55}.wd-gear-sidebar{position:sticky;top:100px;padding:22px;border-radius:22px;background:linear-gradient(180deg,#fff 0%,#f1fbff 100%);border:1px solid rgba(8,167,199,.18);box-shadow:0 18px 44px rgba(2,32,46,.1)}.wd-gear-price{padding:18px;border-radius:18px;margin-bottom:12px;background:linear-gradient(135deg,rgba(8,167,199,.12),rgba(150,218,234,.18))}.wd-gear-price span{display:block;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#5f7180}.wd-gear-price strong{display:block;margin-top:4px;color:#06384d;font-size:28px;letter-spacing:-.04em}.wd-gear-sidebar p{color:#607684;line-height:1.6}.wd-gear-sidebar .wd-btn{width:100%;text-align:center;min-height:38px;border-radius:999px;margin-top:12px}@media(max-width:900px){.wd-gear-detail-hero{min-height:0;padding-top:108px;padding-bottom:46px}.wd-gear-hero-grid,.wd-gear-content-grid{grid-template-columns:1fr;gap:24px}.wd-gear-visual-card{min-height:360px}.wd-gear-outcomes{grid-template-columns:1fr}.wd-gear-sidebar{position:relative;top:auto}}
</style></head>
<body <?php body_class('whaledive-inner whaledive-equipment whaledive-single-equipment'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>
  <section class="wd-gear-detail-hero"><div class="wd-shell wd-gear-hero-grid"><div><div class="wd-gear-breadcrumb"><a href="/">Home</a> / <a href="/equipment/">Equipment</a> / <?php echo esc_html($item['title']); ?></div><span class="wd-kicker"><?php echo esc_html($item['category']); ?></span><h1><?php echo esc_html($item['title']); ?></h1><p><?php echo esc_html($item['fit']); ?></p><div class="wd-actions"><a class="wd-btn" href="/contact/">Ask Availability</a><a class="wd-btn alt" href="/equipment/">All Equipment</a></div></div><aside class="wd-gear-visual-card"><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['title']); ?>"></aside></div></section>
  <section class="wd-gear-detail-body"><div class="wd-shell wd-gear-content-grid"><div class="wd-gear-main"><span class="wd-kicker">About this gear</span><h2 class="wd-title"><?php echo esc_html($item['title']); ?></h2><p><?php echo esc_html($item['summary']); ?></p><div class="wd-gear-outcomes"><article><b>Fit first</b><span>We check comfort, size, and setup before recommending gear.</span></article><article><b>Dive-ready</b><span>Selected for training habits, simple use, and reliable performance.</span></article><article><b>Crew guidance</b><span>Ask about buy, rent, servicing, and care before checkout.</span></article></div></div><aside class="wd-gear-sidebar"><div class="wd-gear-price"><span>Buy price</span><strong>Rp <?php echo esc_html($item['price']); ?></strong></div><p>Rental and exact model availability can change. Message the crew for fitting, stock check, and bundle advice.</p><a class="wd-btn" href="/contact/">Request Gear Fit</a><a class="wd-btn alt" href="/equipment/">Back to Catalog</a></aside></div></section>
  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Need gear advice?</span><h2 class="wd-title">Get fit guidance before you buy.</h2><p class="wd-sub">Tell us your certification level, dive plans, and budget.</p><a class="wd-btn alt" href="/contact/">Ask the Crew</a></div></section>
  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Equipment</h3><a href="/equipment/masks/">Masks</a><a href="/equipment/wetsuits/">Wetsuits</a><a href="/equipment/bcd/">BCD</a><a href="/equipment/regulators/">Regulators</a><a href="/equipment/fins/">Fins</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var b=document.querySelector('.wd-hamburger'),m=document.querySelector('.wd-menu');if(b&&m){b.addEventListener('click',function(){var o=document.body.classList.toggle('wd-menu-open');b.setAttribute('aria-expanded',o?'true':'false')});}var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body></html>
