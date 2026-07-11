<?php
/**
 * Template Name: Trip Packages
 * Description: Dive trip packages listing for Whale Dive Centre
 */
$trips = array(
  array(
    'badge' => 'POPULAR',
    'category' => 'DAY TRIP',
    'title' => 'Nusa Penida Day Dive',
    'desc' => 'Two dives at Manta Point and Crystal Bay. Encounter manta rays, mola mola (seasonal), and vibrant coral walls.',
    'features' => array('2 guided dives', 'Boat transfer included', 'Lunch & refreshments', 'Max 6 divers per guide'),
    'price' => 'Rp 1.200.000',
    'color' => '3B44AC',
    'label' => 'Nusa+Penida',
  ),
  array(
    'badge' => '',
    'category' => 'DAY TRIP',
    'title' => 'Tulamben USAT Liberty Wreck',
    'desc' => "Explore Bali's famous wreck dive. Swim through the Liberty shipwreck and discover macro life at the Drop Off.",
    'features' => array('2 guided dives', 'Land transfer included', 'Breakfast & lunch', 'Wreck dive briefing'),
    'price' => 'Rp 950.000',
    'color' => '004A98',
    'label' => 'Tulamben',
  ),
  array(
    'badge' => '',
    'category' => 'DAY TRIP',
    'title' => 'Amed Coral Gardens',
    'desc' => 'Relaxed shore dives in calm waters. Perfect for newer divers or underwater photography.',
    'features' => array('2 guided shore dives', 'Land transfer included', 'Lunch & refreshments', 'Beginner-friendly'),
    'price' => 'Rp 850.000',
    'color' => '0b617c',
    'label' => 'Amed',
  ),
  array(
    'badge' => 'WEEKEND',
    'category' => '2 DAYS / 1 NIGHT',
    'title' => 'Bali Weekend Dive Escape',
    'desc' => 'Two-day dive adventure covering Tulamben, Amed, and Padang Bai. Accommodation, meals, and 4 dives included.',
    'features' => array('4 guided dives', '1 night accommodation', 'All meals included', 'Land transfer included'),
    'price' => 'Rp 2.800.000',
    'color' => 'C31C4A',
    'label' => 'Weekend',
  ),
  array(
    'badge' => 'LIVEABOARD',
    'category' => '4 DAYS / 3 NIGHTS',
    'title' => 'Komodo Liveaboard Adventure',
    'desc' => 'Multi-day liveaboard to Komodo National Park. Dive with manta rays, sharks, and pristine coral reefs.',
    'features' => array('10+ guided dives', '3 nights onboard', 'All meals & snacks', 'Nitrox available'),
    'price' => 'Rp 12.500.000',
    'color' => '03172d',
    'label' => 'Komodo',
  ),
  array(
    'badge' => '',
    'category' => 'SPECIALTY',
    'title' => 'Padang Bai Night Dive',
    'desc' => 'Experience the underwater world after dark. Spot nocturnal marine life and bioluminescence.',
    'features' => array('1 guided night dive', 'Dive light provided', 'Safety briefing', 'AOW recommended'),
    'price' => 'Rp 550.000',
    'color' => '000000',
    'label' => 'Night+Dive',
  ),
);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
<style id="wd-trips-page-polish">
body.whaledive-trips .wd-trips-hero{padding:132px 0 56px;background:linear-gradient(135deg,#004A98 0%,#03172d 100%);color:#fff}
body.whaledive-trips .wd-trips-hero .wd-kicker{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.2);color:#fff}
body.whaledive-trips .wd-trips-hero h1{margin:14px 0 12px;font-size:clamp(34px,6vw,52px);line-height:1.05;color:#fff}
body.whaledive-trips .wd-trips-hero p{margin:0;max-width:720px;color:rgba(255,255,255,.88);line-height:1.7}
body.whaledive-trips .wd-trips-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
body.whaledive-trips .wd-trip-card{display:flex;flex-direction:column;overflow:hidden;border-radius:24px;background:#fff;border:1px solid rgba(6,56,77,.1);box-shadow:0 16px 40px rgba(2,32,46,.08)}
body.whaledive-trips .wd-trip-image{position:relative;min-height:170px;background-size:cover;background-position:center}
body.whaledive-trips .wd-trip-badge{position:absolute;top:14px;left:14px;padding:7px 12px;border-radius:999px;background:rgba(255,255,255,.92);color:#04172d;font-size:11px;font-weight:900;letter-spacing:.06em}
body.whaledive-trips .wd-trip-content{display:flex;flex-direction:column;gap:10px;padding:18px;flex:1}
body.whaledive-trips .wd-trip-category{color:#0b617c;font-size:12px;font-weight:900;letter-spacing:.08em}
body.whaledive-trips .wd-trip-content h3{margin:0;color:#04172d;font-size:22px;line-height:1.2}
body.whaledive-trips .wd-trip-description{margin:0;color:#516b7a;line-height:1.6;font-size:14px}
body.whaledive-trips .wd-trip-features{margin:0;padding:0;list-style:none;display:grid;gap:6px;color:#334155;font-size:13px}
body.whaledive-trips .wd-trip-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:auto;padding-top:12px;border-top:1px solid rgba(6,56,77,.08)}
body.whaledive-trips .wd-price-label{display:block;color:#64748b;font-size:11px;font-weight:800;text-transform:uppercase}
body.whaledive-trips .wd-price-amount{color:#04172d;font-size:18px;font-weight:950}
body.whaledive-trips .wd-included-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
body.whaledive-trips .wd-included-item{padding:20px;border-radius:22px;background:linear-gradient(180deg,#fff,#eef8fb);border:1px solid rgba(6,56,77,.1);box-shadow:0 12px 30px rgba(2,32,46,.06)}
body.whaledive-trips .wd-included-item h4{margin:8px 0;color:#04172d}
body.whaledive-trips .wd-included-item p{margin:0;color:#5b7180;line-height:1.55;font-size:14px}
body.whaledive-trips .wd-trips-cta .wd-shell{padding:34px 28px;border-radius:28px;background:linear-gradient(145deg,#f7fcff,#e8f7fb);border:1px solid rgba(6,56,77,.12);box-shadow:0 16px 40px rgba(2,32,46,.07);text-align:center}
body.whaledive-trips .wd-trips-cta h2{margin:0 0 10px;color:#04172d;font-size:clamp(28px,5vw,40px)}
body.whaledive-trips .wd-trips-cta p{margin:0 auto 18px;max-width:640px;color:#475569}
@media(max-width:980px){
  body.whaledive-trips .wd-trips-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  body.whaledive-trips .wd-included-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:560px){
  body.whaledive-trips .wd-trips-hero{padding:110px 0 40px}
  body.whaledive-trips .wd-trips-grid,body.whaledive-trips .wd-included-grid{grid-template-columns:1fr}
  body.whaledive-trips .wd-trip-footer{flex-direction:column;align-items:stretch}
  body.whaledive-trips .wd-trip-footer .wd-btn{width:100%;justify-content:center}
}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-trips'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-trips-hero">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Petualangan selam', 'Dive adventures')); ?></span>
      <h1><?php echo esc_html(contenly_tr('Jelajahi dunia bawah air bersama crew.', 'Explore the underwater world with the crew.')); ?></h1>
      <p><?php echo esc_html(contenly_tr('Trip terpandu, weekend adventure, dan liveaboard multi-hari untuk diver tersertifikasi. Grup kecil, guide berpengalaman, dan eksplorasi yang peduli laut.', 'Guided dive trips, weekend adventures, and multi-day liveaboards for certified divers. Small groups, experienced guides, and conservation-minded exploration.')); ?></p>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Paket dive', 'Dive packages')); ?></span>
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Tanya crew untuk rekomendasi site terkini', 'Ask the crew for current dive site recommendations')); ?></h2>
      <p class="wd-sub"><?php echo esc_html(contenly_tr('Semua trip mencakup guide berpengalaman, opsi sewa peralatan, dan briefing konservasi laut.', 'All trips include experienced dive guides, equipment rental options, and marine conservation briefings.')); ?></p>

      <div class="wd-trips-grid" style="margin-top:28px">
        <?php foreach ($trips as $trip) :
          $bg = 'https://placehold.co/800x500/' . rawurlencode($trip['color']) . '/FFFFFF?text=' . rawurlencode($trip['label']);
          ?>
          <article class="wd-trip-card">
            <div class="wd-trip-image" style="background-image:url('<?php echo esc_url($bg); ?>')">
              <?php if (!empty($trip['badge'])) : ?><div class="wd-trip-badge"><?php echo esc_html($trip['badge']); ?></div><?php endif; ?>
            </div>
            <div class="wd-trip-content">
              <span class="wd-trip-category"><?php echo esc_html($trip['category']); ?></span>
              <h3><?php echo esc_html($trip['title']); ?></h3>
              <p class="wd-trip-description"><?php echo esc_html($trip['desc']); ?></p>
              <ul class="wd-trip-features">
                <?php foreach ($trip['features'] as $feature) : ?><li>✓ <?php echo esc_html($feature); ?></li><?php endforeach; ?>
              </ul>
              <div class="wd-trip-footer">
                <div>
                  <span class="wd-price-label"><?php echo esc_html(contenly_tr('Mulai dari', 'From')); ?></span>
                  <span class="wd-price-amount"><?php echo esc_html($trip['price']); ?></span>
                </div>
                <a class="wd-btn" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html(contenly_tr('Booking Trip', 'Book Trip')); ?></a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wd-section">
    <div class="wd-shell">
      <h2 class="wd-title"><?php echo esc_html(contenly_tr('Yang termasuk di setiap trip', "What's included in every trip")); ?></h2>
      <div class="wd-included-grid" style="margin-top:22px">
        <div class="wd-included-item"><div class="wd-included-icon">01</div><h4><?php echo esc_html(contenly_tr('Guide berpengalaman', 'Experienced guides')); ?></h4><p><?php echo esc_html(contenly_tr('Guide bersertifikat dengan pengetahuan site lokal.', 'Certified dive guides with local site knowledge.')); ?></p></div>
        <div class="wd-included-item"><div class="wd-included-icon">02</div><h4><?php echo esc_html(contenly_tr('Safety first', 'Safety first')); ?></h4><p><?php echo esc_html(contenly_tr('Oxygen kit, first aid, dan protokol darurat di setiap trip.', 'Oxygen kit, first aid, and emergency protocols on every trip.')); ?></p></div>
        <div class="wd-included-item"><div class="wd-included-icon">03</div><h4><?php echo esc_html(contenly_tr('Grup kecil', 'Small groups')); ?></h4><p><?php echo esc_html(contenly_tr('Maksimal 6 diver per guide untuk perhatian lebih personal.', 'Maximum 6 divers per guide for personalized attention.')); ?></p></div>
        <div class="wd-included-item"><div class="wd-included-icon">04</div><h4><?php echo esc_html(contenly_tr('Fokus konservasi', 'Conservation focus')); ?></h4><p><?php echo esc_html(contenly_tr('Briefing biota laut dan praktik dive yang ramah reef.', 'Marine life briefings and reef-safe diving practices.')); ?></p></div>
      </div>
    </div>
  </section>

  <section class="wd-section white wd-trips-cta">
    <div class="wd-shell">
      <h2><?php echo esc_html(contenly_tr('Siap eksplor dive site berikutnya?', 'Ready to explore the next dive sites?')); ?></h2>
      <p><?php echo esc_html(contenly_tr('Hubungi crew untuk cek ketersediaan, tanya detail, atau custom trip privat untuk grup kamu.', 'Contact the crew to check availability, ask questions, or customize a private dive trip for your group.')); ?></p>
      <a class="wd-btn" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html(contenly_tr('Rencanakan Trip', 'Plan Your Trip')); ?></a>
    </div>
  </section>

  <?php contenly_render_public_footer(); ?>
</main>
<?php wp_footer(); ?></body></html>
