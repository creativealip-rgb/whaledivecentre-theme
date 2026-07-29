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
  <?php contenly_render_public_header(); ?>
