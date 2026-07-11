<?php
/**
 * Template Name: Whale Dive Courses
 */
$all_courses = get_posts([
    'post_type'   => 'wm_course',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
    'meta_query'  => [
        'relation' => 'OR',
        [
            'key'     => '_wdc_catalog_visible',
            'compare' => 'NOT EXISTS',
        ],
        [
            'key'     => '_wdc_catalog_visible',
            'value'   => '0',
            'compare' => '!=',
        ],
    ],
]);
$levels = get_terms(['taxonomy' => 'course_level', 'hide_empty' => true]);
$levels = is_wp_error($levels) ? [] : $levels;
$theme_uri = get_stylesheet_directory_uri();
function wdc_course_image_url($title, $theme_uri) {
    if (function_exists('wdc_course_asset_file')) {
        $file = wdc_course_asset_file($title);
        return $file ? (rtrim($theme_uri, '/') . '/assets/' . $file) : '';
    }
    $key = strtolower($title);
    $map = [
        'open water' => 'wdc-course-open-water-real.webp',
        'advanced' => 'wdc-course-advanced-open-water-real.webp',
        'rescue' => 'wdc-course-rescue-diver-real.webp',
        'divemaster' => 'wdc-course-divemaster-real.webp',
        'instructor' => 'wdc-course-instructor-course-real.webp',
        'nitrox' => 'wdc-course-nitrox.webp',
    ];
    foreach ($map as $needle => $file) {
        if (strpos($key, $needle) !== false) {
            return $theme_uri . '/assets/' . $file;
        }
    }
    return '';
}

