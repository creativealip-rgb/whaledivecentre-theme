<?php
/**
 * Template Name: Blog Archive
 * Description: Blog/Articles archive page for Whale Dive Centre
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
<body <?php body_class('whaledive-blog'); ?>>

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
    <section class="wd-blog-hero">
        <div class="wd-blog-hero-content">
            <p class="wd-label">DIVE STORIES & OCEAN NOTES</p>
            <h1>Articles from the crew and community.</h1>
            <p class="wd-subtitle">Dive tips, gear guides, safety notes, marine life stories, and conservation updates from Whale Dive Centre.</p>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="wd-section white wd-blog-grid">
        <div class="wd-container">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                'paged' => $paged,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $blog_query = new WP_Query($args);

            if ($blog_query->have_posts()) :
                echo '<div class="wd-blog-cards">';
                while ($blog_query->have_posts()) : $blog_query->the_post();
                    $categories = get_the_category();
                    $cat_label = !empty($categories) ? esc_html($categories[0]->name) : 'ARTICLE';
                    ?>
                    <article class="wd-blog-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="wd-blog-card-image">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="wd-blog-card-content">
                            <span class="wd-blog-card-category"><?php echo strtoupper($cat_label); ?></span>
                            <h3><?php the_title(); ?></h3>
                            <p class="wd-blog-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <div class="wd-blog-card-meta">
                                <span><?php echo get_the_date('d M Y'); ?></span>
                                <a href="<?php the_permalink(); ?>" class="wd-blog-card-link">Read article →</a>
                            </div>
                        </div>
                    </article>
                <?php
                endwhile;
                echo '</div>';

                // Pagination
                if ($blog_query->max_num_pages > 1) :
                    echo '<div class="wd-blog-pagination">';
                    echo paginate_links(array(
                        'total' => $blog_query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →',
                    ));
                    echo '</div>';
                endif;

                wp_reset_postdata();
            else :
                echo '<div class="wd-blog-empty">';
                echo '<p>No articles yet. Check back soon for dive stories and ocean notes.</p>';
                echo '</div>';
            endif;
            ?>
        </div>
    </section>

    <!-- Footer -->
    <?php get_template_part('template-parts/footer'); ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
