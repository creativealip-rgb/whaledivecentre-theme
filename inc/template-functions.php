<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Contenly_Theme
 * @since 1.0.0
 */

/**
 * Filter the archive title.
 */
function contenly_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = get_the_author();
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'contenly_archive_title' );

/**
 * Add custom image sizes to media library
 */
function contenly_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'contenly-small'  => __( 'Contenly Small', 'contenly-theme' ),
        'contenly-medium' => __( 'Contenly Medium', 'contenly-theme' ),
        'contenly-large'  => __( 'Contenly Large', 'contenly-theme' ),
    ) );
}
add_filter( 'image_size_names_choose', 'contenly_custom_image_sizes' );
