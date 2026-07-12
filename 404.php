<?php
/**
 * 404 template — bilingual, clean public chrome.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
<style id="wdc-404-page">
/* Fixed header offset + clean vertical stack */
html body.error404,
html body.wdc-404-page{
  margin:0;
  background:#f5fbff;
}
html body.error404 .wd-page,
html body.wdc-404-page .wd-page{
  overflow:visible;
  min-height:100vh;
  display:flex;
  flex-direction:column;
}
html body.error404 .wdc-404,
html body.wdc-404-page .wdc-404{
  flex:1 1 auto;
  display:flex;
  align-items:center;
  justify-content:center;
  width:100%;
  box-sizing:border-box;
  /* header fixed ~72-80px */
  padding:120px 0 64px;
  background:
    radial-gradient(circle at 18% 12%, rgba(76,200,237,.16), transparent 32%),
    radial-gradient(circle at 86% 8%, rgba(0,74,152,.08), transparent 28%),
    linear-gradient(180deg, #f7fcff 0%, #f3f8fc 55%, #ffffff 100%);
}
html body.error404 .wdc-404 .wd-shell,
html body.wdc-404-page .wdc-404 .wd-shell{
  width:100%;
  max-width:720px;
  margin:0 auto;
  padding:0 22px;
  box-sizing:border-box;
}
html body.error404 .wdc-404-card,
html body.wdc-404-page .wdc-404-card{
  width:100%;
  margin:0;
  text-align:center;
  background:#fff;
  border:1px solid rgba(0,74,152,.10);
  border-radius:28px;
  box-shadow:0 16px 40px rgba(3,36,58,.07);
  padding:40px 36px 36px;
  box-sizing:border-box;
}
html body.error404 .wdc-404-code,
html body.wdc-404-page .wdc-404-code{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  min-width:72px;
  height:36px;
  padding:0 14px;
  border-radius:999px;
  background:rgba(0,74,152,.08);
  color:#004A98;
  font-size:13px;
  font-weight:800;
  letter-spacing:.1em;
  margin:0 0 16px;
}
html body.error404 .wdc-404 h1,
html body.wdc-404-page .wdc-404 h1{
  margin:0 0 10px;
  color:#0b1930;
  font-size:clamp(28px,3.6vw,40px);
  line-height:1.15;
  letter-spacing:-.03em;
  font-weight:800;
}
html body.error404 .wdc-404 p,
html body.wdc-404-page .wdc-404 p{
  margin:0 auto 28px;
  max-width:40ch;
  color:#5b7180;
  font-size:15px;
  line-height:1.65;
}
html body.error404 .wdc-404-actions,
html body.wdc-404-page .wdc-404-actions{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  justify-content:center;
  align-items:center;
  margin:0;
}
html body.error404 .wdc-404-actions a,
html body.wdc-404-page .wdc-404-actions a{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  min-height:42px;
  padding:0 18px;
  border-radius:999px;
  font-size:14px;
  font-weight:700;
  line-height:1;
  text-decoration:none;
  box-sizing:border-box;
  transition:background .15s ease, color .15s ease, border-color .15s ease, transform .15s ease;
}
html body.error404 .wdc-404-actions a.primary,
html body.wdc-404-page .wdc-404-actions a.primary{
  background:#004A98;
  color:#fff;
  border:1px solid #004A98;
}
html body.error404 .wdc-404-actions a.primary:hover,
html body.wdc-404-page .wdc-404-actions a.primary:hover{
  background:#3B44AC;
  border-color:#3B44AC;
}
html body.error404 .wdc-404-actions a.secondary,
html body.wdc-404-page .wdc-404-actions a.secondary{
  background:#fff;
  color:#004A98;
  border:1px solid rgba(0,74,152,.22);
}
html body.error404 .wdc-404-actions a.secondary:hover,
html body.wdc-404-page .wdc-404-actions a.secondary:hover{
  border-color:#004A98;
  background:rgba(0,74,152,.04);
}
/* footer breathing room under fixed-feel pages */
html body.error404 .wd-footer,
html body.wdc-404-page .wd-footer{
  margin-top:0;
}
@media(max-width:760px){
  html body.error404 .wdc-404,
  html body.wdc-404-page .wdc-404{
    padding:100px 0 40px;
    align-items:flex-start;
  }
  html body.error404 .wdc-404 .wd-shell,
  html body.wdc-404-page .wdc-404 .wd-shell{
    padding:0 16px;
  }
  html body.error404 .wdc-404-card,
  html body.wdc-404-page .wdc-404-card{
    padding:28px 18px 24px;
    border-radius:22px;
  }
  html body.error404 .wdc-404 p,
  html body.wdc-404-page .wdc-404 p{
    margin-bottom:22px;
    font-size:14px;
  }
  html body.error404 .wdc-404-actions,
  html body.wdc-404-page .wdc-404-actions{
    display:grid;
    grid-template-columns:1fr;
    gap:10px;
  }
  html body.error404 .wdc-404-actions a,
  html body.wdc-404-page .wdc-404-actions a{
    width:100%;
    min-height:44px;
  }
}
</style>
</head>
<body <?php body_class('whaledive-inner wdc-404-page'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php if (function_exists('contenly_render_public_header')) { contenly_render_public_header(); } ?>

  <section class="wdc-404" aria-labelledby="wdc-404-title">
    <div class="wd-shell">
      <div class="wdc-404-card">
        <div class="wdc-404-code">404</div>
        <h1 id="wdc-404-title"><?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Halaman tidak ditemukan', 'Page not found') : 'Page not found'); ?></h1>
        <p><?php echo esc_html(function_exists('contenly_tr')
          ? contenly_tr(
              'Link ini tidak tersedia. Kembali ke beranda, buka katalog kursus, atau hubungi crew.',
              'This page is unavailable. Head home, browse courses, or contact the crew.'
            )
          : 'This page is unavailable.'); ?></p>
        <div class="wdc-404-actions">
          <a class="primary" href="<?php echo esc_url(function_exists('contenly_localized_url') ? contenly_localized_url('/home/') : home_url('/')); ?>">
            <?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Ke Beranda', 'Back to Home') : 'Back to Home'); ?>
          </a>
          <a class="secondary" href="<?php echo esc_url(function_exists('contenly_localized_url') ? contenly_localized_url('/courses/') : home_url('/courses/')); ?>">
            <?php echo esc_html(function_exists('contenly_tr') ? contenly_tr('Lihat Kursus', 'Explore Courses') : 'Explore Courses'); ?>
          </a>
          <a class="secondary" href="<?php echo esc_url(function_exists('contenly_localized_url') ? contenly_localized_url('/about/') : home_url('/about/')); ?>#contact-form">
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
