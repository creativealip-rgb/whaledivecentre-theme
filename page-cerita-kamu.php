<?php
/**
 * Template Name: Cerita Kamu
 * Dashboard page for member stories (submit + browse)
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$user = wp_get_current_user();
$submitted = isset($_GET['submitted']) && $_GET['submitted'] === '1';
$active_filter = sanitize_text_field($_GET['type'] ?? '');
?>
<div class="wdc-page-head">
    <h1><?php echo contenly_tr('Cerita Kamu', 'Your Stories'); ?></h1>
    <p class="wdc-page-sub"><?php echo contenly_tr('Bagikan pengalaman kursus atau trip diving kamu bersama Whale Dive Centre.', 'Share your course or dive trip experience with Whale Dive Centre.'); ?></p>
</div>


<?php if ($submitted) : ?>
<div style="margin-bottom:18px;padding:14px 16px;border-radius:12px;background:#dcfce7;color:#166534;font-weight:800;font-size:14px;">
    <?php echo contenly_tr('Cerita berhasil dikirim! Admin akan review sebelum dipublish.', 'Story submitted! Admin will review before publishing.'); ?>
</div>
<?php endif; ?>

<!-- Submit form -->
<section style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;margin-bottom:28px;box-shadow:0 12px 34px rgba(15,23,42,.05);">
    <h2 class="wdc-section-title" style="margin:0 0 16px;"><?php echo contenly_tr('Tulis Cerita', 'Write a Story'); ?></h2>
    <form method="post" enctype="multipart/form-data" id="wdc-story-form" style="display:grid;gap:14px;">
        <?php wp_nonce_field('wdc_story_submit', 'wdc_story_nonce'); ?>
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Judul Cerita', 'Story Title'); ?>
            <input type="text" name="story_title" required placeholder="<?php echo contenly_tr('Misal: Pengalaman pertama Open Water di Seribu Islands', 'e.g. My first Open Water experience at Seribu Islands'); ?>" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 14px;font-size:15px;">
        </label>
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Jenis Cerita', 'Story Type'); ?>
            <select name="story_type" style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 14px;font-size:15px;">
                <option value="Cerita Kursus"><?php echo contenly_tr('Cerita Kursus', 'Course Story'); ?></option>
                <option value="Cerita Trip"><?php echo contenly_tr('Cerita Trip', 'Trip Story'); ?></option>
            </select>
        </label>
        <label style="display:grid;gap:6px;font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Upload Gambar', 'Upload Images'); ?> <span style="font-weight:400;color:#94a3b8;">(<?php echo contenly_tr('opsional, bisa lebih dari 1', 'optional, multiple allowed'); ?>)</span>
            <input type="file" name="story_images[]" id="wdc-story-images" accept="image/*" multiple style="border:1px solid #dbe4ea;border-radius:12px;padding:11px 14px;font-size:14px;background:#f8fafc;">
        </label>
        <div id="wdc-image-preview" style="display:none;flex-wrap:wrap;gap:8px;"></div>
        <div style="display:grid;gap:6px;">
            <span style="font-size:13px;font-weight:800;color:#334155;"><?php echo contenly_tr('Cerita Kamu', 'Your Story'); ?></span>
            <?php
            $editor_id = 'wdc_story_editor';
            $settings = [
                'textarea_name' => 'story_content',
                'textarea_rows' => 12,
                'media_buttons' => true,
                'teeny' => false,
                'quicktags' => true,
                'tinymce' => [
                    'toolbar1' => 'formatselect,bold,italic,underline,strikethrough,bullist,numlist,blockquote,link,unlink,wp_adv',
                    'toolbar2' => 'alignleft,aligncenter,alignright,forecolor,backcolor,removeformat,charmap,outdent,indent,undo,redo',
                    'block_formats' => 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4',
                    'paste_as_text' => true,
                    'theme_advanced_statusbar_location' => 'none',
                ],
            ];
            wp_editor('', $editor_id, $settings);
            ?>
        </div>
        <button type="submit" style="border:0;border-radius:999px;background:#06384d;color:#fff;padding:12px 24px;font-weight:900;font-size:15px;cursor:pointer;justify-self:start;"><?php echo contenly_tr('Kirim Cerita', 'Submit Story'); ?></button>
    </form>
</section>

<style>
/* Dashboard wp_editor styling overrides */
#wp-wdc_story_editor-wrap {
    border: 1px solid #dbe4ea !important;
    border-radius: 12px !important;
    overflow: hidden !important;
}
#wp-wdc_story_editor-wrap .wp-editor-tabs {
    background: #f8fafc !important;
    border-bottom: 1px solid #dbe4ea !important;
    padding: 0 8px !important;
}
#wp-wdc_story_editor-wrap .wp-switch-editor {
    border: 0 !important;
    background: transparent !important;
    padding: 10px 14px !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    color: #64748b !important;
}
#wp-wdc_story_editor-wrap .wp-switch-editor.switch-tmce,
#wp-wdc_story_editor-wrap .wp-switch-editor.switch-html {
    background: transparent !important;
}
#wp-wdc_story_editor-wrap .wp-switch-editor.active {
    color: #06384d !important;
    background: #fff !important;
    border-bottom: 2px solid #4cc8ed !important;
}
#wp-wdc_story_editor-wrap .mce-toolbar-grp {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
}
#wp-wdc_story_editor-wrap .mce-btn {
    border-radius: 6px !important;
}
#wp-wdc_story_editor-wrap .mce-quicktags {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 6px !important;
}
#wp-wdc_story_editor-wrap .quicktags-toolbar input {
    border-radius: 6px !important;
    background: #fff !important;
    border: 1px solid #dbe4ea !important;
    padding: 4px 10px !important;
    font-size: 12px !important;
}
#wp-wdc_story_editor-wrap .wp-editor-container {
    border-radius: 0 !important;
}
#wp-wdc_story_editor-wrap iframe {
    min-height: 280px !important;
}
#wp-wdc_story_editor-wrap .wp-editor-area {
    min-height: 280px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 15px !important;
    padding: 12px !important;
}
/* Media buttons */
#wp-wdc_story_editor-wrap .wp-media-buttons {
    background: #f8fafc !important;
    padding: 8px 12px !important;
    border-bottom: 1px solid #e2e8f0 !important;
}
#wp-wdc_story_editor-wrap .wp-media-buttons .button {
    border-radius: 8px !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    padding: 6px 14px !important;
}
/* Hide admin bar and unwanted WP UI */
#wp-wdc_story_editor-wrap .mce-statusbar {
    display: none !important;
}
/* Image preview thumbnails */
#wdc-image-preview img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
}
#wdc-image-preview .wdc-remove-thumb {
    position: relative;
    display: inline-block;
}
#wdc-image-preview .wdc-remove-thumb button {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #dc2626;
    color: #fff;
    border: 2px solid #fff;
    font-size: 11px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    padding: 0;
}
/* Mobile responsive */
@media (max-width: 768px) {
    #wp-wdc_story_editor-wrap .mce-toolbar-grp .mce-flow-layout {
        flex-wrap: wrap !important;
    }
}
</style>

