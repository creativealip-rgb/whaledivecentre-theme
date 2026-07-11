<?php
/**
 * Custom template tags for this theme
 *
 * @package Contenly_Theme
 * @since 1.0.0
 */

if ( ! function_exists( 'contenly_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function contenly_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        
        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        echo '<span class="posted-on">' . $time_string . '</span>';
    }
endif;

if ( ! function_exists( 'contenly_posted_by' ) ) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function contenly_posted_by() {
        echo '<span class="byline"> <span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span></span>';
    }
endif;

if ( ! function_exists( 'contenly_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function contenly_entry_footer() {
        $categories_list = get_the_category_list( ', ' );
        if ( $categories_list ) {
            echo '<span class="cat-links">' . $categories_list . '</span>';
        }

        $tags_list = get_the_tag_list( '', ', ' );
        if ( $tags_list ) {
            echo '<span class="tags-links">' . $tags_list . '</span>';
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'contenly-theme' ),
                        array( 'span' => array( 'class' => array() ) )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'contenly-theme' ),
                    array( 'span' => array( 'class' => array() ) )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if ( ! function_exists( 'contenly_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     */
    function contenly_post_thumbnail( $size = 'contenly-medium' ) {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail( $size ); ?>
            </div>
            <?php
        else :
            ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( $size ); ?>
            </a>
            <?php
        endif;
    }
endif;
