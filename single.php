<?php
/**
 * Single Post Template — Whale Dive Centre
 */
$theme_uri = get_stylesheet_directory_uri();
$wd_single_fallback_image = function($cat_name = '') use ($theme_uri) { $label = strtolower($cat_name . ' ' . get_the_title()); if (strpos($label,'gear')!==false || strpos($label,'mask')!==false) return $theme_uri.'/assets/wdc-equipment-mask-real.png'; if (strpos($label,'fin')!==false) return $theme_uri.'/assets/wdc-equipment-fins-real.png'; if (strpos($label,'bcd')!==false) return $theme_uri.'/assets/wdc-equipment-bcd-real.png'; if (strpos($label,'safety')!==false || strpos($label,'buddy')!==false || strpos($label,'rescue')!==false) return $theme_uri.'/assets/wdc-course-rescue-diver-real.png'; if (strpos($label,'advanced')!==false) return $theme_uri.'/assets/wdc-course-advanced-open-water-real.png'; if (strpos($label,'conservation')!==false || strpos($label,'reef')!==false) return $theme_uri.'/assets/wdc-home-hero-diving-clean3.webp'; return $theme_uri.'/assets/wdc-course-open-water-real.png'; };
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-single'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <?php while (have_posts()) : the_post();
    $cats = get_the_category();
    $cat_name = !empty($cats) ? $cats[0]->name : 'Article';
    $word_count = str_word_count(wp_strip_all_tags(get_post_field('post_content', get_the_ID())));
    $read_min = max(1, (int) ceil($word_count / 220));
    $other_posts = get_posts(['post_type'=>'post','post_status'=>'publish','posts_per_page'=>4,'post__not_in'=>[get_the_ID()],'orderby'=>'date','order'=>'DESC']);
  ?>

  <section class="wd-story-page">
    <div class="wd-shell wd-story-shell">
      <div class="wd-story-grid">
        <article class="wd-story-article">
          <header class="wd-story-head">
            <div class="wd-story-meta-row"><span><?php echo get_the_date('d M Y'); ?></span><span>·</span><span>Whale Dive Centre Team</span><span>·</span><span><?php echo $read_min; ?> <?php echo contenly_tr('menit baca', 'min read'); ?></span></div>
            <h1><?php the_title(); ?></h1>
            <div class="wd-story-cats"><?php foreach($cats as $cat){ echo '<span>'.esc_html($cat->name).'</span>'; } ?></div>
            <?php if (has_excerpt()) : ?><p class="wd-story-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
          </header>
          <figure class="wd-story-cover"><?php if (has_post_thumbnail()) { the_post_thumbnail('large'); } else { echo '<img src="' . esc_url($wd_single_fallback_image($cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></figure>
          <div class="wd-story-body">
            <div class="wd-story-content"><?php the_content(); ?></div>
            <?php if ($word_count < 80) : ?>
            <div class="wd-story-checklist">
              <h3><?php echo contenly_tr('Checklist cepat', 'Quick checklist'); ?></h3>
              <ul>
                <li><?php echo contenly_tr('Cek ukuran dan kenyamanan sebelum memutuskan membeli atau booking.', 'Check fit and comfort before committing to a purchase or booking.'); ?></li>
                <li><?php echo contenly_tr('Tanyakan ke tim bagaimana ini berlaku untuk level pelatihan dan rencana menyelam Anda.', 'Ask the crew how this applies to your training level and dive plan.'); ?></li>
                <li><?php echo contenly_tr('Catat hal-hal sederhana, praktis, dan aman sebelum sesi laut berikutnya.', 'Keep notes simple, practical, and safe before the next ocean session.'); ?></li>
              </ul>
            </div>
            <?php endif; ?>
            <div class="wd-story-tip"><h3><?php echo contenly_tr('Catatan menyelam', 'Dive note'); ?></h3><p><?php echo contenly_tr('Gunakan ini sebagai persiapan praktis, lalu konfirmasi tanggal kursus, kondisi laut, dan ukuran peralatan ke tim Whale Dive Centre.', 'Use this as practical preparation, then confirm course dates, sea conditions, and gear fit with the Whale Dive Centre crew.'); ?></p></div>
            <footer class="wd-story-foot"><a class="ghost" href="/blog/"><?php echo contenly_tr('← Kembali ke Blog', '← Back to Blog'); ?></a><a class="primary" href="/courses/"><?php echo contenly_tr('Jelajahi Kursus', 'Explore Courses'); ?></a></footer>
          </div>
        </article>
        <aside class="wd-story-side">
          <section class="wd-side-card"><h3><?php echo contenly_tr('Artikel Lainnya', 'More Articles'); ?></h3><ul><?php foreach($other_posts as $p){ echo '<li><a href="'.esc_url(get_permalink($p)).'">'.esc_html(get_the_title($p)).'</a><small>'.esc_html(get_the_date('d M Y',$p)).'</small></li>'; } ?></ul></section>
          <section class="wd-side-card wd-side-cta"><h3><?php echo contenly_tr('Butuh panduan kursus?', 'Need course guidance?'); ?></h3><p><?php echo contenly_tr('Bicara dengan instruktur sebelum booking jalur menyelam berikutnya.', 'Talk to an instructor before booking your next dive path.'); ?></p><a href="/contact/"><?php echo contenly_tr('Tanya Tim Kami', 'Ask the Crew'); ?></a></section>
        </aside>
      </div>
    </div>
  </section>

  <?php endwhile; ?>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker"><?php echo contenly_tr('Siap menyelam?', 'Ready to dive?'); ?></span><h2>Whale Dive Centre</h2><p><?php echo contenly_tr('Pelatihan menyelam, perjalanan komunitas, dukungan peralatan, dan pengalaman berwawasan laut.', 'Dive training, community trips, equipment support, and ocean-minded experiences.'); ?></p><a class="wd-btn alt" href="/about/"><?php echo contenly_tr('Mulai Konsultasi', 'Start Inquiry'); ?></a></div><nav class="wd-footer-col"><h3><?php echo contenly_tr('Jelajahi', 'Explore'); ?></h3><a href="/courses/"><?php echo contenly_tr('Kursus Menyelam', 'Dive Courses'); ?></a><a href="/equipment/"><?php echo contenly_tr('Peralatan Selam', 'Scuba Equipment'); ?></a><a href="/about/"><?php echo contenly_tr('Tentang Kami', 'About Us'); ?></a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3><?php echo contenly_tr('Kursus', 'Courses'); ?></h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3><?php echo contenly_tr('Kontak', 'Contact'); ?></h3><p>Email: info@whaledivecentre.com</p><p>Telepon: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. <?php echo contenly_tr('Hak cipta dilindungi.', 'All rights reserved.'); ?></span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>

<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key==='blog'&&document.body.classList.contains('whaledive-single'))||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?></body></html>