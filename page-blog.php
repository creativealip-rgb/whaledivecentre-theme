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
?>
<?php get_header(); ?>
<?php get_header(); ?>

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