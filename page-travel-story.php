<?php
/**
 * Template Name: Travel Story
 */

require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['travel_story_action'])) {
    if (!isset($_POST['travel_story_nonce']) || !wp_verify_nonce($_POST['travel_story_nonce'], 'travel_story_form')) {
        $error = contenly_tr('Pemeriksaan keamanan gagal.', 'Security check failed.');
    } else {
        $action = sanitize_text_field($_POST['travel_story_action']);

        if ($action === 'delete') {
            $story_id = absint($_POST['story_id'] ?? 0);
            $story = get_post($story_id);
            if ($story && (int)$story->post_author === (int)$user_id && $story->post_type === 'post') {
                wp_delete_post($story_id, true);
                $message = contenly_tr('Cerita berhasil dihapus.', 'Story deleted successfully.');
            } else {
                $error = contenly_tr('Cerita tidak ditemukan.', 'Story not found.');
            }
        }

        if ($action === 'save') {
            $story_id = absint($_POST['story_id'] ?? 0);
            $title = sanitize_text_field($_POST['story_title'] ?? '');
            $excerpt = sanitize_textarea_field($_POST['story_excerpt'] ?? '');
            $content = wp_kses_post($_POST['story_content'] ?? '');

            if (!$title || !$content) {
                $error = contenly_tr('Judul dan isi cerita wajib diisi.', 'Story title and content are required.');
            } else {
                $post_data = [
                    'post_type'    => 'post',
                    'post_title'   => $title,
                    'post_excerpt' => $excerpt,
                    'post_content' => $content,
                    'post_author'  => $user_id,
                    'post_status'  => 'pending',
                ];

                if ($story_id) {
                    $existing = get_post($story_id);
                    if (!$existing || (int)$existing->post_author !== (int)$user_id) {
                        $error = contenly_tr('Tidak punya izin edit cerita ini.', 'You do not have permission to edit this story.');
                    } else {
                        $post_data['ID'] = $story_id;
                        $saved_id = wp_update_post($post_data, true);
                    }
                } else {
                    $saved_id = wp_insert_post($post_data, true);
                }

                if (empty($error)) {
                    if (is_wp_error($saved_id)) {
                        $error = contenly_tr('Gagal menyimpan cerita. Coba lagi.', 'Failed to save the story. Please try again.');
                    } else {
                        update_post_meta($saved_id, '_is_travel_story', '1');

                        if (!empty($_FILES['story_cover']['name'])) {
                            require_once ABSPATH . 'wp-admin/includes/file.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';
                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            $attachment_id = media_handle_upload('story_cover', $saved_id);
                            if (!is_wp_error($attachment_id)) {
                                set_post_thumbnail($saved_id, $attachment_id);
                            }
                        }

                        $message = contenly_tr('Cerita berhasil disimpan. Status: menunggu review admin.', 'Story saved successfully. Status: waiting for admin review.');
                    }
                }
            }
        }
    }
}

$edit_id = absint($_GET['edit'] ?? 0);
$edit_story = null;
if ($edit_id) {
    $candidate = get_post($edit_id);
    if ($candidate && (int)$candidate->post_author === (int)$user_id && $candidate->post_type === 'post') {
        $edit_story = $candidate;
    }
}

