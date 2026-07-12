<?php
/**
 * 404 template — bilingual public chrome.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
<style id="wdc-404-page">
body.error404 .wdc-404{
  min-height:calc(100vh - 220px);
  display:grid;
  align-items:center;
  padding:72px 0 88px;
  background:
    radial-gradient(circle at 12% 0, rgba(76,200,237,.18),transparent 34%),
    linear-gradient(180deg,#f4fbff 0%,#eef7fb 52%,#ffffff 100%);
}
body.error404 .wdc-404-card{
  max-width:720px;
  margin:0 auto;
  text-align:center;
  background:#fff;
  border:1px solid rgba(0,74,152,.10);
  border-radius:24px;
  box-shadow:0 18px 44px rgba(3,36,58,.08);
  padding:42px 28px 36px;
}
body.error404 .wdc-404-code{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  min-width:88px;
  height:42px;
  padding:0 16px;
  border-radius:999px;
  background:rgba(0,74,152,.08);
  color:#004A98;
  font-size:14px;
  font-weight:800;
  letter-spacing:.08em;
  margin:0 0 18px;
}
body.error404 .wdc-404 h1{
  margin:0 0 12px;
  color:#0b1930;
  font-size:clamp(30px,4vw,42px);
  line-height:1.12;
  letter-spacing:-.03em;
}
body.error404 .wdc-404 p{
  margin:0 auto 24px;
  max-width:46ch;
  color:#5b7180;
  font-size:16px;
  line-height:1.65;
}
body.error404 .wdc-404-actions{
  display:flex;
  flex-wrap:wrap;
  gap:12px;
  justify-content:center;
}
body.error404 .wdc-404-actions .wd-btn{
  min-height:44px;
  padding:0 20px;
  border-radius:999px;
  font-weight:800;
}
body.error404 .wdc-404-actions .wd-btn.alt{
  background:#4CC8ED!important;
  color:#000!important;
  -webkit-text-fill-color:#000!important;
  border:1px solid #4CC8ED!important;
}
@media(max-width:640px){
  body.error404 .wdc-404{padding:48px 0 56px}
  body.error404 .wdc-404-card{padding:30px 18px 26px;border-radius:20px}
  body.error404 .wdc-404-actions{display:grid;grid-template-columns:1fr}
  body.error404 .wdc-404-actions .wd-btn{width:100%}
}
</style>
</head>
<body <?php body_class('whaledive-inner wdc-404-page'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php if (function_exists('contenly_render_public_header')) { contenly_render_public_header(); } ?>

  <section class="wdc-404">
    <div class="wd-shell">
      <div class="wdc-404-card">
        <div class="wdc-404-code">404</div>
        <h1><?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Halaman tidak ditemukan', 'Page not found') : 'Page not found'); ?></h1>
        <p><?php echo esc_html(function_exists('contenly_tr')
          ? contenly_tr(
              'Link ini tidak tersedia. Coba kembali ke beranda, buka katalog kursus, atau hubungi crew kalau kamu butuh bantuan.',
              'This page is unavailable. Head home, browse courses, or contact the crew if you need help.'
            )
          : 'This page is unavailable.'); ?></p>
        <div class="wdc-404-actions">
          <a class="wd-btn" href="<?php echo esc_url(function_exists('contenly_localized_url') ? contenly_localized_url('/home/') : home_url('/')); ?>">
            <?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Ke Beranda', 'Back to Home') : 'Back to Home'); ?>
          </a>
          <a class="wd-btn alt" href="<?php echo esc_url(function_exists('contenly_localized_url') ? contenly_localized_url('/courses/') : home_url('/courses/')); ?>">
            <?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Lihat Kursus', 'Explore Courses') : 'Explore Courses'); ?>
          </a>
          <a class="wd-btn alt" href="<?php echo esc_url(function_exists('contenly_localized_url') ? contenly_localized_url('/about/') : home_url('/about/')); ?>#contact-form">
            <?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Hubungi Crew', 'Contact Crew') : 'Contact Crew'); ?>
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php if (function_exists('contenly_render_public_footer')) { contenly_render_public_footer(); } ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
