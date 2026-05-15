<?php
/**
 * Template Name: Whale Dive Register
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('whaledive-inner whaledive-register'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img src="https://whaledivecentre.com/wp-content/themes/theme-travel-master/assets/logo.jpg" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/">Home</a><a href="/courses/">Courses</a><a href="/equipment/">Equipment</a><a href="/about/">About</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard - '.esc_html($u->display_name).'</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <section class="wd-inner-hero" style="min-height:520px;padding:150px 0 58px;color:#fff;background:linear-gradient(130deg,#021126 0%,#0a3d62 40%,#145374 100%)">
    <div class="wd-shell wd-auth-polish" style="max-width:520px;margin:0 auto;text-align:center">
      <div class="wd-auth-proof"><span>Start your diver profile</span><b>Training, equipment, and order updates in one place.</b></div>
      <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:48px;margin:0 0 12px">Create Diver Profile</h1>
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
            wp_redirect(home_url('/member-dashboard/'));
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
        <button type="submit" name="register_submit" style="width:100%;height:52px;border:0;border-radius:999px;background:linear-gradient(135deg,#3B44AC,#004A98);color:#fff;font-weight:900;font-size:15px;cursor:pointer">Create Diver Profile</button>
      </form>
      
      <p style="margin-top:24px;color:rgba(255,255,255,.7);font-size:14px">
        Already have an account? <a href="/member-login/" style="color:#4CC8ED;font-weight:700">Log in</a>
      </p>
      <p style="margin-top:14px">
        <a href="/" style="display:inline-flex;align-items:center;gap:8px;color:#fff;font-size:14px;font-weight:800;text-decoration:none;border:1px solid rgba(255,255,255,.24);background:rgba(255,255,255,.1);border-radius:999px;padding:10px 16px">← Back to Home</a>
      </p>
    </div>
  </section>
</main>

<footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/about/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Facebook">FB</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">IG</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="YouTube">YT</a><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="TikTok">TT</a></div></div></div><div class="wd-footer-bottom"><span>&copy; <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
<?php wp_footer(); ?>
</body></html>