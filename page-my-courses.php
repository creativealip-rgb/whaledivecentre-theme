<?php
/**
 * Template Name: My Courses
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$notice = '';
$notice_type = 'success';
$course_requests = get_user_meta($user_id, '_wdc_course_requests', true);
$course_requests = is_array($course_requests) ? $course_requests : [];
$course_orders = get_user_meta($user_id, '_wdc_course_orders', true);
$course_orders = is_array($course_orders) ? $course_orders : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wdc_course_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_course_nonce'])), 'wdc_course_request')) {
    $selected_course = sanitize_text_field(wp_unslash($_POST['selected_course'] ?? ''));
    $preferred_date = sanitize_text_field(wp_unslash($_POST['preferred_date'] ?? ''));
    $experience = sanitize_text_field(wp_unslash($_POST['experience'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if ($selected_course) {
        array_unshift($course_requests, [
            'course' => $selected_course,
            'preferred_date' => $preferred_date,
            'experience' => $experience,
            'message' => $message,
            'status' => 'Requested',
            'created_at' => current_time('mysql'),
        ]);
        update_user_meta($user_id, '_wdc_course_requests', array_slice($course_requests, 0, 10));
        $notice = contenly_tr('Permintaan kursus tersimpan. Kru akan menghubungi untuk jadwal dan langkah selanjutnya.', 'Course request saved. The crew can follow up with schedule and next steps.');
    } else {
        $notice = contenly_tr('Pilih kursus terlebih dahulu.', 'Please choose a course first.');
        $notice_type = 'error';
    }
}

$courses = [];
if (post_type_exists('wm_course')) {
    $course_posts = get_posts(['post_type' => 'wm_course', 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC', 'meta_query' => ['relation' => 'OR', ['key' => '_wdc_catalog_visible', 'compare' => 'NOT EXISTS'], ['key' => '_wdc_catalog_visible', 'value' => '0', 'compare' => '!=']]]);
    foreach ($course_posts as $course_post) {
        $level_terms = wp_get_post_terms($course_post->ID, 'course_level', ['fields' => 'names']);
        $courses[] = [
            'id' => $course_post->ID,
            'title' => $course_post->post_title,
            'level' => !is_wp_error($level_terms) && $level_terms ? $level_terms[0] : 'Course',
            'duration' => get_post_meta($course_post->ID, '_wm_duration', true) ?: 'Flexible',
            'price' => (float) get_post_meta($course_post->ID, '_wm_price', true),
            'href' => get_permalink($course_post),
        ];
    }
}
if (!$courses) {
    $courses = [
        ['id' => 0, 'title' => 'Open Water Diver', 'level' => 'Beginner', 'duration' => '3-4 days', 'price' => 4500000, 'href' => '/courses/open-water-diver/'],
        ['id' => 0, 'title' => 'Advanced Open Water', 'level' => 'Next level', 'duration' => '2 days', 'price' => 3900000, 'href' => '/courses/advanced-open-water/'],
        ['id' => 0, 'title' => 'Rescue Diver', 'level' => 'Safety', 'duration' => '2-3 days', 'price' => 4200000, 'href' => '/courses/rescue-diver/'],
        ['id' => 0, 'title' => 'Divemaster', 'level' => 'Pro track', 'duration' => 'Flexible', 'price' => 0, 'href' => '/courses/divemaster/'],
    ];
}
?>
<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:8px;"><?php echo contenly_tr('Kursus Saya', 'My Courses'); ?></h1>
</div>

<?php if ($notice) : ?>
<div style="margin-bottom:18px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:minmax(0,1.2fr) minmax(300px,.8fr);gap:20px;align-items:start;margin-bottom:28px;">
    <section>
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0 0 16px;letter-spacing:.03em;"><?php echo contenly_tr('Jalur Kursus Tersedia', 'Available Course Pathways'); ?></h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
            <?php foreach ($courses as $course) : ?>
            <article style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:20px;box-shadow:0 12px 30px rgba(15,23,42,.05);">
                <div style="display:flex;justify-content:space-between;gap:10px;align-items:center;margin-bottom:14px;">
                    <span style="font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($course['level']); ?></span>
                    <span style="font-size:12px;color:#64748b;font-weight:700;"><?php echo esc_html($course['duration']); ?></span>
                </div>
                <h3 style="font-size:19px;font-weight:900;color:#0f172a;margin:0 0 8px;letter-spacing:.03em;"><?php echo esc_html($course['title']); ?></h3>
                <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0 0 18px;"><?php echo contenly_tr('Review prasyarat, opsi jadwal, dan hasil pelatihan sebelum mendaftar.', 'Review prerequisites, schedule options, and training outcomes before enrolling.'); ?></p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <a href="<?php echo esc_url(add_query_arg(['type' => 'course', 'item_id' => $course['id'] ?? 0, 'item' => $course['title'], 'price' => $course['price']], '/direct-checkout/')); ?>" style="display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:999px;background:#4cc8ed;color:#06384d;text-decoration:none;font-weight:950;font-size:13px;"><?php echo contenly_tr('Daftar / Checkout', 'Enroll / Checkout'); ?></a>
                    <a href="<?php echo esc_url($course['href']); ?>" style="display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:999px;background:#f3fbff;color:#06384d;text-decoration:none;font-weight:900;font-size:13px;border:1px solid rgba(6,56,77,.12);"><?php echo contenly_tr('Detail', 'Details'); ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <aside style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;letter-spacing:.03em;"><?php echo contenly_tr('Butuh Review Kru?', 'Need Crew Review?'); ?></h2>
        <form method="post" style="display:grid;gap:12px;">
            <?php wp_nonce_field('wdc_course_request', 'wdc_course_nonce'); ?>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Kursus', 'Course'); ?>
                <select name="selected_course" required style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
                    <option value=""><?php echo contenly_tr('Pilih kursus', 'Choose course'); ?></option>
                    <?php foreach ($courses as $course) : ?>
                    <option value="<?php echo esc_attr($course['title']); ?>"><?php echo esc_html($course['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Tanggal yang diinginkan', 'Preferred date'); ?>
                <input type="date" name="preferred_date" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Level pengalaman', 'Experience level'); ?>
                <select name="experience" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
                    <option value="New diver"><?php echo contenly_tr('Penyelam baru', 'New diver'); ?></option>
                    <option value="Certified diver"><?php echo contenly_tr('Penyelam bersertifikat', 'Certified diver'); ?></option>
                    <option value="Returning diver"><?php echo contenly_tr('Penyelam kembali', 'Returning diver'); ?></option>
                    <option value="Professional track"><?php echo contenly_tr('Jalur profesional', 'Professional track'); ?></option>
                </select>
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Catatan', 'Notes'); ?>
                <textarea name="message" rows="4" placeholder="<?php echo contenly_tr('Target tanggal, level sertifikasi, pertanyaan...', 'Target dates, certification level, questions...'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;resize:vertical;"></textarea>
            </label>
            <button type="submit" style="border:0;border-radius:999px;background:#4cc8ed;color:#06384d;padding:12px 16px;font-weight:950;cursor:pointer;"><?php echo contenly_tr('Minta Review', 'Request Review'); ?></button>
        </form>
    </aside>
</div>

<?php if (!empty($course_orders)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;">
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;letter-spacing:.03em;"><?php echo contenly_tr('Pesanan Kursus', 'Course Orders'); ?></h2>
    <div style="display:grid;gap:10px;">
        <?php foreach (array_slice($course_orders, 0, 5) as $order) : $order_link = !empty($order['item_id']) ? get_permalink((int) $order['item_id']) : ''; ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php if ($order_link) : ?><a href="<?php echo esc_url($order_link); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html($order['item'] ?? 'Course'); ?></a><?php else : ?><?php echo esc_html($order['item'] ?? 'Course'); ?><?php endif; ?></strong>
                <div style="font-size:13px;color:#64748b;">Order: <?php echo esc_html($order['id'] ?? 'Direct checkout'); ?><?php if (!empty($order['admin_note'])) : ?> · <?php echo esc_html($order['admin_note']); ?><?php endif; ?></div>
            </div>
            <span style="font-size:12px;font-weight:900;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($order['status'] ?? 'Payment Uploaded'); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($course_requests)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:28px;">
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 14px;"><?php echo contenly_tr('Aktivitas Kursus Terbaru', 'Recent Course Activity'); ?></h2>
    <div style="display:grid;gap:10px;">
        <?php foreach (array_slice($course_requests, 0, 5) as $request) : ?>
        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;flex-wrap:wrap;">
            <div>
                <strong style="color:#0f172a;"><?php echo esc_html($request['course'] ?? 'Course'); ?></strong>
                <div style="font-size:13px;color:#64748b;">Preferred: <?php echo esc_html($request['preferred_date'] ?: 'Flexible'); ?> · <?php echo esc_html($request['experience'] ?? ''); ?></div>
            </div>
            <span style="font-size:12px;font-weight:900;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px;"><?php echo esc_html($request['status'] ?? 'Requested'); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
