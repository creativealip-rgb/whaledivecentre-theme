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
?>
<?php get_header(); ?>
<?php get_header(); ?>

  <section class="wd-inner-hero wd-equipment-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker"><?php echo esc_html(contenly_tr('Dukungan peralatan selam', 'Scuba gear support')); ?></span><h1><?php echo esc_html(contenly_tr('Peralatan yang sesuai dengan dive, bukan hanya keranjang Anda.', 'Equipment that fits your dive, not just your cart.')); ?></h1><p><?php echo esc_html(contenly_tr('Jelajahi gear berdasarkan kategori, cek harga, dan dapatkan panduan crew sebelum membeli atau menyewa.', 'Browse gear by category, check pricing, and get crew guidance before buying or renting.')); ?></p><div class="wd-actions"><a class="wd-btn" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Ketersediaan', 'Ask Availability')); ?></a><a class="wd-btn alt" href="#equipment-catalog"><?php echo esc_html(contenly_tr('Lihat Peralatan', 'Browse Gear')); ?></a></div></div><aside class="wd-inner-card"><b><?php echo esc_html(contenly_tr('Dukungan gear mencakup', 'Gear support covers')); ?></b><ul><li><?php echo esc_html(contenly_tr('Pemeriksaan pas dan kenyamanan', 'Fit and comfort checks')); ?></li><li><?php echo esc_html(contenly_tr('Rekomendasi siap latihan', 'Training-ready recommendations')); ?></li><li><?php echo esc_html(contenly_tr('Panduan beli atau sewa', 'Buy or rent guidance')); ?></li><li><?php echo esc_html(contenly_tr('Hanya brand terpercaya', 'Trusted brands only')); ?></li></ul></aside></div></section>

  <section id="equipment-catalog" class="wd-section white wd-center">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Katalog peralatan', 'Equipment catalog')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Peralatan selam dari brand terpercaya', 'Dive gear from trusted brands')); ?></h2>
      <p class="wd-sub"><?php echo count($all_items); ?> <?php echo contenly_tr('produk dari', 'products across'); ?> <?php echo count($categories) ? count($categories) . ' ' . contenly_tr('kategori', 'categories') : contenly_tr('kategori gear pilihan', 'featured gear categories'); ?>.</p>
      <div id="equipFilters" class="wd-filter-bar">
        <button class="wd-chip active" data-filter="all"><?php echo esc_html(contenly_tr('Semua Gear', 'All Gear')); ?></button>
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
          <div class="wd-equip-card-body" style="padding:16px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important;">
            <div class="wd-course-meta wd-shop-meta" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
              <?php if($cat_name): ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
              <?php if($brand_name): ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
            </div>
            <h3 style="font-size:20px!important;line-height:1.28!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important;"><?php echo esc_html($item->post_title); ?></h3>
            <?php if($price): ?>
            <div class="wd-equip-price" style="margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important;">
              <span class="wd-price-label" style="display:block!important;width:100%!important;flex-basis:100%!important;margin:0 0 4px!important;color:#5f7180!important;font-size:10px!important;font-weight:800!important;letter-spacing:.02em!important;"><?php echo contenly_tr('Harga beli · sewa atas permintaan', 'Buy price · rental on request'); ?></span>
              <span class="wd-price-amount" style="display:block!important;width:100%!important;flex-basis:100%!important;color:#06384d!important;font-size:17px!important;line-height:1.1!important;font-weight:950!important;letter-spacing:-.02em!important;">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
            </div>
            <?php endif; ?>
            <div class="wd-equip-chips" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
              <?php if($sizes): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo contenly_tr('Ukuran: ', 'Sizes: '); ?><?php echo esc_html($sizes); ?></span><?php endif; ?>
              <span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo $stock ? esc_html($stock) . ' ' . contenly_tr('stok', 'in stock') : contenly_tr('Cek ketersediaan', 'Check availability'); ?></span>
            </div>
            <div class="wd-equip-actions" style="margin-top:8px!important;padding-top:0!important;border-top:0!important;display:flex!important;gap:8px!important;flex-wrap:wrap!important;">
              <a class="wd-mini-link" style="min-height:38px!important;min-width:100px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:13px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.14)!important;color:#06384d!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url($permalink); ?>"><?php echo contenly_tr('Lihat Detail', 'View Details'); ?></a>
              <?php if($price && (!$stock || (int)$stock > 0)): ?>
              <a class="wd-mini-btn" onclick="event.stopPropagation();" style="min-height:38px!important;min-width:100px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:13px!important;font-weight:900!important;background:#06384d!important;border:1px solid #06384d!important;color:#fff!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url(home_url('/direct-checkout/?type=equipment&item=' . rawurlencode($item->post_title) . '&item_id=' . $item->ID . '&price=' . $price)); ?>"><?php echo contenly_tr('Beli', 'Buy Now'); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo contenly_tr('Butuh saran gear?', 'Need gear advice?'); ?></span><h2><?php echo esc_html(contenly_tr('Crew bantu cari yang pas.', 'The crew helps you find the right fit.')); ?></h2><p><?php echo contenly_tr('Ceritakan level sertifikasi, rencana dive, dan budget — kami rekomendasikan gear yang cocok.', 'Tell us your certification level, dive plans, and budget — we recommend gear that works.'); ?></p><a class="wd-btn alt" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Ukuran Gear', 'Ask About Gear Fit')); ?></a></div></section>

  <?php get_footer(); ?>