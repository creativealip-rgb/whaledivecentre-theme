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
// Giveaway was historically stored in course_orders for activity tracking — hide from Kursus Saya.
$course_orders = array_values(array_filter($course_orders, function ($order) {
    if (!is_array($order)) {
        return false;
    }
    $type = sanitize_key($order['type'] ?? '');
    $id = (string) ($order['id'] ?? '');
    $item = (string) ($order['item'] ?? '');
    if ($type === 'giveaway') {
        return false;
    }
    if (stripos($id, 'GW-') === 0) {
        return false;
    }
    if (stripos($item, 'Giveaway') === 0) {
        return false;
    }
    return true;
}));

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

<style>
.wdc-mc-layout{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(280px,.9fr);gap:18px;align-items:start;margin-bottom:24px}
.wdc-mc-left{display:grid;gap:18px;min-width:0}
.wdc-mc-panel{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:18px;box-shadow:0 8px 24px rgba(15,23,42,.04)}
.wdc-mc-activity-list{display:grid;gap:10px}
.wdc-mc-activity-item{
  display:flex;justify-content:space-between;gap:12px;align-items:center;
  padding:12px 14px;border:1px solid #e2e8f0;border-radius:12px;background:#f8fafc;flex-wrap:wrap
}
.wdc-mc-activity-item strong{display:block;color:#0f172a;font-size:14px;font-weight:700;line-height:1.35}
.wdc-mc-activity-item .meta{font-size:12px;color:#64748b;margin-top:3px;line-height:1.4}
.wdc-mc-activity-badge{
  font-size:11px;font-weight:800;color:#004A98;background:#e8f1fb;
  border-radius:999px;padding:5px 10px;white-space:nowrap
}
.wdc-mc-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px;flex-wrap:wrap}
.wdc-mc-page-head{display:none}
.wdc-mc-page-head p{font-size:15px;color:#5f7180;margin:0}
.wdc-mc-head h2{font-size:16px;font-weight:900;color:#0f172a;margin:0}
.wdc-mc-count{font-size:12px;font-weight:800;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:6px 10px}
.wdc-mc-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.wdc-mc-actions button{border:0;border-radius:999px;background:#4cc8ed;color:#004A98;padding:9px 14px;font-weight:950;font-size:13px;cursor:pointer}
.wdc-mc-side h2{font-size:17px;font-weight:900;color:#0f172a;margin:0 0 6px}
.wdc-mc-side p{font-size:13px;color:#64748b;margin:0 0 14px;line-height:1.5}
.wdc-mc-side form{display:grid;gap:8px}
.wdc-mc-side label{display:grid;gap:4px;font-size:12px;font-weight:800;color:#334155}
.wdc-mc-side input,
.wdc-mc-side select,
.wdc-mc-side textarea{
  border:1px solid #dbe4ea!important;
  border-radius:10px!important;
  padding:8px 10px!important;
  width:100%!important;
  max-width:100%!important;
  box-sizing:border-box!important;
  background:#fff!important;
  font-family:inherit!important;
  font-size:13px!important;
  font-weight:500!important;
  line-height:1.35!important;
  min-height:38px!important;
  height:auto!important;
  color:#0f172a!important;
}
.wdc-mc-side select{
  min-height:38px!important;
  padding-right:28px!important;
}
.wdc-mc-side textarea{
  min-height:72px!important;
  resize:vertical!important;
}
.wdc-mc-side button[type="submit"]{
  border:0;border-radius:999px;background:#004A98;color:#fff;
  padding:10px 14px;font-weight:950;font-size:13px;cursor:pointer;width:100%;min-height:40px
}
/* keep iOS no-zoom on phone only */
@media(max-width:760px){
  .wdc-mc-side input,.wdc-mc-side select,.wdc-mc-side textarea{font-size:16px!important;min-height:42px!important}
}
.wdc-mc-empty{text-align:center;padding:36px 16px;border:1px dashed #dbe4ea;border-radius:14px;background:#f8fafc}
.wdc-completed-list{display:grid;gap:10px}
.wdc-remove-completed{
  border:0;background:transparent;cursor:pointer;color:#94a3b8;
  font-size:16px;line-height:1;padding:4px 6px;border-radius:8px;flex-shrink:0
}
.wdc-remove-completed:hover{color:#C31C4A;background:#fff5f7}
.wdc-completed-item{
  display:grid;
  grid-template-columns:minmax(0,1.4fr) .7fr .8fr .9fr auto;
  gap:10px;
  align-items:center;
  padding:12px 14px;
  background:#fff;
  border:1px solid #e2e8f0;
  border-radius:12px;
}
.wdc-completed-item h3,
.wdc-completed-item .cell-title h3{
  margin:0!important;
  font-size:14px!important;
  font-weight:600!important;
  color:#0f172a!important;
  line-height:1.35!important;
  letter-spacing:0!important;
  font-family:inherit!important;
}
.wdc-completed-item .cell{font-size:12px;color:#64748b}
.wdc-completed-item .cell b{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;margin-bottom:2px;font-weight:700}
.wdc-completed-item .cell span{color:#0f172a;font-weight:600}
.wdc-add-form{
  display:none;
  margin:0 0 12px;
  padding:12px;
  background:#f8fafc;
  border:1px solid #e2e8f0;
  border-radius:12px;
}
.wdc-add-form-head{
  display:flex;justify-content:space-between;align-items:center;gap:8px;margin:0 0 10px
}
.wdc-add-form-head h3{
  margin:0!important;font-size:13px!important;font-weight:700!important;color:#0f172a!important;line-height:1.3!important
}
.wdc-add-form-close{
  border:0;background:transparent;color:#94a3b8;cursor:pointer;font-size:16px;line-height:1;padding:2px 6px;border-radius:8px
}
.wdc-add-form-close:hover{color:#C31C4A;background:#fff5f7}
.wdc-add-form-grid{
  display:grid;
  grid-template-columns:minmax(0,1.4fr) minmax(120px,.8fr) minmax(120px,.9fr) auto;
  gap:8px;
  align-items:end;
}
.wdc-add-form label{display:grid;gap:4px;font-size:11px;font-weight:700;color:#475569;min-width:0}
.wdc-add-form label span.opt{font-weight:400;color:#94a3b8}
.wdc-add-form input,
.wdc-add-form select{
  border:1px solid #dbe4ea!important;
  border-radius:10px!important;
  padding:8px 10px!important;
  width:100%!important;
  min-height:36px!important;
  box-sizing:border-box!important;
  background:#fff!important;
  font-size:13px!important;
  font-weight:500!important;
  line-height:1.3!important;
  color:#0f172a!important;
  font-family:inherit!important;
}
.wdc-add-form-actions{display:flex;align-items:center;gap:8px}
.wdc-add-form-actions button{
  border:0;border-radius:999px;background:#004A98;color:#fff;
  padding:0 14px;min-height:36px;font-size:12px;font-weight:800;cursor:pointer;white-space:nowrap
}
.wdc-add-form-actions button:hover{background:#3B44AC}
#wdc-completed-feedback{font-size:12px;font-weight:700}
@media(max-width:980px){.wdc-mc-layout{grid-template-columns:1fr}}
@media(max-width:700px){
  .wdc-completed-item{grid-template-columns:1fr 1fr;gap:8px}
  .wdc-completed-item .cell-title{grid-column:1 / -1}
  .wdc-completed-item .cell-action{grid-column:1 / -1;justify-self:end}
  .wdc-add-form-grid{grid-template-columns:1fr}
  .wdc-add-form-actions{justify-content:flex-start}
}
@media(max-width:760px){
  .wdc-add-form input,.wdc-add-form select{font-size:16px!important;min-height:42px!important}
}
</style>

<div class="wdc-page-head">
    <h1><?php echo contenly_tr('Kursus Saya', 'My Courses'); ?></h1>
    <p class="wdc-page-sub"><?php echo contenly_tr('Daftar kursus menyelam yang sudah pernah kamu ikuti.', 'Your dive courses you have completed.'); ?></p>
</div>


<?php if ($notice) : ?>
<div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:<?php echo $notice_type === 'success' ? '#dcfce7' : '#fee2e2'; ?>;color:<?php echo $notice_type === 'success' ? '#166534' : '#991b1b'; ?>;font-weight:800;font-size:14px;">
    <?php echo esc_html($notice); ?>
</div>
<?php endif; ?>

<div class="wdc-mc-layout">
    <div class="wdc-mc-left">
    <section class="wdc-mc-panel wdc-mc-main">
        <div class="wdc-mc-head">
            <div>
                <h2 class="wdc-section-title" style="margin:0;"><?php echo contenly_tr('Kursus Selesai', 'Completed Courses'); ?></h2>
            </div>
            <div class="wdc-mc-actions">
                <span class="wdc-mc-count"><?php echo intval($completed_count); ?> <?php echo contenly_tr('kursus', 'courses'); ?></span>
                <button type="button" id="wdc-toggle-add-completed">+ <?php echo contenly_tr('Tambah Kursus', 'Add Course'); ?></button>
            </div>
        </div>

        <!-- Add completed course form (hidden by default) -->
        <div id="wdc-add-completed-form" class="wdc-add-form">
            <div class="wdc-add-form-head">
                <h3><?php echo contenly_tr('Tambah Kursus Selesai', 'Add Completed Course'); ?></h3>
                <button type="button" class="wdc-add-form-close" id="wdc-close-add-completed" aria-label="<?php echo contenly_tr('Tutup', 'Close'); ?>">✕</button>
            </div>
            <div class="wdc-add-form-grid">
                <label><?php echo contenly_tr('Kursus', 'Course'); ?>
                    <select id="wdc-completed-course-select">
                        <option value=""><?php echo contenly_tr('Pilih kursus', 'Choose course'); ?></option>
                        <?php foreach ($courses as $c) : ?>
                        <option value="<?php echo esc_attr($c['id']); ?>" data-title="<?php echo esc_attr($c['title']); ?>" data-level="<?php echo esc_attr($c['level']); ?>"><?php echo esc_html($c['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label><?php echo contenly_tr('Tanggal', 'Date'); ?>
                    <input type="date" id="wdc-completed-date">
                </label>
                <label><?php echo contenly_tr('Sertifikat', 'Cert'); ?> <span class="opt">(<?php echo contenly_tr('opsional', 'optional'); ?>)</span>
                    <input type="text" id="wdc-completed-cert" placeholder="<?php echo contenly_tr('No. sertifikat', 'Cert number'); ?>">
                </label>
                <div class="wdc-add-form-actions">
                    <button id="wdc-save-completed" type="button"><?php echo contenly_tr('Simpan', 'Save'); ?></button>
                    <span id="wdc-completed-feedback"></span>
                </div>
            </div>
        </div>

        <!-- Completed courses list — design D compact table -->
        <div id="wdc-completed-list" class="wdc-completed-list">
            <?php if ($completed_courses) : ?>
                <?php foreach ($completed_courses as $idx => $cc) :
                    $title = (string) ($cc['course_title'] ?? 'Course');
                    $level = (string) ($cc['level'] ?? '');
                    $date_raw = (string) ($cc['date_completed'] ?? '');
                    $cert = (string) ($cc['cert_number'] ?? '');
                    $date_label = $date_raw;
                    if ($date_raw && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_raw)) {
                        $ts = strtotime($date_raw);
                        if ($ts) {
                            // Prefer Indonesian month names when available.
                            $date_label = date_i18n('j F Y', $ts);
                        }
                    }
                ?>
                <article class="wdc-completed-item" data-index="<?php echo (int) $idx; ?>">
                    <div class="cell-title">
                        <h3><?php echo esc_html($title); ?></h3>
                    </div>
                    <div class="cell"><b><?php echo contenly_tr('Level', 'Level'); ?></b><span><?php echo esc_html($level ?: '—'); ?></span></div>
                    <div class="cell"><b><?php echo contenly_tr('Tanggal', 'Date'); ?></b><span><?php echo esc_html($date_label ?: '—'); ?></span></div>
                    <div class="cell"><b><?php echo contenly_tr('Sertifikat', 'Cert'); ?></b><span><?php echo esc_html($cert ?: '—'); ?></span></div>
                    <button class="wdc-remove-completed cell-action" type="button" data-index="<?php echo (int) $idx; ?>" title="<?php echo contenly_tr('Hapus', 'Remove'); ?>" aria-label="<?php echo contenly_tr('Hapus', 'Remove'); ?>">✕</button>
                </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="wdc-mc-empty">
                    <div style="font-size:28px;margin-bottom:8px;">🎓</div>
                    <h3 style="font-size:15px;font-weight:800;color:#0f172a;margin:0 0 6px;"><?php echo contenly_tr('Belum Ada Kursus', 'No Courses Yet'); ?></h3>
                    <p style="color:#64748b;font-size:13px;margin:0 0 14px;line-height:1.5;"><?php echo contenly_tr('Tambahkan kursus yang sudah pernah kamu ikuti, atau ajukan pendaftaran di panel kanan.', 'Add completed courses, or request enrollment from the right panel.'); ?></p>
                    <button type="button" onclick="document.getElementById('wdc-toggle-add-completed').click()" style="border:0;border-radius:999px;background:#4cc8ed;color:#004A98;padding:10px 16px;font-weight:950;font-size:13px;cursor:pointer;">+ <?php echo contenly_tr('Tambah Kursus', 'Add Course'); ?></button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($course_requests)) : ?>
    <section class="wdc-mc-panel wdc-mc-activity">
        <div class="wdc-mc-head">
            <div>
                <h2 class="wdc-section-title" style="margin:0;"><?php echo contenly_tr('Aktivitas Kursus Terbaru', 'Recent Course Activity'); ?></h2>
            </div>
            <span class="wdc-mc-count"><?php echo (int) min(5, count($course_requests)); ?> <?php echo contenly_tr('request', 'requests'); ?></span>
        </div>
        <div class="wdc-mc-activity-list">
            <?php foreach (array_slice($course_requests, 0, 5) as $request) : ?>
            <div class="wdc-mc-activity-item">
                <div>
                    <strong><?php echo esc_html($request['course'] ?? 'Course'); ?></strong>
                    <div class="meta">
                        <?php echo esc_html(contenly_tr('Preferensi', 'Preferred')); ?>:
                        <?php echo esc_html(!empty($request['preferred_date']) ? $request['preferred_date'] : contenly_tr('Fleksibel', 'Flexible')); ?>
                        <?php if (!empty($request['experience'])) : ?> · <?php echo esc_html($request['experience']); ?><?php endif; ?>
                    </div>
                </div>
                <span class="wdc-mc-activity-badge"><?php echo esc_html($request['status'] ?? 'Requested'); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($course_orders)) : ?>
    <section class="wdc-mc-panel wdc-mc-orders">
        <div class="wdc-mc-head">
            <div>
                <h2 class="wdc-section-title" style="margin:0;"><?php echo contenly_tr('Pesanan Kursus', 'Course Orders'); ?></h2>
            </div>
        </div>
        <div class="wdc-mc-activity-list">
            <?php foreach (array_slice($course_orders, 0, 5) as $order) :
                $order_link = !empty($order['item_id']) ? get_permalink((int) $order['item_id']) : '';
            ?>
            <div class="wdc-mc-activity-item">
                <div>
                    <strong>
                        <?php if ($order_link) : ?>
                        <a href="<?php echo esc_url($order_link); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html($order['item'] ?? 'Course'); ?></a>
                        <?php else : ?>
                        <?php echo esc_html($order['item'] ?? 'Course'); ?>
                        <?php endif; ?>
                    </strong>
                    <div class="meta">
                        Order: <?php echo esc_html($order['id'] ?? 'Direct checkout'); ?>
                        <?php if (!empty($order['admin_note'])) : ?> · <?php echo esc_html($order['admin_note']); ?><?php endif; ?>
                    </div>
                </div>
                <span class="wdc-mc-activity-badge"><?php echo esc_html($order['status'] ?? 'Payment Uploaded'); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    </div>

    <aside class="wdc-mc-panel wdc-mc-side" id="wdc-course-request">
        <h2 class="wdc-section-title"><?php echo contenly_tr('Ajukan Pendaftaran Kursus', 'Request Course Enrollment'); ?></h2>
        <p class="wdc-section-sub"><?php echo contenly_tr('Isi form ini untuk daftar. Crew follow-up setelah request masuk.', 'Fill this form to enroll. Crew follows up after the request lands.'); ?></p>
        <form method="post">
            <?php wp_nonce_field('wdc_course_request', 'wdc_course_nonce'); ?>
            <input type="hidden" name="item_id" value="<?php echo esc_attr($prefill_item_id); ?>">
            <label><?php echo contenly_tr('Kursus', 'Course'); ?>
                <select name="selected_course" required>
                    <option value=""><?php echo contenly_tr('Pilih kursus', 'Choose course'); ?></option>
                    <?php foreach ($courses as $c) :
                        $selected = ($prefill_item_id && (int)$c['id'] === $prefill_item_id) || ($prefill_item && strcasecmp($prefill_item, $c['title']) === 0);
                    ?>
                    <option value="<?php echo esc_attr($c['title']); ?>" <?php selected($selected); ?>><?php echo esc_html($c['title']); ?><?php echo !empty($c['price']) ? ' · Rp ' . number_format((float)$c['price'], 0, ',', '.') : ''; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label><?php echo contenly_tr('Tanggal preferensi', 'Preferred date'); ?>
                <input type="date" name="preferred_date">
            </label>
            <label><?php echo contenly_tr('Level / pengalaman', 'Level / experience'); ?>
                <input type="text" name="experience" placeholder="<?php echo contenly_tr('Misal: Open Water, 20 dive', 'e.g. Open Water, 20 dives'); ?>">
            </label>
            <label><?php echo contenly_tr('Catatan', 'Notes'); ?>
                <textarea name="message" rows="3" placeholder="<?php echo contenly_tr('Jadwal longgar, private/group, tujuan sertifikasi...', 'Flexible schedule, private/group, certification goal...'); ?>"></textarea>
            </label>
            <button type="submit"><?php echo contenly_tr('Kirim Permintaan Daftar', 'Submit Enrollment Request'); ?></button>
        </form>
    </aside>
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
    
    // Focus request panel when ?request=1 or prefill present
    if (<?php echo $show_request ? 'true' : 'false'; ?>) {
        var req = document.getElementById('wdc-course-request');
        if (req) {
            try { req.scrollIntoView({behavior:'smooth', block:'start'}); } catch(e) {}
            var sel = req.querySelector('select[name="selected_course"]');
            if (sel) sel.focus();
        }
    }

    function setAddFormOpen(open) {
        if (!form) return;
        form.style.display = open ? 'block' : 'none';
        if (open) {
            try { form.scrollIntoView({behavior:'smooth', block:'nearest'}); } catch(e) {}
            var sel = document.getElementById('wdc-completed-course-select');
            if (sel) sel.focus();
        }
    }
    if (toggleBtn && form) {
        toggleBtn.addEventListener('click', function(){
            setAddFormOpen(form.style.display === 'none' || form.style.display === '');
        });
    }
    var closeBtn = document.getElementById('wdc-close-add-completed');
    if (closeBtn && form) {
        closeBtn.addEventListener('click', function(){ setAddFormOpen(false); });
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
            var notesEl = document.getElementById('wdc-completed-notes');
            fd.append('notes', notesEl ? notesEl.value : '');

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
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
