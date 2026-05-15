<?php
/**
 * Template Name: Blog Page
 */

$posts_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 10,
  'orderby' => 'date',
  'order' => 'DESC',
]);
$posts = $posts_q->posts;
$featured = $posts[0] ?? null;
$side_posts = array_slice($posts, 1, 4);
$more_posts = array_slice($posts, 5);
function wd_blog_card_image($post_id) {
  return get_the_post_thumbnail_url($post_id, 'large') ?: get_stylesheet_directory_uri() . '/assets/hero-bg.jpg';
}
function wd_blog_read_time($post_id) {
  return max(2, (int) ceil(str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id))) / 220));
}
function wd_blog_cat_name($post_id) {
  $cats = get_the_category($post_id);
  return $cats ? $cats[0]->name : 'Article';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-section white wd-blog-simple wd-blog-editorial">
    <div class="wd-shell">
      <div class="wd-blog-simple-head">
        <span class="wd-blog-tag">Safety / Gear Guide / Training Tips</span>
        <h1>Dive Stories & Safety Notes</h1>
        <p>Premium field notes from the Whale Dive Centre crew: safety habits, gear-fit guidance, training tips, conservation, and travel prep.</p>
      </div>

      <?php if ($featured) : setup_postdata($featured); ?>
        <div class="wd-blog-editorial-grid">
          <article class="wd-blog-feature-card">
            <a class="wd-blog-feature-image" href="<?php echo esc_url(get_permalink($featured)); ?>" style="background-image:url('<?php echo esc_url(wd_blog_card_image($featured->ID)); ?>')" aria-label="<?php echo esc_attr(get_the_title($featured)); ?>">
              <span>Featured article</span>
            </a>
            <div class="wd-blog-feature-body">
              <span class="wd-blog-tag"><?php echo esc_html(wd_blog_cat_name($featured->ID)); ?></span>
              <span class="wd-blog-meta"><?php echo esc_html(get_the_date('', $featured)); ?> · <?php echo esc_html(wd_blog_read_time($featured->ID)); ?> min read</span>
              <h2><a href="<?php echo esc_url(get_permalink($featured)); ?>"><?php echo esc_html(get_the_title($featured)); ?></a></h2>
              <p><?php echo esc_html(wp_trim_words(get_the_excerpt($featured), 28)); ?></p>
              <a class="wd-blog-read" href="<?php echo esc_url(get_permalink($featured)); ?>">Read article &rarr;</a>
            </div>
          </article>

          <aside class="wd-blog-side-card">
            <span class="wd-blog-tag">More articles</span>
            <h3>Latest from the crew</h3>
            <p>Quick scan for other dive topics before you choose what to read next.</p>
            <div class="wd-blog-side-list">
              <?php foreach ($side_posts as $post) : setup_postdata($post); ?>
                <a class="wd-blog-side-item" href="<?php the_permalink(); ?>">
                  <span class="wd-blog-side-thumb" style="background-image:url('<?php echo esc_url(wd_blog_card_image(get_the_ID())); ?>')"></span>
                  <span class="wd-blog-side-copy">
                    <small><?php echo esc_html(wd_blog_cat_name(get_the_ID())); ?> · <?php echo get_the_date(); ?></small>
                    <b><?php the_title(); ?></b>
                  </span>
                </a>
              <?php endforeach; ?>
            </div>
          </aside>
        </div>

        <?php if ($more_posts) : ?>
          <div class="wd-blog-more-grid">
            <?php foreach ($more_posts as $post) : setup_postdata($post); ?>
              <article class="wd-blog-card wd-blog-simple-card">
                <a class="wd-blog-card-image" href="<?php the_permalink(); ?>" style="background-image:url('<?php echo esc_url(wd_blog_card_image(get_the_ID())); ?>')" aria-label="<?php the_title_attribute(); ?>"></a>
                <div class="wd-blog-card-body">
                  <span class="wd-blog-tag"><?php echo esc_html(wd_blog_cat_name(get_the_ID())); ?></span>
                  <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                  <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                  <a href="<?php the_permalink(); ?>" class="wd-blog-read">Read article &rarr;</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php else : ?>
        <p class="wd-empty">No articles published yet.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<?php wp_footer(); ?>
</body>
</html>
