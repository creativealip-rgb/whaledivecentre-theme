<?php
/**
 * Template Name: Tour Packages Page
 */
get_header();
$wishlist_ids = [];
if (is_user_logged_in()) {
  $wishlist_ids = get_user_meta(get_current_user_id(), '_member_wishlist', true);
  if (!is_array($wishlist_ids)) $wishlist_ids = [];
  $wishlist_ids = array_map('absint', $wishlist_ids);
}
$scope = sanitize_key(isset($_GET['scope']) ? $_GET['scope'] : '');
$destination_query = sanitize_text_field(isset($_GET['q_destination']) ? $_GET['q_destination'] : '');
$duration_query = sanitize_text_field(isset($_GET['q_duration']) ? $_GET['q_duration'] : '');
$traveler_query = sanitize_text_field(isset($_GET['q_traveler']) ? $_GET['q_traveler'] : '');
$travel_date_query = sanitize_text_field(isset($_GET['q_date']) ? $_GET['q_date'] : '');
$style_query = sanitize_key(isset($_GET['travel_style']) ? $_GET['travel_style'] : '');
$style_presets = contenly_get_trip_style_presets();
$scope_title = contenly_tr('Paket Tour Pilihan', 'Curated Tour Packages');
$scope_desc = contenly_tr('Temukan destinasi terbaik dengan itinerary yang sudah dikurasi tim Whale Dive Centre.', 'Discover the best destinations with itineraries curated by the Whale Dive Centre team.');
if ($scope === 'domestic') {
  $scope_title = contenly_tr('Paket Tour Domestik', 'Domestic Tour Packages');
  $scope_desc = contenly_tr('Kumpulan paket dalam negeri yang cocok buat short escape, family trip, atau private trip dengan flow yang rapi.', 'A collection of domestic packages that work well for short escapes, family trips, or private trips with a cleaner flow.');
} elseif ($scope === 'international') {
  $scope_title = contenly_tr('Paket Tour Internasional', 'International Tour Packages');
  $scope_desc = contenly_tr('Pilih trip luar negeri dengan itinerary yang lebih jelas, nyaman, dan lebih gampang direview sebelum booking.', 'Choose international trips with clearer itineraries, better comfort, and easier review before booking.');
}
if ($style_query && isset($style_presets[$style_query])) {
  $scope_title = $style_presets[$style_query]['label'];
  $scope_desc = $style_presets[$style_query]['desc'];
}
$active_filters = [];
if ($style_query && isset($style_presets[$style_query])) $active_filters[] = contenly_tr('Tipe liburan: ', 'Trip style: ') . $style_presets[$style_query]['label'];
if ($destination_query !== '') $active_filters[] = contenly_tr('Destinasi: ', 'Destination: ') . $destination_query;
if ($duration_query !== '') $active_filters[] = contenly_tr('Durasi: ', 'Duration: ') . $duration_query . contenly_tr(' hari', ' days');
if ($traveler_query !== '') $active_filters[] = contenly_tr('Traveler: ', 'Travellers: ') . $traveler_query;
if ($travel_date_query !== '') $active_filters[] = contenly_tr('Tanggal: ', 'Date: ') . $travel_date_query;
$reset_query = [];
if ($scope) {
  $reset_query['scope'] = $scope;
}
$reset_url = contenly_localized_url('/tour-packages/');
if (!empty($reset_query)) {
  $reset_url = add_query_arg($reset_query, $reset_url);
}
?>