<script>
// Image preview
(function(){
    var input = document.getElementById('wdc-story-images');
    var preview = document.getElementById('wdc-image-preview');
    if (!input || !preview) return;
    
    var dt = new DataTransfer();
    
    input.addEventListener('change', function(){
        preview.innerHTML = '';
        preview.style.display = 'flex';
        
        for (var i = 0; i < input.files.length; i++) {
            (function(file, index){
                var reader = new FileReader();
                reader.onload = function(e) {
                    var wrap = document.createElement('span');
                    wrap.className = 'wdc-remove-thumb';
                    wrap.innerHTML = '<img src="'+e.target.result+'" alt="preview"><button type="button" data-index="'+index+'">✕</button>';
                    preview.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            })(input.files[i], i);
        }
    });
    
    preview.addEventListener('click', function(e){
        var btn = e.target.closest('button[data-index]');
        if (!btn) return;
        e.preventDefault();
        var idx = parseInt(btn.getAttribute('data-index'));
        dt.clear();
        for (var i = 0; i < input.files.length; i++) {
            if (i !== idx) dt.items.add(input.files[i]);
        }
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    });
})();
</script>

<!-- Filter chips -->
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
    <a href="<?php echo esc_url(contenly_localized_url('/cerita-kamu/')); ?>" style="display:inline-flex;padding:10px 20px;border-radius:999px;font-size:14px;font-weight:700;text-decoration:none;<?php echo !$active_filter ? 'background:#06384d;color:#fff;' : 'background:#fff;color:#06384d;border:1px solid #dde3ea;'; ?>transition:all .2s;"><?php echo contenly_tr('Semua', 'All'); ?></a>
    <a href="<?php echo esc_url(add_query_arg('type', 'cerita-kursus', contenly_localized_url('/cerita-kamu/'))); ?>" style="display:inline-flex;padding:10px 20px;border-radius:999px;font-size:14px;font-weight:700;text-decoration:none;<?php echo $active_filter === 'cerita-kursus' ? 'background:#06384d;color:#fff;' : 'background:#fff;color:#06384d;border:1px solid #dde3ea;'; ?>transition:all .2s;"><?php echo contenly_tr('Cerita Kursus', 'Course Stories'); ?></a>
    <a href="<?php echo esc_url(add_query_arg('type', 'cerita-trip', contenly_localized_url('/cerita-kamu/'))); ?>" style="display:inline-flex;padding:10px 20px;border-radius:999px;font-size:14px;font-weight:700;text-decoration:none;<?php echo $active_filter === 'cerita-trip' ? 'background:#06384d;color:#fff;' : 'background:#fff;color:#06384d;border:1px solid #dde3ea;'; ?>transition:all .2s;"><?php echo contenly_tr('Cerita Trip', 'Trip Stories'); ?></a>
</div>

<!-- Published stories -->
<?php
$story_args = ['post_type' => 'wdc_story', 'posts_per_page' => 20, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC', 'meta_query' => [['key' => '_wdc_story_approved', 'value' => '1']]];
if ($active_filter) {
    $story_args['tax_query'] = [['taxonomy' => 'story_type', 'field' => 'slug', 'terms' => $active_filter]];
}
$stories = new WP_Query($story_args);
?>

<?php if ($stories->have_posts()) : ?>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-bottom:28px;">
    <?php while ($stories->have_posts()) : $stories->the_post();
        $types = wp_get_post_terms(get_the_ID(), 'story_type', ['fields' => 'names']);
        $type_name = !is_wp_error($types) && $types ? $types[0] : 'Cerita';
        $author = get_post_meta(get_the_ID(), '_wdc_story_author_name', true) ?: get_the_author();
        $gallery_ids = get_post_meta(get_the_ID(), '_wdc_story_gallery', true);
        $gallery_ids = is_array($gallery_ids) ? $gallery_ids : [];
    ?>
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;overflow:hidden;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <?php if (has_post_thumbnail()) : ?>
        <div style="height:180px;overflow:hidden;">
            <?php the_post_thumbnail('medium_large', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
        </div>
        <?php elseif ($gallery_ids) : ?>
        <div style="height:180px;overflow:hidden;">
            <img src="<?php echo esc_url(wp_get_attachment_url($gallery_ids[0])); ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <?php endif; ?>
        <div style="padding:20px;">
            <span style="display:inline-block;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:#0b617c;background:#e8f8fc;border-radius:999px;padding:5px 10px;margin-bottom:12px;"><?php echo esc_html($type_name); ?></span>
            <h3 style="font-size:18px;font-weight:900;color:#0f172a;margin:0 0 8px;letter-spacing:.03em;"><?php echo esc_html(get_the_title()); ?></h3>
            <p style="color:#64748b;font-size:14px;line-height:1.6;margin:0 0 14px;"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
            <?php if ($gallery_ids && count($gallery_ids) > 1) : ?>
            <div style="display:flex;gap:4px;margin-bottom:12px;">
                <span style="font-size:11px;color:#94a3b8;">📷 <?php echo count($gallery_ids); ?> <?php echo contenly_tr('foto', 'photos'); ?></span>
            </div>
            <?php endif; ?>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:12px;color:#94a3b8;"><?php echo esc_html($author); ?> · <?php echo get_the_date(); ?></span>
                <a href="<?php the_permalink(); ?>" style="font-size:13px;font-weight:800;color:#0b617c;text-decoration:none;"><?php echo contenly_tr('Baca →', 'Read →'); ?></a>
            </div>
        </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
</div>
<?php else : ?>
<div style="text-align:center;padding:60px 20px;background:#fff;border:1px solid #e2e8f0;border-radius:20px;">
    <div style="font-size:48px;margin-bottom:16px;">📖</div>
    <h3 style="font-size:20px;color:#0f172a;margin-bottom:8px;"><?php echo contenly_tr('Belum ada cerita', 'No stories yet'); ?></h3>
    <p style="color:#64748b;"><?php echo contenly_tr('Jadilah yang pertama berbagi pengalaman dive kamu!', 'Be the first to share your dive experience!'); ?></p>
</div>
<?php endif; ?>


<!-- Own submissions (pending/approved) -->
<?php
$own_args = [
    'post_type' => 'wdc_story',
    'posts_per_page' => 10,
    'post_status' => ['pending', 'publish', 'draft'],
    'author' => $user_id,
    'orderby' => 'date',
    'order' => 'DESC',
];
$own = new WP_Query($own_args);
if ($user_id && $own->have_posts()) :
?>
<section style="margin-top:10px;margin-bottom:28px;background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:20px;box-shadow:0 12px 34px rgba(15,23,42,.05);">
  <h2 style="font-size:18px;font-weight:900;color:#0f172a;margin:0 0 14px;"><?php echo contenly_tr('Kiriman saya', 'My submissions'); ?></h2>
  <div style="display:grid;gap:10px;">
    <?php while ($own->have_posts()) : $own->the_post();
      $approved = get_post_meta(get_the_ID(), '_wdc_story_approved', true) === '1';
      $st = get_post_status();
      $label = ($approved && $st === 'publish')
        ? contenly_tr('Live di Blog', 'Live on Blog')
        : contenly_tr('Menunggu review admin', 'Waiting for admin review');
      $color = ($approved && $st === 'publish') ? '#065f46' : '#9a3412';
      $bg = ($approved && $st === 'publish') ? '#ecfdf5' : '#fff7ed';
    ?>
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:center;padding:12px 14px;border:1px solid #e2e8f0;border-radius:14px;">
      <div>
        <div style="font-weight:800;color:#0f172a;"><?php echo esc_html(get_the_title()); ?></div>
        <div style="font-size:12px;color:#94a3b8;margin-top:4px;"><?php echo esc_html(get_the_date()); ?></div>
      </div>
      <span style="display:inline-flex;padding:6px 10px;border-radius:999px;background:<?php echo esc_attr($bg); ?>;color:<?php echo esc_attr($color); ?>;font-size:12px;font-weight:800;white-space:nowrap;"><?php echo esc_html($label); ?></span>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
