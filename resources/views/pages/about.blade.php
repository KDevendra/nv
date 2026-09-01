@extends('layouts.app')
@section('title', 'About Us - ZendoIndia')
@section('content')
    <!-- ABOUT PAGE BANNER -->
    <style>
        .about-banner-section {
            position: relative;
            background-image: url('https://zendoindia.com/new-home/zendo/assets/images/bg/cta-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 160px 0 80px;
            color: #fff;
        }

        .about-banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 32, 39, 0.88), rgba(32, 58, 67, 0.85), rgba(44, 83, 100, 0.82));
        }

        .about-banner-container {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .about-banner-left {
            max-width: 600px;
        }

        .about-banner-heading {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .about-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            margin-top: 12px;
        }

        .about-breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .about-breadcrumb span {
            color: #ffffff;
        }

        .about-breadcrumb p {
            margin: 0;
            opacity: 0.8;
        }

        @media (max-width: 767px) {
            .about-banner-heading {
                font-size: 32px;
            }

            .about-breadcrumb {
                font-size: 14px;
            }

            .about-banner-section {
                padding: 130px 0 60px;
            }
        }
    </style>
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div class="about-banner-left">
                <h1 class="about-banner-heading">About Us</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <p>About Us</p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
        <!-- CHANGED bg -->
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="section-subheading" style="font-weight: 600; color: #9a7a3e;">Our Company</span>
                <h2 class="font-heading text-zendo-navy" style="font-family: 'Nunito Sans', sans-serif !important; font-weight: 700 !important;">{{ $aboutPage->section_subtitle ?? 'Building Trust, Delivering Excellence' }}
                </h2>
            </div>
            <!-- Feature Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 card-grid-container">
                <!-- Card 1: Who We Are -->
                <div class="why-choose-card card-item bg-white rounded-lg shadow-xl p-8 text-center border border-gray-100">
                    <div class="w-20 h-20 mx-auto bg-zendo-light-bg rounded-full flex items-center justify-center mb-6">
                        @if ($aboutPage && $aboutPage->who_we_are_icon_url)
                            <img src="{{ $aboutPage->who_we_are_icon_url }}" alt="Who We Are Icon"
                                class="w-10 h-10 text-zendo-gold">
                        @else
                            <img src="{{ asset('assets/icons/trustworthiness.svg') }}" alt="Who We Are Icon"
                                class="w-10 h-10 text-zendo-gold">
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-3">
                        {{ $aboutPage->who_we_are_title ?? 'Who We Are' }}</h3>
                    <p class="text-gray-600 font-body leading-relaxed">
                        {{ $aboutPage->who_we_are_description ?? 'Aliquam dictum elit vitae mauris facilisis at dictum urna dignissim donec vel lectus vel felis.' }}
                    </p>
                </div>
                <!-- Card 2: Mission -->
                <div class="why-choose-card card-item bg-white rounded-lg shadow-xl p-8 text-center border border-gray-100">
                    <div class="w-20 h-20 mx-auto bg-zendo-light-bg rounded-full flex items-center justify-center mb-6">
                        @if ($aboutPage && $aboutPage->mission_icon_url)
                            <img src="{{ $aboutPage->mission_icon_url }}" alt="Mission Icon"
                                class="w-10 h-10 text-zendo-gold">
                        @else
                            <img src="{{ asset('assets/icons/residential.svg') }}" alt="Mission Icon"
                                class="w-10 h-10 text-zendo-gold">
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-3">
                        {{ $aboutPage->mission_title ?? 'Mission' }}</h3>
                    <p class="text-gray-600 font-body leading-relaxed">
                        {{ $aboutPage->mission_description ?? 'Aliquam dictum elit vitae mauris facilisis at dictum urna dignissim donec vel lectus vel felis.' }}
                    </p>
                </div>
                <!-- Card 3: Vision -->
                <div class="why-choose-card card-item bg-white rounded-lg shadow-xl p-8 text-center border border-gray-100">
                    <div class="w-20 h-20 mx-auto bg-zendo-light-bg rounded-full flex items-center justify-center mb-6">
                        @if ($aboutPage && $aboutPage->vision_icon_url)
                            <img src="{{ $aboutPage->vision_icon_url }}" alt="Vision Icon"
                                class="w-10 h-10 text-zendo-gold">
                        @else
                            <img src="{{ asset('assets/icons/coin.svg') }}" alt="Vision Icon"
                                class="w-10 h-10 text-zendo-gold">
                        @endif
                    </div>
                    <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-3">
                        {{ $aboutPage->vision_title ?? 'Vision' }}</h3>
                    <p class="text-gray-600 font-body leading-relaxed">
                        {{ $aboutPage->vision_description ?? 'Aliquam dictum elit vitae mauris facilisis at dictum urna dignissim donec vel lectus vel felis.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- tab section -->
    <style>
        .abt-value-section {
            padding: 80px 0;
            background: #fbf8f2;
        }

        .abt-value-container {
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .abt-value-main-headings {
            font-size: 40px;
            font-weight: 700;
            color: #0b2c3d;
            margin-bottom: 40px !important;
        }

        .abt-value-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 40px;
        }

        .abt-value-tabs {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .abt-tab-item {
            font-size: 18px;
            padding: 10px 0;
            cursor: pointer;
            position: relative;
            color: #0b2c3d;
            font-weight: 500;
        }

        .abt-tab-item .abt-arrow {
            opacity: 0;
            transition: 0.3s;
            margin-left: 6px;
        }

        .abt-tab-item.active {
            color: #b39359;
            font-weight: 700;
        }

        .abt-tab-item.active .abt-arrow {
            opacity: 1;
        }

        .abt-value-content h3 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #0b2c3d;
        }

        .abt-value-content p {
            line-height: 1.7;
            font-size: 16px;
            color: #444;
        }

        .abt-icon-card {
            padding: 40px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .abt-icon-card img {
            width: 140px;
        }

        @media (max-width: 992px) {
            .abt-value-grid {
                grid-template-columns: 1fr;
            }

            .abt-icon-card img {
                width: 120px;
            }
        }
    </style>
    <section id="abtValueSection" class="abt-value-section">
        <div class="abt-value-container">
            <h2 class="abt-value-main-headings">{{ $aboutPage->values_heading ?? 'Our Values' }}</h2>
            <div class="abt-value-grid">
                <!-- LEFT TABS -->
                <div class="abt-value-tabs">
                    <div class="abt-tab-item active" data-tab="who">
                        Who We Are <span class="abt-arrow">→</span>
                    </div>
                    <div class="abt-tab-item" data-tab="mission">
                        Our Mission <span class="abt-arrow">→</span>
                    </div>
                    <div class="abt-tab-item" data-tab="vision">
                        Our Vision <span class="abt-arrow">→</span>
                    </div>
                    <div class="abt-tab-item" data-tab="pro">
                        Teamwork <span class="abt-arrow">→</span>
                    </div>
                </div>
                <!-- CONTENT AREA -->
                <div class="abt-value-content">
                    <div class="abt-content-box" id="tab-who">
                        <h3>Who We Are</h3>
                        <p>{{ $aboutPage->values_who_we_are ?? 'We are a passionate and dedicated team committed to delivering high-quality work and creating meaningful value.' }}
                        </p>
                    </div>
                    <div class="abt-content-box" id="tab-mission" style="display:none;">
                        <h3>Our Mission</h3>
                        <p>{{ $aboutPage->values_mission ?? 'Our mission is to provide innovative, customer-focused solutions that inspire growth and long-term success.' }}
                        </p>
                    </div>
                    <div class="abt-content-box" id="tab-vision" style="display:none;">
                        <h3>Our Vision</h3>
                        <p>{{ $aboutPage->values_vision ?? 'Our vision is to become a leader in our industry through innovation, dedication, and an unwavering commitment to excellence.' }}
                        </p>
                    </div>
                    <div class="abt-content-box" id="tab-pro" style="display:none;">
                        <h3>Teamwork</h3>
                        <p>{{ $aboutPage->values_teamwork ?? 'Our vision is to become a leader in our industry through innovation, dedication, and an unwavering commitment to excellence.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const tabs = document.querySelectorAll(".abt-tab-item");

            const icons = {
                who: "{{ $aboutPage->values_who_we_are_image_url ?? 'https://cdn-icons-png.flaticon.com/512/992/992651.png' }}",
                mission: "{{ $aboutPage->values_mission_image_url ?? 'https://cdn-icons-png.flaticon.com/512/1828/1828884.png' }}",
                vision: "{{ $aboutPage->values_vision_image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}",
                pro: "{{ $aboutPage->values_teamwork_image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}"
            };

            tabs.forEach(tab => {
                tab.addEventListener("click", function() {

                    // Remove active from previous
                    tabs.forEach(t => t.classList.remove("active"));
                    this.classList.add("active");

                    let target = this.getAttribute("data-tab");

                    // Hide all content
                    document.querySelectorAll(".abt-content-box").forEach(box => box.style.display =
                        "none");

                    // Show active content
                    document.getElementById("tab-" + target).style.display = "block";

                    // Update icon
                    document.getElementById("abtIconImage").src = icons[target];
                });
            });
        });
    </script>
    <!-- logo section -->
    <!-- CLIENTS SECTION - Auto Scrolling Carousel -->
    <section class="clients-section">
        <div class="clients-container">
            <h2 class="clients-headings">Our Clients</h2>
            <div class="clients-carousel-wrapper">
                <div class="clients-carousel-track">
                    @forelse($clients as $client)
                        <div class="client-slide">
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }}">
                        </div>
                    @empty
                        <p class="text-gray-600" style="padding:20px;">No clients added yet.</p>
                    @endforelse
                    {{-- Duplicate for seamless infinite scroll --}}
                    @if($clients->count() > 0)
                        @foreach($clients as $client)
                            <div class="client-slide">
                                <img src="{{ $client->logo_url }}" alt="{{ $client->name }}">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- CSS -->
    <style>
        .clients-section {
            padding: 80px 0;
            background: white;
            overflow: hidden;
        }

        .clients-container {
            max-width: 1250px;
            margin: auto;
            padding: 0 20px;
        }

        .clients-headings {
            font-size: 38px;
            font-weight: 700;
            color: #0b2c3d;
            margin-bottom: 40px !important;
        }

        .clients-carousel-wrapper {
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        }

        .clients-carousel-track {
            display: flex;
            gap: 40px;
            animation: clientsScroll 25s linear infinite;
            width: max-content;
        }

        .clients-carousel-track:hover {
            animation-play-state: paused;
        }

        .client-slide {
            flex-shrink: 0;
            width: 180px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .client-slide:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .client-slide img {
            max-width: 100%;
            max-height: 60px;
            object-fit: contain;
        }

        @keyframes clientsScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @media (max-width: 768px) {
            .client-slide {
                width: 140px;
                height: 80px;
                padding: 10px 15px;
            }

            .client-slide img {
                max-height: 45px;
            }

            .clients-carousel-track {
                gap: 24px;
                animation-duration: 18s;
            }
        }
    </style>
    <!-- Our recoginzation-->
    <!--- profile section -->
    {{-- <section class="bg-pattern-light py-24 animate-on-scroll fade-in-up">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="section-subheading">{{ $aboutPage->team_section_title ?? 'Our Team Members' }}</span>
                <h2 class="font-heading text-zendo-navy">
                    {{ $aboutPage->team_section_heading ?? 'What We Think About Our Company' }}
                </h2>
            </div>
            <!-- Team Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 card-grid-container">
                @forelse($teamMembers as $member)
                    <!-- Team Member Card -->
                    <div class="blog-card card-item bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
                        <a href="{{ $member->linkedin_url ?? '#' }}" target="_blank">
                            <div class="overflow-hidden">
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                                    class="card-image w-full h-80 object-cover">
                            </div>
                            <div class="p-6">
                                <h3
                                    class="text-xl font-semibold font-heading text-zendo-navy hover:text-zendo-gold transition-colors mb-3">
                                    {{ $member->name }}</h3>
                                @if ($member->position)
                                    <p class="text-sm text-gray-600 font-body mb-2">{{ $member->position }}</p>
                                @endif
                                @if ($member->linkedin_url)
                                    <div class="flex items-center text-xs text-gray-500 font-body">
                                        <span>LinkedIn</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <!-- No Team Members -->
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-600 font-body text-lg">No team members added yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section> --}}
    <!-- ended -->
    <!-- cta button -->
    <section id="inquiry-section" class="py-24 animate-on-scroll fade-in-up">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Left Content -->
            <div>
                <span class="section-subheading-dark-bg">Get Inquiry</span>
                <h2 class="md:text-5xl font-heading text-white">Find Your Perfect Property — Connect with Our Advisors Today</h2>
                <p class="text-lg text-gray-300 font-body max-w-2xl mx-auto">
                    Partner with ZENDO INDIA to find your ideal space. Secure your property requirements across industrial warehousing, warehouse land, factories, 3PL company tie-ups, commercial land, shops, agricultural land, or premium residential plots and flats—it's just an inquiry away.
                </p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="max-w-2xl mx-auto mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="max-w-2xl mx-auto mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Right Form -->
            <div class="w-full max-w-6xl mx-auto mt-12">
                <form action="{{ route('inquiries.store') }}" method="POST"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                    @csrf
                    <div>
                        <label for="name-2" class="sr-only">Name</label>
                        <input type="text" name="name" id="name-2" class="inquiry-form-input w-full"
                            placeholder="Name" required>
                    </div>
                    <div>
                        <label for="email-2" class="sr-only">Email</label>
                        <input type="email" name="email" id="email-2" class="inquiry-form-input w-full"
                            placeholder="Email" required>
                    </div>
                    <div>
                        <label for="phone-2" class="sr-only">Phone number</label>
                        <input type="tel" name="phone" id="phone-2" class="inquiry-form-input w-full"
                            placeholder="Phone number" required>
                    </div>
                    <div>
                        <label for="requirement-2" class="sr-only">Requirement</label>
                        <select name="message" id="requirement-2" class="inquiry-form-input w-full" required>
                            <option value="">Select Requirement</option>
                            <option value="Warehouse Leasing">Warehouse Leasing</option>
                            <option value="Warehouse/Industrial Land">Warehouse/Industrial Land</option>
                            <option value="Factories & Manufacturing Setups">Factories & Manufacturing Setups</option>
                            <option value="3PL Company Tie-up">3PL Company Tie-up</option>
                            <option value="Residential Plot / Flat">Residential Plot / Flat</option>
                            <option value="Commercial Land / Shop">Commercial Land / Shop</option>
                            <option value="Agricultural Land">Agricultural Land</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full h-[56px] px-6 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 btn-anim btn-dark-bg">
                            Get Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- ended-->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inquiryForm = document.querySelector('form[action*="inquiries"]');
    
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            // Get form data
            const formData = new FormData(this);
            
            // Submit via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showMessage('success', data.message || 'Thank you for your inquiry! We will get back to you soon.');
                    
                    // Reset form
                    this.reset();
                } else {
                    // Show error message
                    let errorMsg = data.message || 'Something went wrong. Please try again.';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).flat().join('<br>');
                    }
                    showMessage('error', errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('error', 'Something went wrong. Please try again later.');
            })
            .finally(() => {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    function showMessage(type, message) {
        // Remove existing messages
        const existingMsg = document.querySelector('.inquiry-message');
        if (existingMsg) {
            existingMsg.remove();
        }
        
        // Create message element
        const msgDiv = document.createElement('div');
        msgDiv.className = `inquiry-message fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-md ${
            type === 'success' 
                ? 'bg-green-50 border border-green-200 text-green-800' 
                : 'bg-red-50 border border-red-200 text-red-800'
        }`;
        msgDiv.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${type === 'success' 
                        ? '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                        : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                    }
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(msgDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            msgDiv.style.transition = 'opacity 0.5s';
            msgDiv.style.opacity = '0';
            setTimeout(() => msgDiv.remove(), 500);
        }, 5000);
    }
});
</script>
@endsection
