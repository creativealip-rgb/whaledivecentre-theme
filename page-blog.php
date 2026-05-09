<?php
/**
 * Template Name: Blog Page
 */
get_header();

$paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: 1);
$posts_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 10,
  'paged' => $paged,
  'orderby' => 'date',
  'order' => 'DESC',
]);
?>

<main class="site-main blog-page-v2">
  <section class="blog-hero">
    <div class="site-container">
      <p class="blog-eyebrow">Blog</p>
      <h1>Cerita, tips, dan insight perjalanan</h1>
      <p>Konten pilihan dari tim Ganesha Travel untuk bantu kamu merencanakan trip yang lebih rapi dan minim drama.</p>
    </div>
  </section>

  <section class="blog-section">
    <div class="site-container">
      <?php
      $featured_post = null;
      if ($posts_q->have_posts()) {
        $posts_q->the_post();
        $featured_post = get_post();
      }
      ?>

      <?php if ($featured_post): ?>
        <article class="featured-story">
          <a href="<?php echo esc_url(get_permalink($featured_post)); ?>" class="featured-media">
            <?php if (has_post_thumbnail($featured_post)) : ?>
              <?php echo get_the_post_thumbnail($featured_post, 'large', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
            <?php else: ?>
              <div class="featured-fallback"><span>Featured Story</span></div>
            <?php endif; ?>
          </a>
          <div class="featured-body">
            <div class="meta"><?php echo esc_html(get_the_date('d M Y', $featured_post)); ?> · <?php echo esc_html(get_the_author_meta('display_name', $featured_post->post_author)); ?></div>
            <h2><a href="<?php echo esc_url(get_permalink($featured_post)); ?>"><?php echo esc_html(get_the_title($featured_post)); ?></a></h2>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt($featured_post) ?: $featured_post->post_content, 34, '...')); ?></p>
            <a class="read" href="<?php echo esc_url(get_permalink($featured_post)); ?>">Baca selengkapnya →</a>
          </div>
        </article>
      <?php endif; ?>

      <div class="blog-tax">
        <span>Kategori:</span>
        <?php
        $cats = get_categories(['hide_empty' => true, 'number' => 8]);
        foreach ($cats as $cat) {
          echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" class="chip">' . esc_html($cat->name) . '</a>';
        }
        ?>
      </div>

      <div class="blog-grid">
        <?php if($posts_q->have_posts()): while($posts_q->have_posts()): $posts_q->the_post(); ?>
          <article class="blog-card">
            <a href="<?php the_permalink(); ?>" class="blog-media">
              <?php if(has_post_thumbnail()): the_post_thumbnail('medium_large',['style'=>'width:100%;height:100%;object-fit:cover;']); else: ?>
                <div class="blog-fallback"><span>Travel Notes</span></div>
              <?php endif; ?>
            </a>
            <div class="blog-body">
              <div class="meta"><?php echo esc_html(get_the_date('d M Y')); ?> · <?php the_author(); ?></div>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 18, '...')); ?></p>
              <a class="read" href="<?php the_permalink(); ?>">Baca selengkapnya →</a>
            </div>
          </article>
        <?php endwhile; else: ?>
          <p class="empty">Belum ada artikel yang dipublikasikan.</p>
        <?php endif; wp_reset_postdata(); ?>
      </div>

      <?php if($posts_q->max_num_pages > 1): ?>
        <div class="blog-paginate"><?php echo paginate_links(['total'=>$posts_q->max_num_pages,'current'=>$paged]); ?></div>
      <?php endif; ?>
    </div>
  </section>
</main>

<style>
.blog-page-v2{background:#f8fafc}
.blog-hero{padding:72px 0;background:linear-gradient(135deg,#1d4ed8,#0ea5e9);color:#fff;text-align:center}
.blog-eyebrow{margin:0 0 8px;font-size:12px;letter-spacing:.08em;text-transform:uppercase;font-weight:800;opacity:.92}
.blog-hero h1{margin:0 0 10px;font-size:clamp(32px,5vw,50px)}
.blog-hero p{margin:0 auto;max-width:760px;font-size:18px;opacity:.95;line-height:1.65}
.blog-section{padding:56px 0}

.featured-story{display:grid;grid-template-columns:1.1fr .9fr;gap:18px;background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:14px;box-shadow:0 10px 24px rgba(15,23,42,.07);margin-bottom:22px}
.featured-media{display:block;border-radius:14px;overflow:hidden;min-height:320px}
.featured-fallback{height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1d4ed8,#38bdf8);color:#fff;font-weight:700;letter-spacing:.04em}
.featured-body{display:flex;flex-direction:column;justify-content:center;padding:8px 6px}
.meta{font-size:12px;color:#64748b;margin-bottom:8px}
.featured-body h2{margin:0 0 10px;font-size:32px;line-height:1.2}
.featured-body h2 a{text-decoration:none;color:#0f172a}
.featured-body p{margin:0 0 12px;color:#64748b;line-height:1.7}

.blog-tax{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:16px}
.blog-tax span{font-size:13px;color:#64748b;font-weight:700}
.chip{display:inline-flex;align-items:center;height:32px;padding:0 12px;border-radius:999px;border:1px solid #dbeafe;background:#fff;color:#1d4ed8;text-decoration:none;font-size:13px;font-weight:700}

.blog-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.blog-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 8px 20px rgba(15,23,42,.06);transition:transform .22s ease,box-shadow .22s ease}
.blog-card:hover{transform:translateY(-3px);box-shadow:0 14px 28px rgba(15,23,42,.11)}
.blog-media{height:180px;display:block;overflow:hidden}
.blog-fallback{height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#2563eb,#38bdf8);color:#fff}
.blog-fallback span{font-weight:700;letter-spacing:.04em}
.blog-body{padding:14px;display:grid;gap:8px}
.blog-body h3{margin:0;font-size:20px;line-height:1.3;min-height:52px}
.blog-body h3 a{text-decoration:none;color:#0f172a}
.blog-body p{margin:0;color:#64748b;line-height:1.65}
.read{color:#2563eb;text-decoration:none;font-weight:700}
.blog-paginate{margin-top:20px;text-align:center}
.empty{text-align:center;color:#64748b}

@media (max-width: 1100px){
  .blog-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .featured-body h2{font-size:26px}
}
@media (max-width: 820px){
  .featured-story{grid-template-columns:1fr}
  .featured-media{min-height:220px}
}
@media (max-width: 768px){
  .blog-hero{padding:52px 0}
  .blog-grid{grid-template-columns:1fr}
}
</style>

<?php get_footer(); ?>