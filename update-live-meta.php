<?php
// Update course meta on live
define('ABSPATH', '/home/whalediv/public_html/');
require_once(ABSPATH . 'wp-load.php');

$updates = [
  'open-water-scuba-diver' => ['_wm_price' => '5500000', '_wm_duration' => '3 days', '_wm_max_students' => '4', '_wm_prerequisites' => 'Swim comfort'],
  'advanced-open-water-diver' => ['_wm_price' => '5200000', '_wm_duration' => '2 days', '_wm_max_students' => '4', '_wm_prerequisites' => 'Open Water certification'],
  'rescue-scuba-diver' => ['_wm_price' => '6500000', '_wm_duration' => '3 days', '_wm_max_students' => '4', '_wm_prerequisites' => 'Advanced certification'],
  'divemaster' => ['_wm_price' => '18000000', '_wm_duration' => '4-6 weeks', '_wm_max_students' => '2', '_wm_prerequisites' => 'Rescue certification'],
  'instructor' => ['_wm_price' => '', '_wm_duration' => 'Varies', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'master-scuba-diver' => ['_wm_price' => '', '_wm_duration' => 'Varies', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'enriched-air-nitrox' => ['_wm_price' => '2800000', '_wm_duration' => 'Half day', '_wm_max_students' => '6', '_wm_prerequisites' => ''],
  'deep-diver' => ['_wm_price' => '', '_wm_duration' => '2-3 days', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'night-diver' => ['_wm_price' => '', '_wm_duration' => '1-2 days', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'diving-first-aid-for-professionals' => ['_wm_price' => '', '_wm_duration' => '1-2 days', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'full-face-mask-diver' => ['_wm_price' => '', '_wm_duration' => '1-2 days', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'junior-scuba-diver' => ['_wm_price' => '', '_wm_duration' => '3-4 days', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'trial-scuba' => ['_wm_price' => '', '_wm_duration' => '1 day', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'intro-to-tech' => ['_wm_price' => '4500000', '_wm_duration' => '2 days', '_wm_max_students' => '4', '_wm_prerequisites' => 'Open Water certification'],
  'nitrox-diver' => ['_wm_price' => '2800000', '_wm_duration' => '1 day', '_wm_max_students' => '6', '_wm_prerequisites' => 'Open Water certification'],
  'advanced-nitrox-diver' => ['_wm_price' => '5500000', '_wm_duration' => '2-3 days', '_wm_max_students' => '4', '_wm_prerequisites' => 'Nitrox certification'],
  'decompression-procedures-diver' => ['_wm_price' => '6500000', '_wm_duration' => '3 days', '_wm_max_students' => '4', '_wm_prerequisites' => 'Advanced Nitrox'],
  'cpr-first-aid-dan' => ['_wm_price' => '', '_wm_duration' => '1 day', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
  'emergency-oxygen-dan' => ['_wm_price' => '', '_wm_duration' => '1 day', '_wm_max_students' => '8', '_wm_prerequisites' => ''],
];

$level_map = [
  'open-water-scuba-diver' => 'beginner',
  'advanced-open-water-diver' => 'continued-education',
  'rescue-scuba-diver' => 'continued-education',
  'divemaster' => 'professional',
  'instructor' => 'professional',
  'master-scuba-diver' => 'continued-education',
  'enriched-air-nitrox' => 'specialty',
  'deep-diver' => 'specialty',
  'night-diver' => 'specialty',
  'diving-first-aid-for-professionals' => 'specialty',
  'full-face-mask-diver' => 'specialty',
  'junior-scuba-diver' => 'beginner',
  'trial-scuba' => 'beginner',
  'intro-to-tech' => 'specialty',
  'nitrox-diver' => 'specialty',
  'advanced-nitrox-diver' => 'advanced',
  'decompression-procedures-diver' => 'advanced',
  'cpr-first-aid-dan' => 'specialty',
  'emergency-oxygen-dan' => 'specialty',
];

$updated = 0;
$skipped = 0;

foreach ($updates as $slug => $meta) {
  $posts = get_posts(['post_type' => 'wm_course', 'name' => $slug, 'numberposts' => 1, 'post_status' => 'publish']);
  if (empty($posts)) {
    echo "SKIP: $slug (not found)\n";
    $skipped++;
    continue;
  }
  $post = $posts[0];
  
  foreach ($meta as $key => $value) {
    if ($value !== '') {
      update_post_meta($post->ID, $key, $value);
    } else {
      delete_post_meta($post->ID, $key);
    }
  }
  
  // Update level taxonomy
  if (isset($level_map[$slug])) {
    wp_set_object_terms($post->ID, $level_map[$slug], 'course_level');
  }
  
  echo "UPDATED: $slug (ID: $post->ID)\n";
  $updated++;
}

echo "\nDone: $updated updated, $skipped skipped\n";
