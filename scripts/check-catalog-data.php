<?php
/**
 * Quick production data QA for WDC catalog posts.
 * Usage: php scripts/check-catalog-data.php /path/to/wordpress
 */

$wp_root = $argv[1] ?? dirname(__DIR__, 4);
$wp_load = rtrim($wp_root, '/\\') . '/wp-load.php';

if (!file_exists($wp_load)) {
    fwrite(STDERR, "wp-load.php not found. Pass the WordPress root path.\n");
    exit(1);
}

require $wp_load;

$issues = [];

function wdc_catalog_check_posts($post_type, $required_meta, &$issues) {
    if (!post_type_exists($post_type)) {
        $issues[] = "$post_type post type is missing";
        return;
    }

    $posts = get_posts([
        'post_type' => $post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    if (!$posts) {
        $issues[] = "$post_type has no published posts";
        return;
    }

    foreach ($posts as $post) {
        foreach ($required_meta as $meta_key) {
            $value = get_post_meta($post->ID, $meta_key, true);
            if ($value === '') {
                $issues[] = "{$post_type} #{$post->ID} {$post->post_title}: missing {$meta_key}";
            }
        }
    }
}

wdc_catalog_check_posts('wm_course', ['_wm_price', '_wm_duration'], $issues);
wdc_catalog_check_posts('wm_equipment', ['_wm_price', '_wm_stock'], $issues);

if (taxonomy_exists('course_level')) {
    $courses = get_posts(['post_type' => 'wm_course', 'post_status' => 'publish', 'numberposts' => -1]);
    foreach ($courses as $course) {
        if (!get_the_terms($course->ID, 'course_level')) {
            $issues[] = "wm_course #{$course->ID} {$course->post_title}: missing course_level term";
        }
    }
} else {
    $issues[] = 'course_level taxonomy is missing';
}

if ($issues) {
    echo "Catalog data issues:\n";
    foreach ($issues as $issue) {
        echo "- {$issue}\n";
    }
    exit(1);
}

echo "Catalog data OK.\n";
