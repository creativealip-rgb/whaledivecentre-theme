<?php
/**
 * Generic page template - renders page content with shortcodes
 */
get_header();
?>
<main class="wd-page-content">
<?php
while (have_posts()) : the_post();
    the_content();
endwhile;
?>
</main>
<?php get_footer(); ?>