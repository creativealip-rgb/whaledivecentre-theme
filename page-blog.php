<?php
/**
 * Template Name: Blog Page
 */

$paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: 1);
$sticky_posts = get_option('sticky_posts', []);
$featured_id = !empty($sticky_posts) ? (int) $sticky_posts[0] : 0;

$featured_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 1,
  'p' => $featured_id ?: 0,
  'ignore_sticky_posts' => true,
]);

if (!$featured_id || !$featured_q->have_posts()) {
  $featured_q = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
  ]);
  $featured_id = $featured_q->have_posts() ? (int) $featured_q->posts[0]->ID : 0;
}

$posts_q = new WP_Query([
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => 12,
  'paged' => $paged,
  'post__not_in' => $featured_id ? [$featured_id] : [],
  'orderby' => 'date',
  'order' => 'DESC',
  'ignore_sticky_posts' => true,
]);
$theme_uri = get_stylesheet_directory_uri();

$wd_blog_fallback_image = function($post_id, $cat_name = '') use ($theme_uri) {
  $label = strtolower($cat_name . ' ' . get_the_title($post_id));
  if (strpos($label, 'gear') !== false || strpos($label, 'mask') !== false) return $theme_uri . '/assets/wdc-equipment-mask-real.png';
  if (strpos($label, 'fin') !== false) return $theme_uri . '/assets/wdc-equipment-fins-real.png';
  if (strpos($label, 'bcd') !== false) return $theme_uri . '/assets/wdc-equipment-bcd-real.png';
  if (strpos($label, 'safety') !== false || strpos($label, 'buddy') !== false || strpos($label, 'rescue') !== false) return $theme_uri . '/assets/wdc-course-rescue-diver-real.png';
  if (strpos($label, 'advanced') !== false) return $theme_uri . '/assets/wdc-course-advanced-open-water-real.png';
  if (strpos($label, 'conservation') !== false || strpos($label, 'reef') !== false) return $theme_uri . '/assets/wdc-home-hero-diving-clean3.webp';
  return $theme_uri . '/assets/wdc-course-open-water-real.png';
};
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?><style id="wd-blog-ux-pass">.wd-blog-search{display:flex;gap:10px;margin:0 0 14px}.wd-blog-search input{flex:1;min-height:50px;border:1px solid #d8e8e8;border-radius:999px;padding:0 18px}.wd-blog-search button{border:0;border-radius:999px;padding:0 22px;background:#06384d;color:#fff;font-weight:800}.wd-topic-pills{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:22px}.wd-topic-pills a{display:inline-flex;align-items:center;min-height:38px;padding:0 13px;border-radius:999px;background:#eef8fb;color:#0b617c;text-decoration:none;font-weight:800;font-size:13px}.wd-blog-cta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin:0 0 28px;padding:18px;border-radius:22px;background:linear-gradient(135deg,#06384d,#08a7c7);color:#fff}.wd-blog-cta span{color:rgba(255,255,255,.78)}.wd-blog-cta a{margin-left:auto;color:#06384d;background:#fff;border-radius:999px;padding:10px 14px;text-decoration:none;font-weight:900}@media(max-width:640px){.wd-blog-search{display:grid}.wd-blog-cta a{width:100%;text-align:center}}</style><style id="wd-blog-header-readable-critical">
/* WDC blog archive heading visibility fix */
.whaledive-blog .wd-blog-section-head{position:relative!important;z-index:2!important;text-align:center!important;margin:0 auto 24px!important;max-width:820px!important;color:#fff!important}
.whaledive-blog .wd-blog-section-head .wd-kicker{display:inline-flex!important;align-items:center!important;justify-content:center!important;margin:0 0 14px!important;padding:8px 14px!important;border-radius:999px!important;background:rgba(76,200,237,.18)!important;border:1px solid rgba(76,200,237,.38)!important;color:#96DAEA!important;-webkit-text-fill-color:#96DAEA!important;font-size:12px!important;font-weight:900!important;letter-spacing:.13em!important;text-transform:uppercase!important;opacity:1!important;visibility:visible!important}
.whaledive-blog .wd-blog-section-head h2{display:block!important;position:relative!important;margin:0 auto 14px!important;max-width:760px!important;color:#fff!important;-webkit-text-fill-color:#fff!important;font-family:'Plus Jakarta Sans',sans-serif!important;font-size:clamp(36px,5.6vw,72px)!important;font-weight:900!important;line-height:.95!important;letter-spacing:-.065em!important;text-shadow:0 16px 40px rgba(0,0,0,.45)!important;opacity:1!important;visibility:visible!important;transform:none!important;clip:auto!important;clip-path:none!important;height:auto!important;overflow:visible!important}
.whaledive-blog .wd-blog-section-head p{display:block!important;margin:0 auto!important;max-width:680px!important;color:rgba(255,255,255,.78)!important;-webkit-text-fill-color:rgba(255,255,255,.78)!important;font-size:17px!important;line-height:1.7!important;opacity:1!important;visibility:visible!important}
@media(max-width:640px){.whaledive-blog .wd-blog-section-head h2{font-size:42px!important}.whaledive-blog .wd-blog-section-head p{font-size:15px!important}}

</style><style id="wd-blog-editorial-redesign">
.whaledive-blog{--wd-sea:#06384d;--wd-cyan:#4CC8ED;--wd-foam:#dff8ff;--wd-sand:#f7efe2;--wd-ink:#061a36;background:#03172d!important}
.whaledive-blog .wd-blog-section-clean{position:relative;isolation:isolate;padding:160px 0 84px!important;background:radial-gradient(circle at 16% 16%,rgba(76,200,237,.26),transparent 28%),radial-gradient(circle at 86% 8%,rgba(150,218,234,.2),transparent 24%),linear-gradient(180deg,#03172d 0%,#06384d 42%,#f3fbff 42%,#fff 100%)!important;overflow:hidden}
.whaledive-blog .wd-blog-section-clean:before{content:"";position:absolute;inset:0 0 auto;height:520px;background:linear-gradient(180deg,rgba(2,15,32,.18),rgba(2,15,32,.56)),url('<?php echo esc_url($theme_uri . '/assets/wdc-home-hero-diving-clean3.webp'); ?>') center/cover no-repeat;opacity:.42;z-index:-2}
.whaledive-blog .wd-blog-section-clean:after{content:"";position:absolute;inset:0;background:repeating-radial-gradient(circle at 50% 0,rgba(255,255,255,.055) 0 1px,transparent 1px 38px);z-index:-1;pointer-events:none}
.whaledive-blog .wd-blog-section-head{max-width:940px!important;margin-bottom:26px!important}.whaledive-blog .wd-blog-section-head h2{max-width:900px!important;font-size:clamp(42px,7vw,86px)!important}.whaledive-blog .wd-blog-section-head p{max-width:720px!important;font-size:18px!important}.wd-blog-hero-actions{display:flex;justify-content:center;gap:12px;flex-wrap:wrap;margin:24px 0 22px}.wd-blog-hero-actions a{display:inline-flex;align-items:center;justify-content:center;min-height:48px;padding:0 18px;border-radius:999px;font-weight:900;text-decoration:none}.wd-blog-hero-actions .primary{background:#fff;color:#06384d;box-shadow:0 18px 34px rgba(0,0,0,.18)}.wd-blog-hero-actions .ghost{border:1px solid rgba(255,255,255,.32);color:#fff;background:rgba(255,255,255,.1);backdrop-filter:blur(10px)}.wd-blog-proof-row{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;max-width:820px;margin:0 auto 28px}.wd-blog-proof-row div{border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.1);backdrop-filter:blur(14px);border-radius:20px;padding:14px 16px;color:#fff;text-align:left}.wd-blog-proof-row b{display:block;font-size:20px;line-height:1;color:#96DAEA}.wd-blog-proof-row span{display:block;margin-top:5px;color:rgba(255,255,255,.74);font-size:13px;line-height:1.35}.whaledive-blog .wd-blog-category-filter{display:flex!important;gap:10px!important;justify-content:center!important;flex-wrap:wrap!important;margin:0 auto 30px!important;max-width:960px!important}.whaledive-blog .wd-blog-category-filter .wd-chip{display:inline-flex!important;align-items:center!important;min-height:42px!important;padding:0 15px!important;border-radius:999px!important;background:rgba(255,255,255,.94)!important;color:#06384d!important;border:1px solid rgba(255,255,255,.7)!important;box-shadow:0 12px 28px rgba(0,0,0,.08)!important;font-weight:900!important}.whaledive-blog .wd-blog-category-filter .wd-chip.active{background:#4CC8ED!important;color:#03172d!important;border-colo... [truncated]
</style><style id="wd-blog-readable-critical">
/* WDC blog readable cards, rounded corners, reliable image fallback */
.whaledive-blog .wd-blog-featured-card,.whaledive-blog .wd-blog-side-card,.whaledive-blog .wd-blog-card-modern{border-radius:26px!important;overflow:hidden!important;background:#fff!important;color:#061a36!important}
.whaledive-blog .wd-blog-featured-media{border-radius:26px 26px 0 0!important;overflow:hidden!important}
.whaledive-blog .wd-blog-card-media{border-radius:26px 26px 0 0!important;overflow:hidden!important}
.whaledive-blog .wd-blog-mini a{border-radius:18px!important;background:#fff!important;color:#061a36!important}
.whaledive-blog .wd-blog-mini-thumb{border-radius:14px!important;overflow:hidden!important;background:linear-gradient(135deg,#004A98,#4CC8ED)!important}
.whaledive-blog .wd-blog-featured-body,.whaledive-blog .wd-blog-card-body,.whaledive-blog .wd-blog-side-card{background:#fff!important;color:#061a36!important}
.whaledive-blog .wd-blog-featured-body h2,.whaledive-blog .wd-blog-featured-body h2 a,.whaledive-blog .wd-blog-card-body h3,.whaledive-blog .wd-blog-card-body h3 a,.whaledive-blog .wd-blog-mini strong{color:#061a36!important;text-shadow:none!important;opacity:1!important}
.whaledive-blog .wd-blog-featured-body p,.whaledive-blog .wd-blog-card-body p,.whaledive-blog .wd-blog-meta-row em{color:#50697b!important;opacity:1!important;text-shadow:none!important}
.whaledive-blog .wd-blog-card-body span,.whaledive-blog .wd-blog-meta-row span,.whaledive-blog .wd-blog-mini small{color:#004A98!important;opacity:1!important}
.whaledive-blog .wd-blog-card-body>a{background:#eef8fb!important;color:#004A98!important;border-color:rgba(0,74,152,.16)!important}
.whaledive-blog .wd-blog-featured-media img,.whaledive-blog .wd-blog-card-media img,.whaledive-blog .wd-blog-mini-thumb img{background:linear-gradient(135deg,#004A98,#4CC8ED)!important;min-height:100%!important}
</style><style id="wd-blog-text-force-critical">
/* WDC blog force readable text in every card */
.whaledive-blog article.wd-blog-featured-card,
.whaledive-blog article.wd-blog-card-modern,
.whaledive-blog .wd-blog-side-card,
.whaledive-blog article.wd-blog-mini{background:#fff!important;color:#061a36!important;border-radius:26px!important;overflow:hidden!important}
.whaledive-blog .wd-blog-featured-body,
.whaledive-blog .wd-blog-card-body{display:flex!important;flex-direction:column!important;background:#fff!important;color:#061a36!important;position:relative!important;z-index:2!important;visibility:visible!important;opacity:1!important}
.whaledive-blog .wd-blog-featured-body *,
.whaledive-blog .wd-blog-card-body *,
.whaledive-blog .wd-blog-side-card *,
.whaledive-blog .wd-blog-mini *{visibility:visible!important;opacity:1!important;text-shadow:none!important;mix-blend-mode:normal!important;filter:none!important}
.whaledive-blog .wd-blog-featured-body h2,
.whaledive-blog .wd-blog-featured-body h2 a,
.whaledive-blog .wd-blog-card-body h3,
.whaledive-blog .wd-blog-card-body h3 a,
.whaledive-blog .wd-blog-side-card h3,
.whaledive-blog .wd-blog-mini strong{color:#061a36!important;background:transparent!important;-webkit-text-fill-color:#061a36!important}
.whaledive-blog .wd-blog-featured-body p,
.whaledive-blog .wd-blog-card-body p,
.whaledive-blog .wd-blog-meta-row em{color:#334155!important;background:transparent!important;-webkit-text-fill-color:#334155!important}
.whaledive-blog .wd-blog-meta-row span,
.whaledive-blog .wd-blog-card-body span,
.whaledive-blog .wd-blog-mini small,
.whaledive-blog .wd-blog-side-card>span{color:#004A98!important;background:transparent!important;-webkit-text-fill-color:#004A98!important}
.whaledive-blog .wd-blog-featured-media b{background:#fff!important;color:#061a36!important;-webkit-text-fill-color:#061a36!important;border:1px solid rgba(255,255,255,.55)!important}
.whaledive-blog .wd-blog-read{background:#004A98!important;color:#fff!important;-webkit-text-fill-color:#fff!important}
.whaledive-blog .wd-blog-card-body>a{background:#eef8fb!important;color:#004A98!important;-webkit-text-fill-color:#004A98!important}
</style><style id="wd-blog-hard-layout-critical">
/* WDC blog hard layout fix: keep text outside image overlays */
.whaledive-blog .wd-blog-latest-layout{align-items:start!important}
.whaledive-blog .wd-blog-featured-card,
.whaledive-blog .wd-blog-card-modern{display:flex!important;flex-direction:column!important;position:relative!important;border-radius:26px!important;background:#fff!important;overflow:hidden!important;color:#061a36!important}
.whaledive-blog .wd-blog-featured-media,
.whaledive-blog .wd-blog-card-media{display:block!important;position:relative!important;inset:auto!important;width:100%!important;min-height:0!important;margin:0!important;transform:none!important;z-index:1!important;overflow:hidden!important;background:#eaf8fb!important}
.whaledive-blog .wd-blog-featured-media{aspect-ratio:16/8.5!important;height:auto!important}
.whaledive-blog .wd-blog-card-media{aspect-ratio:16/10!important;height:auto!important}
.whaledive-blog .wd-blog-featured-media img,
.whaledive-blog .wd-blog-card-media img{display:block!important;position:static!important;width:100%!important;height:100%!important;object-fit:cover!important;opacity:1!important;visibility:visible!important;filter:none!important;transform:none!important}
.whaledive-blog .wd-blog-featured-media:before,
.whaledive-blog .wd-blog-featured-media:after,
.whaledive-blog .wd-blog-card-media:before,
.whaledive-blog .wd-blog-card-media:after{display:none!important;content:none!important}
.whaledive-blog .wd-blog-featured-body,
.whaledive-blog .wd-blog-card-body{display:flex!important;flex-direction:column!important;position:relative!important;inset:auto!important;z-index:3!important;width:100%!important;height:auto!important;min-height:0!important;margin:0!important;padding:26px!important;background:#fff!important;color:#061a36!important;overflow:visible!important;transform:none!important;clip:auto!important;clip-path:none!important}
.whaledive-blog .wd-blog-featured-body{padding:30px!important}
.whaledive-blog .wd-blog-featured-body h2,
.whaledive-blog .wd-blog-card-body h3{display:block!important;position:static!important;margin:0 0 12px!important;height:auto!important;min-height:0!important;overflow:visible!important;color:#061a36!important;line-height:1.08!important;opacity:1!important;visibility:visible!important}
.whaledive-blog .wd-blog-featured-body h2 a,
.whaledive-blog .wd-blog-card-body h3 a{display:inline!important;position:static!important;color:#061a36!important;-webkit-text-fill-color:#061a36!important;text-decoration:none!important;opacity:1!important;visibility:visible!important}
.whaledive-blog .wd-blog-featured-body p,
.whaledive-blog .wd-blog-card-body p{display:block!important;position:static!important;margin:0 0 18px!important;height:auto!important;min-height:0!important;overflow:visible!important;color:#334155!important;-webkit-text-fill-color:#334155!important;line-height:1.65!important;opacity:1!important;visibility:visible!important}
.whaledive-blog .wd-blog-meta-row,
.whaledive-blog .wd-blog-card-body>span{display:flex!important;position:static!important;height:auto!important;overflow:visible!important;color:#004A98!important;-webkit-text-fill-color:#004A98!important;opacity:1!important;visibility:visible!important}
.whaledive-blog .wd-blog-card-body>a,
.whaledive-blog .wd-blog-read{position:static!important;margin-top:auto!important;z-index:4!important;opacity:1!important;visibility:visible!important}
.whaledive-blog .wd-blog-side-card{border-radius:26px!important;background:#fff!important;color:#061a36!important;overflow:visible!important}
.whaledive-blog .wd-blog-mini a{background:#fff!important;color:#061a36!important;border-radius:18px!important;overflow:hidden!important}
.whaledive-blog .wd-blog-mini strong{color:#061a36!important;-webkit-text-fill-color:#061a36!important;opacity:1!important;visibility:visible!important}
@media(max-width:680px){.whaledive-blog .wd-blog-featured-body,.whaledive-blog .wd-blog-card-body{padding:20px!important}.whaledive-blog .wd-blog-featured-media{aspect-ratio:16/10!important}}
</style>
<style id="wd-blog-declutter-pass">
.whaledive-blog .wd-blog-section-clean{padding-top:148px!important;background:radial-gradient(circle at 16% 16%,rgba(76,200,237,.22),transparent 28%),radial-gradient(circle at 86% 8%,rgba(150,218,234,.16),transparent 24%),linear-gradient(180deg,#03172d 0%,#06384d 36%,#f3fbff 36%,#fff 100%)!important}
.whaledive-blog .wd-blog-section-clean:before{height:500px!important;opacity:.36!important}
.whaledive-blog .wd-blog-section-head{margin-bottom:22px!important}.whaledive-blog .wd-blog-section-head h2{font-size:clamp(40px,6.2vw,76px)!important;line-height:1!important;letter-spacing:-.055em!important}.whaledive-blog .wd-blog-section-head p{font-size:17px!important}.wd-blog-hero-actions{margin-bottom:0!important}.wd-blog-topic-strip{margin:0 auto 30px;max-width:1040px}.wd-blog-topic-strip .wd-blog-proof-row{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin:0!important;max-width:none!important}.wd-blog-topic-strip .wd-blog-proof-row div{position:relative;min-height:118px;padding:20px 20px 18px 58px!important;border:1px solid rgba(6,56,77,.1)!important;border-radius:26px!important;background:linear-gradient(180deg,#fff,#eefaff)!important;color:#061a36!important;box-shadow:0 18px 42px rgba(2,21,43,.09)!important;backdrop-filter:none!important;overflow:hidden}.wd-blog-topic-strip .wd-blog-proof-row div:before{content:"";position:absolute;left:18px;top:22px;width:28px;height:28px;border-radius:50%;background:radial-gradient(circle at 35% 35%,#fff 0 2px,#4CC8ED 3px 100%);box-shadow:0 0 0 8px rgba(76,200,237,.14)}.wd-blog-topic-strip .wd-blog-proof-row div:after{content:"";position:absolute;right:-26px;bottom:-34px;width:110px;height:110px;border-radius:50%;background:rgba(76,200,237,.1)}.wd-blog-topic-strip .wd-blog-proof-row b{position:relative;font-size:18px!important;color:#06384d!important}.wd-blog-topic-strip .wd-blog-proof-row span{position:relative;color:#557082!important;font-size:14px!important;line-height:1.5!important}.whaledive-blog .wd-blog-category-filter{margin-bottom:18px!important}.whaledive-blog .wd-blog-card-modern{border-radius:28px!important;overflow:hidden!important}.whaledive-blog .wd-blog-card-media{aspect-ratio:4/3!important;background:#dff8ff!important}.whaledive-blog .wd-blog-card-media img{transition:transform .35s ease!important}.whaledive-blog .wd-blog-card-modern:hover .wd-blog-card-media img{transform:scale(1... [truncated]
</style><style id="wd-blog-final-polish">
.whaledive-blog .wd-blog-section-clean{padding-top:128px!important;padding-bottom:70px!important;background:radial-gradient(circle at 16% 16%,rgba(76,200,237,.18),transparent 26%),radial-gradient(circle at 86% 8%,rgba(150,218,234,.12),transparent 22%),linear-gradient(180deg,#03172d 0%,#06384d 32%,#f3fbff 32%,#fff 100%)!important}.whaledive-blog .wd-blog-section-clean:before{height:460px!important;opacity:.32!important}.whaledive-blog .wd-blog-section-head{margin-bottom:18px!important}.whaledive-blog .wd-blog-section-head h2{font-size:clamp(38px,5.7vw,68px)!important;max-width:820px!important}.whaledive-blog .wd-blog-section-head p{font-size:16px!important;max-width:660px!important}.wd-blog-hero-actions{margin-top:20px!important}.whaledive-blog .wd-blog-category-filter{margin-bottom:16px!important}.wd-blog-topic-strip{margin:0 auto 26px!important}.wd-blog-topic-strip .wd-blog-proof-row a{position:relative;display:block;min-height:98px;padding:18px 18px 16px 58px!important;border:1px solid rgba(6,56,77,.1)!important;border-radius:24px!important;background:linear-gradient(180deg,#fff,#eefaff)!important;color:#061a36!important;text-decoration:none!important;box-shadow:0 18px 42px rgba(2,21,43,.09)!important;overflow:hidden;transition:transform .2s ease,box-shadow .2s ease}.wd-blog-topic-strip .wd-blog-proof-row a:hover{transform:translateY(-3px);box-shadow:0 24px 58px rgba(2,21,43,.13)!important}.wd-blog-topic-strip .wd-blog-proof-row a:focus-visible{outline:3px solid rgba(76,200,237,.8);outline-offset:3px}.wd-blog-topic-strip .wd-blog-proof-row a:before{content:"";position:absolute;left:18px;top:22px;width:27px;height:27px;border-radius:50%;background:radial-gradient(circle at 35% 35%,#fff 0 2px,#4CC8ED 3px 100%);box-shadow:0 0 0 8px rgba(76,200,237,.14)}.wd-blog-topic-strip .wd-blog-proof-row a.safety:before{background:radial-gradient(circle at 35% 35%,#fff 0 2px,#ff7b66 3px 100%);box-shadow:0 0 0 8px rgba(255,123,102,.14)}.wd-blog-topic-strip .wd-blog-proof-row a.com... [truncated]
</style><style id="wd-blog-simple-compact-final">
.whaledive-blog{background:#f4fbff!important}.whaledive-blog .wd-blog-simple{position:relative;padding:112px 0 56px!important;background:#f4fbff!important}.wd-blog-simple .wd-shell{position:relative}.wd-blog-simple-head{display:block;margin:0 0 22px;color:#061a36}.wd-blog-simple-head .wd-kicker{display:inline-flex!important;margin:0!important;padding:8px 13px!important;border-radius:999px!important;background:rgba(76,200,237,.16)!important;border:1px solid rgba(76,200,237,.34)!important;color:#96DAEA!important;font-size:12px!important;font-weight:900!important;letter-spacing:.13em!important;text-transform:uppercase!important}.wd-blog-simple-head h1{margin:10px 0 0;color:#061a36;font-size:clamp(30px,3.8vw,44px);line-height:1;letter-spacing:-.035em}.whaledive-blog .wd-blog-category-filter{justify-content:flex-start!important;margin:0 0 24px!important;max-width:none!important;gap:8px!important}.whaledive-blog .wd-blog-category-filter .wd-chip{min-height:38px!important;padding:0 13px!important;border-radius:999px!important;background:#fff!important;color:#06384d!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 10px 22px rgba(2,21,43,.08)!important;font-size:13px!important;font-weight:900!important}.whaledive-blog .wd-blog-category-filter .wd-chip.active{background:#4CC8ED!important;color:#03172d!important;border-color:#4CC8ED!important}.whaledive-blog .wd-blog-grid-compact{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:16px!important;margin:0!important}.whaledive-blog .wd-blog-card-compact{border-radius:20px!important;background:#fff!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 16px 36px rgba(2,21,43,.08)!important;overflow:hidden!important;display:flex!important;flex-direction:column!important;min-height:0!important;transition:transform .18s ease,box-shadow .18s ease!important}.whaledive-blog .wd-blog-... [truncated]
</style><style id="wd-blog-responsive-meta-fix">
.whaledive-blog .wd-blog-grid-compact{grid-template-columns:repeat(4,minmax(0,1fr))!important;gap:14px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body{padding:14px!important;gap:7px!important;min-height:158px!important}.whaledive-blog .wd-card-meta{display:flex!important;align-items:center!important;gap:6px!important;min-width:0!important;margin:0!important}.whaledive-blog .wd-card-meta span{display:inline-flex!important;align-items:center!important;min-width:0!important;width:auto!important;max-width:100%!important;margin:0!important;padding:5px 8px!important;border-radius:999px!important;font-size:10px!important;font-weight:900!important;line-height:1!important;letter-spacing:.035em!important;text-transform:uppercase!important;white-space:nowrap!important;overflow:hidden!important;text-overflow:ellipsis!important}.whaledive-blog .wd-card-meta .wd-card-cat{flex:1 1 auto!important;background:#e9f8fd!important;color:#006b8f!important;-webkit-text-fill-color:#006b8f!important}.whaledive-blog .wd-card-meta .wd-card-time{flex:0 0 auto!important;background:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>span{display:none!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body h3{font-size:clamp(15px,1.18vw,18px)!important;line-height:1.16!important;-webkit-line-clamp:2!important;margin:0!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body p{font-size:12.5px!important;line-height:1.42!important;-webkit-line-clamp:2!important;margin:0!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a{padding:7px 10px!important;font-size:12px!important;background:#eef8fb!important;color:#004A98!important;-webkit-text-fill-color:#004A98!important;border:1px solid rgba(0,74,152,.12)!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-media{aspect-ratio:16/9!important}@media(max-width:980px){.whaledive-blog .wd-blog-grid-compact{grid-template-columns:repeat(3,minmax(0,1fr))!important}}@media(max-width:760px){.whaledive-blog .wd-blog-grid-compact{grid-template-columns:repeat(2,minmax(0,1fr))!important}.whaledive-blog .wd-blog-simple{padding-top:96px!important}}@media(max-width:540px){.whaledive-blog .wd-blog-grid-compact{grid-template-columns:1fr!important;gap:14px!important}.whaledive-blog .wd-blog-card-compact{display:grid!important;grid-template-columns:112px minmax(0,1fr)!important;border-radius:18px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-media{height:100%!important;aspect-ratio:auto!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body{min-height:142px!important;padding:12px!important}.whaledive-blog .wd-card-meta .wd-card-cat{max-width:135px!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body p{display:none!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a{margin-top:auto!important}.wd-blog-simple-head h1{font-size:32px!important}}
</style><style id="wd-blog-featured-pagination-fix">
.whaledive-blog .wd-blog-featured-simple{display:grid!important;grid-template-columns:minmax(0,1.08fr) minmax(320px,.92fr)!important;gap:0!important;margin:0 0 20px!important;border-radius:24px!important;overflow:hidden!important;background:#fff!important;border:1px solid rgba(6,56,77,.08)!important;box-shadow:0 18px 42px rgba(2,21,43,.08)!important}.whaledive-blog .wd-featured-media{display:block!important;min-height:240px!important;background:#dff8ff!important}.whaledive-blog .wd-featured-media img{display:block!important;width:100%!important;height:100%!important;object-fit:cover!important}.whaledive-blog .wd-featured-body{display:flex!important;flex-direction:column!important;justify-content:center!important;padding:28px!important;color:#061a36!important}.whaledive-blog .wd-featured-body h2{margin:10px 0 10px!important;font-size:clamp(28px,3.3vw,46px)!important;line-height:1!important;letter-spacing:-.045em!important}.whaledive-blog .wd-featured-body h2 a{color:#061a36!important;text-decoration:none!important}.whaledive-blog .wd-featured-body p{margin:0 0 18px!important;color:#50697b!important;font-size:15px!important;line-height:1.55!important}.whaledive-blog .wd-featured-read{align-self:flex-start!important;margin-top:auto!important;padding:10px 14px!important;border-radius:999px!important;background:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important;text-decoration:none!important;font-weight:900!important;font-size:13px!important}.whaledive-blog .wd-blog-pagination{display:flex!important;justify-content:center!important;gap:12px!important;flex-wrap:wrap!important;margin:28px 0 0!important}.whaledive-blog .wd-blog-pagination span{background:transparent!important;padding:0!important;border:0!important;box-shadow:none!important}.whaledive-blog .wd-blog-pagination a{display:inline-flex!important;align-items:center!important;justify-content:center!important;min-height:44px!important;padding:0 18px!important;border-radius:999px!important;background:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important;text-decoration:none!important;font-weight:900!important;box-shadow:0 14px 28px rgba(2,21,43,.12)!important}.whaledive-blog .wd-blog-pagination a:hover{background:#004A98!important}@media(max-width:860px){.whaledive-blog .wd-blog-featured-simple{grid-template-columns:1fr!important}.whaledive-blog .wd-featured-media{min-height:220px!important}.whaledive-blog .wd-featured-body{padding:22px!important}}@media(max-width:540px){.whaledive-blog .wd-featured-media{min-height:190px!important}.whaledive-blog .wd-featured-body h2{font-size:27px!important}.whaledive-blog .wd-blog-pagination{justify-content:stretch!important}.whaledive-blog .wd-blog-pagination span,.whaledive-blog .wd-blog-pagination a{width:100%!important}}
</style><style id="wd-blog-kicker-title-fix">
.wd-blog-simple-head{margin-bottom:16px!important}.wd-blog-simple-head .wd-kicker{background:#06384d!important;border-color:#06384d!important;color:#fff!important;-webkit-text-fill-color:#fff!important;box-shadow:0 10px 22px rgba(2,21,43,.12)!important}.wd-blog-simple-head h1{font-size:clamp(34px,4vw,52px)!important}.wd-blog-latest-head{display:flex!important;align-items:end!important;justify-content:space-between!important;gap:16px!important;margin:30px 0 14px!important;padding-top:4px!important}.wd-blog-latest-head span{color:#006b8f!important;font-size:12px!important;font-weight:900!important;letter-spacing:.12em!important;text-transform:uppercase!important}.wd-blog-latest-head h2{margin:0!important;color:#061a36!important;font-size:clamp(26px,3vw,38px)!important;line-height:1!important;letter-spacing:-.035em!important}@media(max-width:540px){.wd-blog-latest-head{display:block!important}.wd-blog-latest-head h2{margin-top:8px!important}}
</style><style id="wd-blog-section-spacing-polish">
.whaledive-blog .wd-blog-simple{padding-top:132px!important}.wd-blog-simple-head{margin:0 0 20px!important;max-width:760px!important}.wd-blog-simple-head .wd-kicker{padding:7px 12px!important;font-size:11px!important;letter-spacing:.12em!important}.wd-blog-simple-head h1{margin-top:12px!important;font-size:clamp(30px,3.2vw,42px)!important;line-height:1.05!important;letter-spacing:-.03em!important}.whaledive-blog .wd-blog-featured-simple{margin-bottom:34px!important}.wd-blog-latest-head{position:relative!important;display:flex!important;align-items:center!important;justify-content:space-between!important;margin:0 0 18px!important;padding:24px 0 0!important;border-top:1px solid rgba(6,56,77,.12)!important}.wd-blog-latest-head span{color:#061a36!important;font-size:clamp(24px,2.6vw,36px)!important;font-weight:900!important;line-height:1!important;letter-spacing:-.03em!important;text-transform:none!important}.wd-blog-latest-head:after{content:"";width:54px;height:3px;border-radius:99px;background:#4CC8ED}@media(max-width:540px){.whaledive-blog .wd-blog-simple{padding-top:104px!important}.wd-blog-simple-head h1{font-size:30px!important}.wd-blog-latest-head{align-items:flex-start!important}.wd-blog-latest-head:after{width:42px;margin-top:8px}}
</style><style id="wd-blog-card-cta-pagination-tighten">
.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a{display:inline-flex!important;align-items:center!important;justify-content:center!important;align-self:flex-start!important;min-height:40px!important;padding:0 16px!important;border-radius:999px!important;background:#eef8fb!important;color:#004A98!important;-webkit-text-fill-color:#004A98!important;border:1px solid rgba(0,74,152,.16)!important;font-weight:900!important;line-height:1!important;text-decoration:none!important;box-shadow:0 10px 22px rgba(0,74,152,.08)!important}.whaledive-blog .wd-blog-card-compact .wd-blog-card-body>a:hover{background:#004A98!important;color:#fff!important;-webkit-text-fill-color:#fff!important}.whaledive-blog .wd-blog-pagination{margin:12px 0 0!important}.whaledive-blog .wd-blog-grid-compact{margin-bottom:0!important}
</style></head>
<body <?php body_class('whaledive-inner whaledive-blog'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <header class="wd-header"><div class="wd-shell"><div class="wd-nav"><a class="wd-brand" href="/"><img class="wd-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/wdc-navbar-logo.jpg?v=20260514b'); ?>" alt="Whale Dive Centre"><span>Whale Dive Centre</span></a><button class="wd-hamburger" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button><nav class="wd-menu" id="wd-mobile-menu"><a href="/" data-nav="home">Home</a><a href="/courses/" data-nav="courses">Courses</a><a href="/equipment/" data-nav="equipment">Equipment</a><a href="/blog/" data-nav="blog">Blog</a><?php if(is_user_logged_in()){ $u=wp_get_current_user(); echo '<a href="/member-dashboard/" class="wd-nav-member">Dashboard</a>'; } else { echo '<a href="/member-login/" class="wd-nav-member">Login</a>'; } ?></nav></div></div></header>

  <!-- BLOG POSTS -->
  <section class="wd-section wd-blog-simple" id="articles">
    <div class="wd-shell">
      <?php if ($paged === 1 && $featured_q->have_posts()) : ?>
      <div class="wd-blog-simple-head">
        <span class="wd-kicker">Dive Journal</span>
        <h1>Featured Article</h1>
      </div>
        <?php $featured_q->the_post(); $cats=get_the_category(); $cat_name=$cats?$cats[0]->name:'Article'; $read_time=max(2,(int)ceil(str_word_count(wp_strip_all_tags(get_the_content()))/220)); ?>
        <article class="wd-blog-featured-simple">
          <a class="wd-featured-media" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()){the_post_thumbnail('large');} else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></a>
          <div class="wd-featured-body">
            <div class="wd-card-meta"><span class="wd-card-cat"><?php echo esc_html($cat_name); ?></span><span class="wd-card-time"><?php echo esc_html($read_time); ?> min</span></div>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 24)); ?></p>
            <a class="wd-featured-read" href="<?php the_permalink(); ?>">Read More →</a>
          </div>
        </article>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
      <div class="wd-blog-simple-head wd-blog-archive-head">
        <span class="wd-kicker">Dive Journal</span>
        <h1>More from the journal</h1>
      </div>
      <?php endif; ?>

      <?php if ($paged === 1) : ?>
      <div class="wd-blog-latest-head">
        <span>More from the journal</span>
      </div>
      <?php endif; ?>
      <?php if ($posts_q->have_posts()) : ?>
        <div class="wd-blog-grid wd-blog-grid-modern wd-blog-grid-compact">
          <?php while ($posts_q->have_posts()) : $posts_q->the_post(); $cats=get_the_category(); $cat_name=$cats?$cats[0]->name:'Article'; $read_time=max(2,(int)ceil(str_word_count(wp_strip_all_tags(get_the_content()))/220)); ?>
            <article class="wd-blog-card-modern wd-blog-card-compact">
              <a class="wd-blog-card-media" href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()){the_post_thumbnail('medium_large');} else { echo '<img src="' . esc_url($wd_blog_fallback_image(get_the_ID(), $cat_name)) . '" alt="' . esc_attr(get_the_title()) . '">'; } ?></a>
              <div class="wd-blog-card-body"><div class="wd-card-meta"><span class="wd-card-cat"><?php echo esc_html($cat_name); ?></span><span class="wd-card-time"><?php echo esc_html($read_time); ?> min</span></div><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 12)); ?></p><a href="<?php the_permalink(); ?>">Read More →</a></div>
            </article>
          <?php endwhile; ?>
        </div>
        <nav class="wd-blog-pagination" aria-label="Blog pagination">
          <?php $older = get_next_posts_link('Next: Older Posts →', $posts_q->max_num_pages); $newer = get_previous_posts_link('← Newer Posts'); ?>
          <?php if ($newer) : ?><span><?php echo $newer; ?></span><?php endif; ?>
          <?php if ($older) : ?><span><?php echo $older; ?></span><?php endif; ?>
        </nav>
      <?php else : ?>
        <p class="wd-empty">No latest articles published yet.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <footer id="contact" class="wd-footer"><div class="wd-shell"><div class="wd-footer-top"><div class="wd-footer-brand"><span class="wd-footer-kicker">Ready to dive?</span><h2>Whale Dive Centre</h2><p>Dive training, community trips, equipment support, and ocean-minded experiences for safer adventures below the surface.</p><a class="wd-btn alt" href="/contact/">Start Inquiry</a></div><nav class="wd-footer-col"><h3>Explore</h3><a href="/courses/">Dive Courses</a><a href="/equipment/">Scuba Equipment</a><a href="/contact/">About Us</a><a href="/blog/">Blog</a></nav><nav class="wd-footer-col"><h3>Courses</h3><a href="/course/open-water-diver/">Open Water</a><a href="/course/advanced-open-water/">Advanced Open Water</a><a href="/course/rescue-diver/">Rescue Diver</a><a href="/course/divemaster/">Divemaster</a><a href="/course/instructor-course/">Instructor</a></nav><div class="wd-footer-col"><h3>Contact</h3><p>Email: info@whaledivecentre.com</p><p>Phone: (021) 27939068</p><p>Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240</p><div class="wd-social"><a href="https://www.instagram.com/whaledivecentre.id?igsh=YjE1Z3o4NjBmcjAy" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a></div></div></div><div class="wd-footer-bottom"><span>© <?php echo date('Y'); ?> Whale Dive Centre. All rights reserved.</span><span>PADI / SSI / NAUI / TDI training pathways</span></div></div></footer>
</main>
<script>document.addEventListener('DOMContentLoaded',function(){var path=location.pathname;document.querySelectorAll('.wd-menu a[data-nav]').forEach(function(a){var key=a.getAttribute('data-nav');var active=(key==='home'&&path==='/')||(key!=='home'&&path.indexOf('/'+key+'/')===0);if(active){a.classList.add('is-active');a.setAttribute('aria-current','page');}});});</script><?php wp_footer(); ?>
</body>
</html>