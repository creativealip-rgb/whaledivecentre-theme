<?php
/**
 * Template Name: Account Settings
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$user = wp_get_current_user();
$notice = '';
$notice_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wdc_settings_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_settings_nonce'])), 'wdc_account_settings')) {
    $display_name = sanitize_text_field(wp_unslash($_POST['display_name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $certification = sanitize_text_field(wp_unslash($_POST['certification'] ?? ''));
    $gear_sizes = sanitize_textarea_field(wp_unslash($_POST['gear_sizes'] ?? ''));
    $emergency_contact = sanitize_text_field(wp_unslash($_POST['emergency_contact'] ?? ''));

    if ($display_name) {
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $display_name,
        ]);
        update_user_meta($user_id, '_wdc_phone', $phone);
        update_user_meta($user_id, '_wdc_certification', $certification);
        update_user_meta($user_id, '_wdc_gear_sizes', $gear_sizes);
        update_user_meta($user_id, '_wdc_emergency_contact', $emergency_contact);
        $user = wp_get_current_user();
        $notice = 'Account settings saved.';
    } else {
        $notice = 'Display name is required.';
        $notice_type = 'error';
    }
}

$phone = get_user_meta($user_id, '_wdc_phone', true);
$certification = get_user_meta($user_id, '_wdc_certification', true);
$gear_sizes = get_user_meta($user_id, '_wdc_gear_sizes', true);
$emergency_contact = get_user_meta($user_id, '_wdc_emergency_contact', true);
?>
<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:8px;">Account Settings</h1>
    <p style="font-size:15px;color:#64748b;">Keep your Whale Dive Centre profile ready for course registration and gear purchase support.</p>
</div>

<?php if ($notice) : ?>
<div style="margin-bottom:18px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:minmax(0,1.3fr) minmax(260px,.7fr);gap:20px;align-items:start;">
    <section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 18px;">Profile Details</h2>
        <form method="post" style="display:grid;gap:14px;">
            <?php wp_nonce_field('wdc_account_settings', 'wdc_settings_nonce'); ?>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;">Display name
                <input name="display_name" value="<?php echo esc_attr($user->display_name); ?>" required style="width:100%;border:1px solid #dbe4ea;border-radius:12px;padding:12px 14px;background:#fff;color:#0f172a;">
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;">Email
                <input value="<?php echo esc_attr($user->user_email); ?>" disabled style="width:100%;border:1px solid #dbe4ea;border-radius:12px;padding:12px 14px;background:#f8fafc;color:#0f172a;">
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;">Phone / WhatsApp
                <input name="phone" value="<?php echo esc_attr($phone); ?>" placeholder="+62..." style="width:100%;border:1px solid #dbe4ea;border-radius:12px;padding:12px 14px;background:#fff;color:#0f172a;">
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;">Current certification
                <select name="certification" style="width:100%;border:1px solid #dbe4ea;border-radius:12px;padding:12px 14px;background:#fff;color:#0f172a;">
                    <?php foreach (['New Diver', 'Open Water', 'Advanced Open Water', 'Rescue Diver', 'Divemaster', 'Instructor'] as $level) : ?>
                    <option value="<?php echo esc_attr($level); ?>" <?php selected($certification ?: 'New Diver', $level); ?>><?php echo esc_html($level); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;">Gear sizing notes
                <textarea name="gear_sizes" rows="4" placeholder="Height, weight, shoe size, wetsuit size, mask fit notes..." style="width:100%;border:1px solid #dbe4ea;border-radius:12px;padding:12px 14px;background:#fff;color:#0f172a;resize:vertical;"><?php echo esc_textarea($gear_sizes); ?></textarea>
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;">Emergency contact
                <input name="emergency_contact" value="<?php echo esc_attr($emergency_contact); ?>" placeholder="Name + phone" style="width:100%;border:1px solid #dbe4ea;border-radius:12px;padding:12px 14px;background:#fff;color:#0f172a;">
            </label>
            <button type="submit" style="border:0;border-radius:999px;background:#4cc8ed;color:#06384d;padding:12px 16px;font-weight:950;cursor:pointer;">Save Settings</button>
        </form>
    </section>

    <aside style="background:linear-gradient(135deg,#f8fdff,#eef9fc);border:1px solid #ccecf5;border-radius:20px;padding:24px;">
        <div style="font-size:12px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;margin-bottom:8px;">Diver profile</div>
        <h2 style="font-size:22px;color:#06384d;margin:0 0 8px;"><?php echo esc_html($tier_info['name']); ?></h2>
        <p style="color:#64748b;line-height:1.6;margin:0 0 18px;">These details help the crew recommend the right course path, gear fit, and preparation notes.</p>
        <a href="/contact/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:950;">Contact Whale Dive</a>
    </aside>
</div>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
