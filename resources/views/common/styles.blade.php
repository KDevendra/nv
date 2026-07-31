<style>
    /* Apply base fonts */
    body {
        font-family: 'Nunito Sans', sans-serif;
        overflow-x: hidden;  
        font-size: 1.125rem;
        line-height: 1.7;
    }
    
    /* Main Heading Styling */
    h1, h2, h5, h6 {
        font-family: 'Forum', cursive;
        font-size: 3rem !important;
        font-weight: 400;
        font-style: normal;
        line-height: 0.9166em;
        text-decoration: none;
        text-transform: none;
        margin-top: 0.17em !important;
        margin-bottom: 0.17em !important;
    }
    
    /* Preloader Styles */
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
    }
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border-left-color: #0B2C3D;
        animation: spin 1s linear infinite;
    }
    body.loaded #preloader {
         opacity: 0;
         visibility: hidden;
    }

    select:disabled,
    select[disabled],
    input:disabled,
    input[disabled],
    textarea:disabled,
    textarea[disabled] {
        background-color: #f3f4f6 !important;
        color: #374151 !important;
        cursor: not-allowed !important;
    }

    /* Style for the active search tab */
    .search-tab-button {
        font-family: 'Raleway', sans-serif;
        font-weight: 700;
    }
    .search-tab.active {
        border-bottom-width: 3px;
        border-color: #0B2C3D;
        color: #0B2C3D;
        font-weight: 700;
    }
    
    /* Style for the sticky header */
    header {
         transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    
    /* Header alignment fix */
    header .flex.items-center {
        align-items: center !important;
    }
    
    header nav {
        display: flex;
        align-items: center;
    }
    
    .header-nav-link, .header-button {
        font-family: 'Raleway', sans-serif;
        font-weight: 700;
    }
    header.scrolled {
        background-color: #0b2c3d;
    }
    header:not(.scrolled) {
         background-color: rgb(11 44 61 / 52%);
    }
    header:not(.scrolled) .header-nav-link {
        color: #ffffff;
    }
    header:not(.scrolled) .header-nav-link:hover {
        color: #B39359;
    }
    
    /* Scrolled state for nav links */
     header.scrolled .header-nav-link {
        color: #d0cdc8;
   }
     header.scrolled .header-nav-link:hover {
         color:#d0cdc8;
     }
     header.scrolled #mobile-menu-button {
         color: #0B2C3D;
     }
     header:not(.scrolled) #mobile-menu-button {
        color: #ffffff;
     }
     #mobile-menu a {
         font-family: 'Raleway', sans-serif;
         font-weight: 700;
     }

     .card-grid-container:hover .card-item:not(:hover) {
         filter: grayscale(80%) blur(2px);
         opacity: 0.7;
         transform: scale(0.98);
         transition: all 0.3s ease-in-out;
     }
     .card-grid-container .card-item {
         transition: all 0.3s ease-in-out;
     }

     .card-grid-container .card-item:hover {
         filter: none;
         opacity: 1;
         transform: translateY(-5px);
         box-shadow: 0 15px 30px rgba(0,0,0,0.1);
     }
     
     /* Specific hover effects */
     .category-grid .category-card:hover {
         border-color: #B39359;
         background-color: rgba(255, 255, 255, 0.1);
     }
     .category-grid .category-card:hover h3 {
         color: #B39359;
     }

    /* Hover effect for City Gallery */
    .city-card .city-image {  
        transition: transform 0.4s ease-in-out;
    }
    .city-card:hover .city-image {
        transform: scale(1.1);
    }
     .city-card .overlay {
         transition: background-color 0.3s ease-in-out;
     }
     .city-card:hover .overlay {
         background-color: rgba(0,0,0,0.5);  
     }

    /* Testimonial Carousel Styles */
    .testimonial-carousel {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }
    .testimonial-slide {
        flex: 0 0 100%;  
        padding: 0 1rem;  
        box-sizing: border-box;
    }
    @media (min-width: 768px) {  
        .testimonial-slide {
            flex: 0 0 50%;  
        }
    }
    @media (min-width: 1024px) {  
        .testimonial-slide {
            flex: 0 0 33.333%;  
        }
    }
    .testimonial-dots button {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: rgba(11, 44, 61, 0.2);
        transition: background-color 0.3s ease;
         margin: 0 4px;  
    }
    .testimonial-dots button.active {
        background-color: #0B2C3D;
        transform: scale(1.1);  
    }

    /* Footer links hover */
    .footer-link:hover {
        color: #B39359;
        text-decoration: underline;
    }
    
    /* General Link Hover */
    a {
        transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
    }
    
    /* Line clamp utility */
    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;    
    }

    /* Subheading Style */
    .section-subheading {
       font-family: 'Raleway', sans-serif;
       font-style: normal; 
       color: #9a7a3e;  
       font-size: 0.875rem;
       line-height: 1.5em; 
       display: inline-block;
       font-weight: 600;
       text-transform: uppercase;
       letter-spacing: 0.12em;
    }
     .section-subheading-dark-bg {
       font-family: 'Raleway', sans-serif;
       font-style: normal; 
       color: #B39359;  
       font-size: 0.875rem;
       line-height: 1.5em;
       display: inline-block;
       font-weight: 600;
       text-transform: uppercase;
       letter-spacing: 0.12em;
     }
     
     /* Hero h1 margins */
     .hero-text-shadow {
        margin-top: 0;
        margin-bottom: 0.57em;
     }

    /* Premium Background Patterns */
     .bg-pattern-white {
         background-color: #ffffff;
         background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle fill='%23FBF8F2' opacity='0.7' cx='10' cy='10' r='1.5'/%3E%3C/svg%3E");
         background-size: 15px 15px;
     }
    .bg-pattern-light {
        background-color: #FBF8F2;
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle fill='%23B39359' opacity='0.08' cx='10' cy='10' r='2'/%3E%3C/svg%3E");
        background-size: 12px 12px;
    }

     /* Scroll Animation */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        transition-delay: var(--animation-delay, 0s);
    }
    .animate-on-scroll.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Button Wipe Animation */
    .btn-anim {
        position: relative;
        z-index: 1;
        overflow: hidden;
        transition: color 0.4s ease-out, background-color 0.4s ease-out, border-color 0.4s ease-out !important;  
        border-width: 1px;
        border-style: solid;
        font-family: 'Raleway', sans-serif;
        font-weight: 700;
    }

    .btn-anim::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        transition: width 0.4s ease-in-out;
        z-index: -1;
        border-radius: 9999px;
    }

    /* Button Style 1: On Light BG */
    .btn-light-bg {
        background-color: #B39359;
        color: #ffffff;
        border-color: #B39359;
    }
    .btn-light-bg::before {
        background-color: #0B2C3D;
    }
    .btn-light-bg:hover {
        color: #ffffff !important;
        border-color: #0B2C3D;
    }
    .btn-light-bg:hover::before {
        width: 100%;
    }

    /* Button Style 2: On Dark BG */
    .btn-dark-bg {
        background-color: #B39359;
        color: #ffffff;
        border-color: #B39359;
    }
    .btn-dark-bg::before {
        background-color: #ffffff;
    }
    .btn-dark-bg:hover {
        color: #0B2C3D !important;
        border-color: #ffffff;
    }
    .btn-dark-bg:hover::before {
        width: 100%;
    }
    
    /* Mobile Menu Button */
    .btn-mobile-menu {
        background-color: #B39359;
        color: #ffffff;
        border-color: #B39359;
    }
    .btn-mobile-menu::before {
        background-color: #0B2C3D;
    }
    .btn-mobile-menu:hover {
        color: #ffffff !important;
        border-color: #0B2C3D;
    }
    .btn-mobile-menu:hover::before {
        width: 100%;
    }

     /* Video Section Specifics */
     .video-popup-button {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         background-color: rgba(255, 255, 255, 0.9);
         width: 80px;
         height: 80px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         cursor: pointer;
         transition: all 0.3s ease;
         box-shadow: 0 10px 30px rgba(0,0,0,0.2);
     }
     .video-popup-button:hover {
         transform: translate(-50%, -50%) scale(1.1);
         background-color: #ffffff;
     }
     .video-popup-button svg {
         width: 32px;
         height: 32px;
         color: #0B2C3D;
         margin-left: 4px;
     }

     /* Custom background image for categories section */
     #categories-section {
         position: relative;
         overflow: hidden;
     }

     #categories-section::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;  
         height: 100%;
         background-image: url('{{ asset('main/images/bg/real-estate-shape.webp') }}');  
         background-repeat: no-repeat;
         background-position: center center;
         background-size: 80% auto;  
         background-attachment: fixed;
         z-index: 0;  
         opacity: 0.1;
         pointer-events: none;  
     }

     /* Responsive adjustments for the background image */
     @media (min-width: 768px) {
         #categories-section::before {
             background-size: 60% auto;  
         }
     }
     @media (min-width: 1024px) {
         #categories-section::before {
             background-size: 50% auto;  
         }
     }

     /* Hero Text Shadow */
     .hero-text-shadow {
        text-shadow: 1px 2px 2px rgba(0, 0, 0, 0.7);
     }

     /* Inquiry Form Section */
    #inquiry-section {
        background-image: url('{{ asset('main/images/bg/cta-bg.jpg') }}');
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }

    #inquiry-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(11, 44, 61, 0.7);
        z-index: 1;
    }

    #inquiry-section > div {
        position: relative;
        z-index: 2;
    }

    /* Form styling */
    .inquiry-form-input {
        font-family: 'Nunito Sans', sans-serif;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        height: 3.5rem;
        font-size: 1.125rem;
    }

    .inquiry-form-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .inquiry-form-input:focus {
        outline: none;
        border-color: #B39359;
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    /* Dropdown select styling within inquiry form */
    select.inquiry-form-input {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.8)' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }
    
    select.inquiry-form-input option {
        background-color: #0b2c3d;
        color: #ffffff;
        font-size: 1rem;
    }
    
    select.inquiry-form-input option:first-child {
        color: rgba(255, 255, 255, 0.6);
    }

    /* FAQ Section */
    .faq-item {
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .faq-question {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        font-family: 'Raleway', sans-serif;
        font-weight: 700;
        color: #ffffff;
        background-color: #B39359;
        transition: background-color 0.3s ease-in-out;
    }
    .faq-question:hover {
        background-color: #9a7c4d;
    }
    .faq-question[aria-expanded="true"] {
        background-color: #0B2C3D;
        color: #ffffff;
    }
    .faq-question[aria-expanded="true"]:hover {
         background-color: #0B2C3D;
    }
    .faq-answer {
        overflow: hidden;
        transition: max-height 0.5s ease-in-out, padding 0.5s ease-in-out;
        color: #374151;
        background-color: #ffffff;
    }
    .faq-answer p {
        padding-top: 1rem;
    }
    .faq-icon {
        transition: transform 0.3s ease-in-out;
        flex-shrink: 0;
        color: #ffffff;
    }
    .faq-question[aria-expanded="true"] .faq-icon {
        transform: rotate(45deg);
    }
    /* Alpine transitions for accordion */
    [x-cloak] { display: none !important; }
</style>