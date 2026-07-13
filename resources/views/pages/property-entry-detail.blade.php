@extends('layouts.app')

@section('title', ($entry->facility_type ?? 'Property') . ' - ' . ($entry->nearest_city ?? '') . ' - ZendoIndia')
@section('description', Str::limit($entry->name_full_address ?? $entry->remarks ?? '', 160))

@section('content')

    <!-- BANNER -->
    <section class="about-banner-section">
        <div class="about-banner-overlay"></div>
        <div class="about-banner-container">
            <div class="about-banner-left">
                <h1 class="about-banner-heading">{{ $entry->facility_type ?? 'Property Details' }}</h1>
                <div class="about-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('properties.index') }}">Properties</a>
                    <span>/</span>
                    <p>{{ $entry->code }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROPERTY HERO SECTION -->
    <div id="sgdxp-page">
        <div class="sgdxp-header-row">
            <div class="sgdxp-header-left">
                <div class="sgdxp-badges">
                    @if($entry->deal_type)
                        <span class="sgdxp-badge sgdxp-badge-status">{{ $entry->deal_type }}</span>
                    @endif
                    <span class="sgdxp-badge sgdxp-badge-status">Verified</span>
                </div>
                <h1 class="sgdxp-title">{{ Str::limit($entry->name_full_address ?? $entry->facility_type, 80) }}</h1>
                <div class="sgdxp-location-line">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                        <path fill="#b39359" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                    </svg>
                    <span>{{ $entry->name_full_address }}</span>
                </div>
            </div>

            <div class="sgdxp-header-right">
                @if($entry->expected_rent)
                    <div class="sgdxp-starting-price-label">Expected Rent</div>
                    <div class="sgdxp-starting-price-value">₹{{ number_format($entry->expected_rent, 2) }}/sq ft/mo</div>
                @elseif($entry->expected_sale_price)
                    <div class="sgdxp-starting-price-label">Expected Sale Price</div>
                    <div class="sgdxp-starting-price-value">₹{{ number_format($entry->expected_sale_price / 100000, 2) }} Lac</div>
                @else
                    <div class="sgdxp-starting-price-label">Price</div>
                    <div class="sgdxp-starting-price-value">On Request</div>
                @endif
            </div>
        </div>

        <div id="sgdxp-main">
            <div class="sgdxp-image-card">
                <div class="sgdxp-image-wrapper">
                    @if($entry->photos->count() > 0)
                        <img src="{{ asset('images/property_photos/' . basename($entry->photos->first()->file_path)) }}" alt="{{ $entry->facility_type }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=70" alt="{{ $entry->facility_type }}">
                    @endif
                </div>
            </div>

            <aside class="sgdxp-contact-card">
                <div>
                    <h2>Get in Touch</h2>
                    <p class="sgdxp-contact-subtext">Contact us for more details, site visits, or pricing information.</p>

                    <div class="sgdxp-contact-section">
                        <div class="sgdxp-contact-row">
                            <div class="sgdxp-contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path fill="#b39359" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Our Office</div>
                                <div class="sgdxp-contact-details">
                                    <a href="https://maps.google.com/?q=Tapasya+Corp+Heights+Tower+B+Sector+126+Noida" target="_blank" style="color:#e6edf8;text-decoration:none;">
                                        <p>Tapasya Corp Heights, Tower B,</p>
                                        <p>Sector 126, Noida,</p>
                                        <p>Uttar Pradesh 201303</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="sgdxp-contact-row">
                            <div class="sgdxp-contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path fill="#b39359" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Email Us</div>
                                <div class="sgdxp-contact-details">
                                    <a href="mailto:info@zendoindia.com" style="color:#e6edf8;text-decoration:none;">info@zendoindia.com</a>
                                </div>
                            </div>
                        </div>

                        <div class="sgdxp-contact-row">
                            <div class="sgdxp-contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path fill="#b39359" d="M6.62 10.79a15.093 15.093 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.07 21 3 13.93 3 5c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.11.35.03.74-.24 1.02l-2.21 2.2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Call Us</div>
                                <div class="sgdxp-contact-details">
                                    <a href="tel:+917494010101" class="sgdxp-call-number" style="color:#e6edf8;text-decoration:none;">+91 74-94-01-01-01</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sgdxp-request-btn">
                    <button type="button" id="open-callback-modal-btn">Request Callback</button>
                </div>
            </aside>

        </div>
    </div>

    <!-- SECTION 2: USP CARD + OVERVIEW + SPECIFICATIONS -->
    <section id="sg2-section">
        <div class="sg2-row">
            <div>
                <!-- USP Card -->
                <div class="sg2-usp-card">
                    <div class="sg2-usp-grid">
                        <div>
                            <div class="sg2-usp-item-label">Type</div>
                            <div class="sg2-usp-item-value">{{ $entry->facility_type ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Area</div>
                            <div class="sg2-usp-item-value">
                                {{ $entry->available_area ? number_format($entry->available_area) . ' ' . str_replace('_', ' ', $entry->area_unit ?? 'sq ft') : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Clear Height</div>
                            <div class="sg2-usp-item-value">{{ $entry->clear_height_highest ? $entry->clear_height_highest . ' ft' : 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="sg2-usp-item-label">Possession</div>
                            <div class="sg2-usp-item-value">{{ $entry->available_from ? $entry->available_from->format('M Y') : 'On Request' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Property Overview -->
                <h2 class="sg2-title-main">Property Overview</h2>
                <hr class="sg2-hr">
                @if($entry->remarks)
                <p class="sg2-overview-text">{{ $entry->remarks }}</p>
                @else
                <p class="sg2-overview-text">Premium {{ $entry->facility_type }} available for {{ $entry->deal_type ?? 'lease/sale' }} in {{ $entry->nearest_city }}. Contact us for detailed specifications and site visit.</p>
                @endif

                <!-- Key Features -->

                @if($entry->dock_door_count || $entry->power_sanctioned_kva || $entry->fire_noc)
                <h3 class="sg2-subtitle">Top Reasons to Invest</h3>
                <ul class="sg2-reasons">
                    @if($entry->dock_door_count)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>{{ $entry->dock_door_count }} Dock Doors</span>
                    </li>
                    @endif
                    @if($entry->power_sanctioned_kva)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>{{ $entry->power_sanctioned_kva }} KVA Power</span>
                    </li>
                    @endif
                    @if($entry->fire_noc === 'Yes')
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>Fire NOC Approved</span>
                    </li>
                    @endif
                    @if($entry->nearest_highway)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>Excellent Connectivity - {{ $entry->nearest_highway }}</span>
                    </li>
                    @endif
                    @if($entry->water_source)
                    <li>
                        <span class="sg2-bullet-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none">
                                <path fill="#b39359" d="M12 3l3.7 4.3 5.3 1.4-3.4 4.1.4 5.5L12 16.8 6 18.3l.4-5.5-3.4-4.1 5.3-1.4L12 3z" />
                            </svg>
                        </span>
                        <span>{{ $entry->water_source }} Water Supply</span>
                    </li>
                    @endif
                </ul>
                @endif

                <!-- Specifications Table -->
                <h2 class="sg2-title-main">Specifications</h2>
                <hr class="sg2-hr">

                <div class="apw-table-wrap">
                    <table class="apw-table">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Attributes</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $srNo = 1; @endphp
                            @if($entry->facility_type)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Facility Type</td>
                                <td>{{ $entry->facility_type }}</td>
                            </tr>
                            @endif
                            @if($entry->plot_area)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Plot Area</td>
                                <td>{{ number_format($entry->plot_area) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</td>
                            </tr>
                            @endif
                            @if($entry->built_up_area)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Built-up Area</td>
                                <td>{{ number_format($entry->built_up_area) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</td>
                            </tr>
                            @endif
                            @if($entry->carpet_area)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Carpet Area</td>
                                <td>{{ number_format($entry->carpet_area) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</td>
                            </tr>
                            @endif
                            @if($entry->available_area)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Available Area</td>
                                <td>{{ number_format($entry->available_area) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</td>
                            </tr>
                            @endif
                            @if($entry->clear_height_highest)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Clear Height (Highest)</td>
                                <td>{{ $entry->clear_height_highest }} ft</td>
                            </tr>
                            @endif
                            @if($entry->clear_height_lowest)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Clear Height (Lowest)</td>
                                <td>{{ $entry->clear_height_lowest }} ft</td>
                            </tr>
                            @endif
                            @if($entry->number_of_floors)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Number of Floors</td>
                                <td>{{ $entry->number_of_floors }}</td>
                            </tr>
                            @endif
                            @if($entry->dock_door_count)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Doors</td>
                                <td>{{ $entry->dock_door_count }}</td>
                            </tr>
                            @endif
                            @if($entry->dock_type)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Type</td>
                                <td>{{ $entry->dock_type }}</td>
                            </tr>
                            @endif
                            @if($entry->dock_height)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Dock Height</td>
                                <td>{{ $entry->dock_height }} ft</td>
                            </tr>
                            @endif
                            @if($entry->power_sanctioned_kva)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Power Sanctioned</td>
                                <td>{{ $entry->power_sanctioned_kva }} KVA</td>
                            </tr>
                            @endif
                            @if($entry->discom_name)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>DISCOM</td>
                                <td>{{ $entry->discom_name }}</td>
                            </tr>
                            @endif
                            @if($entry->water_source)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Water Source</td>
                                <td>{{ $entry->water_source }}</td>
                            </tr>
                            @endif
                            @if($entry->fire_fighting_system)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Fire Fighting System</td>
                                <td>{{ $entry->fire_fighting_system }}</td>
                            </tr>
                            @endif
                            @if($entry->fire_noc)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Fire NOC</td>
                                <td>{{ $entry->fire_noc }}</td>
                            </tr>
                            @endif
                            @if($entry->pollution_noc)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Pollution NOC</td>
                                <td>{{ $entry->pollution_noc }}</td>
                            </tr>
                            @endif
                            @if($entry->occupancy_certificate)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Occupancy Certificate</td>
                                <td>{{ $entry->occupancy_certificate }}</td>
                            </tr>
                            @endif
                            @if($entry->no_of_offices)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Number of Offices</td>
                                <td>{{ $entry->no_of_offices }}</td>
                            </tr>
                            @endif

                            @if($entry->canteen)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Canteen</td>
                                <td>{{ $entry->canteen ? 'Yes' : 'No' }}</td>
                            </tr>
                            @endif
                            @if($entry->washrooms)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Washrooms</td>
                                <td>{{ $entry->washrooms }}</td>
                            </tr>
                            @endif
                            @if($entry->flooring_type)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Flooring Type</td>
                                <td>{{ $entry->flooring_type }}</td>
                            </tr>
                            @endif
                            @if($entry->tenure)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Tenure</td>
                                <td>{{ $entry->tenure }}</td>
                            </tr>
                            @endif
                            @if($entry->nearest_city)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest City</td>
                                <td>{{ $entry->nearest_city }}</td>
                            </tr>
                            @endif
                            @if($entry->nearest_highway)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Highway</td>
                                <td>{{ $entry->nearest_highway }}</td>
                            </tr>
                            @endif
                            @if($entry->nearest_railway_station)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Railway Station</td>
                                <td>{{ $entry->nearest_railway_station }}</td>
                            </tr>
                            @endif
                            @if($entry->nearest_airport)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Nearest Airport</td>
                                <td>{{ $entry->nearest_airport }}</td>
                            </tr>
                            @endif
                            @if($entry->expected_rent)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Expected Rent</td>
                                <td>₹{{ number_format($entry->expected_rent, 2) }} /sq ft/month</td>
                            </tr>
                            @endif
                            @if($entry->expected_sale_price)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Expected Sale Price</td>
                                <td>₹{{ number_format($entry->expected_sale_price / 100000, 2) }} Lac</td>
                            </tr>
                            @endif
                            @if($entry->security_deposit_months)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Security Deposit</td>
                                <td>{{ $entry->security_deposit_months }} months</td>
                            </tr>
                            @endif
                            @if($entry->lock_in_years)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Lock-in Period</td>
                                <td>{{ $entry->lock_in_years }} years</td>
                            </tr>
                            @endif
                            @if($entry->available_from)
                            <tr>
                                <td>{{ $srNo++ }}</td>
                                <td>Available From</td>
                                <td>{{ $entry->available_from->format('M d, Y') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <aside>
                <h2 class="sg2-form-title">Request a Callback</h2>
                <p class="sg2-form-subtext">Share your details and our team will call you with floor plans, pricing and exclusive offers.</p>

                <!-- Success Message -->
                <div id="callback-success-message" style="display:none;background:#10b981;color:#fff;padding:12px;border-radius:8px;margin-bottom:16px;font-size:14px;">
                    Thank you! We'll contact you shortly.
                </div>

                <!-- Error Message -->
                <div id="callback-error-message" style="display:none;background:#ef4444;color:#fff;padding:12px;border-radius:8px;margin-bottom:16px;font-size:14px;">
                    Something went wrong. Please try again.
                </div>

                <form id="callback-form" action="{{ route('inquiries.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_entry_code" value="{{ $entry->code }}">
                    <div class="sg2-form-group">
                        <input type="text" name="name" class="sg2-input" placeholder="Your Name" required>
                    </div>
                    <div class="sg2-form-group">
                        <input type="tel" name="phone" class="sg2-input" placeholder="Phone Number" required>
                    </div>
                    <div class="sg2-form-group">
                        <input type="email" name="email" class="sg2-input" placeholder="Email">
                    </div>
                    <div class="sg2-form-group">
                        <textarea name="message" class="sg2-textarea" placeholder="I am interested in {{ $entry->facility_type }} - {{ $entry->code }}..."></textarea>
                    </div>
                    <div class="sg2-btn-wrap">
                        <button type="submit" class="sg2-btn" id="callback-submit-btn">
                            <span class="btn-text">Get Best Price</span>
                            <span class="btn-loading" style="display:none;">
                                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </div>
                </form>
            </aside>
        </div>
    </section>

    <!-- GALLERY -->
    @if($entry->photos->count() > 0)
    <section id="sg-gallery-similar">
        <div class="sg-gs-row">
            <div class="sg-gallery-box">
                <h2 class="sg-gallery-title">Gallery</h2>
                <hr class="sg2-hr">

                <div class="sg-slider">
                    @foreach($entry->photos as $photo)
                        <div class="sg-slide">
                            <img src="{{ asset('images/property_photos/' . basename($photo->file_path)) }}" alt="{{ $entry->facility_type }}">
                        </div>
                    @endforeach

                    <div class="sg-prev" onclick="sgPlusSlides(-1)">‹</div>
                    <div class="sg-next" onclick="sgPlusSlides(1)">›</div>
                </div>
            </div>
        </div>
    </section>
    @endif

@endsection
