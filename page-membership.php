<?php
/**
 * Template Name: Membership
 * Dashboard version with sidebar
 */

require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$current_tier = $current_tier ?? 'silver';
$total_spend = isset($total_spend) ? (int) $total_spend : 0;
$plans = [
    'silver' => [
        'name' => 'Silver',
        'icon' => '🥈',
        'color' => '#94a3b8',
        'threshold' => 0,
        'threshold_label' => contenly_tr('Mulai otomatis saat daftar', 'Starts automatically when you register'),
        'benefits' => [
            contenly_tr('Browse semua tour', 'Browse all tours'),
            contenly_tr('Booking standar', 'Standard booking'),
            contenly_tr('Update via email', 'Updates via email'),
            contenly_tr('Simpan wishlist tanpa batas', 'Unlimited wishlist saving'),
        ],
    ],
    'gold' => [
        'name' => 'Gold',
        'icon' => '🥇',
        'color' => '#fbbf24',
        'threshold' => 5000000,
        'threshold_label' => contenly_tr('Total spending Rp 5.000.000', 'Total spending of Rp 5,000,000'),
        'benefits' => [
            contenly_tr('Semua benefit Silver', 'All Silver benefits'),
            contenly_tr('5% discount tour', '5% tour discount'),
            contenly_tr('Review admin lebih cepat', 'Faster admin review'),
            contenly_tr('Price alerts & early deals', 'Price alerts & early deals'),
            contenly_tr('No booking fee', 'No booking fee'),
        ],
    ],
    'platinum' => [
        'name' => 'Platinum',
        'icon' => '💎',
        'color' => '#355F72',
        'threshold' => 15000000,
        'threshold_label' => contenly_tr('Total spending Rp 15.000.000', 'Total spending of Rp 15,000,000'),
        'benefits' => [
            contenly_tr('Semua benefit Gold', 'All Gold benefits'),
            contenly_tr('10% discount tour', '10% tour discount'),
            contenly_tr('Priority handling lebih tinggi', 'Higher priority handling'),
            contenly_tr('Akses promo eksklusif', 'Access to exclusive promos'),
            contenly_tr('Birthday rewards', 'Birthday rewards'),
            contenly_tr('Concierge assistance', 'Concierge assistance'),
        ],
    ],
];
$next_tier = $current_tier === 'silver' ? 'gold' : ($current_tier === 'gold' ? 'platinum' : null);
$next_target_spend = $next_tier ? $plans[$next_tier]['threshold'] : null;
$remaining_spend = $next_target_spend ? max(0, $next_target_spend - $total_spend) : 0;
?>

<!-- Page Header -->
<div style="margin-bottom: 32px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo esc_html(contenly_tr('👑 Tier Member', '👑 Member Tier')); ?></h1>
    <p style="font-size: 15px; color: #64748b;"><?php echo esc_html(contenly_tr('Tier membership sekarang naik otomatis dari total spending, bukan paket langganan.', 'Membership tiers now upgrade automatically based on total spending, not subscription packages.')); ?></p>
</div>

<!-- Current Tier Notice -->
<?php if (is_user_logged_in()) : ?>
<div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; border-radius: 12px; margin-bottom: 32px;">
    <p style="margin: 0; color: #166534; font-size: 14px;">
        <strong>✅ <?php echo esc_html(contenly_tr('Tier Saat Ini:', 'Current Tier:')); ?></strong> 
        <span style="color: <?php echo esc_attr($plans[$current_tier]['color']); ?>; font-weight: 700;">
            <?php echo esc_html($plans[$current_tier]['icon'] . ' ' . $plans[$current_tier]['name']); ?>
        </span>
        <?php if ($next_tier) : ?>
        <span style="color: #64748b;"> • </span>
        <span style="color: #64748b;"><?php echo esc_html(sprintf(contenly_tr('Spending saat ini Rp %1$s • butuh Rp %2$s lagi buat %3$s', 'Current spending is Rp %1$s • you need Rp %2$s more to reach %3$s'), number_format($total_spend, 0, ',', '.'), number_format($remaining_spend, 0, ',', '.'), $plans[$next_tier]['name'])); ?></span>
        <?php else : ?>
        <span style="color: #10b981; font-weight: 600;"> 👑 <?php echo esc_html(contenly_tr('Tier maksimal sudah tercapai!', 'Max tier reached!')); ?></span>
        <?php endif; ?>
    </p>
