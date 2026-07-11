<?php
/**
 * Template Name: Testimonials
 * Description: Customer testimonials and reviews page for Whale Dive Centre
 */
$testimonials = array(
  array('name' => 'Sarah Mitchell', 'course' => 'Open Water Diver', 'rating' => 5, 'text' => 'The instructors at Whale Dive Centre made my first diving experience unforgettable. Patient, professional, and genuinely passionate about the ocean.', 'date' => 'March 2026'),
  array('name' => 'David Chen', 'course' => 'Advanced Open Water', 'rating' => 5, 'text' => 'Small group sizes and personalized attention made all the difference. The crew took time to help me improve buoyancy and navigation.', 'date' => 'February 2026'),
  array('name' => 'Emma Rodriguez', 'course' => 'Rescue Diver', 'rating' => 5, 'text' => 'This course changed how I think about diving safety. The scenarios were realistic and the feedback was constructive.', 'date' => 'January 2026'),
  array('name' => 'James Wilson', 'course' => 'Divemaster', 'rating' => 5, 'text' => 'Great mentorship, real-world experience, and a supportive community. This is where I became a dive professional.', 'date' => 'December 2025'),
  array('name' => 'Lisa Anderson', 'course' => 'Open Water Diver', 'rating' => 5, 'text' => 'I was nervous about diving, but the crew made me feel comfortable from day one. Equipment was well-maintained and the vibe was calm.', 'date' => 'November 2025'),
  array('name' => 'Michael Brown', 'course' => 'Advanced Open Water', 'rating' => 5, 'text' => 'Excellent instruction and thoughtful briefing. Deep dive and wreck dive became highlights of my training path.', 'date' => 'October 2025'),
  array('name' => 'Sophie Taylor', 'course' => 'Rescue Diver', 'rating' => 5, 'text' => 'Challenging but rewarding. The instructors pushed me to think critically and act decisively.', 'date' => 'September 2025'),
  array('name' => 'Ryan Martinez', 'course' => 'Open Water Diver', 'rating' => 5, 'text' => 'Friendly, knowledgeable, and safety-focused. I completed certification and stayed connected with the community.', 'date' => 'August 2025'),
  array('name' => 'Anna Kim', 'course' => 'Divemaster', 'rating' => 5, 'text' => 'Hands-on experience with real students and dive operations. The crew treated me like family.', 'date' => 'July 2025'),
);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?>
<style id="wd-testimonials-page-polish">
body.whaledive-testimonials .wd-testimonials-hero{padding:132px 0 56px;background:linear-gradient(135deg,#03172d 0%,#004A98 100%);color:#fff}
body.whaledive-testimonials .wd-testimonials-hero .wd-kicker{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.2);color:#fff}
body.whaledive-testimonials .wd-testimonials-hero h1{margin:14px 0 12px;font-size:clamp(34px,6vw,52px);line-height:1.05;color:#fff}
body.whaledive-testimonials .wd-testimonials-hero p{margin:0;max-width:680px;color:rgba(255,255,255,.88);line-height:1.7}
body.whaledive-testimonials .wd-stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
body.whaledive-testimonials .wd-stat-item{padding:22px 18px;border-radius:22px;background:linear-gradient(180deg,#fff,#eef8fb);border:1px solid rgba(6,56,77,.1);box-shadow:0 14px 34px rgba(2,32,46,.06);text-align:center}
body.whaledive-testimonials .wd-stat-number{font-size:30px;font-weight:950;color:#04172d;line-height:1}
body.whaledive-testimonials .wd-stat-label{margin-top:8px;color:#5b7180;font-weight:700;font-size:13px}
body.whaledive-testimonials .wd-testimonials-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
body.whaledive-testimonials .wd-testimonial-card{padding:22px;border-radius:22px;background:#fff;border:1px solid rgba(6,56,77,.1);box-shadow:0 14px 34px rgba(2,32,46,.07)}
body.whaledive-testimonials .wd-testimonial-rating{color:#f5b301;letter-spacing:1px;margin-bottom:10px}
body.whaledive-testimonials .wd-testimonial-text{margin:0 0 16px;color:#334155;line-height:1.65}
body.whaledive-testimonials .wd-testimonial-author{display:flex;gap:12px;align-items:center}
body.whaledive-testimonials .wd-testimonial-avatar{width:42px;height:42px;border-radius:999px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#004A98,#3B44AC);color:#fff;font-weight:900}
body.whaledive-testimonials .wd-testimonial-info strong{display:block;color:#04172d}
body.whaledive-testimonials .wd-testimonial-info span{color:#64748b;font-size:13px}
body.whaledive-testimonials .wd-testimonials-cta .wd-shell{padding:34px 28px;border-radius:28px;background:linear-gradient(145deg,#f7fcff,#e8f7fb);border:1px solid rgba(6,56,77,.12);box-shadow:0 16px 40px rgba(2,32,46,.07);text-align:center}
body.whaledive-testimonials .wd-testimonials-cta h2{margin:0 0 10px;color:#04172d;font-size:clamp(28px,5vw,40px)}
body.whaledive-testimonials .wd-testimonials-cta p{margin:0 auto 18px;max-width:640px;color:#475569}
body.whaledive-testimonials .wd-testimonials-cta-buttons{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
@media(max-width:900px){
  body.whaledive-testimonials .wd-stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  body.whaledive-testimonials .wd-testimonials-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:560px){
  body.whaledive-testimonials .wd-testimonials-hero{padding:110px 0 40px}
  body.whaledive-testimonials .wd-stats-grid,body.whaledive-testimonials .wd-testimonials-grid{grid-template-columns:1fr}
  body.whaledive-testimonials .wd-testimonials-cta-buttons .wd-btn{width:100%}
}
</style>
</head>
<body <?php body_class('whaledive-inner whaledive-testimonials'); ?>><?php wp_body_open(); ?>
<main class="wd-page">
  <?php contenly_render_public_header(); ?>

  <section class="wd-testimonials-hero">
    <div class="wd-shell">
      <span class="wd-kicker"><?php echo esc_html(contenly_tr('Cerita diver', 'Diver stories')); ?></span>
      <h1><?php echo esc_html(contenly_tr('Apa kata diver kami.', 'What our divers say.')); ?></h1>
      <p><?php echo esc_html(contenly_tr('Pengalaman nyata dari siswa, diver tersertifikasi, dan anggota komunitas yang berlatih bersama Whale Dive Centre.', 'Real experiences from students, certified divers, and community members who trained with Whale Dive Centre.')); ?></p>
    </div>
  </section>

  <section class="wd-section white">
    <div class="wd-shell">
      <div class="wd-stats-grid">
        <div class="wd-stat-item"><div class="wd-stat-number">500+</div><div class="wd-stat-label"><?php echo esc_html(contenly_tr('Diver tersertifikasi', 'Certified divers')); ?></div></div>
        <div class="wd-stat-item"><div class="wd-stat-number">4.9/5</div><div class="wd-stat-label"><?php echo esc_html(contenly_tr('Rating rata-rata', 'Average rating')); ?></div></div>
        <div class="wd-stat-item"><div class="wd-stat-number">98%</div><div class="wd-stat-label"><?php echo esc_html(contenly_tr('Akan merekomendasikan', 'Would recommend')); ?></div></div>
        <div class="wd-stat-item"><div class="wd-stat-number">1000+</div><div class="wd-stat-label"><?php echo esc_html(contenly_tr('Dive trips', 'Dive trips')); ?></div></div>
      </div>
    </div>
  </section>

  <section class="wd-section">
    <div class="wd-shell">
      <div class="wd-testimonials-grid">
        <?php foreach ($testimonials as $testimonial) : ?>
          <article class="wd-testimonial-card">
            <div class="wd-testimonial-rating" aria-label="<?php echo (int) $testimonial['rating']; ?> stars">
              <?php for ($i = 0; $i < (int) $testimonial['rating']; $i++) : ?><span class="wd-star">★</span><?php endfor; ?>
            </div>
            <p class="wd-testimonial-text">"<?php echo esc_html($testimonial['text']); ?>"</p>
            <div class="wd-testimonial-author">
              <div class="wd-testimonial-avatar"><?php echo esc_html(strtoupper(substr($testimonial['name'], 0, 1))); ?></div>
              <div class="wd-testimonial-info">
                <strong><?php echo esc_html($testimonial['name']); ?></strong>
                <span><?php echo esc_html($testimonial['course']); ?> · <?php echo esc_html($testimonial['date']); ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="wd-section white wd-testimonials-cta">
    <div class="wd-shell">
      <h2><?php echo esc_html(contenly_tr('Siap mulai perjalanan dive kamu?', 'Ready to start your dive journey?')); ?></h2>
      <p><?php echo esc_html(contenly_tr('Gabung ratusan diver tersertifikasi yang berlatih di Whale Dive Centre. Grup kecil, instruksi profesional, komunitas yang peduli laut.', 'Join hundreds of certified divers who trained with Whale Dive Centre. Small groups, professional instruction, and an ocean-minded community.')); ?></p>
      <div class="wd-testimonials-cta-buttons">
        <a class="wd-btn" href="<?php echo esc_url(home_url('/courses/')); ?>"><?php echo esc_html(contenly_tr('Lihat Kursus', 'View Courses')); ?></a>
        <a class="wd-btn alt" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html(contenly_tr('Hubungi Kami', 'Contact Us')); ?></a>
      </div>
    </div>
  </section>

  <?php contenly_render_public_footer(); ?>
</main>
<?php wp_footer(); ?></body></html>
