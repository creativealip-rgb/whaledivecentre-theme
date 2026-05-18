<?php
/**
 * Template Name: Single Post
 * Description: Single blog post template for Whale Dive Centre
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
<body <?php body_class('whaledive-single-post'); ?>>

<main class="wd-page">
    <!-- Header/Navbar -->
    <header class="wd-header">
        <a href="<?php echo home_url('/'); ?>" class="wd-brand">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.jpg" alt="Whale Dive Centre">
            <span>WHALE DIVE CENTRE</span>
        </a>
        <nav class="wd-menu">
            <a href="<?php echo home_url('/'); ?>#membership">MEMBERSHIP</a>
            <a href="<?php echo home_url('/courses/'); ?>">Courses</a>
            <a href="<?php echo home_url('/equipment/'); ?>">Equipment</a>
            <a href="<?php echo home_url('/trips/'); ?>">DIVE TRIPS</a>
            <a href="<?php echo home_url('/gallery/'); ?>">GALLERY</a>
            <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
            <a href="<?php echo home_url('/our-crew/'); ?>">OUR CREW</a>
            <a href="<?php echo home_url('/faq/'); ?>">FAQ</a>
            <a href="<?php echo home_url('/contact/'); ?>">CONTACT</a>
        </nav>
    </header>

    <?php while (have_posts()) : the_post(); ?>
        <!-- Article Header -->
        <article class="wd-single-article">
            <div class="wd-single-header">
                <div class="wd-container-narrow">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) :
                        echo '<span class="wd-single-category">' . strtoupper(esc_html($categories[0]->name)) . '</span>';
                    endif;
                    ?>
                    <h1><?php the_title(); ?></h1>
                    <div class="wd-single-meta">
                        <span><?php echo get_the_date('d F Y'); ?></span>
                        <span>•</span>
                        <span><?php echo get_the_author(); ?></span>
                        <span>•</span>
                        <span><?php echo ceil(str_word_count(get_the_content()) / 200); ?> min read</span>
                    </div>
                </div>
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <div class="wd-single-featured-image">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="wd-single-content">
                <div class="wd-container-narrow">
                    <?php the_content(); ?>
                </div>
            </div>

            <!-- Article Footer -->
            <div class="wd-single-footer">
                <div class="wd-container-narrow">
                    <div class="wd-single-tags">
                        <?php
                        $tags = get_the_tags();
                        if ($tags) :
                            echo '<span class="wd-tags-label">Tags:</span>';
                            foreach ($tags as $tag) :
                                echo '<a href="' . get_tag_link($tag->term_id) . '" class="wd-tag">' . esc_html($tag->name) . '</a>';
                            endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="wd-single-nav">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        <?php if ($prev_post) : ?>
                            <a href="<?php echo get_permalink($prev_post); ?>" class="wd-single-nav-prev">
                                <span>← Previous</span>
                                <strong><?php echo get_the_title($prev_post); ?></strong>
                            </a>
                        <?php endif; ?>
                        <?php if ($next_post) : ?>
                            <a href="<?php echo get_permalink($next_post); ?>" class="wd-single-nav-next">
                                <span>Next →</span>
                                <strong><?php echo get_the_title($next_post); ?></strong>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="wd-single-back">
                        <a href="<?php echo home_url('/blog/'); ?>" class="wd-btn-secondary">← Back to all articles</a>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>

    <!-- Footer -->
    <?php get_template_part('template-parts/footer'); ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
