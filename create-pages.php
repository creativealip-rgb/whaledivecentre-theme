<?php
/**
 * Create WordPress pages for new features
 * Run this once via browser: https://whaledivecentre.com/wp-content/themes/whaledivecentre-theme/create-pages.php
 * Then delete this file
 */

// Load WordPress
require_once('../../../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('Unauthorized');
}

$pages_created = [];

// Blog page
$blog_page = array(
    'post_title'    => 'Blog',
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'page',
    'post_name'     => 'blog'
);
$blog_id = wp_insert_post($blog_page);
if ($blog_id) {
    $pages_created[] = "Blog (ID: $blog_id)";
}

// Gallery page
$gallery_page = array(
    'post_title'    => 'Gallery',
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'page',
    'post_name'     => 'gallery'
);
$gallery_id = wp_insert_post($gallery_page);
if ($gallery_id) {
    $pages_created[] = "Gallery (ID: $gallery_id)";
}

// Testimonials page
$testimonials_page = array(
    'post_title'    => 'Testimonials',
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'page',
    'post_name'     => 'testimonials'
);
$testimonials_id = wp_insert_post($testimonials_page);
if ($testimonials_id) {
    $pages_created[] = "Testimonials (ID: $testimonials_id)";
}

// Dive Trips page
$trips_page = array(
    'post_title'    => 'Dive Trips',
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'page',
    'post_name'     => 'trips'
);
$trips_id = wp_insert_post($trips_page);
if ($trips_id) {
    $pages_created[] = "Dive Trips (ID: $trips_id)";
}

// Flush rewrite rules
flush_rewrite_rules();

echo "<h1>Pages Created Successfully!</h1>";
echo "<ul>";
foreach ($pages_created as $page) {
    echo "<li>$page</li>";
}
echo "</ul>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Delete this file (create-pages.php)</li>";
echo "<li>Visit <a href='/blog/'>Blog</a>, <a href='/gallery/'>Gallery</a>, <a href='/testimonials/'>Testimonials</a>, <a href='/trips/'>Dive Trips</a></li>";
echo "<li>Update navbar to include new links</li>";
echo "</ol>";
?>
