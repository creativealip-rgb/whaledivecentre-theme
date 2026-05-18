<?php
/**
 * Template Name: Testimonials
 * Description: Customer testimonials and reviews page for Whale Dive Centre
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
<body <?php body_class('whaledive-testimonials'); ?>>

<main class="wd-page">
    <!-- Header/Navbar -->
    <header class="wd-header">
        <a href="<?php echo home_url('/'); ?>" class="wd-brand">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/logo.jpg" alt="Whale Dive Centre">
            <span>WHALE DIVE CENTRE</span>
        </a>
        <nav class="wd-menu">
            <a href="<?php echo home_url('/'); ?>#membership">MEMBERSHIP</a>
            <a href="<?php echo home_url('/courses/'); ?>">Courses</a>
            <a href="<?php echo home_url('/equipment/'); ?>">Equipment</a>
            <a href="<?php echo home_url('/trips/'); ?>">DIVE TRIPS</a>
            <a href="<?php echo home_url('/gallery/'); ?>">GALLERY</a>
            <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
            <a href="<?php echo home_url('/our-crew/'); ?>">OUR CREW</a>
            <a href="<?php echo home_url('/faq/'); ?>">FAQ</a>
            <a href="<?php echo home_url('/contact/'); ?>">CONTACT</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="wd-testimonials-hero">
        <div class="wd-testimonials-hero-content">
            <p class="wd-label">DIVER STORIES</p>
            <h1>What our divers say.</h1>
            <p class="wd-subtitle">Real experiences from students, certified divers, and community members who trained and dove with Whale Dive Centre.</p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="wd-section white wd-testimonials-stats">
        <div class="wd-container">
            <div class="wd-stats-grid">
                <div class="wd-stat-item">
                    <div class="wd-stat-number">500+</div>
                    <div class="wd-stat-label">Certified Divers</div>
                </div>
                <div class="wd-stat-item">
                    <div class="wd-stat-number">4.9/5</div>
                    <div class="wd-stat-label">Average Rating</div>
                </div>
                <div class="wd-stat-item">
                    <div class="wd-stat-number">98%</div>
                    <div class="wd-stat-label">Would Recommend</div>
                </div>
                <div class="wd-stat-item">
                    <div class="wd-stat-number">1000+</div>
                    <div class="wd-stat-label">Dive Trips</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Grid -->
    <section class="wd-section light">
        <div class="wd-container">
            <div class="wd-testimonials-grid">
                <?php
                // Sample testimonials - in production, these would come from custom post type or ACF
                $testimonials = array(
                    array(
                        'name' => 'Sarah Mitchell',
                        'course' => 'Open Water Diver',
                        'rating' => 5,
                        'text' => 'The instructors at Whale Dive Centre made my first diving experience unforgettable. Patient, professional, and genuinely passionate about the ocean. I felt safe every step of the way.',
                        'date' => 'March 2026'
                    ),
                    array(
                        'name' => 'David Chen',
                        'course' => 'Advanced Open Water',
                        'rating' => 5,
                        'text' => 'Small group sizes and personalized attention made all the difference. The crew took time to help me improve my buoyancy and navigation skills. Highly recommend!',
                        'date' => 'February 2026'
                    ),
                    array(
                        'name' => 'Emma Rodriguez',
                        'course' => 'Rescue Diver',
                        'rating' => 5,
                        'text' => 'This course changed how I think about diving safety. The scenarios were realistic, the feedback was constructive, and I left feeling confident in my rescue skills.',
                        'date' => 'January 2026'
                    ),
                    array(
                        'name' => 'James Wilson',
                        'course' => 'Divemaster',
                        'rating' => 5,
                        'text' => 'The Divemaster program here is top-notch. Great mentorship, real-world experience, and a supportive community. This is where I became a dive professional.',
                        'date' => 'December 2025'
                    ),
                    array(
                        'name' => 'Lisa Anderson',
                        'course' => 'Open Water Diver',
                        'rating' => 5,
                        'text' => 'I was nervous about diving, but the crew made me feel comfortable from day one. The equipment was well-maintained, and the dive sites were beautiful. Worth every moment!',
                        'date' => 'November 2025'
                    ),
                    array(
                        'name' => 'Michael Brown',
                        'course' => 'Advanced Open Water',
                        'rating' => 5,
                        'text' => 'Excellent instruction and amazing dive sites. The deep dive and wreck dive were highlights. The crew knows Bali waters like no one else.',
                        'date' => 'October 2025'
                    ),
                    array(
                        'name' => 'Sophie Taylor',
                        'course' => 'Rescue Diver',
                        'rating' => 5,
                        'text' => 'Challenging but rewarding course. The instructors pushed me to think critically and act decisively. I feel prepared to handle real emergencies now.',
                        'date' => 'September 2025'
                    ),
                    array(
                        'name' => 'Ryan Martinez',
                        'course' => 'Open Water Diver',
                        'rating' => 5,
                        'text' => 'Best decision I made in Bali! The crew is friendly, knowledgeable, and safety-focused. I completed my certification and made lifelong friends.',
                        'date' => 'August 2025'
                    ),
                    array(
                        'name' => 'Anna Kim',
                        'course' => 'Divemaster',
                        'rating' => 5,
                        'text' => 'The Divemaster internship gave me hands-on experience with real students and dive operations. The crew treated me like family. Grateful for this journey.',
                        'date' => 'July 2025'
                    )
                );

                foreach ($testimonials as $testimonial) :
                ?>
                    <div class="wd-testimonial-card">
                        <div class="wd-testimonial-rating">
                            <?php for ($i = 0; $i < $testimonial['rating']; $i++) : ?>
                                <span class="wd-star">★</span>
                            <?php endfor; ?>
                        </div>
                        <p class="wd-testimonial-text">"<?php echo esc_html($testimonial['text']); ?>"</p>
                        <div class="wd-testimonial-author">
                            <div class="wd-testimonial-avatar">
                                <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                            </div>
                            <div class="wd-testimonial-info">
                                <strong><?php echo esc_html($testimonial['name']); ?></strong>
                                <span><?php echo esc_html($testimonial['course']); ?> • <?php echo esc_html($testimonial['date']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="wd-section wd-testimonials-cta">
        <div class="wd-container">
            <div class="wd-testimonials-cta-content">
                <h2>Ready to start your dive journey?</h2>
                <p>Join hundreds of certified divers who trained with Whale Dive Centre. Small groups, professional instruction, and ocean-minded community.</p>
                <div class="wd-testimonials-cta-buttons">
                    <a href="<?php echo home_url('/courses/'); ?>" class="wd-btn-primary">View Courses</a>
                    <a href="<?php echo home_url('/contact/'); ?>" class="wd-btn-secondary">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php get_template_part('template-parts/footer'); ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
