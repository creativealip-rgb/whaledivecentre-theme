<?php
/**
 * Template Name: Wishlist
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$booking_count = (int) get_user_meta($user_id, '_tmp_bookings_count', true);
$wishlist_ids = get_user_meta($user_id, '_member_wishlist', true);
if (!is_array($wishlist_ids)) $wishlist_ids = [];
$wishlist_ids = array_values(array_unique(array_map('absint', $wishlist_ids)));
$wishlist_tours = !empty($wishlist_ids) ? get_posts([
    'post_type' => 'tour',
    'post__in' => $wishlist_ids,
    'posts_per_page' => -1,
    'orderby' => 'post__in',
    'post_status' => 'publish',
]) : [];

$recommended = new WP_Query([
    'post_type' => ['tour', 'post'],
    'posts_per_page' => 6,
    'post_status' => 'publish',
]);
?>

<style>
.member-btn-primary{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;background:#539294;color:#fff!important;text-decoration:none;border:none;font-weight:700;cursor:pointer;transition:.2s}
.member-btn-primary:hover{background:#355F72}
.member-btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;background:#fff;color:#334155!important;text-decoration:none;border:1px solid #cbd5e1;font-weight:600;cursor:pointer;transition:.2s}
.member-btn-ghost:hover{background:#f8fafc}
.member-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px}
.member-muted{color:#64748b}
.member-grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
.member-grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
@media (max-width:768px){.member-grid-3,.member-grid-2{grid-template-columns:1fr !important}.member-card{padding:14px !important}}
</style>

<div style="margin-bottom: 24px;">
    <h1 class="page-title">❤️ <?php echo esc_html(contenly_tr('Wishlist', 'Wishlist')); ?></h1>
    <p class="page-subtitle"><?php echo esc_html(contenly_tr('Simpan paket favoritmu dan booking saat sudah siap.', 'Save your favourite packages and book them when you are ready.')); ?></p>
</div>

<div class="member-grid-3" style="margin-bottom:22px;">
    <div class="member-card" style="padding:14px;"><div style="font-size:12px;color:#64748b;">Saved Tours</div><div style="font-size:24px;font-weight:800;color:#0f172a;"><?php echo count($wishlist_tours); ?></div></div>
    <div class="member-card" style="padding:14px;"><div style="font-size:12px;color:#64748b;">Price Alerts</div><div style="font-size:24px;font-weight:800;color:#0f172a;">0</div></div>
    <div class="member-card" style="padding:14px;"><div style="font-size:12px;color:#64748b;">Total Trips</div><div style="font-size:24px;font-weight:800;color:#0f172a;"><?php echo max(0, $booking_count); ?></div></div>
</div>

<?php if (!empty($wishlist_tours)) : ?>
<div class="member-card" style="margin-bottom:22px;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px;flex-wrap:wrap;"><h3 style="font-size:18px;font-weight:700;color:#0f172a;"><?php echo esc_html(contenly_tr('Tour Tersimpan', 'Saved Tours')); ?></h3><select id="wishlist-sort" class="member-btn-ghost" style="padding:8px 10px;"><option value="newest"><?php echo esc_html(contenly_tr('Terbaru Ditambahkan', 'Recently Added')); ?></option><option value="az"><?php echo esc_html(contenly_tr('Judul A-Z', 'Title A-Z')); ?></option><option value="price-low"><?php echo esc_html(contenly_tr('Harga Termurah', 'Lowest Price')); ?></option><option value="price-high"><?php echo esc_html(contenly_tr('Harga Tertinggi', 'Highest Price')); ?></option></select></div>
  <div class="member-grid-2">
    <?php foreach ($wishlist_tours as $wt) : $p = (int) get_post_meta($wt->ID, 'price', true); ?>
      <div class="member-card" data-wishlist-card="<?php echo $wt->ID; ?>" data-title="<?php echo esc_attr(strtolower($wt->post_title)); ?>" data-price="<?php echo (int)$p; ?>" data-order="<?php echo $wt->ID; ?>" style="padding:14px;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:6px;"><?php echo esc_html($wt->post_title); ?></div>
        <div style="font-size:12px;color:#64748b;margin-bottom:10px;"><?php echo esc_html(contenly_tr('Mulai dari', 'Starting from')); ?> IDR <?php echo esc_html(number_format($p,0,',','.')); ?></div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <a class="member-btn-primary" href="<?php echo esc_url(get_permalink($wt->ID)); ?>"><?php echo esc_html(contenly_tr('Lihat Detail', 'View Details')); ?></a>
          <button type="button" class="member-btn-ghost wishlist-remove-btn" data-tour-id="<?php echo esc_attr($wt->ID); ?>"><?php echo esc_html(contenly_tr('Hapus', 'Remove')); ?></button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<div id="wishlist-pagination" style="display:flex;gap:8px;justify-content:flex-end;margin-top:12px;"></div>
</div>
<?php else : ?>
<div class="member-card" style="margin-bottom:22px;">
    <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:6px;"><?php echo esc_html(contenly_tr('Belum ada wishlist', 'No wishlist yet')); ?></h3>
    <p style="color:#64748b;margin-bottom:14px;"><?php echo esc_html(contenly_tr('Kamu bisa mulai simpan paket dari halaman tour. Nanti semua yang kamu simpan muncul di sini.', 'You can start saving packages from the tour page. Everything you save will appear here.')); ?></p>
    <a href="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>" class="member-btn-primary">🔎 <?php echo esc_html(contenly_tr('Jelajahi Tour', 'Browse Tours')); ?></a>
</div>
<?php endif; ?>

<div class="member-card">
    <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:12px;"><?php echo esc_html(contenly_tr('Rekomendasi untuk kamu', 'Recommended for you')); ?></h3>
    <?php if ($recommended->have_posts()) : ?>
        <div class="member-grid-2">
            <?php while ($recommended->have_posts()) : $recommended->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="member-btn-ghost" style="display:block;">
                    <div style="font-weight:700;color:#0f172a;margin-bottom:4px;"><?php the_title(); ?></div>
                    <div style="font-size:12px;color:#64748b;"><?php echo esc_html(contenly_tr('Lihat detail paket', 'View package details')); ?></div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <p style="color:#64748b;"><?php echo esc_html(contenly_tr('Belum ada rekomendasi tour saat ini. Coba cek kembali setelah menambah preferensi trip.', 'There are no tour recommendations right now. Check back after adding your trip preferences.')); ?></p>
    <?php endif; ?>
</div>

<script>
jQuery(function($){
  $('#wishlist-sort').on('change', function(){
    const mode = this.value;
    const grid = this.closest('.member-card').querySelector('.member-grid-2');
    if (!grid) return;
    const cards = Array.from(grid.querySelectorAll('[data-wishlist-card]'));
    cards.sort((a,b)=>{
      const ta=a.dataset.title||'', tb=b.dataset.title||'';
      const pa=parseInt(a.dataset.price||'0',10), pb=parseInt(b.dataset.price||'0',10);
      const oa=parseInt(a.dataset.order||'0',10), ob=parseInt(b.dataset.order||'0',10);
      if (mode==='az') return ta.localeCompare(tb);
      if (mode==='price-low') return pa-pb;
      if (mode==='price-high') return pb-pa;
      return ob-oa;
    });
    cards.forEach(c=>grid.appendChild(c));
    window.__wishlistPage = 1;
    renderWishlistPage(window.__wishlistPage);
  });

  
  const PER_PAGE = 6;
  function renderWishlistPage(page){
    const cards = Array.from(document.querySelectorAll('[data-wishlist-card]'));
    const total = cards.length;
    const pages = Math.max(1, Math.ceil(total / PER_PAGE));
    const current = Math.min(Math.max(1, page), pages);

    cards.forEach((c,idx)=>{
      const show = idx >= (current-1)*PER_PAGE && idx < current*PER_PAGE;
      c.style.display = show ? '' : 'none';
    });

    const pag = document.getElementById('wishlist-pagination');
    if (!pag) return;
    pag.innerHTML = '';
    if (pages <= 1) return;

    const mkBtn = (label, target, disabled=false, active=false) => {
      const b = document.createElement('button');
      b.type='button';
      b.textContent=label;
      b.disabled=disabled;
      b.className='member-btn-ghost';
      b.style.padding='6px 10px';
      if (active) { b.style.background='#539294'; b.style.color='#fff'; b.style.borderColor='#539294'; }
      b.addEventListener('click', ()=>{ window.__wishlistPage = target; renderWishlistPage(target); });
      return b;
    };

    pag.appendChild(mkBtn('‹', current-1, current===1));
    for (let i=1;i<=pages;i++) pag.appendChild(mkBtn(String(i), i, false, i===current));
    pag.appendChild(mkBtn('›', current+1, current===pages));
  }

  window.__wishlistPage = 1;
  renderWishlistPage(window.__wishlistPage);

  $('.wishlist-remove-btn').on('click', async function(){
    const btn = this;
    if (!window.contenlyBooking) return;
    const tourId = btn.getAttribute('data-tour-id');
    const fd = new FormData();
    fd.append('action', 'contenly_toggle_wishlist');
    fd.append('tour_id', tourId);
    fd.append('nonce', window.contenlyBooking.nonce || '');
    btn.disabled = true;
    try {
      const res = await fetch(<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>, { method: 'POST', body: fd });
      const data = await res.json();
      if (data.success) {
        const card = document.querySelector('[data-wishlist-card="'+tourId+'"]');
        if (card) card.remove();
        renderWishlistPage(window.__wishlistPage || 1);
        if (typeof showToast === 'function') showToast(<?php echo wp_json_encode(contenly_tr('✅ Dihapus dari wishlist', '✅ Removed from wishlist')); ?>, 'success');
      }
    } catch (e) {
      if (typeof showToast === 'function') showToast(<?php echo wp_json_encode(contenly_tr('❌ Gagal hapus wishlist', '❌ Failed to remove wishlist item')); ?>, 'error');
    }
    btn.disabled = false;
  });
});
</script>

<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>
