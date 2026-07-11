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

  <?php contenly_render_public_footer(); ?>
</main><?php wp_footer(); ?></body></html>