</div>
<?php endif; ?>

<!-- Tier Cards -->
<div class="membership-plans-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
    <?php foreach ($plans as $plan_id => $plan) : 
        $is_current = $plan_id === $current_tier;
    ?>
    <div class="membership-plan-card" style="background: white; border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: relative; <?php echo $is_current ? 'border: 3px solid #539294;' : ''; ?>">

        <?php if ($is_current) : ?>
        <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #10b981; color: white; padding: 6px 20px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
            <?php echo esc_html(contenly_tr('Tier Aktif', 'Current Tier')); ?>
        </div>
        <?php endif; ?>
        
        <!-- Plan Icon -->
        <div style="font-size: 48px; margin-bottom: 16px; text-align: center;"><?php echo $plan['icon']; ?></div>
        
        <!-- Plan Name -->
        <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; text-align: center; margin-bottom: 8px;">
            <?php echo $plan['name']; ?>
        </h3>
        
        <!-- Threshold -->
        <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 18px; font-weight: 800; color: #0f172a;"><?php echo $plan['threshold_label']; ?></span>
        </div>

        <!-- Benefits -->
        <ul style="list-style: none; padding: 0; margin: 0 0 32px 0;">
            <?php foreach ($plan['benefits'] as $benefit) : ?>
            <li style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #475569; display: flex; align-items: flex-start; gap: 8px;">
                <span style="color:#10b981; font-weight: 700; font-size: 16px;">✓</span>
                <span><?php echo esc_html($benefit); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>

        <div style="width: 100%; padding: 16px; background: <?php echo $is_current ? '#EEF5F4' : '#f8fafc'; ?>; color: <?php echo $is_current ? '#355F72' : '#64748b'; ?>; text-align: center; border-radius: 12px; font-weight: 700; font-size: 15px;">
            <?php echo esc_html($is_current ? contenly_tr('Tier aktif sekarang', 'Current active tier') : contenly_tr('Naik otomatis saat spending memenuhi syarat', 'Upgrades automatically once your spending qualifies')); ?>
        </div>

    </div>
    <?php endforeach; ?>
</div>

<!-- Auto-Upgrade Info -->
<div style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border-radius: 20px; padding: 32px; text-align: center;">
    <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
        <?php echo esc_html(contenly_tr('🎯 Tier naik otomatis dari spending', '🎯 Tiers upgrade automatically from spending')); ?>
    </h2>
    <p style="font-size: 15px; color: #475569; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto;">
        <?php echo esc_html(contenly_tr('Nggak ada paket langganan dan nggak ada tombol upgrade. Semakin besar total spending dari booking yang paid/confirmed/completed, tier lu otomatis naik.', 'There is no subscription package and no upgrade button. The higher your total spending from paid, confirmed, or completed bookings, the higher your tier will automatically become.')); ?>
    </p>
    
    <div class="membership-upgrade-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; max-width: 800px; margin: 0 auto;">
        <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="font-size: 36px; margin-bottom: 8px;">🥇</div>
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">Gold</div>
            <div style="font-size: 14px; color: #64748b;">Rp 5.000.000 total spending</div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="font-size: 36px; margin-bottom: 8px;">💎</div>
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">Platinum</div>
            <div style="font-size: 14px; color: #64748b;">Rp 15.000.000 total spending</div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px){
  .membership-plans-grid{grid-template-columns:1fr !important; gap:16px !important;}
  .membership-plan-card{padding:20px !important;}
  .membership-upgrade-grid{grid-template-columns:1fr !important; gap:12px !important;}
}
</style>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
