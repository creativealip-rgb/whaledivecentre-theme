<?php get_header(); ?>

  <!-- HERO -->
  <section class="wd-hero wd-hero-simple"><div class="wd-shell wd-hero-focus"><div class="wd-hero-copy"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Latihan selam Jakarta & komunitas diving', 'Jakarta scuba training & dive community')); ?></span><h1><?php echo wp_kses_post(contenly_tr('Mulai Tenang.<br>Dive Pede.', 'Start Calm.<br>Dive Confident.')); ?></h1><p><?php echo esc_html(contenly_tr('Belajar, siapkan gear, dan rencanakan petualangan bawah air berikutnya bersama crew yang menjaga setiap dive tetap jelas, aman, dan peduli laut.', 'Learn, gear up, and plan your next underwater adventure with a crew that keeps every dive clear, safe, and ocean-minded.')); ?></p><div class="wd-actions"><a class="wd-btn" href="/courses/"><?php echo esc_html(contenly_tr('Lihat Kursus', 'Explore Courses')); ?></a><a class="wd-btn alt" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Crew', 'Ask the Crew')); ?></a></div><div class="wd-hero-proof"><span><b><?php echo esc_html(contenly_tr('Bersertifikat', 'Certified')); ?></b> <?php echo esc_html(contenly_tr('dipandu instruktur', 'instructor-led courses')); ?></span><span><b><?php echo esc_html(contenly_tr('Grup kecil', 'Small groups')); ?></b> <?php echo esc_html(contenly_tr('progres skill lebih tenang', 'calmer skill progression')); ?></span><span><b><?php echo esc_html(contenly_tr('Rekreasional', 'Recreational')); ?></b> <?php echo esc_html(contenly_tr('jalur dive santai', 'relaxed dive pathway')); ?></span></div></div><aside class="wd-hero-card" aria-label="Dive centre highlights"><span><?php echo esc_html(contenly_tr('Intake berikutnya', 'Next intake')); ?></span><strong>Open Water</strong><p><?php echo esc_html(contenly_tr('Kelas grup kecil, fitting gear, dan bimbingan instruktur yang tenang dari kolam ke laut.', 'Small-group classes, gear fitting, and calm instructor guidance from pool to ocean.')); ?></p><a href="/courses/"><?php echo esc_html(contenly_tr('Lihat jalur', 'View pathway')); ?></a></aside></div></section>
  <!-- WELCOME / TRUST BAR -->
  <section class="wd-trust-bar"><div class="wd-shell"><p class="wd-trust-text"><?php echo esc_html(contenly_tr('Gerbang kamu ke dunia bawah laut. Kami menggabungkan pelatihan selam profesional, peralatan berkualitas, bimbingan grup kecil, dan semangat konservasi laut.', 'Your gateway to the underwater world — professional scuba training, quality gear, small-group guidance, and a passion for marine conservation.')); ?></p><div class="wd-trust-label"><?php echo esc_html(contenly_tr('Dipercaya oleh', 'Trusted by')); ?></div><div class="wd-trust-logos" aria-label="Dive training and equipment partners"><div class="wd-trust-row"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/naui.webp" alt="NAUI" loading="lazy" decoding="async"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/tdi.webp" alt="TDI" loading="lazy" decoding="async"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/dan.webp" alt="DAN" loading="lazy" decoding="async"></div><div class="wd-trust-row wd-trust-row-5"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/sherwood.webp" alt="Sherwood Scuba" loading="lazy" decoding="async"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/zeagle.webp" alt="Zeagle" loading="lazy" decoding="async"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/waterproof.webp" alt="Waterproof" loading="lazy" decoding="async"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/shearwater.webp" alt="Shearwater Research" loading="lazy" decoding="async"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/partners/bare.webp" alt="BARE" loading="lazy" decoding="async"></div></div></div></section>

  <!-- FOCUS AREAS (moved from /about) -->
  <section class="wd-section wd-dark wd-center"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Fokus Kami', 'Our Focus')); ?></span><h2 class="wd-title"><?php echo esc_html(contenly_tr('Dari dive pertama sampai level profesional.', 'From first dive to professional level.')); ?></h2><div class="wd-safety-grid"><article><b><?php echo esc_html(contenly_tr('Pelatihan rekreasional', 'Recreational training')); ?></b><span><?php echo esc_html(contenly_tr('Membangun fondasi skill, buoyancy, buddy awareness, dan kepercayaan diri untuk diver baru.', 'Build core skills, buoyancy, buddy awareness, and confidence for new divers.')); ?></span></article><article><b><?php echo esc_html(contenly_tr('Pelatihan profesional', 'Professional training')); ?></b><span><?php echo esc_html(contenly_tr('Mengembangkan leadership, briefing, rescue awareness, dan standar kerja profesional.', 'Develop leadership, briefing, rescue awareness, and professional working standards.')); ?></span></article><article><b><?php echo esc_html(contenly_tr('Budaya teknis & keselamatan', 'Budaya teknis & keselamatan')); ?></b><span><?php echo esc_html(contenly_tr('Mendorong perencanaan konservatif, disiplin prosedur, dan keputusan yang sadar risiko.', 'Promote conservative planning, procedural discipline, and risk-aware decisions.')); ?></span></article></div></div></section>

  <!-- COURSES -->
  <section id="courses" class="wd-section wd-dark wd-center"><div class="wd-shell"><div class="wd-divider"><h2 class="wd-title"><?php echo esc_html(contenly_tr('Kursus Selam Kami', 'Our Dive Courses')); ?></h2></div><p class="wd-sub" style="margin-bottom:28px!important;"><?php echo esc_html(contenly_tr('Jalur terstruktur dari napas pertama di bawah air hingga kepemimpinan dive profesional.', 'A structured pathway from your first breath underwater to pro-level dive leadership.')); ?></p><div class="wd-filter-bar" aria-label="Course category tabs" style="margin-bottom:36px!important;">
