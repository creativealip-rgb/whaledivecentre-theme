/**
 * Contenly Theme - Main JavaScript
 * Premium Design System - Enhanced Animations & Interactions
 *
 * @package Contenly_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';

    // ========================================
    // INITIALIZATION
    // ========================================
    
    const DOM = {
        header: document.querySelector('.site-header'),
        mobileMenuToggle: document.querySelector('.mobile-menu-toggle'),
        navMenu: document.querySelector('.nav-menu'),
        body: document.body
    };

    // ========================================
    // MOBILE MENU
    // ========================================
    
    function initMobileMenu() {
        if (!DOM.mobileMenuToggle || !DOM.navMenu) return;

        DOM.mobileMenuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            this.setAttribute('aria-expanded', !expanded);
            DOM.navMenu.classList.toggle('nav-menu--active');
            DOM.body.classList.toggle('menu-open');
            
            // Animate hamburger
            animateHamburger(!expanded);
        });

        // Close on outside click
        document.addEventListener('click', function(event) {
            if (!DOM.navMenu.contains(event.target) && 
                !DOM.mobileMenuToggle.contains(event.target)) {
                closeMobileMenu();
            }
        });

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });
    }

    function closeMobileMenu() {
        if (DOM.navMenu) {
            DOM.navMenu.classList.remove('nav-menu--active');
            DOM.mobileMenuToggle?.setAttribute('aria-expanded', 'false');
            DOM.body.classList.remove('menu-open');
            animateHamburger(false);
        }
    }

    function animateHamburger(isActive) {
        const icon = DOM.mobileMenuToggle?.querySelector('.hamburger-icon');
        if (!icon) return;

        if (isActive) {
            icon.style.background = 'transparent';
            icon.style.transform = 'rotate(45deg)';
        } else {
            icon.style.background = 'currentColor';
            icon.style.transform = 'rotate(0)';
        }
    }

    // ========================================
    // HEADER SCROLL EFFECT
    // ========================================
    
    function initHeaderScroll() {
        if (!DOM.header) return;

        let lastScroll = 0;
        let ticking = false;

        window.addEventListener('scroll', function() {
            lastScroll = window.scrollY;

            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll(lastScroll);
                    ticking = false;
                });

                ticking = true;
            }
        });

        function handleScroll(scrollY) {
            if (scrollY > 50) {
                DOM.header.classList.add('scrolled');
            } else {
                DOM.header.classList.remove('scrolled');
            }
        }
    }

    // ========================================
    // SMOOTH SCROLL
    // ========================================
    
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }

    // ========================================
    // LAZY LOAD IMAGES
    // ========================================
    
    function initLazyLoad() {
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                });
            }, {
                threshold: 0,
                rootMargin: '0px 0px 200px 0px'
            });

            images.forEach(function(image) {
                imageObserver.observe(image);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            images.forEach(function(img) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        }
    }

    // ========================================
    // STAGGERED ANIMATIONS
    // ========================================
    
    function initStaggeredAnimations() {
        const animatedElements = document.querySelectorAll('.animate-fade-up, .card-clean, .benefit-item');
        
        if (!('IntersectionObserver' in window)) {
            animatedElements.forEach(el => el.style.opacity = '1');
            return;
        }

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        animatedElements.forEach(function(el, index) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(el);
        });
    }

    // ========================================
    // FORM VALIDATION
    // ========================================
    
    function initFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');
                        
                        // Add error message
                        let errorMsg = field.parentNode.querySelector('.error-message');
                        if (!errorMsg) {
                            errorMsg = document.createElement('span');
                            errorMsg.className = 'error-message';
                            errorMsg.textContent = 'This field is required';
                            errorMsg.style.cssText = 'color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;';
                            field.parentNode.appendChild(errorMsg);
                        }
                    } else {
                        field.classList.remove('error');
                        const errorMsg = field.parentNode.querySelector('.error-message');
                        if (errorMsg) errorMsg.remove();
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Remove error on input
            form.querySelectorAll('[required]').forEach(function(field) {
                field.addEventListener('input', function() {
                    this.classList.remove('error');
                    const errorMsg = this.parentNode.querySelector('.error-message');
                    if (errorMsg) errorMsg.remove();
                });
            });
        });
    }

    // ========================================
    // SEARCH ENHANCEMENT
    // ========================================
    
    function initSearchEnhancement() {
        const searchForms = document.querySelectorAll('.search-form');
        
        searchForms.forEach(function(form) {
            const searchInput = form.querySelector('.search-field');
            const searchButton = form.querySelector('.search-submit');

            if (searchInput && searchButton) {
                searchButton.setAttribute('aria-label', 'Search');
                
                // Auto-focus search input on click
                searchButton.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        searchInput.focus();
                    }
                });
            }
        });
    }

    // ========================================
    // PERFORMANCE OPTIMIZATION
    // ========================================
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // ========================================
    // PAGE LOAD HANDLING
    // ========================================
    
    function initPageLoad() {
        // Add loading class
        DOM.body.classList.add('loading');

        // Remove loading class when page is fully loaded
        window.addEventListener('load', function() {
            DOM.body.classList.remove('loading');
            DOM.body.classList.add('loaded');
            
            // Trigger entrance animations
            setTimeout(() => {
                initStaggeredAnimations();
            }, 100);
        });

        // Fallback if load event already fired
        if (document.readyState === 'complete') {
            DOM.body.classList.remove('loading');
            DOM.body.classList.add('loaded');
        }
    }

    // ========================================
    // ACCESSIBILITY ENHANCEMENTS
    // ========================================
    
    function initAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.setAttribute('tabindex', '-1');
                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }

        // Handle reduced motion preference
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--transition-fast', '0ms');
            document.documentElement.style.setProperty('--transition-base', '0ms');
            document.documentElement.style.setProperty('--transition-slow', '0ms');
        }
    }

    // ========================================
    // INITIALIZE ALL
    // ========================================

    // ========================================
    // COURSE & EQUIPMENT FILTERS
    // ========================================
    
    function initFilters() {
        // Course filters
        const courseFilters = document.getElementById('courseFilters');
        const courseGrid = document.getElementById('courseGrid');
        
        if (courseFilters && courseGrid) {
            const courseChips = courseFilters.querySelectorAll('.wd-chip');
            const courseCards = courseGrid.querySelectorAll('.wd-course-card, .wd-detail-card');
            
            courseChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active state
                    courseChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter cards
                    courseCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = '';
                        } else {
                            const cardLevel = card.getAttribute('data-level');
                            if (cardLevel === filter) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        }
        
        // Equipment filters
        const equipFilters = document.getElementById('equipFilters');
        const equipGrid = document.getElementById('equipGrid');
        
        if (equipFilters && equipGrid) {
            const equipChips = equipFilters.querySelectorAll('.wd-chip');
            const equipCards = equipGrid.querySelectorAll('.wd-equip-card, .wd-detail-card');
            
            equipChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active state
                    equipChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter cards
                    equipCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = '';
                        } else {
                            const cardCategory = card.getAttribute('data-cat');
                            if (cardCategory === filter) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        }
    }
    
    function init() {
        initPageLoad();
        initMobileMenu();
        initHeaderScroll();
        initSmoothScroll();
        initLazyLoad();
        initFormValidation();
        initSearchEnhancement();
        initAccessibility();
        initFilters();
        
        // Initialize after a short delay for better UX
        setTimeout(() => {
            initStaggeredAnimations();
        }, 500);

        // Log initialization
        console.log('✨ Contenly Theme initialized with Premium Design System');
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

