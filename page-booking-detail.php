<?php
/**
 * Template Name: Booking Detail Page
 */

if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    $booking_request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : contenly_localized_url('/booking-detail/');
    wp_redirect(add_query_arg('redirect_to', $booking_request_uri, contenly_localized_url('/login/')));
    exit;
}

$tour_id = isset($_GET['tour_id']) ? absint($_GET['tour_id']) : 0;
$tour = $tour_id ? get_post($tour_id) : null;
if (!$tour || $tour->post_type !== 'tour') {
    wp_redirect(contenly_localized_url('/tour-packages/'));
    exit;
}

$price = absint(get_post_meta($tour_id, '_tour_price', true) ?: get_post_meta($tour_id, 'price', true));
$child_price = absint(get_post_meta($tour_id, '_tour_child_price', true));
if ($child_price <= 0) {
    $child_price = $price;
}
$quota = absint(get_post_meta($tour_id, '_tour_quota', true) ?: 20);
$min_pax = absint(get_post_meta($tour_id, '_tour_min_pax', true) ?: 1);
$duration = get_post_meta($tour_id, '_tour_duration_days', true) ?: get_post_meta($tour_id, 'duration', true);
$location = get_post_meta($tour_id, '_tour_location', true) ?: get_post_meta($tour_id, 'location', true);
$user = wp_get_current_user();
$featured_image = get_the_post_thumbnail_url($tour_id, 'large');
$featured_image = $featured_image ?: get_template_directory_uri() . '/assets/images/hero-placeholder.jpg';
$contact_method_default = 'whatsapp';

get_header();
?>

