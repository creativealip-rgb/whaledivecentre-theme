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
  if (strpos($label, 'safety') !== false || strpos($label, 'buddy') !== false || strpos($label, 'rescue') !== false) return $theme_uri . '/assets/Rescue Diver.png';
  if (strpos($label, 'advanced') !== false) return $theme_uri . '/assets/Advanced Open Water.png';
  if (strpos($label, 'conservation') !== false || strpos($label, 'reef') !== false) return $theme_uri . '/assets/wdc-home-hero-diving-clean3.webp';
  return $theme_uri . '/assets/Open Water.png';
};
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-blog-ux-pass">.wd-blog-search{display:flex;gap:10px;margin:0 0 14px}.wd-blog-search input{flex:1;min-height:50px;border:1px solid #d8e8e8;border-radius:999px;padding:0 18px}.wd-blog-search button{border:0;border-radius:999px;padding:0 22px;background:#06384d;color:#fff;font-weight:800}.wd-topic-pills{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:22px}.wd-topic-pills a{display:inline-flex;align-items:center;min-height:38px;padding:0 13px;border-radius:999px;background:#eef8fb;color:#0b617c;text-decoration:none;font-weight:800;font-size:13px}.wd-blog-cta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0 0 28px;padding:18px;border-radius:22px;background:linear-gradient(135deg,#06384d,#08a7c7);color:#fff}.wd-blog-cta span{color:rgba(255,255,255,.78)}.wd-blog-cta a{margin-left:auto;color:#06384d;background:#fff;border-radius:999px;padding:10px 14px;text-decoration:none;font-weight:900}@media(max-width:640px){.wd-blog-search{display:grid}.wd-blog-cta a{width:100%;text-align:center}}</style><style id="wd-blog-brand-critical">
/* WDC blog brand guideline cleanup */
.whaledive-blog{background:#f5fbff!important;color:#0b1930!important;font-family:"Trebuchet MS",Verdana,sans-serif!important}
.whaledive-blog .wd-blog-editorial-hero{padding:132px 0 44px!important;background:radial-gradient(circle at 86% 12%,rgba(76,200,237,.32),transparent 28%),radial-gradient(circle at 8% 18%,rgba(150,218,234,.42),transparent 24%),linear-gradient(135deg,#061a36 0%,#004A98 56%,#3B44AC 100%)!important;color:#fff!important}
.whaledive-blog .wd-blog-editorial-hero:before{background:linear-gradient(180deg,rgba(0,0,0,.12),rgba(0,0,0,.06)),repeating-radial-gradient(circle at 20% 0,rgba(255,255,255,.1) 0 1px,transparent 1px 34px)!important}
.whaledive-blog .wd-blog-kicker{background:rgba(255,255,255,.14)!important;color:#96DAEA!important;border-color:rgba(150,218,234,.32)!important;box-shadow:none!important}
.whaledive-blog .wd-blog-hero-copy h1{max-width:900px!important;color:#fff!important;text-shadow:0 18px 44px rgba(0,0,0,.18)!important}
.whaledive-blog .wd-blog-hero-copy p{max-width:760px!important;color:rgba(255,255,255,.84)!important}
.whaledive-blog .wd-blog-proof span{background:rgba(255,255,255,.12)!important;border-color:rgba(255,255,255,.18)!important;color:#fff!important;backdrop-filter:blur(10px)}
.whaledive-blog .wd-blog-category-row{margin-top:30px!important;background:rgba(255,255,255,.96)!important;border-color:rgba(150,218,234,.4)!important;box-shadow:0 22px 58px rgba(0,0,0,.18)!important}
.whaledive-blog .wd-blog-category-row span{color:#004A98!important}
.whaledive-blog .wd-blog-category-row a{background:#eef8fb!important;color:#004A98!important;border:1px solid rgba(0,74,152,.12)!important}
.whaledive-blog .wd-blog-category-row a:first-of-type{background:#C31C4A!important;color:#fff!important;border-color:#C31C4A!important}
.whaledive-blog .wd-blog-section-clean{padding:58px 0 90px!important;background:linear-gradient(180deg,#fff 0%,#f5fbff 100%)!important}
.whaledive-blog .wd-blog-section-head{max-width:780px!important;margin:0 0 30px!important;padding:0!important;text-align:left!important}
.whaledive-blog .wd-blog-section-head>span{background:#eef8fb!important;color:#004A98!important;border-color:rgba(0,74,152,.14)!important;box-shadow:none!important}
.whaledive-blog .wd-blog-section-head h2{color:#061a36!important}
.whaledive-blog .wd-blog-section-head p{color:#63748a!important}
.whaledive-blog .wd-blog-latest-layout{width:100%!important;max-width:none!important;grid-template-columns:minmax(0,1fr) 370px!important;gap:28px!important;align-items:stretch!important;margin:0 0 34px!important}
.whaledive-blog .wd-blog-grid-modern{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:28px!important;align-items:stretch!important}
.whaledive-blog .wd-blog-featured-card,.whaledive-blog .wd-blog-side-card,.whaledive-blog .wd-blog-card-modern{border-radius:0!important;border:1px solid rgba(0,74,152,.12)!important;background:#fff!important;box-shadow:0 24px 68px rgba(6,26,54,.1)!important;overflow:hidden!important}
.whaledive-blog .wd-blog-featured-media,.whaledive-blog .wd-blog-card-media{background:linear-gradient(135deg,#004A98,#4CC8ED)!important}
.whaledive-blog .wd-blog-featured-media b{background:#C31C4A!important;color:#fff!important;letter-spacing:.09em!important}
.whaledive-blog .wd-blog-meta-row span{background:#eef8fb!important;color:#004A98!important}
.whaledive-blog .wd-blog-featured-body h2 a,.whaledive-blog .wd-blog-card-modern h3 a,.whaledive-blog .wd-blog-mini strong{color:#061a36!important}
.whaledive-blog .wd-blog-featured-body p,.whaledive-blog .wd-blog-card-body p{color:#63748a!important}
.whaledive-blog .wd-blog-read{background:#004A98!important;color:#fff!important;box-shadow:0 12px 28px rgba(0,74,152,.18)!important}
.whaledive-blog .wd-blog-side-card>span,.whaledive-blog .wd-blog-mini small,.whaledive-blog .wd-blog-card-body span{color:#004A98!important}
.whaledive-blog .wd-blog-mini a{background:#f5fbff!important;border-color:rgba(0,74,152,.1)!important}
.whaledive-blog .wd-blog-card-body{min-height:260px!important}
.whaledive-blog .wd-blog-card-body>a{background:#eef8fb!important;color:#004A98!important;border:1px solid rgba(0,74,152,.12)!important}
.whaledive-blog .wd-blog-card-body>a:hover,.whaledive-blog .wd-blog-read:hover{background:#C31C4A!important;color:#fff!important;border-color:#C31C4A!important}
@media(max-width:980px){.whaledive-blog .wd-blog-latest-layout{grid-template-columns:1fr!important}.whaledive-blog .wd-blog-grid-modern{grid-template-columns:1fr 1fr!important}.whaledive-blog .wd-blog-editorial-hero{padding-top:118px!important}}
@media(max-width:680px){.whaledive-blog .wd-blog-editorial-hero{padding:104px 0 34px!important}.whaledive-blog .wd-blog-hero-copy h1{font-size:40px!important;line-height:1!important}.whaledive-blog .wd-blog-latest-layout,.whaledive-blog .wd-blog-grid-modern{gap:20px!important}.whaledive-blog .wd-blog-grid-modern{grid-template-columns:1fr!important}.whaledive-blog .wd-blog-category-row{display:grid!important;grid-template-columns:1fr 1fr!important}.whaledive-blog .wd-blog-category-row span{grid-column:1/-1}.whaledive-blog .wd-blog-featured-body,.whaledive-blog .wd-blog-card-body,.whaledive-blog .wd-blog-side-card{padding:20px!important}}
</style></head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-blog-editorial-hero">
    <div class="wd-shell">
      <div class="wd-blog-hero-copy">
        <span class="wd-blog-kicker">Dive Journal</span>
        <h1>Cerita, tips, dan insight diving yang gampang discan</h1>
        <p>Baca panduan pilihan dari Whale Dive Centre: dari persiapan course, safety, equipment, sampai inspirasi trip bawah laut buat diver baru maupun yang sudah aktif.</p>
        <div class="wd-blog-proof"><span>Artikel terbaru</span><span>Tips first timer</span><span>Gear & safety insight</span></div>
      </div>
      <div class="wd-blog-category-row">
        <span>Kategori:</span>
        <a href="/blog/">Semua</a>
        <?php foreach (get_categories(['hide_empty' => true, 'number' => 6]) as $wd_cat) : ?>
          <a href="<?php echo esc_url(get_category_link($wd_cat)); ?>"><?php echo esc_html($wd_cat->name); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- BLOG POSTS -->
  <section class="wd-section white wd-blog-section-clean" id="articles">
    <div class="wd-shell">
      <div class="wd-blog-section-head"><span>Artikel Terbaru</span><h2>Pilih bacaan yang paling relevan dulu</h2><p>Format dibuat lebih editorial: satu artikel utama untuk fokus, lalu daftar ringkas dan grid lanjutan untuk quick scan.</p></div>
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