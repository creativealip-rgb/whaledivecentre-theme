<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$term = get_queried_object();
$tour_query = new WP_Query([
    'post_type' => 'tour',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => [[
        'taxonomy' => 'travel_category',
        'field' => 'term_id',
        'terms' => $term ? (int) $term->term_id : 0,
    ]],
]);
$tours = contenly_filter_real_posts($tour_query->posts, 'tour');
$destination_terms = contenly_get_real_destination_terms();
?>

<main class="site-main tours-page">
  <section class="tours-hero destination-hero">
    <div class="site-container">
      <p class="destination-badge">Destinasi Pilihan</p>
      <h1><?php echo esc_html($term ? $term->name : 'Destinasi'); ?></h1>
      <p>
        <?php if (!empty($tours)) : ?>
          Ada <?php echo esc_html(count($tours)); ?> paket aktif untuk destinasi ini. Cocok buat lu yang mau itinerary rapi, harga jelas, dan flow booking yang gampang.
        <?php else : ?>
          Belum ada paket aktif untuk destinasi ini. Coba cek destinasi lain atau isi form kebutuhan trip buat request custom.
        <?php endif; ?>
      </p>
      <div class="destination-hero-actions">
        <a href="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>" class="destination-hero-btn primary">Lihat Semua Paket</a>
        <a href="<?php echo esc_url(contenly_localized_url('/contact/#contact-form-start')); ?>" class="destination-hero-btn secondary">Isi Form Trip</a>
      </div>
    </div>
  </section>

  <section class="tours-section destination-nav-wrap">
    <div class="site-container">
      <div class="destination-nav-scroll">
        <?php foreach ($destination_terms as $destination_term) : ?>
          <a href="<?php echo esc_url(get_term_link($destination_term)); ?>"
             class="destination-nav-chip <?php echo ($term && (int) $destination_term->term_id === (int) $term->term_id) ? 'active' : ''; ?>">
            <span><?php echo esc_html($destination_term->name); ?></span>
            <small><?php echo esc_html($destination_term->real_count ?? 0); ?> paket</small>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tours-section tours-list-wrap">
    <div class="site-container">
      <div class="destination-section-head">
        <h2 class="section-title">Paket untuk <?php echo esc_html($term ? $term->name : 'Destinasi Ini'); ?></h2>
        <p class="destination-subtitle">Pilih paket yang paling cocok, lalu lanjut ke detail booking kalau udah nemu yang pas.</p>
      </div>

      <div class="tours-grid">
        <?php if (!empty($tours)) : ?>
          <?php foreach ($tours as $tour_post) :
            $tour_id = $tour_post->ID;
            $price = (int) (get_post_meta($tour_id, '_tour_price', true) ?: get_post_meta($tour_id, 'price', true));
            $duration = get_post_meta($tour_id, 'duration', true) ?: get_post_meta($tour_id, '_tour_duration_days', true);
            $location = get_post_meta($tour_id, 'location', true) ?: get_post_meta($tour_id, '_tour_location', true);
            $excerpt = get_post_field('post_excerpt', $tour_id) ?: wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $tour_id)), 18, '...');
          ?>
            <article class="tour-card destination-tour-card">
              <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="tour-media">
                <?php if (has_post_thumbnail($tour_id)) :
                  echo get_the_post_thumbnail($tour_id, 'medium_large', ['style' => 'width:100%;height:100%;object-fit:cover;']);
                else : ?>
                  <div class="tour-media-fallback"><span>Whale Dive</span></div>
                <?php endif; ?>
              </a>
              <div class="tour-body">
                <h3><a href="<?php echo esc_url(get_permalink($tour_id)); ?>"><?php echo esc_html(get_the_title($tour_id)); ?></a></h3>
                <p class="tour-meta"><span class="meta-dot"></span><?php echo esc_html($location ?: 'Destinasi'); ?></p>
                <p class="tour-duration"><span class="meta-dot"></span><?php echo esc_html($duration ?: '-'); ?> · Rating 4.9</p>
                <p class="destination-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                <div class="tour-price-label">Mulai dari</div>
                <div class="tour-price">IDR <?php echo number_format($price, 0, ',', '.'); ?></div>
                <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="tour-btn" style="margin-top:12px;">Lihat Detail</a>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="destination-empty-state">
            <h3>Belum ada paket aktif di destinasi ini</h3>
            <p>Kalau lu mau, tetap bisa isi form kebutuhan trip untuk request custom itinerary sesuai budget dan tanggal berangkat.</p>
            <div class="destination-hero-actions">
              <a href="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>" class="destination-hero-btn primary">Lihat Destinasi Lain</a>
              <a href="<?php echo esc_url(contenly_localized_url('/contact/#contact-form-start')); ?>" class="destination-hero-btn secondary">Isi Form Custom Trip</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<style>
