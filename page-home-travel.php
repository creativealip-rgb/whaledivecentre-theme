<?php
/**
 * Template Name: Travel Homepage
 */

get_header();
$wishlist_ids = [];
if (is_user_logged_in()) {
    $wishlist_ids = get_user_meta(get_current_user_id(), '_member_wishlist', true);
    if (!is_array($wishlist_ids)) {
        $wishlist_ids = [];
    }
    $wishlist_ids = array_map('absint', $wishlist_ids);
}
?>

<!-- Hero Section -->
<?php
$wdc_content = function_exists('wdc_get_content_settings') ? wdc_get_content_settings() : [];
$hero_image_id = absint($wdc_content['hero_image_id'] ?? 0);
$hero_image = $hero_image_id ? wp_get_attachment_image_url($hero_image_id, 'full') : get_the_post_thumbnail_url(get_the_ID(), 'full');
if (!$hero_image) {
    $legacy_hero_image = wp_get_attachment_url(175);
    $hero_image = $legacy_hero_image ?: '';
}
$hero_eyebrow = contenly_tr($wdc_content['hero_eyebrow_id'] ?? 'Curated trip planner untuk domestik & internasional', $wdc_content['hero_eyebrow_en'] ?? 'Curated trip planner for domestic and international journeys');
$hero_title = contenly_tr($wdc_content['hero_title_id'] ?? 'Liburan Rapi, Berangkat Pasti', $wdc_content['hero_title_en'] ?? 'Plan Clearly, Travel Confidently');
$hero_text = contenly_tr($wdc_content['hero_text_id'] ?? 'Dari trip private sampai open trip, kami bantu pilih itinerary yang pas budget, nyaman, dan minim drama.', $wdc_content['hero_text_en'] ?? 'From private trips to open departures, we help you choose itineraries that fit your budget, stay comfortable, and keep the journey hassle-free.');
$hero_bg = $hero_image ? 'url(' . esc_url($hero_image) . ')' : 'linear-gradient(135deg, #355F72 0%, #539294 72%, #E5A736 100%)';
?>
<div class="gt-hero" style="background: <?php echo $hero_bg; ?> center center / cover no-repeat;">
    <div class="gt-hero-overlay"></div>
    <div class="gt-hero-inner">
        <div class="gt-hero-copy">
            <span class="gt-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></span>
            <h1><?php echo esc_html($hero_title); ?></h1>
            <p><?php echo esc_html($hero_text); ?></p>
        </div>

        <div class="gt-search-card" id="gt-search-card">
            <div class="gt-search-topline">
                <span><?php echo esc_html(contenly_tr('Pilih layanan utama yang paling sering dicari traveler', 'Choose the travel service people ask for most often')); ?></span>
                <span><?php echo esc_html(contenly_tr('Isi kebutuhan trip lebih cepat, flow booking lebih jelas', 'Share your trip needs faster with a clearer booking flow')); ?></span>
            </div>
            <div class="gt-tabs" role="tablist" aria-label="<?php echo esc_attr(contenly_tr('Layanan perjalanan', 'Travel services')); ?>">
                <button class="gt-tab active" data-tab="tour">
                    <span class="gt-tab-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M4 19h16M6 19l2-9h8l2 9M9 10V7a3 3 0 1 1 6 0v3"/></svg></span>
                    <span>Tour Packages</span>
                </button>
                <button class="gt-tab" data-tab="flight">
                    <span class="gt-tab-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M2.5 14.5l19-8-4 11-5.5-2.3L8 20l.3-5.1-5.8-2.4z"/></svg></span>
                    <span>Flights</span>
                </button>
                <button class="gt-tab" data-tab="hotel">
                    <span class="gt-tab-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M3 20V6h8v5h10v9M3 13h8M7 9h.01"/></svg></span>
                    <span>Hotels</span>
                </button>
                <button class="gt-tab" data-tab="train">
                    <span class="gt-tab-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M7 4h10a2 2 0 0 1 2 2v8a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4V6a2 2 0 0 1 2-2zM8 21l2-3m6 3-2-3M8 9h3m5 0h-3"/></svg></span>
                    <span>Train</span>
                </button>
                <button class="gt-tab" data-tab="bus">
                    <span class="gt-tab-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M6 5h12a2 2 0 0 1 2 2v7a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V7a2 2 0 0 1 2-2zM7 10h10M8 21l1.5-3M16 21 14.5 18"/></svg></span>
                    <span>Bus & Travel</span>
                </button>
            </div>
            <form class="gt-panel active" data-panel="tour" method="get" action="<?php echo esc_url(contenly_localized_url('/tour-packages/')); ?>">
                <div class="gt-panel-shell">
                    <div class="gt-grid">
                        <div><label><?php echo esc_html(contenly_tr('Destinasi', 'Destination')); ?></label><input type="text" name="q_destination" placeholder="<?php echo esc_attr(contenly_tr('Contoh: Bali, Phuket, Jepang', 'Example: Bali, Phuket, Japan')); ?>" value="<?php echo esc_attr(isset($_GET['q_destination']) ? $_GET['q_destination'] : ''); ?>" /></div>
                        <div><label><?php echo esc_html(contenly_tr('Tanggal Berangkat', 'Departure Date')); ?></label><input type="date" name="q_date" value="<?php echo esc_attr(isset($_GET['q_date']) ? $_GET['q_date'] : ''); ?>" /></div>
                        <div><label><?php echo esc_html(contenly_tr('Durasi', 'Duration')); ?></label><select name="q_duration"><option value=""><?php echo esc_html(contenly_tr('Semua Durasi', 'All Durations')); ?></option><option value="3" <?php selected((isset($_GET['q_duration']) ? $_GET['q_duration'] : ''), '3'); ?>><?php echo esc_html(contenly_tr('3 Hari 2 Malam', '3 Days 2 Nights')); ?></option><option value="4" <?php selected((isset($_GET['q_duration']) ? $_GET['q_duration'] : ''), '4'); ?>><?php echo esc_html(contenly_tr('4 Hari 3 Malam', '4 Days 3 Nights')); ?></option><option value="5" <?php selected((isset($_GET['q_duration']) ? $_GET['q_duration'] : ''), '5'); ?>><?php echo esc_html(contenly_tr('5 Hari 4 Malam', '5 Days 4 Nights')); ?></option></select></div>
                        <div><label><?php echo esc_html(contenly_tr('Traveler', 'Travellers')); ?></label><select name="q_traveler"><option value=""><?php echo esc_html(contenly_tr('Semua Traveler', 'All Travellers')); ?></option><option value="1" <?php selected((isset($_GET['q_traveler']) ? $_GET['q_traveler'] : ''), '1'); ?>><?php echo esc_html(contenly_tr('1 Orang', '1 Traveller')); ?></option><option value="2" <?php selected((isset($_GET['q_traveler']) ? $_GET['q_traveler'] : ''), '2'); ?>><?php echo esc_html(contenly_tr('2 Orang', '2 Travellers')); ?></option><option value="3-4" <?php selected((isset($_GET['q_traveler']) ? $_GET['q_traveler'] : ''), '3-4'); ?>><?php echo esc_html(contenly_tr('3-4 Orang', '3-4 Travellers')); ?></option><option value="5+" <?php selected((isset($_GET['q_traveler']) ? $_GET['q_traveler'] : ''), '5+'); ?>><?php echo esc_html(contenly_tr('5+ Orang', '5+ Travellers')); ?></option></select></div>
                    </div>
                    <div class="gt-panel-actions">
                        <div class="gt-panel-note"><?php echo esc_html(contenly_tr('Mulai dari paket tour yang paling sesuai, lalu tim kami bantu rapihin detail keberangkatan.', 'Start with the package that fits best, then let our team help refine the departure details.')); ?></div>
                        <button class="gt-cta" type="submit"><?php echo esc_html(contenly_tr('Cari Paket Tour', 'Search Tour Packages')); ?></button>
                    </div>
                </div>
            </form>

            <div class="gt-panel" data-panel="flight">
                <div class="gt-panel-shell">
                    <div class="gt-grid">
                        <div><label>Dari</label><input type="text" placeholder="Jakarta (CGK)" /></div>
                        <div><label>Ke</label><input type="text" placeholder="Denpasar (DPS)" /></div>
                        <div><label>Tanggal Pergi</label><input type="date" /></div>
                        <div><label>Penumpang</label><select><option>1 Dewasa</option><option>2 Dewasa</option><option>2 Dewasa, 1 Anak</option></select></div>
                    </div>
                    <div class="gt-panel-actions gt-panel-actions-inline">
                        <div class="gt-panel-note">Cocok buat cek rute populer dulu sebelum lanjut request bundling flight + hotel atau trip full itinerary.</div>
                        <button class="gt-cta" type="button">Cari Penerbangan</button>
                    </div>
                </div>
            </div>

            <div class="gt-panel" data-panel="hotel">
                <div class="gt-panel-shell">
                    <div class="gt-grid">
                        <div><label>Kota / Hotel</label><input type="text" placeholder="Bandung / Yogyakarta" /></div>
                        <div><label>Check-in</label><input type="date" /></div>
                        <div><label>Check-out</label><input type="date" /></div>
                        <div><label>Tamu & Kamar</label><select><option>2 Tamu, 1 Kamar</option><option>4 Tamu, 2 Kamar</option><option>Family Room</option></select></div>
                    </div>
                    <div class="gt-panel-actions gt-panel-actions-inline">
                        <div class="gt-panel-note">Lebih enak buat compare area nginep dulu, terutama kalau trip-nya mau disesuaiin sama budget dan itinerary.</div>
                        <button class="gt-cta" type="button">Cari Hotel</button>
                    </div>
                </div>
            </div>

            <div class="gt-panel" data-panel="train">
                <div class="gt-panel-shell">
                    <div class="gt-grid">
                        <div><label>Stasiun Asal</label><input type="text" placeholder="Gambir" /></div>
                        <div><label>Stasiun Tujuan</label><input type="text" placeholder="Yogyakarta" /></div>
                        <div><label>Tanggal</label><input type="date" /></div>
                        <div><label>Penumpang</label><select><option>1 Penumpang</option><option>2 Penumpang</option><option>3 Penumpang</option></select></div>
                    </div>
                    <div class="gt-panel-actions gt-panel-actions-inline">
                        <div class="gt-panel-note">Pas buat short trip antarkota yang jadwalnya udah jelas dan pengen opsi keberangkatan yang praktis.</div>
                        <button class="gt-cta" type="button">Cari Kereta</button>
                    </div>
                </div>
            </div>

            <div class="gt-panel" data-panel="bus">
                <div class="gt-panel-shell">
                    <div class="gt-grid">
                        <div><label>Kota Asal</label><input type="text" placeholder="Jakarta" /></div>
                        <div><label>Kota Tujuan</label><input type="text" placeholder="Bandung" /></div>
                        <div><label>Tanggal</label><input type="date" /></div>
                        <div><label>Kursi</label><select><option>1 Kursi</option><option>2 Kursi</option><option>3+ Kursi</option></select></div>
                    </div>
                    <div class="gt-panel-actions gt-panel-actions-inline">
                        <div class="gt-panel-note">Ideal buat perjalanan rombongan kecil, city transfer, atau kebutuhan travel darat yang lebih fleksibel.</div>
                        <button class="gt-cta" type="button">Cari Bus & Travel</button>
                    </div>
                </div>
            </div>

            <div class="gt-panel" data-panel="transfer">
                <div class="gt-grid">
                    <div><label>Dari Bandara</label><input type="text" placeholder="Contoh: Soekarno-Hatta (CGK)" /></div>
                    <div><label>Ke Area / Alamat</label><input type="text" placeholder="Contoh: Kuta, Bali" /></div>
                    <div><label>Tanggal Jemput</label><input type="date" /></div>
                    <div><label>Jam Jemput</label><input type="time" /></div>
                </div>
                <button class="gt-cta">Cari Airport Transfer</button>
            </div>

            <div class="gt-panel" data-panel="rental">
                <div class="gt-grid">
                    <div><label>Lokasi Sewa</label><input type="text" placeholder="Contoh: Jakarta Selatan" /></div>
                    <div><label>Tanggal Mulai</label><input type="date" /></div>
                    <div><label>Tanggal Selesai</label><input type="date" /></div>
                    <div><label>Tipe</label><select><option>Tanpa Supir</option><option>Dengan Supir</option></select></div>
                </div>
                <button class="gt-cta">Cari Car Rental</button>
            </div>
        </div>

        <div class="gt-stats">
            <div><strong>50+</strong><span>Destinasi</span></div>
            <div><strong>100+</strong><span>Paket Tour</span></div>
            <div><strong>10K+</strong><span>Traveler</span></div>
        </div>
    </div>