?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-courses-equipment-copy">.whaledive-courses .wd-gear-note{display:flex;gap:12px;justify-content:center;align-items:center;flex-wrap:wrap;max-width:820px;margin:0 auto 22px;padding:14px 18px;border-radius:18px;background:#eef8fb;border:1px solid rgba(0,91,122,.1);color:#5b7180}.whaledive-courses .wd-gear-note b{color:#06384d}.whaledive-courses #courseGrid > article[data-visible="1"]{display:flex!important}.whaledive-courses #courseGrid > article[data-visible="0"]{display:none!important}.whaledive-courses .wd-gear-finder{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:0 0 28px}.whaledive-courses .wd-gear-finder span,.whaledive-courses .wd-gear-finder a{min-height:42px;display:inline-flex;align-items:center;border-radius:999px;padding:0 14px;font-weight:800;font-size:13px}.whaledive-courses .wd-gear-finder span{background:#06384d;color:#fff}.whaledive-courses .wd-gear-finder a{background:#fff;color:#0b617c;border:1px solid rgba(11,97,124,.16);text-decoration:none}.whaledive-courses .wd-mini-btn{background:#06384d!important;color:#fff!important}.whaledive-courses .wd-mini-link{border-color:rgba(11,97,124,.24)!important;color:#0b617c!important}.whaledive-courses .wd-equip-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0}.whaledive-courses .wd-equip-visual.has-photo:before{background:linear-gradient(180deg,rgba(3,23,45,.05),rgba(3,23,45,.55));z-index:1}.whaledive-courses .wd-equip-visual.has-photo:after{display:none!important;content:none!important}.whaledive-courses .wd-equip-visual.has-photo .wd-equip-type{background:rgba(3,23,45,.62);backdrop-filter:blur(8px)}.whaledive-courses #courseFilters .wd-chip{background:#fff!important;color:#06384d!important;border:1px solid #dde3ea!important;padding:11px 24px!important;border-radius:999px!important;font-size:14px!important;font-weight:700!important;letter-spacing:.01em!important;box-shadow:0 2px 8px rgba(2,21,43,.06)!important;transition:all .2s ease!important;cursor:pointer!important}.whaledive-courses #courseFilters .wd-chip:hover{background:#f7fbfd!important;border-color:#b8c4ce!important}.whaledive-courses #courseFilters .wd-chip.active{background:#06384d!important;color:#fff!important;border-color:#06384d!important;box-shadow:0 4px 14px rgba(6,56,77,.18)!important}.whaledive-courses #courseGrid.wd-page-grid{width:100%!important;max-width:none!important;margin-left:0!important;margin-right:0!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}.whaledive-courses .wd-course-card{border-radius:18px!important;overflow:hidden!important;background:#fff!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 14px 34px rgba(2,21,43,.07)!important;padding:0!important}.whaledive-courses .wd-course-card .wd-equip-visual{height:138px!important;min-height:0!important;border-radius:0!important;margin:0!important}.whaledive-courses .wd-course-card .wd-equip-type{top:10px!important;left:10px!important;padding:6px 9px!important;border-radius:999px!important;font-size:10px!important;letter-spacing:.08em!important}.whaledive-courses .wd-course-card .wd-equip-card-body{padding:14px!important;display:flex!important;flex-direction:column!important;min-height:238px!important}.whaledive-courses .wd-course-card .wd-course-meta{gap:6px!important;margin:0 0 4px!important}.whaledive-courses .wd-course-card .wd-course-meta span{padding:5px 8px!important;border-radius:999px!important;background:#eef8fb!important;color:#0b617c!important;font-size:10px!important;font-weight:900!important;line-height:1!important;letter-spacing:.02em!important}.whaledive-courses .wd-course-card h3{font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important}.whaledive-courses .wd-course-card .wd-equip-price{margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important}.whaledive-courses .wd-course-card .wd-price-label{display:block!important;font-size:11px!important;color:#6f7f8d!important;line-height:1.2!important;margin-bottom:4px!important}.whaledive-courses .wd-course-card .wd-price-amount{display:block!important;font-size:clamp(15px,1.2vw,17px)!important;font-weight:900!important;color:#06384d!important;line-height:1!important;white-space:nowrap!important;letter-spacing:-.02em!important}.whaledive-courses .wd-course-card .wd-equip-chips{gap:6px!important;margin:0 0 12px!important}.whaledive-courses .wd-course-card .wd-equip-chips span{padding:6px 8px!important;border-radius:999px!important;background:#f1fbff!important;color:#4f6575!important;font-size:10px!important;font-weight:800!important;line-height:1.1!important}.whaledive-courses .wd-course-card .wd-equip-actions{margin-top:8px!important;padding-top:0!important;border-top:0!important}.whaledive-courses .wd-course-card .wd-mini-link{min-height:34px!important;width:auto!important;padding:0 12px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.12)!important;color:#06384d!important}.whaledive-courses .wd-course-card .wd-mini-link:after{content:' →'}@media(max-width:980px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important}}@media(max-width:760px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}.whaledive-courses .wd-course-card .wd-equip-card-body{min-height:220px!important}}@media(max-width:540px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:1fr!important}.whaledive-courses .wd-course-card{display:grid!important;grid-template-columns:118px minmax(0,1fr)!important}.whaledive-courses .wd-course-card .wd-equip-visual{height:100%!important;min-height:178px!important}.whaledive-courses .wd-course-card .wd-equip-card-body{min-height:0!important;padding:12px!important}.whaledive-courses .wd-course-card h3{font-size:18px!important}}
.whaledive-courses #courseGrid .wd-course-card{min-height:0!important;height:auto!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-card-body{padding:14px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important}.whaledive-courses #courseGrid .wd-course-card h3{font-size:20px!important;line-height:1.08!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-desc{display:none!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-tags{gap:6px!important;margin:0 0 4px!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-tags span{padding:5px 8px!important;border-radius:999px!important;background:#eef8fb!important;color:#0b617c!important;font-size:10px!important;font-weight:900!important;line-height:1!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-price{margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-price-label{display:block!important;margin:0 0 4px!important;font-size:11px!important;line-height:1.2!important;color:#789!important}.whaledive-courses #courseGrid .wd-course-card .wd-price-amount{font-size:17px!important;line-height:1!important;color:#06384d!important;font-weight:900!important;white-space:nowrap!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-meta{gap:6px!important;margin:0 0 8px!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-meta span,.whaledive-courses #courseGrid .wd-course-card .wd-course-requirement span{padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-requirement{margin:0 0 12px!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-actions{margin-top:8px!important;padding-top:0!important;border-top:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-course-actions .wd-btn{min-height:34px!important;width:max-content!important;padding:0 12px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.12)!important;color:#06384d!important;box-shadow:none!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-badge{font-size:9px!important;padding:6px 8px!important;border-radius:999px!important}

