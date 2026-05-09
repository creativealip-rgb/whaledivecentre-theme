<?php
/**
 * Template Name: Settings
 * User profile and membership settings
 */

require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$user = wp_get_current_user();

// Get user meta
$phone = get_user_meta($user_id, 'phone_number', true);
$tier_data = contenly_get_user_tier_data($user_id);
$membership_tier = $tier_data['tier'];
$total_spend = (int) $tier_data['total_spend'];
$booking_count = get_user_meta($user_id, '_tmp_bookings_count', true) ?: 0;
$reward_points = get_user_meta($user_id, '_tmp_reward_points', true) ?: 0;

// Tier info
$tier_info = contenly_get_member_tier_map();
$current_tier = $tier_info[$membership_tier];
?>

<style>
.member-btn-primary{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;background:#539294;color:#fff!important;text-decoration:none;border:none;font-weight:700;cursor:pointer;transition:.2s}
.member-btn-primary:hover{background:#355F72}
.member-btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;background:#fff;color:#334155!important;text-decoration:none;border:1px solid #cbd5e1;font-weight:600;cursor:pointer;transition:.2s}
.member-btn-ghost:hover{background:#f8fafc}
.member-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px}
.member-muted{color:#64748b}
.member-grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.member-grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
@media (max-width:768px){.member-grid-3,.member-grid-2{grid-template-columns:1fr !important}.member-card{padding:14px !important}}
</style>



<!-- Page Header -->
<div style="margin-bottom: 32px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">⚙️ <?php echo esc_html(contenly_tr('Pengaturan', 'Settings')); ?></h1>
    <p style="font-size: 15px; color: #64748b;"><?php echo esc_html(contenly_tr('Kelola profil dan pengaturan membership kamu', 'Manage your profile and membership settings.')); ?></p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    
    <!-- Profile Settings -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 24px;">👤</span> <?php echo esc_html(contenly_tr('Pengaturan Profil', 'Profile Settings')); ?>
        </h2>
        
        <form id="profile-settings-form" style="display: grid; gap: 16px;">
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    <?php echo esc_html(contenly_tr('Nama Tampil', 'Display Name')); ?>
                </label>
                <input type="text" name="display_name" value="<?php echo esc_attr($user->display_name); ?>"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    Email Address
                </label>
                <input type="email" name="email" value="<?php echo esc_attr($user->user_email); ?>"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    <?php echo esc_html(contenly_tr('Nomor Telepon', 'Phone Number')); ?>
                </label>
                <input type="tel" name="phone" value="<?php echo esc_attr($phone); ?>"
                       placeholder="+62 xxx xxxx xxxx"
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
            </div>
            
            <div>
                <button type="submit" id="update-profile-btn"
                        class="member-btn-primary" style="width:100%;justify-content:center;font-size:16px;">
                    💾 <?php echo esc_html(contenly_tr('Update Profil', 'Update Profile')); ?>
                </button>
            </div>
        </form>
        
        <div id="profile-success" style="display: none; background: #f0fdf4; border: 2px solid #86efac; padding: 16px; border-radius: 12px; margin-top: 16px;">
            <p style="color: #166534; margin: 0; font-weight: 600;">✅ <?php echo esc_html(contenly_tr('Profil berhasil diupdate!', 'Profile updated successfully!')); ?></p>
        </div>
    </div>
    
    <!-- Membership Info -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 24px;">🎫</span> <?php echo esc_html(contenly_tr('Membership', 'Membership')); ?>
        </h2>
        
        <!-- Current Tier -->
        <div style="background: <?php echo $current_tier['color']; ?>10; border: 2px solid <?php echo $current_tier['color']; ?>; padding: 20px; border-radius: 12px; margin-bottom: 20px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 8px;"><?php echo $current_tier['icon']; ?></div>
            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                <?php echo esc_html($current_tier['name'] . ' ' . contenly_tr('Member', 'Member')); ?>
            </h3>
            <p style="color: #64748b; font-size: 14px; margin: 0;"><?php echo esc_html(contenly_tr('Tier dihitung otomatis dari total spending booking', 'Your tier is calculated automatically from total booking spending.')); ?></p>
        </div>
        
        <!-- Stats -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
            <div style="background: #f8fafc; padding: 16px; border-radius: 10px; text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: #539294; margin-bottom: 4px;"><?php echo $booking_count; ?></div>
                <div style="font-size: 12px; color: #64748b; text-transform: uppercase;"><?php echo esc_html(contenly_tr('Total Booking', 'Total Bookings')); ?></div>
            </div>
            <div style="background: #f8fafc; padding: 16px; border-radius: 10px; text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: #7c3aed; margin-bottom: 4px;"><?php echo $reward_points; ?></div>
                <div style="font-size: 12px; color: #64748b; text-transform: uppercase;"><?php echo esc_html(contenly_tr('Poin Reward', 'Reward Points')); ?></div>
            </div>
        </div>
        
        <!-- Progress to Next Tier -->
        <?php if ($membership_tier !== 'platinum') : 
            $next_tier = $membership_tier === 'silver' ? 'gold' : 'platinum';
            $target_spend = $membership_tier === 'silver' ? 5000000 : 15000000;
            $previous_spend = $membership_tier === 'silver' ? 0 : 5000000;
            $progress = min(100, (max(0, $total_spend - $previous_spend) / max(1, ($target_spend - $previous_spend))) * 100);
            $remaining_spend = max(0, $target_spend - $total_spend);
        ?>
        <div style="background: #f0f9ff; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
            <h4 style="font-size: 14px; font-weight: 600; color: #0f172a; margin-bottom: 12px;">
                <?php echo esc_html(sprintf(contenly_tr('🎯 Progress ke %s', '🎯 Progress to %s'), ucfirst($next_tier))); ?>
            </h4>
            <div style="background: #e2e8f0; border-radius: 9999px; height: 10px; overflow: hidden; margin-bottom: 8px;">
                <div style="width: <?php echo $progress; ?>%; background: linear-gradient(90deg, #539294, #539294); height: 100%; border-radius: 9999px;"></div>
            </div>
            <p style="font-size: 13px; color: #64748b; margin: 0;">
                <?php echo esc_html(sprintf(contenly_tr('Spend Rp %1$s / Rp %2$s • butuh Rp %3$s lagi', 'Spend Rp %1$s / Rp %2$s • need Rp %3$s more'), number_format($total_spend, 0, ',', '.'), number_format($target_spend, 0, ',', '.'), number_format($remaining_spend, 0, ',', '.'))); ?>
            </p>
        </div>
        <?php endif; ?>

        <div style="padding: 14px; background: #EEF5F4; color: #355F72; text-align: center; border-radius: 12px; font-weight: 600;">
            <?php echo esc_html(contenly_tr('Upgrade manual dihapus. Tier naik otomatis dari spending.', 'Manual upgrades have been removed. Your tier upgrades automatically from spending.')); ?>
        </div>
    </div>
</div>

<!-- Password Change -->
<div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 24px;">
    <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 24px;">🔐</span> <?php echo esc_html(contenly_tr('Ubah Kata Sandi', 'Change Password')); ?>
    </h2>
    
    <form id="password-form" style="display: grid; gap: 16px; max-width: 500px;">
        <div>
            <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                New Password
            </label>
            <input type="password" name="new_password" required minlength="6"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #0f172a; font-size: 14px;">
                Confirm Password
            </label>
            <input type="password" name="confirm_password" required minlength="6"
                   style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
        </div>
        
        <div>
            <button type="submit" id="update-password-btn"
                    class="member-btn-primary" style="background:#dc2626;">
                🔐 Change Password
            </button>
        </div>
    </form>
    
    <div id="password-success" style="display: none; background: #f0fdf4; border: 2px solid #86efac; padding: 16px; border-radius: 12px; margin-top: 16px;">
        <p style="color: #166534; margin: 0; font-weight: 600;">✅ Password updated successfully!</p>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Profile update
    $('#profile-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#update-profile-btn');
        var originalText = $btn.html();
        $btn.prop('disabled', true).html(<?php echo wp_json_encode(contenly_tr('⏳ Mengupdate...', '⏳ Updating...')); ?>);
        
        var formData = new FormData(this);
        formData.append('action', 'contenly_update_profile');
        formData.append('nonce', contenlyBooking ? contenlyBooking.nonce : '');
        
        $.ajax({
            url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast(<?php echo wp_json_encode(contenly_tr('✅ Profil berhasil diupdate', '✅ Profile updated successfully')); ?>, 'success');
                    $('#profile-success').fadeIn();
                    setTimeout(function() {
                        $('#profile-success').fadeOut();
                        $btn.prop('disabled', false).html(originalText);
                    }, 2000);
                } else {
                    showToast('❌ ' + (response.data.message || <?php echo wp_json_encode(contenly_tr('Gagal mengupdate profil', 'Failed to update profile')); ?>), 'error');
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                showToast(<?php echo wp_json_encode(contenly_tr('❌ Gagal mengupdate profil. Coba lagi ya.', '❌ Failed to update profile. Please try again.')); ?>, 'error');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Password change
    $('#password-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var newPassword = formData.get('new_password');
        var confirmPassword = formData.get('confirm_password');
        
        if (newPassword !== confirmPassword) {
            showToast(<?php echo wp_json_encode(contenly_tr('❌ Password tidak sama', '❌ Passwords do not match')); ?>, 'error');
            return;
        }
        
        var $btn = $('#update-password-btn');
        var originalText = $btn.html();
        $btn.prop('disabled', true).html(<?php echo wp_json_encode(contenly_tr('⏳ Mengupdate...', '⏳ Updating...')); ?>);
        
        formData.append('action', 'contenly_change_password');
        formData.append('nonce', contenlyBooking ? contenlyBooking.nonce : '');
        
        $.ajax({
            url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast(<?php echo wp_json_encode(contenly_tr('✅ Password berhasil diupdate', '✅ Password updated successfully')); ?>, 'success');
                    $('#password-success').fadeIn();
                    $('#password-form')[0].reset();
                    setTimeout(function() {
                        $('#password-success').fadeOut();
                        $btn.prop('disabled', false).html(originalText);
                    }, 2000);
                } else {
                    showToast('❌ ' + (response.data.message || <?php echo wp_json_encode(contenly_tr('Gagal mengubah password', 'Failed to change password')); ?>), 'error');
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                showToast(<?php echo wp_json_encode(contenly_tr('❌ Gagal mengubah password. Coba lagi ya.', '❌ Failed to change password. Please try again.')); ?>, 'error');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
