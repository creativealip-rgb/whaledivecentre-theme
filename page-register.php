<?php
/**
 * Template Name: Whale Dive Register
 */
$errors = [];
if (isset($_POST['register_submit'])) {
    $username = sanitize_user($_POST['username'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$username) {
        $errors[] = contenly_tr('Username wajib diisi.', 'Username is required.');
    }
    if (!is_email($email)) {
        $errors[] = contenly_tr('Email valid wajib diisi.', 'Valid email is required.');
    }
    if (username_exists($username)) {
        $errors[] = contenly_tr('Username sudah terdaftar.', 'Username already exists.');
    }
    if (email_exists($email)) {
        $errors[] = contenly_tr('Email sudah terdaftar.', 'Email already registered.');
    }
    if ($pass !== $confirm) {
        $errors[] = contenly_tr('Password tidak sama.', 'Passwords do not match.');
    }
    if (strlen($pass) < 6) {
        $errors[] = contenly_tr('Password minimal 6 karakter.', 'Password must be at least 6 characters.');
    }
    if (empty($errors)) {
        $user_id = wp_create_user($username, $pass, $email);
        if (!is_wp_error($user_id)) {
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
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('whaledive-inner whaledive-register wdc-auth-page'); ?>>
<?php wp_body_open(); ?>
<main class="wd-page wdc-auth-main">
  <section class="wdc-auth-hero">
    <div class="wd-shell wdc-auth-grid">
      <div class="wdc-auth-copy">
        <a class="wdc-auth-back" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(contenly_tr('Kembali ke Beranda', 'Back to Home')); ?></a>
        <h1><?php echo esc_html(contenly_tr('Bergabung dengan crew.', 'Join the crew.')); ?></h1>
        <p><?php echo esc_html(contenly_tr('Buat akun untuk simpan perencanaan kursus, request gear, catatan sertifikasi, dan update dive di satu tempat.', 'Create an account to keep course planning, gear requests, certification notes, and dive updates in one place.')); ?></p>
        <div class="wdc-auth-proof">
          <div><b><?php echo esc_html(contenly_tr('Belajar', 'Learn')); ?></b><span><?php echo esc_html(contenly_tr('Kursus', 'Courses')); ?></span></div>
          <div><b><?php echo esc_html(contenly_tr('Persiapan', 'Prepare')); ?></b><span><?php echo esc_html(contenly_tr('Gear', 'Gear')); ?></span></div>
          <div><b><?php echo esc_html(contenly_tr('Lacak', 'Track')); ?></b><span><?php echo esc_html(contenly_tr('Progres', 'Progress')); ?></span></div>
        </div>
      </div>

      <aside class="wdc-auth-card">
        <div class="wdc-auth-card-head">
          <h2><?php echo esc_html(contenly_tr('Buat Akun', 'Create Account')); ?></h2>
          <p><?php echo esc_html(contenly_tr('Mulai dengan profil member sederhana.', 'Start with a simple member profile.')); ?></p>
        </div>

        <?php if (!empty($errors)) : ?>
        <div class="wdc-auth-alert" role="alert"><?php echo wp_kses_post(implode('<br>', array_map('esc_html', $errors))); ?></div>
        <?php endif; ?>

        <form class="wdc-auth-form" method="post">
          <div class="wdc-auth-field">
            <label for="wd-reg-username"><?php echo esc_html(contenly_tr('Nama pengguna', 'Username')); ?></label>
            <input id="wd-reg-username" type="text" name="username" autocomplete="username" value="<?php echo esc_attr(wp_unslash($_POST['username'] ?? '')); ?>" required>
          </div>

          <div class="wdc-auth-field">
            <label for="wd-reg-email"><?php echo esc_html(contenly_tr('Email', 'Email')); ?></label>
            <input id="wd-reg-email" type="email" name="email" autocomplete="email" value="<?php echo esc_attr(wp_unslash($_POST['email'] ?? '')); ?>" required>
          </div>

          <div class="wdc-auth-field">
            <label for="wd-reg-password"><?php echo esc_html(contenly_tr('Kata sandi', 'Password')); ?></label>
            <div class="wd-password-wrap">
              <input id="wd-reg-password" type="password" name="password" autocomplete="new-password" required>
              <button class="wd-password-toggle" type="button" data-target="wd-reg-password" aria-label="Tampilkan/sembunyikan sandi" title="Tampilkan/sembunyikan sandi"><svg class="wd-eye wd-eye-open" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg><svg class="wd-eye wd-eye-off" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-5.94"/><path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></button>
            </div>
          </div>

          <div class="wdc-auth-field">
            <label for="wd-reg-confirm"><?php echo esc_html(contenly_tr('Konfirmasi kata sandi', 'Confirm password')); ?></label>
            <div class="wd-password-wrap">
              <input id="wd-reg-confirm" type="password" name="confirm_password" autocomplete="new-password" required>
              <button class="wd-password-toggle" type="button" data-target="wd-reg-confirm" aria-label="Tampilkan/sembunyikan sandi" title="Tampilkan/sembunyikan sandi"><svg class="wd-eye wd-eye-open" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg><svg class="wd-eye wd-eye-off" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-5.94"/><path d="M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.8 21.8 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></button>
            </div>
          </div>

          <button class="wdc-auth-submit" type="submit" name="register_submit" value="1"><?php echo esc_html(contenly_tr('Buat Akun', 'Create Account')); ?></button>
        </form>

        <p class="wdc-auth-switch"><?php echo esc_html(contenly_tr('Sudah punya akun?', 'Already have an account?')); ?> <a href="<?php echo esc_url(home_url('/login/')); ?>"><?php echo esc_html(contenly_tr('Masuk', 'Log in')); ?></a></p>
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
