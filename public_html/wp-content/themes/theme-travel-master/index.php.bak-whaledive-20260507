<?php
/**
 * The main template file
 *
 * @package Contenly_Theme
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="site-content">
        <div class="site-container">
            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header class="page-header"><meta charset="utf-8">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card-clean mb-4' ); ?>>
                        <header class="entry-header mb-3">
                            <?php
                            if ( is_singular() ) :
                                the_title( '<h1 class="entry-title text-gradient">', '</h1>' );
                            else :
                                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            endif;

                            if ( 'post' === get_post_type() ) :
                                ?>
                                <div class="entry-meta text-muted text-small mt-2">
                                    <?php
                                    contenly_posted_on();
                                    contenly_posted_by();
                                    ?>
                                </div>
                                <?php
                            endif;
                            ?>
                        </header>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail mb-3">
                                <?php the_post_thumbnail( 'contenly-medium' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            if ( is_singular() ) :
                                the_content();
                            else :
                                the_excerpt();
                            endif;
                            ?>
                        </div>

                        <footer class="entry-footer text-muted text-small mt-3">
                            <?php contenly_entry_footer(); ?>
                        </footer>
                    </article>
                    <?php

                endwhile;

                the_posts_navigation();

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
