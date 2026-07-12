<?php
/**
 * Template Name: Account Settings
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$user = wp_get_current_user();
$notice = '';
$notice_type = 'success';

/**
 * Rank diver level from completed courses (highest wins).
 */
if (!function_exists('wdc_rank_diver_level_from_text')) {
    function wdc_rank_diver_level_from_text($text) {
        $t = strtolower(trim((string) $text));
        if ($t === '') {
            return 0;
        }
        if (strpos($t, 'instructor') !== false) {
            return 70;
        }
        if (strpos($t, 'divemaster') !== false || strpos($t, 'dive master') !== false) {
            return 60;
        }
        if (strpos($t, 'master scuba') !== false) {
            return 55;
        }
        if (strpos($t, 'rescue') !== false) {
            return 50;
        }
        if (strpos($t, 'advanced open water') !== false || strpos($t, 'advanced scuba') !== false || $t === 'advanced' || strpos($t, 'next level') !== false) {
            return 40;
        }
        if (strpos($t, 'open water') !== false || strpos($t, 'scuba diver') !== false || $t === 'beginner') {
            return 30;
        }
        if (strpos($t, 'junior') !== false || strpos($t, 'trial') !== false || strpos($t, 'new diver') !== false) {
            return 10;
        }
        // Specialty / nitrox / first aid etc. keep below core ladder unless no core cert yet
        if (strpos($t, 'specialty') !== false || strpos($t, 'nitrox') !== false || strpos($t, 'first aid') !== false || strpos($t, 'deep') !== false || strpos($t, 'night') !== false) {
            return 35;
        }
        return 5;
    }
}

if (!function_exists('wdc_label_from_diver_rank')) {
    function wdc_label_from_diver_rank($rank) {
        if ($rank >= 70) {
            return 'Instructor';
        }
        if ($rank >= 60) {
            return 'Divemaster';
        }
        if ($rank >= 55) {
            return 'Master Scuba Diver';
        }
        if ($rank >= 50) {
            return 'Rescue Diver';
        }
        if ($rank >= 40) {
            return 'Advanced Open Water';
        }
        if ($rank >= 30) {
            return 'Open Water';
        }
        if ($rank >= 10) {
            return 'New Diver';
        }
        return contenly_tr('Belum ada sertifikasi', 'No certification yet');
    }
}

