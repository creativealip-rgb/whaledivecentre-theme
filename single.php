<?php
/**
 * Single Post Template — Whale Dive Centre
 */
$theme_uri = get_stylesheet_directory_uri();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-single'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <?php while (have_posts()) : the_post();
    $cats = get_the_category();
    $cat_name = !empty($cats) ? $cats[0]->name : 'Article';
    $word_count = str_word_count(wp_strip_all_tags(get_post_field('post_content', get_the_ID())));
    $read_min = max(1, (int) ceil($word_count / 220));
  ?>

  <section class="wd-compact-hero wd-single-hero">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(strtoupper($cat_name)); ?></span>
      <h1><?php the_title(); ?></h1>
      <div class="wd-single-meta">
        <span><?php echo get_the_date('d M Y'); ?></span>
        <span>·</span>
        <span><?php echo esc_html(get_the_author()); ?></span>
        <span>·</span>
        <span><?php echo $read_min; ?> min read</span>
      </div>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-article-shell">
      <?php if (has_post_thumbnail()) : ?>
        <figure class="wd-article-cover">
          <?php the_post_thumbnail('large', ['class' => 'wd-cover-img']); ?>
        </figure>
      <?php endif; ?>

      <div class="wd-article-body">
        <?php the_content(); ?>
      </div>

      <?php
      // Tags
      $tags = get_the_tags();
      if ($tags) : ?>
        <div class="wd-article-tags">
          <?php foreach ($tags as $tag) : ?>
            <span class="wd-tag"><?php echo esc_html($tag->name); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Post navigation -->
      <div class="wd-post-nav">
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        if ($prev) : ?>
          <a class="wd-post-nav-link prev" href="<?php echo get_permalink($prev); ?>">
            <span class="wd-nav-label">← Previous</span>
            <strong><?php echo esc_html(get_the_title($prev)); ?></strong>
          </a>
        <?php endif;
        if ($next) : ?>
          <a class="wd-post-nav-link next" href="<?php echo get_permalink($next); ?>">
            <span class="wd-nav-label">Next →</span>
            <strong><?php echo esc_html(get_the_title($next)); ?></strong>
          </a>
        <?php endif; ?>
      </div>

      <div class="wd-back-blog">
        <a class="wd-btn alt" href="/blog/">← Back to Blog</a>
      </div>
    </div>
  </section>

  <?php endwhile; ?>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences.</p><a class="wd-btn alt" href="/about/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Instagram: @whaledivecentre.id</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener("DOMContentLoaded",function(){var b=document.querySelector(".wd-hamburger"),m=document.querySelector(".wd-menu");if(!b||!m)return;b.addEventListener("click",function(){var o=document.body.classList.toggle("wd-menu-open");b.setAttribute("aria-expanded",o?"true":"false")});m.querySelectorAll("a").forEach(function(a){a.addEventListener("click",function(){document.body.classList.remove("wd-menu-open");b.setAttribute("aria-expanded","false")})})});</script>
<?php wp_footer(); ?></body></html>