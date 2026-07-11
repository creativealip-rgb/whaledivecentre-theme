<?php
/**
 * Template Name: Whale Dive Login
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
</head>
<body <?php body_class('whaledive-inner whaledive-login wdc-auth-page'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <section class="wdc-auth-hero"><div class="wd-shell wdc-auth-grid"><div class="wdc-auth-copy"><a class="wdc-auth-back" href="/"><?php echo esc_html(contenly_tr('Kembali ke Beranda', 'Back to Home')); ?></a><span class="wd-kicker"><?php echo esc_html(contenly_tr('Akses Member', 'Member Access')); ?></span><h1><?php echo esc_html(contenly_tr('Selamat datang kembali.', 'Welcome back.')); ?></h1><p><?php echo esc_html(contenly_tr('Masuk untuk kelola progres kursus, permintaan gear, sertifikasi, dan update crew dari satu dashboard.', 'Log in to manage course progress, gear requests, certifications, and crew updates from one calm dashboard.')); ?></p><div class="wdc-auth-proof"><div><b><?php echo contenly_tr('Kursus', 'Course'); ?></b><span><?php echo contenly_tr('Tracking', 'Tracking'); ?></span></div><div><b><?php echo contenly_tr('Gear', 'Gear'); ?></b><span><?php echo contenly_tr('Permintaan', 'Requests'); ?></span></div><div><b><?php echo contenly_tr('Crew', 'Crew'); ?></b><span><?php echo contenly_tr('Update', 'Updates'); ?></span></div></div></div><aside class="wdc-auth-card"><h2><?php echo esc_html(contenly_tr('Masuk', 'Log in')); ?></h2><p><?php echo esc_html(contenly_tr('Gunakan detail akun Whale Dive Centre.', 'Use your Whale Dive Centre account details.')); ?></p><?php if(isset($_GET['login']) && $_GET['login'] === 'failed'): ?><div class="wdc-auth-alert" role="alert"><?php echo contenly_tr('Login gagal. Cek username/email dan password, lalu coba lagi.', 'Login failed. Check username/email and password, then try again.'); ?></div><?php endif; ?><form class="wdc-auth-form" method="post" action="<?php echo esc_url(home_url('/wp-login.php')); ?>"><?php
$login_redirect = home_url('/dashboard/');
if (!empty($_GET['redirect_to'])) {
    $candidate = wp_unslash($_GET['redirect_to']);
    $candidate = is_string($candidate) ? rawurldecode($candidate) : '';
    // Allow only same-site absolute or relative paths.
    if ($candidate !== '') {
        $login_redirect = wp_validate_redirect($candidate, home_url('/dashboard/'));
    }
} elseif (!empty($_GET['next'])) {
    $login_redirect = home_url('/' . sanitize_title(wp_unslash($_GET['next'])) . '/');
}
?>
<input type="hidden" name="redirect_to" value="<?php echo esc_attr($login_redirect); ?>"><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Email atau username', 'Email or username')); ?></label><input type="text" name="log" autocomplete="username" required></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Kata Sandi', 'Password')); ?></label><div class="wd-password-wrap"><input id="wd-login-password" type="password" name="pwd" autocomplete="current-password" required><button class="wd-password-toggle" type="button" data-target="wd-login-password"><?php echo contenly_tr('Tampilkan', 'Show'); ?></button></div></div><div class="wdc-auth-row"><label><input type="checkbox" name="rememberme" value="forever"> <?php echo esc_html(contenly_tr('Ingat saya', 'Remember me')); ?></label><a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php echo contenly_tr('Lupa?', 'Forgot?'); ?></a></div><button class="wdc-auth-submit" type="submit"><?php echo esc_html(contenly_tr('Masuk', 'Log In')); ?></button></form><p class="wdc-auth-switch"><?php echo contenly_tr('Belum punya akun?', 'Don\'t have an account?'); ?> <a href="/register/"><?php echo esc_html(contenly_tr('Buat akun', 'Create one')); ?></a></p></aside></div></section>
<script>document.addEventListener("click",function(e){var b=e.target.closest(".wd-password-toggle");if(!b)return;var i=document.getElementById(b.dataset.target);if(!i)return;var show=i.type==="password";i.type=show?"text":"password";b.textContent=show?"Hide":"Show";});</script>
</main>
<?php wp_footer(); ?></body></html>
