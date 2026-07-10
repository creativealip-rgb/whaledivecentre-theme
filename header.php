<?php
/**
 * Header - Whale Dive Centre (unified wd-header design)
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri() . '/assets/brand/favicon-192.png'); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_template_directory_uri() . '/assets/brand/favicon-32.png'); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(get_template_directory_uri() . '/assets/brand/favicon-16.png'); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri() . '/assets/brand/favicon-180.png'); ?>">
<?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="<?php echo esc_url(contenly_localized_url('/home/')); ?>" data-nav="home"><?php echo esc_html(contenly_tr('Beranda', 'Home')); ?></a><a href="<?php echo esc_url(contenly_localized_url('/courses/')); ?>" data-nav="courses"><?php echo esc_html(contenly_tr('Kursus', 'Courses')); ?></a><a href="<?php echo esc_url(contenly_localized_url('/equipment/')); ?>" data-nav="equipment"><?php echo esc_html(contenly_tr('Peralatan', 'Equipment')); ?></a><a href="<?php echo esc_url(contenly_localized_url('/about/')); ?>" data-nav="about"><?php echo esc_html(contenly_tr('Tentang', 'About')); ?></a><a href="<?php echo esc_url(contenly_localized_url('/blog/')); ?>" data-nav="blog">Blog</a><?php echo contenly_render_language_switcher('wd-lang-switcher'); ?><?php if(is_user_logged_in()){ echo '<a href="' . esc_url(contenly_localized_url('/member-dashboard/')) . '" class="wd-nav-member">' . esc_html(contenly_tr('Dashboard', 'Dashboard')) . '</a>'; } else { echo '<a href="' . esc_url(contenly_localized_url('/member-login/')) . '" class="wd-nav-member">' . esc_html(contenly_tr('Masuk', 'Login')) . '</a>'; } ?></nav></div></div></header>