<main class="site-main tours-page">
  <section class="tours-hero">
    <div class="site-container">
      <p class="tours-hero-kicker"><?php echo esc_html(contenly_tr('Paket terkurasi buat domestik & internasional', 'Curated packages for domestic & international trips')); ?></p>
      <h1><?php echo esc_html($scope_title); ?></h1>
      <p><?php echo esc_html($scope_desc); ?></p>
      <div class="tours-hero-proof">
        <span><?php echo esc_html(contenly_tr('Itinerary lebih realistis', 'More realistic itineraries')); ?></span>
        <span><?php echo esc_html(contenly_tr('Harga lebih jelas dari awal', 'Clearer pricing from the start')); ?></span>
        <span><?php echo esc_html(contenly_tr('Bisa lanjut isi form kebutuhan trip', 'Continue with the trip request form')); ?></span>
      </div>
      <form class="tours-search" method="get" action="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>">
        <?php if ($scope) : ?><input type="hidden" name="scope" value="<?php echo esc_attr($scope); ?>" /><?php endif; ?>
        <?php if ($style_query) : ?><input type="hidden" name="travel_style" value="<?php echo esc_attr($style_query); ?>" /><?php endif; ?>
        <input type="text" name="q_destination" placeholder="<?php echo esc_attr(contenly_tr('Cari destinasi atau nama tour...', 'Search destination or tour name...')); ?>" value="<?php echo esc_attr($destination_query); ?>" />
        <select name="q_duration">
          <option value=""><?php echo esc_html(contenly_tr('Semua Durasi', 'All Durations')); ?></option>
          <option value="3" <?php selected($duration_query, '3'); ?>><?php echo esc_html(contenly_tr('3 Hari 2 Malam', '3 Days 2 Nights')); ?></option>
          <option value="4" <?php selected($duration_query, '4'); ?>><?php echo esc_html(contenly_tr('4 Hari 3 Malam', '4 Days 3 Nights')); ?></option>
          <option value="5" <?php selected($duration_query, '5'); ?>><?php echo esc_html(contenly_tr('5 Hari 4 Malam', '5 Days 4 Nights')); ?></option>
        </select>
        <select name="q_traveler">
          <option value=""><?php echo esc_html(contenly_tr('Semua Traveler', 'All Travellers')); ?></option>
          <option value="1" <?php selected($traveler_query, '1'); ?>><?php echo esc_html(contenly_tr('1 Orang', '1 Traveller')); ?></option>
          <option value="2" <?php selected($traveler_query, '2'); ?>><?php echo esc_html(contenly_tr('2 Orang', '2 Travellers')); ?></option>
          <option value="3-4" <?php selected($traveler_query, '3-4'); ?>><?php echo esc_html(contenly_tr('3-4 Orang', '3-4 Travellers')); ?></option>
          <option value="5+" <?php selected($traveler_query, '5+'); ?>><?php echo esc_html(contenly_tr('5+ Orang', '5+ Travellers')); ?></option>
        </select>
        <button type="submit"><?php echo esc_html(contenly_tr('Cari', 'Search')); ?></button>
      </form>
      <?php if (!empty($active_filters)) : ?>
        <div class="tours-active-filters">
          <?php foreach ($active_filters as $filter_label) : ?>
            <span class="tours-active-chip"><?php echo esc_html($filter_label); ?></span>
          <?php endforeach; ?>
          <a href="<?php echo esc_url($reset_url); ?>" class="tours-reset-link"><?php echo esc_html(contenly_tr('Reset filter', 'Reset filters')); ?></a>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <?php
  $tours_query = new WP_Query([
    'post_type' => 'tour',
    'posts_per_page' => -1,
    'post_status' => 'publish',
  ]);
  $tours = contenly_filter_real_posts($tours_query->posts, 'tour');
  $visible_tours = [];

  foreach ($tours as $tour_post) {
    $tour_id = $tour_post->ID;
    $location = get_post_meta($tour_id, 'location', true) ?: get_post_meta($tour_id, '_tour_location', true);
    $title = get_the_title($tour_id);
    $loc_l = strtolower((string) $location);
    $title_l = strtolower((string) $title);
    $duration_value = (string) (get_post_meta($tour_id, '_tour_duration_days', true) ?: '');
    $is_domestic_tour = contenly_is_domestic_tour($tour_id);
    if ($scope === 'domestic' && !$is_domestic_tour) {
      continue;
    }
    if ($scope === 'international' && $is_domestic_tour) {
      continue;
    }
    if ($style_query && !contenly_tour_matches_style($tour_id, $style_query)) {
      continue;
    }
    if ($destination_query !== '' && strpos($title_l . ' ' . $loc_l, strtolower($destination_query)) === false) {
      continue;
    }
    if ($duration_query !== '' && $duration_value !== $duration_query) {
      continue;
    }
    if ($traveler_query !== '') {
      $min_pax_value = (int) (get_post_meta($tour_id, '_tour_min_pax', true) ?: 1);
      if ($traveler_query === '1' && $min_pax_value > 1) {
        continue;
      }
      if ($traveler_query === '2' && $min_pax_value > 2) {
        continue;
      }
      if ($traveler_query === '3-4' && $min_pax_value > 4) {
        continue;
      }
    }
    $visible_tours[] = $tour_post;
  }

  $tour_rows = [
    [
      'key' => 'domestic',
      'label' => contenly_tr('Tour Domestik', 'Domestic Tours'),
      'chips' => $style_presets,
      'tours' => array_values(array_filter($visible_tours, function($tour_post) {
        return contenly_is_domestic_tour($tour_post->ID);
      })),
    ],
    [
      'key' => 'international',
      'label' => contenly_tr('Tour Internasional', 'International Tours'),
      'chips' => $style_presets,
      'tours' => array_values(array_filter($visible_tours, function($tour_post) {
        return contenly_is_international_tour($tour_post->ID);
      })),
    ],
    [
      'key' => 'diving',
      'label' => contenly_tr('Paket Diving', 'Diving Packages'),
      'chips' => [
        'resort' => ['label' => contenly_tr('Menginap', 'Stay Package')],
        'liveaboard' => ['label' => 'Liveaboard'],
      ],
      'tours' => array_values(array_filter($visible_tours, function($tour_post) {
        return contenly_is_diving_tour($tour_post->ID);
      })),
    ],
  ];
  ?>

  <section class="tours-section tours-list-wrap tours-home-like">
    <div class="site-container">
      <div class="tours-list-head tours-list-head--stack tours-list-head--refined">
        <div class="tours-list-copy">
          <h2 class="section-title"><?php echo esc_html(contenly_tr('Pilih paket yang paling nyambung', 'Choose the package that fits best')); ?></h2>
          <p class="tours-list-subtitle"><?php echo esc_html(contenly_tr('Browse paket lebih enak karena sekarang dibagi rapi ke 3 jalur: domestik, internasional, dan diving.', 'Browsing packages is easier now because they are neatly divided into 3 tracks: domestic, international, and diving.')); ?></p>
        </div>
        <div class="tours-results-meta tours-results-meta--cards">
          <span class="tours-meta-pill tours-meta-pill--count"><strong><?php echo esc_html(count($visible_tours)); ?></strong><em><?php echo esc_html(contenly_tr('paket tampil', 'packages shown')); ?></em></span>
          <span class="tours-meta-pill"><?php echo esc_html(contenly_tr('Filter dari hero tetap kebawa ke semua row di bawah', 'Filters from the hero stay applied across all rows below')); ?></span>
        </div>
      </div>

      <?php if (!empty($visible_tours)) : ?>
        <div class="tour-row-group">
          <?php foreach ($tour_rows as $row) : ?>
            <?php if (empty($row['tours'])) continue; ?>
            <?php
            $first_chip_key = array_key_first($row['chips']);
            $first_chip_label = isset($row['chips'][$first_chip_key]['label']) ? $row['chips'][$first_chip_key]['label'] : '';
            $row_count = 0;
            foreach ($row['tours'] as $tour_item) {
              if ($row['key'] === 'diving') {
                if (contenly_get_diving_trip_mode($tour_item->ID) === $first_chip_key) {
                  $row_count++;
                }
              } else {
                if (contenly_tour_matches_style($tour_item->ID, $first_chip_key)) {
                  $row_count++;
                }
              }
            }
            ?>
            <section class="tour-row-section" data-tour-row="<?php echo esc_attr($row['key']); ?>">
              <div class="trip-style-inline-summary">
                <div class="trip-style-inline-main">
                  <div class="trip-style-inline-copy">
                    <span class="trip-style-row-label"><?php echo esc_html($row['label']); ?></span>
                    <span class="trip-style-summary-title"><?php echo esc_html($first_chip_label); ?></span>
                    <span class="trip-style-summary-meta"><?php echo esc_html($row_count); ?> <?php echo esc_html(contenly_tr('paket cocok', 'matching packages')); ?></span>
                  </div>
                  <div class="trip-style-chip-bar">
                    <?php foreach ($row['chips'] as $chip_key => $chip_data) : ?>
                      <?php
                      $chip_count = 0;
                      foreach ($row['tours'] as $tour_item) {
                        if ($row['key'] === 'diving') {
                          if (contenly_get_diving_trip_mode($tour_item->ID) === $chip_key) {
                            $chip_count++;
                          }
                        } else {
                          if (contenly_tour_matches_style($tour_item->ID, $chip_key)) {
                            $chip_count++;
                          }
                        }
                      }
                      ?>
                      <button type="button" class="trip-style-chip<?php echo $chip_key === $first_chip_key ? ' active' : ''; ?>" data-style="<?php echo esc_attr($chip_key); ?>" data-label="<?php echo esc_attr($chip_data['label']); ?>" data-count="<?php echo esc_attr($chip_count); ?>">
                        <?php echo esc_html($chip_data['label']); ?>
                      </button>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>

              <div class="tour-row-grid">
                <?php foreach ($row['tours'] as $tour_post) : ?>
                  <?php
                  $tour_id = $tour_post->ID;
                  $price = (int) (get_post_meta($tour_id, '_tour_price', true) ?: get_post_meta($tour_id, 'price', true));
                  $duration = get_post_meta($tour_id, 'duration', true) ?: get_post_meta($tour_id, '_tour_duration_days', true);
                  $location = get_post_meta($tour_id, 'location', true) ?: get_post_meta($tour_id, '_tour_location', true);
                  $title = get_the_title($tour_id);
                  $tags = $row['key'] === 'diving' ? [contenly_get_diving_trip_mode($tour_id)] : contenly_get_tour_travel_styles($tour_id);
                  $tag_attr = implode(',', array_unique($tags));
                  ?>
                  <article class="home-tour-card" data-filter-tags="<?php echo esc_attr($tag_attr); ?>">
                    <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="home-tour-card-media">
                      <?php if (has_post_thumbnail($tour_id)) : ?>
                        <?php echo get_the_post_thumbnail($tour_id, 'medium_large', ['style' => 'width:100%;height:100%;object-fit:cover;display:block;']); ?>
                      <?php else : ?>
                        <div class="tour-media-fallback"><span>Whale Dive</span></div>
                      <?php endif; ?>
                    </a>
                    <div class="home-tour-card-body">
                      <h3 class="home-tour-card-title"><a href="<?php echo esc_url(get_permalink($tour_id)); ?>"><?php echo esc_html($title); ?></a></h3>
                      <div class="home-tour-card-meta">
                        <p><span class="home-tour-card-meta-dot"></span><?php echo esc_html($location ?: contenly_tr('Destinasi', 'Destination')); ?></p>
                        <p><span class="home-tour-card-meta-dot"></span><?php echo esc_html($duration ?: '-'); ?> · Rating 4.9</p>
                      </div>
                      <div class="home-tour-card-price">
                        <span class="home-tour-card-price-label"><?php echo esc_html(contenly_tr('Mulai dari', 'Starting from')); ?></span>
                        <span class="home-tour-card-price-value">IDR <?php echo number_format($price, 0, ',', '.'); ?></span>
                      </div>
                      <div class="home-tour-card-footer">
                        <div class="home-tour-card-actions">
                          <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="home-tour-card-cta"><?php echo esc_html(contenly_tr('Lihat Detail', 'View Details')); ?></a>
                          <button type="button" class="home-tour-wishlist-btn tour-wishlist-btn" data-tour-id="<?php echo esc_attr($tour_id); ?>" data-in-wishlist="<?php echo in_array($tour_id, $wishlist_ids, true) ? '1' : '0'; ?>">
                            <?php echo in_array($tour_id, $wishlist_ids, true) ? '❤️' : '🤍'; ?>
                          </button>
                        </div>
                      </div>
                    </div>
                  </article>
                <?php endforeach; ?>
                <div class="home-tour-empty"><?php echo esc_html(contenly_tr('Belum ada paket yang cocok untuk filter ini.', 'No packages match this filter.')); ?></div>
              </div>
            </section>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p class="empty"><?php echo esc_html(contenly_tr('Belum ada paket tour tersedia. Cek lagi sebentar ya.', 'No tour packages are available right now. Please check again shortly.')); ?></p>
      <?php endif; ?>
    </div>
  </section>
