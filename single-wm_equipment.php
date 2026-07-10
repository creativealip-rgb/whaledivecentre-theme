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
        'mask' => 'wdc-mask.webp',
        'fin' => 'wdc-fins.webp',
        'bcd' => 'wdc-bcd.webp',
        'regulator' => 'wdc-regulators.webp',
        'computer' => 'wdc-dive-computer.webp',
        'wetsuit' => 'wdc-wetsuit.webp',
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
?>
<?php get_header(); ?>
<?php get_header(); ?>

  <section class="wd-compact-hero wd-equipment-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/"><?php echo contenly_tr('Beranda', 'Home'); ?></a> <span>/</span> <a href="/equipment/"><?php echo contenly_tr('Peralatan', 'Equipment'); ?></a> <span>/</span> <?php the_title(); ?></div>
        <?php if($brand_name): ?><span class="wd-kicker"><?php echo esc_html($brand_name); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php if($equipment_image_url): ?>
        <div class="wd-single-gear-visual has-photo" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>">
          <img src="<?php echo esc_url($equipment_image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
        </div>
        <?php else: ?>
        <div class="wd-single-gear-visual" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>">
          <span><?php echo esc_html($cat_name ?: contenly_tr('Peralatan Selam', 'Dive Gear')); ?></span>
          <b><?php echo esc_html($brand_name ?: 'Whale Dive Centre'); ?></b>
          <small><?php echo contenly_tr('Foto produk akan muncul di sini saat tersedia.', 'Product photography will appear here when available.'); ?></small>
        </div>
        <?php endif; ?>

        <div class="wd-detail-meta">
          <?php if($cat_name): ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
          <?php if($brand_name): ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
          <?php if($price): ?><span class="wd-agency-badge">Rp <?php echo number_format((float)$price,0,',','.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <?php if (is_user_logged_in()): ?><button type="button" class="wd-btn wd-equipment-add-cart" data-item-id="<?php echo get_the_ID(); ?>"><?php echo contenly_tr('Tambah ke Keranjang', 'Add to Cart'); ?></button><?php else: ?><a class="wd-btn" href="/contact/"><?php echo contenly_tr('Cek Ketersediaan', 'Check Availability'); ?></a><?php endif; ?>
          <a class="wd-btn alt" href="/equipment/"><?php echo contenly_tr('Semua Peralatan', 'All Equipment'); ?></a>
        </div>
      </div>
      
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): the_content(); else: ?>
          <span class="wd-kicker"><?php echo contenly_tr('Tentang Peralatan Ini', 'About this gear'); ?></span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <p><?php echo contenly_tr('Peralatan selam pilihan tim Whale Dive Centre. Hubungi kami untuk panduan ukuran, ketersediaan, dan opsi sewa.', 'Quality dive gear selected by the Whale Dive Centre crew. Contact us for fit guidance, availability, and rental options.'); ?></p>
        <?php endif; endwhile; ?>
      <div class="wd-gear-service-grid"><article><b><?php echo contenly_tr('Panduan ukuran', 'Fit guidance'); ?></b><span><?php echo contenly_tr('Tanyakan ke tim tentang seal mask, ukuran BCD, perlindungan exposure, dan kenyamanan sebelum membeli.', 'Ask the crew about mask seal, BCD size, exposure protection, and comfort before buying.'); ?></span></article><article><b><?php echo contenly_tr('Siap untuk pelatihan', 'Training-ready setup'); ?></b><span><?php echo contenly_tr('Direkomendasikan untuk sesi kursus, fun dive, dan membangun kebiasaan lebih aman.', 'Recommended for course sessions, fun dives, and safer habit-building.'); ?></span></article><article><b><?php echo contenly_tr('Cek ketersediaan', 'Availability check'); ?></b><span><?php echo contenly_tr('Stok dan ukuran bisa berubah; konfirmasi sebelum checkout atau pengambilan.', 'Stock and sizing can change; confirm before checkout or pickup.'); ?></span></article></div>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if($price): ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label"><?php echo contenly_tr('Harga', 'Price'); ?></span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if($sizes): ?><h4><?php echo contenly_tr('Ukuran Tersedia', 'Available Sizes'); ?></h4><p><?php echo esc_html($sizes); ?></p><?php endif; ?>
          <?php if (is_user_logged_in()): ?><button type="button" class="wd-btn wd-equipment-add-cart" data-item-id="<?php echo get_the_ID(); ?>" style="width:100%;text-align:center;margin-top:16px"><?php echo contenly_tr('Tambah ke Keranjang', 'Add to Cart'); ?></button><?php else: ?><a class="wd-btn" href="/contact/" style="width:100%;text-align:center;margin-top:16px"><?php echo contenly_tr('Cek Ketersediaan', 'Check Availability'); ?></a><?php endif; ?>
        </div>
      </aside>
    </div>
  </section>
<?php get_footer(); ?>
