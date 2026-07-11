<?php
/**
 * Template Name: Blog Page
 */

$paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: 1);
$sticky_posts = get_option('sticky_posts', []);
$featured_id = !empty($sticky_posts) ? (int) $sticky_posts[0] : 0;

$featured_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 1,
  'p' => $featured_id ?: 0,
  'ignore_sticky_posts' => true,
]);

if (!$featured_id || !$featured_q->have_posts()) {
  $featured_q = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
  ]);
  $featured_id = $featured_q->have_posts() ? (int) $featured_q->posts[0]->ID : 0;
}

$posts_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 12,
  'paged' => $paged,
  'post__not_in' => $featured_id ? [$featured_id] : [],
  'orderby' => 'date',
  'order' => 'DESC',
  'ignore_sticky_posts' => true,
]);
$theme_uri = get_stylesheet_directory_uri();

$wd_blog_fallback_image = function($post_id, $cat_name = '') use ($theme_uri) {
  $label = strtolower($cat_name . ' ' . get_the_title($post_id));
  if (strpos($label, 'gear') !== false || strpos($label, 'mask') !== false) return $theme_uri . '/assets/wdc-equipment-mask-real.webp';
  if (strpos($label, 'fin') !== false) return $theme_uri . '/assets/wdc-equipment-fins-real.png';
  if (strpos($label, 'bcd') !== false) return $theme_uri . '/assets/wdc-equipment-bcd-real.webp';
  if (strpos($label, 'safety') !== false || strpos($label, 'buddy') !== false || strpos($label, 'rescue') !== false) return $theme_uri . '/assets/wdc-course-rescue-diver-real.webp';
  if (strpos($label, 'advanced') !== false) return $theme_uri . '/assets/wdc-course-advanced-open-water-real.webp';
  if (strpos($label, 'conservation') !== false || strpos($label, 'reef') !== false) return $theme_uri . '/assets/wdc-home-hero-diving-clean3.webp';
  return $theme_uri . '/assets/wdc-course-open-water-real.webp';
};
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
<style id="wd-blog-compact">
/* WDC blog single style pass 2026-07-11 — merged simple+meta+featured+uiux */
.whaledive-blog{background:#f4fbff!important}.whaledive-blog .wd-blog-simple{position:relative;padding:112px 0 56px!important;background:#f4fbff!important}.wd-blog-simple .wd-shell{position:relative}.wd-blog-simple-head{display:block;margin:0 0 22px;color:#061a36}.wd-blog-simple-head .wd-kicker{display:inline-flex!important;margin:0!important;padding:8px 13px!important;border-radius:999px!important;background:rgba(76,200,237,.16)!important;border:1px solid rgba(76,200,237,.34)!important;color:#96DAEA!important;font-size:12px!important;font-weight:900!important;letter-spacing:.13em!important;text-transform:uppercase!important}.wd-blog-simple-head h1{margin:10px 0 0;color:#061a36;font-size:clamp(30px,3.8vw,44px);line-height:1;letter-spacing:-.035em}.whaledive-blog .wd-blog-category-filter{justify-content:flex-start!important;margin:0 0 24px!important;max-width:none!important;gap:8px!important}.whaledive-blog .wd-blog-category-filter .wd-chip{min-height:38px!important;padding:0 13px!important;border-radius:999px!important;background:#fff!important;color:#06384d!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 10px 22px rgba(2,21,43,.08)!important;font-size:13px!important;font-weight:900!important}.whaledive-blog .wd-blog-category-filter .wd-chip.active{background:#4CC8ED!important;color:#03172d!important;border-color:#4CC8ED!important}.whaledive-blog .wd-blog-grid-compact{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:16px!important;margin:0!important}.whaledive-blog .wd-blog-card-compact{border-radius:20px!important;background:#fff!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 16px 36px rgba(2,21,43,.08)!important;overflow:hidden!important;display:flex!important;flex-direction:column!important;min-height:0!important;transition:transform .18s ease,box-shadow .18s ease!important}.whaledive-blog .wd-blog-... [truncated]
.whaledive-blog .wd-blog-grid-compact{grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body{padding:14px!important;gap:7px!important;min-height:158px!important}.whaledive-blog .wd-card-meta{display:flex!important;align-items:center!important;gap:6px!important;min-width:0!important;margin:0!important}.whaledive-blog .wd-card-meta span{display:inline-flex!important;align-items:center!important;min-width:0!important;width:auto!important;max-width:100%!important;margin:0!important;padding:5px 8px!important;border-radius:999px!important;font-size:10px!important;font-weight:900!important;line-height:1!important;letter-spacing:.035em!important;text-transform:uppercase!important;white-space:nowrap!important;overflow:hidden!important;text-overflow:ellipsis!important}.whaledive-blog .wd-card-meta .wd-card-cat{flex:1 1 auto!important;background:#e9f8fd!important;color:#006b8f!important;-webkit-text-fill-color:#006b8f!important}.whaledive-blog .wd-card-meta .wd-card-time{flex:0 0 auto!important;background:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>span{display:none!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body h3{font-size:clamp(15px,1.18vw,18px)!important;line-height:1.16!important;-webkit-line-clamp:2!important;margin:0!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body p{font-size:12.5px!important;line-height:1.42!important;-webkit-line-clamp:2!important;margin:0!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a{padding:7px 10px!important;font-size:12px!important;background:#eef8fb!important;color:#004A98!important;-webkit-text-fill-color:#004A98!important;border:1px solid rgba(0,74,152,.12)!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-media{aspect-ratio:16/9!important}@media(max-width:980px){.whaledive-blog .wd-blog-grid-compact{grid-template-columns:repeat(3,minmax(0,1fr))!important}}@media(max-width:760px){.whaledive-blog .wd-blog-grid-compact{grid-template-columns:repeat(2,minmax(0,1fr))!important}.whaledive-blog .wd-blog-simple{padding-top:96px!important}}@media(max-width:540px){.whaledive-blog .wd-blog-grid-compact{grid-template-columns:1fr!important;gap:14px!important}.whaledive-blog .wd-blog-card-compact{display:grid!important;grid-template-columns:112px minmax(0,1fr)!important;border-radius:18px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-media{height:100%!important;aspect-ratio:auto!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body{min-height:142px!important;padding:12px!important}.whaledive-blog .wd-card-meta .wd-card-cat{max-width:135px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body p{display:none!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a{margin-top:auto!important}.wd-blog-simple-head h1{font-size:32px!important}}
.whaledive-blog .wd-blog-featured-simple{display:grid!important;grid-template-columns:minmax(0,1.08fr) minmax(320px,.92fr)!important;gap:0!important;margin:0 0 20px!important;border-radius:24px!important;overflow:hidden!important;background:#fff!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 18px 42px rgba(2,21,43,.08)!important}.whaledive-blog .wd-featured-media{display:block!important;min-height:240px!important;background:#dff8ff!important}.whaledive-blog .wd-featured-media img{display:block!important;width:100%!important;height:100%!important;object-fit:cover!important}.whaledive-blog .wd-featured-body{display:flex!important;flex-direction:column!important;justify-content:center!important;padding:28px!important;color:#061a36!important}.whaledive-blog .wd-featured-body h2{margin:10px 0 10px!important;font-size:clamp(28px,3.3vw,46px)!important;line-height:1!important;letter-spacing:-.045em!important}.whaledive-blog .wd-featured-body h2 a{color:#061a36!important;text-decoration:none!important}.whaledive-blog .wd-featured-body p{margin:0 0 18px!important;color:#50697b!important;font-size:15px!important;line-height:1.55!important}.whaledive-blog .wd-featured-read{align-self:flex-start!important;margin-top:auto!important;padding:10px 14px!important;border-radius:999px!important;background:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important;text-decoration:none!important;font-weight:900!important;font-size:13px!important}.whaledive-blog .wd-blog-pagination{display:flex!important;justify-content:center!important;gap:12px!important;flex-wrap:wrap!important;margin:28px 0 0!important}.whaledive-blog .wd-blog-pagination span{background:transparent!important;padding:0!important;border:0!important;box-shadow:none!important}.whaledive-blog .wd-blog-pagination a{display:inline-flex!important;align-items:center!important;justify-content:center!important;min-height:44px!important;padding:0 18px!important;border-radius:999px!important;background:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important;text-decoration:none!important;font-weight:900!important;box-shadow:0 14px 28px rgba(2,21,43,.12)!important}.whaledive-blog .wd-blog-pagination a:hover{background:#004A98!important}@media(max-width:860px){.whaledive-blog .wd-blog-featured-simple{grid-template-columns:1fr!important}.whaledive-blog .wd-featured-media{min-height:220px!important}.whaledive-blog .wd-featured-body{padding:22px!important}}@media(max-width:540px){.whaledive-blog .wd-featured-media{min-height:190px!important}.whaledive-blog .wd-featured-body h2{font-size:27px!important}.whaledive-blog .wd-blog-pagination{justify-content:stretch!important}.whaledive-blog .wd-blog-pagination span,.whaledive-blog .wd-blog-pagination a{width:100%!important}}
/* UIUX 2026-05-30 — unify blog cards with site card tokens (radius 18, readable 18px title, 16px body, 20px gap, 13px read-more). Last block wins cascade. */
.whaledive-blog .wd-blog-card-compact{border-radius:18px!important}
body.whaledive-blog article.wd-blog-card-compact{border-radius:18px!important}
.whaledive-blog .wd-blog-grid-compact{gap:20px!important}
.whaledive-blog .wd-blog-card-compact .wd-blog-card-body{padding:16px!important}
.whaledive-blog .wd-blog-card-compact .wd-blog-card-body h3{font-size:18px!important;line-height:1.28!important}
.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a{font-size:13px!important}
@media(max-width:760px){.whaledive-blog .wd-blog-grid-compact{gap:16px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body h3{font-size:17px!important}}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <!-- BLOG POSTS -->
  <section class="wd-section wd-blog-simple" id="articles">
    <div class="wd-shell">
      <?php if ($paged === 1 && $featured_q->have_posts()) : ?>
      <div class="wd-blog-simple-head">
        <span class="wd-kicker"><?php echo esc_html(contenly_tr('Jurnal Selam', 'Dive Journal')); ?></span>
        <h1><?php echo esc_html(contenly_tr('Artikel Pilihan', 'Featured Article')); ?></h1>
      </div>
        <?php $featured_q->the_post(); $cats=get_the_category(); $cat_name=$cats?$cats[0]->name:'Article'; $read_time=max(2,(int)ceil(str_word_count(wp_strip_all_tags(get_the_content()))/220)); ?>
        <article class="wd-blog-featured-simple">
          <a class="wd-featured-media" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()){the_post_thumbnail('large');} else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></a>
          <div class="wd-featured-body">
            <div class="wd-card-meta"><span class="wd-card-cat"><?php echo esc_html($cat_name); ?></span><span class="wd-card-time"><?php echo esc_html($read_time); ?> min</span></div>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 24)); ?></p>
            <a class="wd-featured-read" href="<?php the_permalink(); ?>"><?php echo contenly_tr('Baca Selengkapnya →', 'Read More →'); ?></a>
          </div>
        </article>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
      <div class="wd-blog-simple-head wd-blog-archive-head">
        <span class="wd-kicker"><?php echo esc_html(contenly_tr('Jurnal Selam', 'Dive Journal')); ?></span>
        <h1><?php echo esc_html(contenly_tr('Artikel lainnya', 'More from the Journal')); ?></h1>
      </div>
      <?php endif; ?>

      <?php if ($paged === 1) : ?>
      <div class="wd-blog-latest-head">
        <span><?php echo esc_html(contenly_tr('Artikel lainnya', 'More from the Journal')); ?></span>
      </div>
      <?php endif; ?>
      <?php if ($posts_q->have_posts()) : ?>
        <div class="wd-blog-grid wd-blog-grid-modern wd-blog-grid-compact">
          <?php while ($posts_q->have_posts()) : $posts_q->the_post(); $cats=get_the_category(); $cat_name=$cats?$cats[0]->name:'Article'; $read_time=max(2,(int)ceil(str_word_count(wp_strip_all_tags(get_the_content()))/220)); ?>
            <article class="wd-blog-card-modern wd-blog-card-compact">
              <a class="wd-blog-card-media" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()){the_post_thumbnail('medium_large');} else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></a>
              <div class="wd-blog-card-body"><div class="wd-card-meta"><span class="wd-card-cat"><?php echo esc_html($cat_name); ?></span><span class="wd-card-time"><?php echo esc_html($read_time); ?> min</span></div><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 12)); ?></p><a href="<?php the_permalink(); ?>"><?php echo contenly_tr('Baca Selengkapnya →', 'Read More →'); ?></a></div>
            </article>
          <?php endwhile; ?>
        </div>
        <nav class="wd-blog-pagination" aria-label="Blog pagination">
          <?php $older = get_next_posts_link(contenly_tr('Selanjutnya: Artikel Lama →', 'Next: Older Posts →'), $posts_q->max_num_pages); $newer = get_previous_posts_link(contenly_tr('← Artikel Baru', '← Newer Posts')); ?>
          <?php if ($newer) : ?><span><?php echo $newer; ?></span><?php endif; ?>
          <?php if ($older) : ?><span><?php echo $older; ?></span><?php endif; ?>
        </nav>
      <?php else : ?>
        <p class="wd-empty"><?php echo contenly_tr('Belum ada artikel yang dipublikasikan.', 'No latest articles published yet.'); ?></p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <!-- CLOSING CTA — match courses/equipment pattern for cross-page consistency -->
  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo contenly_tr('Mulai dive kamu', 'Start your dive'); ?></span><h2><?php echo esc_html(contenly_tr('Siap ambil langkah pertama di bawah air?', 'Ready to take your first step underwater?')); ?></h2><p><?php echo contenly_tr('Dari kursus pemula sampai panduan gear — crew bantu kamu mulai dengan tenang dan terarah.', 'From beginner courses to gear guidance — the crew helps you start calm and on track.'); ?></p><a class="wd-btn alt" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Crew', 'Ask the Crew')); ?></a></div></section>

  <?php get_footer(); ?>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body>
</html>