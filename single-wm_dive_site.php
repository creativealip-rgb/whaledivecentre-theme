<?php
/**
 * Single Dive Site — Whale Dive Centre
 */
while(have_posts()): the_post();
$highlights = get_post_meta(get_the_ID(), '_wm_highlights', true);
$best_season = get_post_meta(get_the_ID(), '_wm_best_season', true);
$depth = get_post_meta(get_the_ID(), '_wm_depth_range', true);
$region = wp_get_post_terms(get_the_ID(), 'dive_region');
$difficulty = wp_get_post_terms(get_the_ID(), 'dive_difficulty');
$region_name = !empty($region) ? $region[0]->name : '';
$diff_name = !empty($difficulty) ? $difficulty[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
endwhile; rewind_posts();
?>
<?php get_header(); ?>
<?php get_header(); ?>

  <section class="wd-compact-hero wd-divesite-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/">Home</a> <span>/</span> <span>/</span> <?php the_title(); ?></div>
        <?php if($region_name): ?><span class="wd-kicker"><?php echo esc_html($region_name); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="wd-detail-meta">
          <?php if($diff_name): ?><span><?php echo esc_html($diff_name); ?></span><?php endif; ?>
          <?php if($depth): ?><span><?php echo esc_html($depth); ?></span><?php endif; ?>
          <?php if($best_season): ?><span><?php echo esc_html($best_season); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <a class="wd-btn" href="/contact/">Plan a Dive Trip</a>
</div>
      </div>
      
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): the_content(); else: ?>
          <span class="wd-kicker">About this dive site</span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <?php if($highlights): ?><p><strong>Highlights:</strong> <?php echo esc_html($highlights); ?></p><?php endif; ?>
          <p>Contact the crew for trip scheduling, conditions, and group availability.</p>
        <?php endif; endwhile; ?>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <h3>Plan Your Dive</h3>
          <dl class="wd-info-list">
            <?php if($diff_name): ?><dt>Difficulty</dt><dd><?php echo esc_html($diff_name); ?></dd><?php endif; ?>
            <?php if($depth): ?><dt>Depth Range</dt><dd><?php echo esc_html($depth); ?></dd><?php endif; ?>
            <?php if($best_season): ?><dt>Best Season</dt><dd><?php echo esc_html($best_season); ?></dd><?php endif; ?>
            <?php if($region_name): ?><dt>Region</dt><dd><?php echo esc_html($region_name); ?></dd><?php endif; ?>
          </dl>
          <a class="wd-btn" href="/contact/" style="width:100%;text-align:center;margin-top:16px">Plan a Trip</a>
        </div>
      </aside>
    </div>
  </section>

  <section class="wd-section wd-community wd-center"><div class="wd-shell"><span class="wd-kicker">Explore more</span><h2 class="wd-title">Discover Bali&rsquo;s best underwater worlds.</h2><p class="wd-sub">From shipwrecks to manta encounters — the crew knows every site.</p>
</div></section>
<?php get_footer(); ?>
