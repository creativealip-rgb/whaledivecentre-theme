<?php
/**
 * Booking Form Component
 * Usage: include get_template_directory() . '/booking-form.php';
 */

if (!defined('ABSPATH')) {
    exit;
}

$tour_id = $tour_id ?? get_the_ID();
$price = absint(get_post_meta($tour_id, '_tour_price', true) ?: get_post_meta($tour_id, 'price', true));
$duration = get_post_meta($tour_id, '_tour_duration_days', true) ?: get_post_meta($tour_id, 'duration', true);
$quota = get_post_meta($tour_id, '_tour_quota', true) ?: 20;
$min_pax = get_post_meta($tour_id, '_tour_min_pax', true) ?: 1;

$user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
?>

<div class="booking-card" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
    <?php if (!$is_logged_in) : ?>
        <!-- Not Logged In -->
        <div class="guest-auth-wrap" style="text-align:center; padding:0; width:100%;">
            <div class="guest-auth-card" style="background:linear-gradient(180deg,#f8fbff 0%,#f1f5f9 100%); border:1px solid #DCE9E6; border-radius:14px; padding:20px; margin-bottom:14px; width:100%; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <div style="font-size:42px; margin-bottom:10px; line-height:1;">🔐</div>
                <h3 style="font-size:19px; font-weight:800; color:#0f172a; margin:0 0 8px;">Login untuk Booking Tour</h3>
                <p style="color:#64748b; margin:0; font-size:14px; line-height:1.6; max-width: 320px;">Silakan login dulu untuk mengisi data traveler, pilih tanggal, dan lanjut pembayaran.</p>
            </div>

            <div class="guest-auth-actions" style="display:grid; gap:10px; width:100%;">
                <a class="guest-auth-btn guest-auth-btn-primary" href="/login?redirect_to=<?php echo urlencode(get_permalink($tour_id) . '?book=1'); ?>"
                   style="display:flex; align-items:center; justify-content:center; padding:12px 16px; background:linear-gradient(135deg,#539294,#539294); color:#ffffff !important; text-decoration:none; border-radius:10px; font-weight:700; width:100%;">
                    Login Sekarang
                </a>
                <a class="guest-auth-btn guest-auth-btn-secondary" href="/register?redirect_to=<?php echo urlencode(get_permalink($tour_id) . '?book=1'); ?>"
                   style="display:flex; align-items:center; justify-content:center; padding:12px 16px; background:#fff; color:#539294 !important; text-decoration:none; border-radius:10px; font-weight:700; border:1px solid #D8E8E8; width:100%;">
                    Buat Akun
                </a>
            </div>
        </div>
    <?php else : ?>
        <!-- Booking Form -->
        <div style="margin-bottom: 20px;">
            <div style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                Rp <?php echo number_format($price, 0, ',', '.'); ?>
            </div>
            <div style="color: #64748b; font-size: 14px;">per person</div>
        </div>
        
        <div style="display: grid; gap: 12px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 14px;">
                <span>⏱️</span>
                <span><?php echo esc_html($duration); ?> days</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 14px;">
                <span>👥</span>
                <span>Min: <?php echo esc_html($min_pax); ?> pax</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 14px;">
                <span>🎫</span>
                <span>Quota: <?php echo esc_html($quota); ?> persons</span>
            </div>
        </div>
        
        <form id="contenly-booking-form" style="display: grid; gap: 16px;">
            <input type="hidden" name="tour_id" value="<?php echo esc_attr($tour_id); ?>">
            
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    Number of Pax *
                </label>
                <input type="number" name="pax" min="<?php echo esc_attr($min_pax); ?>" max="<?php echo esc_attr($quota); ?>" 
                       value="<?php echo esc_attr($min_pax); ?>" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    Travel Date *
                </label>
                <input type="date" name="travel_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>
            
            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        Full Name *
                    </label>
                    <input type="text" name="name" value="<?php echo esc_attr($user->display_name); ?>" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                        Email *
                    </label>
                    <input type="email" name="email" value="<?php echo esc_attr($user->user_email); ?>" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    Phone Number *
                </label>
                <input type="tel" name="phone" value="<?php echo esc_attr(get_user_meta($user->ID, 'phone_number', true)); ?>" required
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Nationality *</label>
                    <input type="text" name="nationality" placeholder="Contoh: Indonesia" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Kota Keberangkatan *</label>
                    <input type="text" name="departure_city" placeholder="Contoh: Jakarta" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Jenis Identitas *</label>
                    <select name="id_type" required style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; background:#fff;">
                        <option value="">Pilih</option>
                        <option value="KTP">KTP</option>
                        <option value="Passport">Passport</option>
                        <option value="SIM">SIM</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Nomor Identitas *</label>
                    <input type="text" name="id_number" required placeholder="Nomor KTP/Paspor"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Nomor Passport</label>
                    <input type="text" name="passport_number" placeholder="Isi jika perjalanan internasional"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Passport Expiry Date</label>
                    <input type="date" name="passport_expiry"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Perlu Visa? *</label>
                    <select name="visa_required" required style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; background:#fff;">
                        <option value="">Pilih</option>
                        <option value="yes">Ya</option>
                        <option value="no">Tidak</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Status Visa</label>
                    <select name="visa_status" style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; background:#fff;">
                        <option value="">Pilih</option>
                        <option value="already_have">Sudah Punya</option>
                        <option value="in_process">Sedang Proses</option>
                        <option value="not_yet">Belum Ada</option>
                    </select>
                </div>
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Visa Number</label>
                    <input type="text" name="visa_number" placeholder="Opsional"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Visa Expiry Date</label>
                    <input type="date" name="visa_expiry"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>

            <div>
                <label style="display:flex; align-items:center; gap:8px; font-weight:600; color:#0f172a; font-size:14px;">
                    <input type="checkbox" name="visa_assistance" value="1" style="width:auto !important;">
                    Butuh bantuan pengurusan visa
                </label>
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Kontak Darurat (Nama) *</label>
                    <input type="text" name="emergency_contact_name" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Kontak Darurat (HP) *</label>
                    <input type="tel" name="emergency_contact_phone" required
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>

            <div class="contenly-booking-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Preferensi Makanan</label>
                    <input type="text" name="dietary_requirements" placeholder="Halal, vegetarian, alergi seafood, dll"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">Catatan Kesehatan</label>
                    <input type="text" name="medical_notes" placeholder="Contoh: mabuk perjalanan, alergi obat"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    Notes / Special Requests
                </label>
                <textarea name="notes" rows="3" placeholder="Catatan tambahan: room preference, seat preference, dll"
                          style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; resize: vertical;"></textarea>
            </div>
            
            <div style="background: #f8fafc; padding: 16px; border-radius: 12px; margin-top: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: #475569;">Total Amount:</span>
                    <span id="booking-total" style="font-size: 20px; font-weight: 800; color: #539294;">
                        Rp <?php echo number_format($price, 0, ',', '.'); ?>
                    </span>
                </div>
            </div>
            
            <button type="submit" id="booking-submit-btn" 
                    style="width: 100%; padding: 16px; background: linear-gradient(135deg, #539294, #539294); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);">
                🎫 Book Now
            </button>
        </form>
        
        <!-- Success Message -->
        <div id="booking-success" style="display: none; text-align: center; padding: 30px;">
            <div style="font-size: 56px; margin-bottom: 16px;">✅</div>
            <h3 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Booking Successful!</h3>
            <p style="color: #64748b; margin-bottom: 16px;">Your booking code:</p>
            <div id="booking-code-display" style="font-size: 24px; font-weight: 800; color: #539294; background: #EEF5F4; padding: 16px; border-radius: 12px; margin-bottom: 20px;"></div>
            <p style="color: #64748b; margin-bottom: 24px;">We'll contact you soon for payment confirmation.</p>
            <a href="/my-bookings" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #10b981, #059669); color: white; text-decoration: none; border-radius: 10px; font-weight: 600;">
                View My Bookings →
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.booking-card,
.booking-card * {
    box-sizing: border-box;
}

