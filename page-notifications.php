<?php
/**
 * Template Name: Notifications
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$email_notif = get_user_meta($user_id, '_notif_email', true);
$wa_notif = get_user_meta($user_id, '_notif_whatsapp', true);
$promo_notif = get_user_meta($user_id, '_notif_promo', true);

$email_notif = ($email_notif === '') ? '1' : $email_notif;
$wa_notif = ($wa_notif === '') ? '1' : $wa_notif;
$promo_notif = ($promo_notif === '') ? '0' : $promo_notif;
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



<div style="margin-bottom:24px;">
  <h1 class="page-title">🔔 <?php echo esc_html(contenly_tr('Notifikasi', 'Notifications')); ?></h1>
  <p class="page-subtitle"><?php echo esc_html(contenly_tr('Atur notifikasi penting supaya kamu tidak ketinggalan update perjalanan.', 'Manage important notifications so you never miss a trip update.')); ?></p>
</div>

<div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:16px;">
  <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:12px;"><?php echo esc_html(contenly_tr('Preferensi notifikasi', 'Notification preferences')); ?></h3>
  <form id="notif-form" style="display:grid;gap:10px;max-width:640px;">
    <label style="display:flex;align-items:center;gap:10px;color:#334155;">
      <input type="checkbox" name="notif_email" value="1" <?php checked($email_notif, '1'); ?>>
      <?php echo esc_html(contenly_tr('Email: status booking, invoice, itinerary', 'Email: booking status, invoice, itinerary')); ?>
    </label>
    <label style="display:flex;align-items:center;gap:10px;color:#334155;">
      <input type="checkbox" name="notif_whatsapp" value="1" <?php checked($wa_notif, '1'); ?>>
      <?php echo esc_html(contenly_tr('Telepon / follow-up admin: reminder penting dan perubahan jadwal', 'Phone / admin follow-up: important reminders and schedule changes')); ?>
    </label>
    <label style="display:flex;align-items:center;gap:10px;color:#334155;">
      <input type="checkbox" name="notif_promo" value="1" <?php checked($promo_notif, '1'); ?>>
      <?php echo esc_html(contenly_tr('Promo: diskon, flash sale, seasonal campaign', 'Promos: discounts, flash sales, seasonal campaigns')); ?>
    </label>
    <div style="display:flex;gap:10px;align-items:center;">
      <button type="submit" id="notif-save-btn" class="member-btn-primary">💾 <?php echo esc_html(contenly_tr('Simpan Preferensi', 'Save Preferences')); ?></button>
      <span id="notif-msg" style="font-size:13px;color:#64748b;"></span>
    </div>
  </form>
</div>

<div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;">
  <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:10px;"><?php echo esc_html(contenly_tr('Aktivitas terbaru', 'Recent activity')); ?></h3>
  <div style="display:grid;gap:10px;">
    <div style="padding:12px;border:1px solid #f1f5f9;border-radius:10px;background:#f8fafc;">
      <div style="font-weight:600;color:#0f172a;">Booking update</div>
      <div style="font-size:13px;color:#64748b;">Status perjalanan kamu akan muncul di sini.</div>
    </div>
    <div style="padding:12px;border:1px solid #f1f5f9;border-radius:10px;background:#f8fafc;">
      <div style="font-weight:600;color:#0f172a;">Pembayaran</div>
      <div style="font-size:13px;color:#64748b;">Notifikasi invoice / verifikasi payment akan tampil di sini.</div>
    </div>
    <div style="padding:12px;border:1px solid #f1f5f9;border-radius:10px;background:#f8fafc;">
      <div style="font-weight:600;color:#0f172a;">Promo & rewards</div>
      <div style="font-size:13px;color:#64748b;">Promo member dan bonus poin akan tampil otomatis.</div>
    </div>
  </div>
</div>

<script>
jQuery(function($){
  $('#notif-form').on('submit', function(e){
    e.preventDefault();
    const $btn = $('#notif-save-btn');
    const $msg = $('#notif-msg');
    $btn.prop('disabled', true).text(<?php echo wp_json_encode(contenly_tr('⏳ Menyimpan...', '⏳ Saving...')); ?>);

    const fd = new FormData();
    fd.append('action', 'contenly_update_notifications');
    fd.append('nonce', window.contenlyBooking ? window.contenlyBooking.nonce : '');
    fd.append('notif_email', $('input[name=notif_email]').is(':checked') ? '1' : '0');
    fd.append('notif_whatsapp', $('input[name=notif_whatsapp]').is(':checked') ? '1' : '0');
    fd.append('notif_promo', $('input[name=notif_promo]').is(':checked') ? '1' : '0');

    $.ajax({
      url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      success: function(res){
        if(res && res.success){
          $msg.css('color', '#166534').text(<?php echo wp_json_encode(contenly_tr('✅ Tersimpan', '✅ Saved')); ?>);
          showToast(<?php echo wp_json_encode(contenly_tr('✅ Preferensi notifikasi berhasil disimpan', '✅ Notification preferences saved')); ?>, 'success');
        } else {
          $msg.css('color', '#b91c1c').text(<?php echo wp_json_encode(contenly_tr('❌ Gagal simpan', '❌ Save failed')); ?>);
          showToast(<?php echo wp_json_encode(contenly_tr('❌ Gagal menyimpan preferensi notifikasi', '❌ Failed to save notification preferences')); ?>, 'error');
        }
      },
      error: function(){
        $msg.css('color', '#b91c1c').text(<?php echo wp_json_encode(contenly_tr('❌ Error server', '❌ Server error')); ?>);
        showToast(<?php echo wp_json_encode(contenly_tr('❌ Terjadi error server saat menyimpan notifikasi', '❌ Server error while saving notifications')); ?>, 'error');
      },
      complete: function(){
        $btn.prop('disabled', false).text(<?php echo wp_json_encode(contenly_tr('💾 Simpan Preferensi', '💾 Save Preferences')); ?>);
      }
    });
  });
});
</script>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
