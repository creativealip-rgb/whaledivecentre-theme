<?php
/**
 * Template Name: Informasi
 * Compact list of member info (Giveaway, Events, Trips, Updates)
 */
require_once get_template_directory() . '/dashboard-header.php';

$info_types = get_terms(['taxonomy' => 'info_type', 'hide_empty' => false]);
$active_filter = sanitize_text_field($_GET['type'] ?? '');
$color_map = [
    '1st Giveaway' => ['#ecfdf5', '#166534'],
    'Event' => ['#eff6ff', '#1e40af'],
    'Trip' => ['#fff7ed', '#9a3412'],
    'Update NAUI/WDC/TDI/DAN' => ['#f5f3ff', '#6d28d9'],
];
?>
<div style="margin-bottom:18px;">
    <h1 style="font-size:26px;font-weight:800;color:#0f172a;margin:0 0 6px;"><?php echo contenly_tr('Informasi', 'Information'); ?></h1>
    <p style="font-size:14px;color:#64748b;margin:0;"><?php echo contenly_tr('Giveaway, event, trip, dan update terbaru dari Whale Dive Centre.', 'Giveaways, events, trips, and latest updates from Whale Dive Centre.'); ?></p>
</div>

<?php if (!is_wp_error($info_types) && $info_types) : ?>
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
    <a href="<?php echo esc_url(contenly_localized_url('/informasi/')); ?>" style="display:inline-flex;padding:8px 14px;border-radius:999px;font-size:13px;font-weight:700;text-decoration:none;<?php echo !$active_filter ? 'background:#004A98;color:#fff;' : 'background:#fff;color:#004A98;border:1px solid #dbe4ea;'; ?>"><?php echo contenly_tr('Semua', 'All'); ?></a>
    <?php foreach ($info_types as $type) : ?>
    <a href="<?php echo esc_url(add_query_arg('type', $type->slug, contenly_localized_url('/informasi/'))); ?>" style="display:inline-flex;padding:8px 14px;border-radius:999px;font-size:13px;font-weight:700;text-decoration:none;<?php echo $active_filter === $type->slug ? 'background:#004A98;color:#fff;' : 'background:#fff;color:#004A98;border:1px solid #dbe4ea;'; ?>"><?php echo esc_html($type->name); ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php
$args = [
    'post_type' => 'wdc_info',
    'posts_per_page' => 30,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
];
if ($active_filter) {
    $args['tax_query'] = [['taxonomy' => 'info_type', 'field' => 'slug', 'terms' => $active_filter]];
}
$infos = new WP_Query($args);
?>

<?php if ($infos->have_posts()) : ?>
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.04);">
    <?php $i = 0; while ($infos->have_posts()) : $infos->the_post();
        $types = wp_get_post_terms(get_the_ID(), 'info_type', ['fields' => 'names']);
        $type_name = !is_wp_error($types) && $types ? $types[0] : 'Info';
        $colors = $color_map[$type_name] ?? ['#f8fafc', '#475569'];
        $excerpt = get_the_excerpt();
        if ($excerpt === '') {
            $excerpt = wp_trim_words(wp_strip_all_tags(get_the_content()), 18, '…');
        } else {
            $excerpt = wp_trim_words(wp_strip_all_tags($excerpt), 18, '…');
        }
        $border = $i > 0 ? 'border-top:1px solid #eef2f6;' : '';
        $i++;
    ?>
    <a href="<?php the_permalink(); ?>" style="display:flex;gap:12px;align-items:flex-start;padding:14px 16px;text-decoration:none;color:inherit;<?php echo $border; ?>">
        <div style="flex:1;min-width:0;">
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:4px;">
                <span style="display:inline-block;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:<?php echo esc_attr($colors[1]); ?>;background:<?php echo esc_attr($colors[0]); ?>;border-radius:999px;padding:3px 8px;"><?php echo esc_html($type_name); ?></span>
                <span style="font-size:12px;color:#94a3b8;"><?php echo esc_html(get_the_date()); ?></span>
            </div>
            <div style="font-size:15px;font-weight:800;color:#0f172a;line-height:1.35;margin-bottom:4px;"><?php echo esc_html(get_the_title()); ?></div>
            <?php if ($excerpt) : ?>
            <div style="font-size:13px;color:#64748b;line-height:1.5;"><?php echo esc_html($excerpt); ?></div>
            <?php endif; ?>
        </div>
        <span style="flex-shrink:0;font-size:13px;font-weight:800;color:#004A98;padding-top:2px;"><?php echo contenly_tr('Baca', 'Read'); ?> →</span>
    </a>
    <?php endwhile; wp_reset_postdata(); ?>
</div>
<?php else : ?>
<div style="text-align:center;padding:40px 18px;background:#fff;border:1px solid #e2e8f0;border-radius:16px;">
    <h3 style="font-size:16px;color:#0f172a;margin:0 0 6px;"><?php echo contenly_tr('Belum ada informasi', 'No information yet'); ?></h3>
    <p style="color:#64748b;margin:0;font-size:14px;"><?php echo contenly_tr('Cek kembali nanti untuk update terbaru dari crew.', 'Check back later for the latest updates from the crew.'); ?></p>
</div>
<?php endif; ?>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
