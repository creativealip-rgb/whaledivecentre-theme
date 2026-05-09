<?php
/**
 * Template Name: Travel Dashboard
 */

get_header();

// Get user membership info
$user_id = get_current_user_id();
$tier_live = contenly_get_user_tier_data($user_id);
$current_tier = $tier_live['tier'];
$total_spend = (int) $tier_live['total_spend'];
$booking_count = get_user_meta($user_id, '_tmp_bookings_count', true) ?: 0;

// Tier display data
$tier_data = [
    'silver' => ['name' => 'Silver', 'icon' => '🥈', 'color' => '#94a3b8', 'next' => 'Gold', 'need' => 5000000],
    'gold' => ['name' => 'Gold', 'icon' => '🥇', 'color' => '#fbbf24', 'next' => 'Platinum', 'need' => 15000000],
    'platinum' => ['name' => 'Platinum', 'icon' => '💎', 'color' => '#355F72', 'next' => null, 'need' => null],
];
$current_tier_info = $tier_data[$current_tier];
$progress = $current_tier === 'platinum' ? 100 : min(100, ($total_spend / ($current_tier_info['need'] ?? 1)) * 100);
?>

<div style="padding: 140px 20px 80px; background: linear-gradient(180deg, #f1f5f9 0%, #ffffff 100%);">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Page Header with Tier Badge -->
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 42px; font-weight: 800; margin-bottom: 12px; background: linear-gradient(135deg, #539294, #539294); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <?php echo $current_tier_info['icon']; ?> My Travel Dashboard
            </h1>
            <p style="font-size: 18px; color: #64748b; max-width: 600px; margin: 0 auto 16px;">
                Track your adventures and plan your next trip
            </p>
            
            <!-- Tier Badge -->
            <div style="display: inline-block; padding: 8px 24px; background: <?php echo $current_tier_info['color']; ?>; color: white; border-radius: 9999px; font-weight: 600; font-size: 14px; margin-bottom: 16px;">
                <?php echo $current_tier_info['icon']; ?> <?php echo $current_tier_info['name']; ?> Member
            </div>
            
            <?php if ($current_tier !== 'platinum') : ?>
            <p style="font-size: 14px; color: #64748b;">
                Butuh spending Rp <?php echo number_format(max(0, $current_tier_info['need'] - $total_spend), 0, ',', '.'); ?> lagi buat <?php echo $current_tier_info['next']; ?>!
            </p>
            <?php endif; ?>
        </div>

        <!-- Membership Progress Card -->
        <div style="background: white; border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0;">🏆 Membership Progress</h2>
                <?php if ($current_tier !== 'platinum') : ?>
                <a href="/membership" style="color: #539294; text-decoration: none; font-weight: 600;">Lihat syarat tier →</a>
                <?php endif; ?>
            </div>
            
            <!-- Progress Bar -->
            <div style="background: #e2e8f0; border-radius: 9999px; height: 12px; overflow: hidden; margin-bottom: 16px;">
                <div style="width: <?php echo $progress; ?>%; background: linear-gradient(90deg, <?php echo $current_tier_info['color']; ?>, #539294); height: 100%; border-radius: 9999px; transition: width 0.3s;"></div>
            </div>
            
            <div style="display: flex; justify-content: space-between; font-size: 14px; color: #64748b;">
                <span>Spend Rp <?php echo number_format($total_spend, 0, ',', '.'); ?></span>
                <span><?php echo $current_tier === 'platinum' ? 'Max tier reached!' : 'Target ' . $current_tier_info['next'] . ': Rp ' . number_format($current_tier_info['need'], 0, ',', '.'); ?></span>
            </div>
            
            <!-- Tier Benefits Preview -->
            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <h3 style="font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 12px;">Your Benefits:</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <?php if ($current_tier === 'silver') : ?>
                    <div style="padding: 12px; background: #f0f9ff; border-radius: 8px; font-size: 14px;">✓ 5% discount on tours</div>
                    <div style="padding: 12px; background: #f0f9ff; border-radius: 8px; font-size: 14px;">✓ Review admin lebih cepat</div>
                    <div style="padding: 12px; background: #f0f9ff; border-radius: 8px; font-size: 14px;">✓ Free cancellation (24h)</div>
                    <?php elseif ($current_tier === 'gold') : ?>
                    <div style="padding: 12px; background: #fffbeb; border-radius: 8px; font-size: 14px;">✓ 10% discount on tours</div>
                    <div style="padding: 12px; background: #fffbeb; border-radius: 8px; font-size: 14px;">✓ Prioritas handling VIP</div>
                    <div style="padding: 12px; background: #fffbeb; border-radius: 8px; font-size: 14px;">✓ Travel insurance</div>
                    <?php elseif ($current_tier === 'platinum') : ?>
                    <div style="padding: 12px; background: #EEF5F4; border-radius: 8px; font-size: 14px;">✓ 10% discount on tours</div>
                    <div style="padding: 12px; background: #EEF5F4; border-radius: 8px; font-size: 14px;">✓ Concierge assistance</div>
                    <div style="padding: 12px; background: #EEF5F4; border-radius: 8px; font-size: 14px;">✓ Priority handling tertinggi</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px;">
            
            <!-- Add New Destination Form -->
            <div style="background: white; border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 20px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 24px;">📍</span> Add New Destination
                </h2>
                
                <?php if (is_user_logged_in()) : ?>
                <form id="tmp-add-travel-form" enctype="multipart/form-data" style="display: grid; gap: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Destination Name *</label>
                            <input type="text" name="title" required style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;" />
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Visit Date *</label>
                            <input type="date" name="visit_date" required style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;" />
                        </div>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Description</label>
                        <textarea name="description" rows="4" style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc; resize: vertical;"></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Country</label>
                            <select name="country_id" style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;">
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Category</label>
                            <select name="category_id" style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;">
                                <option value="">Select Category</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Location (City, Region)</label>
                        <input type="text" name="location" style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;" />
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Rating</label>
                        <select name="rating" style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;">
                            <option value="">Select Rating</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Photos</label>
                        <input type="file" name="photos[]" multiple accept="image/*" style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc;" />
                        <p style="font-size: 13px; color: #64748b; margin-top: 6px;">JPG, PNG, GIF, WebP (max 5MB)</p>
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">Personal Notes</label>
                        <textarea name="notes" rows="3" placeholder="Your memories, tips, or highlights..." style="width: 100%; padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: #f8fafc; resize: vertical;"></textarea>
                    </div>
                    
                    <button type="submit" style="padding: 16px 40px; background: linear-gradient(135deg, #539294, #539294); color: white; border: none; border-radius: 9999px; font-weight: 700; font-size: 15px; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);">Add Travel</button>
                </form>
                <?php else : ?>
                <div style="text-align: center; padding: 40px; color: #64748b;">
                    <div style="font-size: 48px; margin-bottom: 16px;">🔐</div>
                    <p style="font-size: 16px;">Please <a href="/login" style="color: #539294; font-weight: 600;">login</a> to add destinations</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Travel Map -->
            <div style="background: white; border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 20px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 24px;">🗺️</span> My Travel Map
                </h2>
                <div style="border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
                    <?php echo do_shortcode('[travel_map height="400"]'); ?>
                </div>
            </div>
        </div>

        <!-- My Travel History -->
        <div style="background: white; border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 20px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 24px;">📖</span> My Travel History
            </h2>
            <?php echo do_shortcode('[user_travel_history]'); ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>
