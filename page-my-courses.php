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

if ((($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') && isset($_POST['wdc_course_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wdc_course_nonce'])), 'wdc_course_request')) {
    $selected_course = sanitize_text_field(wp_unslash($_POST['selected_course'] ?? ''));
    $preferred_date = sanitize_text_field(wp_unslash($_POST['preferred_date'] ?? ''));
    $experience = sanitize_text_field(wp_unslash($_POST['experience'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $item_id = absint($_POST['item_id'] ?? 0);

    if ($selected_course) {
        array_unshift($course_requests, [
            'course' => $selected_course,
            'item_id' => $item_id,
            'preferred_date' => $preferred_date,
            'experience' => $experience ?: 'Not specified',
            'message' => $message,
            'status' => 'Requested',
            'created_at' => current_time('mysql'),
        ]);
        update_user_meta($user_id, '_wdc_course_requests', array_slice($course_requests, 0, 20));
        if (function_exists('wdc_notify_request')) {
            wdc_notify_request('course', $user_id, $course_requests[0]);
        }
        $notice = contenly_tr('Permintaan kursus tersimpan. Crew akan follow-up konfirmasi jadwal.', 'Course request saved. Crew will follow up to confirm schedule.');
    } else {
        $notice = contenly_tr('Pilih kursus terlebih dahulu.', 'Please choose a course first.');
        $notice_type = 'error';
    }
}

$prefill_item = sanitize_text_field(wp_unslash($_GET['item'] ?? ''));
$prefill_item_id = absint($_GET['item_id'] ?? 0);
$show_request = isset($_GET['request']) || $prefill_item || $prefill_item_id;

// Load courses for the add-form dropdown
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
$completed_courses = get_user_meta($user_id, '_wdc_completed_courses', true);
$completed_courses = is_array($completed_courses) ? $completed_courses : [];
$completed_count = count($completed_courses);
?>

<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:6px;"><?php echo contenly_tr('Kursus Saya', 'My Courses'); ?></h1>
    <p style="font-size:15px;color:#5f7180;margin:0;"><?php echo contenly_tr('Daftar kursus menyelam yang sudah pernah kamu ikuti.', 'Your dive courses you have completed.'); ?></p>
</div>

<?php if ($notice) : ?>
<div style="margin-bottom:18px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<section id="wdc-course-request" style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-bottom:24px;box-shadow:0 12px 34px rgba(15,23,42,.05);<?php echo $show_request ? '' : ''; ?>">
    <h2 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 8px;"><?php echo contenly_tr('Ajukan Pendaftaran Kursus', 'Request Course Enrollment'); ?></h2>
    <p style="font-size:14px;color:#64748b;margin:0 0 16px;"><?php echo contenly_tr('Isi form ini untuk daftar. Crew follow-up setelah request masuk.', 'Fill this form to enroll. Crew follows up after the request lands.'); ?></p>
    <form method="post" style="display:grid;gap:12px;">
        <?php wp_nonce_field('wdc_course_request', 'wdc_course_nonce'); ?>
        <input type="hidden" name="item_id" value="<?php echo esc_attr($prefill_item_id); ?>">
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Kursus', 'Course'); ?>
            <select name="selected_course" required style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
                <option value=""><?php echo contenly_tr('Pilih kursus', 'Choose course'); ?></option>
                <?php foreach ($courses as $c) :
                    $selected = ($prefill_item_id && (int)$c['id'] === $prefill_item_id) || ($prefill_item && strcasecmp($prefill_item, $c['title']) === 0);
                ?>
                <option value="<?php echo esc_attr($c['title']); ?>" <?php selected($selected); ?>><?php echo esc_html($c['title']); ?><?php echo !empty($c['price']) ? ' · Rp ' . number_format((float)$c['price'], 0, ',', '.') : ''; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Tanggal preferensi', 'Preferred date'); ?>
                <input type="date" name="preferred_date" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
            </label>
            <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Level / pengalaman', 'Level / experience'); ?>
                <input type="text" name="experience" placeholder="<?php echo contenly_tr('Misal: Open Water, 20 dive', 'e.g. Open Water, 20 dives'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
            </label>
        </div>
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Catatan', 'Notes'); ?>
            <textarea name="message" rows="3" placeholder="<?php echo contenly_tr('Jadwal longgar, private/group, tujuan sertifikasi...', 'Flexible schedule, private/group, certification goal...'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;resize:vertical;"></textarea>
        </label>
        <button type="submit" style="border:0;border-radius:999px;background:#004A98;color:#fff;padding:12px 18px;font-weight:950;cursor:pointer;width:fit-content;"><?php echo contenly_tr('Kirim Permintaan Daftar', 'Submit Enrollment Request'); ?></button>
    </form>
</section>

<!-- Action bar -->
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:20px;">
    <div style="display:flex;gap:12px;align-items:center;">
        <span style="font-size:13px;color:#5f7180;font-weight:700;"><?php echo $completed_count; ?> <?php echo contenly_tr('kursus', 'courses'); ?></span>
    </div>
    <button id="wdc-toggle-add-completed" style="border:0;border-radius:999px;background:#4cc8ed;color:#06384d;padding:10px 18px;font-weight:950;font-size:13px;cursor:pointer;">+ <?php echo contenly_tr('Tambah Kursus', 'Add Course'); ?></button>
</div>

<!-- Add completed course form (hidden by default) -->
<div id="wdc-add-completed-form" style="display:none;background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;margin-bottom:20px;box-shadow:0 8px 24px rgba(15,23,42,.05);">
    <h3 style="font-size:16px;font-weight:800;color:#0f172a;margin:0 0 14px;"><?php echo contenly_tr('Tambah Kursus Selesai', 'Add Completed Course'); ?></h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;align-items:end;">
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Kursus', 'Course'); ?>
            <select id="wdc-completed-course-select" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
                <option value=""><?php echo contenly_tr('Pilih kursus', 'Choose course'); ?></option>
                <?php foreach ($courses as $c) : ?>
                <option value="<?php echo esc_attr($c['id']); ?>" data-title="<?php echo esc_attr($c['title']); ?>" data-level="<?php echo esc_attr($c['level']); ?>"><?php echo esc_html($c['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Tanggal Selesai', 'Date Completed'); ?>
            <input type="date" id="wdc-completed-date" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
        </label>
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('No. Sertifikat', 'Cert Number'); ?> <span style="font-weight:400;color:#94a3b8;">(<?php echo contenly_tr('opsional', 'optional'); ?>)</span>
            <input type="text" id="wdc-completed-cert" placeholder="<?php echo contenly_tr('No. sertifikat', 'Cert number'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;">
        </label>
    </div>
    <div style="margin-top:12px;">
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Catatan', 'Notes'); ?> <span style="font-weight:400;color:#94a3b8;">(<?php echo contenly_tr('opsional', 'optional'); ?>)</span>
            <textarea id="wdc-completed-notes" rows="2" placeholder="<?php echo contenly_tr('Nama instruktur, lokasi, dll...', 'Instructor name, location, etc...'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 12px;resize:vertical;"></textarea>
        </label>
    </div>
    <div style="margin-top:14px;display:flex;gap:8px;align-items:center;">
        <button id="wdc-save-completed" style="border:0;border-radius:999px;background:#16a34a;color:#fff;padding:10px 20px;font-weight:900;font-size:13px;cursor:pointer;"><?php echo contenly_tr('Simpan', 'Save'); ?></button>
        <span id="wdc-completed-feedback" style="font-size:13px;font-weight:700;"></span>
    </div>
</div>

<!-- Completed courses list -->
<div id="wdc-completed-list">
    <?php if ($completed_courses) : ?>
        <?php foreach ($completed_courses as $idx => $cc) : ?>
        <div class="wdc-completed-item" data-index="<?php echo $idx; ?>" style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:16px 18px;background:#fff;border:1px solid #e2e8f0;border-radius:16px;margin-bottom:10px;box-shadow:0 6px 20px rgba(15,23,42,.04);flex-wrap:wrap;">
            <div style="min-width:0;flex:1;">
                <strong style="color:#0f172a;font-size:16px;"><?php echo esc_html($cc['course_title']); ?></strong>
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;font-size:12px;">
                    <?php if (!empty($cc['level'])) : ?><span style="padding:4px 10px;border-radius:999px;background:#e8f8fc;color:#0b617c;font-weight:800;"><?php echo esc_html($cc['level']); ?></span><?php endif; ?>
                    <?php if (!empty($cc['date_completed'])) : ?><span style="color:#64748b;font-weight:600;">📅 <?php echo esc_html($cc['date_completed']); ?></span><?php endif; ?>
                    <?php if (!empty($cc['cert_number'])) : ?><span style="color:#64748b;font-weight:600;">🎫 <?php echo esc_html($cc['cert_number']); ?></span><?php endif; ?>
                </div>
                <?php if (!empty($cc['notes'])) : ?><p style="font-size:13px;color:#5f7180;margin:8px 0 0;line-height:1.5;"><?php echo esc_html($cc['notes']); ?></p><?php endif; ?>
            </div>
            <button class="wdc-remove-completed" data-index="<?php echo $idx; ?>" title="<?php echo contenly_tr('Hapus', 'Remove'); ?>" style="border:0;background:none;cursor:pointer;color:#94a3b8;font-size:18px;padding:6px 10px;border-radius:8px;flex-shrink:0;transition:color .15s;">✕</button>
        </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div style="text-align:center;padding:60px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:20px;">
            <div style="font-size:48px;margin-bottom:16px;">🎓</div>
            <h3 style="font-size:18px;font-weight:800;color:#0f172a;margin:0 0 8px;"><?php echo contenly_tr('Belum Ada Kursus', 'No Courses Yet'); ?></h3>
            <p style="color:#64748b;font-size:14px;margin:0 0 20px;max-width:360px;margin-left:auto;margin-right:auto;line-height:1.6;"><?php echo contenly_tr('Kamu belum menambahkan kursus apapun. Tambahkan kursus menyelam yang sudah pernah kamu ikuti untuk melacak progresmu.', 'You haven\'t added any courses yet. Add dive courses you have completed to track your progress.'); ?></p>
            <button onclick="document.getElementById('wdc-toggle-add-completed').click()" style="border:0;border-radius:999px;background:#4cc8ed;color:#06384d;padding:12px 24px;font-weight:950;font-size:14px;cursor:pointer;">+ <?php echo contenly_tr('Tambah Kursus Pertama', 'Add Your First Course'); ?></button>
        </div>
    <?php endif; ?>
</div>

<!-- Completed Courses AJAX -->
<script>
(function(){
    var ajaxUrl = (typeof wdcMemberAjax !== 'undefined') ? wdcMemberAjax.ajaxUrl : '<?php echo admin_url("admin-ajax.php"); ?>';
    var nonce = (typeof wdcMemberAjax !== 'undefined') ? wdcMemberAjax.nonce : '<?php echo wp_create_nonce("wdc_member_nonce"); ?>';
    var userId = <?php echo $user_id; ?>;
    var i18n = {
        confirmRemove: '<?php echo contenly_tr("Hapus kursus ini?", "Remove this course?"); ?>',
        saved: '<?php echo contenly_tr("Tersimpan ✓", "Saved ✓"); ?>',
        removed: '<?php echo contenly_tr("Dihapus ✓", "Removed ✓"); ?>',
        selectCourse: '<?php echo contenly_tr("Pilih kursus dulu", "Select a course first"); ?>',
        error: '<?php echo contenly_tr("Gagal, coba lagi", "Failed, try again"); ?>'
    };

    // Toggle form
    var toggleBtn = document.getElementById('wdc-toggle-add-completed');
    var form = document.getElementById('wdc-add-completed-form');
    if (toggleBtn && form) {
        toggleBtn.addEventListener('click', function(){
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
            if (form.style.display === 'block') {
                form.scrollIntoView({behavior:'smooth', block:'nearest'});
            }
        });
    }

    // Save completed course
    var saveBtn = document.getElementById('wdc-save-completed');
    if (saveBtn) {
        saveBtn.addEventListener('click', function(){
            var sel = document.getElementById('wdc-completed-course-select');
            var opt = sel.options[sel.selectedIndex];
            if (!opt || !opt.value) {
                document.getElementById('wdc-completed-feedback').textContent = i18n.selectCourse;
                document.getElementById('wdc-completed-feedback').style.color = '#dc2626';
                return;
            }
            var fd = new FormData();
            fd.append('action', 'wdc_add_completed_course');
            fd.append('nonce', nonce);
            fd.append('user_id', userId);
            fd.append('course_id', opt.value);
            fd.append('course_title', opt.getAttribute('data-title') || opt.textContent);
            fd.append('level', opt.getAttribute('data-level') || '');
            fd.append('date_completed', document.getElementById('wdc-completed-date').value);
            fd.append('cert_number', document.getElementById('wdc-completed-cert').value);
            fd.append('notes', document.getElementById('wdc-completed-notes').value);

            fetch(ajaxUrl, {method:'POST', body:fd, credentials:'same-origin'})
                .then(function(r){return r.json();})
                .then(function(d){
                    var fb = document.getElementById('wdc-completed-feedback');
                    if (d.success) {
                        fb.textContent = i18n.saved;
                        fb.style.color = '#16a34a';
                        setTimeout(function(){ location.reload(); }, 600);
                    } else {
                        fb.textContent = d.data || i18n.error;
                        fb.style.color = '#dc2626';
                    }
                })
                .catch(function(){
                    document.getElementById('wdc-completed-feedback').textContent = i18n.error;
                    document.getElementById('wdc-completed-feedback').style.color = '#dc2626';
                });
        });
    }

    // Remove completed course
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.wdc-remove-completed');
        if (!btn) return;
        if (!confirm(i18n.confirmRemove)) return;

        var idx = btn.getAttribute('data-index');
        var fd = new FormData();
        fd.append('action', 'wdc_remove_completed_course');
        fd.append('nonce', nonce);
        fd.append('user_id', userId);
        fd.append('index', idx);

        fetch(ajaxUrl, {method:'POST', body:fd, credentials:'same-origin'})
            .then(function(r){return r.json();})
            .then(function(d){
                if (d.success) {
                    var item = btn.closest('.wdc-completed-item');
                    if (item) item.remove();
                    var list = document.getElementById('wdc-completed-list');
                    if (list && !list.querySelector('.wdc-completed-item')) {
                        location.reload();
                    }
                }
            })
            .catch(function(){ alert(i18n.error); });
    });
})();
</script>

<?php if (!empty($course_orders)) : ?>
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:22px;margin-top:28px;margin-bottom:28px;">
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