if (!function_exists('wdc_get_auto_diver_level')) {
    function wdc_get_auto_diver_level($user_id) {
        $completed = get_user_meta($user_id, '_wdc_completed_courses', true);
        $completed = is_array($completed) ? $completed : [];
        $best_rank = 0;
        $best_title = '';
        foreach ($completed as $row) {
            if (!is_array($row)) {
                continue;
            }
            $title = (string) ($row['course_title'] ?? '');
            $level = (string) ($row['level'] ?? '');
            $rank = max(
                wdc_rank_diver_level_from_text($title),
                wdc_rank_diver_level_from_text($level)
            );
            if ($rank > $best_rank) {
                $best_rank = $rank;
                $best_title = $title !== '' ? $title : $level;
            }
        }
        return [
            'rank' => $best_rank,
            'label' => wdc_label_from_diver_rank($best_rank),
            'source_title' => $best_title,
            'count' => count($completed),
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wdc_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_settings_nonce'])), 'wdc_account_settings')) {
    $display_name = sanitize_text_field(wp_unslash($_POST['display_name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $emergency_contact = sanitize_text_field(wp_unslash($_POST['emergency_contact'] ?? ''));
    $height = sanitize_text_field(wp_unslash($_POST['gear_height'] ?? ''));
    $weight = sanitize_text_field(wp_unslash($_POST['gear_weight'] ?? ''));
    $shoe = sanitize_text_field(wp_unslash($_POST['gear_shoe'] ?? ''));
    $wetsuit = sanitize_text_field(wp_unslash($_POST['gear_wetsuit'] ?? ''));
    $fit_notes = sanitize_textarea_field(wp_unslash($_POST['gear_fit_notes'] ?? ''));

    if ($display_name) {
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $display_name,
        ]);
        update_user_meta($user_id, '_wdc_phone', $phone);
        update_user_meta($user_id, '_wdc_emergency_contact', $emergency_contact);
        update_user_meta($user_id, '_wdc_gear_height', $height);
        update_user_meta($user_id, '_wdc_gear_weight', $weight);
        update_user_meta($user_id, '_wdc_gear_shoe', $shoe);
        update_user_meta($user_id, '_wdc_gear_wetsuit', $wetsuit);
        update_user_meta($user_id, '_wdc_gear_fit_notes', $fit_notes);

        // Keep legacy summary string for any old consumers.
        $legacy_bits = array_filter([
            $height !== '' ? 'Tinggi: ' . $height : '',
            $weight !== '' ? 'Berat: ' . $weight : '',
            $shoe !== '' ? 'Sepatu: ' . $shoe : '',
            $wetsuit !== '' ? 'Wetsuit: ' . $wetsuit : '',
            $fit_notes !== '' ? 'Fit: ' . $fit_notes : '',
        ]);
        update_user_meta($user_id, '_wdc_gear_sizes', implode(' | ', $legacy_bits));

        // Auto-sync certification label from completed courses.
        $auto = wdc_get_auto_diver_level($user_id);
        update_user_meta($user_id, '_wdc_certification', $auto['label']);

        $user = wp_get_current_user();
        $notice = contenly_tr('Pengaturan akun berhasil disimpan.', 'Account settings saved.');
    } else {
        $notice = contenly_tr('Nama tampilan wajib diisi.', 'Display name is required.');
        $notice_type = 'error';
    }
}

$phone = get_user_meta($user_id, '_wdc_phone', true);
$emergency_contact = get_user_meta($user_id, '_wdc_emergency_contact', true);
$gear_height = get_user_meta($user_id, '_wdc_gear_height', true);
$gear_weight = get_user_meta($user_id, '_wdc_gear_weight', true);
$gear_shoe = get_user_meta($user_id, '_wdc_gear_shoe', true);
$gear_wetsuit = get_user_meta($user_id, '_wdc_gear_wetsuit', true);
$gear_fit_notes = get_user_meta($user_id, '_wdc_gear_fit_notes', true);

// One-time migrate old free-text gear notes into fit notes if structured empty.
if ($gear_fit_notes === '' && $gear_height === '' && $gear_weight === '' && $gear_shoe === '' && $gear_wetsuit === '') {
    $legacy = (string) get_user_meta($user_id, '_wdc_gear_sizes', true);
    if ($legacy !== '') {
        $gear_fit_notes = $legacy;
    }
}

$auto_level = wdc_get_auto_diver_level($user_id);
// Keep certification meta in sync for admin/other pages.
if ((string) get_user_meta($user_id, '_wdc_certification', true) !== (string) $auto_level['label']) {
    update_user_meta($user_id, '_wdc_certification', $auto_level['label']);
}

$courses_url = function_exists('contenly_localized_url') ? contenly_localized_url('/my-courses/') : home_url('/my-courses/');
$gear_url = function_exists('contenly_localized_url') ? contenly_localized_url('/my-gear/') : home_url('/my-gear/');

// Crew WhatsApp
$wa_phone_raw = '';
if (function_exists('wdc_site_get')) {
    $wa_phone_raw = (string) wdc_site_get('phone_tel', '');
    if ($wa_phone_raw === '' || strpos($wa_phone_raw, '*') !== false) {
        $wa_phone_raw = (string) wdc_site_get('phone', '0821-2666-6111');
    }
} else {
    $wa_phone_raw = '0821-2666-6111';
}
$wa_digits = preg_replace('/\D+/', '', $wa_phone_raw);
if ($wa_digits !== '' && $wa_digits[0] === '0') {
    $wa_digits = '62' . substr($wa_digits, 1);
}
if (strlen($wa_digits) < 10) {
    $wa_digits = '622127939068';
}
$wa_name = trim((string) ($user->display_name ?: $user->user_login));
$wa_text = contenly_tr(
    'Halo crew Whale Dive Centre, saya ' . $wa_name . ' (member). Mau update / tanya soal profil diver saya.',
    'Hi Whale Dive Centre crew, I am ' . $wa_name . ' (member). I want to update / ask about my diver profile.'
);
$wa_url = 'https://wa.me/' . $wa_digits . '?text=' . rawurlencode($wa_text);

$gear_summary_bits = array_filter([
    $gear_height !== '' ? contenly_tr('Tinggi', 'Height') . ' ' . $gear_height : '',
    $gear_weight !== '' ? contenly_tr('Berat', 'Weight') . ' ' . $gear_weight : '',
    $gear_shoe !== '' ? contenly_tr('Sepatu', 'Shoe') . ' ' . $gear_shoe : '',
    $gear_wetsuit !== '' ? 'Wetsuit ' . $gear_wetsuit : '',
]);
$gear_summary = $gear_summary_bits ? implode(' · ', $gear_summary_bits) : contenly_tr('Belum diisi', 'Not set yet');
?>
<style>
.wdc-set-grid{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(260px,.75fr);gap:18px;align-items:start}
.wdc-set-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:18px;box-shadow:0 8px 24px rgba(15,23,42,.04)}
.wdc-set-form{display:grid;gap:12px}
.wdc-set-form label{display:grid;gap:5px;font-size:12px;font-weight:700;color:#475569}
.wdc-set-form input,
.wdc-set-form textarea{
  width:100%;border:1px solid #dbe4ea;border-radius:10px;padding:9px 10px;background:#fff;color:#0f172a;
  font-size:13px;font-weight:500;line-height:1.35;box-sizing:border-box;min-height:38px
}
.wdc-set-form input:disabled{background:#f8fafc;color:#64748b}
.wdc-set-form textarea{min-height:72px;resize:vertical}
.wdc-set-row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.wdc-set-help{font-size:12px;color:#64748b;line-height:1.45;margin:0}
.wdc-set-level{
  display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;
  padding:12px 14px;border:1px solid #dbeafe;border-radius:12px;background:#f8fbff
}
.wdc-set-level strong{display:block;font-size:15px;font-weight:800;color:#0f172a;margin-top:2px}
.wdc-set-level span{font-size:11px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#004A98}
.wdc-set-level p{margin:4px 0 0;font-size:12px;color:#64748b}
.wdc-set-side .kicker{font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#004A98;margin-bottom:6px}
.wdc-set-side h2{margin:0 0 10px;font-size:18px;font-weight:800;color:#0f172a}
.wdc-set-meta{display:grid;gap:8px;margin:0 0 14px}
.wdc-set-meta div{display:grid;gap:2px;padding:10px 12px;border-radius:10px;background:#f8fafc;border:1px solid #eef2f6}
.wdc-set-meta b{font-size:10px;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8}
.wdc-set-meta span{font-size:13px;font-weight:600;color:#0f172a;line-height:1.35}
.wdc-set-links{display:grid;gap:8px}
.wdc-set-links a{
  display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border-radius:999px;
  text-decoration:none;font-size:13px;font-weight:800
}
.wdc-set-links a.primary{background:#004A98;color:#fff}
.wdc-set-links a.soft{background:#fff;color:#004A98;border:1px solid #ccecf5}
.wdc-set-links a.wa{background:#16a34a;color:#fff}
.wdc-set-submit{
  border:0;border-radius:999px;background:#004A98;color:#fff;padding:10px 16px;
  font-weight:800;font-size:13px;cursor:pointer;width:fit-content
}
@media(max-width:900px){
  .wdc-set-grid{grid-template-columns:1fr}
  .wdc-set-row{grid-template-columns:1fr}
}
@media(max-width:760px){
  .wdc-set-form input,.wdc-set-form textarea{font-size:16px;min-height:42px}
}
</style>

<div class="wdc-page-head">
    <h1><?php echo esc_html(contenly_tr('Pengaturan Akun', 'Account Settings')); ?></h1>
    <p class="wdc-page-sub"><?php echo esc_html(contenly_tr('Kelola kontak, level diver, dan catatan ukuran gear biar crew lebih mudah bantu kamu.', 'Manage contact details, diver level, and gear sizing notes so crew can help you faster.')); ?></p>
</div>

<?php if ($notice) : ?>
<div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<div class="wdc-set-grid">
    <section class="wdc-set-card">
        <h2 class="wdc-section-title" style="margin:0 0 14px;"><?php echo esc_html(contenly_tr('Detail Profil', 'Profile Details')); ?></h2>
        <form method="post" class="wdc-set-form">
            <?php wp_nonce_field('wdc_account_settings', 'wdc_settings_nonce'); ?>

            <div class="wdc-set-row">
                <label><?php echo esc_html(contenly_tr('Nama tampilan', 'Display name')); ?>
                    <input name="display_name" value="<?php echo esc_attr($user->display_name); ?>" required>
                </label>
                <label><?php echo esc_html(contenly_tr('Email', 'Email')); ?>
                    <input value="<?php echo esc_attr($user->user_email); ?>" disabled>
                </label>
            </div>

            <div class="wdc-set-row">
                <label><?php echo esc_html(contenly_tr('Telepon / WhatsApp', 'Phone / WhatsApp')); ?>
                    <input name="phone" value="<?php echo esc_attr($phone); ?>" placeholder="+62...">
                </label>
                <label><?php echo esc_html(contenly_tr('Kontak darurat', 'Emergency contact')); ?>
                    <input name="emergency_contact" value="<?php echo esc_attr($emergency_contact); ?>" placeholder="<?php echo esc_attr(contenly_tr('Nama + nomor telepon', 'Name + phone')); ?>">
                </label>
            </div>

            <div>
                <div style="font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;"><?php echo esc_html(contenly_tr('Level diver', 'Diver level')); ?></div>
                <div class="wdc-set-level">
                    <div>
                        <span><?php echo esc_html(contenly_tr('Otomatis dari Kursus Saya', 'Auto from My Courses')); ?></span>
                        <strong><?php echo esc_html($auto_level['label']); ?></strong>
                        <p>
                            <?php if ((int) $auto_level['count'] > 0) : ?>
                                <?php echo esc_html(sprintf(contenly_tr('%d kursus tercatat', '%d courses logged'), (int) $auto_level['count'])); ?>
                                <?php if (!empty($auto_level['source_title'])) : ?>
                                    · <?php echo esc_html($auto_level['source_title']); ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php echo esc_html(contenly_tr('Belum ada kursus di Kursus Saya. Tambah di sana biar level terisi.', 'No courses in My Courses yet. Add one there to fill this level.')); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <a href="<?php echo esc_url($courses_url); ?>" style="display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border-radius:999px;background:#e8f8fc;color:#004A98;text-decoration:none;font-size:12px;font-weight:800;"><?php echo esc_html(contenly_tr('Kelola Kursus', 'Manage Courses')); ?></a>
                </div>
                <p class="wdc-set-help" style="margin-top:8px;"><?php echo esc_html(contenly_tr('Level diambil otomatis dari kursus tertinggi di Kursus Saya. Tidak perlu diisi manual di sini.', 'Level is taken automatically from the highest course in My Courses. No manual input here.')); ?></p>
            </div>

            <div>
                <div style="font-size:12px;font-weight:700;color:#475569;margin:4px 0 8px;"><?php echo esc_html(contenly_tr('Catatan ukuran gear', 'Gear sizing notes')); ?></div>
                <div class="wdc-set-row">
                    <label><?php echo esc_html(contenly_tr('Tinggi', 'Height')); ?>
                        <input name="gear_height" value="<?php echo esc_attr($gear_height); ?>" placeholder="<?php echo esc_attr(contenly_tr('Contoh: 170 cm', 'e.g. 170 cm')); ?>">
                    </label>
                    <label><?php echo esc_html(contenly_tr('Berat', 'Weight')); ?>
                        <input name="gear_weight" value="<?php echo esc_attr($gear_weight); ?>" placeholder="<?php echo esc_attr(contenly_tr('Contoh: 65 kg', 'e.g. 65 kg')); ?>">
                    </label>
                </div>
                <div class="wdc-set-row" style="margin-top:10px;">
                    <label><?php echo esc_html(contenly_tr('Ukuran sepatu', 'Shoe size')); ?>
                        <input name="gear_shoe" value="<?php echo esc_attr($gear_shoe); ?>" placeholder="<?php echo esc_attr(contenly_tr('Contoh: 42 EU', 'e.g. 42 EU')); ?>">
                    </label>
                    <label><?php echo esc_html(contenly_tr('Ukuran wetsuit', 'Wetsuit size')); ?>
                        <input name="gear_wetsuit" value="<?php echo esc_attr($gear_wetsuit); ?>" placeholder="<?php echo esc_attr(contenly_tr('Contoh: M / 3mm', 'e.g. M / 3mm')); ?>">
                    </label>
                </div>
                <label style="margin-top:10px;"><?php echo esc_html(contenly_tr('Catatan fit', 'Fit notes')); ?>
                    <textarea name="gear_fit_notes" rows="3" placeholder="<?php echo esc_attr(contenly_tr('Fit masker, preferensi BCD, alergi, dll...', 'Mask fit, BCD preference, allergies, etc...')); ?>"><?php echo esc_textarea($gear_fit_notes); ?></textarea>
                </label>
            </div>

            <button type="submit" class="wdc-set-submit"><?php echo esc_html(contenly_tr('Simpan Pengaturan', 'Save Settings')); ?></button>
        </form>
    </section>

    <aside class="wdc-set-card wdc-set-side">
        <div class="kicker"><?php echo esc_html(contenly_tr('Ringkasan diver', 'Diver summary')); ?></div>
        <h2><?php echo esc_html($auto_level['label']); ?></h2>
        <div class="wdc-set-meta">
            <div>
                <b><?php echo esc_html(contenly_tr('WhatsApp', 'WhatsApp')); ?></b>
                <span><?php echo esc_html($phone !== '' ? $phone : contenly_tr('Belum diisi', 'Not set yet')); ?></span>
            </div>
            <div>
                <b><?php echo esc_html(contenly_tr('Kontak darurat', 'Emergency contact')); ?></b>
                <span><?php echo esc_html($emergency_contact !== '' ? $emergency_contact : contenly_tr('Belum diisi', 'Not set yet')); ?></span>
            </div>
            <div>
                <b><?php echo esc_html(contenly_tr('Ukuran gear', 'Gear sizing')); ?></b>
                <span><?php echo esc_html($gear_summary); ?></span>
            </div>
            <?php if ($gear_fit_notes !== '') : ?>
            <div>
                <b><?php echo esc_html(contenly_tr('Catatan fit', 'Fit notes')); ?></b>
                <span><?php echo esc_html(wp_trim_words($gear_fit_notes, 18, '…')); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <div class="wdc-set-links">
            <a class="primary" href="<?php echo esc_url($courses_url); ?>"><?php echo esc_html(contenly_tr('Kursus Saya', 'My Courses')); ?></a>
            <a class="soft" href="<?php echo esc_url($gear_url); ?>"><?php echo esc_html(contenly_tr('Peralatan', 'Gear')); ?></a>
            <a class="wa" href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(contenly_tr('WhatsApp Crew', 'WhatsApp Crew')); ?></a>
        </div>
    </aside>
</div>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