</main>

<style>
.tours-hero{padding:120px 0 78px;background:linear-gradient(135deg,#355F72,#539294 60%,#E5A736);color:#fff;text-align:center}
.tours-hero-kicker{margin:0 0 10px;font-size:12px;letter-spacing:.08em;text-transform:uppercase;font-weight:800;opacity:.92}
.tours-hero h1{margin:0 0 12px;font-size:clamp(32px,4vw,48px)}
.tours-hero p{margin:0 auto 18px;max-width:760px;font-size:18px;opacity:.95;line-height:1.7}
.tours-hero-proof{margin:0 auto 22px;display:flex;justify-content:center;gap:10px;flex-wrap:wrap}
.tours-hero-proof span{display:inline-flex;align-items:center;min-height:34px;padding:0 14px;border-radius:999px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);color:#EEF5F4;font-size:12px;font-weight:700;letter-spacing:.02em}
.tours-search{max-width:1020px;margin:0 auto;display:grid;grid-template-columns:1.2fr .9fr .9fr auto;gap:10px;background:rgba(255,255,255,.15);padding:10px;border-radius:16px;box-shadow:0 16px 36px rgba(15,23,42,.12)}
.tours-search input,.tours-search select{height:50px;border:0;border-radius:12px;padding:0 14px;background:#fff;color:#0f172a}
.tours-search button{height:50px;border:0;border-radius:12px;padding:0 24px;background:#fff;color:#355F72;font-weight:800}
.tours-active-filters{display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin-top:14px}
.tours-active-chip{display:inline-flex;align-items:center;height:32px;padding:0 12px;border-radius:999px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.26);color:#fff;font-size:12px;font-weight:700}
.tours-reset-link{display:inline-flex;align-items:center;height:32px;padding:0 12px;border-radius:999px;background:#fff;color:#355F72;text-decoration:none;font-size:12px;font-weight:700}

.tours-section{padding:60px 0}
.tours-list-wrap{background:#f8fafc}
.tours-home-like{padding-top:52px}
.section-title{font-size:30px;margin:0 0 12px;color:#0f172a;text-align:center}
.tours-list-head{display:flex;justify-content:space-between;align-items:flex-end;gap:18px;flex-wrap:wrap;margin-bottom:18px}
.tours-list-head .section-title{text-align:left;margin-bottom:8px}
.tours-list-head--stack{align-items:flex-start;margin-bottom:28px}
.tours-list-head--refined{gap:22px;padding:0 2px}
.tours-list-copy{min-width:0;flex:1 1 520px}
.tours-list-subtitle{margin:0;color:#64748b;line-height:1.75;max-width:660px}
.tours-results-meta{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin:0;padding:0 2px;color:#64748b;font-size:14px}
.tours-results-meta strong{color:#0f172a}
.tours-results-meta--cards{justify-content:flex-end;align-items:center;flex:0 1 420px}
.tours-meta-pill{display:flex;align-items:center;min-height:42px;padding:0 14px;border-radius:999px;background:#F8FBFB;border:1px solid #E3EEEC;box-shadow:none;color:#64748b;font-size:12px;line-height:1.5}
.tours-meta-pill--count{gap:8px;min-width:auto;justify-content:center;background:#EEF5F4;border-color:#D8E8E8;color:#355F72}
.tours-meta-pill--count strong{font-size:18px;line-height:1;color:#0f172a}
.tours-meta-pill--count em{font-style:normal;font-weight:700;color:#355F72}
.tour-row-group{display:grid;gap:34px}
.tour-row-section{display:grid;gap:18px}
.tour-row-section:first-of-type{margin-top:0}
.trip-style-inline-summary{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin:0;padding:0 4px}
.trip-style-inline-main{display:flex;align-items:center;gap:10px;flex-wrap:wrap;min-width:0;flex:1 1 auto}
.trip-style-inline-copy{display:flex;align-items:center;gap:8px;flex-wrap:wrap;color:#334155}
.trip-style-chip-bar{display:flex;align-items:center;gap:8px;flex-wrap:wrap;min-width:0}
.trip-style-chip{padding:7px 11px;border-radius:999px;border:1px solid #cbd5e1;background:#fff;color:#334155;font-size:13px;font-weight:600;line-height:1.2;cursor:pointer;transition:.2s}
.trip-style-chip.active,.trip-style-chip:hover{background:#539294;color:#fff;border-color:#539294}
.trip-style-row-label{display:inline-flex;align-items:center;min-height:28px;padding:0 10px;border-radius:999px;background:#EEF5F4;color:#355F72;font-size:11px;font-weight:800;letter-spacing:.04em;text-transform:uppercase}
.trip-style-summary-title{font-size:14px;font-weight:700;color:#0f172a}
.trip-style-summary-meta{display:inline-flex;align-items:center;height:26px;padding:0 9px;border-radius:999px;background:#E3EEEC;color:#355F72;font-size:11px;font-weight:800}
.tour-row-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
.home-tour-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;height:100%;box-shadow:0 8px 20px rgba(15,23,42,.08);transition:transform .22s ease, box-shadow .22s ease}
.home-tour-card:hover{transform:translateY(-3px);box-shadow:0 16px 30px rgba(15,23,42,.12) !important}
.home-tour-card-media{display:block;height:158px;overflow:hidden;flex:0 0 158px;background:#DCE9E6}
.home-tour-card-media img{width:100%;height:100%;object-fit:cover;display:block}
.tour-media-fallback{height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#355F72,#539294,#E5A736);color:#fff}
.tour-media-fallback span{font-weight:700;letter-spacing:.03em}
.home-tour-card-body{padding:12px !important;display:flex;flex-direction:column;gap:6px;flex:1;min-height:0}
.home-tour-card-title{margin:0 !important;font-size:16px !important;line-height:1.3 !important;min-height:42px !important;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.home-tour-card-title a{color:#0f172a !important;text-decoration:none}
.home-tour-card-meta{display:grid;gap:0 !important;margin-bottom:0 !important;color:#64748b;font-size:12px;line-height:1.45;min-height:auto !important;align-content:start}
.home-tour-card-meta p{margin:0;min-height:16px;display:flex;align-items:center;gap:6px}
.home-tour-card-meta-dot{width:6px;height:6px;border-radius:999px;background:#B7D3D3;display:inline-block;flex:0 0 auto}
.home-tour-card-price{display:grid;gap:0}
.home-tour-card-price-label{font-size:11px;color:#64748b;margin-top:auto}
.home-tour-card-price-value{font-size:20px;line-height:1.1;font-weight:800;color:#355F72;letter-spacing:-.01em}
.home-tour-card-footer{margin-top:auto;display:grid;gap:6px}
.home-tour-card-actions{display:grid;grid-template-columns:1fr auto;gap:7px;align-items:center}
.home-tour-card-cta{display:block;text-align:center;text-decoration:none;background:linear-gradient(135deg,#539294,#539294);color:#fff;border-radius:999px;font-weight:700;font-size:12px;line-height:1.2;min-height:34px;padding:8px 11px;white-space:nowrap;box-shadow:none !important}
.home-tour-card-cta:hover{filter:saturate(1.04)}
.home-tour-wishlist-btn{height:34px;min-width:36px;border:1px solid #cbd5e1;border-radius:999px;background:#fff;cursor:pointer;font-size:14px;line-height:1;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
.home-tour-wishlist-btn:hover{transform:translateY(-1px);box-shadow:0 8px 18px rgba(15,23,42,.08);border-color:#94a3b8}
.home-tour-empty{display:none;grid-column:1 / -1;text-align:center;padding:28px;border:1px dashed #cbd5e1;border-radius:20px;background:#fff;color:#64748b;font-size:16px}
.empty{text-align:center;color:#64748b}

@media (min-width: 1025px){
  .trip-style-inline-summary{flex-wrap:nowrap}
  .trip-style-inline-main{flex-wrap:nowrap}
  .trip-style-inline-copy{flex-wrap:nowrap}
  .trip-style-chip-bar{flex-wrap:nowrap}
}
@media (max-width: 1200px){
  .tour-row-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
}
@media (max-width: 1024px){
  .tour-row-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .trip-style-inline-summary{align-items:flex-start}
}
@media (max-width: 768px){
  .tours-hero{padding:112px 0 46px}
  .tours-search{grid-template-columns:1fr}
  .tours-list-head .section-title,.section-title{font-size:24px}
  .tours-list-head--refined{gap:16px}
  .tours-results-meta--cards{flex:1 1 100%;justify-content:flex-start}
  .tours-meta-pill{width:100%;min-height:44px}
  .tours-meta-pill--count{justify-content:flex-start}
  .trip-style-inline-summary{padding:0;gap:10px}
  .trip-style-inline-main{width:100%;gap:10px;display:grid}
  .trip-style-inline-copy{width:100%;display:grid;grid-template-columns:1fr;grid-template-areas:'label' 'title' 'meta';align-items:flex-start;gap:6px}
  .trip-style-row-label{grid-area:label;width:max-content}
  .trip-style-summary-title{grid-area:title;font-size:15px;line-height:1.3}
  .trip-style-summary-meta{grid-area:meta;justify-self:start;min-height:24px;width:max-content}
  .trip-style-chip-bar{justify-content:flex-start;flex-wrap:nowrap;overflow-x:auto;padding-bottom:6px;scrollbar-width:none;width:100%;order:3}
  .trip-style-chip-bar::-webkit-scrollbar{display:none}
  .trip-style-chip{flex:0 0 auto}
  .tour-row-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
  .home-tour-card-media{height:112px;flex-basis:112px}
  .home-tour-card-body{padding:10px !important;gap:4px}
  .home-tour-card-title{min-height:auto !important;font-size:13px !important;line-height:1.26 !important}
  .home-tour-card-meta{min-height:auto !important;font-size:10px;line-height:1.35}
  .home-tour-card-meta p{min-height:auto;gap:5px}
  .home-tour-card-meta-dot{width:5px;height:5px}
  .home-tour-card-price-label{font-size:10px}
  .home-tour-card-price-value{font-size:15px;line-height:1.06}
  .home-tour-card-footer{gap:4px}
  .home-tour-card-actions{grid-template-columns:1fr auto;gap:5px}
  .home-tour-card-cta{min-height:30px;font-size:10.5px;padding:6px 8px}
  .home-tour-wishlist-btn{height:30px;min-width:30px;font-size:12px}
}
@media (max-width: 380px){
  .tour-row-grid{grid-template-columns:1fr}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const tourRows = document.querySelectorAll('[data-tour-row]');

  const activateRowFilter = (row, filterKey) => {
    if (!row) return;
    const chips = row.querySelectorAll('.trip-style-chip');
    const cards = row.querySelectorAll('.home-tour-card');
    const summaryTitle = row.querySelector('.trip-style-summary-title');
    const summaryMeta = row.querySelector('.trip-style-summary-meta');
    const emptyState = row.querySelector('.home-tour-empty');
    let visibleCount = 0;

    chips.forEach((chip) => {
      const active = chip.getAttribute('data-style') === filterKey;
      chip.classList.toggle('active', active);
      if (active) {
        if (summaryTitle) summaryTitle.textContent = chip.getAttribute('data-label') || '';
        if (summaryMeta) summaryMeta.textContent = `${chip.getAttribute('data-count') || 0} <?php echo esc_js(contenly_tr('paket cocok', 'matching packages')); ?>`;
      }
    });

    cards.forEach((card) => {
      const tags = (card.getAttribute('data-filter-tags') || '').split(',').map((item) => item.trim()).filter(Boolean);
      const show = tags.includes(filterKey);
      card.style.display = show ? 'flex' : 'none';
      card.classList.toggle('is-visible', show);
      if (show) visibleCount += 1;
    });

    if (emptyState) {
      emptyState.style.display = visibleCount ? 'none' : 'block';
    }
  };

  tourRows.forEach((row) => {
    const chips = row.querySelectorAll('.trip-style-chip');
    chips.forEach((chip) => {
      chip.addEventListener('click', () => activateRowFilter(row, chip.getAttribute('data-style')));
    });
    if (chips.length) {
      activateRowFilter(row, chips[0].getAttribute('data-style'));
    }
  });

  const wlBtns = document.querySelectorAll('.tour-wishlist-btn');
  wlBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
      const tourId = btn.getAttribute('data-tour-id');
      if (!window.contenlyBooking) { window.location.href = '/login?redirect_to=' + encodeURIComponent(window.location.pathname); return; }
      const fd = new FormData();
      fd.append('action','contenly_toggle_wishlist');
      fd.append('tour_id', tourId);
      fd.append('nonce', window.contenlyBooking.nonce || '');
      btn.disabled = true;
      try {
        const res = await fetch('/wp-admin/admin-ajax.php',{method:'POST',body:fd});
        const data = await res.json();
        if (data.success) {
          const inWl = !!data.data.in_wishlist;
          btn.setAttribute('data-in-wishlist', inWl ? '1':'0');
          btn.textContent = inWl ? '❤️' : '🤍';
        }
      } catch(e) {}
      btn.disabled = false;
    });
  });
});
</script>

<?php get_footer(); ?>