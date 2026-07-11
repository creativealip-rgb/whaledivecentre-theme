<?php
/**
 * Single Tour Template
 */

get_header();

while (have_posts()) : the_post();
    $tour_id = get_the_ID();
    $price = absint(get_post_meta($tour_id, '_tour_price', true) ?: get_post_meta($tour_id, 'price', true));
    $duration = get_post_meta($tour_id, '_tour_duration_days', true) ?: get_post_meta($tour_id, 'duration', true);
    $quota = get_post_meta($tour_id, '_tour_quota', true) ?: 20;
    $min_pax = get_post_meta($tour_id, '_tour_min_pax', true) ?: 1;
    $location = get_post_meta($tour_id, '_tour_location', true) ?: get_post_meta($tour_id, 'location', true);
    $includes = get_post_meta($tour_id, '_tour_includes', true);
    $excludes = get_post_meta($tour_id, '_tour_excludes', true);
    $itinerary = get_post_meta($tour_id, '_tour_itinerary', true);
    $is_booking_detail = isset($_GET['book']) && $_GET['book'] === '1';
    $booking_detail_url = add_query_arg('book', '1', get_permalink($tour_id)) . '#booking-form-section';
    
    // Get gallery images
    $gallery = get_post_meta($tour_id, '_tour_gallery', true);
    if (is_array($gallery)) {
        $gallery_ids = array_map('absint', $gallery);
    } elseif (!empty($gallery)) {
        $gallery_ids = array_map('absint', array_filter(explode(',', (string) $gallery)));
    } else {
        $gallery_ids = [];
    }
    if (empty($gallery_ids) && has_post_thumbnail()) {
        $gallery_ids = [get_post_thumbnail_id()];
    }

    $wishlist_ids = is_user_logged_in() ? get_user_meta(get_current_user_id(), '_member_wishlist', true) : [];
    if (!is_array($wishlist_ids)) $wishlist_ids = [];
    $in_wishlist = in_array($tour_id, array_map('absint', $wishlist_ids), true);

    $hero_image_url = !empty($gallery_ids)
        ? wp_get_attachment_image_url($gallery_ids[0], 'full')
        : '';

    $includes_items = array_values(array_filter(array_map('trim', explode("\n", (string) $includes))));
    $excludes_items = array_values(array_filter(array_map('trim', explode("\n", (string) $excludes))));
    $is_international = preg_match('/japan|korea|singapore|thailand|malaysia|hong kong|vietnam|fuji|tokyo|seoul/i', strtolower((string) $location . ' ' . get_the_title()));
    $tour_highlights = array_slice(array_filter([
        $duration ? $duration . ' itinerary yang sudah disusun rapi' : '',
        $location ? 'Destinasi utama: ' . $location : '',
        !empty($includes_items) ? $includes_items[0] : '',
        !empty($includes_items[1]) ? $includes_items[1] : '',
    ]), 0, 4);

    $ideal_for = [
        contenly_tr('First timer yang pengin trip lebih terarah dan nggak ribet urus detail sendiri.', 'First-time travellers who want a more guided trip without handling every detail themselves.'),
        $is_international ? contenly_tr('Traveler internasional yang butuh flow itinerary, dokumen, dan pendampingan yang lebih jelas.', 'International travellers who need a clearer itinerary flow, documents, and support.') : contenly_tr('Traveler domestik yang mau short escape dengan flow perjalanan yang efisien.', 'Domestic travellers looking for a short escape with an efficient travel flow.'),
        contenly_tr('Pasangan, small group, atau family kecil yang cari itinerary rapi dan nyaman diikuti.', 'Couples, small groups, or small families looking for a neat and comfortable itinerary.'),
    ];

    $tour_faqs = [
        [
            'q' => contenly_tr('Apakah paket ini bisa request custom?', 'Can this package be customised?'),
            'a' => contenly_tr('Bisa. Kalau butuh ubah hotel, durasi, aktivitas, atau tipe trip private, tinggal isi form kebutuhan dulu sebelum booking.', 'Yes. If you need to change the hotel, duration, activities, or the type of private trip, just fill in the trip request form before booking.'),
        ],
        [
            'q' => contenly_tr('Apakah harga sudah termasuk semua kebutuhan utama?', 'Does the price include all main necessities?'),
            'a' => contenly_tr('Harga mengikuti detail yang tertulis di bagian included dan excluded. Jadi user bisa lihat dari awal apa yang sudah masuk dan apa yang perlu disiapkan sendiri.', 'The price follows the details listed in the included and excluded sections. That way, you can see from the start what is covered and what needs to be prepared separately.'),
        ],
        [
            'q' => contenly_tr('Kalau masih bingung pilih tanggal atau jumlah pax?', 'What if I am still unsure about the date or number of travellers?'),
            'a' => contenly_tr('Bisa lanjut isi form kebutuhan dulu. Tim Whale Dive Centre bisa bantu cek opsi tanggal, penyesuaian pax, dan skenario budget yang paling masuk.', 'You can continue by filling in the request form first. The Whale Dive Centre team can help check date options, traveller adjustments, and the most suitable budget scenario.'),
        ],
    ];

    $contact = function_exists('contenly_get_contact_details') ? contenly_get_contact_details() : [];
    $request_form_link = esc_url(contenly_localized_url('/contact/#contact-form-start'));
