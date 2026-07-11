<?php
/**
 * Template Name: Gallery
 * Description: Photo gallery page for Whale Dive Centre
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
<style id="wd-gallery-page-polish">
body.whaledive-gallery .wd-gallery-hero{padding:132px 0 56px;background:linear-gradient(135deg,#004A98 0%,#3B44AC 100%);color:#fff}
body.whaledive-gallery .wd-gallery-hero .wd-shell{max-width:860px}
body.whaledive-gallery .wd-gallery-hero .wd-kicker{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.2);color:#fff}
body.whaledive-gallery .wd-gallery-hero h1{margin:14px 0 12px;font-size:clamp(34px,6vw,52px);line-height:1.05;letter-spacing:-.04em;color:#fff}
body.whaledive-gallery .wd-gallery-hero p{margin:0;color:rgba(255,255,255,.88);font-size:16px;line-height:1.7;max-width:680px}
body.whaledive-gallery .wd-gallery-filter{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin:0 0 28px}
body.whaledive-gallery .wd-filter-btn{min-height:42px;padding:0 16px;border-radius:999px;border:1px solid rgba(6,56,77,.12);background:#fff;color:#0b617c;font-weight:800;font-size:13px;cursor:pointer}
body.whaledive-gallery .wd-filter-btn.active,body.whaledive-gallery .wd-filter-btn:hover{background:#06384d;color:#fff;border-color:#06384d}
body.whaledive-gallery .wd-gallery-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
body.whaledive-gallery .wd-gallery-item{position:relative;overflow:hidden;border-radius:22px;aspect-ratio:4/3;background:#eef8fb;cursor:pointer;box-shadow:0 14px 34px rgba(2,32,46,.08);border:1px solid rgba(6,56,77,.08)}
body.whaledive-gallery .wd-gallery-item img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .25s ease}
body.whaledive-gallery .wd-gallery-item:hover img{transform:scale(1.05)}
body.whaledive-gallery .wd-gallery-caption{position:absolute;left:0;right:0;bottom:0;padding:14px 16px;background:linear-gradient(180deg,transparent,rgba(3,23,45,.78));color:#fff;opacity:0;transition:opacity .2s ease}
body.whaledive-gallery .wd-gallery-item:hover .wd-gallery-caption{opacity:1}
body.whaledive-gallery .wd-gallery-cta{margin-top:34px;padding:28px 24px;border-radius:24px;text-align:center;background:linear-gradient(145deg,#f7fcff,#e8f7fb);border:1px solid rgba(6,56,77,.12);box-shadow:0 16px 40px rgba(2,32,46,.07)}
body.whaledive-gallery .wd-gallery-cta h3{margin:0 0 8px;color:#04172d;font-size:28px}
body.whaledive-gallery .wd-gallery-cta p{margin:0 0 16px;color:#475569}
body.whaledive-gallery .wd-lightbox{display:none;position:fixed;inset:0;z-index:2000;background:rgba(2,17,38,.88);align-items:center;justify-content:center;padding:24px}
body.whaledive-gallery .wd-lightbox-content{max-width:min(1000px,92vw);max-height:78vh;border-radius:18px;object-fit:contain}
body.whaledive-gallery .wd-lightbox-close,body.whaledive-gallery .wd-lightbox-prev,body.whaledive-gallery .wd-lightbox-next{position:absolute;border:0;background:rgba(255,255,255,.14);color:#fff;width:44px;height:44px;border-radius:999px;cursor:pointer;font-size:24px}
body.whaledive-gallery .wd-lightbox-close{top:18px;right:18px}
body.whaledive-gallery .wd-lightbox-prev{left:18px;top:50%}
body.whaledive-gallery .wd-lightbox-next{right:18px;top:50%}
body.whaledive-gallery .wd-lightbox-caption{position:absolute;left:50%;bottom:24px;transform:translateX(-50%);color:#fff;text-align:center;max-width:80vw}
@media(max-width:900px){body.whaledive-gallery .wd-gallery-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:560px){body.whaledive-gallery .wd-gallery-hero{padding:110px 0 40px}body.whaledive-gallery .wd-gallery-grid{grid-template-columns:1fr}}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-gallery'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-gallery-hero">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Momen bawah air', 'Underwater moments')); ?></span>
      <h1><?php echo esc_html(contenly_tr('Kenangan dive dari komunitas kami.', 'Dive memories from our community.')); ?></h1>
      <p><?php echo esc_html(contenly_tr('Sorotan kursus, trip, kehidupan laut, dan momen konservasi yang ditangkap crew serta diver Whale Dive Centre.', 'Course highlights, dive trips, marine life encounters, and ocean conservation moments captured by the Whale Dive Centre crew and divers.')); ?></p>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell">
      <div class="wd-gallery-filter" role="tablist" aria-label="<?php echo esc_attr(contenly_tr('Filter galeri', 'Gallery filters')); ?>">
        <button type="button" class="wd-filter-btn active" data-filter="all"><?php echo esc_html(contenly_tr('Semua Foto', 'All Photos')); ?></button>
        <button type="button" class="wd-filter-btn" data-filter="courses"><?php echo esc_html(contenly_tr('Kursus', 'Courses')); ?></button>
        <button type="button" class="wd-filter-btn" data-filter="trips"><?php echo esc_html(contenly_tr('Dive Trips', 'Dive Trips')); ?></button>
        <button type="button" class="wd-filter-btn" data-filter="marine"><?php echo esc_html(contenly_tr('Kehidupan Laut', 'Marine Life')); ?></button>
        <button type="button" class="wd-filter-btn" data-filter="conservation"><?php echo esc_html(contenly_tr('Konservasi', 'Conservation')); ?></button>
      </div>

      <div class="wd-gallery-grid">
        <?php
        $args = array(
          'post_type' => 'attachment',
          'post_mime_type' => 'image',
          'posts_per_page' => 24,
          'post_status' => 'inherit',
          'orderby' => 'date',
          'order' => 'DESC',
        );
        $gallery_query = new WP_Query($args);
        if ($gallery_query->have_posts()) :
          while ($gallery_query->have_posts()) : $gallery_query->the_post();
            $image_url = wp_get_attachment_url(get_the_ID());
            $image_caption = get_the_excerpt() ?: get_the_title();
            $image_category = get_post_meta(get_the_ID(), 'gallery_category', true);
            $category_class = $image_category ? $image_category : 'all';
            ?>
            <div class="wd-gallery-item" data-category="<?php echo esc_attr($category_class); ?>">
              <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_caption); ?>" loading="lazy">
              <?php if ($image_caption) : ?>
                <div class="wd-gallery-caption"><p><?php echo esc_html($image_caption); ?></p></div>
              <?php endif; ?>
            </div>
          <?php endwhile; wp_reset_postdata();
        else :
          $placeholders = array(
            array('courses', contenly_tr('Latihan open water', 'Open water training')),
            array('trips', contenly_tr('Trip komunitas', 'Community trip')),
            array('marine', contenly_tr('Kehidupan laut', 'Marine life')),
            array('conservation', contenly_tr('Aksi konservasi', 'Conservation action')),
            array('courses', contenly_tr('Sesi buoyancy', 'Buoyancy session')),
            array('trips', contenly_tr('Dive weekend', 'Weekend dive')),
            array('marine', contenly_tr('Coral garden', 'Coral garden')),
            array('conservation', contenly_tr('Reef briefing', 'Reef briefing')),
            array('courses', contenly_tr('Rescue practice', 'Rescue practice')),
            array('trips', contenly_tr('Shore dive', 'Shore dive')),
            array('marine', contenly_tr('Macro life', 'Macro life')),
            array('conservation', contenly_tr('Ocean care', 'Ocean care')),
          );
          foreach ($placeholders as $i => $item) :
            $n = $i + 1;
            ?>
            <div class="wd-gallery-item" data-category="<?php echo esc_attr($item[0]); ?>">
              <img src="https://placehold.co/800x600/004A98/FFFFFF?text=Dive+<?php echo (int) $n; ?>" alt="<?php echo esc_attr($item[1]); ?>" loading="lazy">
              <div class="wd-gallery-caption"><p><?php echo esc_html($item[1]); ?></p></div>
            </div>
          <?php endforeach;
        endif;
        ?>
      </div>

      <div class="wd-gallery-cta">
        <h3><?php echo esc_html(contenly_tr('Bagikan foto dive kamu', 'Share your dive photos')); ?></h3>
        <p><?php echo esc_html(contenly_tr('Punya momen bawah air bagus? Kirim ke crew, kami bisa tampilkan di galeri.', 'Captured a great underwater moment? Send your photos to the crew and we may feature them in the gallery.')); ?></p>
        <a class="wd-btn" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html(contenly_tr('Kirim Foto', 'Submit Photos')); ?></a>
      </div>
    </div>
  </section>

  <?php contenly_render_public_footer(); ?>
</main>

<div id="wd-lightbox" class="wd-lightbox" aria-hidden="true">
  <button type="button" class="wd-lightbox-close" aria-label="Close">&times;</button>
  <button type="button" class="wd-lightbox-prev" aria-label="Previous">&#10094;</button>
  <img class="wd-lightbox-content" id="wd-lightbox-img" alt="">
  <button type="button" class="wd-lightbox-next" aria-label="Next">&#10095;</button>
  <div class="wd-lightbox-caption" id="wd-lightbox-caption"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var filterBtns = document.querySelectorAll('.wd-filter-btn');
  var galleryItems = document.querySelectorAll('.wd-gallery-item');
  filterBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
      var filter = this.getAttribute('data-filter');
      filterBtns.forEach(function(b){ b.classList.remove('active'); });
      this.classList.add('active');
      galleryItems.forEach(function(item){
        var cat = item.getAttribute('data-category') || 'all';
        item.style.display = (filter === 'all' || cat === filter || cat === 'all') ? '' : 'none';
      });
    });
  });

  var lightbox = document.getElementById('wd-lightbox');
  var lightboxImg = document.getElementById('wd-lightbox-img');
  var lightboxCaption = document.getElementById('wd-lightbox-caption');
  var currentIndex = 0;
  var visibleItems = [];
  function updateVisibleItems(){
    visibleItems = Array.prototype.slice.call(galleryItems).filter(function(item){ return item.style.display !== 'none'; });
  }
  function showLightbox(item){
    var img = item.querySelector('img');
    var caption = item.querySelector('.wd-gallery-caption p');
    lightbox.style.display = 'flex';
    lightbox.setAttribute('aria-hidden', 'false');
    lightboxImg.src = img.src;
    lightboxCaption.textContent = caption ? caption.textContent : '';
    document.body.style.overflow = 'hidden';
  }
  function hideLightbox(){
    lightbox.style.display = 'none';
    lightbox.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
  galleryItems.forEach(function(item){
    item.addEventListener('click', function(){
      updateVisibleItems();
      currentIndex = visibleItems.indexOf(this);
      showLightbox(this);
    });
  });
  document.querySelector('.wd-lightbox-close').addEventListener('click', hideLightbox);
  lightbox.addEventListener('click', function(e){ if (e.target === lightbox) hideLightbox(); });
  document.querySelector('.wd-lightbox-prev').addEventListener('click', function(){
    if (!visibleItems.length) return;
    currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
    showLightbox(visibleItems[currentIndex]);
  });
  document.querySelector('.wd-lightbox-next').addEventListener('click', function(){
    if (!visibleItems.length) return;
    currentIndex = (currentIndex + 1) % visibleItems.length;
    showLightbox(visibleItems[currentIndex]);
  });
  document.addEventListener('keydown', function(e){
    if (lightbox.style.display !== 'flex') return;
    if (e.key === 'Escape') hideLightbox();
    if (e.key === 'ArrowLeft') document.querySelector('.wd-lightbox-prev').click();
    if (e.key === 'ArrowRight') document.querySelector('.wd-lightbox-next').click();
  });
});
</script>
<?php wp_footer(); ?></body></html>
