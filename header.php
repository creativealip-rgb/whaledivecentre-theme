<?php
/**
 * Header - Whale Dive Centre (unified wd-header design)
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/jpeg" href="<?php echo esc_url(get_template_directory_uri() . '/assets/brand/favicon-192.png'); ?>">
<link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/brand/favicon-192.png'); ?>">
<?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>
