<?php
/**
 * Template Name: Whale Dive Register
 */
$errors = [];
if(isset($_POST['register_submit'])) {
    $username = sanitize_user($_POST['username'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if(!$username) $errors[] = contenly_tr('Username wajib diisi.', 'Username is required.');
    if(!is_email($email)) $errors[] = contenly_tr('Email valid wajib diisi.', 'Valid email is required.');
    if(username_exists($username)) $errors[] = contenly_tr('Username sudah terdaftar.', 'Username already exists.');
    if(email_exists($email)) $errors[] = contenly_tr('Email sudah terdaftar.', 'Email already registered.');
    if($pass !== $confirm) $errors[] = contenly_tr('Password tidak sama.', 'Passwords do not match.');
    if(strlen($pass) < 6) $errors[] = contenly_tr('Password minimal 6 karakter.', 'Password must be at least 6 characters.');
    if(empty($errors)) {
        $user_id = wp_create_user($username, $pass, $email);
        if(!is_wp_error($user_id)) {
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            wp_redirect(home_url('/dashboard/'));
            exit;
        }
        $errors[] = $user_id->get_error_message();
    }
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
</head>
<body <?php body_class('whaledive-inner whaledive-register wdc-auth-page'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <section class="wdc-auth-hero"><div class="wd-shell wdc-auth-grid"><div class="wdc-auth-copy"><a class="wdc-auth-back" href="/"><?php echo esc_html(contenly_tr('Kembali ke Beranda', 'Back to Home')); ?></a><span class="wd-kicker"><?php echo esc_html(contenly_tr('Mulai profil diver Anda', 'Start your diver profile')); ?></span><h1><?php echo esc_html(contenly_tr('Bergabung dengan crew.', 'Join the crew.')); ?></h1><p><?php echo esc_html(contenly_tr('Buat akun untuk menyimpan perencanaan kursus, permintaan gear, catatan sertifikasi, dan update dive di satu tempat.', 'Create an account to keep course planning, gear requests, certification notes, and dive updates in one place.')); ?></p><div class="wdc-auth-proof"><div><b><?php echo contenly_tr('Belajar', 'Learn'); ?></b><span><?php echo contenly_tr('Kursus', 'Courses'); ?></span></div><div><b><?php echo contenly_tr('Persiapan', 'Prepare'); ?></b><span><?php echo contenly_tr('Gear', 'Gear'); ?></span></div><div><b><?php echo contenly_tr('Lacak', 'Track'); ?></b><span><?php echo contenly_tr('Progres', 'Progress'); ?></span></div></div></div><aside class="wdc-auth-card"><h2><?php echo esc_html(contenly_tr('Buat Akun', 'Create Account')); ?></h2><p><?php echo esc_html(contenly_tr('Mulai dengan profil member sederhana.', 'Start with a simple member profile.')); ?></p><?php if(!empty($errors)): ?><div class="wdc-auth-alert"><?php echo wp_kses_post(implode('<br>', $errors)); ?></div><?php endif; ?><form class="wdc-auth-form" method="post"><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Nama Pengguna', 'Username')); ?></label><input type="text" name="username" autocomplete="username" required></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Surel', 'Email')); ?></label><input type="email" name="email" autocomplete="email" required></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Kata Sandi', 'Password')); ?></label><div class="wd-password-wrap"><input id="wd-register-password" type="password" name="password" autocomplete="new-password" required><button class="wd-password-toggle" type="button" data-target="wd-register-password"><?php echo contenly_tr('Tampilkan', 'Show'); ?></button></div></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Konfirmasi Kata Sandi', 'Confirm Password')); ?></label><div class="wd-password-wrap"><input id="wd-register-confirm" type="password" name="confirm_password" autocomplete="new-password" required><button class="wd-password-toggle" type="button" data-target="wd-register-confirm"><?php echo contenly_tr('Tampilkan', 'Show'); ?></button></div></div><button class="wdc-auth-submit" name="register_submit" type="submit"><?php echo esc_html(contenly_tr('Buat Akun', 'Create Account')); ?></button></form><p class="wdc-auth-switch"><?php echo contenly_tr('Sudah punya akun?', 'Already have an account?'); ?> <a href="/login/"><?php echo esc_html(contenly_tr('Masuk', 'Log in')); ?></a></p></aside></div></section>
<script>document.addEventListener("click",function(e){var b=e.target.closest(".wd-password-toggle");if(!b)return;var i=document.getElementById(b.dataset.target);if(!i)return;var show=i.type==="password";i.type=show?"text":"password";b.textContent=show?"Hide":"Show";});</script>
</main>
<?php wp_footer(); ?></body></html>
