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
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-blog-ux-pass">.wd-blog-search{display:flex;gap:10px;margin:0 0 14px}.wd-blog-search input{flex:1;min-height:50px;border:1px solid #d8e8e8;border-radius:999px;padding:0 18px}.wd-blog-search button{border:0;border-radius:999px;padding:0 22px;background:#06384d;color:#fff;font-weight:800}.wd-topic-pills{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:22px}.wd-topic-pills a{display:inline-flex;align-items:center;min-height:38px;padding:0 13px;border-radius:999px;background:#eef8fb;color:#0b617c;text-decoration:none;font-weight:800;font-size:13px}.wd-blog-cta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0 0 28px;padding:18px;border-radius:22px;background:linear-gradient(135deg,#06384d,#08a7c7);color:#fff}.wd-blog-cta span{color:rgba(255,255,255,.78)}.wd-blog-cta a{margin-left:auto;color:#06384d;background:#fff;border-radius:999px;padding:10px 14px;text-decoration:none;font-weight:900}@media(max-width:640px){.wd-blog-search{display:grid}.wd-blog-cta a{width:100%;text-align:center}}</style></head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <!-- HERO -->
  <section class="wd-inner-hero wd-blog-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker">Blog</span>
          <h1>Stories, tips, and diving insights</h1>
          <p>Selected articles from the Whale Dive Centre crew to help you dive safer, calmer, and better prepared.</p><div class="wd-actions"><a class="wd-btn" href="/courses/">Explore Courses</a><a class="wd-btn alt" href="/contact/">Talk to an Instructor</a></div>
        </div>
      </div>
    </div>
  </section>

  <!-- BLOG POSTS -->
  <section class="wd-section white">
    <div class="wd-shell">
      <div class="wd-editorial-note"><span>Field notes</span><b>Practical dive knowledge, written for real decisions.</b><p>Use these guides to prepare for courses, gear choices, and safer habits before you meet the crew.</p></div><form class="wd-blog-search" method="get" action="/blog/"><input type="search" name="s" placeholder="Search dive tips, gear, courses, or safety topics"><button type="submit">Search</button></form><div class="wd-topic-pills"><a href="/blog/">All</a><a href="/category/beginner/">Beginner Tips</a><a href="/category/safety/">Safety</a><a href="/category/gear-guide/">Gear Guide</a><a href="/category/training/">Training</a><a href="/category/conservation/">Conservation</a></div><div class="wd-blog-cta"><b>Reading before your first dive?</b><span>Get a course plan based on comfort level, schedule, and gear needs.</span><a href="/contact/">Talk to an Instructor</a></div>
      <?php if ($posts_q->have_posts()) : ?>
        <?php $first = true; ?>
        <?php while ($posts_q->have_posts()) : $posts_q->the_post(); ?>
          <?php
            $cats = get_the_category();
            $cat_name = $cats ? $cats[0]->name : 'Article';
            $read_time = max(2, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 220));
            $byline = 'Whale Dive Centre Team';
          ?>
          <?php if ($first) : ?>
            <?php $first = false; ?>
            <article class="wd-blog-featured">
              <span class="wd-blog-tag"><?php echo esc_html($cat_name); ?></span>
              <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
              <span class="wd-blog-meta"><?php echo esc_html($byline); ?> · <?php echo get_the_date(); ?> · <?php echo esc_html($read_time); ?> min read</span>
              <a href="<?php the_permalink(); ?>" class="wd-btn">Read more &rarr;</a>
            </article>
            <div class="wd-blog-grid">
          <?php else : ?>
            <article class="wd-blog-card">
              <span class="wd-blog-tag"><?php echo esc_html($cat_name); ?></span>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
              <span class="wd-blog-meta"><?php echo esc_html($byline); ?> · <?php echo get_the_date(); ?> · <?php echo esc_html($read_time); ?> min read</span>
              <a href="<?php the_permalink(); ?>">Read more &rarr;</a>
            </article>
          <?php endif; ?>
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