<button class="wd-chip active" data-tab="naui" onclick="wdSwitchTab('naui')">NAUI</button>
<button class="wd-chip" data-tab="tdi" onclick="wdSwitchTab('tdi')">TDI</button>
</div>
<div class="wd-course-grid" id="wd-tab-naui" style="width:100%!important;min-width:0!important;max-width:100%!important;margin:0!important;display:grid!important;grid-auto-flow:row!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:22px!important;">
<?php
$naui_courses=[
['Open Water Scuba Diver',contenly_tr('Pemula','Beginner'),contenly_tr('3-4 hari','3-4 days'),contenly_tr('Sertifikasi pertama untuk dive aman bersama buddy.','Your first certification to dive safely with a buddy.'),'open-water-scuba-diver','wdc-course-open-water-real.png'],
['Advanced Open Water Diver',contenly_tr('Level lanjut','Next level'),contenly_tr('2-3 hari','2-3 days'),contenly_tr('Kembangkan skill dengan dive lebih dalam, navigasi, dan pengalaman khusus.','Expand your skills with deeper dives, navigation, and specialty experiences.'),'advanced-open-water-diver','wdc-course-advanced-open-water-real.png'],
['Rescue Scuba Diver',contenly_tr('Keselamatan','Safety'),contenly_tr('2-3 hari','2-3 days'),contenly_tr('Pelajari pencegahan dan penanganan darurat selam.','Learn to prevent and manage dive emergencies.'),'rescue-scuba-diver','wdc-course-rescue-diver-real.png'],
['Divemaster',contenly_tr('Jalur pro','Pro track'),contenly_tr('Bervariasi','Varies'),contenly_tr('Pimpin dive, bantu kelas, dan mulai karir profesional selam.','Lead dives, assist classes, and start your professional diving career.'),'divemaster','wdc-course-divemaster-real.png']
];
foreach($naui_courses as $e): ?><article class="wd-course-card"><div class="wd-course-photo" style="background-image:linear-gradient(135deg,rgba(232,251,255,.28),rgba(255,255,255,.42)),url('<?php echo esc_url(get_template_directory_uri().'/assets/'.$e[5]); ?>')!important;background-size:cover,contain!important;background-position:center!important;background-repeat:no-repeat!important;"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/'.$e[5]); ?>" alt="<?php echo esc_attr($e[0]); ?>" loading="eager" decoding="async"></div><div class="wd-course-body"><div class="wd-course-meta"><span><?php echo esc_html($e[1]); ?></span><span><?php echo esc_html($e[2]); ?></span></div><h3><?php echo esc_html($e[0]); ?></h3><p><?php echo esc_html($e[3]); ?></p><a href="/courses/"><?php echo esc_html(contenly_tr('Lihat Detail','View Details')); ?> →</a></div></article><?php endforeach; ?>
</div>
<div class="wd-course-grid wd-hidden" id="wd-tab-tdi" style="width:100%!important;min-width:0!important;max-width:100%!important;margin:0!important;display:none!important;grid-auto-flow:row!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:22px!important;">
<?php
$tdi_courses=[
['Intro to Tech',contenly_tr('Perkenalan','Intro'),contenly_tr('1-2 hari','1-2 days'),contenly_tr('Perkenalan diving teknis dan peralatan lanjutan.','Introduction to technical diving and advanced equipment.'),'intro-to-tech','wdc-course-intro-tech.webp'],
['Nitrox Diver',contenly_tr('Nitrox','Nitrox'),contenly_tr('1-2 hari','1-2 days'),contenly_tr('Diving dengan udara terperkaya untuk limit tanpa dekompresi lebih lama.','Dive with enriched air for longer no-decompression limits.'),'nitrox-diver','wdc-course-nitrox.webp'],
['Advanced Nitrox Diver',contenly_tr('Nitrox lanjut','Adv Nitrox'),contenly_tr('2-3 hari','2-3 days'),contenly_tr('Penggunaan nitrox tingkat lanjut untuk dive lebih dalam.','Advanced nitrox use for deeper dives.'),'advanced-nitrox-diver','wdc-course-adv-nitrox.webp'],
['Decompression Procedures',contenly_tr('Dekompresi','Decompression'),contenly_tr('2-3 hari','2-3 days'),contenly_tr('Prosedur dekompresi aman untuk dive teknis.','Safe decompression procedures for technical diving.'),'decompression-procedures-diver','wdc-course-decompression.webp']
];
foreach($tdi_courses as $e): ?><article class="wd-course-card"><div class="wd-course-photo" style="background-image:linear-gradient(135deg,rgba(232,251,255,.28),rgba(255,255,255,.42)),url('<?php echo esc_url(get_template_directory_uri().'/assets/'.$e[5]); ?>')!important;background-size:cover,contain!important;background-position:center!important;background-repeat:no-repeat!important;"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/'.$e[5]); ?>" alt="<?php echo esc_attr($e[0]); ?>" loading="eager" decoding="async"></div><div class="wd-course-body"><div class="wd-course-meta"><span><?php echo esc_html($e[1]); ?></span><span><?php echo esc_html($e[2]); ?></span></div><h3><?php echo esc_html($e[0]); ?></h3><p><?php echo esc_html($e[3]); ?></p><a href="/courses/"><?php echo esc_html(contenly_tr('Lihat Detail','View Details')); ?> →</a></div></article><?php endforeach; ?>
</div>
<script>
function wdSwitchTab(tab) {
  document.querySelectorAll('.wd-chip[data-tab]').forEach(function(b) {
    b.classList.toggle('active', b.dataset.tab === tab);
  });
  var showEl = document.getElementById(tab === 'naui' ? 'wd-tab-naui' : 'wd-tab-tdi');
  var hideEl = document.getElementById(tab === 'naui' ? 'wd-tab-tdi' : 'wd-tab-naui');
  showEl.style.setProperty('display', 'grid', 'important');
  showEl.style.setProperty('visibility', 'visible', 'important');
  showEl.style.setProperty('height', 'auto', 'important');
  showEl.style.setProperty('position', 'static', 'important');
  showEl.style.setProperty('left', 'auto', 'important');
  hideEl.style.setProperty('display', 'none', 'important');
  hideEl.style.setProperty('visibility', 'hidden', 'important');
  hideEl.style.setProperty('height', '0', 'important');
  hideEl.style.setProperty('overflow', 'hidden', 'important');
}
</script>
<div class="wd-section-cta"><a class="wd-btn alt" href="/courses/"><?php echo esc_html(contenly_tr('Lihat Semua Kursus','View All Courses')); ?> →</a></div></div></section>

  <!-- DIVE SITES HIDDEN -->
