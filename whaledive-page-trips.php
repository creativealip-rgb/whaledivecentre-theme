<?php
/**
 * Template Name: Trip Packages
 * Description: Dive trip packages listing for Whale Dive Centre
 */
get_header();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8">
    ">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class('whaledive-trips'); ?>>

<main class="wd-page">
    <!-- Header/Navbar -->
    <header class="wd-header">
        <a href="<?php echo home_url('/'); ?>" class="wd-brand">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.jpg" alt="Whale Dive Centre">
            <span>Whale Dive Centre</span>
        </a>
        <nav class="wd-menu">
            <a href="<?php echo home_url('/'); ?>#membership">MEMBERSHIP</a>
            <a href="<?php echo home_url('/courses/'); ?>">COURSES</a>
            <a href="<?php echo home_url('/equipment/'); ?>">EQUIPMENT</a>
            <a href="<?php echo home_url('/trips/'); ?>">DIVE TRIPS</a>
            <a href="<?php echo home_url('/gallery/'); ?>">GALLERY</a>
            <a href="<?php echo home_url('/blog/'); ?>">BLOG</a>
            <a href="<?php echo home_url('/our-crew/'); ?>">OUR CREW</a>
            <a href="<?php echo home_url('/faq/'); ?>">FAQ</a>
            <a href="<?php echo home_url('/contact/'); ?>">CONTACT</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="wd-trips-hero">
        <div class="wd-trips-hero-content">
            <p class="wd-label">DIVE ADVENTURES</p>
            <h1>Explore Bali's underwater world.</h1>
            <p class="wd-subtitle">Guided dive trips, weekend adventures, and multi-day liveaboards for certified divers. Small groups, experienced guides, and conservation-minded exploration.</p>
        </div>
    </section>

    <!-- Trip Packages -->
    <section class="wd-section white">
        <div class="wd-container">
            <div class="wd-section-header">
                <p class="wd-label">DIVE PACKAGES</p>
                <h2>Choose your next underwater adventure</h2>
                <p class="wd-section-subtitle">All trips include experienced dive guides, equipment rental options, and marine conservation briefings.</p>
            </div>

            <div class="wd-trips-grid">
                <!-- Day Trips -->
                <article class="wd-trip-card">
                    <div class="wd-trip-badge">POPULAR</div>
                    <div class="wd-trip-image" style="background-image: url('https://placehold.co/800x500/3B44AC/FFFFFF?text=Nusa+Penida');">
                    </div>
                    <div class="wd-trip-content">
                        <span class="wd-trip-category">DAY TRIP</span>
                        <h3>Nusa Penida Day Dive</h3>
                        <p class="wd-trip-description">Two dives at Manta Point and Crystal Bay. Encounter manta rays, mola mola (seasonal), and vibrant coral walls.</p>
                        <ul class="wd-trip-features">
                            <li>✓ 2 guided dives</li>
                            <li>✓ Boat transfer included</li>
                            <li>✓ Lunch & refreshments</li>
                            <li>✓ Max 6 divers per guide</li>
                        </ul>
                        <div class="wd-trip-footer">
                            <div class="wd-trip-price">
                                <span class="wd-price-label">From</span>
                                <span class="wd-price-amount">IDR 1,200,000</span>
                            </div>
                            <a href="<?php echo home_url('/contact/'); ?>" class="wd-trip-btn">Book Trip</a>
                        </div>
                    </div>
                </article>

                <article class="wd-trip-card">
                    <div class="wd-trip-image" style="background-image: url('https://placehold.co/800x500/004A98/FFFFFF?text=Tulamben');">
                    </div>
                    <div class="wd-trip-content">
                        <span class="wd-trip-category">DAY TRIP</span>
                        <h3>Tulamben USAT Liberty Wreck</h3>
                        <p class="wd-trip-description">Explore Bali's most famous wreck dive. Swim through the Liberty shipwreck and discover macro life at the Drop Off.</p>
                        <ul class="wd-trip-features">
                            <li>✓ 2 guided dives</li>
                            <li>✓ Land transfer included</li>
                            <li>✓ Breakfast & lunch</li>
                            <li>✓ Wreck dive briefing</li>
                        </ul>
                        <div class="wd-trip-footer">
                            <div class="wd-trip-price">
                                <span class="wd-price-label">From</span>
                                <span class="wd-price-amount">IDR 950,000</span>
                            </div>
                            <a href="<?php echo home_url('/contact/'); ?>" class="wd-trip-btn">Book Trip</a>
                        </div>
                    </div>
                </article>

                <article class="wd-trip-card">
                    <div class="wd-trip-image" style="background-image: url('https://placehold.co/800x500/4CC8ED/FFFFFF?text=Amed');">
                    </div>
                    <div class="wd-trip-content">
                        <span class="wd-trip-category">DAY TRIP</span>
                        <h3>Amed Coral Gardens</h3>
                        <p class="wd-trip-description">Relaxed shore dives in calm waters. Perfect for new divers or underwater photography. Healthy coral reefs and abundant fish life.</p>
                        <ul class="wd-trip-features">
                            <li>✓ 2 guided shore dives</li>
                            <li>✓ Land transfer included</li>
                            <li>✓ Lunch & refreshments</li>
                            <li>✓ Beginner-friendly</li>
                        </ul>
                        <div class="wd-trip-footer">
                            <div class="wd-trip-price">
                                <span class="wd-price-label">From</span>
                                <span class="wd-price-amount">IDR 850,000</span>
                            </div>
                            <a href="<?php echo home_url('/contact/'); ?>" class="wd-trip-btn">Book Trip</a>
                        </div>
                    </div>
                </article>

                <!-- Weekend Packages -->
                <article class="wd-trip-card wd-trip-featured">
                    <div class="wd-trip-badge">WEEKEND</div>
                    <div class="wd-trip-image" style="background-image: url('https://placehold.co/800x500/C31C4A/FFFFFF?text=Weekend+Package');">
                    </div>
                    <div class="wd-trip-content">
                        <span class="wd-trip-category">2 DAYS / 1 NIGHT</span>
                        <h3>Bali Weekend Dive Escape</h3>
                        <p class="wd-trip-description">Two-day dive adventure covering Tulamben, Amed, and Padang Bai. Accommodation, meals, and 4 dives included.</p>
                        <ul class="wd-trip-features">
                            <li>✓ 4 guided dives</li>
                            <li>✓ 1 night accommodation</li>
                            <li>✓ All meals included</li>
                            <li>✓ Land transfer included</li>
                        </ul>
                        <div class="wd-trip-footer">
                            <div class="wd-trip-price">
                                <span class="wd-price-label">From</span>
                                <span class="wd-price-amount">IDR 2,800,000</span>
                            </div>
                            <a href="<?php echo home_url('/contact/'); ?>" class="wd-trip-btn">Book Trip</a>
                        </div>
                    </div>
                </article>

                <!-- Liveaboard -->
                <article class="wd-trip-card wd-trip-featured">
                    <div class="wd-trip-badge">LIVEABOARD</div>
                    <div class="wd-trip-image" style="background-image: url('https://placehold.co/800x500/96DAEA/000000?text=Komodo+Liveaboard');">
                    </div>
                    <div class="wd-trip-content">
                        <span class="wd-trip-category">4 DAYS / 3 NIGHTS</span>
                        <h3>Komodo Liveaboard Adventure</h3>
                        <p class="wd-trip-description">Multi-day liveaboard to Komodo National Park. Dive with manta rays, sharks, and pristine coral reefs. Limited spots.</p>
                        <ul class="wd-trip-features">
                            <li>✓ 10+ guided dives</li>
                            <li>✓ 3 nights onboard</li>
                            <li>✓ All meals & snacks</li>
                            <li>✓ Nitrox available</li>
                        </ul>
                        <div class="wd-trip-footer">
                            <div class="wd-trip-price">
                                <span class="wd-price-label">From</span>
                                <span class="wd-price-amount">IDR 12,500,000</span>
                            </div>
                            <a href="<?php echo home_url('/contact/'); ?>" class="wd-trip-btn">Book Trip</a>
                        </div>
                    </div>
                </article>

                <!-- Night Dive -->
                <article class="wd-trip-card">
                    <div class="wd-trip-image" style="background-image: url('https://placehold.co/800x500/000000/4CC8ED?text=Night+Dive');">
                    </div>
                    <div class="wd-trip-content">
                        <span class="wd-trip-category">SPECIALTY</span>
                        <h3>Padang Bai Night Dive</h3>
                        <p class="wd-trip-description">Experience the underwater world after dark. Spot nocturnal marine life, bioluminescence, and hunting predators.</p>
                        <ul class="wd-trip-features">
                            <li>✓ 1 guided night dive</li>
                            <li>✓ Dive light provided</li>
                            <li>✓ Safety briefing</li>
                            <li>✓ Advanced Open Water required</li>
                        </ul>
                        <div class="wd-trip-footer">
                            <div class="wd-trip-price">
                                <span class="wd-price-label">From</span>
                                <span class="wd-price-amount">IDR 550,000</span>
                            </div>
                            <a href="<?php echo home_url('/contact/'); ?>" class="wd-trip-btn">Book Trip</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- What's Included -->
    <section class="wd-section light">
        <div class="wd-container">
            <div class="wd-section-header">
                <h2>What's included in every trip</h2>
            </div>
            <div class="wd-included-grid">
                <div class="wd-included-item">
                    <div class="wd-included-icon">🤿</div>
                    <h4>Experienced Guides</h4>
                    <p>PADI/SSI certified dive guides with local site knowledge</p>
                </div>
                <div class="wd-included-item">
                    <div class="wd-included-icon">🛡️</div>
                    <h4>Safety First</h4>
                    <p>Oxygen kit, first aid, and emergency protocols on every trip</p>
                </div>
                <div class="wd-included-item">
                    <div class="wd-included-icon">🌊</div>
                    <h4>Small Groups</h4>
                    <p>Maximum 6 divers per guide for personalized attention</p>
                </div>
                <div class="wd-included-item">
                    <div class="wd-included-icon">🐠</div>
                    <h4>Conservation Focus</h4>
                    <p>Marine life briefings and reef-safe diving practices</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="wd-section wd-trips-cta">
        <div class="wd-container">
            <div class="wd-trips-cta-content">
                <h2>Ready to explore Bali's dive sites?</h2>
                <p>Contact the crew to check availability, ask questions, or customize a private dive trip for your group.</p>
                <a href="<?php echo home_url('/contact/'); ?>" class="wd-btn-primary">Plan Your Trip</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php get_template_part('template-parts/footer'); ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