<main class="site-main booking-request-page">
  <div class="site-container booking-request-shell">
    <div class="booking-request-header">
      <div class="booking-request-breadcrumb">
        <a href="<?php echo esc_url(contenly_localized_url('/')); ?>">Home</a>
        <span>›</span>
        <a href="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>">Tour Packages</a>
        <span>›</span>
        <a href="<?php echo esc_url(get_permalink($tour_id)); ?>"><?php echo esc_html(get_the_title($tour_id)); ?></a>
        <span>›</span>
        <span><?php echo esc_html(contenly_tr('Pesan Tour', 'Book Tour')); ?></span>
      </div>
      <div class="booking-request-header-copy">
        <h1><?php echo esc_html(contenly_tr('Pesan Tour', 'Book Tour')); ?></h1>
        <p><?php echo esc_html(contenly_tr('Isi detail perjalanan dulu, lalu tim Whale Dive Centre akan review dan konfirmasi booking kamu sebelum lanjut ke tahap berikutnya.', 'Fill in your trip details first, then the Whale Dive Centre team will review and confirm your booking before you move to the next stage.')); ?></p>
      </div>
    </div>

    <div class="booking-request-stepper" id="booking-request-stepper">
      <div class="booking-step-chip is-active" data-step-chip="1"><span>1</span><strong><?php echo esc_html(contenly_tr('Detail Trip', 'Trip Details')); ?></strong></div>
      <div class="booking-step-chip" data-step-chip="2"><span>2</span><strong><?php echo esc_html(contenly_tr('Data Pemesan', 'Booker Details')); ?></strong></div>
      <div class="booking-step-chip" data-step-chip="3"><span>3</span><strong><?php echo esc_html(contenly_tr('Data Peserta', 'Traveller Details')); ?></strong></div>
      <div class="booking-step-chip" data-step-chip="4"><span>4</span><strong><?php echo esc_html(contenly_tr('Review', 'Review')); ?></strong></div>
    </div>

    <div class="booking-request-layout">
      <section class="booking-request-form-wrap">
        <form id="tour-request-form" class="booking-request-form" novalidate>
          <input type="hidden" name="tour_id" value="<?php echo esc_attr($tour_id); ?>">

          <section class="booking-step-panel is-active" data-step-panel="1">
            <div class="step-panel-head">
              <h2><?php echo esc_html(contenly_tr('Detail Trip', 'Trip Details')); ?></h2>
              <p><?php echo esc_html(contenly_tr('Tentukan paket, tanggal, jumlah peserta, dan preferensi keberangkatan dulu.', 'Choose the package, date, participant count, and departure preferences first.')); ?></p>
            </div>

            <div class="form-group">
              <label class="field-label"><?php echo esc_html(contenly_tr('Pilih Paket Tour', 'Choose Tour Package')); ?></label>
              <div class="option-cards package-options">
                <label class="option-card is-selected" data-option-card>
                  <input type="radio" name="package_type" value="open-trip" checked>
                  <strong>Open Trip</strong>
                  <span><?php echo esc_html(contenly_tr('Cocok buat solo traveler atau small group yang cari harga lebih efisien.', 'Suitable for solo travellers or small groups looking for a more efficient price.')); ?></span>
                </label>
                <label class="option-card" data-option-card>
                  <input type="radio" name="package_type" value="private-trip">
                  <strong>Private Trip</strong>
                  <span><?php echo esc_html(contenly_tr('Lebih fleksibel buat couple, family, atau group kecil yang pengin ritme sendiri.', 'More flexible for couples, families, or small groups who want their own pace.')); ?></span>
                </label>
                <label class="option-card" data-option-card>
                  <input type="radio" name="package_type" value="family-trip">
                  <strong>Family Trip</strong>
                  <span><?php echo esc_html(contenly_tr('Setup lebih nyaman untuk trip keluarga dengan pace yang lebih santai.', 'A more comfortable setup for family trips with a more relaxed pace.')); ?></span>
                </label>
              </div>
              <div class="field-error" data-error-for="package_type"></div>
            </div>

            <div class="form-row form-row-2">
              <div class="form-group">
                <label class="field-label" for="departure_date"><?php echo esc_html(contenly_tr('Tanggal Keberangkatan', 'Departure Date')); ?></label>
                <input class="input-control" id="departure_date" type="date" name="departure_date" min="<?php echo esc_attr(date('Y-m-d', strtotime('+1 day'))); ?>">
                <div class="field-error" data-error-for="departure_date"></div>
              </div>
              <div class="form-group">
                <label class="field-label" for="preferred_time"><?php echo esc_html(contenly_tr('Jam Preferensi', 'Preferred Time')); ?></label>
                <select class="select-control" id="preferred_time" name="preferred_time">
                  <option value=""><?php echo esc_html(contenly_tr('Pilih jam', 'Choose time')); ?></option>
                  <option value="pagi"><?php echo esc_html(contenly_tr('Pagi', 'Morning')); ?></option>
                  <option value="siang"><?php echo esc_html(contenly_tr('Siang', 'Afternoon')); ?></option>
                  <option value="fleksibel"><?php echo esc_html(contenly_tr('Fleksibel', 'Flexible')); ?></option>
                </select>
              </div>
            </div>

            <div class="form-row form-row-2 count-row">
              <div class="form-group">
                <label class="field-label" for="adult_count"><?php echo esc_html(contenly_tr('Jumlah Dewasa', 'Adults')); ?></label>
                <div class="count-control">
                  <button type="button" class="count-btn" data-count-target="adult_count" data-action="decrease">−</button>
                  <input class="input-control count-input" id="adult_count" type="number" name="adult_count" min="1" max="<?php echo esc_attr($quota); ?>" value="<?php echo esc_attr(max(1, $min_pax)); ?>">
                  <button type="button" class="count-btn" data-count-target="adult_count" data-action="increase">+</button>
                </div>
                <div class="field-error" data-error-for="adult_count"></div>
              </div>
              <div class="form-group">
                <label class="field-label" for="child_count"><?php echo esc_html(contenly_tr('Jumlah Anak', 'Children')); ?></label>
                <div class="count-control">
                  <button type="button" class="count-btn" data-count-target="child_count" data-action="decrease">−</button>
                  <input class="input-control count-input" id="child_count" type="number" name="child_count" min="0" max="<?php echo esc_attr($quota); ?>" value="0">
                  <button type="button" class="count-btn" data-count-target="child_count" data-action="increase">+</button>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="field-label"><?php echo esc_html(contenly_tr('Titik Keberangkatan', 'Departure Point')); ?></label>
              <div class="option-cards departure-options compact">
                <label class="option-card is-selected" data-option-card>
                  <input type="radio" name="departure_type" value="meeting-point" checked>
                  <strong>Meeting Point</strong>
                  <span><?php echo esc_html(contenly_tr('Ketemu di titik kumpul yang ditentukan tim.', 'Meet at the meeting point determined by the team.')); ?></span>
                </label>
                <label class="option-card" data-option-card>
                  <input type="radio" name="departure_type" value="hotel-pickup">
                  <strong>Hotel Pickup</strong>
                  <span><?php echo esc_html(contenly_tr('Cocok kalau mau lebih praktis dari hotel.', 'Ideal if you want a more practical pickup from the hotel.')); ?></span>
                </label>
                <label class="option-card" data-option-card>
                  <input type="radio" name="departure_type" value="custom-location">
                  <strong><?php echo esc_html(contenly_tr('Custom Lokasi', 'Custom Location')); ?></strong>
                  <span><?php echo esc_html(contenly_tr('Ada titik jemput lain yang ingin diajukan.', 'If you want to request another pickup point.')); ?></span>
                </label>
              </div>
              <div class="field-error" data-error-for="departure_type"></div>
            </div>

            <div class="form-group is-hidden" id="pickup-location-group">
              <label class="field-label" for="pickup_location"><?php echo esc_html(contenly_tr('Lokasi Jemput / Detail Meeting', 'Pickup Location / Meeting Details')); ?></label>
              <textarea class="textarea-control" id="pickup_location" name="pickup_location" rows="4" placeholder="<?php echo esc_attr(contenly_tr('Contoh: Hotel Atria Bangkok, lobby utama / meeting point custom lainnya.', 'Example: Hotel Atria Bangkok, main lobby / another custom meeting point.')); ?>"></textarea>
              <div class="field-error" data-error-for="pickup_location"></div>
            </div>

            <div class="form-group">
              <label class="field-label" for="special_request"><?php echo esc_html(contenly_tr('Catatan Tambahan', 'Additional Notes')); ?></label>
              <textarea class="textarea-control" id="special_request" name="special_request" rows="4" placeholder="<?php echo esc_attr(contenly_tr('Misal bawa orang tua, pengin pace lebih santai, ada request child seat, atau kebutuhan khusus lainnya.', 'For example: travelling with parents, wanting a slower pace, needing a child seat, or any other special request.')); ?>"></textarea>
            </div>

            <div class="step-actions">
              <a class="btn-secondary btn-link" href="<?php echo esc_url(get_permalink($tour_id)); ?>"><?php echo esc_html(contenly_tr('Kembali ke Detail Tour', 'Back to Tour Details')); ?></a>
              <button type="button" class="btn-primary" data-next-step="2"><?php echo esc_html(contenly_tr('Lanjut ke Data Pemesan', 'Continue to Booker Details')); ?></button>
            </div>
          </section>

          <section class="booking-step-panel" data-step-panel="2">
            <div class="step-panel-head">
              <h2><?php echo esc_html(contenly_tr('Data Pemesan', 'Booker Details')); ?></h2>
              <p><?php echo esc_html(contenly_tr('Kami pakai data ini untuk kirim update dan follow up booking kamu.', 'We use these details to send updates and follow up on your booking.')); ?></p>
            </div>

            <div class="form-row form-row-2">
              <div class="form-group">
                <label class="field-label" for="customer_name"><?php echo esc_html(contenly_tr('Nama Lengkap', 'Full Name')); ?></label>
                <input class="input-control" id="customer_name" type="text" name="customer_name" value="<?php echo esc_attr($user->display_name); ?>">
                <div class="field-error" data-error-for="customer_name"></div>
              </div>
              <div class="form-group">
                <label class="field-label" for="customer_email"><?php echo esc_html(contenly_tr('Email Aktif', 'Active Email')); ?></label>
                <input class="input-control" id="customer_email" type="email" name="customer_email" value="<?php echo esc_attr($user->user_email); ?>">
                <div class="field-error" data-error-for="customer_email"></div>
              </div>
            </div>

            <div class="form-row form-row-2">
              <div class="form-group">
                <label class="field-label" for="customer_phone"><?php echo esc_html(contenly_tr('Nomor HP Aktif', 'Active Phone Number')); ?></label>
                <input class="input-control" id="customer_phone" type="tel" name="customer_phone" value="<?php echo esc_attr(get_user_meta($user->ID, 'phone_number', true)); ?>">
                <div class="field-error" data-error-for="customer_phone"></div>
              </div>
              <div class="form-group">
                <label class="field-label" for="customer_city"><?php echo esc_html(contenly_tr('Kota Asal', 'Origin City')); ?></label>
                <input class="input-control" id="customer_city" type="text" name="customer_city" placeholder="<?php echo esc_attr(contenly_tr('Contoh: Jakarta', 'Example: Jakarta')); ?>">
              </div>
            </div>

            <div class="step-actions">
              <button type="button" class="btn-secondary" data-prev-step="1"><?php echo esc_html(contenly_tr('Kembali', 'Back')); ?></button>
              <button type="button" class="btn-primary" data-next-step="3"><?php echo esc_html(contenly_tr('Lanjut ke Data Peserta', 'Continue to Traveller Details')); ?></button>
            </div>
          </section>

          <section class="booking-step-panel" data-step-panel="3">
            <div class="step-panel-head">
              <h2><?php echo esc_html(contenly_tr('Data Peserta', 'Traveller Details')); ?></h2>
              <p><?php echo esc_html(contenly_tr('Isi data traveler utama dulu. Nama peserta lain akan menyesuaikan jumlah pax yang kamu pilih.', 'Fill in the lead traveller details first. Additional traveller names will follow the number of travellers you selected.')); ?></p>
            </div>

            <div class="form-group">
              <label class="field-label" for="lead_traveler_name"><?php echo esc_html(contenly_tr('Nama Peserta Utama', 'Lead Traveller Name')); ?></label>
              <input class="input-control" id="lead_traveler_name" type="text" name="lead_traveler_name" value="<?php echo esc_attr($user->display_name); ?>">
              <p class="field-helper"><?php echo esc_html(contenly_tr('Default-nya mengikuti nama pemesan. Ubah hanya kalau peserta utama berbeda dengan pemesan.', 'By default this follows the booker name. Change it only if the lead traveller is different from the booker.')); ?></p>
              <div class="field-error" data-error-for="lead_traveler_name"></div>
            </div>

            <div class="form-group">
              <label class="field-label"><?php echo esc_html(contenly_tr('Nama Peserta Lain', 'Other Traveller Names')); ?></label>
              <div id="participant-repeater" class="participant-repeater"></div>
            </div>

            <div class="step-actions">
              <button type="button" class="btn-secondary" data-prev-step="2"><?php echo esc_html(contenly_tr('Kembali', 'Back')); ?></button>
              <button type="button" class="btn-primary" data-next-step="4"><?php echo esc_html(contenly_tr('Review Booking', 'Review Booking')); ?></button>
            </div>
          </section>

          <section class="booking-step-panel" data-step-panel="4">
            <div class="step-panel-head">
              <h2><?php echo esc_html(contenly_tr('Review Booking', 'Review Booking')); ?></h2>
              <p><?php echo esc_html(contenly_tr('Pastikan semua detail udah sesuai sebelum dikirim ke tim kami.', 'Please make sure all details are correct before sending them to our team.')); ?></p>
            </div>

            <div class="review-card">
              <h3><?php echo esc_html(contenly_tr('Detail Trip', 'Trip Details')); ?></h3>
              <div class="review-grid" id="review-trip"></div>
            </div>

            <div class="review-card">
              <h3><?php echo esc_html(contenly_tr('Data Pemesan', 'Booker Details')); ?></h3>
              <div class="review-grid" id="review-customer"></div>
            </div>

            <div class="review-card">
              <h3><?php echo esc_html(contenly_tr('Data Peserta', 'Traveller Details')); ?></h3>
              <div class="review-grid" id="review-travelers"></div>
            </div>

            <div class="review-card">
              <h3><?php echo esc_html(contenly_tr('Ringkasan Biaya', 'Price Summary')); ?></h3>
              <div class="price-summary" id="review-pricing"></div>
            </div>

            <label class="checkbox-wrap">
              <input type="checkbox" id="terms_agree" name="terms_agree" value="1">
              <span><?php echo esc_html(contenly_tr('Saya setuju dengan syarat & ketentuan dan paham bahwa booking ini akan direview dulu oleh admin sebelum dikonfirmasi final.', 'I agree to the terms and conditions and understand that this booking request will be reviewed by the admin before final confirmation.')); ?></span>
            </label>
            <div class="field-error" data-error-for="terms_agree"></div>

            <div class="step-actions">
              <button type="button" class="btn-secondary" data-prev-step="3"><?php echo esc_html(contenly_tr('Kembali Edit', 'Back to Edit')); ?></button>
              <button type="submit" class="btn-primary" id="booking-request-submit"><?php echo esc_html(contenly_tr('Kirim Permintaan Booking', 'Send Booking Request')); ?></button>
            </div>
          </section>
        </form>
      </section>

      <aside class="booking-request-summary">
        <div class="summary-card">
          <div class="summary-thumb" style="background-image:url('<?php echo esc_url($featured_image); ?>');"></div>
          <div class="summary-badge"><?php echo esc_html(contenly_tr('Permintaan Booking', 'Booking Request')); ?></div>
          <h3><?php echo esc_html(get_the_title($tour_id)); ?></h3>
          <div class="summary-meta">
            <?php if ($duration) : ?><span><?php echo esc_html($duration); ?> <?php echo esc_html(contenly_tr('hari', 'days')); ?></span><?php endif; ?>
            <?php if ($location) : ?><span><?php echo esc_html($location); ?></span><?php endif; ?>
          </div>

          <div class="summary-list" id="booking-summary-live">
            <div><span><?php echo esc_html(contenly_tr('Paket', 'Package')); ?></span><strong id="summary-package">Open Trip</strong></div>
            <div><span><?php echo esc_html(contenly_tr('Tanggal', 'Date')); ?></span><strong id="summary-date"><?php echo esc_html(contenly_tr('Belum dipilih', 'Not selected yet')); ?></strong></div>
            <div><span><?php echo esc_html(contenly_tr('Peserta', 'Travellers')); ?></span><strong id="summary-pax"><?php echo esc_html(max(1, $min_pax)); ?> <?php echo esc_html(contenly_tr('dewasa', 'adults')); ?></strong></div>
            <div><span><?php echo esc_html(contenly_tr('Keberangkatan', 'Departure')); ?></span><strong id="summary-departure">Meeting Point</strong></div>
          </div>

          <div class="summary-total">
            <span><?php echo esc_html(contenly_tr('Estimasi Total', 'Estimated Total')); ?></span>
            <strong id="summary-total">Rp <?php echo esc_html(number_format($price * max(1, $min_pax), 0, ',', '.')); ?></strong>
          </div>

          <div class="summary-note">
            <?php echo esc_html(contenly_tr('Booking akan masuk dulu sebagai permintaan yang direview admin. Setelah itu tim Whale Dive Centre akan follow up sesuai kebutuhan trip kamu.', 'Your booking will first be submitted as a request for admin review. After that, the Whale Dive Centre team will follow up based on your trip needs.')); ?>
          </div>
        </div>
      </aside>
    </div>
  </div>
