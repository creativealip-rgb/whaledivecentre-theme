<?php
/**
 * Template Name: Dashboard
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$course_requests = get_user_meta($user_id, '_wdc_course_requests', true);
$gear_requests = get_user_meta($user_id, '_wdc_gear_requests', true);
$course_requests = is_array($course_requests) ? $course_requests : [];
$gear_requests = is_array($gear_requests) ? $gear_requests : [];
$course_orders = get_user_meta($user_id, '_wdc_course_orders', true);
$gear_orders = get_user_meta($user_id, '_wdc_gear_orders', true);
$course_orders = is_array($course_orders) ? $course_orders : [];
$gear_orders = is_array($gear_orders) ? $gear_orders : [];
$active_items = array_filter(array_merge($course_orders, $gear_orders), function($item) {
    return in_array($item['status'] ?? '', ['Verified', 'Active', 'Completed'], true);
});
?>
<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:8px;"><?php echo contenly_tr('Dashboard Member', 'Member Dashboard'); ?></h1>
    <p style="font-size:15px;color:#64748b;"><?php echo contenly_tr('Pusat Whale Dive Centre untuk perencanaan kursus, permintaan alat selam, dan dukungan kru.', 'Your Whale Dive Centre hub for course planning, scuba gear requests, and crew support.'); ?></p>
</div>

<div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:28px;">
    <div style="background:linear-gradient(135deg,#eef9fc,#dff4fa);padding:20px;border-radius:16px;border:1px solid #ccecf5;">
        <div style="font-size:12px;color:#0b617c;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Permintaan Kursus', 'Course Requests'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#06384d;"><?php echo count($course_requests); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);padding:20px;border-radius:16px;border:1px solid #fed7aa;">
        <div style="font-size:12px;color:#9a3412;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Permintaan Peralatan', 'Gear Requests'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#7c2d12;"><?php echo count($gear_requests); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#f8fafc,#eef2ff);padding:20px;border-radius:16px;border:1px solid #e2e8f0;">
        <div style="font-size:12px;color:#475569;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Pesanan Langsung', 'Direct Orders'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#0f172a;"><?php echo count($course_orders) + count($gear_orders); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#ecfdf5,#dcfce7);padding:20px;border-radius:16px;border:1px solid #bbf7d0;">
        <div style="font-size:12px;color:#166534;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;"><?php echo contenly_tr('Terverifikasi / Aktif', 'Verified / Active'); ?></div>
        <div style="font-size:34px;font-weight:950;color:#166534;"><?php echo count($active_items); ?></div>
    </div>
</div>

<!-- Giveaway Section (only for users who haven't claimed) -->
<?php if (is_user_logged_in() && get_option('wdc_giveaway_enabled', '1') && !wdc_user_claimed_giveaway()) : ?>
<section id="wdc-giveaway-section" style="background:linear-gradient(135deg,#fef9c3,#fef08a);border:2px solid #facc15;border-radius:20px;padding:28px;margin-bottom:28px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
        <span style="font-size:32px;">🎁</span>
        <div>
            <h2 style="font-size:22px;font-weight:900;color:#0f172a;margin:0;"><?php echo contenly_tr('Selamat Datang! Pilih Giveaway Kamu', 'Welcome! Pick Your Giveaway'); ?></h2>
            <p style="font-size:14px;color:#713f12;margin:4px 0 0;"><?php echo contenly_tr('Barangnya gratis — kamu cukup bayar ongkirnya aja!', 'Items are free — just pay for shipping!'); ?></p>
        </div>
    </div>

    <div id="wdc-giveaway-items" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:20px;">
        <?php foreach (wdc_get_giveaway_items() as $item) : ?>
        <label class="wdc-giveaway-card" data-item-id="<?php echo esc_attr($item['id']); ?>" style="background:#fff;border:2px solid #e5e7eb;border-radius:16px;padding:20px;cursor:pointer;transition:all .2s;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative;">
            <input type="checkbox" name="wdc_giveaway_items[]" value="<?php echo esc_attr($item['id']); ?>" style="position:absolute;top:12px;right:12px;width:20px;height:20px;accent-color:#10b981;">
            <div style="width:80px;height:80px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:36px;">
                <?php
                $icons = ['sticker-pack' => '🏷️', 'lanyard' => '🪢', 'keychain' => '🔑'];
                echo $icons[$item['id']] ?? '🎁';
                ?>
            </div>
            <strong style="font-size:15px;color:#0f172a;margin-bottom:4px;"><?php echo esc_html($item['name']); ?></strong>
            <span style="font-size:13px;color:#64748b;"><?php echo esc_html($item['desc']); ?></span>
            <span style="font-size:11px;color:#10b981;font-weight:800;margin-top:8px;text-transform:uppercase;"><?php echo contenly_tr('GRATIS', 'FREE'); ?></span>
        </label>
        <?php endforeach; ?>
    </div>

    <!-- Shipping address form (hidden until items selected) -->
    <div id="wdc-giveaway-shipping" style="display:none;background:#fff;border-radius:16px;padding:24px;border:1px solid #e5e7eb;">
        <h3 style="font-size:18px;font-weight:800;color:#0f172a;margin:0 0 16px;"><?php echo contenly_tr('Detail Pengiriman', 'Shipping Details'); ?></h3>

        <div style="display:grid;gap:14px;">
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Nama Lengkap', 'Full Name'); ?> *</label>
                <input type="text" id="wdc-gw-name" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="Nama penerima">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('No. HP', 'Phone Number'); ?> *</label>
                <input type="tel" id="wdc-gw-phone" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Kota / Kodepos Tujuan', 'Destination City / Postal Code'); ?> *</label>
                <div style="position:relative;">
                    <input type="text" id="wdc-gw-city" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;" placeholder="Ketik nama kota atau kodepos..." autocomplete="off">
                    <div id="wdc-gw-city-dropdown" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #d1d5db;border-radius:10px;max-height:200px;overflow-y:auto;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.12);"></div>
                </div>
                <input type="hidden" id="wdc-gw-area-id" value="">
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:700;color:#374151;margin-bottom:4px;"><?php echo contenly_tr('Alamat Lengkap', 'Full Address'); ?> *</label>
                <textarea id="wdc-gw-address" rows="3" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:14px;resize:vertical;" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
            </div>
        </div>

        <button id="wdc-btn-check-shipping" style="margin-top:16px;width:100%;padding:14px;background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:800;cursor:pointer;">
            <?php echo contenly_tr('🔍 Cek Ongkir', '🔍 Check Shipping Cost'); ?>
        </button>

        <!-- Shipping rates result -->
        <div id="wdc-shipping-rates" style="display:none;margin-top:18px;">
            <h4 style="font-size:15px;font-weight:800;color:#0f172a;margin:0 0 10px;"><?php echo contenly_tr('Pilih Kurir', 'Choose Courier'); ?></h4>
            <div id="wdc-rates-list" style="display:grid;gap:8px;"></div>
        </div>

        <!-- Submit button (hidden until courier selected) -->
        <button id="wdc-btn-claim-giveaway" style="display:none;margin-top:16px;width:100%;padding:16px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;border:none;border-radius:12px;font-size:16px;font-weight:900;cursor:pointer;">
            🎁 <?php echo contenly_tr('Klaim Giveaway & Bayar Ongkir', 'Claim Giveaway & Pay Shipping'); ?>
        </button>
    </div>

    <div id="wdc-giveaway-error" style="display:none;background:#fee2e2;color:#991b1b;border-radius:10px;padding:12px;margin-top:12px;font-size:14px;font-weight:600;"></div>
</section>

<style>
.wdc-giveaway-card:hover { border-color:#10b981 !important; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
.wdc-giveaway-card.selected { border-color:#10b981 !important; background:#f0fdf4 !important; box-shadow:0 0 0 3px rgba(16,185,129,.2); }
.wdc-rate-option { padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:all .15s; }
.wdc-rate-option:hover { border-color:#059669;background:#f0fdf4; }
.wdc-rate-option.active { border-color:#059669;background:#ecfdf5; }
</style>

<script>
jQuery(document).ready(function($) {
    var selectedItems = [];
    var selectedRate = null;
    var searchTimer = null;

    // Card selection
    $('.wdc-giveaway-card input[type="checkbox"]').on('change', function() {
        var card = $(this).closest('.wdc-giveaway-card');
        if (this.checked) {
            card.addClass('selected');
        } else {
            card.removeClass('selected');
        }
        selectedItems = [];
        $('.wdc-giveaway-card input:checked').each(function() {
            selectedItems.push($(this).val());
        });
        if (selectedItems.length > 0) {
            $('#wdc-giveaway-shipping').slideDown(200);
        } else {
            $('#wdc-giveaway-shipping').slideUp(200);
        }
        $('#wdc-shipping-rates').hide();
        $('#wdc-btn-claim-giveaway').hide();
        selectedRate = null;
    });

    // City autocomplete (debounced)
    $('#wdc-gw-city').on('input', function() {
        var query = $(this).val();
        clearTimeout(searchTimer);
        if (query.length < 3) {
            $('#wdc-gw-city-dropdown').hide();
            return;
        }
        searchTimer = setTimeout(function() {
            $.post(wdcGiveawayAjax.ajaxurl, {
                action: 'wdc_search_area',
                nonce: wdcGiveawayAjax.nonce,
                query: query
            }, function(res) {
                if (!res.success || !res.data.areas.length) {
                    $('#wdc-gw-city-dropdown').hide();
                    return;
                }
                var html = '';
                res.data.areas.forEach(function(a) {
                    html += '<div class="wdc-city-option" data-id="' + a.id + '" data-name="' + a.name + '" style="padding:10px 14px;cursor:pointer;border-bottom:1px solid #f1f5f9;font-size:13px;">';
                    html += '<strong>' + a.name + '</strong>';
                    if (a.admin2_name) html += ' <span style="color:#64748b;">— ' + a.admin2_name + (a.admin1_name ? ', ' + a.admin1_name : '') + '</span>';
                    if (a.postal_code) html += ' <span style="color:#9ca3af;">(' + a.postal_code + ')</span>';
                    html += '</div>';
                });
                $('#wdc-gw-city-dropdown').html(html).show();
            });
        }, 400);
    });

    // Select city from dropdown
    $(document).on('click', '.wdc-city-option', function() {
        var name = $(this).data('name');
        var id = $(this).data('id');
        $('#wdc-gw-city').val(name);
        $('#wdc-gw-area-id').val(id);
        $('#wdc-gw-city-dropdown').hide();
        // Reset rates
        $('#wdc-shipping-rates').hide();
        $('#wdc-btn-claim-giveaway').hide();
        selectedRate = null;
    });

    // Close dropdown on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#wdc-gw-city, #wdc-gw-city-dropdown').length) {
            $('#wdc-gw-city-dropdown').hide();
        }
    });

    // Check shipping
    $('#wdc-btn-check-shipping').on('click', function() {
        var btn = $(this);
        var city = $('#wdc-gw-city').val();
        var areaId = $('#wdc-gw-area-id').val();

        if (!areaId) {
            showGiveawayError('<?php echo contenly_tr('Pilih kota dari dropdown dulu ya.', 'Please select a city from the dropdown first.'); ?>');
            return;
        }
        if (selectedItems.length === 0) {
            showGiveawayError('<?php echo contenly_tr('Pilih minimal 1 item giveaway.', 'Select at least 1 giveaway item.'); ?>');
            return;
        }

        btn.prop('disabled', true).html('⏳ <?php echo contenly_tr('Menghitung ongkir...', 'Calculating shipping...'); ?>');
        hideGiveawayError();

        $.post(wdcGiveawayAjax.ajaxurl, {
            action: 'wdc_check_shipping',
            nonce: wdcGiveawayAjax.nonce,
            destination: city,
            item_ids: selectedItems,
            couriers: 'jne,jnt,sicepat'
        }, function(res) {
            btn.prop('disabled', false).html('🔍 <?php echo contenly_tr('Cek Ongkir', 'Check Shipping Cost'); ?>');
            if (!res.success) {
                showGiveawayError(res.data.message);
                return;
            }
            renderRates(res.data.rates);
        }).fail(function() {
            btn.prop('disabled', false).html('🔍 <?php echo contenly_tr('Cek Ongkir', 'Check Shipping Cost'); ?>');
            showGiveawayError('<?php echo contenly_tr('Gagal menghubungi server.', 'Failed to reach server.'); ?>');
        });
    });

    function renderRates(rates) {
        if (!rates || rates.length === 0) {
            showGiveawayError('<?php echo contenly_tr('Tidak ada kurir tersedia untuk tujuan ini.', 'No couriers available for this destination.'); ?>');
            $('#wdc-shipping-rates').hide();
            return;
        }
        var html = '';
        rates.forEach(function(r, i) {
            var costFmt = 'Rp ' + r.cost.toLocaleString('id-ID');
            html += '<div class="wdc-rate-option" data-index="' + i + '" data-courier="' + r.courier_code + '" data-service="' + r.service_code + '" data-cost="' + r.cost + '">';
            html += '<div><strong style="text-transform:uppercase;">' + r.courier + '</strong> ' + r.service;
            if (r.etd) html += ' <span style="color:#64748b;font-size:12px;">(' + r.etd + ')</span>';
            html += '</div>';
            html += '<strong style="color:#059669;">' + costFmt + '</strong>';
            html += '</div>';
        });
        $('#wdc-rates-list').html(html);
        $('#wdc-shipping-rates').slideDown(200);
        $('#wdc-btn-claim-giveaway').hide();
        selectedRate = null;
    }

    // Select rate
    $(document).on('click', '.wdc-rate-option', function() {
        $('.wdc-rate-option').removeClass('active');
        $(this).addClass('active');
        selectedRate = {
            courier: $(this).data('courier'),
            service: $(this).data('service'),
            cost: $(this).data('cost')
        };
        $('#wdc-btn-claim-giveaway').html('🎁 <?php echo contenly_tr('Klaim & Bayar Ongkir', 'Claim & Pay Shipping'); ?>: Rp ' + selectedRate.cost.toLocaleString('id-ID'));
        $('#wdc-btn-claim-giveaway').slideDown(200);
    });

    // Claim giveaway
    $('#wdc-btn-claim-giveaway').on('click', function() {
        var btn = $(this);
        var name = $('#wdc-gw-name').val().trim();
        var phone = $('#wdc-gw-phone').val().trim();
        var address = $('#wdc-gw-address').val().trim();
        var city = $('#wdc-gw-city').val();
        var areaId = $('#wdc-gw-area-id').val();

        if (!name || !phone || !address || !areaId) {
            showGiveawayError('<?php echo contenly_tr('Lengkapi semua data pengiriman.', 'Complete all shipping details.'); ?>');
            return;
        }
        if (!selectedRate) {
            showGiveawayError('<?php echo contenly_tr('Pilih kurir dulu.', 'Select a courier first.'); ?>');
            return;
        }

        btn.prop('disabled', true).html('⏳ Memproses...');
        hideGiveawayError();

        $.post(wdcGiveawayAjax.ajaxurl, {
            action: 'wdc_submit_giveaway',
            nonce: wdcGiveawayAjax.nonce,
            item_ids: selectedItems,
            courier: selectedRate.courier,
            service: selectedRate.service,
            shipping_cost: selectedRate.cost,
            destination: city,
            dest_area_id: areaId,
            address: address,
            phone: phone,
            recipient_name: name
        }, function(res) {
            if (!res.success) {
                showGiveawayError(res.data.message);
                btn.prop('disabled', false).html('🎁 <?php echo contenly_tr('Klaim & Bayar Ongkir', 'Claim & Pay Shipping'); ?>');
                return;
            }
            // Success — redirect to giveaway checkout
            window.location.href = res.data.checkout_url;
        }).fail(function() {
            showGiveawayError('<?php echo contenly_tr('Gagal menghubungi server.', 'Failed to reach server.'); ?>');
            btn.prop('disabled', false).html('🎁 <?php echo contenly_tr('Klaim & Bayar Ongkir', 'Claim & Pay Shipping'); ?>');
        });
    });

    function showGiveawayError(msg) { $('#wdc-giveaway-error').text(msg).show(); }
    function hideGiveawayError() { $('#wdc-giveaway-error').hide(); }
});
</script>
<?php endif; ?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;margin-bottom:28px;">
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <span style="font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;"><?php echo contenly_tr('Belajar', 'Learn'); ?></span>
        <h2 style="font-size:24px;color:#0f172a;margin:10px 0 10px;letter-spacing:.03em;"><?php echo contenly_tr('Gabung Kursus Menyelam', 'Join a dive course'); ?></h2>
        <p style="color:#64748b;line-height:1.65;margin:0 0 18px;"><?php echo contenly_tr('Mulai Open Water, lanjut ke Advanced, atau bangun keterampilan rescue dan kepemimpinan bersama kru.', 'Start Open Water, continue to Advanced, or build safer rescue and leadership skills with the crew.'); ?></p>
        <a href="/my-courses/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Buka Kursus Saya', 'Open My Courses'); ?></a>
    </article>
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <span style="font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;"><?php echo contenly_tr('Peralatan', 'Gear'); ?></span>
        <h2 style="font-size:24px;color:#0f172a;margin:10px 0 10px;letter-spacing:.03em;"><?php echo contenly_tr('Beli Peralatan Selam', 'Buy scuba equipment'); ?></h2>
        <p style="color:#64748b;line-height:1.65;margin:0 0 18px;"><?php echo contenly_tr('Jelajahi masker, fin, BCD, regulator, baju selam, dan dive computer dengan bantuan fitting sebelum checkout.', 'Browse masks, fins, BCDs, regulators, wetsuits, and dive computers with fit support before checkout.'); ?></p>
        <a href="/my-gear/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Buka Peralatan Saya', 'Open My Gear'); ?></a>
    </article>
</div>


<?php
$recent_activity = array_slice(array_merge($course_orders, $gear_orders, $course_requests, $gear_requests), 0, 5);
$status_steps = ['Payment Uploaded' => 'Proof received', 'Verified' => 'Payment verified', 'Active' => 'Ready / active', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled', 'Requested' => 'Crew review', 'Awaiting Payment' => 'Waiting for payment'];
?>
<?php if (!empty($recent_activity)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;box-shadow:0 12px 34px rgba(15,23,42,.05);">
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;letter-spacing:.03em;"><?php echo contenly_tr('Aktivitas Terbaru', 'Latest Activity'); ?></h2>
    <div style="display:grid;gap:10px;">
        <?php foreach ($recent_activity as $item) : $status = $item['status'] ?? 'Requested'; ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php echo esc_html($item['item'] ?? $item['course'] ?? $item['gear'] ?? 'Member item'); ?></strong>
                <div style="font-size:13px;color:#64748b;"><?php echo esc_html($status_steps[$status] ?? 'Crew update'); ?> · <?php echo esc_html($item['admin_note'] ?? ($item['id'] ?? 'Crew will update this soon.')); ?></div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:8px;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.04em;">
                    <?php foreach (['Payment Uploaded', 'Verified', 'Active'] as $step) : $done = array_search($status, ['Payment Uploaded', 'Verified', 'Active', 'Completed'], true) !== false && array_search($step, ['Payment Uploaded', 'Verified', 'Active'], true) <= array_search($status, ['Payment Uploaded', 'Verified', 'Active', 'Completed'], true); ?>
                    <span style="padding:5px 8px;border-radius:999px;background:<?php echo $done ? '#dcfce7' : '#f1f5f9'; ?>;color:<?php echo $done ? '#166534' : '#64748b'; ?>;"><?php echo esc_html($step); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <span style="font-size:12px;font-weight:900;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($status); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<div style="background:linear-gradient(135deg,#f8fdff,#eef9fc);border:1px solid #ccecf5;border-radius:20px;padding:22px;">
    <div style="font-size:12px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;margin-bottom:6px;"><?php echo contenly_tr('Langkah Selanjutnya', 'Recommended next step'); ?></div>
    <h2 style="font-size:22px;color:#06384d;margin:0 0 6px;letter-spacing:.03em;"><?php echo contenly_tr('Pilih kursus atau minta saran peralatan.', 'Pick a course or request gear advice.'); ?></h2>
    <p style="color:#64748b;margin:0 0 16px;line-height:1.6;"><?php echo contenly_tr('Area member fokus pada kebutuhan anggota: kursus menyelam dan membeli peralatan selam yang tepat.', 'The member area now focuses on what Whale Dive Centre members need most: joining courses and buying the right dive gear.'); ?></p>
    <div style="display:flex;gap:10px;flex-wrap:wrap;"><a href="/my-courses/" style="display:inline-flex;padding:10px 14px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;"><?php echo contenly_tr('Lihat Kursus', 'Browse Courses'); ?></a><a href="/my-gear/" style="display:inline-flex;padding:10px 14px;border-radius:999px;background:#fff;color:#06384d;text-decoration:none;font-weight:900;border:1px solid #ccecf5;"><?php echo contenly_tr('Lihat Peralatan', 'Browse Gear'); ?></a></div>
</div>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