.tours-hero.destination-hero{
  padding:120px 0 72px;
  background:linear-gradient(135deg,#355F72,#539294 60%,#E5A736);
  color:#fff;
  text-align:center;
}
.destination-badge{
  display:inline-flex;
  padding:8px 14px;
  border-radius:999px;
  background:rgba(255,255,255,.15);
  border:1px solid rgba(255,255,255,.2);
  margin-bottom:14px;
  font-weight:700;
  letter-spacing:.02em;
}
.destination-hero h1{margin:0 0 12px;font-size:clamp(34px,4.8vw,54px)}
.destination-hero p{margin:0 auto;max-width:760px;font-size:18px;line-height:1.7;opacity:.95}
.destination-hero-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:26px}
.destination-hero-btn{display:inline-flex;align-items:center;justify-content:center;padding:13px 22px;border-radius:999px;text-decoration:none;font-weight:700;transition:.2s}
.destination-hero-btn.primary{background:#fff;color:#355F72}
.destination-hero-btn.secondary{border:1px solid rgba(255,255,255,.35);color:#fff;background:rgba(255,255,255,.08)}
.destination-nav-wrap{padding:24px 0;background:#fff;border-bottom:1px solid #e2e8f0}
.destination-nav-scroll{display:flex;gap:12px;overflow:auto;padding-bottom:6px}
.destination-nav-chip{min-width:140px;padding:14px 16px;border-radius:18px;border:1px solid #DCE9E6;background:#f8fbff;text-decoration:none;color:#0f172a;display:flex;flex-direction:column;gap:4px;flex:0 0 auto}
.destination-nav-chip small{color:#64748b}
.destination-nav-chip.active{background:#539294;color:#fff;border-color:#539294;box-shadow:0 10px 24px rgba(83,146,148,.25)}
.destination-nav-chip.active small{color:rgba(255,255,255,.88)}
.destination-section-head{margin-bottom:24px;text-align:center}
.destination-subtitle{margin:8px auto 0;max-width:760px;color:#64748b;line-height:1.7}
.destination-card-excerpt{margin:4px 0 0;color:#475569;line-height:1.7;font-size:14px}
.destination-empty-state{grid-column:1/-1;background:#fff;border:1px dashed #cbd5e1;border-radius:20px;padding:40px;text-align:center}
.destination-empty-state h3{margin:0 0 10px;color:#0f172a}
.destination-empty-state p{margin:0 auto;max-width:640px;color:#64748b;line-height:1.7}
.tours-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
.tour-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;height:100%;box-shadow:0 8px 20px rgba(15,23,42,.08);transition:transform .22s ease,box-shadow .22s ease}
.tour-card:hover{transform:translateY(-3px);box-shadow:0 16px 30px rgba(15,23,42,.12)}
.tour-media{display:block;height:190px;overflow:hidden;flex:0 0 190px}
.tour-media img{width:100%;height:100%;object-fit:cover}
.tour-media-fallback{height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#355F72,#539294,#E5A736);color:#fff}
.tour-media-fallback span{font-weight:700;letter-spacing:.03em}
.tour-body{padding:16px;display:flex;flex-direction:column;gap:8px;flex:1}
.tour-body h3{margin:0;font-size:20px;line-height:1.35}
.tour-body h3 a{text-decoration:none;color:#0f172a}
.tour-meta,.tour-duration{margin:0;color:#64748b;font-size:13px;display:flex;align-items:center;gap:7px}
.meta-dot{width:7px;height:7px;border-radius:999px;background:#B7D3D3;display:inline-block}
.tour-price-label{font-size:12px;color:#64748b;margin-top:auto}
.tour-price{font-size:24px;font-weight:800;color:#355F72;line-height:1.2}
.tour-btn{display:block;text-align:center;text-decoration:none;background:linear-gradient(135deg,#355F72,#539294);color:#fff;padding:10px 12px;border-radius:999px;font-weight:700;font-size:14px}
@media (max-width: 1200px){.tours-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media (max-width: 992px){.tours-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width: 768px){
  .destination-hero{padding:52px 0 !important}
  .destination-hero p{font-size:16px}
  .tours-grid{grid-template-columns:1fr}
  .destination-nav-chip{min-width:132px}
}
</style>

<?php get_footer(); ?>
