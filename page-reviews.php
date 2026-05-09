<?php
/**
 * Template Name: My Reviews
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$status_filter = isset($_GET['status']) ? sanitize_key($_GET['status']) : 'all';
$allowed_status = ['all', 'publish', 'pending'];
if (!in_array($status_filter, $allowed_status, true)) {
    $status_filter = 'all';
}

$post_status = $status_filter === 'all' ? ['publish', 'pending'] : [$status_filter];

$reviews = get_posts([
    'post_type' => 'destination',
    'posts_per_page' => -1,
    'post_status' => $post_status,
    'meta_query' => [
        'relation' => 'AND',
        [
            'key' => '_user_id',
            'value' => $user_id,
            'compare' => '=',
        ],
        [
            'key' => '_is_review',
            'value' => '1',
            'compare' => '=',
        ],
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);

$status_badge_map = [
    'publish' => ['Published', '#166534', '#dcfce7'],
    'pending' => ['Pending', '#92400e', '#fef3c7'],
    'draft' => ['Draft', '#355F72', '#DCE9E6'],
];

function contenly_review_filter_link($status) {
    return esc_url(add_query_arg('status', $status, contenly_localized_url('/reviews/')));
}
?>

<h1 class="page-title">⭐ <?php echo esc_html(contenly_tr('Review Saya', 'My Reviews')); ?></h1>
<p class="page-subtitle"><?php echo esc_html(contenly_tr('Review yang pernah kamu berikan untuk trip yang sudah completed', 'Reviews you have written for trips that are already completed.')); ?></p>

<div style="display:flex; gap:8px; flex-wrap:wrap; margin:0 0 18px;">
    <?php foreach (['all' => contenly_tr('Semua', 'All'), 'publish' => 'Published', 'pending' => 'Pending'] as $key => $label) :
        $active = $status_filter === $key;
    ?>
    <a href="<?php echo contenly_review_filter_link($key); ?>" style="padding:8px 14px; border-radius:999px; text-decoration:none; font-weight:600; font-size:13px; border:1px solid <?php echo $active ? '#539294' : '#cbd5e1'; ?>; background:<?php echo $active ? '#539294' : '#fff'; ?>; color:<?php echo $active ? '#fff' : '#334155'; ?>;">
        <?php echo esc_html($label); ?>
    </a>
    <?php endforeach; ?>
</div>

<?php if (empty($reviews)) : ?>
<div style="text-align: center; padding: 56px 20px; background: #f8fafc; border-radius: 16px; border:1px solid #e2e8f0;">
    <div style="font-size: 56px; margin-bottom: 12px;">⭐</div>
    <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;"><?php echo esc_html(contenly_tr('Belum ada review', 'No reviews yet')); ?></h3>
    <p style="color: #64748b; margin-bottom: 20px;"><?php echo esc_html(contenly_tr('Selesaikan trip dulu, lalu tulis review dari halaman My Travels (Completed).', 'Complete a trip first, then write a review from the My Travels page (Completed).')); ?></p>
    <a href="<?php echo esc_url(add_query_arg('tab', 'completed', contenly_localized_url('/my-travels/'))); ?>" style="display:inline-block; padding:12px 20px; background:linear-gradient(135deg,#539294,#539294); color:#fff; text-decoration:none; border-radius:10px; font-weight:600;"><?php echo esc_html(contenly_tr('Buka My Travels', 'Open My Travels')); ?></a>
</div>
<?php else : ?>
<div style="display:grid; gap:14px;">
    <?php foreach ($reviews as $review) :
        $rating = absint(get_post_meta($review->ID, '_rating', true));
        $tour_id = absint(get_post_meta($review->ID, '_review_tour_id', true));
        $booking_id = absint(get_post_meta($review->ID, '_review_booking_id', true));
        $visit_date = get_post_meta($review->ID, '_visit_date', true);
        $tour_title = $tour_id ? get_the_title($tour_id) : 'Tour';
        $tour_link = $tour_id ? get_permalink($tour_id) : '#';
        $when = $visit_date ? date_i18n('d M Y', strtotime($visit_date)) : get_the_date('d M Y', $review);
        $status_key = $review->post_status;
        $badge = $status_badge_map[$status_key] ?? [ucfirst($status_key), '#334155', '#e2e8f0'];
    ?>
    <article style="background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:16px;">
        <div style="display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; margin-bottom:8px; align-items:flex-start;">
            <div>
                <h3 style="margin:0 0 6px; font-size:18px; color:#0f172a;"><?php echo esc_html($review->post_title); ?></h3>
                <div style="font-size:13px; color:#64748b;">
                    <?php echo esc_html(contenly_tr('Tour', 'Tour')); ?>: <a href="<?php echo esc_url($tour_link); ?>" style="color:#539294; text-decoration:none;"><?php echo esc_html($tour_title); ?></a>
                    • <?php echo esc_html(contenly_tr('Kunjungan', 'Visit')); ?>: <?php echo esc_html($when); ?>
                    <?php if ($booking_id) : ?> • <?php echo esc_html(contenly_tr('Booking', 'Booking')); ?> #<?php echo esc_html($booking_id); ?><?php endif; ?>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="padding:6px 10px; background:<?php echo esc_attr($badge[2]); ?>; border:1px solid <?php echo esc_attr($badge[2]); ?>; color:<?php echo esc_attr($badge[0] === 'Pending' ? '#92400e' : '#166534'); ?>; border-radius:999px; font-size:12px; font-weight:700; white-space:nowrap;">
                    <?php echo esc_html($badge[0]); ?>
                </span>
                <span style="padding:6px 10px; background:#fffbeb; border:1px solid #fde68a; border-radius:999px; font-size:14px; white-space:nowrap;">
                    <?php echo str_repeat('⭐', max(1, min(5, $rating))); ?>
                </span>
            </div>
        </div>

        <p style="margin:0 0 12px; color:#334155; line-height:1.75;"><?php echo nl2br(esc_html($review->post_content)); ?></p>

        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <?php if ($booking_id) : ?>
            <a href="<?php echo esc_url(add_query_arg(['tab' => 'completed', 'edit_review_booking' => $booking_id], contenly_localized_url('/my-travels/'))); ?>" style="padding:8px 12px; border-radius:8px; background:#dcfce7; color:#166534; text-decoration:none; font-size:13px; font-weight:600;">✏️ <?php echo esc_html(contenly_tr('Edit', 'Edit')); ?></a>
            <?php endif; ?>
            <button class="js-delete-review" data-review-id="<?php echo esc_attr($review->ID); ?>" style="padding:8px 12px; border:none; border-radius:8px; background:#fee2e2; color:#b91c1c; font-size:13px; font-weight:600; cursor:pointer;">🗑 <?php echo esc_html(contenly_tr('Hapus', 'Delete')); ?></button>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
document.querySelectorAll('.js-delete-review').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const reviewId = this.dataset.reviewId;
        if (!reviewId) return;
        if (!confirm(<?php echo wp_json_encode(contenly_tr('Hapus review ini? Tindakan ini tidak bisa dibatalkan.', 'Delete this review? This action cannot be undone.')); ?>)) return;

        const formData = new FormData();
        formData.append('action', 'contenly_delete_review');
        formData.append('review_id', reviewId);
        formData.append('nonce', '<?php echo wp_create_nonce("tmpb_booking_nonce"); ?>');

        fetch(<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.data?.message || <?php echo wp_json_encode(contenly_tr('Review berhasil dihapus', 'Review deleted successfully')); ?>);
                location.reload();
                return;
            }
            alert(data.data?.message || <?php echo wp_json_encode(contenly_tr('Gagal menghapus review', 'Failed to delete review')); ?>);
        })
        .catch(() => {
            alert(<?php echo wp_json_encode(contenly_tr('Terjadi error saat menghapus review.', 'An error occurred while deleting the review.')); ?>);
        });
    });
});
</script>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