<!-- EQUIPMENT -->
  <style>
.home .wd-eq .wd-home-equip-card{transition:transform .3s ease,box-shadow .3s ease!important}
.home .wd-eq .wd-home-equip-card:hover{transform:translateY(-6px)!important;box-shadow:0 20px 50px rgba(0,0,0,.28)!important}
.home .wd-eq .wd-home-equip-card:hover .wd-home-equip-photo img{transform:scale(1.06)!important}
.home .wd-eq .wd-sub{margin-bottom:28px!important}
</style>

  <section id="equipment" class="wd-section white wd-center wd-eq"><div class="wd-shell"><div class="wd-divider"><h2 class="wd-title"><?php echo esc_html(contenly_tr('Peralatan Selam', 'Scuba Equipment')); ?></h2></div><p class="wd-sub"><?php echo esc_html(contenly_tr('Gear berkualitas untuk latihan, fun dive, dan kenyamanan bawah air yang lebih aman. Beli atau sewa melalui crew.', 'Quality gear for training, fun dives, and safer underwater comfort. Buy or rent through the crew.')); ?></p><div class="wd-equipment-grid"><?php $eq=[['Masker',contenly_tr('Visi jelas dan pas yang bisa diandalkan', 'Clear vision and reliable fit'),'wdc-equipment-mask-real.png'],['Wetsuit',contenly_tr('Kenyamanan termal untuk dive lebih lama', 'Thermal comfort for longer dives'),'wdc-equipment-wetsuit-real.png'],['BCD',contenly_tr('Kontrol buoyancy dan dukungan trim', 'Buoyancy control and trim support'),'wdc-equipment-bcd-real.png'],['Regulator',contenly_tr('Pernapasan lancar dan pengiriman udara yang aman', 'Smooth breathing and safe air delivery'),'wdc-equipment-regulator-real.png']]; foreach($eq as $e): ?><article class="wd-course-card wd-home-equip-card"><div class="wd-course-photo wd-home-equip-photo" style="background-image:linear-gradient(135deg,rgba(232,251,255,.28),rgba(255,255,255,.42)),url('<?php echo esc_url(get_template_directory_uri() . '/assets/' . $e[2]); ?>')!important;background-size:cover,contain!important;background-position:center!important;background-repeat:no-repeat!important;"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/' . $e[2]); ?>" alt="<?php echo esc_attr($e[0]); ?>" loading="eager" decoding="async"></div><div class="wd-course-body wd-home-equip-body"><div class="wd-course-meta"><span><?php echo esc_html($e[0]); ?></span><?php echo '<span>' . contenly_tr('Peralatan', 'Gear') . '</span>'; ?></div><h3><?php echo esc_html($e[0]); ?></h3><p><?php echo esc_html($e[1]); ?></p><a href="/equipment/"><?php echo esc_html(contenly_tr('Lihat Detail', 'View Details')); ?></a></div></article><?php endforeach; ?></div><div class="wd-section-cta"><a class="wd-btn alt" href="/equipment/"><?php echo esc_html(contenly_tr('Lihat Semua Peralatan', 'View All Equipment')); ?></a></div></div></section>

  <!-- SOCIAL PROOF (NEW) -->
  <section class="wd-section wd-proof wd-center"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Dipercaya Diver', 'Trusted by Divers')); ?></span><h2 class="wd-title"><?php echo esc_html(contenly_tr('Kata Komunitas Kami', 'What Our Community Says')); ?></h2><div class="wd-reviews-grid">
    <article class="wd-review-card"><div class="wd-review-stars">★★★★★</div><p>"Kursus open water pertamaku terasa aman dan tenang berkat crew-nya. Grup kecil, instruktur sabar, dan kondisi gear sangat bagus."</p><div class="wd-review-author"><b>Sarah M.</b><span>Open Water Diver</span></div></article>
    <article class="wd-review-card"><div class="wd-review-stars">★★★★★</div><p>"Pelatihan serius dengan crew Jakarta yang tenang. Jalur Divemaster-ku terasa terstruktur, jujur, dan fokus pada kepemimpinan dive yang nyata."</p><div class="wd-review-author"><b>Marco R.</b><span>PADI Divemaster</span></div></article>
    <article class="wd-review-card"><div class="wd-review-stars">★★★★★</div><p>"Beli masker dan fins pertamaku di sini. Crew-nya bantu cari ukuran yang pas sebelum aku masuk ke air. Pelayanan oke banget."</p><div class="wd-review-author"><b>Ayu P.</b><span>Diver Aktif</span></div></article>
  </div></div></section>

  <!-- ARTICLES -->
  <?php
  $wd_latest_posts = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'ignore_sticky_posts' => true,
  ]);
  $wd_fallback_articles = [
    ['Unggulan', 'Cara Mempersiapkan Kursus Open Water Pertamamu', 'Apa yang perlu dipersiapkan sebelum sesi kolam, dive laut, fitting gear, dan kebiasaan yang bikin diver baru merasa tenang di bawah air.', '/blog/', 'wdc-course-open-water-real.png'],
    ['Panduan Gear', 'Tips fitting masker sebelum beli scuba mask pertama', 'Cek sederhana untuk mencegah kebocoran, fogging, dan bekas tekanan sebelum sesi laut pertamamu.', '/tips-fitting-masker-sebelum-beli-scuba-mask-pertama/', 'wdc-equipment-mask-real.png'],
    ['Keselamatan', 'Mengapa naik perlahan dan kontrol buoyancy itu penting', 'Kebiasaan buoyancy sederhana yang bikin setiap naik lebih tenang, aman, dan mudah untuk diver baru.', '/mengapa-naik-perlahan-dan-kontrol-buoyancy-itu-penting/', 'wdc-course-rescue-diver-real.png'],
    ['Komunitas', 'Membangun kebiasaan dive lebih baik bersama grup kecil', 'Bagaimana grup latihan kecil membantu diver membangun kepercayaan diri, awareness, dan skill buddy yang lebih kuat.', '/membangun-kebiasaan-dive-lebih-baik-bersama-grup-kecil/', 'wdc-course-divemaster-real.png'],
  ];
  $wd_article_fallback_images = ['wdc-course-open-water-real.png', 'wdc-equipment-mask-real.png', 'wdc-course-rescue-diver-real.png', 'wdc-course-divemaster-real.png'];
  ?>
  <section id="articles" class="wd-section wd-articles"><div class="wd-shell"><div class="wd-article-head"><div><span class="wd-kicker"><?php echo esc_html(contenly_tr('Artikel Pilihan', 'Featured Article')); ?></span><h2 class="wd-title"><?php echo esc_html(contenly_tr('Cerita Dive & Catatan Laut', 'Dive Stories & Ocean Notes')); ?></h2><p class="wd-sub"><?php echo esc_html(contenly_tr('Bacaan pilihan untuk diver baru, pembeli gear, dan anggota komunitas yang peduli laut.', 'Curated reads for new divers, gear buyers, and ocean-minded community members.')); ?></p></div><a class="wd-btn" href="/blog/"><?php echo esc_html(contenly_tr('Baca Blog', 'Read Blog')); ?></a></div><div class="wd-article-grid wd-article-grid-dynamic">
  <?php if ($wd_latest_posts->have_posts()) : $wd_article_index = 0; while ($wd_latest_posts->have_posts()) : $wd_latest_posts->the_post();
    $wd_article_index++;
    $wd_article_url = get_permalink();
    $wd_article_title = get_the_title();
    $wd_article_label = $wd_article_index === 1 ? 'Unggulan' : (get_the_category()[0]->name ?? 'Artikel');
    $wd_article_excerpt = wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), $wd_article_index === 1 ? 24 : 18, '...');
    $wd_article_image = get_the_post_thumbnail_url(get_the_ID(), $wd_article_index === 1 ? 'large' : 'medium_large');
    if (!$wd_article_image) {
      $wd_article_image = get_template_directory_uri() . '/assets/' . $wd_article_fallback_images[min($wd_article_index - 1, count($wd_article_fallback_images) - 1)];
    }
    if ($wd_article_index === 1) : ?>
      <article class="wd-featured-card wd-featured-card-image" style="--wd-article-img:url('<?php echo esc_url($wd_article_image); ?>')"><span><?php echo esc_html($wd_article_label); ?></span><h3><?php echo esc_html($wd_article_title); ?></h3><p><?php echo esc_html($wd_article_excerpt); ?></p><a href="<?php echo esc_url($wd_article_url); ?>">Baca Artikel</a></article>
    <?php else : ?>
      <article class="wd-mini-article wd-mini-article-image"><a class="wd-mini-article-thumb" href="<?php echo esc_url($wd_article_url); ?>"><img src="<?php echo esc_url($wd_article_image); ?>" alt="<?php echo esc_attr($wd_article_title); ?>" loading="lazy"></a><b><?php echo esc_html($wd_article_label); ?></b><h3><a href="<?php echo esc_url($wd_article_url); ?>"><?php echo esc_html($wd_article_title); ?></a></h3><p><?php echo esc_html($wd_article_excerpt); ?></p><a class="wd-article-link" href="<?php echo esc_url($wd_article_url); ?>">Baca Artikel</a></article>
    <?php endif; endwhile; wp_reset_postdata(); else : foreach ($wd_fallback_articles as $wd_article_index => $wd_article) :
      $wd_article_image = get_template_directory_uri() . '/assets/' . $wd_article[4];
      if ($wd_article_index === 0) : ?>
        <article class="wd-featured-card wd-featured-card-image" style="--wd-article-img:url('<?php echo esc_url($wd_article_image); ?>')"><span><?php echo esc_html($wd_article[0]); ?></span><h3><?php echo esc_html($wd_article[1]); ?></h3><p><?php echo esc_html($wd_article[2]); ?></p><a href="<?php echo esc_url($wd_article[3]); ?>">Baca Artikel</a></article>
      <?php else : ?>
        <article class="wd-mini-article wd-mini-article-image"><a class="wd-mini-article-thumb" href="<?php echo esc_url($wd_article[3]); ?>"><img src="<?php echo esc_url($wd_article_image); ?>" alt="<?php echo esc_attr($wd_article[1]); ?>" loading="lazy"></a><b><?php echo esc_html($wd_article[0]); ?></b><h3><a href="<?php echo esc_url($wd_article[3]); ?>"><?php echo esc_html($wd_article[1]); ?></a></h3><p><?php echo esc_html($wd_article[2]); ?></p><a class="wd-article-link" href="<?php echo esc_url($wd_article[3]); ?>">Baca Artikel</a></article>
      <?php endif; endforeach; endif; ?>
  </div></div></section>

  <!-- MEMBERSHIP CTA -->
  <section id="membership" class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker"><?php echo esc_html(contenly_tr('Portal Member', 'Member Portal')); ?></span><h2 class="wd-title"><?php echo esc_html(contenly_tr('Gabung Komunitas Whale Dive', 'Join the Whale Dive Community')); ?></h2><p class="wd-sub"><?php echo esc_html(contenly_tr('Lacak kursus, kelola sertifikasi, beli peralatan, dan terhubung dengan crew — semua dari dashboard member.', 'Track your courses, manage certifications, purchase equipment, and connect with the crew — all from your member dashboard.')); ?></p><div class="wd-trust-row"><div><b><?php echo esc_html(contenly_tr('Lacak Kursus', 'Course tracking')); ?></b><span><?php echo esc_html(contenly_tr('Dari pendaftaran sampai sertifikasi', 'Enrollment to certification')); ?></span></div><div><b><?php echo esc_html(contenly_tr('Toko Peralatan', 'Equipment shop')); ?></b><span><?php echo esc_html(contenly_tr('Beli atau sewa gear online', 'Buy or rent gear online')); ?></span></div><div><b><?php echo esc_html(contenly_tr('Portofolio Sertifikasi', 'Cert portfolio')); ?></b><span><?php echo esc_html(contenly_tr('Semua kartu dive di satu tempat', 'All your dive cards in one place')); ?></span></div></div><a class="wd-btn alt" href="/member-register/"><?php echo esc_html(contenly_tr('Buat Akun Gratis', 'Create Free Account')); ?></a></div></section>
<?php get_footer(); ?>