.booking-card {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    overflow-x: hidden;
}

.booking-card input,
.booking-card select,
.booking-card textarea,
.booking-card button {
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

.guest-auth-wrap,
.guest-auth-card,
.guest-auth-actions,
.guest-auth-btn {
    width: 100% !important;
    max-width: 100% !important;
}

.guest-auth-card p {
    text-align: center !important;
    white-space: normal !important;
    word-break: normal !important;
}

.guest-auth-btn {
    min-height: 46px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 15px !important;
    line-height: 1.2 !important;
}

@media (min-width: 769px) {
    .guest-auth-card {
        padding: 24px !important;
        min-height: 210px;
    }

    .guest-auth-card p {
        max-width: none !important;
        font-size: 14px !important;
    }

    .guest-auth-actions {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .booking-card {
        padding: 16px !important;
    }

    .contenly-booking-row {
        grid-template-columns: 1fr !important;
    }

    .guest-auth-card {
        padding: 14px !important;
    }

    .guest-auth-card h3 {
        font-size: 17px !important;
    }

    .guest-auth-card p {
        font-size: 13px !important;
    }

    .guest-auth-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Normalize forced inline styles from external scripts (desktop/mobile safety)
    function normalizeBookingCard() {
        var card = document.querySelector('.booking-card');
        if (!card) return;
        card.style.setProperty('width', '100%', 'important');
        card.style.setProperty('max-width', '100%', 'important');
        card.style.setProperty('min-width', '0', 'important');
        card.style.setProperty('margin-left', '0', 'important');
        card.style.setProperty('margin-right', '0', 'important');
        card.style.setProperty('box-sizing', 'border-box', 'important');
        if (window.innerWidth >= 769) {
            card.style.setProperty('padding-left', '24px', 'important');
            card.style.setProperty('padding-right', '24px', 'important');
        }
    }

    normalizeBookingCard();
    setTimeout(normalizeBookingCard, 150);
    setTimeout(normalizeBookingCard, 600);
    window.addEventListener('resize', normalizeBookingCard);

    var $form = $('#contenly-booking-form');
    var $paxInput = $form.find('input[name="pax"]');
    var $totalDisplay = $('#booking-total');
    var basePrice = <?php echo $price; ?>;
    
    // Calculate total
    $paxInput.on('change input', function() {
        var pax = parseInt($(this).val()) || <?php echo $min_pax; ?>;
        var total = basePrice * pax;
        $totalDisplay.text('Rp ' + total.toLocaleString('id-ID'));
    });
    
    // Submit form
    $form.on('submit', function(e) {
        e.preventDefault();
        
        var $submitBtn = $('#booking-submit-btn');
        var originalText = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('⏳ Processing...');
        
        var formData = new FormData(this);
        formData.append('action', 'tmpb_create_booking');
        formData.append('nonce', contenlyBooking.nonce);
        
        // Format data for plugin
        var data = {
            pax: $form.find('input[name="pax"]').val(),
            travel_date: $form.find('input[name="travel_date"]').val(),
            name: $form.find('input[name="name"]').val(),
            email: $form.find('input[name="email"]').val(),
            phone: $form.find('input[name="phone"]').val(),
            nationality: $form.find('input[name="nationality"]').val(),
            departure_city: $form.find('input[name="departure_city"]').val(),
            id_type: $form.find('select[name="id_type"]').val(),
            id_number: $form.find('input[name="id_number"]').val(),
            emergency_contact_name: $form.find('input[name="emergency_contact_name"]').val(),
            emergency_contact_phone: $form.find('input[name="emergency_contact_phone"]').val(),
            dietary_requirements: $form.find('input[name="dietary_requirements"]').val(),
            medical_notes: $form.find('input[name="medical_notes"]').val(),
            passport_number: $form.find('input[name="passport_number"]').val(),
            passport_expiry: $form.find('input[name="passport_expiry"]').val(),
            visa_required: $form.find('select[name="visa_required"]').val(),
            visa_status: $form.find('select[name="visa_status"]').val(),
            visa_number: $form.find('input[name="visa_number"]').val(),
            visa_expiry: $form.find('input[name="visa_expiry"]').val(),
            visa_assistance: $form.find('input[name="visa_assistance"]').is(':checked') ? '1' : '0',
            notes: $form.find('textarea[name="notes"]').val(),
        };
        formData.append('data', JSON.stringify(data));
        
        $.ajax({
            url: contenlyBooking.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $form.hide();
                    $('#booking-code-display').text(response.data.booking_code || 'TMPB-' + Math.random().toString(36).substr(2,6).toUpperCase());
                    $('#booking-success').fadeIn();
                    
                    // Redirect after 2 seconds
                    setTimeout(function() {
                        window.location.href = '/my-bookings?success=1';
                    }, 2000);
                } else {
                    alert('Error: ' + (response.data.message || 'Booking failed'));
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Booking error:', status, error);
                alert('Booking gagal. Coba lagi beberapa saat. Kalau masih error, isi form kontak agar admin bisa cek.');
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