?>

<main class="site-main" style="padding-top: 0;">
    <article class="tour-single">
        <!-- Hero Section -->
        <section class="tour-hero-image" style="position:relative; min-height: 420px; display:flex; align-items:flex-end; <?php echo $hero_image_url ? "background-image:url('" . esc_url($hero_image_url) . "'); background-size:cover; background-position:center;" : 'background: linear-gradient(135deg, #355F72 0%, #539294 65%, #E5A736 100%);'; ?>">
            <div style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(15,23,42,0.25) 0%, rgba(15,23,42,0.78) 100%);"></div>
            <div style="position:relative; max-width: 1200px; width:100%; margin: 0 auto; padding: 56px 20px;">
                <h1 style="font-size: clamp(30px, 5vw, 48px); font-weight: 800; color: white; margin-bottom: 12px; line-height: 1.2; text-shadow: 0 2px 12px rgba(0,0,0,0.3);">
                    <?php the_title(); ?>
                </h1>
                <?php if ($location) : ?>
                <p style="font-size: 17px; color: rgba(255,255,255,0.92); display: flex; align-items: center; gap: 8px; margin:0;">
                    <span>📍</span> <?php echo esc_html($location); ?>
                </p>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Content Grid -->
        <section class="tour-content-section" style="padding: 40px 20px;">
            <div class="tour-content-grid" style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 400px; gap: 40px;">
                
                <!-- Main Content -->
                <div>
                    
                    <!-- Tour Overview -->
                    <div style="margin-bottom: 40px;">
                        <h2 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">Tour Overview</h2>
                        <div style="color: #475569; line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($tour_highlights)) : ?>
                    <div class="tour-section-card" style="margin-bottom: 32px; background:#EEF5F4; border:1px solid #D8E8E8; border-radius:20px; padding:24px;">
                        <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 14px;"><?php echo esc_html(contenly_tr('Highlight Paket', 'Package Highlights')); ?></h2>
                        <div style="display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px;">
                            <?php foreach ($tour_highlights as $highlight) : ?>
                            <div style="display:flex; gap:10px; align-items:flex-start; background:#fff; border-radius:14px; padding:14px; border:1px solid #DCE9E6;">
                                <span style="font-size:18px; line-height:1;">✨</span>
                                <span style="color:#334155; line-height:1.7;"><?php echo esc_html($highlight); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="tour-section-card" style="margin-bottom: 32px; background:#fff; border:1px solid #e2e8f0; border-radius:20px; padding:24px; box-shadow:0 8px 20px rgba(15,23,42,.04);">
                        <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 14px;"><?php echo esc_html(contenly_tr('Paket ini cocok untuk siapa?', 'Who is this package for?')); ?></h2>
                        <div style="display:grid; gap:12px;">
                            <?php foreach ($ideal_for as $ideal_item) : ?>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <span style="width:28px;height:28px;border-radius:999px;background:#DCE9E6;color:#355F72;display:inline-flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;flex:0 0 auto;">✓</span>
                                <p style="margin:0;color:#475569;line-height:1.75;"><?php echo esc_html($ideal_item); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Itinerary -->
                    <?php if ($itinerary && is_array($itinerary)) : ?>
                    <div style="margin-bottom: 40px;">
                        <h2 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">Itinerary</h2>
                        <div style="display: grid; gap: 20px;">
                            <?php foreach ($itinerary as $index => $day) : ?>
                            <div style="background: #f8fafc; padding: 24px; border-radius: 16px; border-left: 4px solid #539294;">
                                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                                    <?php echo esc_html($day['day'] ?? 'Day ' . ($index + 1)); ?>
                                </h3>
                                <p style="color: #64748b; line-height: 1.6;">
                                    <?php echo esc_html($day['description'] ?? ''); ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Includes & Excludes -->
                    <?php if ($includes || $excludes) : ?>
                    <div style="margin-bottom: 40px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <?php if ($includes) : ?>
                            <div style="background: #f0fdf4; padding: 24px; border-radius: 16px;">
                                <h3 style="font-size: 18px; font-weight: 700; color: #166534; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                                    ✅ Included
                                </h3>
                                <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 8px;">
                                    <?php foreach (explode("\n", $includes) as $item) : if (trim($item)) : ?>
                                    <li style="color: #166534; display: flex; align-items: center; gap: 8px;">
                                        <span style="color: #10b981;">•</span> <?php echo esc_html(trim($item)); ?>
                                    </li>
                                    <?php endif; endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($excludes) : ?>
                            <div style="background: #fef2f2; padding: 24px; border-radius: 16px;">
                                <h3 style="font-size: 18px; font-weight: 700; color: #991b1b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                                    ❌ Excluded
                                </h3>
                                <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 8px;">
                                    <?php foreach (explode("\n", $excludes) as $item) : if (trim($item)) : ?>
                                    <li style="color: #991b1b; display: flex; align-items: center; gap: 8px;">
                                        <span style="color: #dc2626;">•</span> <?php echo esc_html(trim($item)); ?>
                                    </li>
                                    <?php endif; endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="tour-section-card" style="margin-bottom: 40px; background:#fff; border:1px solid #e2e8f0; border-radius:20px; padding:24px; box-shadow:0 8px 20px rgba(15,23,42,.04);">
                        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-bottom:18px;">
                            <div>
                                <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin:0 0 8px;"><?php echo esc_html(contenly_tr('Masih ragu pilih paket ini?', 'Still unsure about this package?')); ?></h2>
                                <p style="margin:0; color:#64748b; line-height:1.75; max-width:700px;"><?php echo esc_html(contenly_tr('Kalau butuh bantuan tim, isi form kebutuhan trip dulu. Nanti admin review itinerary, tanggal, jumlah pax, sampai preferensi budget yang paling masuk.', 'If you need help from the team, fill in the trip request form first. The admin will review your itinerary, dates, number of travellers, and the most suitable budget preferences.')); ?></p>
                            </div>
                            <a href="<?php echo $request_form_link; ?>" style="display:inline-flex; align-items:center; justify-content:center; padding:13px 18px; border-radius:12px; background:linear-gradient(135deg,#355F72,#539294); color:#fff; text-decoration:none; font-weight:800; white-space:nowrap;"><?php echo esc_html(contenly_tr('Isi Form Kebutuhan', 'Fill in the Request Form')); ?></a>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px;">
                            <div style="background:#f8fafc; border-radius:16px; padding:16px; border:1px solid #e2e8f0;">
                                <div style="font-size:12px; text-transform:uppercase; letter-spacing:.05em; color:#64748b; font-weight:700; margin-bottom:8px;"><?php echo esc_html(contenly_tr('Proses booking', 'Booking process')); ?></div>
                                <p style="margin:0; color:#334155; line-height:1.7;"><?php echo esc_html(contenly_tr('Mulai dari form singkat dulu, lalu tim bantu review detail perjalanan sebelum lanjut pembayaran.', 'Start with a short form first, then the team helps review your trip details before moving to payment.')); ?></p>
                            </div>
                            <div style="background:#f8fafc; border-radius:16px; padding:16px; border:1px solid #e2e8f0;">
                                <div style="font-size:12px; text-transform:uppercase; letter-spacing:.05em; color:#64748b; font-weight:700; margin-bottom:8px;"><?php echo esc_html(contenly_tr('Pendampingan admin', 'Admin support')); ?></div>
                                <p style="margin:0; color:#334155; line-height:1.7;"><?php echo esc_html(contenly_tr('Kalau ada kebutuhan visa, itinerary custom, atau request hotel, tinggal isi form kebutuhan sebelum deal.', 'If you need visa support, a custom itinerary, or hotel requests, simply fill in the request form before confirming.')); ?></p>
                            </div>
                            <div style="background:#f8fafc; border-radius:16px; padding:16px; border:1px solid #e2e8f0;">
                                <div style="font-size:12px; text-transform:uppercase; letter-spacing:.05em; color:#64748b; font-weight:700; margin-bottom:8px;"><?php echo esc_html(contenly_tr('Keputusan lebih aman', 'Safer decision making')); ?></div>
                                <p style="margin:0; color:#334155; line-height:1.7;"><?php echo esc_html(contenly_tr('User bisa lihat apa yang included, apa yang belum, dan ngobrol dulu sebelum commit ke booking.', 'Users can see what is included, what is not, and discuss it first before committing to a booking.')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="tour-section-card" style="margin-bottom: 40px; background:#fff; border:1px solid #e2e8f0; border-radius:20px; padding:24px; box-shadow:0 8px 20px rgba(15,23,42,.04);">
                        <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin:0 0 16px;"><?php echo esc_html(contenly_tr('FAQ Singkat', 'Quick FAQ')); ?></h2>
                        <div style="display:grid; gap:12px;">
                            <?php foreach ($tour_faqs as $faq) : ?>
                            <details style="border:1px solid #e2e8f0; border-radius:14px; padding:16px 18px; background:#fff;">
                                <summary style="cursor:pointer; font-weight:700; color:#0f172a; list-style:none;"><?php echo esc_html($faq['q']); ?></summary>
                                <p style="margin:12px 0 0; color:#64748b; line-height:1.75;"><?php echo esc_html($faq['a']); ?></p>
                            </details>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Booking CTA / Form -->
                <div class="tour-booking-column">
                    <div class="tour-booking-sticky-wrap">
                        <?php if ($is_booking_detail) : ?>
                            <div id="booking-form-section" style="background:#fff;border-radius:16px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,0.08);margin-bottom:16px;">
                                <h3 style="margin:0 0 8px;font-size:20px;font-weight:800;color:#0f172a;">Complete Booking Details</h3>
                                <p style="margin:0;color:#64748b;font-size:14px;">Fill the form below to continue your booking.</p>
                            </div>
                            <?php
                            // Full booking form on booking detail mode
                            include get_template_directory() . '/booking-form.php';
                            ?>
                        <?php else : ?>
                            <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                                <div style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:4px;">Rp <?php echo number_format($price, 0, ',', '.'); ?></div>
                                <div style="color:#64748b;font-size:14px;margin-bottom:16px;">per person</div>

                                <div style="display:grid;gap:10px;margin-bottom:20px;color:#475569;font-size:14px;">
                                    <div>⏱️ <?php echo esc_html($duration); ?> days</div>
                                    <div>👥 Min: <?php echo esc_html($min_pax); ?> pax</div>
                                    <div>🎫 Quota: <?php echo esc_html($quota); ?> persons</div>
                                </div>

                                <div style="background:#EEF5F4;border:1px solid #D8E8E8;border-radius:14px;padding:14px 16px;margin-bottom:18px;">
                                    <div style="font-size:13px;font-weight:800;color:#355F72;letter-spacing:.03em;text-transform:uppercase;margin-bottom:6px;"><?php echo esc_html(contenly_tr('Butuh review admin dulu?', 'Need admin review first?')); ?></div>
                                    <p style="margin:0;color:#475569;line-height:1.7;font-size:14px;"><?php echo esc_html(contenly_tr('Kalau mau cek kecocokan itinerary, budget, atau dokumen perjalanan, isi form kebutuhan dulu sebelum lanjut booking detail.', 'If you want to check itinerary fit, budget, or travel documents, fill in the request form first before continuing to booking details.')); ?></p>
                                </div>

                                <button type="button"
                                        class="book-tour-toggle-btn"
                                        data-target="booking-form-inline-<?php echo esc_attr($tour_id); ?>"
                                        data-booking-url="<?php echo esc_url(contenly_localized_url('/booking-detail/?tour_id=' . $tour_id)); ?>"
                                        style="display:block;width:100%;text-align:center;padding:14px 16px;background:linear-gradient(135deg,#539294,#539294);color:#fff;text-decoration:none;border:none;border-radius:12px;font-weight:700;cursor:pointer;">
                                    🎫 <?php echo esc_html(contenly_tr('Lanjut Detail Booking', 'Continue to Booking Details')); ?>
                                </button>
                                <a href="<?php echo $request_form_link; ?>" style="display:flex;width:100%;margin-top:10px;text-align:center;padding:12px 16px;background:#fff;color:#355F72;border:1px solid #D8E8E8;border-radius:12px;font-weight:700;cursor:pointer;text-decoration:none;align-items:center;justify-content:center;">
                                    📝 <?php echo esc_html(contenly_tr('Isi Form Kebutuhan Trip', 'Fill in the Trip Request Form')); ?>
                                </a>
                                <button type="button" class="single-wishlist-btn" data-tour-id="<?php echo esc_attr($tour_id); ?>" data-in-wishlist="<?php echo $in_wishlist ? '1' : '0'; ?>" style="display:block;width:100%;margin-top:10px;text-align:center;padding:12px 16px;background:#fff;color:#334155;border:1px solid #cbd5e1;border-radius:12px;font-weight:700;cursor:pointer;">
                                    <?php echo $in_wishlist ? '❤️ Saved in Wishlist' : '🤍 Save to Wishlist'; ?>
                                </button>
                                <div style="margin-top:14px;display:grid;gap:8px;color:#64748b;font-size:13px;line-height:1.6;">
                                    <div>✓ <?php echo esc_html(contenly_tr('Bisa isi request dulu sebelum commit booking', 'You can submit a request first before committing to booking')); ?></div>
                                    <div>✓ <?php echo esc_html(contenly_tr('Tim bantu review detail traveler dan kebutuhan trip', 'The team helps review traveller details and trip requirements')); ?></div>
                                    <div>✓ <?php echo esc_html(contenly_tr('Cocok untuk private trip, family trip, atau request custom', 'Suitable for private trips, family trips, or custom requests')); ?></div>
                                </div>
                            </div>

                            <div id="booking-form-inline-<?php echo esc_attr($tour_id); ?>" class="booking-form-inline" style="display:none; margin-top:16px;">
                                <div style="background:#fff;border-radius:16px;padding:20px;box-shadow:0 4px 20px rgba(0,0,0,0.08);margin-bottom:16px;">
                                    <h3 style="margin:0 0 8px;font-size:20px;font-weight:800;color:#0f172a;">Complete Booking Details</h3>
                                    <p style="margin:0;color:#64748b;font-size:14px;">Fill the form below to continue your booking.</p>
                                </div>
                                <?php include get_template_directory() . '/booking-form.php'; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </section>
    <!-- Reviews Section -->
    <?php include get_template_directory() . '/tour-reviews-section.php'; ?>
    
    </article>
</main>

<?php endwhile; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.book-tour-toggle-btn');
    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var bookingUrl = btn.getAttribute('data-booking-url');
            if (bookingUrl) {
                window.location.href = bookingUrl;
                return;
            }

            var targetId = btn.getAttribute('data-target');
            var target = document.getElementById(targetId);
            if (!target) return;

            var isOpen = target.style.display !== 'none';
            target.style.display = isOpen ? 'none' : 'block';
            btn.textContent = isOpen ? '🎫 Book This Tour' : '✖ Close Booking Form';

            if (!isOpen) {
                setTimeout(function () {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 80);
            }
        });
    });

    var wlBtn = document.querySelector('.single-wishlist-btn');
    if (wlBtn) {
        wlBtn.addEventListener('click', async function () {
            if (!window.contenlyBooking) {
                window.location.href = '/login?redirect_to=' + encodeURIComponent(window.location.pathname);
                return;
            }
            var fd = new FormData();
            fd.append('action', 'contenly_toggle_wishlist');
            fd.append('tour_id', wlBtn.getAttribute('data-tour-id'));
            fd.append('nonce', window.contenlyBooking.nonce || '');
            wlBtn.disabled = true;
            try {
                const res = await fetch('/wp-admin/admin-ajax.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    const inWl = !!data.data.in_wishlist;
                    wlBtn.setAttribute('data-in-wishlist', inWl ? '1' : '0');
                    wlBtn.innerHTML = inWl ? '❤️ Saved in Wishlist' : '🤍 Save to Wishlist';
                }
            } catch (e) {}
            wlBtn.disabled = false;
        });
    }
});
</script>

