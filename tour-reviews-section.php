<?php
/**
 * Tour Reviews Section - Enhanced Design
 * Displays average rating and all reviews for a tour
 */

if (!defined('ABSPATH')) {
    exit;
}

$tour_id = $tour_id ?? get_the_ID();

// Get all reviews for this tour
$reviews = get_posts([
    'post_type' => 'destination',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => '_review_tour_id',
            'value' => $tour_id,
        ],
        [
            'key' => '_is_review',
            'value' => '1',
        ],
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);

// Calculate average rating
$total_rating = 0;
$review_count = count($reviews);

foreach ($reviews as $review) {
    $rating = get_post_meta($review->ID, '_rating', true);
    $total_rating += absint($rating);
}

$average_rating = $review_count > 0 ? round($total_rating / $review_count, 1) : 0;

// Rating distribution
$rating_distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
foreach ($reviews as $review) {
    $rating = absint(get_post_meta($review->ID, '_rating', true));
    if ($rating >= 1 && $rating <= 5) {
        $rating_distribution[$rating]++;
    }
}
?>

<?php if ($review_count > 0) : ?>
<!-- Reviews Section -->
<section class="tour-reviews-wrap" style="margin-top: 80px; padding: 40px; background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%); border-radius: 24px;">
    
    <!-- Section Header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
            ⭐ Member Reviews
        </h2>
        <p style="color: #64748b; font-size: 16px;">
            Real experiences from travelers who booked this tour
        </p>
    </div>
    
    <!-- Rating Summary -->
    <div class="tour-reviews-summary" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; margin-bottom: 48px; align-items: center;">
        
        <!-- Average Rating -->
        <div style="background: linear-gradient(135deg, #355F72 0%, #539294 62%, #E5A736 100%); padding: 40px; border-radius: 20px; text-align: center; color: white; box-shadow: 0 20px 60px rgba(83, 146, 148, 0.3);">
            <div style="font-size: 72px; font-weight: 900; line-height: 1; margin-bottom: 12px;">
                <?php echo number_format($average_rating, 1); ?>
            </div>
            <div style="font-size: 32px; margin-bottom: 16px; letter-spacing: 4px;">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <span style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);"><?php echo $i <= round($average_rating) ? '⭐' : '☆'; ?></span>
                <?php endfor; ?>
            </div>
            <div style="font-size: 15px; opacity: 0.95; font-weight: 600;">
                Based on <?php echo $review_count; ?> <?php echo ($review_count == 1 ? 'review' : 'reviews'); ?>
            </div>
        </div>
        
        <!-- Rating Distribution -->
        <div style="background: white; padding: 32px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 24px;">Rating Breakdown</h3>
            
            <?php for ($stars = 5; $stars >= 1; $stars--) :
                $count = $rating_distribution[$stars];
                $percentage = $review_count > 0 ? ($count / $review_count) * 100 : 0;
            ?>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 60px; font-weight: 600; color: #0f172a; font-size: 14px;">
                    <?php echo $stars; ?> ⭐
                </div>
                <div style="flex: 1; background: #f1f5f9; height: 10px; border-radius: 9999px; overflow: hidden;">
                    <div style="width: <?php echo $percentage; ?>%; background: linear-gradient(90deg, #fbbf24, #f59e0b); height: 100%; border-radius: 9999px; transition: width 0.5s ease;"></div>
                </div>
                <div style="width: 40px; text-align: right; font-weight: 600; color: #64748b; font-size: 13px;">
                    <?php echo $count; ?>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
    
    <!-- Reviews List -->
    <div class="tour-reviews-list" style="display: grid; gap: 24px;">
        <?php foreach ($reviews as $review) :
            $rating = get_post_meta($review->ID, '_rating', true);
            $user_id = get_post_meta($review->ID, '_user_id', true);
            $user = $user_id ? get_user_by('id', $user_id) : null;
            $review_date = get_post_meta($review->ID, '_visit_date', true);
            $review_post_date = get_the_date('F j, Y', $review);
        ?>
        <div class="tour-review-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 32px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" 
             onmouseover="this.style.boxShadow='0 12px 40px rgba(0,0,0,0.12)'; this.style.transform='translateY(-4px)'; this.style.borderColor='#539294';" 
             onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'; this.style.transform='translateY(0)'; this.style.borderColor='#e2e8f0';">
            
            <!-- Review Header -->
            <div class="tour-review-header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <?php if ($user) : ?>
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #355F72, #539294); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 22px; box-shadow: 0 4px 12px rgba(83, 146, 148, 0.3);">
                        <?php echo strtoupper(substr($user->display_name, 0, 1)); ?>
                    </div>
                    <?php else : ?>
                    <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #94a3b8, #64748b); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 22px;">
                        ?
                    </div>
                    <?php endif; ?>
                    
                    <div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 17px; margin-bottom: 4px;">
                            <?php echo $user ? esc_html($user->display_name) : 'Anonymous Traveler'; ?>
                        </div>
                        <div style="font-size: 14px; color: #94a3b8;">
                            <?php echo $review_date ? 'Visited ' . date_i18n('F Y', strtotime($review_date)) : 'Reviewed ' . $review_post_date; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Star Rating -->
                <div class="tour-review-stars" style="display: flex; gap: 4px; font-size: 22px; background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 8px 16px; border-radius: 12px;">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <span style="<?php echo $i <= $rating ? 'filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));' : 'opacity: 0.3;'; ?>">⭐</span>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Review Content -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 12px; line-height: 1.4;">
                    <?php echo esc_html($review->post_title); ?>
                </h3>
                <div style="color: #475569; line-height: 1.8; font-size: 16px;">
                    <?php echo nl2br(esc_html($review->post_content)); ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="tour-review-actions" style="display: flex; gap: 12px; padding-top: 20px; border-top: 2px solid #f1f5f9;">
                <button style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f8fafc; color: #64748b; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s;" 
                        onmouseover="this.style.background='#EEF5F4'; this.style.borderColor='#539294'; this.style.color='#355F72';" 
                        onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.color='#64748b';">
                    <span>👍</span>
                    <span>Helpful</span>
                </button>
                <button style="display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f8fafc; color: #64748b; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s;" 
                        onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#ef4444'; this.style.color='#ef4444';" 
                        onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.color='#64748b';">
                    <span>⚠️</span>
                    <span>Report</span>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php else : ?>
