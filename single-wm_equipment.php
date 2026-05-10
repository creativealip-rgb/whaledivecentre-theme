<?php
/**
 * Single Equipment — Whale Dive Centre
 */
while(have_posts()): the_post();
$price = get_post_meta(get_the_ID(), '_wm_price', true);
$stock = get_post_meta(get_the_ID(), '_wm_stock', true);
$sizes = get_post_meta(get_the_ID(), '_wm_sizes', true);
$category = wp_get_post_terms(get_the_ID(), 'equipment_category');
$brand = wp_get_post_terms(get_the_ID(), 'equipment_brand');
$cat_name = !empty($category) ? $category[0]->name : '';
$brand_name = !empty($brand) ? $brand[0]->name : '';
$theme_uri = get_stylesheet_directory_uri();
endwhile; rewind_posts();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-equipment whaledive-single-equip'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-compact-hero wd-equipment-hero">
    <div class="wd-shell wd-inner-grid">
      <div>
        <div class="wd-breadcrumb"><a href="/">Home</a> <span>/</span> <a href="/equipment/">Equipment</a> <span>/</span> <?php the_title(); ?></div>
        <?php if($brand_name): ?><span class="wd-kicker"><?php echo esc_html($brand_name); ?></span><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php
        // Equipment category images (Unsplash placeholders)
        $equip_images = [
            'masks' => 'https://images.unsplash.com/photo-1682687982501-1e58ab814714?w=800&h=500&fit=crop',
            'wetsuits' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=500&fit=crop',
            'bcd' => 'https://images.unsplash.com/photo-1559827291-bae8bb5af212?w=800&h=500&fit=crop',
            'regulators' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?w=800&h=500&fit=crop',
            'fins' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=500&fit=crop',
            'dive-computers' => 'https://images.unsplash.com/photo-1559827291-bae8bb5af212?w=800&h=500&fit=crop',
        ];
        $cat_slug = isset($cat_slug) ? $cat_slug : '';
        $img_url = $equip_images[$cat_slug] ?? $equip_images['masks'];
        ?>
        <div style="width:100%;height:320px;border-radius:24px;overflow:hidden;margin-bottom:28px;background:url('<?php echo $img_url; ?>') center/cover no-repeat"></div>

        <div class="wd-detail-meta">
          <?php if($cat_name): ?><span><?php echo esc_html($cat_name); ?></span><?php endif; ?>
          <?php if($brand_name): ?><span><?php echo esc_html($brand_name); ?></span><?php endif; ?>
          <?php if($price): ?><span class="wd-agency-badge">Rp <?php echo number_format((float)$price,0,',','.'); ?></span><?php endif; ?>
        </div>
        <div class="wd-actions">
          <?php if (is_user_logged_in()): ?><button type="button" class="wd-btn wd-equipment-add-cart" data-item-id="<?php echo get_the_ID(); ?>">Add to Cart</button><?php else: ?><a class="wd-btn" href="/member-login/?next=checkout">Login to Buy</a><?php endif; ?>
          <a class="wd-btn alt" href="/equipment/">All Equipment</a>
        </div>
      </div>
      
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell wd-content-grid">
      <div class="wd-content-main">
        <?php while(have_posts()): the_post(); ?>
        <?php if(get_the_content()): the_content(); else: ?>
          <span class="wd-kicker">About this gear</span>
          <h2 class="wd-title"><?php the_title(); ?></h2>
          <p>Quality dive gear selected by the Whale Dive Centre crew. Contact us for fit guidance, availability, and rental options.</p>
        <?php endif; endwhile; ?>
      </div>
      <aside class="wd-content-sidebar">
        <div class="wd-sidebar-card">
          <?php if($price): ?>
          <div class="wd-sidebar-price">
            <span class="wd-price-label">Price</span>
            <span class="wd-price-amount">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
          </div>
          <?php endif; ?>
          <?php if($sizes): ?><h4>Available Sizes</h4><p><?php echo esc_html($sizes); ?></p><?php endif; ?>
          <?php if (is_user_logged_in()): ?><button type="button" class="wd-btn wd-equipment-add-cart" data-item-id="<?php echo get_the_ID(); ?>" style="width:100%;text-align:center;margin-top:16px">Add to Cart</button><?php else: ?><a class="wd-btn" href="/member-login/?next=checkout" style="width:100%;text-align:center;margin-top:16px">Login to Buy</a><?php endif; ?>
        </div>
      </aside>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Instagram: @whaledivecentre.id</p><p>Bali dive crew — base details available on inquiry</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener("DOMContentLoaded",function(){var b=document.querySelector(".wd-hamburger"),m=document.querySelector(".wd-menu");if(!b||!m)return;b.addEventListener("click",function(){var o=document.body.classList.toggle("wd-menu-open");b.setAttribute("aria-expanded",o?"true":"false")});m.querySelectorAll("a").forEach(function(a){a.addEventListener("click",function(){document.body.classList.remove("wd-menu-open");b.setAttribute("aria-expanded","false")})})});</script>
<?php wp_footer(); ?><script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.wd-equipment-add-cart').forEach(function(btn){
    btn.addEventListener('click', function(){
      if (!window.wmCart || !wmCart.addToCart) { window.location.href = '/checkout/'; return; }
      var original = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Adding...';
      wmCart.addToCart('equipment', btn.getAttribute('data-item-id'), 1, {}).then(function(data){
        if (data && data.success) { window.location.href = '/checkout/'; return; }
        btn.disabled = false;
        btn.textContent = original;
      }).catch(function(){ btn.disabled = false; btn.textContent = original; });
    });
  });
});
</script>
</body></html>