</div>

<style>
html,body{max-width:100%;overflow-x:hidden}
.gt-hero{position:relative;min-height:auto;padding:132px 20px 48px;display:flex;align-items:center;color:#fff;overflow:hidden;overflow-x:clip}
.gt-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at top center,rgba(255,255,255,.12),transparent 34%),linear-gradient(180deg,rgba(6,12,24,.18) 0%,rgba(6,12,24,.38) 28%,rgba(6,12,24,.58) 100%)}
.gt-hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(2,6,23,.12) 0%,rgba(2,6,23,.20) 22%,rgba(2,6,23,.52) 62%,rgba(2,6,23,.72) 100%)}
.gt-hero-inner{position:relative;z-index:2;max-width:1240px;margin:0 auto;width:100%;text-align:center}
.gt-hero-copy{max-width:780px;margin:0 auto 18px}
.gt-hero-eyebrow{display:inline-flex;align-items:center;gap:8px;min-height:30px;padding:0 13px;border-radius:999px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.24);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);font-size:10.5px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#EEF5F4;margin-bottom:12px}
.gt-hero-inner h1{font-size:clamp(36px,4.6vw,52px);margin:0 0 12px;line-height:1.04;letter-spacing:-.03em;color:#fff !important;-webkit-text-fill-color:#fff !important;text-shadow:0 12px 32px rgba(2,6,23,.22)}
.gt-hero-copy>p{max-width:640px;margin:0 auto;font-size:16px;line-height:1.65;color:rgba(241,245,249,.94);text-shadow:0 10px 26px rgba(2,6,23,.18)}
.gt-mobile-form-toggle{display:none}
.gt-search-card{position:relative;background:linear-gradient(180deg,rgba(255,255,255,.20) 0%,rgba(255,255,255,.13) 100%);backdrop-filter:blur(18px) saturate(130%);-webkit-backdrop-filter:blur(18px) saturate(130%);border:1px solid rgba(255,255,255,.22);border-radius:26px;padding:13px;box-shadow:0 20px 38px rgba(2,6,23,.22),inset 0 1px 0 rgba(255,255,255,.22);text-align:left;max-width:1040px;margin:0 auto}
.gt-search-card::before{content:"";position:absolute;inset:0;border-radius:26px;padding:1px;background:linear-gradient(135deg,rgba(255,255,255,.34),rgba(255,255,255,.06) 42%,rgba(255,255,255,.2));-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none}
.gt-search-topline{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;padding:0 2px 6px;color:rgba(241,245,249,.84);font-size:11px;line-height:1.45}
.gt-search-topline span:last-child{font-weight:700;color:#f8fafc}
.gt-tabs{display:flex;gap:7px;overflow:auto;padding:0 2px 6px;scrollbar-width:none}
.gt-tabs::-webkit-scrollbar{display:none}
.gt-tab{white-space:nowrap;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.08);color:rgba(241,245,249,.86);padding:8px 12px;border-radius:999px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:all .25s ease;box-shadow:inset 0 1px 0 rgba(255,255,255,.08)}
.gt-tab:hover{background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);color:#fff}
.gt-tab-icon{width:16px;height:16px;display:grid;place-items:center;opacity:.8}
.gt-tab-icon svg{width:16px;height:16px;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.gt-tab.active{background:rgba(255,255,255,.96);color:#0f172a;border-color:rgba(255,255,255,.96);box-shadow:0 8px 18px rgba(15,23,42,.15)}
.gt-panel{display:none}
.gt-panel.active{display:block}
.gt-panel-shell{display:grid;gap:11px}
.gt-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px}
.gt-grid label{display:block;font-size:10.5px;font-weight:800;color:rgba(248,250,252,.78);margin:0 0 5px;letter-spacing:.07em;text-transform:uppercase}
.gt-grid input,.gt-grid select{width:100%;height:46px;border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:0 14px;background:rgba(255,255,255,.95);color:#0f172a;box-shadow:inset 0 1px 0 rgba(255,255,255,.82),0 8px 14px rgba(15,23,42,.06)}
.gt-grid input:focus,.gt-grid select:focus{outline:none;border-color:#D8E8E8;box-shadow:0 0 0 4px rgba(216,232,232,.28)}
.gt-panel-actions{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;padding-top:0}
.gt-panel-actions-inline{align-items:flex-end}
.gt-panel-note{max-width:520px;color:rgba(241,245,249,.78);font-size:11.5px;line-height:1.5}
.gt-cta{margin-top:0;height:46px;border:0;border-radius:14px;padding:0 20px;background:linear-gradient(135deg,#355F72 0%,#539294 52%,#E5A736 100%);color:#f8fbff;font-weight:800;letter-spacing:.01em;cursor:pointer;box-shadow:0 14px 26px rgba(83,146,148,.30),inset 0 1px 0 rgba(255,255,255,.3);transition:transform .22s ease,box-shadow .22s ease,filter .22s ease}
.gt-cta:hover{transform:translateY(-1px);box-shadow:0 22px 44px rgba(83,146,148,.42),inset 0 1px 0 rgba(255,255,255,.36);filter:saturate(1.04)}
.gt-cta:active{transform:translateY(0);box-shadow:0 12px 20px rgba(83,146,148,.28)}
.gt-stats{display:flex;justify-content:center;flex-wrap:wrap;margin-top:28px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.16);border-radius:20px;overflow:hidden;max-width:760px;margin-left:auto;margin-right:auto;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);box-shadow:0 18px 30px rgba(2,6,23,.16)}
.gt-stats > div{padding:16px 26px;min-width:180px;position:relative}
.gt-stats > div:not(:last-child)::after{content:'';position:absolute;right:0;top:16px;bottom:16px;width:1px;background:rgba(255,255,255,.12)}
.gt-stats strong{display:block;font-size:34px;line-height:1;font-weight:800;color:#fff}.gt-stats span{font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:rgba(241,245,249,.74)}
.home-section{padding:88px 20px}
.home-section.soft{background:#f8fafc}
.home-section.white{background:#fff}
.home-shell{max-width:1200px;margin:0 auto}
.section-kicker{display:inline-flex;align-items:center;justify-content:center;min-height:32px;padding:0 14px;border-radius:999px;background:#EEF5F4;color:#355F72;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:16px}
.section-heading{font-size:clamp(32px,4vw,42px);font-weight:800;line-height:1.12;letter-spacing:-.03em;color:#0f172a;margin:0 0 14px;text-align:center}
.section-subheading{max-width:720px;margin:0 auto 42px;font-size:17px;line-height:1.8;color:#64748b;text-align:center}
.premium-card{background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);border:1px solid #DCE9E6;border-radius:28px;box-shadow:0 22px 44px rgba(15,23,42,.06)}
.premium-pill-link{display:inline-flex;align-items:center;justify-content:center;min-height:46px;padding:0 18px;border-radius:999px;background:linear-gradient(135deg,#355F72 0%,#539294 52%,#E5A736 100%);color:#fff;text-decoration:none;font-weight:700;box-shadow:0 14px 28px rgba(83,146,148,.26)}
.premium-pill-link:hover{transform:translateY(-1px);box-shadow:0 18px 34px rgba(83,146,148,.32)}
.tour-row-section{display:grid;gap:18px;margin-top:34px}
.tour-row-section:first-of-type{margin-top:0}
.trip-style-inline-summary{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin:0 0 22px;padding:0 4px}
.trip-style-inline-main{display:flex;align-items:center;gap:10px;flex-wrap:wrap;min-width:0;flex:1 1 auto}
.trip-style-inline-copy{display:flex;align-items:center;gap:8px;flex-wrap:wrap;color:#334155}
.trip-style-chip-bar{display:flex;align-items:center;gap:8px;flex-wrap:wrap;min-width:0}
.trip-style-chip{padding:7px 11px;border-radius:999px;border:1px solid #cbd5e1;background:#fff;color:#334155;font-size:13px;font-weight:600;line-height:1.2;cursor:pointer;transition:.2s}
.trip-style-chip.active,.trip-style-chip:hover{background:#539294;color:#fff;border-color:#539294}
.trip-style-row-label{display:inline-flex;align-items:center;min-height:28px;padding:0 10px;border-radius:999px;background:#EEF5F4;color:#355F72;font-size:11px;font-weight:800;letter-spacing:.04em;text-transform:uppercase}
.trip-style-summary-title{font-size:14px;font-weight:700;color:#0f172a}
.trip-style-summary-meta{display:inline-flex;align-items:center;height:26px;padding:0 9px;border-radius:999px;background:#E3EEEC;color:#355F72;font-size:11px;font-weight:800}
.trip-style-inline-summary a{display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 13px;border-radius:999px;background:#fff;border:1px solid #cbd5e1;color:#539294;text-decoration:none;font-size:13px;font-weight:700;white-space:nowrap}
.tour-row-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
.home-tour-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;height:100%;box-shadow:0 8px 20px rgba(15,23,42,.08);transition:transform .22s ease, box-shadow .22s ease}
.home-tour-card:hover{transform:translateY(-3px);box-shadow:0 16px 30px rgba(15,23,42,.12) !important}
.home-tour-card-media{display:block;height:158px;overflow:hidden;flex:0 0 158px;background:#DCE9E6}
.home-tour-card-media img{width:100%;height:100%;object-fit:cover;display:block}
.home-tour-card-body{padding:12px !important;display:flex;flex-direction:column;gap:6px;flex:1;min-height:0}
.home-tour-card-title{margin:0 !important;font-size:16px !important;line-height:1.3 !important;min-height:42px !important;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.home-tour-card-title a{color:#0f172a !important;text-decoration:none}
.home-tour-card-meta{display:grid;gap:0 !important;margin-bottom:0 !important;color:#64748b;font-size:12px;line-height:1.45;min-height:auto !important;align-content:start}
.home-tour-card-meta p{margin:0;min-height:16px;display:flex;align-items:center;gap:6px}
.home-tour-card-meta-dot{width:6px;height:6px;border-radius:999px;background:#B7D3D3;display:inline-block;flex:0 0 auto}
.home-tour-card-price{display:grid;gap:0}
.home-tour-card-price-label{font-size:11px;color:#64748b;margin-top:auto}
.home-tour-card-price-value{font-size:20px;line-height:1.1;font-weight:800;color:#355F72;letter-spacing:-.01em}
.home-tour-card-footer{margin-top:auto;display:grid;gap:6px}
.home-tour-card-actions{display:grid;grid-template-columns:1fr auto;gap:7px;align-items:center}
.home-tour-card-cta{display:block;text-align:center;text-decoration:none;background:linear-gradient(135deg,#539294,#539294);color:#fff;border-radius:999px;font-weight:700;font-size:12px;line-height:1.2;min-height:34px;padding:8px 11px;white-space:nowrap;box-shadow:none !important}
.home-tour-card-cta:hover{filter:saturate(1.04)}
.home-tour-wishlist-btn{height:34px;min-width:36px;border:1px solid #cbd5e1;border-radius:999px;background:#fff;cursor:pointer;font-size:14px;line-height:1;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
.home-tour-wishlist-btn:hover{transform:translateY(-1px);box-shadow:0 8px 18px rgba(15,23,42,.08);border-color:#94a3b8}
.home-tour-empty{display:none;grid-column:1 / -1;text-align:center;padding:28px;border:1px dashed #cbd5e1;border-radius:20px;background:#fff;color:#64748b;font-size:16px}

@media (min-width: 1025px){
  .trip-style-inline-summary{flex-wrap:nowrap}
  .trip-style-inline-main{flex-wrap:nowrap}
  .trip-style-inline-copy{flex-wrap:nowrap}
  .trip-style-chip-bar{flex-wrap:nowrap}
}
@media (max-width: 1200px){.tour-row-grid{grid-template-columns:repeat(3,minmax(0,1fr)) !important}}
@media (max-width: 1024px){
  .gt-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .tour-row-grid{grid-template-columns:repeat(2,minmax(0,1fr)) !important}
  .trip-style-inline-summary{align-items:flex-start}
}
@media (max-width: 1024px){
  .gt-hero{min-height:auto;align-items:flex-start;overflow:hidden;overflow-y:visible;overflow-x:clip;padding:84px 14px 14px}
  .gt-hero-inner{width:100%;max-width:100%;text-align:left;padding:0;margin:0 auto}
  .gt-hero-copy{margin:0 0 12px}
  .gt-hero-eyebrow{min-height:26px;padding:0 10px;font-size:9.5px;letter-spacing:.06em;margin-bottom:8px}
  .gt-hero-inner h1{font-size:clamp(26px,6.8vw,31px);line-height:1.05;margin:0 0 8px}
  .gt-hero-copy>p{font-size:12.5px;line-height:1.52;margin:0;max-width:100%}

  .gt-mobile-form-toggle{display:none !important}
  .gt-search-card{width:100%;max-width:100%;min-width:0;box-sizing:border-box;padding:11px;border-radius:18px;margin:0}
  .gt-search-card::before{display:none}
  .gt-search-card.is-collapsed .gt-panel.active,
  .gt-search-card.is-collapsed .gt-cta{display:none !important}
  .gt-search-topline{display:block;padding:0 0 8px;font-size:11px;line-height:1.4}
  .gt-search-topline span{display:block}
  .gt-search-topline span + span{margin-top:1px}
  .gt-secondary-services{padding:0 0 10px;font-size:11px}

  /* Traveloka-like mobile service selector */
  .gt-tabs{display:flex;gap:6px;overflow-x:auto;overflow-y:hidden;padding:0 0 8px;scroll-snap-type:x proximity;-webkit-overflow-scrolling:touch;scrollbar-width:none}
  .gt-tabs::-webkit-scrollbar{display:none}
  .gt-tab{flex:0 0 auto;min-width:auto;justify-content:center;align-items:center;white-space:nowrap;padding:7px 10px;min-height:34px;font-size:11px;line-height:1.15;text-align:center;border-radius:999px;scroll-snap-align:start}
  .gt-tab span{display:block}
  .gt-tab-icon{display:none}

  .gt-grid{grid-template-columns:1fr;gap:6px}
  .gt-grid label{font-size:10.5px;margin-bottom:4px}
  .gt-grid input,.gt-grid select,.gt-grid .choices__inner{height:42px!important;min-height:42px!important;font-size:13px!important}
  .gt-panel-actions{gap:8px}
  .gt-panel-note{max-width:none;font-size:11px;line-height:1.45}
  .gt-cta{height:42px;width:100%;margin-top:0;font-size:12.5px}

  .gt-stats{display:none}
  .trip-style-inline-summary{padding:0;gap:10px}
  .trip-style-inline-main{width:100%;gap:10px}
  .trip-style-inline-copy{width:100%}
  .trip-style-chip-bar{justify-content:flex-start;flex-wrap:nowrap;overflow-x:auto;padding-bottom:6px;scrollbar-width:none;width:100%;order:3}
  .trip-style-chip-bar::-webkit-scrollbar{display:none}
  .trip-style-chip{flex:0 0 auto}
  .trip-style-inline-summary a{width:100%}

  #home-tours-grid{grid-template-columns:1fr !important}
  .home-tour-card-media{height:144px;flex-basis:144px}
  .home-tour-card-body{padding:11px !important;gap:5px}
  .home-tour-card-title{min-height:auto !important;font-size:15px !important;line-height:1.28 !important}
  .home-tour-card-meta{min-height:auto !important;font-size:11.5px;line-height:1.4}
  .home-tour-card-price-label{font-size:10.5px}
  .home-tour-card-price-value{font-size:18px;line-height:1.08}
  .home-tour-card-footer{gap:5px}
  .home-tour-card-actions{grid-template-columns:1fr auto;gap:6px}
  .home-tour-card-cta{min-height:32px;font-size:11.5px;padding:7px 10px}
  .home-tour-wishlist-btn{height:32px;min-width:34px;font-size:13px}
}
/* Premium controls (stable custom) */
.gt-grid select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%230f172a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:40px}
.gt-grid input[type="date"],.gt-grid input[type="time"]{appearance:none;-webkit-appearance:none;position:relative;padding-right:42px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%230f172a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:16px}
.gt-grid input[type="time"]{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%230f172a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='9'/%3E%3Cpolyline points='12 7 12 12 15.5 14'/%3E%3C/svg%3E")}

/* Flatpickr */
.flatpickr-calendar{border:1px solid rgba(148,163,184,.25)!important;border-radius:16px!important;box-shadow:0 24px 48px rgba(15,23,42,.30)!important;overflow:hidden;font-family:inherit}
.flatpickr-months{background:linear-gradient(120deg,#355F72,#539294);color:#EEF5F4;padding:4px 0}
.flatpickr-current-month{display:flex;align-items:center;justify-content:center;gap:8px;padding-top:6px}
.flatpickr-current-month .flatpickr-monthDropdown-months,.flatpickr-current-month input.cur-year,.flatpickr-current-month .cur-month{color:#EEF5F4!important;font-weight:700}
.flatpickr-current-month .flatpickr-monthDropdown-months,.flatpickr-current-month .cur-month{background:transparent!important;border:none!important;appearance:none;-webkit-appearance:none;padding-right:0}
.flatpickr-current-month .flatpickr-monthDropdown-months option{color:#0f172a}
.flatpickr-current-month .numInputWrapper{width:58px}
.flatpickr-current-month .numInputWrapper span{border-color:rgba(255,255,255,.45)!important}
.flatpickr-weekdays{background:#f8fbff}
.flatpickr-weekday{color:#4b5f7d!important;font-weight:700}
.flatpickr-day{border-radius:10px!important;max-width:34px;height:34px;line-height:34px;border-color:transparent!important}
.flatpickr-day:hover{background:#EEF5F4!important;border-color:transparent!important}
.flatpickr-day.selected,.flatpickr-day.startRange,.flatpickr-day.endRange{background:#539294!important;border-color:#539294!important;color:#fff!important}
.flatpickr-day.today{border-color:transparent!important;background:transparent!important;color:#334155!important}

/* Choices.js dropdown */
.gt-grid .choices{margin-bottom:0}
.gt-grid .choices__inner{display:flex;align-items:center;min-height:46px!important;height:46px!important;border:1px solid rgba(255,255,255,.25)!important;border-radius:12px!important;background:rgba(255,255,255,.97)!important;color:#0f172a!important;padding:0 40px 0 12px!important;box-shadow:inset 0 1px 0 rgba(255,255,255,.92)!important;transition:border-color .2s ease, box-shadow .2s ease}
.gt-grid .choices.is-focused .choices__inner{border-color:#B7D3D3!important;box-shadow:0 0 0 3px rgba(183,211,211,.30)!important}
.gt-grid .choices__list--single{padding:0!important}
.gt-grid .choices[data-type*="select-one"]:after{border-color:#0f172a transparent transparent!important;right:14px;margin-top:-3px}
.gt-grid .choices.is-open[data-type*="select-one"]:after{border-color:transparent transparent #0f172a!important;margin-top:-8px}
.gt-grid .choices__list--dropdown{margin-top:8px;border:1px solid rgba(148,163,184,.30)!important;border-radius:14px!important;background:rgba(255,255,255,.985)!important;box-shadow:0 22px 44px rgba(15,23,42,.24)!important;overflow:hidden}
.gt-grid .choices__list--dropdown .choices__item{font-size:14px;padding:11px 13px;color:#0f172a}
.gt-grid .choices__list--dropdown .choices__item--selectable.is-highlighted{background:#DCE9E6!important;color:#355F72}
.gt-grid .choices__list--dropdown .choices__item--selectable.is-selected{font-weight:700;color:#355F72}
@media(max-width:760px){
  .home .wd-course-slider{max-width:100vw;overflow-x:auto;overflow-y:hidden}
  .home .wd-course-slider .wd-course-grid{min-width:0!important;width:max-content!important}
  .home .wd-course-slider .wd-course-card{flex-basis:calc(100vw - 72px)!important;max-width:318px!important}
  .home .wd-course-slider .wd-course-card a{max-width:100%;white-space:normal;box-sizing:border-box}
  .home .wd-equipment-grid,.home .wd-home-equip-card,.home .wd-home-equip-photo{max-width:100%!important;overflow:hidden!important}
  .home .wd-home-equip-photo img{width:100%!important;max-width:100%!important;height:100%!important;object-fit:cover!important;transform:none!important;left:auto!important;right:auto!important;display:block!important}
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const tabs = document.querySelectorAll('.gt-tab');
  const panels = document.querySelectorAll('.gt-panel');
  const searchCard = document.querySelector('#gt-search-card');

  tabs.forEach(tab=>{
    tab.addEventListener('click', ()=>{
      const key = tab.getAttribute('data-tab');
      tabs.forEach(t=>t.classList.remove('active'));
      tab.classList.add('active');
      panels.forEach(p=>p.classList.toggle('active', p.getAttribute('data-panel')===key));

      if (searchCard && window.innerWidth <= 768) {
        const wasCollapsed = searchCard.classList.contains('is-collapsed');
        searchCard.classList.remove('is-collapsed');
        // If form was hidden, bring opened form into viewport so user sees it instantly.
        if (wasCollapsed) {
          setTimeout(() => {
            searchCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 40);
        }
      }
    });
  });

  if (window.Choices) {
    document.querySelectorAll('.gt-grid select').forEach((el)=>{
      if (!el.dataset.choicesInit) {
        new Choices(el,{searchEnabled:false,itemSelectText:'',shouldSort:false,allowHTML:false});
        el.dataset.choicesInit='1';
      }
    });
  }

  if (window.flatpickr) {
    document.querySelectorAll('.gt-grid input[type="date"]').forEach((el)=>{
      el.type='text';
      el.placeholder='dd/mm/yyyy';
      flatpickr(el,{dateFormat:'d/m/Y',allowInput:true,monthSelectorType:'static'});
    });
    document.querySelectorAll('.gt-grid input[type="time"]').forEach((el)=>{
      el.type='text';
      el.placeholder='--:--';
      flatpickr(el,{enableTime:true,noCalendar:true,dateFormat:'H:i',time_24hr:true,minuteIncrement:5,allowInput:true});
    });
  }


  const tourRows = document.querySelectorAll('[data-tour-row]');

  const activateRowFilter = (row, filterKey) => {
    if (!row) return;
    const chips = row.querySelectorAll('.trip-style-chip');
    const cards = row.querySelectorAll('.home-tour-card');
    const summaryTitle = row.querySelector('.trip-style-summary-title');
    const summaryMeta = row.querySelector('.trip-style-summary-meta');
    const summaryLink = row.querySelector('.trip-style-summary-link');
    const emptyState = row.querySelector('.home-tour-empty');
    let visibleCount = 0;

    chips.forEach((chip) => {
      const active = chip.getAttribute('data-style') === filterKey;
      chip.classList.toggle('active', active);
      if (active) {
        if (summaryTitle) summaryTitle.textContent = chip.getAttribute('data-label') || '';
        if (summaryMeta) summaryMeta.textContent = `${chip.getAttribute('data-count') || 0} <?php echo esc_js(contenly_tr('paket cocok', 'matching packages')); ?>`;
        if (summaryLink) summaryLink.setAttribute('href', chip.getAttribute('data-href') || '#');
      }
    });

    cards.forEach((card) => {
      const tags = (card.getAttribute('data-filter-tags') || '').split(',').map((item) => item.trim()).filter(Boolean);
      const show = filterKey === 'all' ? true : tags.includes(filterKey);
      card.style.display = show ? 'flex' : 'none';
      card.classList.toggle('is-visible', show);
      if (show) visibleCount += 1;
    });

    if (emptyState) {
      emptyState.style.display = visibleCount ? 'none' : 'block';
    }
  };

  tourRows.forEach((row) => {
    const chips = row.querySelectorAll('.trip-style-chip');
    chips.forEach((chip) => {
      chip.addEventListener('click', () => activateRowFilter(row, chip.getAttribute('data-style')));
    });
    if (chips.length) {
      activateRowFilter(row, chips[0].getAttribute('data-style'));
    }
  });

  const wlBtns = document.querySelectorAll('.home-tour-wishlist-btn');
  wlBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
      const tourId = btn.getAttribute('data-tour-id');
      if (!window.contenlyBooking) {
        window.location.href = '/login?redirect_to=' + encodeURIComponent(window.location.pathname);
        return;
      }
      const fd = new FormData();
      fd.append('action', 'contenly_toggle_wishlist');
      fd.append('tour_id', tourId);
      fd.append('nonce', window.contenlyBooking.nonce || '');
      btn.disabled = true;
      try {
        const res = await fetch('/wp-admin/admin-ajax.php', {method:'POST', body:fd});
        const data = await res.json();
        if (data.success) {
          const inWl = !!data.data.in_wishlist;
          btn.setAttribute('data-in-wishlist', inWl ? '1' : '0');
          btn.textContent = inWl ? '❤️' : '🤍';
        }
      } catch (e) {}
      btn.disabled = false;
    });
  });
});
</script>

<!-- Tour Category Rows Section -->
<?php
$style_presets = contenly_get_trip_style_presets();
$style_keys = array_keys($style_presets);
$tours_query = new WP_Query(['post_type' => 'tour', 'posts_per_page' => -1, 'post_status' => 'publish']);
$all_home_tours = contenly_filter_real_posts($tours_query->posts, 'tour');
$diving_presets = [
    'resort' => ['label' => contenly_tr('Menginap', 'Stay Package'), 'href_arg' => 'resort'],
    'liveaboard' => ['label' => contenly_tr('Liveaboard', 'Liveaboard'), 'href_arg' => 'liveaboard'],
];

$segments = [
    [
        'slug' => 'domestic',
        'label' => contenly_tr('Tour Domestik', 'Domestic Tours'),
        'type' => 'style',
        'tours' => array_values(array_filter($all_home_tours, fn($tour) => contenly_is_domestic_tour($tour))),
    ],
    [
        'slug' => 'international',
        'label' => contenly_tr('Tour Internasional', 'International Tours'),
        'type' => 'style',
        'tours' => array_values(array_filter($all_home_tours, fn($tour) => contenly_is_international_tour($tour))),
    ],
    [
        'slug' => 'diving',
        'label' => contenly_tr('Paket Diving', 'Diving Packages'),
        'type' => 'diving',
        'tours' => array_values(array_filter($all_home_tours, fn($tour) => contenly_is_diving_tour($tour))),
    ],
];
?>
<div class="home-section soft">
    <div class="home-shell">
        <div style="text-align:center;">
            <span class="section-kicker"><?php echo esc_html(contenly_tr('Tipe Perjalanan', 'Trip Styles')); ?></span>
            <h2 class="section-heading"><?php echo esc_html(contenly_tr('Mau liburan tipe yang mana?', 'What kind of trip are you planning?')); ?></h2>
            <p class="section-subheading"><?php echo esc_html(contenly_tr('Pilih dulu kategori traveler yang paling nyambung, nanti paket domestik, internasional, dan diving langsung ngikut lebih rapi.', 'Start with the traveller category that fits best, then domestic, international, and diving packages will follow in a cleaner way.')); ?></p>
        </div>

        <?php foreach ($segments as $segment) : ?>
            <?php
            $row_tours = $segment['tours'];
            $filter_map = [];
            if ($segment['type'] === 'style') {
                foreach ($style_presets as $style_slug => $style_data) {
                    $filter_map[$style_slug] = [
                        'label' => $style_data['label'],
                        'count' => 0,
                        'href' => add_query_arg(['travel_style' => $style_slug], contenly_localized_url('/tour-packages/')),
                    ];
                }
                foreach ($row_tours as $tour_post) {
                    foreach (contenly_get_tour_travel_styles($tour_post) as $style_slug) {
                        if (isset($filter_map[$style_slug])) {
                            $filter_map[$style_slug]['count']++;
                        }
                    }
                }
            } else {
                foreach ($diving_presets as $mode_slug => $mode_data) {
                    $filter_map[$mode_slug] = [
                        'label' => $mode_data['label'],
                        'count' => 0,
                        'href' => add_query_arg(['trip_theme' => 'diving', 'trip_mode' => $mode_data['href_arg']], contenly_localized_url('/tour-packages/')),
                    ];
                }
                foreach ($row_tours as $tour_post) {
                    $mode_slug = contenly_get_diving_trip_mode($tour_post);
                    if (isset($filter_map[$mode_slug])) {
                        $filter_map[$mode_slug]['count']++;
                    }
                }
            }
            $available_filter_keys = array_keys(array_filter($filter_map, function($item){ return (isset($item['count']) ? $item['count'] : 0) > 0; }));
            $default_filter = isset($available_filter_keys[0]) ? $available_filter_keys[0] : array_key_first($filter_map);
            ?>
            <section class="tour-row-section" data-tour-row="<?php echo esc_attr($segment['slug']); ?>">
                <div class="trip-style-inline-summary">
                    <div class="trip-style-inline-main">
                        <div class="trip-style-inline-copy">
                            <span class="trip-style-row-label"><?php echo esc_html($segment['label']); ?></span>
                            <span class="trip-style-summary-title"><?php echo esc_html(isset($filter_map[$default_filter]['label']) ? $filter_map[$default_filter]['label'] : contenly_tr('Kategori', 'Category')); ?></span>
                            <span class="trip-style-summary-meta"><?php echo esc_html(isset($filter_map[$default_filter]['count']) ? $filter_map[$default_filter]['count'] : 0); ?> <?php echo esc_html(contenly_tr('paket cocok', 'matching packages')); ?></span>
                        </div>
                        <div class="trip-style-chip-bar" aria-label="Filter <?php echo esc_attr(strtolower($segment['label'])); ?>">
                            <?php foreach ($filter_map as $filter_slug => $filter_data) : ?>
                                <button type="button" class="trip-style-chip<?php echo $filter_slug === $default_filter ? ' active' : ''; ?>" data-style="<?php echo esc_attr($filter_slug); ?>" data-label="<?php echo esc_attr($filter_data['label']); ?>" data-count="<?php echo esc_attr($filter_data['count']); ?>" data-href="<?php echo esc_url($filter_data['href']); ?>"><?php echo esc_html($filter_data['label']); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a class="trip-style-summary-link" href="<?php echo esc_url(isset($filter_map[$default_filter]['href']) ? $filter_map[$default_filter]['href'] : contenly_localized_url('/tour-packages/')); ?>"><?php echo esc_html(contenly_tr('Lihat semua paket →', 'View all packages →')); ?></a>
                </div>

                <div class="tour-row-grid">
                    <?php if (!empty($row_tours)) : ?>
                        <?php foreach ($row_tours as $tour_post) : ?>
                            <?php
                            $tour_id = $tour_post->ID;
                            $price = (int) (get_post_meta($tour_id, '_tour_price', true) ?: get_post_meta($tour_id, 'price', true));
                            $duration = get_post_meta($tour_id, 'duration', true) ?: get_post_meta($tour_id, '_tour_duration_days', true);
                            $location = get_post_meta($tour_id, 'location', true) ?: get_post_meta($tour_id, '_tour_location', true);
                            $title = get_the_title($tour_id);
                            $filter_tags = $segment['type'] === 'style' ? contenly_get_tour_travel_styles($tour_id) : [contenly_get_diving_trip_mode($tour_id)];
                            $filter_attr = implode(',', $filter_tags);
                            $is_default_visible = in_array($default_filter, $filter_tags, true);
                            ?>
                            <article class="home-tour-card<?php echo $is_default_visible ? ' is-visible' : ''; ?>" data-filter-tags="<?php echo esc_attr($filter_attr); ?>" style="display:<?php echo $is_default_visible ? 'flex' : 'none'; ?>;">
                                <?php if (has_post_thumbnail($tour_id)) : ?>
                                    <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="home-tour-card-media">
                                        <?php echo get_the_post_thumbnail($tour_id, 'medium_large'); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="home-tour-card-media" style="display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #355F72, #539294, #E5A736); color: white; font-size: 20px; font-weight: 700; letter-spacing: .03em; text-decoration:none;">Whale Dive TOUR</a>
                                <?php endif; ?>

                                <div class="home-tour-card-body">
                                    <h3 class="home-tour-card-title">
                                        <a href="<?php echo esc_url(get_permalink($tour_id)); ?>"><?php echo esc_html($title); ?></a>
                                    </h3>
                                    <div class="home-tour-card-meta">
                                        <p><span class="home-tour-card-meta-dot"></span><?php echo esc_html($location ?: contenly_tr('Destinasi', 'Destination')); ?></p>
                                        <p><span class="home-tour-card-meta-dot"></span><?php echo esc_html($duration ?: '-'); ?> · Rating 4.9</p>
                                    </div>
                                    <div class="home-tour-card-footer">
                                        <div class="home-tour-card-price">
                                            <span class="home-tour-card-price-label"><?php echo esc_html(contenly_tr('Mulai dari', 'Starting from')); ?></span>
                                            <strong class="home-tour-card-price-value">IDR <?php echo number_format($price, 0, ',', '.'); ?></strong>
                                        </div>
                                        <div class="home-tour-card-actions">
                                            <a href="<?php echo esc_url(get_permalink($tour_id)); ?>" class="home-tour-card-cta"><?php echo esc_html(contenly_tr('Lihat Detail', 'View Details')); ?></a>
                                            <button type="button" class="home-tour-wishlist-btn" data-tour-id="<?php echo esc_attr($tour_id); ?>" data-in-wishlist="<?php echo in_array($tour_id, $wishlist_ids, true) ? '1' : '0'; ?>">
                                                <?php echo in_array($tour_id, $wishlist_ids, true) ? '❤️' : '🤍'; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                        <div class="home-tour-empty"><?php echo esc_html(contenly_tr('Belum ada paket yang cocok buat kategori ini. Coba pilih filter lain dulu ya.', 'No packages match this category yet. Try another filter first.')); ?></div>
                    <?php else : ?>
                        <p style="text-align: center; padding: 42px; color: #64748b; font-size: 16px; grid-column: 1 / -1;"><?php echo esc_html(contenly_tr('Belum ada paket tersedia untuk section ini.', 'No packages are available for this section yet.')); ?></p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</div>

<!-- Newsletter Section -->
<div style="clear: both; height: 1px;"></div>
<div class="home-section" style="background: linear-gradient(135deg, #355F72 0%, #539294 58%, #E5A736 100%); color: white; margin-bottom: 0 !important; overflow: hidden !important; display: block !important;">
    <div class="home-shell" style="max-width: 720px; text-align:center;">
        <span class="section-kicker" style="background:rgba(255,255,255,.12); color:#fff;"><?php echo esc_html(contenly_tr('Promo & Insight', 'Promos & Insights')); ?></span>
        <h2 class="section-heading" style="color:white;"><?php echo contenly_is_english() ? 'Get 10% Off for<br>Your First Trip!' : 'Dapatkan Diskon 10% untuk<br>Trip Pertama!'; ?></h2>
        <p class="section-subheading" style="color:rgba(255,255,255,.82); margin-bottom:32px;"><?php echo esc_html(contenly_tr('Subscribe newsletter kami untuk dapetin promo eksklusif, tips perjalanan, dan kode diskon spesial dengan ritme update yang tetap nyaman dibaca.', 'Subscribe to our newsletter for exclusive promos, travel tips, and special discount codes with an update rhythm that still feels comfortable to read.')); ?></p>

        <form style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;">
            <input type="email" placeholder="<?php echo esc_attr(contenly_tr('Masukkan email kamu', 'Enter your email')); ?>" style="flex: 1; min-width: 280px; padding: 16px 24px; border-radius: 9999px; border: 1px solid rgba(255,255,255,.18); font-size: 16px; outline: none; background:rgba(255,255,255,.96);" />
            <button type="submit" style="padding: 16px 34px; background: #ffffff; color: #0f172a; border: none; border-radius: 9999px; font-weight: 700; font-size: 16px; cursor: pointer; white-space: nowrap; box-shadow:0 14px 28px rgba(15,23,42,.18);"><?php echo esc_html(contenly_tr('Langganan', 'Subscribe')); ?></button>
        </form>

        <p style="font-size: 14px; margin-top: 16px; color:rgba(255,255,255,.66);"><?php echo esc_html(contenly_tr('Tanpa spam, bisa berhenti kapan saja.', 'No spam, unsubscribe anytime.')); ?></p>
    </div>
</div>


<!-- Benefits Section -->
<div class="home-section white">
    <div class="home-shell">
        <div style="text-align:center;">
            <span class="section-kicker"><?php echo esc_html(contenly_tr('Kenapa Whale Dive Centre', 'Why Whale Dive Centre')); ?></span>
            <h2 class="section-heading"><?php echo esc_html(contenly_tr('Kenapa Harus Travel Bareng Kami', 'Why Travel With Us')); ?></h2>
            <p class="section-subheading"><?php echo esc_html(contenly_tr('Bukan cuma cari paket, tapi bikin flow liburan terasa lebih rapi, lebih jelas, dan lebih aman dari awal sampai pulang.', 'It is not just about finding a package, but making the whole trip feel more organised, clearer, and safer from planning to returning home.')); ?></p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px;">
            <div class="premium-card" style="padding: 32px; text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #355F72, #539294); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto 16px; color: white;">HT</div>
                <h3 style="margin-bottom: 8px; font-size: 18px; font-weight: 600; color: #0f172a !important;"><?php echo esc_html(contenly_tr('Harga Terbaik', 'Best Pricing')); ?></h3>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6;"><?php echo esc_html(contenly_tr('Harga jelas dari awal, tanpa biaya tersembunyi di akhir.', 'Clear pricing from the start, with no hidden charges at the end.')); ?></p>
            </div>
            
            <div class="premium-card" style="padding: 32px; text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #355F72, #539294); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto 16px; color: white;">AM</div>
                <h3 style="margin-bottom: 8px; font-size: 18px; font-weight: 600; color: #0f172a !important;"><?php echo esc_html(contenly_tr('Booking Aman', 'Secure Booking')); ?></h3>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6;"><?php echo esc_html(contenly_tr('Pembayaran aman dengan opsi transfer dan metode yang fleksibel.', 'Secure payment with transfer options and flexible payment methods.')); ?></p>
            </div>
            
            <div class="premium-card" style="padding: 32px; text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #355F72, #539294); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto 16px; color: white;">24/7</div>
                <h3 style="margin-bottom: 8px; font-size: 18px; font-weight: 600; color: #0f172a !important;"><?php echo esc_html(contenly_tr('Dukungan 24/7', '24/7 Support')); ?></h3>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6;"><?php echo esc_html(contenly_tr('Tim kami siap bantu sebelum berangkat sampai perjalanan selesai.', 'Our team is ready to help from before departure until the trip is complete.')); ?></p>
            </div>
            
            <div class="premium-card" style="padding: 32px; text-align: center;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #355F72, #539294); border-radius: 24px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto 16px; color: white;">4.9</div>
                <h3 style="margin-bottom: 8px; font-size: 18px; font-weight: 600; color: #0f172a !important;"><?php echo esc_html(contenly_tr('Rating Tertinggi', 'Top Ratings')); ?></h3>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6;"><?php echo esc_html(contenly_tr('Dipercaya ribuan traveler dengan ulasan yang konsisten positif.', 'Trusted by thousands of travellers with consistently positive reviews.')); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<?php
$latest_reviews = get_posts([
    'post_type' => 'destination',
    'posts_per_page' => 12,
    'post_status' => 'publish',
    'meta_query' => [
        [
            'key' => '_is_review',
            'value' => '1',
        ],
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);
$latest_reviews = array_slice(contenly_filter_real_posts($latest_reviews, 'review'), 0, 6);
?>
<div class="home-section" style="background: linear-gradient(135deg, #355F72 0%, #539294 52%, #355F72 100%); overflow: hidden; color:#e2e8f0;">
    <div class="home-shell" style="margin-bottom: 32px;">
        <div style="text-align:center;">
            <span class="section-kicker" style="background:rgba(255,255,255,.1); color:#D8E8E8;">Social Proof</span>
            <h2 class="section-heading" style="color:#ffffff !important;"><?php echo esc_html(contenly_tr('Apa Kata Traveler Kami', 'What Our Travellers Say')); ?></h2>
            <p class="section-subheading" style="margin-bottom:0; color:rgba(226,232,240,.74);"><?php echo esc_html(contenly_tr('Review terbaru dari traveler yang sudah isi request, booking, dan berangkat bareng tim Whale Dive Centre.', 'Latest reviews from travellers who have submitted requests, completed bookings, and departed with the Whale Dive Centre team.')); ?></p>
        </div>
    </div>

    <?php if (!empty($latest_reviews)) : ?>
    <div class="review-marquee-wrap">
        <div class="review-marquee-row review-marquee-left">
            <div class="review-marquee-track">
                <?php foreach (array_merge($latest_reviews, $latest_reviews) as $review) :
                    $rating = absint(get_post_meta($review->ID, '_rating', true));
                    $user_id = get_post_meta($review->ID, '_user_id', true);
                    $user = $user_id ? get_user_by('id', $user_id) : null;
                    $name = $user ? $user->display_name : 'Traveler';
                    $initial = strtoupper(substr($name, 0, 1));
                ?>
                <article class="review-marquee-card">
                    <div class="review-marquee-stars"><?php echo str_repeat('⭐', max(1, min(5, $rating ?: 5))); ?></div>
                    <p>"<?php echo esc_html(wp_trim_words($review->post_content ?: $review->post_title, 18, '...')); ?>"</p>
                    <div class="review-marquee-user">
                        <div class="review-marquee-avatar"><?php echo esc_html($initial); ?></div>
                        <span><?php echo esc_html($name); ?></span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>


    </div>
    <?php else : ?>
    <div class="home-shell">
        <div class="premium-card" style="max-width: 760px; margin: 0 auto; padding: 32px; text-align:center; background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%); border-color:rgba(216,232,232,.36); box-shadow:0 22px 44px rgba(2,6,23,.22);">
            <div style="width:68px; height:68px; border-radius:22px; background:linear-gradient(135deg,#DCE9E6,#D8E8E8); color:#355F72; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; margin:0 auto 16px;">4.9</div>
            <h3 style="margin:0 0 10px; font-size:26px; color:#0f172a;"><?php echo esc_html(contenly_tr('Review traveler lagi kami kumpulkan', 'We are still gathering traveller reviews')); ?></h3>
            <p style="margin:0 auto 18px; max-width:560px; color:#475569; line-height:1.8;"><?php echo esc_html(contenly_tr('Sambil nunggu review terbaru tampil di sini, lu tetap bisa isi form kebutuhan trip biar tim kami review itinerary, harga, dan flow keberangkatan.', 'While waiting for the latest reviews to appear here, you can still fill in the trip request form so our team can review your itinerary, pricing, and departure flow.')); ?></p>
            <a href="<?php echo esc_url(contenly_localized_url('/contact/#contact-form-start')); ?>" class="premium-pill-link"><?php echo esc_html(contenly_tr('Isi Form Kebutuhan Trip →', 'Fill in Your Trip Request Form →')); ?></a>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.review-marquee-wrap { display: grid; gap: 22px; overflow: hidden; max-width: 100%; }
.review-marquee-row {
    overflow: hidden;
    max-width: 100%;
    position: relative;
    padding: 4px 0;
}
.review-marquee-row::before,
.review-marquee-row::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 70px;
    z-index: 2;
    pointer-events: none;
}
.review-marquee-row::before {
    left: 0;
    background: linear-gradient(90deg, #EEF5F4 0%, rgba(238,245,244,0) 100%);
}
.review-marquee-row::after {
    right: 0;
    background: linear-gradient(270deg, #EEF5F4 0%, rgba(238,245,244,0) 100%);
}
.review-marquee-track { display: flex; gap: 18px; width: max-content; }
.review-marquee-card {
    width: 370px;
    max-width: 92vw;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.10);
    border: 1px solid #DCE9E6;
}
.review-marquee-stars { margin-bottom: 8px; letter-spacing: 1px; }
.review-marquee-card p { margin: 0 0 12px; color: #334155; line-height: 1.65; font-size: 14px; min-height: 70px; }
.review-marquee-user { display: flex; align-items: center; gap: 10px; font-weight: 600; color: #0f172a; }
.review-marquee-avatar {
    width: 34px; height: 34px; border-radius: 50%;
                    background: linear-gradient(135deg,#355F72,#539294);
    color: #fff; display:flex; align-items:center; justify-content:center;
    font-weight: 700; font-size: 14px;
}
.review-marquee-left .review-marquee-track { animation: marquee-left 92s linear infinite; }
.review-marquee-row:hover .review-marquee-track { animation-play-state: paused; }
@keyframes marquee-left { from { transform: translateX(0); } to { transform: translateX(-50%); } }
@media (max-width: 768px) {
    .review-marquee-wrap { gap: 14px; }
    .review-marquee-row::before,
    .review-marquee-row::after { width: 28px; }
    .review-marquee-card { width: 290px; padding: 14px; border-radius: 14px; }
    .review-marquee-card p { font-size: 13px; min-height: 62px; }
    .review-marquee-left .review-marquee-track { animation-duration: 74s; }
}
@media (prefers-reduced-motion: reduce) {
    .review-marquee-left .review-marquee-track { animation: none; }
}
</style>

<!-- Login/Register CTA Section -->
<div class="home-section white">
    <div class="home-shell" style="max-width: 1040px; text-align: center;">
        <span class="section-kicker"><?php echo esc_html(contenly_tr('Membership & Komunitas', 'Membership & Community')); ?></span>
        <h2 class="section-heading"><?php echo esc_html(contenly_tr('Gabung Komunitas Travel Kami', 'Join Our Travel Community')); ?></h2>
        <p class="section-subheading"><?php echo esc_html(contenly_tr('Buat akun untuk simpan paket favorit, pantau booking yang lagi jalan, dan dapetin akses promo yang lebih cepat dari traveler biasa.', 'Create an account to save favourite packages, track active bookings, and get faster access to promos than regular visitors.')); ?></p>
        
        <div class="membership-community-grid" style="display:grid; grid-template-columns:1.1fr .9fr; gap:24px; margin-bottom: 40px; align-items:stretch;">
            <div class="premium-card membership-community-card membership-community-card--primary" style="padding: 36px; text-align: left; background:linear-gradient(135deg,#355F72 0%,#539294 100%); border-color:rgba(255,255,255,.08); color:#fff;">
                <span style="display:inline-flex; align-items:center; min-height:30px; padding:0 12px; border-radius:999px; background:rgba(255,255,255,.12); color:#fff; font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; margin-bottom:16px;"><?php echo esc_html(contenly_tr('Member Area', 'Member Area')); ?></span>
                <h3 style="font-size: 28px; line-height:1.2; margin:0 0 14px; color:#fff !important;"><?php echo esc_html(contenly_tr('Semua kebutuhan trip lu tetap ke-track dalam satu tempat.', 'Keep every trip need tracked in one place.')); ?></h3>
                <p style="margin:0 0 18px; color:rgba(255,255,255,.78); line-height:1.8;"><?php echo esc_html(contenly_tr('Cocok buat traveler yang sering compare paket, isi request perjalanan, atau pengen nyimpen shortlist sebelum booking final.', 'Perfect for travellers who often compare packages, submit trip requests, or want to save a shortlist before confirming a booking.')); ?></p>
                <ul style="list-style:none; padding:0; margin:0; display:grid; gap:12px;">
                    <li style="display:flex; align-items:flex-start; gap:10px; color:rgba(255,255,255,.86);"><span>✓</span><span><?php echo esc_html(contenly_tr('Lihat riwayat booking dan status trip yang akan datang', 'View booking history and upcoming trip status')); ?></span></li>
                    <li style="display:flex; align-items:flex-start; gap:10px; color:rgba(255,255,255,.86);"><span>✓</span><span><?php echo esc_html(contenly_tr('Simpan paket favorit dan lanjutkan pengisian request lebih cepat', 'Save favourite packages and continue trip requests faster')); ?></span></li>
                    <li style="display:flex; align-items:flex-start; gap:10px; color:rgba(255,255,255,.86);"><span>✓</span><span><?php echo esc_html(contenly_tr('Akses dokumen perjalanan dan update promo lebih awal', 'Access travel documents and receive promo updates earlier')); ?></span></li>
                </ul>
            </div>
            
            <div class="premium-card membership-community-card membership-community-card--secondary" style="padding: 32px; text-align: left;">
                <h3 style="font-size: 22px; font-weight: 700; margin: 0 0 16px; color: #0f172a !important;"><?php echo esc_html(contenly_tr('Benefit Eksklusif', 'Exclusive Benefits')); ?></h3>
                <ul style="list-style: none; padding: 0; margin: 0 0 22px; display:grid; gap:12px;">
                    <li style="color: #475569; display: flex; align-items: flex-start; gap: 10px;"><span style="color:#539294; font-weight:800;">•</span><span><?php echo esc_html(contenly_tr('Diskon khusus member untuk paket tertentu', 'Member-only discounts for selected packages')); ?></span></li>
                    <li style="color: #475569; display: flex; align-items: flex-start; gap: 10px;"><span style="color:#539294; font-weight:800;">•</span><span><?php echo esc_html(contenly_tr('Akses awal ke paket baru dan promo musiman', 'Early access to new packages and seasonal promos')); ?></span></li>
                    <li style="color: #475569; display: flex; align-items: flex-start; gap: 10px;"><span style="color:#539294; font-weight:800;">•</span><span><?php echo esc_html(contenly_tr('Flow pengisian request ulang lebih cepat karena preferensi udah tersimpan', 'A faster repeat-request flow because your preferences are already saved')); ?></span></li>
                </ul>
                <div style="padding:18px; border-radius:20px; background:#EEF5F4; color:#355F72; font-size:14px; line-height:1.75;"><?php echo esc_html(contenly_tr('Kalau lu masih tahap compare dan belum siap booking, bikin akun dulu aja biar shortlist paket tetap aman dan gampang dibalikin pas udah siap lanjut.', 'If you are still comparing options and not ready to book yet, create an account first so your shortlist stays safe and is easy to revisit later.')); ?></div>
            </div>
        </div>
        
        <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;">
            <a href="<?php echo esc_url(contenly_localized_url('/login/')); ?>" class="premium-pill-link"><?php echo esc_html(contenly_tr('Login Sekarang', 'Login Now')); ?></a>
            <a href="<?php echo esc_url(contenly_localized_url('/register/')); ?>" style="display: inline-flex; align-items:center; justify-content:center; min-height:46px; padding: 0 24px; background: white; color: #539294; border: 1px solid #D8E8E8; border-radius: 9999px; text-decoration: none; font-weight: 700; font-size: 15px;"><?php echo esc_html(contenly_tr('Buat Akun', 'Create an Account')); ?></a>
        </div>
    </div>
</div>

<!-- Community Stories Section -->
<div class="home-section soft">
    <div class="home-shell">
        <div style="text-align:center;">
            <span class="section-kicker"><?php echo esc_html(contenly_tr('Cerita Perjalanan', 'Travel Stories')); ?></span>
            <h2 class="section-heading"><?php echo esc_html(contenly_tr('Cerita Traveler', 'Traveller Stories')); ?></h2>
            <p class="section-subheading"><?php echo esc_html(contenly_tr('Catatan trip, insight itinerary, dan pengalaman perjalanan yang bisa jadi gambaran sebelum lu pilih paket yang paling cocok.', 'Trip notes, itinerary insights, and travel experiences that help you picture the journey before choosing the package that fits best.')); ?></p>
        </div>

        <?php
        $featured_story = get_posts(contenly_all_language_post_args([
            'post_type' => 'post',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_is_featured_travel_story',
                    'value' => '1',
                ],
                [
                    'key' => '_is_travel_story',
                    'value' => '1',
                ],
            ],
        ]));

        if (empty($featured_story)) {
            $featured_story = get_posts(contenly_all_language_post_args([
                'post_type' => 'post',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'meta_query' => [
                    [
                        'key' => '_is_travel_story',
                        'value' => '1',
                    ],
                ],
                'orderby' => 'date',
                'order' => 'DESC',
            ]));
        }

        if (!empty($featured_story) && contenly_is_dummy_story($featured_story[0])) {
            $featured_story = [];
        }

        $sidebar_articles = get_posts(contenly_all_language_post_args([
            'post_type' => 'post',
            'posts_per_page' => 4,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_is_travel_story',
                    'compare' => 'NOT EXISTS',
                ],
            ],
            'orderby' => 'date',
            'order' => 'DESC',
        ]));

        if (!empty($featured_story)) {
            $featured_id = (int) $featured_story[0]->ID;
            $sidebar_articles = array_values(array_filter($sidebar_articles, function($post) use ($featured_id) {
                return (int) $post->ID !== $featured_id && !contenly_is_dummy_story($post);
            }));
        } else {
            $sidebar_articles = array_values(array_filter($sidebar_articles, function($post) {
                return !contenly_is_dummy_story($post);
            }));
        }
        ?>

        <div class="community-story-layout">
            <div class="community-story-main">
                <?php if (!empty($featured_story)) : $story = $featured_story[0]; setup_postdata($story); ?>
                <article class="community-post-item community-post-item--featured" style="display:grid; grid-template-columns:1fr; gap:18px; background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%); border:1px solid #DCE9E6; border-radius:30px; padding:18px; box-shadow:0 18px 36px rgba(15,23,42,0.08); align-items:stretch; min-height:400px; overflow:hidden; height:100%;">
                    <a href="<?php echo esc_url(get_permalink($story)); ?>" class="community-post-media community-post-media--featured" style="display:block; position:relative; border-radius:24px; overflow:hidden; width:100%; aspect-ratio:16/9; background:#DCE9E6; box-shadow:0 18px 28px rgba(83,146,148,.12);">
                        <?php if (has_post_thumbnail($story)) : ?>
                            <?php echo get_the_post_thumbnail($story, 'large', ['style' => 'width:100%;height:100%;object-fit:cover;display:block;']); ?>
                        <?php else : ?>
                            <div style="width:100%; height:100%; background:linear-gradient(135deg,#355F72,#539294); display:flex; align-items:center; justify-content:center; color:white; font-size:42px;">🏝️</div>
                        <?php endif; ?>
                        <span class="community-story-media-badge"><?php echo esc_html(contenly_tr('Member Story Pilihan', 'Featured Member Story')); ?></span>
                    </a>
                    <div class="community-post-content" style="display:flex; flex-direction:column; justify-content:flex-start; padding:4px 6px 6px; min-width:0; gap:14px;">
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                            <span style="display:inline-flex; align-items:center; min-height:28px; padding:0 12px; border-radius:999px; background:#E3EEEC; color:#355F72; font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase;"><?php echo esc_html(contenly_tr('Cerita Member', 'Member Story')); ?></span>
                            <span style="font-size:13px; color:#94a3b8;"><?php echo esc_html(get_the_date('d M Y', $story)); ?> • <?php echo esc_html(get_the_author_meta('display_name', $story->post_author)); ?></span>
                        </div>
                        <h3 class="community-post-title" style="font-size:34px; line-height:1.12; letter-spacing:-.035em; margin:0; color:#0f172a; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;"><?php echo esc_html(get_the_title($story)); ?></h3>
                        <p class="community-post-excerpt" style="font-size:16px; color:#64748b; line-height:1.85; margin:0; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden;"><?php echo esc_html(wp_trim_words(get_the_excerpt($story), 42, '...')); ?></p>
                        <div class="community-post-actions" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding-top:12px; border-top:1px solid #E7EFED;">
                            <a href="<?php echo esc_url(get_permalink($story)); ?>" class="community-story-cta"><?php echo esc_html(contenly_tr('Baca Cerita', 'Read Story')); ?></a>
                            <span style="font-size:13px; color:#94a3b8;"><?php echo esc_html(contenly_tr('Insight itinerary & pengalaman trip real dari member', 'Itinerary insights and real trip experiences from members')); ?></span>
                        </div>
                    </div>
                </article>
                <?php wp_reset_postdata(); else : ?>
                <div class="community-story-empty" style="background:#fff; border:1px dashed #cbd5e1; border-radius:20px; padding:28px; color:#64748b; text-align:center; height:100%; display:flex; align-items:center; justify-content:center; min-height:280px;">
                    <?php echo esc_html(contenly_tr('Belum ada cerita member yang tayang. Publish dulu story member dari dashboard atau pilih story unggulan di WP Admin → Posts.', 'No member stories are live yet. Publish a member story from the dashboard or choose a featured story in WP Admin → Posts first.')); ?>
                </div>
                <?php endif; ?>
            </div>

            <aside class="community-story-sidebar">
                <div class="community-story-sidebar-card">
                    <div class="community-story-sidebar-head">
                        <span class="community-story-sidebar-kicker"><?php echo esc_html(contenly_tr('Artikel Terbaru', 'Latest Articles')); ?></span>
                        <h3><?php echo esc_html(contenly_tr('Artikel Terbaru', 'Latest Articles')); ?></h3>
                    </div>

                    <div class="community-story-sidebar-list">
                        <?php if (!empty($sidebar_articles)) : ?>
                            <?php foreach (array_slice($sidebar_articles, 0, 4) as $sidebar_post) : setup_postdata($sidebar_post); ?>
                            <article class="community-story-mini-item">
                                <a href="<?php echo esc_url(get_permalink($sidebar_post)); ?>" class="community-story-mini-link">
                                    <span class="community-story-mini-thumb-wrap">
                                        <?php if (has_post_thumbnail($sidebar_post)) : ?>
                                            <?php echo get_the_post_thumbnail($sidebar_post, 'medium', ['class' => 'community-story-mini-thumb', 'style' => 'width:100%;height:100%;object-fit:cover;display:block;']); ?>
                                        <?php else : ?>
                                            <span class="community-story-mini-thumb community-story-mini-thumb--fallback">✈️</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="community-story-mini-copy">
                                        <span class="community-story-mini-date"><?php echo esc_html(get_the_date('d M Y', $sidebar_post)); ?></span>
                                        <strong><?php echo esc_html(get_the_title($sidebar_post)); ?></strong>
                                    </span>
                                </a>
                            </article>
                            <?php endforeach; wp_reset_postdata(); ?>
                        <?php else : ?>
                            <div class="community-story-mini-empty"><?php echo esc_html(contenly_tr('Belum ada artikel admin lain yang tayang.', 'No other admin articles are published yet.')); ?></div>
                        <?php endif; ?>
                    </div>

                    <a href="<?php echo esc_url(contenly_localized_url('/blog/')); ?>" class="community-story-sidebar-cta"><?php echo esc_html(contenly_tr('Lihat semua artikel', 'View all articles')); ?></a>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
.community-story-layout{display:grid; grid-template-columns:minmax(0,2.35fr) minmax(360px,1.15fr); gap:24px; align-items:stretch}
.community-story-main,.community-story-sidebar{min-width:0}
.community-post-item--featured{position:relative}
.community-post-media img{transition:transform .45s ease, filter .35s ease}
.community-post-item:hover .community-post-media img{transform:scale(1.04); filter:saturate(1.03)}
.community-post-media--featured::after{content:''; position:absolute; inset:0; background:linear-gradient(180deg,rgba(15,23,42,0) 48%, rgba(15,23,42,.2) 100%); pointer-events:none}
.community-post-content{align-self:stretch}
.community-post-title{max-width:20ch}
.community-post-excerpt{max-width:70ch}
.community-post-actions{margin-top:auto}
.community-story-media-badge{position:absolute; left:16px; bottom:16px; z-index:2; display:inline-flex; align-items:center; min-height:34px; padding:0 14px; border-radius:999px; background:rgba(15,23,42,.62); backdrop-filter:blur(10px); color:#fff; font-size:12px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; box-shadow:0 12px 24px rgba(15,23,42,.18)}
.community-story-sidebar-card{display:flex; flex-direction:column; gap:18px; height:100%; padding:22px; border-radius:26px; background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%); border:1px solid #DCE9E6; box-shadow:0 18px 36px rgba(15,23,42,0.06)}
.community-story-sidebar-head{display:grid; gap:8px}
.community-story-sidebar-kicker{display:inline-flex; align-items:center; width:max-content; min-height:28px; padding:0 12px; border-radius:999px; background:#EEF5F4; color:#355F72; font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase}
.community-story-sidebar-head h3{margin:0; font-size:22px; line-height:1.2; color:#0f172a}
.community-story-sidebar-list{display:grid; gap:12px}
.community-story-mini-item{border:1px solid #E2ECEA; border-radius:18px; background:#fff}
.community-story-mini-link{display:grid; grid-template-columns:120px minmax(0,1fr); gap:14px; align-items:stretch; min-height:120px; padding:12px; text-decoration:none; color:inherit}
.community-story-mini-thumb-wrap{display:block; width:120px; height:100%; min-height:96px; border-radius:14px; overflow:hidden; background:#DCE9E6; box-shadow:0 10px 18px rgba(15,23,42,.08)}
.community-story-mini-thumb{display:block; width:100%; height:100%; object-fit:cover}
.community-story-mini-thumb--fallback{display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#355F72,#539294); color:#fff; font-size:28px}
.community-story-mini-copy{display:grid; gap:8px; min-width:0; align-content:center; padding:2px 0; min-height:96px}
.community-story-mini-link strong{font-size:15px; line-height:1.45; color:#0f172a; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden}
.community-story-mini-date{font-size:12px; color:#94a3b8; font-weight:700; text-transform:uppercase; letter-spacing:.04em}
.community-story-sidebar-cta,.community-story-cta{display:inline-flex; align-items:center; justify-content:center; min-height:46px; padding:0 18px; border-radius:999px; background:linear-gradient(135deg,#355F72,#539294); color:#fff !important; text-decoration:none; font-weight:800; box-shadow:0 14px 28px rgba(83,146,148,.22); transition:transform .2s ease, box-shadow .2s ease}
.community-story-sidebar-cta{width:100%}
.community-story-sidebar-cta:hover,.community-story-cta:hover{transform:translateY(-1px); box-shadow:0 18px 32px rgba(83,146,148,.28)}
.community-story-mini-empty{padding:16px; border:1px dashed #cbd5e1; border-radius:16px; color:#64748b; text-align:center; font-size:14px; line-height:1.7}
@media (max-width: 1100px){
    .community-story-layout{grid-template-columns:1fr}
}
@media (max-width: 900px) {
    .community-post-item {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
        min-height: auto !important;
        padding:18px !important;
    }

    .community-post-media {
        aspect-ratio: 4 / 3 !important;
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
    }

    .community-post-title,
    .community-post-excerpt {
        max-width:none !important;
    }

    .community-post-item h3,
    .community-story-sidebar-head h3 {
        font-size: 24px !important;
    }
}
@media (max-width: 900px){
    .membership-community-grid{grid-template-columns:1fr !important; gap:16px !important; margin-bottom:28px !important}
    .membership-community-card{padding:24px !important; border-radius:22px !important}
    .membership-community-card h3{font-size:24px !important; line-height:1.25 !important}
}
@media (max-width: 640px){
    .membership-community-grid{gap:14px !important; margin-bottom:24px !important}
    .membership-community-card{padding:18px !important; border-radius:18px !important}
    .membership-community-card h3{font-size:20px !important; line-height:1.3 !important; margin-bottom:12px !important}
    .membership-community-card p,
    .membership-community-card li,
    .membership-community-card a{font-size:14px !important; line-height:1.7 !important}
    .membership-community-card ul{gap:10px !important}
    .membership-community-card .button{width:100%; justify-content:center}

    .community-story-layout{gap:16px !important}
    .community-post-item--featured{padding:14px !important; border-radius:20px !important; min-height:auto !important}
    .community-post-media--featured{border-radius:18px !important; aspect-ratio:4/3 !important}
    .community-story-media-badge{left:12px; right:12px; bottom:12px; justify-content:center; min-height:30px; padding:0 12px; font-size:11px}
    .community-post-content{padding:0 !important; gap:12px !important}
    .community-post-title{font-size:24px !important; line-height:1.2 !important; max-width:none !important}
    .community-post-excerpt{font-size:14px !important; line-height:1.75 !important; max-width:none !important; -webkit-line-clamp:5 !important}
    .community-post-actions{gap:10px !important; padding-top:10px !important}
    .community-story-cta,.community-story-sidebar-cta{width:100%}
    .community-story-sidebar-card{padding:16px; border-radius:20px}
    .community-story-sidebar-head h3{font-size:20px !important}
    .community-story-mini-link{grid-template-columns:72px minmax(0,1fr); gap:10px; min-height:84px; padding:10px}
    .community-story-mini-thumb-wrap{width:72px; min-height:72px; border-radius:12px}
    .community-story-mini-copy{min-height:72px; gap:6px}
    .community-story-mini-link strong{-webkit-line-clamp:2; font-size:14px}
    .community-story-mini-date{font-size:11px}
    .community-story-mini-empty{padding:14px; font-size:13px}
}
</style>

<!-- CLEARFIX BEFORE FOOTER -->
<div style="clear: both; display: block; height: 1px; overflow: hidden;"></div>
<div style="margin-bottom: 100px; clear: both;"></div>

<?php get_footer(); ?>