<style>
.tour-single {
    overflow: visible;
}

.tour-content-grid {
    align-items: start !important;
}

.tour-booking-column {
    position: relative;
    align-self: start;
    min-width: 0;
}

.tour-booking-sticky-wrap {
    position: sticky;
    top: 108px;
    max-height: calc(100vh - 124px);
    overflow: auto;
    padding-right: 6px;
    scrollbar-width: thin;
}

.tour-booking-sticky-wrap::-webkit-scrollbar {
    width: 8px;
}

.tour-booking-sticky-wrap::-webkit-scrollbar-thumb {
    background: rgba(148,163,184,.8);
    border-radius: 999px;
}

@media (max-width: 1024px) {
    .tour-content-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
    }

    .tour-booking-sticky-wrap {
        position: static;
        top: auto;
    }
}

@media (max-width: 768px) {
    .tour-hero-image {
        min-height: 300px !important;
    }

    .tour-content-section {
        padding: 24px 12px !important;
    }

    .tour-content-grid {
        gap: 18px !important;
    }

    .tour-section-card div[style*="grid-template-columns:repeat(2"],
    .tour-section-card div[style*="grid-template-columns:repeat(3"],
    .tour-content-section div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }

    .tour-content-grid > div {
        min-width: 0;
    }

    .tour-booking-column {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
    }

    .tour-booking-column > div {
        position: static !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
    }
}
</style>

<?php get_footer(); ?>
