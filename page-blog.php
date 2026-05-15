<?php
/**
 * Template Name: Blog Page
 */

$paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: 1);
$posts_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 10,
  'paged' => $paged,
  'orderby' => 'date',
  'order' => 'DESC',
]);
$theme_uri = get_stylesheet_directory_uri();

$wd_blog_fallback_image = function($post_id, $cat_name = '') use ($theme_uri) {
  $label = strtolower($cat_name . ' ' . get_the_title($post_id));
  if (strpos($label, 'gear') !== false || strpos($label, 'mask') !== false) return $theme_uri . '/assets/wdc-equipment-mask-real.png';
  if (strpos($label, 'fin') !== false) return $theme_uri . '/assets/wdc-equipment-fins-real.png';
  if (strpos($label, 'bcd') !== false) return $theme_uri . '/assets/wdc-equipment-bcd-real.png';
  if (strpos($label, 'safety') !== false || strpos($label, 'buddy') !== false || strpos($label, 'rescue') !== false) return $theme_uri . '/assets/wdc-course-rescue-diver-real.png';
  if (strpos($label, 'advanced') !== false) return $theme_uri . '/assets/wdc-course-advanced-open-water-real.png';
  if (strpos($label, 'conservation') !== false || strpos($label, 'reef') !== false) return $theme_uri . '/assets/wdc-home-hero-diving-clean3.webp';
  return $theme_uri . '/assets/wdc-course-open-water-real.png';
};
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-blog-ux-pass">.wd-blog-search{display:flex;gap:10px;margin:0 0 14px}.wd-blog-search input{flex:1;min-height:50px;border:1px solid #d8e8e8;border-radius:999px;padding:0 18px}.wd-blog-search button{border:0;border-radius:999px;padding:0 22px;background:#06384d;color:#fff;font-weight:800}.wd-topic-pills{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:22px}.wd-topic-pills a{display:inline-flex;align-items:center;min-height:38px;padding:0 13px;border-radius:999px;background:#eef8fb;color:#0b617c;text-decoration:none;font-weight:800;font-size:13px}.wd-blog-cta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0 0 28px;padding:18px;border-radius:22px;background:linear-gradient(135deg,#06384d,#08a7c7);color:#fff}.wd-blog-cta span{color:rgba(255,255,255,.78)}.wd-blog-cta a{margin-left:auto;color:#06384d;background:#fff;border-radius:999px;padding:10px 14px;text-decoration:none;font-weight:900}@media(max-width:640px){.wd-blog-search{display:grid}.wd-blog-cta a{width:100%;text-align:center}}</style><style id="wd-blog-readable-critical">
/* WDC blog readable cards, rounded corners, reliable image fallback */
.whaledive-blog .wd-blog-featured-card,.whaledive-blog .wd-blog-side-card,.whaledive-blog .wd-blog-card-modern{border-radius:26px!important;overflow:hidden!important;background:#fff!important;color:#061a36!important}
.whaledive-blog .wd-blog-featured-media{border-radius:26px 26px 0 0!important;overflow:hidden!important}
.whaledive-blog .wd-blog-card-media{border-radius:26px 26px 0 0!important;overflow:hidden!important}
.whaledive-blog .wd-blog-mini a{border-radius:18px!important;background:#fff!important;color:#061a36!important}
.whaledive-blog .wd-blog-mini-thumb{border-radius:14px!important;overflow:hidden!important;background:linear-gradient(135deg,#004A98,#4CC8ED)!important}
.whaledive-blog .wd-blog-featured-body,.whaledive-blog .wd-blog-card-body,.whaledive-blog .wd-blog-side-card{background:#fff!important;color:#061a36!important}
.whaledive-blog .wd-blog-featured-body h2,.whaledive-blog .wd-blog-featured-body h2 a,.whaledive-blog .wd-blog-card-body h3,.whaledive-blog .wd-blog-card-body h3 a,.whaledive-blog .wd-blog-mini strong{color:#061a36!important;text-shadow:none!important;opacity:1!important}
.whaledive-blog .wd-blog-featured-body p,.whaledive-blog .wd-blog-card-body p,.whaledive-blog .wd-blog-meta-row em{color:#50697b!important;opacity:1!important;text-shadow:none!important}
.whaledive-blog .wd-blog-card-body span,.whaledive-blog .wd-blog-meta-row span,.whaledive-blog .wd-blog-mini small{color:#004A98!important;opacity:1!important}
.whaledive-blog .wd-blog-card-body>a{background:#eef8fb!important;color:#004A98!important;border-color:rgba(0,74,152,.16)!important}
.whaledive-blog .wd-blog-featured-media img,.whaledive-blog .wd-blog-card-media img,.whaledive-blog .wd-blog-mini-thumb img{background:linear-gradient(135deg,#004A98,#4CC8ED)!important;min-height:100%!important}
</style><style id="wd-blog-text-force-critical">
/* WDC blog force readable text in every card */
.whaledive-blog article.wd-blog-featured-card,
.whaledive-blog article.wd-blog-card-modern,
.whaledive-blog .wd-blog-side-card,
.whaledive-blog article.wd-blog-mini{background:#fff!important;color:#061a36!important;border-radius:26px!important;overflow:hidden!important}
.whaledive-blog .wd-blog-featured-body,
.whaledive-blog .wd-blog-card-body{display:flex!important;flex-direction:column!important;background:#fff!important;color:#061a36!important;position:relative!important;z-index:2!important;visibility:visible!important;opacity:1!important}
.whaledive-blog .wd-blog-featured-body *,
.whaledive-blog .wd-blog-card-body *,
.whaledive-blog .wd-blog-side-card *,
.whaledive-blog .wd-blog-mini *{visibility:visible!important;opacity:1!important;text-shadow:none!important;mix-blend-mode:normal!important;filter:none!important}
.whaledive-blog .wd-blog-featured-body h2,
.whaledive-blog .wd-blog-featured-body h2 a,
.whaledive-blog .wd-blog-card-body h3,
.whaledive-blog .wd-blog-card-body h3 a,
.whaledive-blog .wd-blog-side-card h3,
.whaledive-blog .wd-blog-mini strong{color:#061a36!important;background:transparent!important;-webkit-text-fill-color:#061a36!important}
.whaledive-blog .wd-blog-featured-body p,
.whaledive-blog .wd-blog-card-body p,
.whaledive-blog .wd-blog-meta-row em{color:#334155!important;background:transparent!important;-webkit-text-fill-color:#334155!important}
.whaledive-blog .wd-blog-meta-row span,
.whaledive-blog .wd-blog-card-body span,
.whaledive-blog .wd-blog-mini small,
.whaledive-blog .wd-blog-side-card>span{color:#004A98!important;background:transparent!important;-webkit-text-fill-color:#004A98!important}
.whaledive-blog .wd-blog-featured-media b{background:#fff!important;color:#061a36!important;-webkit-text-fill-color:#061a36!important;border:1px solid rgba(255,255,255,.55)!important}
.whaledive-blog .wd-blog-read{background:#004A98!important;color:#fff!important;-webkit-text-fill-color:#fff!important}
.whaledive-blog .wd-blog-card-body>a{background:#eef8fb!important;color:#004A98!important;-webkit-text-fill-color:#004A98!important}
</style></head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <!-- BLOG POSTS -->
  <section class="wd-section white wd-blog-section-clean" id="articles">
    <div class="wd-shell">
      <div class="wd-blog-section-head"><span class="wd-kicker">Dive Journal</span><h2>Artikel terbaru Whale Dive Centre</h2><p>Tips training, safety, equipment, conservation, dan cerita komunitas untuk bantu kamu dive lebih siap.</p></div>
      <div class="wd-filter-bar wd-blog-category-filter" aria-label="Blog categories">
        <a class="wd-chip active" href="/blog/">All Articles</a>
        <?php foreach (get_categories(['hide_empty' => true, 'number' => 8]) as $wd_cat) : ?>
          <a class="wd-chip" href="<?php echo esc_url(get_category_link($wd_cat)); ?>"><?php echo esc_html($wd_cat->name); ?></a>
        <?php endforeach; ?>
      </div>
      <?php if ($posts_q->have_posts()) : ?>
        <?php $posts_q->the_post();
          $cats = get_the_category(); $cat_name = $cats ? $cats[0]->name : 'Article';
          $read_time = max(2, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 220));
        ?>
        <div class="wd-blog-latest-layout">
          <div class="wd-blog-latest-main">
            <article class="wd-blog-featured-card">
              <a class="wd-blog-featured-media" href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) { the_post_thumbnail('large'); } else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?>
                <b>Artikel Pilihan</b>
              </a>
              <div class="wd-blog-featured-body">
                <div class="wd-blog-meta-row"><span><?php echo esc_html($cat_name); ?></span><em><?php echo get_the_date('d M Y'); ?> · <?php echo esc_html($read_time); ?> min read</em></div>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 34)); ?></p>
                <a class="wd-blog-read" href="<?php the_permalink(); ?>">Baca selengkapnya →</a>
              </div>
            </article>
          </div>
          <aside class="wd-blog-latest-side"><div class="wd-blog-side-card"><span>Artikel Lainnya</span><h3>Artikel terbaru lainnya</h3><div class="wd-blog-mini-list">
            <?php $mini_count=0; while ($posts_q->have_posts() && $mini_count < 4) : $posts_q->the_post(); $mini_count++; $cats=get_the_category(); $cat_name=$cats?$cats[0]->name:'Article'; ?>
              <article class="wd-blog-mini"><a href="<?php the_permalink(); ?>"><span class="wd-blog-mini-thumb"><?php if(has_post_thumbnail()){the_post_thumbnail('thumbnail');} else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></span><span><small><?php echo esc_html($cat_name); ?> · <?php echo get_the_date('d M Y'); ?></small><strong><?php the_title(); ?></strong></span></a></article>
            <?php endwhile; ?>
          </div></div></aside>
        </div>
        <div class="wd-blog-grid wd-blog-grid-modern">
          <?php while ($posts_q->have_posts()) : $posts_q->the_post(); $cats=get_the_category(); $cat_name=$cats?$cats[0]->name:'Article'; $read_time=max(2,(int)ceil(str_word_count(wp_strip_all_tags(get_the_content()))/220)); ?>
            <article class="wd-blog-card-modern">
              <a class="wd-blog-card-media" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()){the_post_thumbnail('medium_large');} else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></a>
              <div class="wd-blog-card-body"><span><?php echo esc_html($cat_name); ?> · <?php echo get_the_date('d M Y'); ?></span><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 18)); ?></p><a href="<?php the_permalink(); ?>">Baca selengkapnya →</a></div>
            </article>
          <?php endwhile; ?>
        </div>
      <?php else : ?>
        <p class="wd-empty">No articles published yet.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body>
</html>