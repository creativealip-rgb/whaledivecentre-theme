<?php get_header(); ?>
<main class="wd-page wd-inner">
<header class="wd-header"><meta charset="utf-8"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>WHALE DIVE CENTRE</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

<section class="wd-hero wd-hero-inner" style="background:linear-gradient(135deg,#0c2d48 0%,#0a3d62 50%,#145374 100%)">
<div class="wd-shell">
<span class="wd-kicker">Dive Training Pathway</span>
<h1>Dive Courses</h1>
<p class="wd-hero-sub">From your first breath underwater to professional-level leadership. Small groups, safety-first, Bali-based.</p>
</div>
</section>

<section class="wd-section white"><div class="wd-shell">
<?php
$levels = get_terms(['taxonomy'=>'course_level','hide_empty'=>true,'orderby'=>'term_id']);
foreach($levels as $lvl):
$courses = new WP_Query(['post_type'=>'wm_course','posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC','tax_query'=>[['taxonomy'=>'course_level','field'=>'term_id','terms'=>$lvl->term_id]]]);
if(!$courses->have_posts()) continue;
?>
<div class="wd-course-section">
<h2 class="wd-course-level-title"><?php echo esc_html($lvl->name); ?></h2>
<div class="wd-course-grid wd-archive-grid">
<?php while($courses->have_posts()): $courses->the_post();
$price = get_post_meta(get_the_ID(), '_wm_price', true);
$duration = get_post_meta(get_the_ID(), '_wm_duration', true);
?>
<article class="wd-course-card wd-course-card-lg">
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<div class="wd-course-meta"><span><?php echo esc_html($lvl->name); ?></span><span><?php echo esc_html($duration); ?></span></div>
<p><?php echo esc_html(get_the_excerpt()); ?></p>
<div class="wd-course-footer">
<?php if($price): ?><span class="wd-course-price">Rp <?php echo number_format((float)$price, 0, ',', '.'); ?></span><?php endif; ?>
<a href="<?php the_permalink(); ?>" class="wd-link">View details →</a>
</div>
</article>
<?php endwhile; wp_reset_postdata(); ?>
</div>
</div>
<?php endforeach; ?>

<div class="wd-section-cta wd-center" style="margin-top:48px">
<h3>Not sure which course?</h3>
<p>Tell us your experience level and goals — the crew will recommend the right path.</p>
<a class="wd-btn" href="/contact/">Ask the Crew</a>
</div>
</div></section>
</main>
<?php get_footer(); ?>
