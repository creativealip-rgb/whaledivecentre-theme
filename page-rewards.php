<?php
/**
 * Template Name: Rewards & Points
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$points = 0; // keep in sync with computed reward events (single source of truth on this page)
$tier_data = contenly_get_user_tier_data($user_id);
$tier = $tier_data['tier'];
$tierLabel = $tier_data['info']['name'];

$reward_events = [];
$booking_posts = get_posts([
  'post_type' => 'tour_booking',
  'posts_per_page' => -1,
  'post_status' => 'any',
  'meta_query' => [[ 'key' => '_user_id', 'value' => $user_id ]],
  'orderby' => 'date',
  'order' => 'DESC',
]);
foreach ($booking_posts as $b) {
  $st = get_post_meta($b->ID, '_booking_status', true);
  if (in_array($st, ['paid','confirmed','completed'], true)) {
    $reward_events[] = ['date'=>$b->post_date, 'label'=>contenly_tr('Booking #', 'Booking #').$b->ID.' '.contenly_tr('terkonfirmasi', 'confirmed'), 'points'=>100];
  }
}
$review_posts = get_posts([
  'post_type' => 'destination',
  'posts_per_page' => -1,
  'post_status' => ['publish', 'pending'],
  'meta_query' => [
    'relation' => 'AND',
    ['key' => '_user_id', 'value' => $user_id],
    ['key' => '_is_review', 'value' => '1'],
  ],
  'orderby' => 'date',
  'order' => 'DESC',
]);
foreach ($review_posts as $r) {
  $reward_events[] = ['date'=>$r->post_date, 'label'=>'Review: '.wp_trim_words($r->post_title, 5, ''), 'points'=>25];
}
$points = array_sum(array_map(function($ev) { return (int) ($ev['points'] ?? 0); }, $reward_events));
usort($reward_events, function($a,$b){ return strtotime($b['date']) <=> strtotime($a['date']); });
$reward_events = array_slice($reward_events, 0, 8);
?>

<style>
.member-btn-primary{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;background:#539294;color:#fff!important;text-decoration:none;border:none;font-weight:700;cursor:pointer;transition:.2s}
.member-btn-primary:hover{background:#355F72}
.member-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px}
.member-grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.member-grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
@media (max-width:768px){.member-grid-3,.member-grid-2{grid-template-columns:1fr !important}.member-card{padding:14px !important}}
</style>

<div style="margin-bottom:24px;">
    <h1 class="page-title">🎁 <?php echo esc_html(contenly_tr('Rewards & Poin', 'Rewards & Points')); ?></h1>
    <p class="page-subtitle"><?php echo esc_html(contenly_tr('Pantau poin, benefit member, dan cara tukar reward.', 'Track your points, member benefits, and how to redeem rewards.')); ?></p>
</div>

<div class="member-grid-3" style="margin-bottom:22px;">
    <div class="member-card" style="padding:14px;"><div style="font-size:12px;color:#64748b;"><?php echo esc_html(contenly_tr('Poin Saat Ini', 'Current Points')); ?></div><div style="font-size:24px;font-weight:800;color:#7c3aed;"><?php echo number_format($points); ?></div></div>
    <div class="member-card" style="padding:14px;"><div style="font-size:12px;color:#64748b;"><?php echo esc_html(contenly_tr('Level Membership', 'Membership Level')); ?></div><div style="font-size:24px;font-weight:800;color:#0f172a;"><?php echo esc_html($tierLabel); ?></div></div>
    <div class="member-card" style="padding:14px;"><div style="font-size:12px;color:#64748b;"><?php echo esc_html(contenly_tr('Voucher Tersedia', 'Available Vouchers')); ?></div><div style="font-size:24px;font-weight:800;color:#0f172a;">0</div></div>
</div>

<div class="member-grid-2" style="gap:16px;">
    <div class="member-card">
        <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:10px;"><?php echo esc_html(contenly_tr('Cara dapat poin', 'How to earn points')); ?></h3>
        <ul style="margin:0;padding-left:18px;color:#334155;line-height:1.9;">
            <li><?php echo esc_html(contenly_tr('Booking terkonfirmasi (+100 poin / booking)', 'Confirmed booking (+100 points / booking)')); ?></li>
            <li><?php echo esc_html(contenly_tr('Review setelah trip (+25 poin)', 'Post-trip review (+25 points)')); ?></li>
            <li><?php echo esc_html(contenly_tr('Referral teman (+150 poin)', 'Friend referral (+150 points)')); ?></li>
            <li><?php echo esc_html(contenly_tr('Promo event musiman (bonus poin)', 'Seasonal event promo (bonus points)')); ?></li>
        </ul>
    </div>
    <div class="member-card">
        <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:10px;"><?php echo esc_html(contenly_tr('Tukar poin', 'Redeem points')); ?></h3>
        <p style="color:#64748b;margin-bottom:14px;"><?php echo esc_html(contenly_tr('Gunakan poin untuk potongan harga booking berikutnya.', 'Use your points to get discounts on your next booking.')); ?></p>
        <div style="display:grid;gap:10px;">
          <div style="padding:10px 12px;border:1px dashed #cbd5e1;border-radius:10px;color:#475569;"><?php echo esc_html(contenly_tr('500 poin → Voucher Rp50.000', '500 points → IDR 50,000 voucher')); ?></div>
          <div style="padding:10px 12px;border:1px dashed #cbd5e1;border-radius:10px;color:#475569;"><?php echo esc_html(contenly_tr('1.000 poin → Voucher Rp120.000', '1,000 points → IDR 120,000 voucher')); ?></div>
          <div style="padding:10px 12px;border:1px dashed #cbd5e1;border-radius:10px;color:#475569;"><?php echo esc_html(contenly_tr('2.000 poin → Voucher Rp300.000', '2,000 points → IDR 300,000 voucher')); ?></div>
        </div>
    </div>
</div>

<div class="member-card" style="margin-top:16px;">
  <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;"><?php echo esc_html(contenly_tr('Riwayat Poin', 'Points History')); ?></h3>
  <?php if (!empty($reward_events)) : ?>
    <div style="display:grid;gap:10px;">
      <?php foreach ($reward_events as $ev) : ?>
        <div style="display:flex;justify-content:space-between;gap:10px;padding:10px 12px;border:1px solid #e2e8f0;border-radius:10px;">
          <div>
            <div style="font-weight:600;color:#0f172a;"><?php echo esc_html($ev['label']); ?></div>
            <div style="font-size:12px;color:#64748b;"><?php echo date_i18n(get_option('date_format'), strtotime($ev['date'])); ?></div>
          </div>
          <div style="font-weight:800;color:#166534;">+<?php echo (int)$ev['points']; ?> <?php echo esc_html(contenly_tr('poin', 'pts')); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <p style="color:#64748b;margin:0;"><?php echo esc_html(contenly_tr('Belum ada riwayat poin untuk akun ini.', 'There is no points history for this account yet.')); ?></p>
  <?php endif; ?>
</div>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
