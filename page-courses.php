<?php
/**
 * Template Name: Whale Dive Courses
 */
$all_courses = get_posts([
    'post_type'   => 'wm_course',
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
$levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
$levels = is_wp_error($levels) ? [] : $levels;
$theme_uri = get_stylesheet_directory_uri();
function wdc_course_image_url($title, $theme_uri) {
    $key = strtolower($title);
    $map = [
        'underwater photography' => 'wdc-course-underwater-photography.webp',
        'photography' => 'wdc-course-underwater-photography.webp',
        'decompression' => 'wdc-course-decompression.webp',
        'deep diver' => 'wdc-course-deep-diver-real-v2.jpg',
        'deep' => 'wdc-course-deep-diver-real-v2.jpg',
        'intro to tech' => 'wdc-course-intro-tech.webp',
        'advanced nitrox' => 'wdc-course-adv-nitrox.webp',
        'nitrox' => 'wdc-course-nitrox.webp',
        'enriched air' => 'wdc-course-nitrox.webp',
        'decompression' => 'wdc-course-decompression.webp',
        'deep diver' => 'wdc-course-deep-diver-real-v2.jpg',
        'deep' => 'wdc-course-deep-diver-real-v2.jpg',
        'night' => 'wdc-course-discover-scuba.webp',
        'first aid' => 'wdc-course-decompression.webp',
        'cpr' => 'wdc-course-decompression.webp',
        'oxygen' => 'wdc-course-nitrox.webp',
        'full face' => 'wdc-course-intro-tech.webp',
        'junior' => 'wdc-course-open-water-real.webp',
        'trial' => 'wdc-course-discover-scuba.webp',
        'master scuba' => 'wdc-course-rescue-diver-real.webp',
        'instructor' => 'wdc-course-instructor-course-real.webp',
        'discover' => 'wdc-course-discover-scuba.webp',
        'advanced' => 'wdc-course-advanced-open-water-real.webp',
        'rescue' => 'wdc-course-rescue-diver-real.webp',
        'divemaster' => 'wdc-course-divemaster-real.webp',
        'instructor' => 'wdc-course-instructor-course-real.webp',
        'open water' => 'wdc-course-open-water-real.webp',
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

  <section class="wd-inner-hero wd-courses-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker"><?php echo esc_html(contenly_tr('Dukungan pelatihan selam', 'Dive training support')); ?></span><h1><?php echo esc_html(contenly_tr('Kursus yang sesuai dengan dive berikutnya, bukan hanya kalender Anda.', 'Courses that fit your next dive, not just your calendar.')); ?></h1><p><?php echo esc_html(contenly_tr('Jelajahi jalur sertifikasi, cek harga, dan dapatkan panduan crew sebelum memilih kursus berikutnya.', 'Browse certification pathways, check pricing, and get crew guidance before choosing your next course.')); ?></p><div class="wd-actions"><a class="wd-btn" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Rencana Kursus', 'Ask Course Plan')); ?></a><a class="wd-btn alt" href="#course-catalog"><?php echo esc_html(contenly_tr('Lihat Kursus', 'Browse Courses')); ?></a></div></div><aside class="wd-inner-card"><b><?php echo esc_html(contenly_tr('Dukungan pelatihan mencakup', 'Training support covers')); ?></b><ul><li><?php echo esc_html(contenly_tr('Pemeriksaan jalur sertifikasi', 'Certification pathway checks')); ?></li><li><?php echo esc_html(contenly_tr('Panduan coaching kelompok kecil', 'Small-group coaching guidance')); ?></li><li><?php echo esc_html(contenly_tr('Kesiapan gear dan jadwal', 'Gear and schedule readiness')); ?></li><li><?php echo esc_html(contenly_tr('Opsi PADI / SSI / NAUI / TDI', 'PADI / SSI / NAUI / TDI options')); ?></li></ul></aside></div></section>

  <section id="course-catalog" class="wd-section white wd-center">
    <div class="wd-shell">
      <?php
      // --- Group courses by agency (computed early for count) ---
      $agency_groups = [];
      foreach($all_courses as $course) {
          $ag = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
          $ag = is_wp_error($ag) ? [] : $ag;
          $agency_key = !empty($ag) ? $ag[0] : 'Other';
          $agency_groups[$agency_key][] = $course;
      }
      $agency_order = ['NAUI', 'TDI', 'DAN'];
      $agency_groups = array_intersect_key($agency_groups, array_flip($agency_order));
      uksort($agency_groups, function($a, $b) use ($agency_order) {
          $ka = array_search($a, $agency_order); if($ka===false) $ka=99;
          $kb = array_search($b, $agency_order); if($kb===false) $kb=99;
          return $ka - $kb;
      });
      $displayed_count = 0; foreach($agency_groups as $g) $displayed_count += count($g);
      ?>

      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Katalog kursus', 'Course catalog')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Kursus selam dari agensi terpercaya', 'Dive courses from trusted agencies')); ?></h2>
      <p class="wd-sub"><?php echo $displayed_count; ?> <?php echo contenly_tr('kursus dari NAUI, TDI, dan DAN', 'courses from NAUI, TDI, and DAN'); ?></p>
      <div id="courseFilters" class="wd-filter-bar" style="margin-bottom:8px!important;">
        <button class="wd-chip active" data-filter="all"><?php echo esc_html(contenly_tr('Semua Kursus', 'All Courses')); ?></button>
        <?php if(!empty($levels) && !is_wp_error($levels)): foreach($levels as $level): ?>
          <button class="wd-chip" data-filter="cat-<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <?php
      // Agency descriptions
      $agency_desc = [
          'NAUI' => contenly_tr('Sertifikasi selam internasional dengan fokus keselamatan dan kemandirian diver.', 'International dive certification focused on diver safety and self-reliance.'),
          'TDI' => contenly_tr('Pelatihan diving teknis dan prosedur dekompresi untuk diver berpengalaman.', 'Technical diving training and decompression procedures for experienced divers.'),
          'DAN' => contenly_tr('Pelatihan tanggap darurat, CPR, dan oksigen pertolongan pertama untuk penyelam.', 'Emergency response, CPR, and oxygen first aid training for divers.'),
      ];
      $agency_colors = ['NAUI' => '#004A98', 'TDI' => '#1B5E20', 'DAN' => '#C31C4A'];
      ?>

      <?php foreach($agency_groups as $agency_name => $agency_courses):
        $ac = $agency_colors[$agency_name] ?? '#06384d';
        $ad = $agency_desc[$agency_name] ?? '';
      ?>
      <div style="margin-bottom:56px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
          <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;border-radius:999px;background:<?php echo $ac; ?>;color:#fff;font-size:15px;font-weight:900;letter-spacing:.04em;font-family:'Plus Jakarta Sans',sans-serif;"><?php echo esc_html($agency_name); ?></span>
          <span style="flex:1;height:2px;background:linear-gradient(90deg,<?php echo $ac; ?>33,transparent);border-radius:1px;"></span>
          <span style="font-size:13px;font-weight:700;color:#5b7180;"><?php echo count($agency_courses); ?> <?php echo contenly_tr('kursus', 'courses'); ?></span>
        </div>
        <?php if($ad): ?><p style="color:#5b7180;font-size:15px;line-height:1.6;margin:0 0 22px;max-width:640px;"><?php echo esc_html($ad); ?></p><?php endif; ?>

        <div id="courseGrid" class="wd-equipment-grid wd-page-grid" style="align-items:start!important;grid-auto-rows:auto!important;">
          <?php foreach($agency_courses as $course):
            $price = get_post_meta($course->ID, '_wm_price', true);
            $duration = get_post_meta($course->ID, '_wm_duration', true);
            $max_students = get_post_meta($course->ID, '_wm_max_students', true);
            $prereqs = get_post_meta($course->ID, '_wm_prerequisites', true);
            $level_terms = wp_get_post_terms($course->ID, 'course_level', ['fields' => 'all']);
            $level_terms = is_wp_error($level_terms) ? [] : $level_terms;
            $agency_terms = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
            $agency_terms = is_wp_error($agency_terms) ? [] : $agency_terms;
            $level_slug = !empty($level_terms) ? $level_terms[0]->slug : '';
            $level_name = !empty($level_terms) ? $level_terms[0]->name : '';
            $permalink = home_url('/courses/' . $course->post_name . '/');
            $image_url = get_the_post_thumbnail_url($course->ID, 'large') ?: wdc_course_image_url($course->post_title, $theme_uri);
          ?>
          <article class="wd-equip-card wd-detail-card wd-shop-card wd-course-card" data-href="<?php echo esc_url($permalink); ?>" data-cat="cat-<?php echo esc_attr($level_slug); ?>" style="border-radius:18px!important;overflow:hidden!important;padding:0!important;background:#fff!important;box-shadow:0 14px 34px rgba(2,21,43,.07)!important;border:1px solid rgba(6,56,77,.08)!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;">
            <div class="wd-equip-visual <?php echo $image_url ? 'has-photo' : ''; ?>" data-course-level="<?php echo esc_attr($level_slug ?: 'course'); ?>" style="height:138px!important;min-height:0!important;border-radius:0!important;margin:0!important;">
              <?php if($image_url): ?><img class="wd-equip-photo" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($course->post_title); ?>" loading="lazy" onerror="this.closest('.wd-equip-visual').classList.remove('has-photo');this.remove();"><?php else: ?><span class="wd-equip-mark"><?php echo esc_html($level_name ? mb_substr($level_name, 0, 1) : 'C'); ?></span><?php endif; ?>
              <?php if($level_name): ?><span class="wd-equip-type"><?php echo esc_html($level_name); ?></span><?php endif; ?>
            </div>
            <div class="wd-equip-card-body" style="padding:14px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important;">
              <div class="wd-course-meta wd-shop-meta" style="gap:6px!important;margin:0 0 4px!important;">
                <?php if($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
                <span><?php echo esc_html($agency_name); ?></span>
              </div>
              <h3 style="font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important;"><?php echo esc_html($course->post_title); ?></h3>
              <?php if($price): ?>
              <div class="wd-equip-price" style="margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important;">
                <span class="wd-price-label" style="display:block!important;margin:0 0 4px!important;font-size:11px!important;line-height:1.2!important;color:#789!important;"><?php echo contenly_tr('Harga kursus · jadwal atas permintaan', 'Course price · schedule on request'); ?></span>
                <span class="wd-price-amount" style="display:block!important;font-size:17px!important;line-height:1!important;color:#06384d!important;font-weight:900!important;white-space:nowrap!important;">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
              </div>
              <?php endif; ?>
              <div class="wd-equip-chips" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
                <?php if($duration): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo esc_html($duration); ?></span><?php endif; ?>
                <span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo $max_students ? contenly_tr('Maks ', 'Max ') . esc_html($max_students) . contenly_tr(' diver', ' divers') : contenly_tr('Cek ketersediaan', 'Check availability'); ?></span>
                <?php if($prereqs): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo contenly_tr('Prasyarat: ', 'Prereq: '); ?><?php echo esc_html($prereqs); ?></span><?php endif; ?>
              </div>
              <div class="wd-equip-actions" style="margin-top:8px!important;padding-top:0!important;border-top:0!important;display:flex!important;gap:8px!important;flex-wrap:wrap!important;">
                <a class="wd-mini-link" style="min-height:38px!important;min-width:100px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.14)!important;color:#06384d!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url($permalink); ?>"><?php echo contenly_tr('Lihat Detail', 'View Details'); ?></a>
                <?php if($price > 0): ?>
                <a class="wd-mini-btn" onclick="event.stopPropagation();" style="min-height:38px!important;min-width:100px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#06384d!important;border:1px solid #06384d!important;color:#fff!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url(home_url('/direct-checkout/?type=course&item=' . rawurlencode($course->post_title) . '&item_id=' . $course->ID . '&price=' . $price)); ?>"><?php echo contenly_tr('Daftar', 'Enroll Now'); ?></a>
                <?php endif; ?>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo contenly_tr('Butuh saran kursus?', 'Need course advice?'); ?></span><h2><?php echo esc_html(contenly_tr('Crew bantu pilih jalur yang tepat.', 'The crew helps you choose the right path.')); ?></h2><p><?php echo contenly_tr('Ceritakan level sertifikasi, target tanggal, dan tujuan kenyamanan — kami rekomendasikan kursus yang cocok.', 'Tell us your certification level, target dates, and comfort goals — we recommend the course that fits.'); ?></p><a class="wd-btn alt" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Rencana Kursus', 'Ask About Course Plan')); ?></a></div></section>

  <?php get_footer(); ?>