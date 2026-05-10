<?php
/**
 * Generic page template - renders page content with shortcodes
 */
get_header();
?>
<div class="wd-page-content" style="padding:40px 0;min-height:60vh;">
<?php
while (have_posts()) : the_post();
    the_content();
endwhile;
?>
</div>
<?php get_footer(); ?>
