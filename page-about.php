<?php
/**
 * Template Name: About Page
 */
get_header();
$theme_uri = get_template_directory_uri();
?>

<main class="wd-page whaledive-inner">
  <!-- HERO -->
  <section class="wd-inner-hero wd-about-hero">
    <div class="wd-shell">
      <div class="wd-inner-grid">
        <div>
          <span class="wd-kicker">About Whale Dive Centre</span>
          <h1>Calm training. Better habits. Safer dives.</h1>
          <p>A Bali-based dive centre focused on professional scuba training, quality gear, and an ocean-minded community.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- OUR CREW -->
  <section class="wd-section white">
    <div class="wd-shell">
      <span class="wd-kicker">Our crew</span>
      <h2 class="wd-title">The people behind your dive experience</h2>
      <p class="wd-sub">Small team, personal attention. Every crew member is here because they love the ocean.</p>
      <div class="wd-about-crew-grid">
        <div class="wd-crew-card">
          <div class="wd-crew-photo"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop&crop=faces" alt="Kadek Arya"></div>
          <h3>Kadek Arya</h3>
          <span class="wd-crew-role">PADI Course Director</span>
        </div>
        <div class="wd-crew-card">
          <div class="wd-crew-photo"><img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop&crop=faces" alt="Made Surya"></div>
          <h3>Made Surya</h3>
          <span class="wd-crew-role">Senior Instructor</span>
        </div>
        <div class="wd-crew-card">
          <div class="wd-crew-photo"><img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop&crop=faces" alt="Wayan Dika"></div>
          <h3>Wayan Dika</h3>
          <span class="wd-crew-role">Dive Guide & Safety Officer</span>
        </div>
        <div class="wd-crew-card">
          <div class="wd-crew-photo"><img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop&crop=faces" alt="Putu Rani"></div>
          <h3>Putu Rani</h3>
          <span class="wd-crew-role">Equipment Specialist</span>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW WE WORK -->
  <section class="wd-section wd-dark">
    <div class="wd-shell wd-split">
      <div>
        <span class="wd-kicker">How we work</span>
        <h2 class="wd-title">Calm briefings. Better habits. Safer dives.</h2>
        <p class="wd-sub left">We keep the experience personal so divers can ask questions, repeat skills, and grow at the right pace.</p>
      </div>
      <div class="wd-steps">
        <div>
          <span style="font-size:48px;font-weight:900;color:rgba(255,255,255,.3);">01</span>
          <h3 style="margin:12px 0 8px;color:#fff;">Before the dive</h3>
          <p style="color:rgba(255,255,255,.7);margin:0;">Clear plan, gear check, site conditions, and skill expectations.</p>
        </div>
        <div>
          <span style="font-size:48px;font-weight:900;color:rgba(255,255,255,.3);">02</span>
          <h3 style="margin:12px 0 8px;color:#fff;">During the dive</h3>
          <p style="color:rgba(255,255,255,.7);margin:0;">Small-group awareness, relaxed pacing, and safety-first decisions.</p>
        </div>
        <div>
          <span style="font-size:48px;font-weight:900;color:rgba(255,255,255,.3);">03</span>
          <h3 style="margin:12px 0 8px;color:#fff;">After the dive</h3>
          <p style="color:rgba(255,255,255,.7);margin:0;">Debrief, next-step coaching, and recommendations for training or gear.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section class="wd-section wd-contact-section">
    <div class="wd-shell">
      <div class="wd-contact-grid">
        <div>
          <span class="wd-kicker">Get in touch</span>
          <h2 class="wd-title">Start the conversation</h2>
          <p class="wd-sub left">Ask about courses, gear, scheduling, or anything else. The crew replies within 24 hours.</p>
          <div class="wd-contact-cards">
            <div>
              <b>Email</b>
              <span>info@whaledivecentre.com</span>
            </div>
            <div>
              <b>WhatsApp</b>
              <span>+62 xxx xxxx xxxx</span>
            </div>
            <div>
              <b>Location</b>
              <span>Bali, Indonesia</span>
            </div>
          </div>
        </div>
        <div>
          <form class="wd-contact-form" method="post">
            <label>
              Your name
              <input type="text" name="name" required>
            </label>
            <label>
              WhatsApp / Email
              <input type="text" name="contact" required>
            </label>
            <label>
              What do you need?
              <select name="inquiry_type">
                <option>Course inquiry</option>
                <option>Equipment availability</option>
                <option>General question</option>
              </select>
            </label>
            <label>
              Message
              <textarea name="message"></textarea>
            </label>
            <button type="submit" class="wd-btn">Send Inquiry</button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>


<script>
document.addEventListener('DOMContentLoaded', function(){
  var p = new URLSearchParams(window.location.search);
  var name = p.get('name');
  if(name){
    var nameInput = document.querySelector('input[name="your-name"], input[placeholder*="name" i], input[name="name"]');
    if(nameInput) nameInput.value = name;
    var waInput = document.querySelector('input[name="whatsapp"], input[name="your-whatsapp"]');
    if(waInput && p.get('whatsapp')) waInput.value = p.get('whatsapp');
    var msgInput = document.querySelector('textarea, input[name="message"]');
    if(msgInput){
      var msg = 'Course inquiry from homepage:\n';
      if(p.get('cert')) msg += 'Certification: ' + p.get('cert') + '\n';
      if(p.get('schedule')) msg += 'Schedule: ' + p.get('schedule') + '\n';
      if(p.get('group')) msg += 'Group: ' + p.get('group');
      msgInput.value = msg;
    }
    document.getElementById('contact-form')?.scrollIntoView({behavior:'smooth'});
  }
});
</script>

<?php get_footer(); ?>
