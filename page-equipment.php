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
$categories = get_terms(['taxonomy' => 'equipment_category', 'hide_empty' => true]);
$categories = is_wp_error($categories) ? [] : $categories;
$brands = get_terms(['taxonomy' => 'equipment_brand', 'hide_empty' => true]);
$brands = is_wp_error($brands) ? [] : $brands;
$theme_uri = get_stylesheet_directory_uri();
function wdc_equipment_detail_slug($title, $cat_slug) {
    $key = strtolower($title . ' ' . $cat_slug);
    $map = [
        'mask' => 'masks',
        'wetsuit' => 'wetsuits',
        'bcd' => 'bcd',
        'regulator' => 'regulators',
        'fin' => 'fins',
        'computer' => 'dive-computers',
    ];
    foreach ($map as $needle => $slug) {
        if (strpos($key, $needle) !== false) {
            return $slug;
        }
    }
    return sanitize_title($cat_slug ?: $title);
}
function wdc_equipment_image_url($title, $cat_slug, $theme_uri) {
    $key = strtolower($title . ' ' . $cat_slug);
    $map = [
        'mask' => 'wdc-equipment-mask-real.png',
        'fin' => 'wdc-equipment-fins-real.png',
        'bcd' => 'wdc-equipment-bcd-real.png',
        'regulator' => 'wdc-equipment-regulator-real.png',
        'computer' => 'wdc-equipment-dive-computer-real.png',
        'wetsuit' => 'wdc-equipment-wetsuit-real.png',
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
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-equipment-ux-pass">.wd-gear-note{display:flex;gap:12px;justify-content:center;align-items:center;flex-wrap:wrap;max-width:820px;margin:0 auto 22px;padding:14px 18px;border-radius:18px;background:#eef8fb;border:1px solid rgba(0,91,122,.1);color:#5b7180}.wd-gear-note b{color:#06384d}.wd-gear-finder{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:0 0 28px}.wd-gear-finder span,.wd-gear-finder a{min-height:42px;display:inline-flex;align-items:center;border-radius:999px;padding:0 14px;font-weight:800;font-size:13px}.wd-gear-finder span{background:#06384d;color:#fff}.wd-gear-finder a{background:#fff;color:#0b617c;border:1px solid rgba(11,97,124,.16);text-decoration:none}.whaledive-equipment .wd-mini-btn{background:#06384d!important;color:#fff!important}.whaledive-equipment .wd-mini-link{border-color:rgba(11,97,124,.24)!important;color:#0b617c!important}.wd-equip-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;z-index:0;padding:14px;background:linear-gradient(135deg,#e8fbff,#ffffff)}.wd-equip-visual.has-photo:before{background:linear-gradient(180deg,rgba(3,23,45,.05),rgba(3,23,45,.55));z-index:1}.wd-equip-visual.has-photo:after{z-index:1}.wd-equip-visual.has-photo .wd-equip-type{background:rgba(3,23,45,.62);backdrop-filter:blur(8px)}.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}.whaledive-equipment .wd-shop-card .wd-equip-card-body{padding:14px!important;display:flex!important;flex-direction:column!important;min-height:238px!important}.whaledive-equipment .wd-shop-card .wd-course-meta{gap:6px!important;margin:0 0 9px!important}.whaledive-equipment .wd-shop-card .wd-course-meta span{padding:5px 8px!important;border-radius:999px!important;background:#eef8fb!important;color:#0b617c!important;font-size:10px!important;font-weight:900!important;line-height:1!important;letter-spacing:.02em!important}.whaledive-equipment .wd-shop-card h3{font-size:20px!important;line-height:1.05!important;letter-spacing:-.03em!important;margin:0 0 10px!important;color:#061a36!important}.whaledive-equipment .wd-shop-card .wd-equip-price{margin:0 0 10px!important;padding:0!important;background:transparent!important;border:0!important}.whaledive-equipment .wd-shop-card .wd-price-label{display:block!important;font-size:11px!important;color:#6f7f8d!important;line-height:1.2!important;margin-bottom:4px!important}.whaledive-equipment .wd-shop-card .wd-price-amount{display:block!important;font-size:clamp(15px,1.2vw,17px)!important;font-weight:900!important;color:#06384d!important;line-height:1!important;white-space:nowrap!important;letter-spacing:-.02em!important}.whaledive-equipment .wd-shop-card .wd-equip-chips{gap:6px!important;margin:0 0 12px!important}.whaledive-equipment .wd-shop-card .wd-equip-chips span{padding:6px 8px!important;border-radius:999px!important;background:#f1fbff!important;color:#4f6575!important;font-size:10px!important;font-weight:800!important;line-height:1.1!important}.whaledive-equipment .wd-shop-card .wd-equip-actions{margin-top:auto!important}.whaledive-equipment .wd-shop-card .wd-mini-link{min-height:34px!important;width:auto!important;padding:0 12px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.12)!important;color:#06384d!important}.whaledive-equipment .wd-shop-card .wd-mini-link:after{content:' →'}@media(max-width:980px){.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important}}@media(max-width:760px){.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}.whaledive-equipment .wd-shop-card .wd-equip-card-body{min-height:220px!important}}@media(max-width:540px){.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:1fr!important}.whaledive-equipment .wd-shop-card{display:grid!important;grid-template-columns:118px minmax(0,1fr)!important}.whaledive-equipment .wd-shop-card .wd-equip-visual{height:100%!important;min-height:178px!important}.whaledive-equipment .wd-shop-card .wd-equip-card-body{min-height:0!important;padding:12px!important}.whaledive-equipment .wd-shop-card h3{font-size:18px!important}}
/* Final equipment catalog polish: balanced compact grid and safer product crops. */
.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:16px!important}.whaledive-equipment #equipGrid .wd-equip-visual{background:radial-gradient(circle at 50% 44%,rgba(76,200,237,.22),rgba(255,255,255,.2) 50%,#f3fbff 100%)!important}.whaledive-equipment #equipGrid .wd-equip-visual:before,.whaledive-equipment #equipGrid .wd-equip-visual:after{display:none!important}.whaledive-equipment #equipGrid .wd-equip-photo{width:100%!important;height:100%!important;object-fit:contain!important;padding:12px!important;display:block!important;position:relative!important;z-index:2!important;opacity:1!important;filter:none!important;mix-blend-mode:normal!important}.whaledive-equipment #equipGrid .wd-course-meta{gap:6px!important;margin:0 0 9px!important}.whaledive-equipment #equipGrid .wd-course-meta span{padding:5px 8px!important;border-radius:999px!important;background:#eef8fb!important;color:#0b617c!important;font-size:10px!important;font-weight:900!important;line-height:1!important}.whaledive-equipment #equipGrid .wd-mini-link:after{content:' →'}@media(max-width:980px){.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}}@media(max-width:620px){.whaledive-equipment #equipGrid.wd-page-grid{grid-template-columns:1fr!important}}

/* Equipment visual refinement: remove heavy dark media band and give labels breathing room. */
.whaledive-equipment #equipGrid .wd-equip-visual{height:154px!important;overflow:hidden!important;background:radial-gradient(circle at 50% 42%,rgba(76,200,237,.22),rgba(255,255,255,.72) 52%,#eef8fb 100%)!important;border-bottom:1px solid rgba(6,56,77,.08)!important}.whaledive-equipment #equipGrid .wd-equip-type{top:12px!important;left:12px!important;right:auto!important;max-width:calc(100% - 24px)!important;padding:6px 10px!important;border-radius:999px!important;background:rgba(255,255,255,.9)!important;color:#06384d!important;border:1px solid rgba(6,56,77,.12)!important;box-shadow:0 8px 18px rgba(2,21,43,.08)!important;font-size:10px!important;line-height:1!important;letter-spacing:.08em!important;white-space:nowrap!important;overflow:hidden!important;text-overflow:ellipsis!important}.whaledive-equipment #equipGrid .wd-equip-photo{transform:scale(1.1)!important;transform-origin:center!important;padding:16px 16px 8px!important}.whaledive-equipment #equipGrid .wd-equip-card{box-shadow:0 12px 28px rgba(2,21,43,.06)!important}.whaledive-equipment #equipGrid .wd-equip-card-body{min-height:228px!important}.whaledive-equipment #equipGrid .wd-mini-link{gap:4px!important}.whaledive-equipment #equipGrid .wd-mini-link:after{content:' →';margin-left:2px!important}
.whaledive-equipment #equipGrid .wd-equip-type{display:none!important}
@media(max-width:620px){.whaledive-equipment .wdc-card-cta .wd-shell{width:calc(100% - 48px)!important;max-width:none!important;margin-left:24px!important;margin-right:24px!important;padding:76px 24px 68px!important}.whaledive-equipment .wdc-card-cta h2{font-size:clamp(30px,8.8vw,40px)!important;line-height:1.08!important;width:100%!important;max-width:100%!important;white-space:normal!important;overflow-wrap:break-word!important}.whaledive-equipment .wdc-card-cta p{width:100%!important;max-width:100%!important;white-space:normal!important;overflow-wrap:break-word!important}.whaledive-equipment .wdc-card-cta .wd-btn{width:100%!important;max-width:100%!important;justify-content:center!important}}
</style></head>
<body <?php body_class('whaledive-inner whaledive-equipment'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>WHALE DIVE CENTRE</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero wd-equipment-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker">Scuba gear support</span><h1>Equipment that fits your dive, not just your cart.</h1><p>Browse gear by category, check pricing, and get crew guidance before buying or renting.</p><div class="wd-actions"><a class="wd-btn" href="/contact/">Ask Availability</a><a class="wd-btn alt" href="#equipment-catalog">Browse Gear</a></div></div><aside class="wd-inner-card"><b>Gear support covers</b><ul><li>Fit and comfort checks</li><li>Training-ready recommendations</li><li>Buy or rent guidance</li><li>Trusted brands only</li></ul></aside></div></section>

  <section id="equipment-catalog" class="wd-section white wd-center">
    <div class="wd-shell">
      <span class="wd-kicker">Equipment catalog</span>
      <h2 class="wd-title">Dive gear from trusted brands</h2>
      <p class="wd-sub"><?php echo count($all_items); ?> products across <?php echo count($categories) ? count($categories) . ' categories' : 'featured gear categories'; ?>.</p>
      <div id="equipFilters" class="wd-filter-bar">
        <button class="wd-chip active" data-filter="all">All Gear</button>
        <?php if(!empty($categories) && !is_wp_error($categories)): foreach($categories as $cat): ?>
          <button class="wd-chip" data-filter="cat-<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <div id="equipGrid" class="wd-equipment-grid wd-page-grid" style="align-items:start!important;grid-auto-rows:auto!important;">
        <?php foreach($all_items as $item):
          $price = get_post_meta($item->ID, '_wm_price', true);
          $stock = get_post_meta($item->ID, '_wm_stock', true);
          $sizes = get_post_meta($item->ID, '_wm_sizes', true);
          $cat_terms = wp_get_post_terms($item->ID, 'equipment_category', ['fields' => 'all']);
          $cat_terms = is_wp_error($cat_terms) ? [] : $cat_terms;
          $brand_terms = wp_get_post_terms($item->ID, 'equipment_brand', ['fields' => 'names']);
          $brand_terms = is_wp_error($brand_terms) ? [] : $brand_terms;
          $cat_slug = !empty($cat_terms) ? $cat_terms[0]->slug : '';
          $cat_name = !empty($cat_terms) ? $cat_terms[0]->name : '';
          $brand_name = !empty($brand_terms) ? $brand_terms[0] : '';
          $permalink = home_url('/equipment/' . wdc_equipment_detail_slug($item->post_title, $cat_slug) . '/');
          $use_case = $cat_name ? 'Crew-selected ' . strtolower($cat_name) . ' for training, comfort, and safer dive habits.' : 'Crew-selected dive gear for training, comfort, and safer dive habits.';
          $image_url = get_the_post_thumbnail_url($item->ID, 'large') ?: wdc_equipment_image_url($item->post_title, $cat_slug, $theme_uri);
        ?>
        <article class="wd-equip-card wd-detail-card wd-shop-card" data-href="<?php echo esc_url($permalink); ?>" onclick="if(!event.target.closest('a,button')){window.location.href=this.dataset.href;}" data-cat="cat-<?php echo esc_attr($cat_slug); ?>" style="border-radius:18px!important;overflow:hidden!important;padding:0!important;background:#fff!important;box-shadow:0 14px 34px rgba(2,21,43,.07)!important;border:1px solid rgba(6,56,77,.08)!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;">
          <div class="wd-equip-visual <?php echo $image_url ? 'has-photo' : ''; ?>" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>" style="height:190px!important;min-height:0!important;border-radius:0!important;margin:0!important;overflow:hidden!important;background:radial-gradient(circle at 50% 42%,rgba(76,200,237,.24),rgba(255,255,255,.68) 50%,#eef8fb 100%)!important;border-bottom:1px solid rgba(6,56,77,.08)!important;">
            <?php if($image_url): ?><img class="wd-equip-photo" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item->post_title); ?>" loading="lazy" style="width:100%!important;height:100%!important;object-fit:contain!important;padding:14px 14px 8px!important;display:block!important;position:relative!important;z-index:2!important;transform:none!important;transform-origin:center!important;" onerror="this.closest('.wd-equip-visual').classList.remove('has-photo');this.remove();"><?php else: ?><span class="wd-equip-mark"><?php echo esc_html($cat_name ? mb_substr($cat_name, 0, 1) : 'G'); ?></span><?php endif; ?>
            <?php if($cat_name): ?><span class="wd-equip-type"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
          </div>
          <div class="wd-equip-card-body" style="padding:14px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important;">
            <div class="wd-course-meta wd-shop-meta" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
              <?php if($cat_name): ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
              <?php if($brand_name): ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
            </div>
            <h3 style="font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important;"><?php echo esc_html($item->post_title); ?></h3>
            <?php if($price): ?>
            <div class="wd-equip-price" style="margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important;">
              <span class="wd-price-label" style="display:block!important;width:100%!important;flex-basis:100%!important;margin:0 0 4px!important;color:#5f7180!important;font-size:10px!important;font-weight:800!important;letter-spacing:.02em!important;">Buy price · rental on request</span>
              <span class="wd-price-amount" style="display:block!important;width:100%!important;flex-basis:100%!important;color:#06384d!important;font-size:17px!important;line-height:1.1!important;font-weight:950!important;letter-spacing:-.02em!important;">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
            </div>
            <?php endif; ?>
            <div class="wd-equip-chips" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
              <?php if($sizes): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;">Sizes: <?php echo esc_html($sizes); ?></span><?php endif; ?>
              <span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo $stock ? esc_html($stock) . ' in stock' : 'Check availability'; ?></span>
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

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker">Need gear advice?</span><h2>The crew helps you find the right fit.</h2><p>Tell us your certification level, dive plans, and budget — we recommend gear that works.</p><a class="wd-btn alt" href="/contact/">Ask About Gear Fit</a></div></section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/contact/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>/* catalog add cart */document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.wd-equipment-add-cart').forEach(function(btn){btn.addEventListener('click',function(){if(!window.wmCart||!wmCart.addToCart){window.location.href='/checkout/';return;}var original=btn.textContent;btn.disabled=true;btn.textContent='Adding...';wmCart.addToCart('equipment',btn.getAttribute('data-item-id'),1,{}).then(function(data){if(data&&data.success){window.location.href='/checkout/';return;}btn.disabled=false;btn.textContent=original;}).catch(function(){btn.disabled=false;btn.textContent=original;});});});});</script><script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});document.querySelectorAll('[data-href]').forEach(function(card){card.style.cursor='pointer';card.addEventListener('click',function(e){if(e.target.closest('a,button'))return;window.location.href=card.getAttribute('data-href');});});});</script><?php wp_footer(); ?>
</body></html>
