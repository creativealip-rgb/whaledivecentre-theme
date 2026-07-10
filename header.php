<?php
/**
 * Header - Whale Dive Centre
 */
$request_path = trailingslashit(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$nav_items = [
    ['slug' => 'home', 'href' => '/', 'id' => 'Beranda', 'en' => 'Home'],
    ['slug' => 'courses', 'href' => '/courses/', 'id' => 'Kursus', 'en' => 'Courses'],
    ['slug' => 'equipment', 'href' => '/equipment/', 'id' => 'Peralatan', 'en' => 'Equipment'],
    ['slug' => 'about', 'href' => '/about/', 'id' => 'Tentang', 'en' => 'About'],
    ['slug' => 'blog', 'href' => '/blog/', 'id' => 'Blog', 'en' => 'Blog'],
];
$nav_html = '';
foreach ($nav_items as $item) {
    $is_active = ('home' === $item['slug'] && ('/' === $request_path || '/index.php' === $request_path))
        || ('home' !== $item['slug'] && 0 === strpos($request_path, $item['href']));
    $active_attr = $is_active ? ' class="is-active" aria-current="page"' : '';
    $nav_html .= '<a href="' . esc_url($item['href']) . '" data-nav="' . esc_attr($item['slug']) . '"' . $active_attr . '>' . esc_html(contenly_tr($item['id'], $item['en'])) . '</a>';
}
$lang_switcher = contenly_render_language_switcher('wd-lang-switcher');
$member_link = is_user_logged_in()
    ? '<a href="' . esc_url(contenly_localized_url('/member-dashboard/')) . '" class="wd-nav-member">' . esc_html(contenly_tr('Dashboard', 'Dashboard')) . '</a>'
    : '<a href="' . esc_url(contenly_localized_url('/member-login/')) . '" class="wd-nav-member">' . esc_html(contenly_tr('Masuk', 'Login')) . '</a>';
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
<header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><?php echo $nav_html . $lang_switcher . $member_link; ?></nav></div></div></header>