</main>

<style>
.booking-request-page{background:linear-gradient(180deg,#f7fbfc 0%,#ffffff 100%);min-height:80vh;padding:118px 0 48px}
.booking-request-shell{max-width:1200px;margin:0 auto;padding:0 18px}
.booking-request-header{margin-bottom:26px}
.booking-request-breadcrumb{display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:13px;color:#64748b;margin-bottom:14px}
.booking-request-breadcrumb a{color:#64748b;text-decoration:none}
.booking-request-header-copy h1{margin:0 0 10px;font-size:clamp(30px,4vw,42px);line-height:1.1;color:#0f172a;font-weight:800}
.booking-request-header-copy p{margin:0;max-width:720px;color:#64748b;line-height:1.8}
.booking-request-stepper{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px}
.booking-step-chip{display:inline-flex;align-items:center;gap:10px;padding:10px 16px;border-radius:999px;background:#eef4f7;color:#64748b;font-size:14px;font-weight:700}
.booking-step-chip span{width:26px;height:26px;border-radius:999px;background:#d8e8e8;color:#355F72;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:800}
.booking-step-chip.is-active{background:linear-gradient(135deg,#355F72,#539294);color:#fff;box-shadow:0 14px 28px rgba(53,95,114,.18)}
.booking-step-chip.is-active span,.booking-step-chip.is-completed span{background:rgba(255,255,255,.18);color:#fff}
.booking-step-chip.is-completed{background:#dce9e6;color:#355F72}
.booking-request-layout{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(320px,.88fr);gap:30px;align-items:start}
.booking-request-form-wrap{min-width:0}
.booking-request-form{display:block}
.booking-step-panel{display:none;background:#fff;border:1px solid #e2e8f0;border-radius:26px;padding:28px;box-shadow:0 20px 40px rgba(15,23,42,.05)}
.booking-step-panel.is-active{display:block}
.step-panel-head{margin-bottom:22px}
.step-panel-head h2{margin:0 0 8px;color:#0f172a;font-size:28px;font-weight:800}
.step-panel-head p{margin:0;color:#64748b;line-height:1.7}
.form-group{margin-bottom:18px}
.form-row{display:grid;gap:16px}
.form-row-2{grid-template-columns:1fr 1fr}
.field-label{display:block;margin-bottom:8px;font-size:14px;font-weight:700;color:#0f172a}
.input-control,.select-control,.textarea-control{width:100%;border:1px solid #dbe5ec;border-radius:16px;background:#f8fbfc;color:#0f172a;font-size:15px;transition:.2s ease;border-box:box-sizing;padding:0 16px}
.input-control,.select-control{height:54px}
.textarea-control{min-height:120px;padding:14px 16px;resize:vertical}
.input-control:focus,.select-control:focus,.textarea-control:focus{outline:none;border-color:#539294;box-shadow:0 0 0 4px rgba(83,146,148,.12);background:#fff}
.option-cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.option-cards.compact{grid-template-columns:repeat(3,minmax(0,1fr))}
.option-card{display:block;position:relative;border:1px solid #dbe5ec;border-radius:20px;padding:18px;background:#fff;cursor:pointer;transition:.2s ease;min-height:132px}
.option-card:hover{transform:translateY(-1px);box-shadow:0 12px 24px rgba(15,23,42,.06)}
.option-card.is-selected{border-color:#355F72;background:rgba(83,146,148,.08);box-shadow:0 12px 26px rgba(53,95,114,.12)}
.option-card input{position:absolute;opacity:0;pointer-events:none}
.option-card strong{display:block;color:#0f172a;font-size:16px;margin-bottom:8px}
.option-card span{display:block;color:#64748b;font-size:13px;line-height:1.7}
.count-row .form-group{margin-bottom:0}
.count-control{display:grid;grid-template-columns:50px 1fr 50px;gap:10px;align-items:center}
.count-btn{height:54px;border:1px solid #dbe5ec;border-radius:16px;background:#fff;color:#355F72;font-size:24px;font-weight:700;cursor:pointer}
.count-input{text-align:center;padding:0 12px}
.participant-repeater{display:grid;gap:12px}
.participant-item{display:flex;gap:10px;align-items:center}
.participant-item .input-control{flex:1 1 auto}
.participant-order{width:34px;height:34px;border-radius:999px;background:#eef4f7;color:#355F72;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex:0 0 auto}
.review-card{border:1px solid #e2e8f0;border-radius:20px;padding:20px;background:#fff;margin-bottom:16px}
.review-card h3{margin:0 0 12px;font-size:18px;font-weight:800;color:#0f172a}
.review-grid,.price-summary{display:grid;gap:10px}
.review-grid div,.price-summary div{display:flex;justify-content:space-between;gap:18px;align-items:flex-start;color:#334155;font-size:14px;line-height:1.6}
.review-grid div span:first-child,.price-summary div span:first-child{color:#64748b}
.price-summary .grand-total{padding-top:12px;border-top:1px solid #e2e8f0;font-size:16px;font-weight:800;color:#0f172a}
.checkbox-wrap{display:flex;gap:12px;align-items:flex-start;background:#f8fbfc;border:1px solid #e2e8f0;border-radius:18px;padding:16px;margin-top:4px}
.checkbox-wrap input{margin-top:3px}
.checkbox-wrap span{color:#475569;line-height:1.7;font-size:14px}
.step-actions{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-top:24px;flex-wrap:wrap}
.btn-primary,.btn-secondary,.btn-link{display:inline-flex;align-items:center;justify-content:center;min-height:52px;padding:0 22px;border-radius:999px;font-weight:800;font-size:14px;text-decoration:none;cursor:pointer;transition:.2s ease}
.btn-primary{border:0;background:linear-gradient(135deg,#355F72 0%,#539294 52%,#E5A736 100%);color:#fff;box-shadow:0 16px 28px rgba(83,146,148,.24)}
.btn-secondary,.btn-link{border:1px solid #cbd5e1;background:#fff;color:#334155}
.btn-primary:hover,.btn-secondary:hover,.btn-link:hover{transform:translateY(-1px)}
.field-helper{margin:8px 0 0;color:#64748b;font-size:13px;line-height:1.6}
.field-error{font-size:13px;color:#dc2626;margin-top:8px;min-height:18px}
.is-hidden{display:none !important}
.booking-request-summary{min-width:0}
.summary-card{position:sticky;top:96px;background:linear-gradient(180deg,#ffffff 0%,#f8fbfc 100%);border:1px solid #e2e8f0;border-radius:26px;padding:22px;box-shadow:0 20px 38px rgba(15,23,42,.06)}
.summary-thumb{height:190px;border-radius:18px;background-size:cover;background-position:center;margin-bottom:16px}
.summary-badge{display:inline-flex;align-items:center;min-height:30px;padding:0 12px;border-radius:999px;background:#eef5f4;color:#355F72;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px}
.summary-card h3{margin:0 0 8px;font-size:24px;color:#0f172a;line-height:1.2}
.summary-meta{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.summary-meta span{display:inline-flex;align-items:center;min-height:30px;padding:0 12px;border-radius:999px;background:#f1f5f9;color:#475569;font-size:12px;font-weight:700}
.summary-list{display:grid;gap:10px}
.summary-list div{display:flex;justify-content:space-between;gap:14px;font-size:14px;color:#334155;line-height:1.6}
.summary-list div span{color:#64748b}
.summary-total{display:flex;justify-content:space-between;gap:12px;align-items:center;padding-top:16px;margin-top:16px;border-top:1px solid #e2e8f0}
.summary-total span{color:#64748b}
.summary-total strong{font-size:22px;color:#0f172a}
.summary-note{margin-top:16px;padding:14px 16px;border-radius:18px;background:#eef5f4;border:1px solid #d8e8e8;color:#475569;line-height:1.7;font-size:14px}
@media (max-width: 991px){.booking-request-layout{grid-template-columns:1fr}.summary-card{position:static}.option-cards,.option-cards.compact,.form-row-2{grid-template-columns:1fr}.step-actions{flex-direction:column-reverse;align-items:stretch}.btn-primary,.btn-secondary,.btn-link{width:100%}}
</style>

<script>
jQuery(function($){
  const baseAdultPrice = <?php echo (int) $price; ?>;
  const baseChildPrice = <?php echo (int) $child_price; ?>;
  const quota = <?php echo (int) $quota; ?>;
  const minAdult = <?php echo (int) max(1, $min_pax); ?>;
  const state = { currentStep: 1, leadTravelerDirty: false };

  function formatCurrency(amount){ return 'Rp ' + Number(amount || 0).toLocaleString('id-ID'); }
  function escapeHtml(str){ return $('<div>').text(str || '').html(); }
  function titleize(value){
    const map = {
      'open-trip':'Open Trip','private-trip':'Private Trip','family-trip':'Family Trip',
      'meeting-point':'Meeting Point','hotel-pickup':'Hotel Pickup','custom-location':'<?php echo esc_js(contenly_tr('Custom Lokasi', 'Custom Location')); ?>',
      'pagi':'<?php echo esc_js(contenly_tr('Pagi', 'Morning')); ?>','siang':'<?php echo esc_js(contenly_tr('Siang', 'Afternoon')); ?>','fleksibel':'<?php echo esc_js(contenly_tr('Fleksibel', 'Flexible')); ?>',
      'whatsapp':'WhatsApp','phone':'<?php echo esc_js(contenly_tr('Telepon', 'Phone')); ?>','email':'Email'
    };
    return map[value] || value || '—';
  }
  function getValue(name){ return $('[name="'+name+'"]').first().val() || ''; }
  function getInt(name){ return parseInt(getValue(name) || '0', 10) || 0; }
  function clearErrors(){ $('.field-error').text(''); $('.input-control, .select-control, .textarea-control').removeClass('is-invalid'); }
  function setError(name, message){ $('[data-error-for="'+name+'"]').text(message || ''); const $field = $('[name="'+name+'"]'); $field.addClass('is-invalid'); }
  function participantCountNeeded(){ return Math.max((getInt('adult_count') + getInt('child_count')) - 1, 0); }

  function syncOptionCards(){
    $('[data-option-card]').each(function(){
      const $card = $(this);
      const checked = $card.find('input').is(':checked');
      $card.toggleClass('is-selected', checked);
    });
  }

  function syncPickupVisibility(){
    const departureType = getValue('departure_type');
    const show = departureType === 'hotel-pickup' || departureType === 'custom-location';
    $('#pickup-location-group').toggleClass('is-hidden', !show);
  }

  function syncLeadTraveler(){
    const customerName = getValue('customer_name').trim();
    const $leadTraveler = $('#lead_traveler_name');
    if (!state.leadTravelerDirty && customerName) {
      $leadTraveler.val(customerName);
    }
  }

  function syncParticipants(){
    const needed = participantCountNeeded();
    const $wrap = $('#participant-repeater');
    const existing = $wrap.find('.participant-item').length;
    if (existing < needed) {
      for (let i = existing; i < needed; i++) {
        $wrap.append(
          '<div class="participant-item">' +
            '<span class="participant-order">' + (i + 2) + '</span>' +
            '<input class="input-control" type="text" name="participant_names[]" placeholder="<?php echo esc_js(contenly_tr('Nama peserta', 'Traveller name')); ?> ' + (i + 2) + '">' +
          '</div>'
        );
      }
    } else if (existing > needed) {
      $wrap.find('.participant-item').slice(needed).remove();
    }
    if (!needed) {
      $wrap.html('<div style="padding:14px 16px;border:1px dashed #dbe5ec;border-radius:16px;background:#f8fbfc;color:#64748b;font-size:14px;line-height:1.7;"><?php echo esc_js(contenly_tr('Belum perlu tambah peserta lain. Kalau total peserta lebih dari 1, field nama peserta akan muncul otomatis di sini.', 'No additional participant is needed yet. If the total travellers exceed 1, extra traveller fields will appear automatically here.')); ?></div>');
    } else if ($wrap.find('.participant-item').length) {
      $wrap.find('> div:first-child[style]').remove();
    }
  }

  function updateSummary(){
    const adult = Math.max(minAdult, getInt('adult_count'));
    const child = Math.max(0, getInt('child_count'));
    const total = (adult * baseAdultPrice) + (child * baseChildPrice);
    const paxLabel = child > 0 ? adult + ' <?php echo esc_js(contenly_tr('dewasa', 'adults')); ?>, ' + child + ' <?php echo esc_js(contenly_tr('anak', 'children')); ?>' : adult + ' <?php echo esc_js(contenly_tr('dewasa', 'adults')); ?>';
    $('#summary-package').text(titleize(getValue('package_type')));
    $('#summary-date').text(getValue('departure_date') || '<?php echo esc_js(contenly_tr('Belum dipilih', 'Not selected yet')); ?>');
    $('#summary-pax').text(paxLabel);
    $('#summary-departure').text(titleize(getValue('departure_type')));
    $('#summary-total').text(formatCurrency(total));
  }

  function renderReview(){
    const adult = Math.max(minAdult, getInt('adult_count'));
    const child = Math.max(0, getInt('child_count'));
    const total = (adult * baseAdultPrice) + (child * baseChildPrice);
    const participantNames = $('[name="participant_names[]"]').map(function(){ return $(this).val().trim(); }).get().filter(Boolean);

    $('#review-trip').html([
      ['<?php echo esc_js(contenly_tr('Paket', 'Package')); ?>', titleize(getValue('package_type'))],
      ['<?php echo esc_js(contenly_tr('Tanggal', 'Date')); ?>', getValue('departure_date') || '<?php echo esc_js(contenly_tr('Belum dipilih', 'Not selected yet')); ?>'],
      ['<?php echo esc_js(contenly_tr('Dewasa', 'Adults')); ?>', adult],
      ['<?php echo esc_js(contenly_tr('Anak', 'Children')); ?>', child],
      ['<?php echo esc_js(contenly_tr('Keberangkatan', 'Departure')); ?>', titleize(getValue('departure_type'))],
      ['<?php echo esc_js(contenly_tr('Jam preferensi', 'Preferred time')); ?>', titleize(getValue('preferred_time')) || '<?php echo esc_js(contenly_tr('Fleksibel', 'Flexible')); ?>'],
      ['Pickup / meeting', getValue('pickup_location') || '<?php echo esc_js(contenly_tr('Akan mengikuti detail default dari tim', 'Will follow the default details from the team')); ?>'],
      ['<?php echo esc_js(contenly_tr('Catatan tambahan', 'Additional notes')); ?>', getValue('special_request') || '—']
    ].map(item => '<div><span>' + escapeHtml(item[0]) + '</span><strong>' + escapeHtml(String(item[1])) + '</strong></div>').join(''));

    $('#review-customer').html([
      ['<?php echo esc_js(contenly_tr('Nama', 'Name')); ?>', getValue('customer_name')],
      ['Email', getValue('customer_email')],
      ['<?php echo esc_js(contenly_tr('No. HP', 'Phone')); ?>', getValue('customer_phone')],
      ['<?php echo esc_js(contenly_tr('Kota asal', 'Origin city')); ?>', getValue('customer_city') || '—']
    ].map(item => '<div><span>' + escapeHtml(item[0]) + '</span><strong>' + escapeHtml(String(item[1] || '—')) + '</strong></div>').join(''));

    $('#review-travelers').html([
      ['<?php echo esc_js(contenly_tr('Peserta utama', 'Lead traveller')); ?>', getValue('lead_traveler_name')],
      ['<?php echo esc_js(contenly_tr('Peserta lain', 'Other travellers')); ?>', participantNames.length ? participantNames.join(', ') : '—']
    ].map(item => '<div><span>' + escapeHtml(item[0]) + '</span><strong>' + escapeHtml(String(item[1])) + '</strong></div>').join(''));

    $('#review-pricing').html([
      ['<?php echo esc_js(contenly_tr('Dewasa', 'Adults')); ?> (' + adult + ' x ' + formatCurrency(baseAdultPrice) + ')', formatCurrency(adult * baseAdultPrice)],
      ['<?php echo esc_js(contenly_tr('Anak', 'Children')); ?> (' + child + ' x ' + formatCurrency(baseChildPrice) + ')', formatCurrency(child * baseChildPrice)],
      ['<?php echo esc_js(contenly_tr('Biaya tambahan', 'Additional fees')); ?>', formatCurrency(0)]
    ].map(item => '<div><span>' + escapeHtml(item[0]) + '</span><strong>' + escapeHtml(String(item[1])) + '</strong></div>').join('') + '<div class="grand-total"><span><?php echo esc_js(contenly_tr('Estimasi Total', 'Estimated Total')); ?></span><strong>' + formatCurrency(total) + '</strong></div>');
  }

  function validateStep(step){
    clearErrors();
    let valid = true;
    if (step === 1) {
      if (!getValue('package_type')) { setError('package_type', <?php echo wp_json_encode(contenly_tr('Pilih paket tour dulu.', 'Please choose a tour package first.')); ?>); valid = false; }
      if (!getValue('departure_date')) { setError('departure_date', <?php echo wp_json_encode(contenly_tr('Tanggal keberangkatan wajib diisi.', 'Departure date is required.')); ?>); valid = false; }
      if (getInt('adult_count') < 1) { setError('adult_count', <?php echo wp_json_encode(contenly_tr('Minimal 1 peserta dewasa.', 'At least 1 adult traveller is required.')); ?>); valid = false; }
      if (!getValue('departure_type')) { setError('departure_type', <?php echo wp_json_encode(contenly_tr('Pilih titik keberangkatan dulu.', 'Please choose a departure option first.')); ?>); valid = false; }
      const departureType = getValue('departure_type');
      if ((departureType === 'hotel-pickup' || departureType === 'custom-location') && !getValue('pickup_location').trim()) {
        setError('pickup_location', <?php echo wp_json_encode(contenly_tr('Lokasi jemput wajib diisi untuk opsi ini.', 'Pickup location is required for this option.')); ?>); valid = false;
      }
    }
    if (step === 2) {
      if (!getValue('customer_name').trim()) { setError('customer_name', <?php echo wp_json_encode(contenly_tr('Nama lengkap wajib diisi.', 'Full name is required.')); ?>); valid = false; }
      const email = getValue('customer_email').trim();
      if (!email) { setError('customer_email', <?php echo wp_json_encode(contenly_tr('Email wajib diisi.', 'Email is required.')); ?>); valid = false; }
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setError('customer_email', <?php echo wp_json_encode(contenly_tr('Format email belum valid.', 'Please enter a valid email format.')); ?>); valid = false; }
      if (!getValue('customer_phone').trim()) { setError('customer_phone', <?php echo wp_json_encode(contenly_tr('Nomor HP wajib diisi.', 'Phone number is required.')); ?>); valid = false; }
    }
    if (step === 3) {
      if (!getValue('lead_traveler_name').trim()) { setError('lead_traveler_name', <?php echo wp_json_encode(contenly_tr('Nama peserta utama wajib diisi.', 'Lead traveller name is required.')); ?>); valid = false; }
    }
    if (step === 4) {
      if (!$('#terms_agree').is(':checked')) { setError('terms_agree', <?php echo wp_json_encode(contenly_tr('Kamu perlu setuju dulu sebelum submit booking.', 'You need to agree before submitting the booking request.')); ?>); valid = false; }
    }
    if (!valid) {
      const $firstError = $('.field-error').filter(function(){ return $(this).text().trim() !== ''; }).first();
      if ($firstError.length) {
        $('html, body').animate({ scrollTop: $firstError.closest('.booking-step-panel').offset().top - 110 }, 250);
      }
    }
    return valid;
  }

  function setStep(step){
    state.currentStep = step;
    $('.booking-step-panel').removeClass('is-active').hide();
    $('.booking-step-panel[data-step-panel="'+step+'"]').addClass('is-active').show();
    $('.booking-step-chip').removeClass('is-active is-completed').each(function(){
      const chipStep = parseInt($(this).attr('data-step-chip'), 10);
      if (chipStep < step) $(this).addClass('is-completed');
      if (chipStep === step) $(this).addClass('is-active');
    });
    if (step === 4) renderReview();
    $('html, body').animate({ scrollTop: $('.booking-request-stepper').offset().top - 100 }, 200);
  }

  function buildPayload(){
    return {
      package_type: getValue('package_type'),
      departure_date: getValue('departure_date'),
      travel_date: getValue('departure_date'),
      preferred_time: getValue('preferred_time'),
      adult_count: Math.max(minAdult, getInt('adult_count')),
      child_count: Math.max(0, getInt('child_count')),
      pax: Math.max(minAdult, getInt('adult_count')) + Math.max(0, getInt('child_count')),
      departure_type: getValue('departure_type'),
      pickup_location: getValue('pickup_location'),
      special_request: getValue('special_request'),
      name: getValue('customer_name'),
      email: getValue('customer_email'),
      phone: getValue('customer_phone'),
      customer_name: getValue('customer_name'),
      customer_email: getValue('customer_email'),
      customer_phone: getValue('customer_phone'),
      customer_city: getValue('customer_city'),
      lead_traveler_name: getValue('lead_traveler_name'),
      participant_names: $('[name="participant_names[]"]').map(function(){ return $(this).val().trim(); }).get().filter(Boolean),
      medical_notes: getValue('special_request'),
      notes: getValue('special_request')
    };
  }

  $(document).on('change', 'input[type="radio"]', function(){ syncOptionCards(); syncPickupVisibility(); updateSummary(); });
  $(document).on('input', '#lead_traveler_name', function(){
    const customerName = getValue('customer_name').trim();
    const leadTravelerName = getValue('lead_traveler_name').trim();
    state.leadTravelerDirty = leadTravelerName !== '' && leadTravelerName !== customerName;
  });
  $(document).on('input change', '#adult_count, #child_count, #departure_date, #pickup_location, #special_request, #customer_name, #customer_email, #customer_phone, #customer_city, #preferred_time', function(){
    const adult = Math.max(minAdult, Math.min(quota, getInt('adult_count') || minAdult));
    const child = Math.max(0, Math.min(quota, getInt('child_count')));
    $('#adult_count').val(adult);
    $('#child_count').val(child);
    syncLeadTraveler();
    syncParticipants();
    updateSummary();
  });

  $(document).on('click', '.count-btn', function(){
    const target = $(this).data('count-target');
    const action = $(this).data('action');
    const $input = $('#' + target);
    let val = parseInt($input.val() || '0', 10) || 0;
    const min = target === 'adult_count' ? minAdult : 0;
    if (action === 'increase') val += 1;
    if (action === 'decrease') val -= 1;
    val = Math.max(min, Math.min(quota, val));
    $input.val(val).trigger('change');
  });

  $('[data-next-step]').on('click', function(){
    const next = parseInt($(this).attr('data-next-step'), 10);
    if (validateStep(next - 1)) setStep(next);
  });
  $('[data-prev-step]').on('click', function(){ setStep(parseInt($(this).attr('data-prev-step'), 10)); });

  $('#tour-request-form').on('submit', function(e){
    e.preventDefault();
    if (!validateStep(4)) return;

    const payload = buildPayload();
    const fd = new FormData();
    fd.append('action', 'tmpb_create_booking');
    fd.append('nonce', (window.contenlyBooking && contenlyBooking.nonce) ? contenlyBooking.nonce : '');
    fd.append('tour_id', $('input[name="tour_id"]').val());
    fd.append('data', JSON.stringify(payload));

    const $btn = $('#booking-request-submit');
    const oldText = $btn.text();
    $btn.prop('disabled', true).text(<?php echo wp_json_encode(contenly_tr('Mengirim booking...', 'Sending booking request...')); ?>);

    $.ajax({
      url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      success: function(res){
        if (res && res.success) {
          window.location.href = '<?php echo esc_js(add_query_arg('booking', 'requested', contenly_localized_url('/my-travels/'))); ?>';
          return;
        }
        alert((res && res.data && res.data.message) ? res.data.message : <?php echo wp_json_encode(contenly_tr('Booking gagal dikirim.', 'The booking request could not be sent.')); ?>);
        $btn.prop('disabled', false).text(oldText);
      },
      error: function(){
        alert(<?php echo wp_json_encode(contenly_tr('Booking gagal dikirim. Coba lagi ya.', 'The booking request could not be sent. Please try again.')); ?>);
        $btn.prop('disabled', false).text(oldText);
      }
    });
  });

  syncOptionCards();
  syncPickupVisibility();
  syncLeadTraveler();
  syncParticipants();
  updateSummary();
  setStep(1);
});
</script>

<?php get_footer(); ?>