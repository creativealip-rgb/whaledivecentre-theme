<?php
/**
 * Single Equipment — Whale Dive Centre (admin data-driven)
 */
while (have_posts()) : the_post();
$equip_id = get_the_ID();
$price = get_post_meta($equip_id, '_wm_price', true);
$stock = get_post_meta($equip_id, '_wm_stock', true);
$sizes = get_post_meta($equip_id, '_wm_sizes', true);
$fit_note = trim((string) get_post_meta($equip_id, '_wdc_equipment_fit', true));
$cta_label = trim((string) get_post_meta($equip_id, '_wdc_equip_cta_label', true));
if ($cta_label === '') {
  $cta_label = contenly_tr('Ajukan Beli', 'Request Purchase');
}
$category = wp_get_post_terms($equip_id, 'equipment_category');
$brand = wp_get_post_terms($equip_id, 'equipment_brand');
$cat_name = (!is_wp_error($category) && !empty($category)) ? $category[0]->name : '';
$cat_slug = (!is_wp_error($category) && !empty($category)) ? $category[0]->slug : '';
$brand_name = (!is_wp_error($brand) && !empty($brand)) ? $brand[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
if (!function_exists('wdc_single_equipment_image_url')) {
    function wdc_single_equipment_image_url($title, $cat_slug, $theme_uri) {
        $key = strtolower($title . ' ' . $cat_slug);
        $map = array(
            'mask' => 'wdc-equipment-mask-real.webp',
            'fin' => 'wdc-equipment-fins-real.webp',
            'bcd' => 'wdc-equipment-bcd-real.webp',
            'regulator' => 'wdc-equipment-regulator-real.webp',
            'computer' => 'wdc-equipment-dive-computer-real.webp',
            'wetsuit' => 'wdc-equipment-wetsuit-real.webp',
        );
        foreach ($map as $needle => $file) {
            if (strpos($key, $needle) !== false) {
                $path = get_stylesheet_directory() . '/assets/' . $file;
                if (file_exists($path)) {
                    return $theme_uri . '/assets/' . $file;
                }
            }
        }
        $fallback = array(
            'mask' => 'wdc-mask.webp',
            'fin' => 'wdc-fins.webp',
            'bcd' => 'wdc-bcd.webp',
            'regulator' => 'wdc-regulators.webp',
            'computer' => 'wdc-dive-computer.webp',
            'wetsuit' => 'wdc-wetsuit.webp',
        );
        foreach ($fallback as $needle => $file) {
            if (strpos($key, $needle) !== false) {
                return $theme_uri . '/assets/' . $file;
            }
        }
        return '';
    }
}
$equipment_image_url = get_the_post_thumbnail_url($equip_id, 'full');
if (!$equipment_image_url) {
    $equipment_image_url = wdc_single_equipment_image_url(get_the_title(), $cat_slug, $theme_uri);
}
$equipment_excerpt = trim(wp_strip_all_tags(get_the_excerpt()));
$action_url = wdc_member_action_url('equipment', $equip_id, get_the_title());

$service_points = [];
for ($i = 1; $i <= 3; $i++) {
  $title = trim((string) get_post_meta($equip_id, '_wdc_equip_point_' . $i . '_title', true));
  $text = trim((string) get_post_meta($equip_id, '_wdc_equip_point_' . $i . '_text', true));
  if ($title !== '' || $text !== '') {
    $service_points[] = ['title' => $title, 'text' => $text];
  }
}
if (!$service_points) {
  $service_points = [
    [
      'title' => contenly_tr('Panduan ukuran', 'Fit guidance'),
      'text' => $fit_note !== '' ? $fit_note : contenly_tr('Tanyakan ke tim tentang seal mask, ukuran BCD, perlindungan exposure, dan kenyamanan sebelum membeli.', 'Ask the crew about mask seal, BCD size, exposure protection, and comfort before buying.'),
    ],
    [
      'title' => contenly_tr('Siap untuk pelatihan', 'Training-ready setup'),
      'text' => contenly_tr('Direkomendasikan untuk sesi kursus, fun dive, dan membangun kebiasaan lebih aman.', 'Recommended for course sessions, fun dives, and safer habit-building.'),
    ],
    [
      'title' => contenly_tr('Cek ketersediaan', 'Availability check'),
      'text' => contenly_tr('Stok dan ukuran bisa berubah; konfirmasi sebelum checkout atau pengambilan.', 'Stock and sizing can change; confirm before checkout or pickup.'),
    ],
  ];
}
endwhile;
rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-single-equipment-photo">.wd-single-gear-visual{position:relative;border-radius:24px;overflow:hidden;background:linear-gradient(145deg,rgba(255,255,255,.14),rgba(255,255,255,.06));border:1px solid rgba(255,255,255,.18)}.wd-single-gear-visual.has-photo{min-height:320px;padding:0}.wd-single-gear-visual.has-photo img{width:100%;height:100%;min-height:320px;max-height:420px;object-fit:contain;object-position:center;display:block;background:rgba(255,255,255,.08)}.wd-single-gear-visual.has-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(3,23,45,0),rgba(3,23,45,.18));pointer-events:none}</style></head>
<body <?php body_class('whaledive-inner whaledive-equipment whaledive-single-equip single single-wm_equipment'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-compact-hero wd-equipment-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo contenly_tr('Beranda', 'Home'); ?></a> <span>/</span> <a href="<?php echo esc_url(home_url('/equipment/')); ?>"><?php echo contenly_tr('Peralatan', 'Equipment'); ?></a> <span>/</span> <?php the_title(); ?></div>
        <?php if ($brand_name) : ?><span class="wd-kicker"><?php echo esc_html($brand_name); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if ($equipment_excerpt) : ?><p><?php echo esc_html($equipment_excerpt); ?></p><?php endif; ?>
        <div class="wd-detail-meta">
          <?php if ($cat_name) : ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
          <?php if ($brand_name) : ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
          <?php if ($price !== '' && $price !== null) : ?><span class="wd-agency-badge">Rp <?php echo number_format((float) $price, 0, ',', '.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="<?php echo esc_url($action_url); ?>"><?php echo esc_html($cta_label); ?></a>
          <a class="wd-btn alt" href="<?php echo esc_url(home_url('/equipment/')); ?>"><?php echo contenly_tr('Semua Peralatan', 'All Equipment'); ?></a>
        </div>
      </div>
      <aside>
        <?php if ($equipment_image_url) : ?>
        <div class="wd-single-gear-visual has-photo" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>">
          <img src="<?php echo esc_url($equipment_image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
        </div>
        <?php else : ?>
        <div class="wd-single-gear-visual" data-cat="<?php echo esc_attr($cat_slug ?: 'gear'); ?>">
          <span><?php echo esc_html($cat_name ?: contenly_tr('Peralatan Selam', 'Dive Gear')); ?></span>
          <b><?php echo esc_html($brand_name ?: 'Whale Dive Centre'); ?></b>
          <small><?php echo contenly_tr('Foto produk akan muncul di sini saat tersedia.', 'Product photography will appear here when available.'); ?></small>
        </div>
        <?php endif; ?>
      </aside>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while (have_posts()) : the_post(); ?>
          <?php
          $raw_content = get_the_content();
          $plain_content = trim(wp_strip_all_tags($raw_content));
          $is_duplicate = $plain_content !== '' && $equipment_excerpt !== '' && $plain_content === $equipment_excerpt;
          if ($plain_content !== '' && !$is_duplicate) :
            the_content();
          else :
          ?>
            <span class="wd-kicker"><?php echo contenly_tr('Tentang Peralatan Ini', 'About this gear'); ?></span>
            <h2 class="wd-title"><?php the_title(); ?></h2>
            <?php if ($equipment_excerpt) : ?>
              <p><?php echo esc_html($equipment_excerpt); ?></p>
            <?php elseif ($fit_note) : ?>
              <p><?php echo esc_html($fit_note); ?></p>
            <?php else : ?>
              <p><?php echo contenly_tr('Detail produk akan ditambahkan admin. Hubungi crew untuk ukuran, stok, dan opsi sewa.', 'Product details will be added by admin. Contact the crew for sizing, stock, and rental options.'); ?></p>
            <?php endif; ?>
          <?php endif; endwhile; ?>

        <?php if ($service_points) : ?>
        <div class="wd-gear-service-grid">
          <?php foreach ($service_points as $point) : ?>
            <article>
              <?php if (!empty($point['title'])) : ?><b><?php echo esc_html($point['title']); ?></b><?php endif; ?>
              <?php if (!empty($point['text'])) : ?><span><?php echo esc_html($point['text']); ?></span><?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if ($price !== '' && $price !== null) : ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label"><?php echo contenly_tr('Harga mulai', 'Starting price'); ?></span>
            <span class="wd-price-amount">Rp <?php echo number_format((float) $price, 0, ',', '.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if ($sizes) : ?><h4><?php echo contenly_tr('Ukuran Tersedia', 'Available Sizes'); ?></h4><p><?php echo esc_html($sizes); ?></p><?php endif; ?>
          <?php if ($stock !== '' && $stock !== null) : ?><p style="margin-top:8px;font-size:13px;color:#5f7180;"><?php echo esc_html(contenly_tr('Stok', 'Stock')); ?>: <?php echo esc_html($stock); ?></p><?php endif; ?>
          <a class="wd-btn" href="<?php echo esc_url($action_url); ?>" style="width:100%;text-align:center;margin-top:16px"><?php echo esc_html($cta_label); ?></a>
          <p class="wd-sidebar-note" style="margin-top:12px;font-size:13px;color:#5f7180;"><?php echo contenly_tr('Login member dulu. Crew konfirmasi size/stok setelah request masuk.', 'Member login required. Crew confirms size/stock after the request lands.'); ?></p>
        </div>
      </aside>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Siap saat kamu siap', 'Ready when you are')); ?></span><h2><?php echo esc_html(contenly_tr('Ajukan lewat akun member.', 'Request through your member account.')); ?></h2><p><?php echo esc_html(contenly_tr('Ajukan gear dari dashboard. Crew follow-up size/stok setelah request masuk — tanpa chat WA di halaman publik.', 'Request gear from your dashboard. Crew follows up on size/stock after the request lands — no public WhatsApp CTA.')); ?></p><a class="wd-btn alt" href="<?php echo esc_url($action_url); ?>"><?php echo esc_html($cta_label); ?></a></div></section>
  <?php contenly_render_public_footer(); ?>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body></html>
