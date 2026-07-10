<?php get_header(); ?>
<main class="wd-page wd-inner">
<?php get_header(); ?>

<section class="wd-hero wd-hero-inner" style="background:linear-gradient(135deg,#0c2d48 0%,#145374 50%,#0a3d62 100%)">
<div class="wd-shell">
<span class="wd-kicker">Scuba Gear Support</span>
<h1>Dive Equipment</h1>
<p class="wd-hero-sub">Quality gear for training, fun dives, and safer underwater comfort. Buy or rent through the crew.</p>
</div>
</section>

<section class="wd-section white"><div class="wd-shell">
<?php
$categories = get_terms(['taxonomy'=>'equipment_category','hide_empty'=>true,'orderby'=>'name']);
foreach($categories as $cat):
$items = new WP_Query(['post_type'=>'wm_equipment','posts_per_page'=>-1,'orderby'=>'menu_order','order'=>'ASC','tax_query'=>[['taxonomy'=>'equipment_category','field'=>'term_id','terms'=>$cat->term_id]]]);
if(!$items->have_posts()) continue;
?>
<div class="wd-equip-section">
<h2 class="wd-course-level-title"><?php echo esc_html($cat->name); ?></h2>
<div class="wd-equip-archive-grid">
<?php while($items->have_posts()): $items->the_post();
$price = get_post_meta(get_the_ID(), '_wm_price', true);
$stock = get_post_meta(get_the_ID(), '_wm_stock', true);
$sizes = get_post_meta(get_the_ID(), '_wm_sizes', true);
$brand = wp_get_post_terms(get_the_ID(), 'equipment_brand');
?>
<article class="wd-product-card">
<div class="wd-product-img"><span class="wd-product-initial"><?php echo mb_substr(get_the_title(), 0, 1); ?></span></div>
<div class="wd-product-body">
<?php if($brand): ?><span class="wd-product-brand"><?php echo esc_html($brand[0]->name); ?></span><?php endif; ?>
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<p><?php echo esc_html(get_the_excerpt()); ?></p>
<div class="wd-product-footer">
<span class="wd-product-price">Rp <?php echo number_format((float)$price, 0, ',', '.'); ?></span>
<div class="wd-product-meta">
<?php if($sizes): ?><span>Sizes: <?php echo esc_html($sizes); ?></span><?php endif; ?>
<?php if($stock): ?><span class="wd-stock <?php echo $stock > 0 ? 'in' : 'out'; ?>">
<?php echo $stock > 0 ? "In stock ($stock)" : 'Out of stock'; ?></span><?php endif; ?>
</div>
</div>
<?php if(is_user_logged_in()): ?>
<button class="wd-btn-sm wd-add-cart" data-type="equipment" data-id="<?php echo get_the_ID(); ?>">Add to Cart</button>
<?php else: ?>
<a href="/member-register/" class="wd-btn-sm">Register to Buy</a>
<?php endif; ?>
</div>
</article>
<?php endwhile; wp_reset_postdata(); ?>
</div>
</div>
<?php endforeach; ?>

<div class="wd-section-cta wd-center" style="margin-top:48px">
<h3>Need help choosing?</h3>
<p>Good scuba equipment should feel calm underwater. Send your needs and the crew will guide the fit.</p>
<a class="wd-btn" href="/contact/">Request Gear Guidance</a>
</div>
</div></section>
</main>
<?php get_footer(); ?>