$user_stories = get_posts(contenly_all_language_post_args([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'post_status' => ['publish', 'pending', 'draft'],
    'author' => $user_id,
    'meta_query' => [
        [
            'key' => '_is_travel_story',
            'value' => '1',
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC'
]));

// De-duplicate stories with same title (prefer publish over pending/draft)
$deduped_stories = [];
foreach ($user_stories as $story_item) {
    $key = sanitize_title($story_item->post_title);
    if (!isset($deduped_stories[$key])) {
        $deduped_stories[$key] = $story_item;
        continue;
    }

    $existing_story = $deduped_stories[$key];
    $existing_rank = $existing_story->post_status === 'publish' ? 3 : ($existing_story->post_status === 'pending' ? 2 : 1);
    $current_rank = $story_item->post_status === 'publish' ? 3 : ($story_item->post_status === 'pending' ? 2 : 1);

    if ($current_rank > $existing_rank) {
        $deduped_stories[$key] = $story_item;
    }
}
$user_stories = array_values($deduped_stories);
?>

<div style="margin-bottom: 28px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">✍️ <?php echo esc_html(contenly_tr('Travel Story', 'Travel Story')); ?></h1>
    <p style="font-size: 15px; color: #64748b;"><?php echo esc_html(contenly_tr('Tulis cerita perjalananmu. Admin bisa pilih salah satu jadi story unggulan di homepage.', 'Write your travel story. The admin can choose one of them to be featured on the homepage.')); ?></p>
</div>

<?php if ($message) : ?>
<div style="margin-bottom:16px; background:#dcfce7; color:#166534; padding:12px 16px; border-radius:10px; font-weight:600;"><?php echo esc_html($message); ?></div>
<?php endif; ?>
<?php if ($error) : ?>
<div style="margin-bottom:16px; background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:10px; font-weight:600;"><?php echo esc_html($error); ?></div>
<?php endif; ?>

<div class="story-form-card" style="background:white; border-radius:16px; padding:20px; margin-bottom:24px; border:1px solid #e2e8f0;">
    <h2 style="font-size:20px; margin-bottom:12px; color:#0f172a;"><?php echo esc_html($edit_story ? contenly_tr('Edit Cerita', 'Edit Story') : contenly_tr('Buat Cerita Baru', 'Create a New Story')); ?></h2>
    <form class="story-form" method="post" enctype="multipart/form-data" style="display:grid; gap:12px;">
        <?php wp_nonce_field('travel_story_form', 'travel_story_nonce'); ?>
        <input type="hidden" name="travel_story_action" value="save">
        <input type="hidden" name="story_id" value="<?php echo esc_attr($edit_story ? $edit_story->ID : 0); ?>">

        <div>
            <label style="display:block; font-size:13px; margin-bottom:6px; color:#334155; font-weight:600;"><?php echo esc_html(contenly_tr('Judul Cerita', 'Story Title')); ?></label>
            <input type="text" name="story_title" value="<?php echo esc_attr($edit_story ? $edit_story->post_title : ''); ?>" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:10px;">
        </div>

        <div>
            <label style="display:block; font-size:13px; margin-bottom:6px; color:#334155; font-weight:600;"><?php echo esc_html(contenly_tr('Ringkasan Singkat', 'Short Summary')); ?></label>
            <textarea name="story_excerpt" rows="3" style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:10px;"><?php echo esc_textarea($edit_story ? $edit_story->post_excerpt : ''); ?></textarea>
        </div>

        <div>
            <label style="display:block; font-size:13px; margin-bottom:6px; color:#334155; font-weight:600;"><?php echo esc_html(contenly_tr('Isi Cerita', 'Story Content')); ?></label>
            <textarea name="story_content" rows="8" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:10px;"><?php echo esc_textarea($edit_story ? $edit_story->post_content : ''); ?></textarea>
        </div>

        <div>
            <label style="display:block; font-size:13px; margin-bottom:6px; color:#334155; font-weight:600;"><?php echo esc_html(contenly_tr('Cover Story (opsional)', 'Story Cover (optional)')); ?></label>
            <input type="file" name="story_cover" accept="image/*" style="display:block;">
        </div>

        <button type="submit" style="padding:12px 16px; background:linear-gradient(135deg,#355F72,#539294); color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer;">
            <?php echo esc_html($edit_story ? contenly_tr('Update Story', 'Update Story') : contenly_tr('Simpan Story', 'Save Story')); ?>
        </button>
    </form>
</div>

<div style="background:white; border-radius:16px; padding:20px; border:1px solid #e2e8f0;">
    <h2 style="font-size:20px; margin-bottom:12px; color:#0f172a;"><?php echo esc_html(contenly_tr('Story Saya', 'My Stories')); ?></h2>
    <?php if (empty($user_stories)) : ?>
        <p style="color:#64748b;"><?php echo esc_html(contenly_tr('Belum ada story. Yuk bikin yang pertama.', 'There are no stories yet. Create your first one.')); ?></p>
    <?php else : ?>
        <div class="story-list" style="display:grid; gap:12px;">
            <?php foreach ($user_stories as $story) : ?>
                <div class="story-item" style="border:1px solid #e2e8f0; border-radius:12px; padding:14px; display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
                    <div>
                        <div style="font-weight:700; color:#0f172a; margin-bottom:6px;"><?php echo esc_html($story->post_title); ?></div>
                        <div style="font-size:12px; color:#64748b;">Status: <?php echo esc_html($story->post_status === 'publish' ? 'publish' : ($story->post_status === 'pending' ? 'pending review' : 'draft')); ?> • <?php echo esc_html(get_the_date('d M Y', $story)); ?></div>
                    </div>
                    <div class="story-item-actions" style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                        <?php if ($story->post_status === 'publish') : ?>
                            <a href="<?php echo esc_url(get_permalink($story)); ?>" target="_blank" rel="noopener" style="padding:8px 10px; border-radius:8px; text-decoration:none; background:#EEF5F4; color:#355F72; font-weight:600; font-size:13px;">Lihat</a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(get_preview_post_link($story)); ?>" target="_blank" rel="noopener" style="padding:8px 10px; border-radius:8px; text-decoration:none; background:#f8fafc; color:#475569; font-weight:600; font-size:13px;">Preview</a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(add_query_arg('edit', $story->ID, get_permalink())); ?>" style="padding:8px 10px; border-radius:8px; text-decoration:none; background:#EEF5F4; color:#539294; font-weight:600; font-size:13px;">Edit</a>
                        <form method="post" onsubmit="return confirm('Hapus story ini?')">
                            <?php wp_nonce_field('travel_story_form', 'travel_story_nonce'); ?>
                            <input type="hidden" name="travel_story_action" value="delete">
                            <input type="hidden" name="story_id" value="<?php echo esc_attr($story->ID); ?>">
                            <button type="submit" style="padding:8px 10px; border:0; border-radius:8px; background:#fee2e2; color:#991b1b; font-weight:600; font-size:13px; cursor:pointer;">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.story-form input[type="text"],
.story-form textarea,
.story-form input[type="file"]{max-width:100%; width:100%; box-sizing:border-box;}
.story-form-card{overflow:hidden;}

@media (max-width: 768px){
  .story-form-card{padding:16px !important;}
  .story-form input[type="text"],
  .story-form textarea{font-size:16px !important; padding:10px 12px !important;}
  .story-item{flex-direction:column !important;}
  .story-item-actions{width:100%; justify-content:stretch !important; display:grid !important; grid-template-columns:1fr !important;}
  .story-item-actions a,.story-item-actions button,.story-item-actions form{width:100%;}
  .story-item-actions form button{width:100%;}
}
</style>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
