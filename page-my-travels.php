<?php
/**
 * Template Name: My Travels
 * Unified booking management with review system
 */

require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();

// Get all bookings
$all_bookings = get_posts([
    'post_type' => 'tour_booking',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'meta_query' => [
        [
            'key' => '_user_id',
            'value' => $user_id,
            'compare' => '=',
        ],
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);

// Group bookings by status
$upcoming = [];
$completed = [];
$cancelled = [];

foreach ($all_bookings as $booking) {
    $status = get_post_meta($booking->ID, '_booking_status', true);
    
    // Categorize by STATUS, not travel date
    if ($status === 'completed') {
        $completed[] = $booking;
    } elseif ($status === 'cancelled') {
        $cancelled[] = $booking;
    } elseif (in_array($status, ['pending_review', 'confirmed', 'paid', 'pending_payment', 'payment_uploaded'])) {
        $upcoming[] = $booking;
    }
}


// Check if review exists for booking
function has_review($booking_id) {
    $reviews = get_posts([
        'post_type' => 'destination',
        'meta_query' => [
            [
                'key' => '_review_booking_id',
                'value' => $booking_id,
            ],
        ],
        'posts_per_page' => 1,
    ]);
    return !empty($reviews);
}

function get_review($booking_id) {
    $reviews = get_posts([
        'post_type' => 'destination',
        'meta_query' => [
            [
                'key' => '_review_booking_id',
                'value' => $booking_id,
            ],
        ],
        'posts_per_page' => 1,
    ]);
    return !empty($reviews) ? $reviews[0] : null;
}

// Get active tab
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'upcoming';
$edit_review_booking_id = isset($_GET['edit_review_booking']) ? absint($_GET['edit_review_booking']) : 0;
$prefill_review = $edit_review_booking_id ? get_review($edit_review_booking_id) : null;
$payment_notice = isset($_GET['payment']) ? sanitize_text_field($_GET['payment']) : '';
$booking_notice = isset($_GET['booking']) ? sanitize_text_field($_GET['booking']) : '';
?>

<?php if ($booking_notice === 'requested') : ?>
<div style="margin-bottom: 16px; padding: 14px 16px; border-radius: 12px; background: linear-gradient(135deg,#eef5f4,#f8fafc); border:1px solid #D8E8E8; color:#355F72; display:flex; align-items:center; justify-content:space-between; gap:12px;">
    <div style="display:flex; align-items:center; gap:10px; font-weight:600;">
        <span style="font-size:18px;">🧾</span>
        <span><?php echo esc_html(contenly_tr('Permintaan booking berhasil dikirim. Tim admin akan review detail trip kamu dulu sebelum konfirmasi tahap selanjutnya.', 'Your booking request has been sent successfully. Our admin team will review your trip details before confirming the next step.')); ?></span>
    </div>
    <a href="<?php echo esc_url(add_query_arg('tab', $active_tab, contenly_localized_url('/my-travels/'))); ?>" style="text-decoration:none; color:#355F72; font-weight:700;"><?php echo esc_html(contenly_tr('Tutup', 'Close')); ?></a>
</div>
<?php endif; ?>

<?php if ($payment_notice === 'uploaded') : ?>
<div style="margin-bottom: 16px; padding: 14px 16px; border-radius: 12px; background: linear-gradient(135deg,#ecfeff,#f0fdfa); border:1px solid #99f6e4; color:#115e59; display:flex; align-items:center; justify-content:space-between; gap:12px;">
    <div style="display:flex; align-items:center; gap:10px; font-weight:600;">
        <span style="font-size:18px;">✅</span>
        <span><?php echo esc_html(contenly_tr('Bukti pembayaran berhasil diupload. Tim admin akan memverifikasi pembayaran kamu.', 'Your payment proof has been uploaded successfully. Our admin team will verify your payment.')); ?></span>
    </div>
    <a href="<?php echo esc_url(add_query_arg('tab', $active_tab, contenly_localized_url('/my-travels/'))); ?>" style="text-decoration:none; color:#0f766e; font-weight:700;"><?php echo esc_html(contenly_tr('Tutup', 'Close')); ?></a>
</div>
<?php endif; ?>

<!-- Page Header -->
<div style="margin-bottom: 32px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">📍 <?php echo esc_html(contenly_tr('Perjalanan Saya', 'My Travels')); ?></h1>
    <p style="font-size: 15px; color: #64748b;"><?php echo esc_html(contenly_tr('Kelola perjalananmu, status booking, dan review', 'Manage your trips, booking statuses, and reviews.')); ?></p>
</div>

<!-- Tabs -->
<div class="mytravels-tabs" style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 0;">
    <a class="mytravels-tab" href="<?php echo esc_url(add_query_arg('tab', 'upcoming', contenly_localized_url('/my-travels/'))); ?>" 
       style="padding: 12px 24px; text-decoration: none; border-radius: 8px 8px 0 0; font-weight: 600; transition: all 0.3s; <?php echo $active_tab === 'upcoming' ? 'background: #539294; color: white;' : 'background: #f1f5f9; color: #64748b;'; ?>">
        📅 <?php echo esc_html(sprintf(contenly_tr('Upcoming (%d)', 'Upcoming (%d)'), count($upcoming))); ?>
    </a>
    <a class="mytravels-tab" href="<?php echo esc_url(add_query_arg('tab', 'completed', contenly_localized_url('/my-travels/'))); ?>" 
       style="padding: 12px 24px; text-decoration: none; border-radius: 8px 8px 0 0; font-weight: 600; transition: all 0.3s; <?php echo $active_tab === 'completed' ? 'background: #10b981; color: white;' : 'background: #f1f5f9; color: #64748b;'; ?>">
        ✔ <?php echo esc_html(sprintf(contenly_tr('Selesai (%d)', 'Completed (%d)'), count($completed))); ?>
    </a>
    <a class="mytravels-tab" href="<?php echo esc_url(add_query_arg('tab', 'cancelled', contenly_localized_url('/my-travels/'))); ?>" 
       style="padding: 12px 24px; text-decoration: none; border-radius: 8px 8px 0 0; font-weight: 600; transition: all 0.3s; <?php echo $active_tab === 'cancelled' ? 'background: #ef4444; color: white;' : 'background: #f1f5f9; color: #64748b;'; ?>">
        ❌ <?php echo esc_html(sprintf(contenly_tr('Dibatalkan (%d)', 'Cancelled (%d)'), count($cancelled))); ?>
    </a>
</div>

<!-- Tab Content -->
<?php
$current_bookings = ${$active_tab};

if (empty($current_bookings)) :
    $empty_messages = [
        'upcoming' => [
            'icon' => '📅',
            'title' => 'No upcoming bookings',
            'text' => 'Start exploring our amazing tours!',
            'button' => contenly_tr('Jelajahi Tour', 'Browse Tours'),
            'link' => contenly_localized_url('/tour-packages'),
        ],
        'completed' => [
            'icon' => '✔',
            'title' => contenly_tr('Belum ada booking selesai', 'No completed bookings yet'),
            'text' => contenly_tr('Selesaikan trip pertamamu lalu bagikan pengalamanmu!', 'Complete your first tour and share your experience!'),
            'button' => contenly_tr('Jelajahi Tour', 'Browse Tours'),
            'link' => contenly_localized_url('/tour-packages'),
        ],
        'cancelled' => [
            'icon' => '❌',
            'title' => contenly_tr('Belum ada booking dibatalkan', 'No cancelled bookings'),
            'text' => contenly_tr('Mantap! Semua booking kamu masih aman di jalur.', 'Great! All your bookings are on track.'),
            'button' => contenly_tr('Jelajahi Tour', 'Browse Tours'),
            'link' => contenly_localized_url('/tour-packages'),
        ],
    ];
    $msg = $empty_messages[$active_tab];
?>
<div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 16px;">
    <div style="font-size: 64px; margin-bottom: 16px;"><?php echo $msg['icon']; ?></div>
    <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo $msg['title']; ?></h3>
    <p style="color: #64748b; margin-bottom: 24px;"><?php echo $msg['text']; ?></p>
    <a href="<?php echo $msg['link']; ?>" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #539294, #539294); color: white; text-decoration: none; border-radius: 12px; font-weight: 600;">
        <?php echo $msg['button']; ?>
    </a>
</div>
<?php else : ?>
<div style="display: grid; gap: 16px;">
    <?php foreach ($current_bookings as $booking) :
        $tour_id = get_post_meta($booking->ID, '_tour_id', true);
        $tour = $tour_id ? get_post($tour_id) : null;
        $status = get_post_meta($booking->ID, '_booking_status', true);
        $total = get_post_meta($booking->ID, '_total_amount', true);
        $pax = get_post_meta($booking->ID, '_pax', true);
        $adult_count = get_post_meta($booking->ID, '_adult_count', true);
        $child_count = get_post_meta($booking->ID, '_child_count', true);
        $package_type = get_post_meta($booking->ID, '_package_type', true);
        $travel_date = get_post_meta($booking->ID, '_travel_date', true);
        
        $review = get_review($booking->ID);
        $has_reviewed = $review !== null;
        $review_rating = $has_reviewed ? get_post_meta($review->ID, '_rating', true) : 0;
        
        $status_labels = [
            'pending_review' => ['label' => '🧾 ' . contenly_tr('Menunggu Review', 'Pending Review'), 'color' => '#355F72', 'bg' => '#DCE9E6'],
            'pending_payment' => ['label' => '⏳ ' . contenly_tr('Menunggu Pembayaran', 'Pending Payment'), 'color' => '#fbbf24', 'bg' => '#fef3c7'],
            'payment_uploaded' => ['label' => '📤 ' . contenly_tr('Bukti Pembayaran Diupload', 'Payment Uploaded'), 'color' => '#539294', 'bg' => '#DCE9E6'],
            'paid' => ['label' => '✅ ' . contenly_tr('Lunas', 'Paid'), 'color' => '#10b981', 'bg' => '#d1fae5'],
            'confirmed' => ['label' => '✓ ' . contenly_tr('Terkonfirmasi', 'Confirmed'), 'color' => '#10b981', 'bg' => '#d1fae5'],
            'cancelled' => ['label' => '❌ ' . contenly_tr('Dibatalkan', 'Cancelled'), 'color' => '#dc2626', 'bg' => '#fee2e2'],
            'completed' => ['label' => '✔ ' . contenly_tr('Selesai', 'Completed'), 'color' => '#10b981', 'bg' => '#d1fae5'],
        ];
        $status_info = isset($status_labels[$status]) ? $status_labels[$status] : array('label' => $status, 'color' => '#64748b', 'bg' => '#f1f5f9');
    ?>
    <div class="booking-card" style="border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; background: white; transition: all 0.3s;" 
         onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'; this.style.transform='translateY(-4px)'" 
         onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
        
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div style="flex: 1;">
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                    <?php echo esc_html($tour ? $tour->post_title : contenly_tr('Booking Tour', 'Tour Booking')); ?>
                </h3>
                <div style="font-size: 14px; color: #94a3b8;"><?php echo esc_html(contenly_tr('Booking', 'Booking')); ?> #<?php echo esc_html($booking->ID); ?></div>
            </div>
            <span style="padding: 6px 16px; background: <?php echo $status_info['bg']; ?>; color: <?php echo $status_info['color']; ?>; border-radius: 9999px; font-size: 13px; font-weight: 600;">
                <?php echo esc_html($status_info['label']); ?>
            </span>
        </div>
        
        <div class="booking-meta-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; padding: 16px; background: #f8fafc; border-radius: 12px; margin-bottom: 16px;">
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;"><?php echo esc_html(contenly_tr('Tanggal Perjalanan', 'Travel Date')); ?></div>
                <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html($travel_date ? date_i18n(get_option('date_format'), strtotime($travel_date)) : contenly_tr('Belum diatur', 'Not set')); ?></div>
            </div>
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Pax</div>
                <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html(($adult_count ? (int) $adult_count : ($pax ?: 1)) . ' ' . contenly_tr('dewasa', 'adults') . ($child_count ? ', ' . (int) $child_count . ' ' . contenly_tr('anak', 'children') : '')); ?></div>
            </div>
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;"><?php echo esc_html(contenly_tr('Paket', 'Package')); ?></div>
                <div style="font-weight: 600; color: #0f172a;"><?php echo esc_html($package_type ? ucwords(str_replace('-', ' ', $package_type)) : contenly_tr('Standar', 'Standard')); ?></div>
            </div>
            <div>
                <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;"><?php echo esc_html(contenly_tr('Total', 'Total')); ?></div>
                <div style="font-weight: 600; color: #0f172a;">Rp <?php echo esc_html(number_format($total ?: 0, 0, ',', '.')); ?></div>
            </div>
        </div>
        
        <!-- Review Section (Only for Completed) -->
        <?php if ($active_tab === 'completed') : ?>
        <div style="padding: 16px; background: <?php echo $has_reviewed ? '#f0fdf4' : '#fffbeb'; ?>; border: 2px solid <?php echo $has_reviewed ? '#86efac' : '#fcd34d'; ?>; border-radius: 12px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <?php if ($has_reviewed) : ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 20px;"><?php echo str_repeat('⭐', $review_rating); ?></span>
                        <span style="color: #166534; font-weight: 600;"><?php echo esc_html(contenly_tr('Review berhasil dikirim!', 'Review submitted!')); ?></span>
                    </div>
                    <div style="font-size: 13px; color: #166534; margin-top: 4px;">
                        "<?php echo esc_html($review->post_title); ?>"
                    </div>
                    <?php else : ?>
                    <div style="color: #92400e; font-weight: 600;">
                        📝 <?php echo esc_html(contenly_tr('Bagikan pengalamanmu!', 'Share your experience!')); ?>
                    </div>
                    <div style="font-size: 13px; color: #92400e; margin-top: 4px;">
                        <?php echo esc_html(contenly_tr('Bantu traveler lain dengan menulis review untuk tour ini', 'Help other travellers by writing a review for this tour.')); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <button onclick='openReviewModal(<?php echo (int) $booking->ID; ?>, <?php echo (int) $tour_id; ?>, <?php echo $has_reviewed ? (int) $review->ID : 0; ?>, <?php echo wp_json_encode($has_reviewed ? $review->post_title : ''); ?>, <?php echo wp_json_encode($has_reviewed ? $review->post_content : ''); ?>, <?php echo (int) ($has_reviewed ? $review_rating : 0); ?>)' 
                        style="padding: 10px 20px; background: <?php echo $has_reviewed ? '#10b981' : '#f59e0b'; ?>; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    <?php echo esc_html($has_reviewed ? contenly_tr('✏️ Edit Review', '✏️ Edit Review') : contenly_tr('⭐ Tulis Review', '⭐ Write Review')); ?>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="booking-actions" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" style="padding: 10px 20px; background: #f0f9ff; color: #539294; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;"><?php echo esc_html(contenly_tr('Lihat Tour', 'View Tour')); ?></a>
            <?php if ($status === 'payment_uploaded') : ?>
            <span style="padding: 10px 20px; background: #DCE9E6; color: #355F72; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">📎 <?php echo esc_html(contenly_tr('Bukti Pembayaran Diupload', 'Payment Proof Uploaded')); ?></span>
            <?php endif; ?>
            <?php if ($status === 'pending_payment') : ?>
            <a href="<?php echo esc_url(add_query_arg('booking_id', $booking->ID, contenly_localized_url('/checkout/'))); ?>" style="padding: 10px 20px; background: linear-gradient(135deg, #10b981, #059669); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">💳 <?php echo esc_html(contenly_tr('Bayar Sekarang', 'Pay Now')); ?></a>
            <button onclick="cancelBooking(<?php echo (int) $booking->ID; ?>)" style="padding: 10px 20px; background: #fee2e2; color: #dc2626; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer;"><?php echo esc_html(contenly_tr('Batalkan Booking', 'Cancel Booking')); ?></button>
            <?php endif; ?>
            <?php if ($status === 'payment_uploaded') : ?>
            <span style="padding:10px 14px;background:#EEF5F4;color:#355F72;border:1px solid #D8E8E8;border-radius:8px;font-size:13px;"><?php echo esc_html(contenly_tr('⌛ Menunggu verifikasi admin. Tombol pembayaran dinonaktifkan.', '⌛ Waiting for admin verification. The payment button is disabled.')); ?></span>
            <?php endif; ?>
            <?php if (!in_array($status, ['pending_payment','payment_uploaded']) && $active_tab === 'upcoming') : ?>
            <span style="padding:10px 14px;background:#fff7ed;color:#9a3412;border:1px solid #fdba74;border-radius:8px;font-size:13px;"><?php echo esc_html(contenly_tr('ℹ️ Perubahan booking tidak tersedia setelah pembayaran diproses.', 'ℹ️ Booking changes are no longer available after payment is processed.')); ?></span>
            <?php endif; ?>

        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Review Modal -->
<div id="review-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; padding: 32px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 24px; font-weight: 700; color: #0f172a;">⭐ Write a Review</h2>
            <button onclick="closeReviewModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        
        <form id="review-form" style="display: grid; gap: 20px;">
            <input type="hidden" id="review-booking-id" name="booking_id">
            <input type="hidden" id="review-tour-id" name="tour_id">
            <input type="hidden" id="review-existing-id" name="existing_id">
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Your Rating *</label>
                <div id="star-rating" style="display: flex; gap: 8px; font-size: 32px; cursor: pointer;">
                    <span data-rating="1" onclick="setRating(1)">⭐</span>
                    <span data-rating="2" onclick="setRating(2)">⭐</span>
                    <span data-rating="3" onclick="setRating(3)">⭐</span>
                    <span data-rating="4" onclick="setRating(4)">⭐</span>
                    <span data-rating="5" onclick="setRating(5)">⭐</span>
                </div>
                <input type="hidden" id="review-rating" name="rating" required>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Review Title *</label>
                <input type="text" id="review-title" name="title" required placeholder="e.g., Amazing experience!" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Your Review *</label>
                <textarea id="review-content" name="content" required rows="5" placeholder="Share your experience with this tour..." 
                          style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px; resize: vertical;"></textarea>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" id="submit-review-btn" 
                        style="flex: 1; padding: 14px; background: linear-gradient(135deg, #539294, #539294); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer;">
                    📝 Submit Review
                </button>
                <button type="button" onclick="closeReviewModal()" 
                        style="padding: 14px 32px; background: #f1f5f9; color: #64748b; border: none; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px){
  .mytravels-tabs{overflow-x:auto; padding-bottom:8px; border-bottom:none !important;}
  .mytravels-tab{white-space:nowrap; padding:10px 14px !important; font-size:13px !important; border-radius:10px !important;}
  .booking-card{padding:16px !important;}
  .booking-meta-grid{grid-template-columns:1fr 1fr !important; gap:10px !important;}
  .booking-actions{display:grid !important; grid-template-columns:1fr !important;}
  .booking-actions a,.booking-actions button,.booking-actions span{width:100%; justify-content:center; text-align:center;}
}
</style>

<script>
let currentRating = 0;

function openReviewModal(bookingId, tourId, existingId = 0, existingTitle = '', existingContent = '', existingRating = 0) {
    document.getElementById('review-booking-id').value = bookingId;
    document.getElementById('review-tour-id').value = tourId;
    document.getElementById('review-existing-id').value = existingId || '';
    document.getElementById('review-modal').style.display = 'flex';

    const submitBtn = document.getElementById('submit-review-btn');
    const isEdit = existingId > 0;

    document.getElementById('review-title').value = existingTitle || '';
    document.getElementById('review-content').value = existingContent || '';

    if (existingRating > 0) {
        setRating(existingRating);
    } else {
        currentRating = 0;
        document.getElementById('review-rating').value = '';
        updateStars();
    }

    submitBtn.innerHTML = isEdit ? '💾 Update Review' : '📝 Submit Review';
}

function closeReviewModal() {
    document.getElementById('review-modal').style.display = 'none';
    document.getElementById('review-form').reset();
    document.getElementById('review-existing-id').value = '';
    document.getElementById('submit-review-btn').innerHTML = '📝 Submit Review';
    currentRating = 0;
    updateStars();
}

function setRating(rating) {
    currentRating = rating;
    document.getElementById('review-rating').value = rating;
    updateStars();
}

function updateStars() {
    const stars = document.querySelectorAll('#star-rating span');
    stars.forEach((star, index) => {
        if (index < currentRating) {
            star.style.opacity = '1';
        } else {
            star.style.opacity = '0.3';
        }
    });
}

// Initialize stars
updateStars();

// Form submission
document.getElementById('review-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (currentRating === 0) {
        showToast('❌ Please select a rating', 'error');
        return;
    }
    
    const formData = new FormData(this);
    formData.append('action', 'contenly_submit_review');
    formData.append('nonce', '<?php echo wp_create_nonce("tmpb_booking_nonce"); ?>');
    
    const submitBtn = document.getElementById('submit-review-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Submitting...';
    
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('✅ Review submitted successfully! Thank you for sharing your experience.', 'success');
            closeReviewModal();
            location.reload();
        } else {
            showToast('❌ ' + (data.data.message || 'Failed to submit review'), 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(err => {
        showToast('❌ Failed to submit review. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Cancel booking
function cancelBooking(bookingId) {
    if (!confirm('Are you sure you want to cancel this booking?')) return;
    
    const formData = new FormData();
    formData.append('action', 'tmpb_cancel_booking');
    formData.append('booking_id', bookingId);
    formData.append('nonce', '<?php echo wp_create_nonce("tmpb_booking_nonce"); ?>');
    
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('✅ Booking cancelled successfully', 'success');
            location.reload();
        } else {
            const msg = (data.data && data.data.message) ? data.data.message : 'Failed to cancel booking';
            if (msg.toLowerCase().includes('already processed')) {
                showToast('ℹ️ Booking sudah diproses dan tidak bisa dibatalkan dari member area.', 'warn');
            } else {
                showToast('❌ ' + msg, 'error');
            }
        }
    });
}

// Deep link edit from /reviews page.
<?php if ($prefill_review) :
    $prefill_booking_id = (int) get_post_meta($prefill_review->ID, '_review_booking_id', true);
    $prefill_tour_id = (int) get_post_meta($prefill_review->ID, '_review_tour_id', true);
    $prefill_rating = (int) get_post_meta($prefill_review->ID, '_rating', true);
?>
document.addEventListener('DOMContentLoaded', function () {
    openReviewModal(
        <?php echo $prefill_booking_id; ?>,
        <?php echo $prefill_tour_id; ?>,
        <?php echo (int) $prefill_review->ID; ?>,
        <?php echo wp_json_encode($prefill_review->post_title); ?>,
        <?php echo wp_json_encode($prefill_review->post_content); ?>,
        <?php echo $prefill_rating; ?>
    );
});
<?php endif; ?>

// Close modal on outside click
document.getElementById('review-modal').addEventListener('click', function(e) {
    if (e.target === this) closeReviewModal();
});
</script>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
