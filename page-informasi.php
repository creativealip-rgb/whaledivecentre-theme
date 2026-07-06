<?php
/**
 * Template Name: Informasi
 * Dashboard page for member info (Giveaway, Events, Trips, Updates)
 */
require_once get_template_directory() . '/dashboard-header.php';

// Get all info types
$info_types = get_terms(['taxonomy' => 'info_type', 'hide_empty' => false]);
$active_filter = sanitize_text_field($_GET['type'] ?? '');
?>
<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:8px;"><?php echo contenly_tr('Informasi', 'Information'); ?></h1>
    <p style="font-size:15px;color:#64748b;"><?php echo contenly_tr('Giveaway, event, trip, dan update terbaru dari Whale Dive Centre.', 'Giveaways, events, trips, and latest updates from Whale Dive Centre.'); ?></p>
</div>

<!-- Filter chips -->
<?php if (!is_wp_error($info_types) && $info_types) : ?>
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
    <a href="<?php echo esc_url(contenly_localized_url('/informasi/')); ?>" style="display:inline-flex;padding:10px 20px;border-radius:999px;font-size:14px;font-weight:700;text-decoration:none;<?php echo !$active_filter ? 'background:#06384d;color:#fff;' : 'background:#fff;color:#06384d;border:1px solid #dde3ea;'; ?>transition:all .2s;"><?php echo contenly_tr('Semua', 'All'); ?></a>
    <?php foreach ($info_types as $type) : ?>
    <a href="<?php echo esc_url(add_query_arg('type', $type->slug, contenly_localized_url('/informasi/'))); ?>" style="display:inline-flex;padding:10px 20px;border-radius:999px;font-size:14px;font-weight:700;text-decoration:none;<?php echo $active_filter === $type->slug ? 'background:#06384d;color:#fff;' : 'background:#fff;color:#06384d;border:1px solid #dde3ea;'; ?>transition:all .2s;"><?php echo esc_html($type->name); ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Info posts -->
<?php
$args = ['post_type' => 'wdc_info', 'posts_per_page' => 20, 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'DESC'];
if ($active_filter) {
    $args['tax_query'] = [['taxonomy' => 'info_type', 'field' => 'slug', 'terms' => $active_filter]];
}
$infos = new WP_Query($args);
?>

<?php if ($infos->have_posts()) : ?>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-bottom:28px;">
    <?php while ($infos->have_posts()) : $infos->the_post();
        $types = wp_get_post_terms(get_the_ID(), 'info_type', ['fields' => 'names']);
        $type_name = !is_wp_error($types) && $types ? $types[0] : 'Info';
        $color_map = [
            '1st Giveaway' => ['#ecfdf5', '#166534', '#dcfce7'],
            'Event' => ['#eff6ff', '#1e40af', '#dbeafe'],
            'Trip' => ['#fef7ee', '#9a3412', '#fed7aa'],
            'Update NAUI/WDC/TDI/DAN' => ['#f5f3ff', '#6d28d9', '#ede9fe'],
        ];
        $colors = $color_map[$type_name] ?? ['#f8fafc', '#475569', '#e2e8f0'];
    ?>
    <article style="background:#fff;border:1px solid <?php echo $colors[2]; ?>;border-radius:20px;overflow:hidden;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <?php if (has_post_thumbnail()) : ?>
        <div style="height:180px;overflow:hidden;">
            <?php the_post_thumbnail('medium_large', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
        </div>
        <?php endif; ?>
        <div style="padding:20px;">
            <span style="display:inline-block;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:<?php echo $colors[1]; ?>;background:<?php echo $colors[0]; ?>;border-radius:999px;padding:5px 10px;margin-bottom:12px;"><?php echo esc_html($type_name); ?></span>
            <h3 style="font-size:18px;font-weight:900;color:#0f172a;margin:0 0 8px;letter-spacing:.03em;"><?php echo esc_html(get_the_title()); ?></h3>
            <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0 0 14px;"><?php echo esc_html(get_the_excerpt()); ?></p>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:12px;color:#94a3b8;"><?php echo get_the_date(); ?></span>
                <a href="<?php the_permalink(); ?>" style="font-size:13px;font-weight:800;color:#0b617c;text-decoration:none;"><?php echo contenly_tr('Baca →', 'Read →'); ?></a>
            </div>
        </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
</div>
<?php else : ?>
<div style="text-align:center;padding:60px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:20px;">
    <div style="font-size:48px;margin-bottom:16px;">📢</div>
    <h3 style="font-size:20px;color:#0f172a;margin-bottom:8px;"><?php echo contenly_tr('Belum ada informasi', 'No information yet'); ?></h3>
    <p style="color:#64748b;"><?php echo contenly_tr('Cek kembali nanti untuk update terbaru dari crew.', 'Check back later for the latest updates from the crew.'); ?></p>
</div>
<?php endif; ?>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
