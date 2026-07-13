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
                                    <path fill="#b39359" d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z" />
                                </svg>
                            </div>
                            <div>
                                <div class="sgdxp-contact-label">Call Us</div>
                                <div class="sgdxp-contact-details">
                                    <a href="tel:+917494010101" style="color:#e6edf8;text-decoration:none;">+91 74-94-01-01-01</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="sgdxp-cta-button">Request Callback</a>
                </div>
            </aside>
        </div>

    <div style="max-width:1200px; margin:0 auto; padding:60px 20px;">

        {{-- Main Content --}}
        <div>

            {{-- Photo Gallery --}}
            @if($entry->photos->count() > 0)
            <div style="margin-bottom:32px; border-radius:12px; overflow:hidden; border:1px solid #e9ebef;">
                <div style="position:relative; padding-bottom:60%; background:#f5f6f8;">
                    <img src="{{ asset('images/property_photos/' . basename($entry->photos->first()->file_path)) }}"
                         alt="Property Photo"
                         style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                </div>
                @if($entry->photos->count() > 1)
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(150px, 1fr)); gap:8px; padding:8px; background:#fff;">
                    @foreach($entry->photos->skip(1) as $photo)
                    <div style="position:relative; padding-bottom:75%; background:#f5f6f8; border-radius:8px; overflow:hidden;">
                        <img src="{{ asset('images/property_photos/' . basename($photo->file_path)) }}"
                             alt="Property Photo"
                             style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            {{-- Overview --}}
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Property Overview</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @if($entry->facility_type)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Facility Type</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->facility_type }}</strong></div>
                    @endif
                    @if($entry->plot_area)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Plot Area</span><strong style="font-size:16px; color:#0b2c3d;">{{ number_format($entry->plot_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong></div>
                    @endif
                    @if($entry->built_up_area)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Built-up Area</span><strong style="font-size:16px; color:#0b2c3d;">{{ number_format($entry->built_up_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong></div>
                    @endif
                    @if($entry->carpet_area)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Carpet Area</span><strong style="font-size:16px; color:#0b2c3d;">{{ number_format($entry->carpet_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong></div>
                    @endif
                    @if($entry->available_area)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Available Area</span><strong style="font-size:16px; color:#0b2c3d;">{{ number_format($entry->available_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong></div>
                    @endif
                    @if($entry->clear_height_highest)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Clear Height</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->clear_height_highest }} ft (highest)</strong></div>
                    @endif
                    @if($entry->number_of_floors)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Floors</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->number_of_floors }}</strong></div>
                    @endif
                    @if($entry->tenure)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Tenure</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->tenure }}</strong></div>
                    @endif
                </div>
            </div>

            {{-- Dock & Loading --}}
            @if($entry->dock_door_count || $entry->dock_type)
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Dock & Loading Facilities</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @if($entry->dock_door_count)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Dock Doors</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->dock_door_count }} total</strong></div>
                    @endif
                    @if($entry->dock_type)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Dock Type</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->dock_type }}</strong></div>
                    @endif
                    @if($entry->dock_height)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Dock Height</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->dock_height }} ft</strong></div>
                    @endif
                    @if($entry->truck_movement)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Truck Movement</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->truck_movement }}</strong></div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Utilities --}}
            @if($entry->power_sanctioned_kva || $entry->water_source)
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Utilities & Infrastructure</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @if($entry->power_sanctioned_kva)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Power Sanctioned</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->power_sanctioned_kva }} KVA</strong></div>
                    @endif
                    @if($entry->discom_name)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">DISCOM</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->discom_name }}</strong></div>
                    @endif
                    @if($entry->water_source)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Water Source</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->water_source }}</strong></div>
                    @endif
                    @if($entry->fire_fighting_system)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Fire Fighting System</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->fire_fighting_system }}</strong></div>
                    @endif
                    @if($entry->solar !== null)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Solar</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->solar ? 'Yes' : 'No' }}</strong></div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Facilities --}}
            @if($entry->no_of_offices || $entry->canteen || $entry->washrooms)
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Facilities</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @if($entry->no_of_offices)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Offices</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->no_of_offices }}</strong></div>
                    @endif
                    @if($entry->canteen)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Canteen</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->canteen ? 'Yes' : 'No' }}</strong></div>
                    @endif
                    @if($entry->washrooms)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Washrooms</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->washrooms }}</strong></div>
                    @endif
                    @if($entry->flooring_type)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Flooring</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->flooring_type }}</strong></div>
                    @endif
                    @if($entry->fire_sprinkler)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Fire Sprinkler</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->fire_sprinkler }}</strong></div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Legal --}}
            @if($entry->fire_noc || $entry->pollution_noc)
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Legal & Compliance</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @if($entry->fire_noc)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Fire NOC</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->fire_noc }}</strong></div>
                    @endif
                    @if($entry->pollution_noc)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Pollution NOC</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->pollution_noc }}</strong></div>
                    @endif
                    @if($entry->occupancy_certificate)
                    <div><span style="font-size:14px; color:#5f738c; display:block; margin-bottom:4px;">Occupancy Certificate</span><strong style="font-size:16px; color:#0b2c3d;">{{ $entry->occupancy_certificate }}</strong></div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Remarks --}}
            @if($entry->remarks)
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 12px 0;">Remarks & Observations</h2>
                <p style="font-size:15px; color:#5f738c; line-height:1.7; margin:0; white-space:pre-wrap;">{{ $entry->remarks }}</p>
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div>

            {{-- Pricing Card --}}
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px; position:sticky; top:100px;">
                <h3 style="font-size:18px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Pricing & Details</h3>

                @if($entry->expected_rent)
                <div style="margin-bottom:16px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:4px;">Expected Rent</span>
                    <strong style="font-size:24px; color:#b39359; font-weight:700; display:block;">₹{{ number_format($entry->expected_rent, 2) }}</strong>
                    <span style="font-size:12px; color:#5f738c;">/sq ft/month</span>
                </div>
                @endif

                @if($entry->expected_sale_price)
                <div style="margin-bottom:16px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:4px;">Expected Sale Price</span>
                    <strong style="font-size:24px; color:#b39359; font-weight:700; display:block;">₹{{ number_format($entry->expected_sale_price / 100000, 2) }} Lac</strong>
                </div>
                @endif

                @if($entry->security_deposit_months)
                <div style="margin-bottom:8px; padding-bottom:8px; border-bottom:1px solid #e9ebef;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:2px;">Security Deposit</span>
                    <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->security_deposit_months }} months</strong>
                </div>
                @endif

                @if($entry->lock_in_years)
                <div style="margin-bottom:8px; padding-bottom:8px; border-bottom:1px solid #e9ebef;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:2px;">Lock-in Period</span>
                    <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->lock_in_years }} years</strong>
                </div>
                @endif

                @if($entry->deal_type)
                <div style="margin-bottom:8px; padding-bottom:8px; border-bottom:1px solid #e9ebef;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:2px;">Deal Type</span>
                    <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->deal_type }}</strong>
                </div>
                @endif

                @if($entry->available_from)
                <div style="margin-bottom:16px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:2px;">Available From</span>
                    <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->available_from->format('M d, Y') }}</strong>
                </div>
                @endif

                <a href="{{ route('contact') }}" style="display:block; text-align:center; background:#b39359; color:#fff; padding:14px 24px; border-radius:8px; font-size:15px; font-weight:600; text-decoration:none; transition:all 0.25s ease;" onmouseover="this.style.background='#9a7f4d';" onmouseout="this.style.background='#b39359';">Inquire Now</a>

            </div>

            {{-- Location Card --}}
            @if($entry->nearest_city || $entry->nearest_highway)
            <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e9ebef; margin-bottom:24px;">
                <h3 style="font-size:18px; font-weight:700; color:#0b2c3d; margin:0 0 16px 0;">Location</h3>
                @if($entry->nearest_city)
                <div style="margin-bottom:12px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:4px;">Nearest City</span>
                    <strong style="font-size:15px; color:#0b2c3d; display:block;">{{ $entry->nearest_city }}</strong>
                </div>
                @endif
                @if($entry->nearest_highway)
                <div style="margin-bottom:12px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:4px;">Road Connectivity</span>
                    <strong style="font-size:15px; color:#0b2c3d; display:block;">{{ $entry->nearest_highway }}</strong>
                </div>
                @endif
                @if($entry->nearest_railway_station)
                <div style="margin-bottom:12px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:4px;">Nearest Railway Station</span>
                    <strong style="font-size:15px; color:#0b2c3d; display:block;">{{ $entry->nearest_railway_station }}</strong>
                </div>
                @endif
                @if($entry->nearest_airport)
                <div style="margin-bottom:12px;">
                    <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:4px;">Nearest Airport</span>
                    <strong style="font-size:15px; color:#0b2c3d; display:block;">{{ $entry->nearest_airport }}</strong>
                </div>
                @endif
            </div>
            @endif

        </div>

    </div>

</div>
@endsection