.whaledive-courses #courseGrid .wd-course-card .wd-equip-chips{gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-chips span{padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions{margin-top:8px!important;padding-top:0!important;border-top:0!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link{min-height:38px!important;min-width:128px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:12px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.14)!important;color:#06384d!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link:after{content:' →'}

/* Final course catalog polish: 3-column balanced grid for 6 courses. */
.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:16px!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-card-body h3{min-height:42px!important}.whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link{min-width:112px!important;justify-content:center!important}@media(max-width:980px){.whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}}@media(max-width:620px){
  .whaledive-courses,.whaledive-courses .wd-page,.whaledive-courses .wd-section{width:100%!important;max-width:100%!important;overflow-x:hidden!important}
  .whaledive-courses .wd-section{padding-top:52px!important;padding-bottom:52px!important}
  .whaledive-courses .wd-shell{width:calc(100% - 48px)!important;max-width:none!important;padding-left:0!important;padding-right:0!important;margin-left:24px!important;margin-right:24px!important}
  .whaledive-courses .wd-inner-grid{display:grid!important;grid-template-columns:1fr!important;width:100%!important;max-width:100%!important;gap:18px!important}
  .whaledive-courses .wd-inner-hero{padding-top:112px!important;padding-bottom:54px!important}
  .whaledive-courses .wd-inner-copy,.whaledive-courses .wd-support-card{width:100%!important;max-width:100%!important;min-width:0!important}
  .whaledive-courses .wd-title,.whaledive-courses h1,.whaledive-courses h2{width:100%!important;max-width:100%!important;font-size:clamp(34px,9.8vw,44px)!important;line-height:1.04!important;letter-spacing:-.045em!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important}
  .whaledive-courses .wd-sub,.whaledive-courses p{width:100%!important;max-width:100%!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important}
  .whaledive-courses .wd-actions{display:grid!important;grid-template-columns:1fr!important;width:100%!important;max-width:100%!important;gap:10px!important}
  .whaledive-courses .wd-actions .wd-btn{width:100%!important;max-width:100%!important;justify-content:center!important;text-align:center!important;min-width:0!important;color:#fff!important}
  .whaledive-courses .wd-actions .wd-btn.secondary{background:rgba(255,255,255,.16)!important;border:1px solid rgba(255,255,255,.36)!important;color:#fff!important}
  .whaledive-courses #courseFilters{width:100%!important;max-width:100%!important;display:flex!important;flex-wrap:wrap!important;justify-content:center!important;gap:12px!important;overflow:visible!important;margin-left:0!important;margin-right:0!important}
  .whaledive-courses #courseFilters .wd-chip{max-width:100%!important;white-space:normal!important;padding-left:12px!important;padding-right:12px!important;box-shadow:none!important}
  .whaledive-courses #courseGrid.wd-page-grid{grid-template-columns:1fr!important;width:100%!important;max-width:100%!important;margin-left:0!important;margin-right:0!important;gap:18px!important}
  .whaledive-courses #courseGrid .wd-course-card{width:100%!important;max-width:100%!important;min-width:0!important;margin-left:0!important;margin-right:0!important;box-sizing:border-box!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-equip-card-body{width:100%!important;max-width:100%!important;padding:16px!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-course-meta,.whaledive-courses #courseGrid .wd-course-card .wd-course-requirement,.whaledive-courses #courseGrid .wd-course-card .wd-equip-chips{display:flex!important;flex-wrap:wrap!important;gap:6px!important;width:100%!important;max-width:100%!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-course-meta span,.whaledive-courses #courseGrid .wd-course-card .wd-course-requirement span,.whaledive-courses #courseGrid .wd-course-card .wd-equip-chips span{max-width:100%!important;white-space:normal!important;line-height:1.18!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-price-amount{white-space:normal!important;font-size:16px!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-equip-actions .wd-mini-link{width:100%!important;max-width:100%!important;min-height:42px!important;justify-content:center!important}
  .whaledive-courses .wd-advice-card,.whaledive-courses .wd-support-card{width:100%!important;max-width:100%!important;box-sizing:border-box!important}
  .whaledive-courses .wd-footer .wd-shell{width:calc(100% - 48px)!important;margin-left:24px!important;margin-right:24px!important;padding-left:0!important;padding-right:0!important}
  .whaledive-courses .wd-footer-top{display:grid!important;grid-template-columns:1fr!important;gap:22px!important;width:100%!important;max-width:100%!important}

  .whaledive-courses .wd-inner-hero h1,.whaledive-courses .wd-inner-copy h1{font-size:clamp(31px,8.4vw,39px)!important;line-height:1.08!important;letter-spacing:-.04em!important;width:calc(100% - 10px)!important;max-width:calc(100% - 10px)!important;margin-right:10px!important;display:block!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important}
  .whaledive-courses .wd-inner-hero p,.whaledive-courses .wd-inner-copy p,.whaledive-courses .wdc-card-cta p,.whaledive-courses .wd-footer-brand p{width:calc(100% - 12px)!important;max-width:calc(100% - 12px)!important;margin-right:12px!important;display:block!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important;line-height:1.58!important}
  .whaledive-courses .wdc-card-cta h2{font-size:clamp(30px,8.8vw,40px)!important;line-height:1.06!important;width:100%!important;max-width:100%!important;white-space:normal!important;overflow-wrap:break-word!important}
  body.whaledive-courses .wd-courses-hero .wd-shell{width:calc(100% - 56px)!important;max-width:none!important;margin-left:28px!important;margin-right:28px!important;padding-left:0!important;padding-right:0!important}
  body.whaledive-courses .wd-courses-hero .wd-shell>div{width:100%!important;max-width:100%!important;min-width:0!important}
  body.whaledive-courses .wd-courses-hero h1{font-size:clamp(29px,7.8vw,36px)!important;line-height:1.12!important;letter-spacing:-.035em!important;width:100%!important;max-width:100%!important;margin-left:0!important;margin-right:0!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important}
  body.whaledive-courses .wd-courses-hero p{font-size:15px!important;line-height:1.56!important;width:100%!important;max-width:100%!important;margin-left:0!important;margin-right:0!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important}
  body.whaledive-courses .wd-courses-hero .wd-actions,body.whaledive-courses .wd-courses-hero .wd-actions a{width:100%!important;max-width:100%!important;min-width:0!important;box-sizing:border-box!important;margin-left:0!important;margin-right:0!important}
  body.whaledive-courses .wd-courses-hero .wd-actions a{display:flex!important;justify-content:center!important;text-align:center!important;padding-left:14px!important;padding-right:14px!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-equip-price{display:flex!important;flex-direction:column!important;align-items:flex-start!important;gap:4px!important;width:100%!important;max-width:100%!important}
  .whaledive-courses #courseGrid .wd-course-card .wd-price-label,.whaledive-courses #courseGrid .wd-course-card .wd-price-amount{width:100%!important;max-width:100%!important;white-space:normal!important;text-align:left!important}
  .whaledive-courses .wd-footer-col,.whaledive-courses .wd-footer-brand{width:100%!important;max-width:100%!important;min-width:0!important}
}


.whaledive-courses #course-catalog .wd-equip-price,.whaledive-courses .wd-course-card .wd-equip-price{display:flex!important;flex-direction:column!important;align-items:flex-start!important;gap:4px!important;flex-wrap:nowrap!important}
.whaledive-courses .wd-course-card .wd-price-label{display:block!important;width:100%!important;margin:0!important}
.whaledive-courses .wd-course-card .wd-price-amount{display:block!important;width:100%!important;margin:0!important}
</style><style id="wd-course-card-image-fullbleed">
.whaledive-courses article.wd-course-card .wd-equip-visual,
.whaledive-courses article.wd-course-card .wd-equip-visual.has-photo{padding:0!important;border:0!important;border-bottom:0!important;background:transparent!important;background-image:none!important;box-shadow:none!important}
.whaledive-courses article.wd-course-card .wd-equip-visual:before,
.whaledive-courses article.wd-course-card .wd-equip-visual:after,
.whaledive-courses article.wd-course-card .wd-equip-visual.has-photo:before,
.whaledive-courses article.wd-course-card .wd-equip-visual.has-photo:after{content:none!important;display:none!important}
.whaledive-courses article.wd-course-card .wd-equip-photo{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;padding:0!important;margin:0!important;border:0!important;object-fit:cover!important;background:transparent!important}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-courses'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-inner-hero wd-courses-hero"><div class="wd-shell wd-inner-grid"><div><span class="wd-kicker"><?php echo esc_html(contenly_tr('Dukungan pelatihan selam', 'Dive training support')); ?></span><h1><?php echo esc_html(contenly_tr('Kursus yang sesuai dengan dive berikutnya, bukan hanya kalender Anda.', 'Courses that fit your next dive, not just your calendar.')); ?></h1><p><?php echo esc_html(contenly_tr('Jelajahi jalur sertifikasi, cek harga, dan dapatkan panduan crew sebelum memilih kursus berikutnya.', 'Browse certification pathways, check pricing, and get crew guidance before choosing your next course.')); ?></p><div class="wd-actions"><a class="wd-btn" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Rencana Kursus', 'Ask Course Plan')); ?></a><a class="wd-btn alt" href="#course-catalog"><?php echo esc_html(contenly_tr('Lihat Kursus', 'Browse Courses')); ?></a></div></div><aside class="wd-inner-card"><b><?php echo esc_html(contenly_tr('Dukungan pelatihan mencakup', 'Training support covers')); ?></b><ul><li><?php echo esc_html(contenly_tr('Pemeriksaan jalur sertifikasi', 'Certification pathway checks')); ?></li><li><?php echo esc_html(contenly_tr('Panduan coaching kelompok kecil', 'Small-group coaching guidance')); ?></li><li><?php echo esc_html(contenly_tr('Kesiapan gear dan jadwal', 'Gear and schedule readiness')); ?></li><li><?php echo esc_html(contenly_tr('Opsi NAUI / TDI / DAN', 'NAUI / TDI / DAN options')); ?></li></ul></aside></div></section>

  <section id="course-catalog" class="wd-section white wd-center">
    <div class="wd-shell">
      <?php
      // --- Group courses by agency (computed early for count) ---
      $agency_groups = [];
      foreach($all_courses as $course) {
          $ag = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
          $ag = is_wp_error($ag) ? [] : $ag;
          $agency_key = !empty($ag) ? $ag[0] : 'Other';
          $agency_groups[$agency_key][] = $course;
      }
      $agency_order = ['NAUI', 'TDI', 'DAN'];
      $agency_groups = array_intersect_key($agency_groups, array_flip($agency_order));
      uksort($agency_groups, function($a, $b) use ($agency_order) {
          $ka = array_search($a, $agency_order); if($ka===false) $ka=99;
          $kb = array_search($b, $agency_order); if($kb===false) $kb=99;
          return $ka - $kb;
      });
      $displayed_count = 0; foreach($agency_groups as $g) $displayed_count += count($g);
      ?>

      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Katalog kursus', 'Course catalog')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Kursus selam dari agensi terpercaya', 'Dive courses from trusted agencies')); ?></h2>
      <p class="wd-sub"><?php echo $displayed_count; ?> <?php echo contenly_tr('kursus dari NAUI, TDI, dan DAN', 'courses from NAUI, TDI, and DAN'); ?></p>
      <div id="courseFilters" class="wd-filter-bar" style="margin-bottom:8px!important;">
        <button class="wd-chip active" data-filter="all"><?php echo esc_html(contenly_tr('Semua Kursus', 'All Courses')); ?></button>
        <?php if(!empty($levels) && !is_wp_error($levels)): foreach($levels as $level): ?>
          <button class="wd-chip" data-filter="cat-<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></button>
        <?php endforeach; endif; ?>
      </div>

      <?php
      // Agency descriptions
      $agency_desc = [
          'NAUI' => contenly_tr('Sertifikasi selam internasional dengan fokus keselamatan dan kemandirian diver.', 'International dive certification focused on diver safety and self-reliance.'),
          'TDI' => contenly_tr('Pelatihan diving teknis dan prosedur dekompresi untuk diver berpengalaman.', 'Technical diving training and decompression procedures for experienced divers.'),
          'DAN' => contenly_tr('Pelatihan tanggap darurat, CPR, dan oksigen pertolongan pertama untuk penyelam.', 'Emergency response, CPR, and oxygen first aid training for divers.'),
      ];
      $agency_colors = ['NAUI' => '#004A98', 'TDI' => '#1B5E20', 'DAN' => '#C31C4A'];
      ?>

      <?php foreach($agency_groups as $agency_name => $agency_courses):
        $ac = $agency_colors[$agency_name] ?? '#06384d';
        $ad = $agency_desc[$agency_name] ?? '';
      ?>
      <div style="margin-bottom:56px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
          <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;border-radius:999px;background:<?php echo $ac; ?>;color:#fff;font-size:15px;font-weight:900;letter-spacing:.04em;font-family:'Plus Jakarta Sans',sans-serif;"><?php echo esc_html($agency_name); ?></span>
          <span style="flex:1;height:2px;background:linear-gradient(90deg,<?php echo $ac; ?>33,transparent);border-radius:1px;"></span>
          <span style="font-size:13px;font-weight:700;color:#5b7180;"><?php echo count($agency_courses); ?> <?php echo contenly_tr('kursus', 'courses'); ?></span>
        </div>
        <?php if($ad): ?><p style="color:#5b7180;font-size:15px;line-height:1.6;margin:0 0 22px;max-width:640px;"><?php echo esc_html($ad); ?></p><?php endif; ?>

        <div class="wd-equipment-grid wd-page-grid course-grid" style="align-items:start!important;grid-auto-rows:auto!important;">
          <?php foreach($agency_courses as $course):
            $price = get_post_meta($course->ID, '_wm_price', true);
            $duration = get_post_meta($course->ID, '_wm_duration', true);
            $max_students = get_post_meta($course->ID, '_wm_max_students', true);
            $prereqs = get_post_meta($course->ID, '_wm_prerequisites', true);
            $level_terms = wp_get_post_terms($course->ID, 'course_level', ['fields' => 'all']);
            $level_terms = is_wp_error($level_terms) ? [] : $level_terms;
            $agency_terms = wp_get_post_terms($course->ID, 'course_agency', ['fields' => 'names']);
            $agency_terms = is_wp_error($agency_terms) ? [] : $agency_terms;
            $level_slug = !empty($level_terms) ? $level_terms[0]->slug : '';
            $level_name = !empty($level_terms) ? $level_terms[0]->name : '';
            $permalink = home_url('/courses/' . $course->post_name . '/');
            $image_url = function_exists('wdc_catalog_image_url') ? wdc_catalog_image_url($course->ID, 'course') : (get_the_post_thumbnail_url($course->ID, 'large') ?: wdc_course_image_url($course->post_title, $theme_uri));
          ?>
          <article class="wd-equip-card wd-detail-card wd-shop-card wd-course-card" data-href="<?php echo esc_url($permalink); ?>" data-cat="cat-<?php echo esc_attr($level_slug); ?>" style="border-radius:18px!important;overflow:hidden!important;padding:0!important;background:#fff!important;box-shadow:0 14px 34px rgba(2,21,43,.07)!important;border:1px solid rgba(6,56,77,.08)!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;">
            <div class="wd-equip-visual <?php echo $image_url ? 'has-photo' : ''; ?>" data-course-level="<?php echo esc_attr($level_slug ?: 'course'); ?>" style="height:190px!important;min-height:0!important;border:0!important;border-radius:0!important;margin:0!important;padding:0!important;overflow:hidden!important;background:transparent!important;box-shadow:none!important;">
              <?php if($image_url): ?><img class="wd-equip-photo" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($course->post_title); ?>" loading="lazy" style="position:absolute!important;inset:0!important;width:100%!important;height:100%!important;object-fit:cover!important;object-position:center!important;padding:0!important;margin:0!important;border:0!important;display:block!important;z-index:1!important;transform:none!important;background:transparent!important;" onerror="this.closest('.wd-equip-visual').classList.remove('has-photo');this.remove();"><?php else: ?><span class="wd-equip-mark"><?php echo esc_html($level_name ? mb_substr($level_name, 0, 1) : 'C'); ?></span><?php endif; ?>
              <?php if($level_name): ?><span class="wd-equip-type"><?php echo esc_html($level_name); ?></span><?php endif; ?>
            </div>
            <div class="wd-equip-card-body" style="padding:16px!important;min-height:0!important;height:auto!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important;">
              <div class="wd-course-meta wd-shop-meta" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
                <?php if($level_name): ?><span><?php echo esc_html($level_name); ?></span><?php endif; ?>
                <span><?php echo esc_html($agency_name); ?></span>
              </div>
              <h3 style="font-size:20px!important;line-height:1.28!important;letter-spacing:-.03em!important;margin:0 0 2px!important;color:#061a36!important;min-height:0!important;"><?php echo esc_html($course->post_title); ?></h3>
              <?php if($price): ?>
              <div class="wd-equip-price" style="display:flex!important;flex-direction:column!important;align-items:flex-start!important;gap:4px!important;margin:0 0 6px!important;padding:0!important;background:transparent!important;border:0!important;">
                <span class="wd-price-label" style="display:block!important;width:100%!important;flex-basis:100%!important;margin:0 0 4px!important;color:#5f7180!important;font-size:10px!important;font-weight:800!important;letter-spacing:.02em!important;"><?php echo contenly_tr('Harga mulai', 'Starting price'); ?></span>
                <span class="wd-price-amount" style="display:block!important;width:100%!important;flex-basis:100%!important;color:#06384d!important;font-size:17px!important;line-height:1.1!important;font-weight:950!important;letter-spacing:-.02em!important;">Rp <?php echo number_format((float)$price,0,',','.'); ?></span>
              </div>
              <?php endif; ?>
              <div class="wd-equip-chips" style="gap:6px!important;margin:0 0 4px!important;display:flex!important;flex-wrap:wrap!important;">
                <?php if($duration): ?><span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo esc_html($duration); ?></span><?php endif; ?>
                <span style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo $max_students ? contenly_tr('Maks ', 'Max ') . esc_html($max_students) . contenly_tr(' diver', ' divers') : contenly_tr('Cek ketersediaan', 'Check availability'); ?></span>
                <?php if($prereqs): ?><span class="wd-course-prereq-chip" style="padding:5px 8px!important;border-radius:999px!important;background:#f3fbff!important;color:#35586a!important;font-size:10px!important;font-weight:800!important;line-height:1!important;"><?php echo contenly_tr('Prasyarat: ', 'Prereq: '); ?><?php echo esc_html($prereqs); ?></span><?php endif; ?>
              </div>
              <div class="wd-equip-actions" style="margin-top:8px!important;padding-top:0!important;border-top:0!important;display:flex!important;gap:8px!important;flex-wrap:wrap!important;">
                <a class="wd-mini-link" style="min-height:38px!important;min-width:100px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:13px!important;font-weight:900!important;background:#f3fbff!important;border:1px solid rgba(6,56,77,.14)!important;color:#06384d!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url($permalink); ?>"><?php echo contenly_tr('Lihat Detail', 'View Details'); ?></a>
                <a class="wd-mini-btn" onclick="event.stopPropagation();" style="min-height:38px!important;min-width:100px!important;justify-content:center!important;width:max-content!important;margin:0!important;padding:0 16px!important;border-radius:999px!important;font-size:13px!important;font-weight:900!important;background:#06384d!important;border:1px solid #06384d!important;color:#fff!important;box-shadow:none!important;display:inline-flex!important;align-items:center!important;text-decoration:none!important;" href="<?php echo esc_url(wdc_member_action_url('course', $course->ID, $course->post_title)); ?>"><?php echo contenly_tr('Daftar', 'Enroll'); ?></a>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="wdc-card-cta"><div class="wd-shell"><span class="wd-kicker"><?php echo contenly_tr('Butuh saran kursus?', 'Need course advice?'); ?></span><h2><?php echo esc_html(contenly_tr('Crew bantu pilih jalur yang tepat.', 'The crew helps you choose the right path.')); ?></h2><p><?php echo contenly_tr('Ceritakan level sertifikasi, target tanggal, dan tujuan kenyamanan — kami rekomendasikan kursus yang cocok.', 'Tell us your certification level, target dates, and comfort goals — we recommend the course that fits.'); ?></p><a class="wd-btn alt" href="/contact/"><?php echo esc_html(contenly_tr('Tanya Rencana Kursus', 'Ask About Course Plan')); ?></a></div></section>

    <?php contenly_render_public_footer(); ?>
</main>
<?php wp_footer(); ?></body></html>
