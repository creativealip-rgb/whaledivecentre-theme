<?php
/**
 * Template Name: Whale Dive Login
 */
get_header();
?>

<main class="wd-page">
  <section class="wd-inner-hero" style="min-height:520px;padding:150px 0 58px;color:#fff;background:linear-gradient(130deg,#021126 0%,#0a3d62 40%,#145374 100%)">
    <div class="wd-shell" style="max-width:480px;margin:0 auto;text-align:center">
      <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:48px;margin:0 0 12px">Welcome Back</h1>
      <p style="font-size:17px;color:rgba(255,255,255,.82);margin:0 0 32px">Log in to manage your courses, equipment, and certifications.</p>
      
      <?php if(isset($_GET['login']) && $_GET['login'] === 'failed'): ?>
        <p style="color:#f39c12;margin-bottom:16px">Login failed. Please check your credentials.</p>
      <?php endif; ?>
      
      <form method="post" action="<?php echo wp_login_url(); ?>" style="text-align:left">
        <div style="margin-bottom:16px">
          <label style="display:block;font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.78);margin-bottom:6px">Email or Username</label>
          <input type="text" name="log" required style="width:100%;height:48px;border:0;border-radius:16px;padding:0 14px;background:rgba(255,255,255,.96);color:#0b1930;box-sizing:border-box">
        </div>
        <div style="margin-bottom:16px">
          <label style="display:block;font-size:11px;font-weight:900;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.78);margin-bottom:6px">Password</label>
          <input type="password" name="pwd" required style="width:100%;height:48px;border:0;border-radius:16px;padding:0 14px;background:rgba(255,255,255,.96);color:#0b1930;box-sizing:border-box">
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
          <label style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.78);font-size:14px">
            <input type="checkbox" name="rememberme" value="forever"> Remember me
          </label>
          <a href="<?php echo wp_lostpassword_url(); ?>" style="color:var(--cyan);font-size:14px">Forgot password?</a>
        </div>
        <button type="submit" style="width:100%;height:52px;border:0;border-radius:999px;background:linear-gradient(135deg,var(--violet),var(--blue));color:#fff;font-weight:900;font-size:15px;cursor:pointer">Log In</button>
      </form>
      
      <p style="margin-top:24px;color:rgba(255,255,255,.7);font-size:14px">
        Don't have an account? <a href="/member-register/" style="color:var(--cyan);font-weight:700">Create one</a>
      </p>
    </div>
  </section>
</main>

<?php get_footer(); ?>