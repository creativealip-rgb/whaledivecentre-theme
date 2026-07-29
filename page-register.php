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
    if(!is_email($email)) $errors[] = 'Valid email is required.';
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
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-register wdc-auth-page'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>
  <section class="wdc-auth-hero"><div class="wd-shell wdc-auth-grid"><div class="wdc-auth-copy"><a class="wdc-auth-back" href="/"><?php echo esc_html(contenly_tr('Back to Home', 'Back to Home')); ?></a><span class="wd-kicker"><?php echo esc_html(contenly_tr('Start your diver profile', 'Start your diver profile')); ?></span><h1><?php echo esc_html(contenly_tr('Join the crew.', 'Join the crew.')); ?></h1><p><?php echo esc_html(contenly_tr('Create an account to keep course planning, gear requests, certification notes, and dive updates in one place.', 'Create an account to keep course planning, gear requests, certification notes, and dive updates in one place.')); ?></p><div class="wdc-auth-proof"><div><b>Learn</b><span>Courses</span></div><div><b>Prepare</b><span>Gear</span></div><div><b>Track</b><span>Progress</span></div></div></div><aside class="wdc-auth-card"><h2><?php echo esc_html(contenly_tr('Create account', 'Create account')); ?></h2><p><?php echo esc_html(contenly_tr('Start with a simple member profile.', 'Start with a simple member profile.')); ?></p><?php if(!empty($errors)): ?><div class="wdc-auth-alert"><?php echo wp_kses_post(implode('<br>', $errors)); ?></div><?php endif; ?><form class="wdc-auth-form" method="post"><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Username', 'Username')); ?></label><input type="text" name="username" autocomplete="username" required></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Email', 'Email')); ?></label><input type="email" name="email" autocomplete="email" required></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Password', 'Password')); ?></label><div class="wd-password-wrap"><input id="wd-register-password" type="password" name="password" autocomplete="new-password" required><button class="wd-password-toggle" type="button" data-target="wd-register-password">Show</button></div></div><div class="wdc-auth-field"><label><?php echo esc_html(contenly_tr('Confirm Password', 'Confirm Password')); ?></label><div class="wd-password-wrap"><input id="wd-register-confirm" type="password" name="confirm_password" autocomplete="new-password" required><button class="wd-password-toggle" type="button" data-target="wd-register-confirm">Show</button></div></div><button class="wdc-auth-submit" name="register_submit" type="submit"><?php echo esc_html(contenly_tr('Create Account', 'Create Account')); ?></button></form><p class="wdc-auth-switch">Already have an account? <a href="/member-login/"><?php echo esc_html(contenly_tr('Log in', 'Log in')); ?></a></p></aside></div></section>
  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/contact/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/courses/open-water-diver/">Open Water</a><a href="/courses/advanced-open-water/">Advanced Open Water</a><a href="/courses/rescue-diver/">Rescue Diver</a><a href="/courses/divemaster/">Divemaster</a><a href="/courses/underwater-photography/">Photography</a></nav><div class="wd-footer-col"><h3>Contact</h3><p><?php echo esc_html(contenly_tr('Email', 'Email')); ?>: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener("click",function(e){var b=e.target.closest(".wd-password-toggle");if(!b)return;var i=document.getElementById(b.dataset.target);if(!i)return;var show=i.type==="password";i.type=show?"text":"password";b.textContent=show?"Hide":"Show";});</script><?php wp_footer(); ?></body></html>