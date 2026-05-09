<?php
/**
 * Template Name: Gallery
 * Description: Photo gallery page for Whale Dive Centre
 */
get_header();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8">
    ">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class('whaledive-gallery'); ?>>

<main class="wd-page">
    <!-- Header/Navbar -->
    <header class="wd-header">
        <a href="<?php echo home_url('/'); ?>" class="wd-brand">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.jpg" alt="Whale Dive Centre">
            <span>Whale Dive Centre</span>
        </a>
        <nav class="wd-menu">
            <a href="<?php echo home_url('/'); ?>#membership">MEMBERSHIP</a>
            <a href="<?php echo home_url('/courses/'); ?>">COURSES</a>
            <a href="<?php echo home_url('/equipment/'); ?>">EQUIPMENT</a>
            <a href="<?php echo home_url('/trips/'); ?>">DIVE TRIPS</a>
            <a href="<?php echo home_url('/gallery/'); ?>">GALLERY</a>
            <a href="<?php echo home_url('/blog/'); ?>">BLOG</a>
            <a href="<?php echo home_url('/our-crew/'); ?>">OUR CREW</a>
            <a href="<?php echo home_url('/faq/'); ?>">FAQ</a>
            <a href="<?php echo home_url('/contact/'); ?>">CONTACT</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="wd-gallery-hero">
        <div class="wd-gallery-hero-content">
            <p class="wd-label">UNDERWATER MOMENTS</p>
            <h1>Dive memories from our community.</h1>
            <p class="wd-subtitle">Course highlights, dive trips, marine life encounters, and ocean conservation moments captured by the Whale Dive Centre crew and divers.</p>
        </div>
    </section>

    <!-- Gallery Filter -->
    <section class="wd-section white">
        <div class="wd-container">
            <div class="wd-gallery-filter">
                <button class="wd-filter-btn active" data-filter="all">All Photos</button>
                <button class="wd-filter-btn" data-filter="courses">Courses</button>
                <button class="wd-filter-btn" data-filter="trips">Dive Trips</button>
                <button class="wd-filter-btn" data-filter="marine">Marine Life</button>
                <button class="wd-filter-btn" data-filter="conservation">Conservation</button>
            </div>

            <!-- Gallery Grid -->
            <div class="wd-gallery-grid">
                <?php
                // Get gallery images from WordPress Media Library
                // Using custom field 'gallery_category' for filtering
                $args = array(
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'posts_per_page' => 24,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $gallery_query = new WP_Query($args);

                if ($gallery_query->have_posts()) :
                    while ($gallery_query->have_posts()) : $gallery_query->the_post();
                        $image_url = wp_get_attachment_url(get_the_ID());
                        $image_caption = get_the_excerpt();
                        $image_category = get_post_meta(get_the_ID(), 'gallery_category', true);
                        $category_class = $image_category ? $image_category : 'all';
                        ?>
                        <div class="wd-gallery-item" data-category="<?php echo esc_attr($category_class); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_caption); ?>" loading="lazy">
                            <?php if ($image_caption) : ?>
                                <div class="wd-gallery-caption">
                                    <p><?php echo esc_html($image_caption); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Placeholder gallery items
                    for ($i = 1; $i <= 12; $i++) :
                        $categories = array('courses', 'trips', 'marine', 'conservation');
                        $random_cat = $categories[array_rand($categories)];
                        ?>
                        <div class="wd-gallery-item" data-category="<?php echo $random_cat; ?>">
                            <img src="https://placehold.co/600x400/3B44AC/FFFFFF?text=Dive+Photo+<?php echo $i; ?>" alt="Gallery placeholder <?php echo $i; ?>" loading="lazy">
                            <div class="wd-gallery-caption">
                                <p>Dive moment #<?php echo $i; ?></p>
                            </div>
                        </div>
                    <?php
                    endfor;
                endif;
                ?>
            </div>

            <!-- Upload CTA -->
            <div class="wd-gallery-cta">
                <h3>Share your dive photos</h3>
                <p>Captured a great underwater moment? Send your photos to the crew and we'll feature them in the gallery.</p>
                <a href="<?php echo home_url('/contact/'); ?>" class="wd-btn-primary">Submit Photos</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php get_template_part('template-parts/footer'); ?>
</main>

<!-- Lightbox Modal -->
<div id="wd-lightbox" class="wd-lightbox">
    <span class="wd-lightbox-close">&times;</span>
    <img class="wd-lightbox-content" id="wd-lightbox-img">
    <div class="wd-lightbox-caption" id="wd-lightbox-caption"></div>
    <button class="wd-lightbox-prev">&#10094;</button>
    <button class="wd-lightbox-next">&#10095;</button>
</div>

<script>
// Gallery filter
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.wd-filter-btn');
    const galleryItems = document.querySelectorAll('.wd-gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Lightbox functionality
    const lightbox = document.getElementById('wd-lightbox');
    const lightboxImg = document.getElementById('wd-lightbox-img');
    const lightboxCaption = document.getElementById('wd-lightbox-caption');
    const closeBtn = document.querySelector('.wd-lightbox-close');
    let currentIndex = 0;
    let visibleItems = [];

    function updateVisibleItems() {
        visibleItems = Array.from(galleryItems).filter(item => item.style.display !== 'none');
    }

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            updateVisibleItems();
            currentIndex = visibleItems.indexOf(this);
            showLightbox(this);
        });
    });

    function showLightbox(item) {
        const img = item.querySelector('img');
        const caption = item.querySelector('.wd-gallery-caption p');
        
        lightbox.style.display = 'flex';
        lightboxImg.src = img.src;
        lightboxCaption.textContent = caption ? caption.textContent : '';
        document.body.style.overflow = 'hidden';
    }

    closeBtn.addEventListener('click', function() {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Navigation
    document.querySelector('.wd-lightbox-prev').addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
        showLightbox(visibleItems[currentIndex]);
    });

    document.querySelector('.wd-lightbox-next').addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % visibleItems.length;
        showLightbox(visibleItems[currentIndex]);
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightbox.style.display === 'flex') {
            if (e.key === 'Escape') {
                lightbox.style.display = 'none';
                document.body.style.overflow = 'auto';
            } else if (e.key === 'ArrowLeft') {
                document.querySelector('.wd-lightbox-prev').click();
            } else if (e.key === 'ArrowRight') {
                document.querySelector('.wd-lightbox-next').click();
            }
        }
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
