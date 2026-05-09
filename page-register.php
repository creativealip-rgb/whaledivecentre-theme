<?php
/**
 * Template Name: Whale Dive Register
 */
get_header();
?>

<main class="wd-page">
  <section class="wd-inner-hero" style="min-height:520px;padding:150px 0 58px;color:#fff;background:linear-gradient(130deg,#021126 0%,#0a3d62 40%,#145374 100%)">
    <div class="wd-shell" style="max-width:480px;margin:0 auto;text-align:center">
      <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:48px;margin:0 0 12px">Join the Community</h1>
      <p style="font-size:17px;color:rgba(255,255,255,.82);margin:0 0 32px">Create an account to enroll in courses, track certifications, and manage gear.</p>
      
      <?php
      if(isset($_POST['register_submit'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $pass = $_POST['password'];
        $confirm = $_POST['confirm_password'];
        
        $errors = [];
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
          } else {
            $errors[] = $user_id->get_error_message();
          }
        }
        
        if(!empty($errors)) {
          echo '<p style="color:#f39c12;margin-bottom:16px">' . implode('<br>', $errors) . '</p>';
        }
      }
      ?>
      
      <form method="post" style="text-align:left">
        <div style="margin-bottom:16px">
          <label style="display:block;font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.78);margin-bottom:6px">Username</label>
          <input type="text" name="username" required style="width:100%;height:48px;border:0;border-radius:16px;padding:0 14px;background:rgba(255,255,255,.96);color:#0b1930;box-sizing:border-box">
        </div>
        <div style="margin-bottom:16px">
          <label style="display:block;font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.78);margin-bottom:6px">Email</label>
          <input type="email" name="email" required style="width:100%;height:48px;border:0;border-radius:16px;padding:0 14px;background:rgba(255,255,255,.96);color:#0b1930;box-sizing:border-box">
        </div>
        <div style="margin-bottom:16px">
          <label style="display:block;font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.78);margin-bottom:6px">Password</label>
          <input type="password" name="password" required style="width:100%;height:48px;border:0;border-radius:16px;padding:0 14px;background:rgba(255,255,255,.96);color:#0b1930;box-sizing:border-box">
        </div>
        <div style="margin-bottom:24px">
          <label style="display:block;font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.78);margin-bottom:6px">Confirm Password</label>
          <input type="password" name="confirm_password" required style="width:100%;height:48px;border:0;border-radius:16px;padding:0 14px;background:rgba(255,255,255,.96);color:#0b1930;box-sizing:border-box">
        </div>
        <button type="submit" name="register_submit" style="width:100%;height:52px;border:0;border-radius:999px;background:linear-gradient(135deg,var(--violet),var(--blue));color:#fff;font-weight:900;font-size:15px;cursor:pointer">Create Account</button>
      </form>
      
      <p style="margin-top:24px;color:rgba(255,255,255,.7);font-size:14px">
        Already have an account? <a href="/member-login/" style="color:var(--cyan);font-weight:700">Log in</a>
      </p>
    </div>
  </section>
</main>

<?php get_footer(); ?>