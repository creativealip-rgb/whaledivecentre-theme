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
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <!-- HERO -->
  <section class="wd-inner-hero wd-blog-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker">Blog</span>
          <h1>Stories, tips, and diving insights</h1>
          <p>Selected articles from the Whale Dive Centre crew to help you dive safer, calmer, and better prepared.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- BLOG POSTS -->
  <section class="wd-section white">
    <div class="wd-shell">
      <?php if ($posts_q->have_posts()) : ?>
        <?php $first = true; ?>
        <?php while ($posts_q->have_posts()) : $posts_q->the_post(); ?>
          <?php
            $cats = get_the_category();
            $cat_name = $cats ? $cats[0]->name : 'Article';
          ?>
          <?php if ($first) : ?>
            <?php $first = false; ?>
            <article class="wd-blog-featured">
              <span class="wd-blog-tag"><?php echo esc_html($cat_name); ?></span>
              <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
              <span class="wd-blog-meta"><?php echo get_the_date(); ?> · <?php the_author(); ?></span>
              <a href="<?php the_permalink(); ?>" class="wd-btn">Read more &rarr;</a>
            </article>
            <div class="wd-blog-grid">
          <?php else : ?>
            <article class="wd-blog-card">
              <span class="wd-blog-tag"><?php echo esc_html($cat_name); ?></span>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
              <span class="wd-blog-meta"><?php echo get_the_date(); ?> · <?php the_author(); ?></span>
              <a href="<?php the_permalink(); ?>">Read more &rarr;</a>
            </article>
          <?php endif; ?>
        <?php endwhile; ?>
        </div>
      <?php else : ?>
        <p class="wd-empty">Belum ada artikel.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>WhatsApp: <?php echo esc_html(get_option("wdc_whatsapp_number", "@whaledivecentre.id")); ?></p><p>Bali dive crew — base details available on inquiry</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<?php wp_footer(); ?>
</body>
</html>