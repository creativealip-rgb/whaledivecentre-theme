<?php
/**
 * The header for our theme - FINAL CLEAN VERSION
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Sticky Footer Layout */
        #page.site {
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
        }
        .site-main {
            flex: 1 !important;
        }
        
        /* Page Headers - White text on blue/purple gradient background ONLY */
        .text-gradient,
        h1.text-gradient,
        h2.text-gradient {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            background: none !important;
            -webkit-background-clip: unset !important;
        }
        
        /* Hero section headings - ONLY with gradient background */
        .site-hero h1,
        .site-hero h2,
        .hero-section h1,
        .hero-section h2,
        [style*="background: linear-gradient"] h1,
        [style*="background: linear-gradient"] h2 {
            color: #ffffff !important;
        }
        
        /* NAVBAR - CLEAN & SIMPLE */
        .site-header {
            background: #ffffff !important;
            position: relative !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10) !important;
            margin-bottom: 0 !important;
            z-index: 50 !important;
        }
        
        .main-navigation-wrapper,
        .main-navigation {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        /* Premium homepage navbar */
        body.page-template-page-home-travel .site-header,
        body.page-template-page-home-travel #masthead,
        body.page-template-page-home-travel header.site-header {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.82) !important;
            border-bottom: 1px solid rgba(226,232,240,0.68) !important;
            box-shadow: 0 18px 38px rgba(15,23,42,0.10) !important;
            backdrop-filter: blur(18px) saturate(135%);
            -webkit-backdrop-filter: blur(18px) saturate(135%);
        }

        /* Solid fixed navbar for inner marketing pages */
        body.page-template-page-about .site-header,
        body.page-template-page-about #masthead,
        body.page-template-page-about header.site-header,
        body.page-template-page-contact .site-header,
        body.page-template-page-contact #masthead,
        body.page-template-page-contact header.site-header,
        body.page-template-page-tours .site-header,
        body.page-template-page-tours #masthead,
        body.page-template-page-tours header.site-header,
        body.page-template-page-blog .site-header,
        body.page-template-page-blog #masthead,
        body.page-template-page-blog header.site-header,
        body.page-template-page-booking-detail .site-header,
        body.page-template-page-booking-detail #masthead,
        body.page-template-page-booking-detail header.site-header {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 12px 28px rgba(15,23,42,0.08) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        
        .site-header.travel-fixed-header {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50 !important;
        }
        .site-header.travel-fixed-header.is-home-glass {
            background: rgba(255,255,255,0.82) !important;
            border-bottom: 1px solid rgba(226,232,240,0.68) !important;
            box-shadow: 0 18px 38px rgba(15,23,42,0.10) !important;
            backdrop-filter: blur(18px) saturate(135%);
            -webkit-backdrop-filter: blur(18px) saturate(135%);
        }
        .site-header.travel-fixed-header.is-solid-header {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 12px 28px rgba(15,23,42,0.08) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        /* Navbar .site-container - keep flex for desktop */
        .site-header .site-container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            height: 60px !important;
        }
        body.page-template-page-home-travel .site-header .site-container,
        body.page-template-page-about .site-header .site-container,
        body.page-template-page-contact .site-header .site-container,
        body.page-template-page-tours .site-header .site-container,
        body.page-template-page-blog .site-header .site-container,
        body.page-template-page-booking-detail .site-header .site-container {
            max-width: 1240px !important;
            padding: 0 24px !important;
            height: 74px !important;
        }
        
        .site-header.travel-fixed-header .site-container {
            max-width: 1240px !important;
            padding: 0 24px !important;
            height: 74px !important;
        }
        .brand-link {
            display: inline-flex !important;
            align-items: center !important;
            text-decoration: none !important;
            line-height: 0 !important;
        }
        .brand-link:hover {
            text-decoration: none !important;
        }
        .brand-logo {
            display: block !important;
            width: auto !important;
            height: 46px !important;
            max-width: min(40vw, 240px) !important;
            object-fit: contain !important;
            flex: 0 0 auto !important;
        }
        .brand-link .logo-name {
            display: none !important;
        }
        .site-header.travel-fixed-header .logo-text {
            font-size: 1.42rem !important;
            letter-spacing: -0.02em;
            color: #355F72 !important;
            -webkit-text-fill-color: #355F72 !important;
        }
        .site-header.travel-fixed-header.is-solid-header .logo-text,
        .site-header.travel-fixed-header.is-solid-header .logo-name,
        .site-header.travel-fixed-header:not(.is-home-glass) .logo-text,
        .site-header.travel-fixed-header:not(.is-home-glass) .logo-name,
        .site-header.is-solid-header .custom-logo-link,
        .site-header.is-solid-header .custom-logo-link img {
            color: #355F72 !important;
            -webkit-text-fill-color: #355F72 !important;
        }
        .site-header.travel-fixed-header .nav-menu-desktop {
            gap: 16px !important;
        }
        .gt-lang-switcher {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px;
            border: 1px solid #dbe4ea;
            border-radius: 999px;
            background: rgba(255,255,255,.82);
        }
        .gt-lang-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 32px;
            padding: 0 10px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .05em;
            color: #355F72 !important;
            -webkit-text-fill-color: #355F72 !important;
        }
        .gt-lang-link.is-active {
            background: #355F72;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            box-shadow: 0 8px 18px rgba(53,95,114,.24);
        }
        .site-header .gt-lang-switcher .gt-lang-link.is-active,
        .site-header.travel-fixed-header .gt-lang-switcher .gt-lang-link.is-active,
        body.page-template-page-home-travel .site-header .gt-lang-switcher .gt-lang-link.is-active {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            text-shadow: none !important;
        }
        .site-header.travel-fixed-header #primary-menu,
        .site-header.travel-fixed-header .nav-menu {
            gap: 22px !important;
        }
        .site-header.travel-fixed-header #primary-menu a,
        .site-header.travel-fixed-header .nav-menu a {
            color: #334155 !important;
            -webkit-text-fill-color: #334155 !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            letter-spacing: .01em;
            display: inline-flex;
            align-items: center;
            position: relative;
        }
        .site-header.travel-fixed-header #primary-menu a:hover,
        .site-header.travel-fixed-header .nav-menu a:hover {
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
        }
        .site-header.travel-fixed-header #primary-menu .current-menu-item > a,
        .site-header.travel-fixed-header .nav-menu .current-menu-item > a,
        .site-header.travel-fixed-header #primary-menu .current_page_item > a,
        .site-header.travel-fixed-header .nav-menu .current_page_item > a,
        .site-header.travel-fixed-header #primary-menu .current_page_parent > a,
        .site-header.travel-fixed-header .nav-menu .current_page_parent > a,
        .site-header.travel-fixed-header #primary-menu .current-menu-ancestor > a,
        .site-header.travel-fixed-header .nav-menu .current-menu-ancestor > a {
            color: #355F72 !important;
            -webkit-text-fill-color: #355F72 !important;
            font-weight: 800 !important;
            position: relative;
        }
        .site-header.travel-fixed-header .login-button {
            min-height: 44px !important;
            padding: 0 18px !important;
            margin-left: 8px !important;
            border-radius: 999px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            background: linear-gradient(135deg,#355F72 0%,#539294 52%,#E5A736 100%) !important;
            box-shadow: 0 14px 28px rgba(83,146,148,.24) !important;
        }

        /* Main content .site-container - block layout */
        .site-main .site-container {
            display: block !important;
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 20px !important;
        }
        
        /* Login/Register Pages - Content area only */
        body.page-template-page-login .site-main .site-container,
        body.page-template-page-register .site-main .site-container {
            max-width: 500px !important;
            margin: 4rem auto !important;
            padding: 0 1rem !important;
        }
        
        body.page-template-page-login .card-clean,
        body.page-template-page-register .card-clean {
            display: block !important;
            margin-bottom: 2rem !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        @media (max-width: 768px) {
            body.page-template-page-login .site-main .site-container,
            body.page-template-page-register .site-main .site-container {
                margin: 2rem 1rem !important;
            }
        }
        
        .site-main {
            min-height: calc(100vh - 60px - 400px) !important;
            padding-bottom: 80px !important;
        }
        
        .logo-text {
            font-size: 1.3rem !important;
            font-weight: 800 !important;
            color: #355F72 !important;
            -webkit-text-fill-color: #355F72 !important;
            text-decoration: none !important;
        }
        .logo-name {
            display: none !important;
        }
        body.page-template-page-home-travel .logo-text,
        body.page-template-page-about .logo-text,
        body.page-template-page-contact .logo-text,
        body.page-template-page-tours .logo-text,
        body.page-template-page-blog .logo-text,
        body.page-template-page-booking-detail .logo-text {
            font-size: 1.42rem !important;
            letter-spacing: -0.02em;
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
        }
        
        .nav-menu-desktop {
            display: flex !important;
            align-items: center !important;
            gap: 32px !important;
        }
        body.page-template-page-home-travel .nav-menu-desktop,
        body.page-template-page-about .nav-menu-desktop,
        body.page-template-page-contact .nav-menu-desktop,
        body.page-template-page-tours .nav-menu-desktop,
        body.page-template-page-blog .nav-menu-desktop,
        body.page-template-page-booking-detail .nav-menu-desktop {
            gap: 22px !important;
        }
        
        #primary-menu,
        .nav-menu {
            display: flex !important;
            gap: 32px !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body.page-template-page-home-travel #primary-menu,
        body.page-template-page-home-travel .nav-menu,
        body.page-template-page-about #primary-menu,
        body.page-template-page-about .nav-menu,
        body.page-template-page-contact #primary-menu,
        body.page-template-page-contact .nav-menu,
        body.page-template-page-tours #primary-menu,
        body.page-template-page-tours .nav-menu,
        body.page-template-page-blog #primary-menu,
        body.page-template-page-blog .nav-menu,
        body.page-template-page-booking-detail #primary-menu,
        body.page-template-page-booking-detail .nav-menu {
            gap: 22px !important;
        }
        
        #primary-menu a,
        .nav-menu a {
            color: #539294 !important;
            -webkit-text-fill-color: #539294 !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            font-size: 14px !important;
        }
        body.page-template-page-home-travel #primary-menu a,
        body.page-template-page-home-travel .nav-menu a,
        body.page-template-page-about #primary-menu a,
        body.page-template-page-about .nav-menu a,
        body.page-template-page-contact #primary-menu a,
        body.page-template-page-contact .nav-menu a,
        body.page-template-page-tours #primary-menu a,
        body.page-template-page-tours .nav-menu a,
        body.page-template-page-blog #primary-menu a,
        body.page-template-page-blog .nav-menu a,
        body.page-template-page-booking-detail #primary-menu a,
        body.page-template-page-booking-detail .nav-menu a {
            color: #334155 !important;
            -webkit-text-fill-color: #334155 !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            letter-spacing: .01em;
        }
        body.page-template-page-home-travel #primary-menu a:hover,
        body.page-template-page-home-travel .nav-menu a:hover,
        body.page-template-page-about #primary-menu a:hover,
        body.page-template-page-about .nav-menu a:hover,
        body.page-template-page-contact #primary-menu a:hover,
        body.page-template-page-contact .nav-menu a:hover,
        body.page-template-page-tours #primary-menu a:hover,
        body.page-template-page-tours .nav-menu a:hover,
        body.page-template-page-blog #primary-menu a:hover,
        body.page-template-page-blog .nav-menu a:hover,
        body.page-template-page-booking-detail #primary-menu a:hover,
        body.page-template-page-booking-detail .nav-menu a:hover {
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
        }
        body.page-template-page-home-travel #primary-menu .current-menu-item > a,
        body.page-template-page-home-travel .nav-menu .current-menu-item > a,
        body.page-template-page-about #primary-menu .current-menu-item > a,
        body.page-template-page-about .nav-menu .current-menu-item > a,
        body.page-template-page-contact #primary-menu .current-menu-item > a,
        body.page-template-page-contact .nav-menu .current-menu-item > a,
        body.page-template-page-tours #primary-menu .current-menu-item > a,
        body.page-template-page-tours .nav-menu .current-menu-item > a,
        body.page-template-page-blog #primary-menu .current-menu-item > a,
        body.page-template-page-blog .nav-menu .current-menu-item > a,
        body.page-template-page-booking-detail #primary-menu .current-menu-item > a,
        body.page-template-page-booking-detail .nav-menu .current-menu-item > a,
        body.page-template-page-home-travel #primary-menu .current_page_item > a,
        body.page-template-page-home-travel .nav-menu .current_page_item > a,
        body.page-template-page-about #primary-menu .current_page_item > a,
        body.page-template-page-about .nav-menu .current_page_item > a,
        body.page-template-page-contact #primary-menu .current_page_item > a,
        body.page-template-page-contact .nav-menu .current_page_item > a,
        body.page-template-page-tours #primary-menu .current_page_item > a,
        body.page-template-page-tours .nav-menu .current_page_item > a,
        body.page-template-page-blog #primary-menu .current_page_item > a,
        body.page-template-page-blog .nav-menu .current_page_item > a,
        body.page-template-page-booking-detail #primary-menu .current_page_item > a,
        body.page-template-page-booking-detail .nav-menu .current_page_item > a,
        body.page-template-page-home-travel #primary-menu .current_page_parent > a,
        body.page-template-page-home-travel .nav-menu .current_page_parent > a,
        body.page-template-page-about #primary-menu .current_page_parent > a,
        body.page-template-page-about .nav-menu .current_page_parent > a,
        body.page-template-page-contact #primary-menu .current_page_parent > a,
        body.page-template-page-contact .nav-menu .current_page_parent > a,
        body.page-template-page-tours #primary-menu .current_page_parent > a,
        body.page-template-page-tours .nav-menu .current_page_parent > a,
        body.page-template-page-blog #primary-menu .current_page_parent > a,
        body.page-template-page-blog .nav-menu .current_page_parent > a,
        body.page-template-page-booking-detail #primary-menu .current_page_parent > a,
        body.page-template-page-booking-detail .nav-menu .current_page_parent > a,
        body.page-template-page-home-travel #primary-menu .current-menu-ancestor > a,
        body.page-template-page-home-travel .nav-menu .current-menu-ancestor > a,
        body.page-template-page-about #primary-menu .current-menu-ancestor > a,
        body.page-template-page-about .nav-menu .current-menu-ancestor > a,
        body.page-template-page-contact #primary-menu .current-menu-ancestor > a,
        body.page-template-page-contact .nav-menu .current-menu-ancestor > a,
        body.page-template-page-tours #primary-menu .current-menu-ancestor > a,
        body.page-template-page-tours .nav-menu .current-menu-ancestor > a,
        body.page-template-page-blog #primary-menu .current-menu-ancestor > a,
        body.page-template-page-blog .nav-menu .current-menu-ancestor > a,
        body.page-template-page-booking-detail #primary-menu .current-menu-ancestor > a,
        body.page-template-page-booking-detail .nav-menu .current-menu-ancestor > a {
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
            position: relative;
        }
        body.page-template-page-home-travel #primary-menu .current-menu-item > a::after,
        body.page-template-page-home-travel .nav-menu .current-menu-item > a::after,
        body.page-template-page-about #primary-menu .current-menu-item > a::after,
        body.page-template-page-about .nav-menu .current-menu-item > a::after,
        body.page-template-page-contact #primary-menu .current-menu-item > a::after,
        body.page-template-page-contact .nav-menu .current-menu-item > a::after,
        body.page-template-page-tours #primary-menu .current-menu-item > a::after,
        body.page-template-page-tours .nav-menu .current-menu-item > a::after,
        body.page-template-page-blog #primary-menu .current-menu-item > a::after,
        body.page-template-page-blog .nav-menu .current-menu-item > a::after,
        body.page-template-page-booking-detail #primary-menu .current-menu-item > a::after,
        body.page-template-page-booking-detail .nav-menu .current-menu-item > a::after,
        body.page-template-page-home-travel #primary-menu .current_page_item > a::after,
        body.page-template-page-home-travel .nav-menu .current_page_item > a::after,
        body.page-template-page-about #primary-menu .current_page_item > a::after,
        body.page-template-page-about .nav-menu .current_page_item > a::after,
        body.page-template-page-contact #primary-menu .current_page_item > a::after,
        body.page-template-page-contact .nav-menu .current_page_item > a::after,
        body.page-template-page-tours #primary-menu .current_page_item > a::after,
        body.page-template-page-tours .nav-menu .current_page_item > a::after,
        body.page-template-page-blog #primary-menu .current_page_item > a::after,
        body.page-template-page-blog .nav-menu .current_page_item > a::after,
        body.page-template-page-booking-detail #primary-menu .current_page_item > a::after,
        body.page-template-page-booking-detail .nav-menu .current_page_item > a::after,
        body.page-template-page-home-travel #primary-menu .current_page_parent > a::after,
        body.page-template-page-home-travel .nav-menu .current_page_parent > a::after,
        body.page-template-page-about #primary-menu .current_page_parent > a::after,
        body.page-template-page-about .nav-menu .current_page_parent > a::after,
        body.page-template-page-contact #primary-menu .current_page_parent > a::after,
        body.page-template-page-contact .nav-menu .current_page_parent > a::after,
        body.page-template-page-tours #primary-menu .current_page_parent > a::after,
        body.page-template-page-tours .nav-menu .current_page_parent > a::after,
        body.page-template-page-blog #primary-menu .current_page_parent > a::after,
        body.page-template-page-blog .nav-menu .current_page_parent > a::after,
        body.page-template-page-booking-detail #primary-menu .current_page_parent > a::after,
        body.page-template-page-booking-detail .nav-menu .current_page_parent > a::after,
        body.page-template-page-home-travel #primary-menu .current-menu-ancestor > a::after,
        body.page-template-page-home-travel .nav-menu .current-menu-ancestor > a::after,
        body.page-template-page-about #primary-menu .current-menu-ancestor > a::after,
        body.page-template-page-about .nav-menu .current-menu-ancestor > a::after,
        body.page-template-page-contact #primary-menu .current-menu-ancestor > a::after,
        body.page-template-page-contact .nav-menu .current-menu-ancestor > a::after,
        body.page-template-page-tours #primary-menu .current-menu-ancestor > a::after,
        body.page-template-page-tours .nav-menu .current-menu-ancestor > a::after,
        body.page-template-page-blog #primary-menu .current-menu-ancestor > a::after,
        body.page-template-page-blog .nav-menu .current-menu-ancestor > a::after,
        body.page-template-page-booking-detail #primary-menu .current-menu-ancestor > a::after,
        body.page-template-page-booking-detail .nav-menu .current-menu-ancestor > a::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(135deg,#355F72,#E5A736);
        }
        
        #primary-menu a:hover {
            color: #355F72 !important;
        }
        
        .login-button {
            display: inline-flex !important;
            align-items: center !important;
            padding: 8px 16px !important;
            background: linear-gradient(135deg, #539294, #539294) !important;
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            margin-left: 16px !important;
        }
        body.page-template-page-home-travel .login-button,
        body.page-template-page-about .login-button,
        body.page-template-page-contact .login-button,
        body.page-template-page-tours .login-button,
        body.page-template-page-blog .login-button,
        body.page-template-page-booking-detail .login-button {
            min-height: 44px !important;
            padding: 0 18px !important;
            margin-left: 8px !important;
            border-radius: 999px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            background: linear-gradient(135deg,#355F72 0%,#539294 52%,#E5A736 100%) !important;
            box-shadow: 0 14px 28px rgba(83,146,148,.24) !important;
        }
        
        .mobile-menu-toggle {
            display: none !important;
        }
        
        @media (max-width: 1024px) {
            .site-header .site-container,
            body.page-template-page-home-travel .site-header .site-container,
            body.page-template-page-about .site-header .site-container,
            body.page-template-page-contact .site-header .site-container,
            body.page-template-page-tours .site-header .site-container,
            body.page-template-page-booking-detail .site-header .site-container {
                height: 68px !important;
                padding: 0 14px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 12px !important;
            }
            .nav-logo {
                flex: 1 1 auto !important;
                min-width: 0 !important;
            }
            .brand-link {
                max-width: calc(100vw - 92px) !important;
            }
            .brand-logo {
                width: auto !important;
                height: 38px !important;
                max-width: min(56vw, 220px) !important;
                flex-basis: auto !important;
            }
            .site-header .gt-lang-switcher {
                display: none !important;
            }
            .logo-text,
            body.page-template-page-home-travel .logo-text,
            body.page-template-page-about .logo-text,
            body.page-template-page-contact .logo-text,
            body.page-template-page-tours .logo-text,
            body.page-template-page-booking-detail .logo-text {
                display: block !important;
                font-size: 1.05rem !important;
                line-height: 1.1 !important;
                max-width: 100% !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            .mobile-menu-toggle {
                display: inline-flex !important;
                flex: 0 0 44px !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .nav-menu-desktop,
            #primary-menu,
            .nav-menu,
            .site-header .login-button {
                display: none !important;
            }
        }

        #mobile-primary-menu {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            display: grid;
            gap: 8px;
        }
        #mobile-primary-menu li { margin: 0 !important; }
        #mobile-primary-menu a {
            display: block;
            text-decoration: none;
            color: #539294 !important;
            -webkit-text-fill-color: #539294 !important;
            font-weight: 600;
            font-size: 17px;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #DCE9E6;
            background: #ffffff;
        }
        #mobile-primary-menu a:hover { background: #EEF5F4; color: #355F72 !important; }
        
        /* Keep membership/login button text white on all pages */
        .site-header .login-button,
        .site-header .login-button *,
        body.page-template-page-home-travel .site-header .login-button,
        body.page-template-page-home-travel .site-header .login-button * {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

    </style>

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/favicon-32.png' ); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/favicon-192.png' ); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/favicon-180.png' ); ?>">
    <link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/favicon.ico' ); ?>">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <?php
    $is_home_glass_header = is_front_page() || is_page_template('page-home-travel.php');
    $is_fixed_marketing_header = $is_home_glass_header
        || is_page_template('page-about.php')
        || is_page_template('page-contact.php')
        || is_page_template('page-tours.php')
        || is_page_template('page-booking-detail.php')
        || is_page_template('page-blog.php')
        || is_singular('tour')
        || is_singular('post')
        || is_home()
        || is_category()
        || is_tag()
        || is_tax('travel_category')
        || is_post_type_archive('tour');
    $header_classes = ['site-header'];
    if ($is_fixed_marketing_header) {
        $header_classes[] = 'travel-overlay';
        $header_classes[] = 'travel-fixed-header';
        $header_classes[] = $is_home_glass_header ? 'is-home-glass' : 'is-solid-header';
    }
    ?>
    <header id="masthead" class="<?php echo esc_attr(implode(' ', $header_classes)); ?>">
        <div class="main-navigation-wrapper">
            <nav id="site-navigation" class="main-navigation">
                <div class="site-container">
                    <div class="nav-logo">
                        <a href="<?php echo esc_url( function_exists('contenly_localized_url') ? contenly_localized_url('/') : home_url( '/' ) ); ?>" class="brand-link logo-text" aria-label="<?php echo esc_attr( get_bloginfo('name') ); ?>">
                            <img class="brand-logo" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/logo.jpg' ); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?> logo">
                            <span class="logo-name"><?php bloginfo('name'); ?></span>
                        </a>
                    </div>
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Buka menu" style="background: rgba(255,255,255,0.92) !important; border: 1px solid rgba(203,213,225,0.95) !important; border-radius: 12px; width: 44px; height: 44px; cursor: pointer; flex-direction: column; align-items: center; justify-content: center; gap: 4px; box-shadow: 0 10px 22px rgba(15,23,42,.08);">
                        <span style="display: block; width: 20px; height: 2px; background: #539294; border-radius: 2px;"></span>
                        <span style="display: block; width: 20px; height: 2px; background: #539294; border-radius: 2px;"></span>
                        <span style="display: block; width: 20px; height: 2px; background: #539294; border-radius: 2px;"></span>
                    </button>
                    <div class="nav-menu-desktop">
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'nav-menu', 'container' => false, 'fallback_cb' => false, ) ); ?>
                        <?php echo contenly_render_language_switcher('desktop-lang-switcher'); ?>
                        <?php if ( is_user_logged_in() ) : $current_user = wp_get_current_user(); ?>
                        <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>" class="login-button"><?php echo esc_html(contenly_tr('Membership', 'Membership')); ?> - <?php echo esc_html($current_user->display_name); ?></a>
                        <?php else : ?>
                        <a href="<?php echo esc_url(contenly_localized_url('/login/')); ?>" class="login-button">🔐 <?php echo esc_html(contenly_tr('Masuk', 'Login')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
        <div id="mobile-menu-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.32); backdrop-filter: blur(1px); z-index: 9999;" onclick="toggleMobileMenu()"></div>
        <div id="mobile-menu" style="display: none; position: fixed; top: 0; right: -100%; width: 85%; max-width: 360px; height: 100vh; background: #ffffff; z-index: 10000; transition: right 0.3s ease; box-shadow: -10px 0 30px rgba(15,23,42,.18);">
            <div style="padding: 20px; height:100%; overflow-y:auto;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
                    <span style="font-size: 1.25rem; font-weight: 700;">Menu</span>
                    <button onclick="toggleMobileMenu()" style="background: #f1f5f9; border: none; border-radius: 10px; width: 40px; height: 40px; cursor: pointer; font-size: 1.3rem; color:#334155;">✕</button>
                </div>
                <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'mobile-primary-menu', 'container' => false, 'fallback_cb' => false, ) ); ?>
                <div style="margin-top:14px; display:flex; justify-content:flex-start;">
                    <?php echo contenly_render_language_switcher('mobile-lang-switcher'); ?>
                </div>
                <div style="margin-top:14px; padding-top:14px; border-top:1px solid #e2e8f0;">
                    <?php if ( is_user_logged_in() ) : $current_user = wp_get_current_user(); ?>
                        <a href="<?php echo esc_url(contenly_localized_url('/dashboard/')); ?>" style="display:block; text-decoration:none; padding:11px 12px; border-radius:10px; color:#355F72; font-weight:700; background:#EEF5F4;"><?php echo esc_html(contenly_tr('Membership', 'Membership')); ?> - <?php echo esc_html($current_user->display_name); ?></a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(contenly_localized_url('/login/')); ?>" style="display:block; text-decoration:none; padding:11px 12px; border-radius:10px; color:#355F72; font-weight:700; background:#EEF5F4;">🔐 <?php echo esc_html(contenly_tr('Masuk', 'Login')); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        const toggles = document.querySelectorAll('.mobile-menu-toggle');
        if (!menu || !overlay) return;

        const isOpen = menu.style.display === 'block';
        if (isOpen) {
            menu.style.right = '-100%';
            overlay.style.display = 'none';
            toggles.forEach(btn => btn.style.visibility = 'visible');
            document.body.style.overflow = '';
            setTimeout(() => { menu.style.display = 'none'; }, 250);
        } else {
            menu.style.display = 'block';
            overlay.style.display = 'block';
            toggles.forEach(btn => btn.style.visibility = 'hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => { menu.style.right = '0'; });
        }
    }

    document.addEventListener('click', function(e) {
        const link = e.target.closest('#mobile-primary-menu a');
        if (link) toggleMobileMenu();
    });
    </script>