<!-- No Reviews Yet -->
<section style="margin-top: 80px; padding: 60px 40px; background: linear-gradient(135deg, #EEF5F4 0%, #DCE9E6 100%); border-radius: 24px; text-align: center; border: 2px dashed #D8E8E8;">
    <div style="font-size: 80px; margin-bottom: 24px; animation: bounce 2s infinite;">⭐</div>
    <h3 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 12px;">
        Be the First to Review!
    </h3>
    <p style="color: #64748b; font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.6;">
        No reviews yet for this tour. Your experience will help other travelers make informed decisions!
    </p>
    <a href="/my-bookings/?tab=completed" 
       style="display: inline-flex; align-items: center; gap: 12px; padding: 16px 40px; background: linear-gradient(135deg, #355F72, #539294, #E5A736); color: white; text-decoration: none; border-radius: 16px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 24px rgba(83, 146, 148, 0.3); transition: all 0.3s;"
       onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 16px 40px rgba(83, 146, 148, 0.4)';"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 24px rgba(83, 146, 148, 0.3);'">
        <span>✍️</span>
        <span>Write a Review</span>
    </a>
</section>
<?php endif; ?>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.tour-reviews-wrap,
.tour-reviews-wrap * {
    box-sizing: border-box;
}

@media (max-width: 768px) {
    .tour-reviews-wrap {
        margin-top: 32px !important;
        padding: 14px 10px !important;
        border-radius: 14px !important;
    }

    .tour-reviews-wrap h2 {
        font-size: 22px !important;
        margin-bottom: 6px !important;
    }

    .tour-reviews-wrap h3 {
        font-size: 16px !important;
        margin-bottom: 8px !important;
    }

    .tour-reviews-wrap p {
        font-size: 13px !important;
    }

    .tour-reviews-summary {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
        margin-bottom: 20px !important;
    }

    .tour-review-card {
        width: 100% !important;
        max-width: 100% !important;
        padding: 14px !important;
        border-radius: 14px !important;
    }

    .tour-review-card [style*="font-size: 20px"] {
        font-size: 16px !important;
    }

    .tour-review-card [style*="font-size: 16px"] {
        font-size: 14px !important;
        line-height: 1.6 !important;
    }

    .tour-review-header {
        flex-direction: column !important;
        gap: 10px !important;
        align-items: flex-start !important;
        margin-bottom: 12px !important;
        padding-bottom: 12px !important;
    }

    .tour-review-header > div:first-child {
        gap: 10px !important;
    }

    .tour-review-header > div:first-child > div:first-child {
        width: 42px !important;
        height: 42px !important;
        font-size: 16px !important;
    }

    .tour-review-stars {
        font-size: 15px !important;
        padding: 4px 8px !important;
        max-width: 100% !important;
        overflow-x: auto;
    }

    .tour-review-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
        padding-top: 12px !important;
    }

    .tour-review-actions button {
        width: 100% !important;
        justify-content: center !important;
        padding: 8px 6px !important;
        font-size: 12px !important;
    }
}
</style>
