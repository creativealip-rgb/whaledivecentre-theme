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
    if(!$username) $errors[] = 'Username is required.';
    if(!is_email($email)) $errors[] = 'Valid email is required.';
    if(username_exists($username)) $errors[] = 'Username already exists.';
    if(email_exists($email)) $errors[] = 'Email already registered.';
    if($pass !== $confirm) $errors[] = 'Passwords do not match.';
    if(strlen($pass) < 6) $errors[] = 'Password must be at least 6 characters.';
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
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member is-active" aria-current="page">Login</a>'; } ?></nav></div></div></header>
  <section class="wdc-auth-hero"><div class="wd-shell wdc-auth-grid"><div class="wdc-auth-copy"><a class="wdc-auth-back" href="/">← Back to Home</a><span class="wd-kicker">Start your diver profile</span><h1>Join the crew.</h1><p>Create an account to keep course planning, equipment requests, certification notes, and dive updates in one place.</p><div class="wdc-auth-proof"><div><b>Learn</b><span>Courses</span></div><div><b>Prepare</b><span>Gear</span></div><div><b>Track</b><span>Progress</span></div></div></div><aside class="wdc-auth-card"><h2>Create account</h2><p>Start with a simple member profile.</p><?php if(!empty($errors)): ?><div class="wdc-auth-alert"><?php echo wp_kses_post(implode('<br>', $errors)); ?></div><?php endif; ?><form class="wdc-auth-form" method="post"><div class="wdc-auth-field"><label>Username</label><input type="text" name="username" autocomplete="username" required></div><div class="wdc-auth-field"><label>Email</label><input type="email" name="email" autocomplete="email" required></div><div class="wdc-auth-field"><label>Password</label><div class="wd-password-wrap"><input id="wd-register-password" type="password" name="password" autocomplete="new-password" required><button class="wd-password-toggle" type="button" data-target="wd-register-password">Show</button></div></div><div class="wdc-auth-field"><label>Confirm password</label><div class="wd-password-wrap"><input id="wd-register-confirm" type="password" name="confirm_password" autocomplete="new-password" required><button class="wd-password-toggle" type="button" data-target="wd-register-confirm">Show</button></div></div><button class="wdc-auth-submit" name="register_submit" type="submit">Create Account</button></form><p class="wdc-auth-switch">Already have an account? <a href="/member-login/">Log in</a></p></aside></div></section>
  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/contact/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/courses/open-water-diver/">Open Water</a><a href="/courses/advanced-open-water/">Advanced Open Water</a><a href="/courses/rescue-diver/">Rescue Diver</a><a href="/courses/divemaster/">Divemaster</a><a href="/courses/underwater-photography/">Photography</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener("click",function(e){var b=e.target.closest(".wd-password-toggle");if(!b)return;var i=document.getElementById(b.dataset.target);if(!i)return;var show=i.type==="password";i.type=show?"text":"password";b.textContent=show?"Hide":"Show";});</script><?php wp_footer(); ?></body></html>