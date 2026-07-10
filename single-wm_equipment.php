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
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-single-equipment-photo">.wd-single-gear-visual.has-photo{min-height:360px;padding:0;overflow:hidden}.wd-single-gear-visual.has-photo img{width:100%;height:100%;min-height:360px;object-fit:cover;display:block}.wd-single-gear-visual.has-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(3,23,45,0),rgba(3,23,45,.28));pointer-events:none}</style></head>
<body <?php body_class('whaledive-inner whaledive-equipment whaledive-single-equip'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

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

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker"><?php echo contenly_tr('Siap menyelam?', 'Ready to dive?'); ?></span><h2>Whale Dive Centre</h2><p><?php echo contenly_tr('Pelatihan menyelam, perjalanan komunitas, dukungan peralatan, dan pengalaman berwawasan laut untuk petualangan lebih aman di bawah permukaan.', 'Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.'); ?></p><a class="wd-btn alt" href="/contact/"><?php echo contenly_tr('Mulai Konsultasi', 'Start Inquiry'); ?></a></div><nav class="wd-footer-col"><h3><?php echo contenly_tr('Jelajahi', 'Explore'); ?></h3><a href="/courses/"><?php echo contenly_tr('Kursus Menyelam', 'Dive Courses'); ?></a><a href="/equipment/"><?php echo contenly_tr('Peralatan Selam', 'Scuba Equipment'); ?></a><a href="/about/"><?php echo contenly_tr('Tentang Kami', 'About Us'); ?></a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3><?php echo contenly_tr('Kursus', 'Courses'); ?></h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3><?php echo contenly_tr('Kontak', 'Contact'); ?></h3><p>Email: info@whaledivecentre.com</p><p>Telepon: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. <?php echo contenly_tr('Hak cipta dilindungi.', 'All rights reserved.'); ?></span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?><script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.wd-equipment-add-cart').forEach(function(btn){
    btn.addEventListener('click', function(){
      if (!window.wmCart || !wmCart.addToCart) { window.location.href = '/direct-checkout/'; return; }
      var original = btn.textContent;
      btn.disabled = true;
      btn.textContent = '<?php echo contenly_tr('Menambahkan...', 'Adding...'); ?>';
      wmCart.addToCart('equipment', btn.getAttribute('data-item-id'), 1, {}).then(function(data){
        if (data && data.success) { window.location.href = '/direct-checkout/'; return; }
        btn.disabled = false;
        btn.textContent = original;
      }).catch(function(){ btn.disabled = false; btn.textContent = original; });
    });
  });
});
</script>
</body></html>