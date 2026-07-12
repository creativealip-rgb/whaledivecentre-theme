<?php
/**
 * Template Name: Whale Dive Login
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('whaledive-inner whaledive-login wdc-auth-page'); ?>>
<?php wp_body_open(); ?>
<main class="wd-page wdc-auth-main">
  <section class="wdc-auth-hero">
    <div class="wd-shell wdc-auth-grid">
      <div class="wdc-auth-copy">
        <a class="wdc-auth-back" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(contenly_tr('Kembali ke Beranda', 'Back to Home')); ?></a>
        <span class="wd-kicker"><?php echo esc_html(contenly_tr('Akses Member', 'Member Access')); ?></span>
        <h1><?php echo esc_html(contenly_tr('Selamat datang kembali.', 'Welcome back.')); ?></h1>
        <p><?php echo esc_html(contenly_tr('Masuk untuk kelola kursus, permintaan gear, sertifikasi, dan update crew dari satu dashboard.', 'Log in to manage courses, gear requests, certifications, and crew updates from one dashboard.')); ?></p>
        <div class="wdc-auth-proof">
          <div><b><?php echo esc_html(contenly_tr('Kursus', 'Course')); ?></b><span><?php echo esc_html(contenly_tr('Tracking', 'Tracking')); ?></span></div>
          <div><b><?php echo esc_html(contenly_tr('Gear', 'Gear')); ?></b><span><?php echo esc_html(contenly_tr('Permintaan', 'Requests')); ?></span></div>
          <div><b><?php echo esc_html(contenly_tr('Crew', 'Crew')); ?></b><span><?php echo esc_html(contenly_tr('Update', 'Updates')); ?></span></div>
        </div>
      </div>

      <aside class="wdc-auth-card">
        <div class="wdc-auth-card-head">
          <h2><?php echo esc_html(contenly_tr('Masuk', 'Log in')); ?></h2>
          <p><?php echo esc_html(contenly_tr('Gunakan akun Whale Dive Centre kamu.', 'Use your Whale Dive Centre account.')); ?></p>
        </div>

        <?php if (isset($_GET['login']) && $_GET['login'] === 'failed') : ?>
        <div class="wdc-auth-alert" role="alert"><?php echo esc_html(contenly_tr('Login gagal. Cek email/username dan password, lalu coba lagi.', 'Login failed. Check email/username and password, then try again.')); ?></div>
        <?php endif; ?>

        <?php
        $login_redirect = home_url('/dashboard/');
        if (!empty($_GET['redirect_to'])) {
            $candidate = wp_unslash($_GET['redirect_to']);
            $candidate = is_string($candidate) ? rawurldecode($candidate) : '';
            if ($candidate !== '') {
                $login_redirect = wp_validate_redirect($candidate, home_url('/dashboard/'));
            }
        } elseif (!empty($_GET['next'])) {
            $login_redirect = home_url('/' . sanitize_title(wp_unslash($_GET['next'])) . '/');
        }
        ?>

        <form class="wdc-auth-form" method="post" action="<?php echo esc_url(home_url('/wp-login.php')); ?>">
          <input type="hidden" name="redirect_to" value="<?php echo esc_attr($login_redirect); ?>">

          <div class="wdc-auth-field">
            <label for="wd-login-user"><?php echo esc_html(contenly_tr('Email atau username', 'Email or username')); ?></label>
            <input id="wd-login-user" type="text" name="log" autocomplete="username" required>
          </div>

          <div class="wdc-auth-field">
            <label for="wd-login-password"><?php echo esc_html(contenly_tr('Kata sandi', 'Password')); ?></label>
            <div class="wd-password-wrap">
              <input id="wd-login-password" type="password" name="pwd" autocomplete="current-password" required>
              <button class="wd-password-toggle" type="button" data-target="wd-login-password" aria-label="Tampilkan/sembunyikan sandi" title="Tampilkan/sembunyikan sandi"><svg class="wd-eye wd-eye-open" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg><svg class="wd-eye wd-eye-off" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-5.94"/><path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></button>
            </div>
          </div>

          <div class="wdc-auth-row">
            <label class="wdc-auth-check">
              <input type="checkbox" name="rememberme" value="forever">
              <span><?php echo esc_html(contenly_tr('Ingat saya', 'Remember me')); ?></span>
            </label>
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php echo esc_html(contenly_tr('Lupa password?', 'Forgot password?')); ?></a>
          </div>

          <button class="wdc-auth-submit" type="submit"><?php echo esc_html(contenly_tr('Masuk', 'Log In')); ?></button>
        </form>

        <p class="wdc-auth-switch"><?php echo esc_html(contenly_tr('Belum punya akun?', "Don't have an account?")); ?> <a href="<?php echo esc_url(home_url('/register/')); ?>"><?php echo esc_html(contenly_tr('Buat akun', 'Create one')); ?></a></p>
      </aside>
    </div>
  </section>
</main>
<script>
document.addEventListener('click', function (e) {
  var b = e.target.closest('.wd-password-toggle');
  if (!b) return;
  var i = document.getElementById(b.dataset.target);
  if (!i) return;
  var show = i.type === 'password';
  i.type = show ? 'text' : 'password';
  b.classList.toggle('is-visible', show);
  b.setAttribute('aria-pressed', show ? 'true' : 'false');
});
</script>
<?php wp_footer(); ?>
</body>
</html>
