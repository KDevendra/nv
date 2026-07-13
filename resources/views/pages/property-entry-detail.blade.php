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

        <!-- Additional Gallery -->
        @if($entry->photos->count() > 1)
        <div style="max-width:1400px; margin:48px auto; padding:0 20px;">
            <h2 style="font-size:28px; font-weight:700; color:#0b2c3d; margin:0 0 24px 0;">More Photos</h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
                @foreach($entry->photos->skip(1) as $photo)
                <div style="position:relative; padding-bottom:75%; background:#f5f6f8; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                    <img src="{{ asset('images/property_photos/' . basename($photo->file_path)) }}"
                         alt="Property Photo"
                         style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Property Details Sections -->
        <div style="max-width:1400px; margin:48px auto; padding:0 20px;">

            {{-- Overview --}}
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
            <div style="background:#fff; padding:32px; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08); margin-bottom:32px;">
                <h2 style="font-size:24px; font-weight:700; color:#0b2c3d; margin:0 0 24px 0; padding-bottom:16px; border-bottom:2px solid #f0f2f5;">Property Overview</h2>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:24px;">
                    @if($entry->facility_type)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Facility Type</span>
                        <strong style="font-size:18px; color:#0b2c3d;">{{ $entry->facility_type }}</strong>
                    </div>
                    @endif
                    @if($entry->plot_area)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Plot Area</span>
                        <strong style="font-size:18px; color:#0b2c3d;">{{ number_format($entry->plot_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong>
                    </div>
                    @endif
                    @if($entry->built_up_area)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Built-up Area</span>
                        <strong style="font-size:18px; color:#0b2c3d;">{{ number_format($entry->built_up_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong>
                    </div>
                    @endif
                    @if($entry->available_area)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Available Area</span>
                        <strong style="font-size:18px; color:#0b2c3d;">{{ number_format($entry->available_area, 0) }} {{ str_replace('_', ' ', $entry->area_unit ?? 'sq ft') }}</strong>
                    </div>
                    @endif
                    @if($entry->clear_height_highest)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Clear Height</span>
                        <strong style="font-size:18px; color:#0b2c3d;">{{ $entry->clear_height_highest }} ft</strong>
                    </div>
                    @endif
                    @if($entry->number_of_floors)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Floors</span>
                        <strong style="font-size:18px; color:#0b2c3d;">{{ $entry->number_of_floors }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Two Column Layout for Facilities --}}
            {{-- Two Column Layout for Facilities --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:32px; margin-bottom:32px;">

                {{-- Dock & Loading --}}
                @if($entry->dock_door_count || $entry->dock_type)
                <div style="background:#fff; padding:28px; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                    <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 20px 0; padding-bottom:12px; border-bottom:2px solid #f0f2f5;">Dock & Loading</h2>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        @if($entry->dock_door_count)
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f2f5;">
                            <span style="font-size:14px; color:#5f738c;">Dock Doors</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->dock_door_count }} total</strong>
                        </div>
                        @endif
                        @if($entry->dock_type)
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f2f5;">
                            <span style="font-size:14px; color:#5f738c;">Dock Type</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->dock_type }}</strong>
                        </div>
                        @endif
                        @if($entry->dock_height)
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f2f5;">
                            <span style="font-size:14px; color:#5f738c;">Dock Height</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->dock_height }} ft</strong>
                        </div>
                        @endif
                        @if($entry->truck_movement)
                        <div style="display:flex; justify-content:space-between; padding:10px 0;">
                            <span style="font-size:14px; color:#5f738c;">Truck Movement</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->truck_movement }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Utilities --}}
                @if($entry->power_sanctioned_kva || $entry->water_source)
                <div style="background:#fff; padding:28px; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                    <h2 style="font-size:20px; font-weight:700; color:#0b2c3d; margin:0 0 20px 0; padding-bottom:12px; border-bottom:2px solid #f0f2f5;">Utilities</h2>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        @if($entry->power_sanctioned_kva)
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f2f5;">
                            <span style="font-size:14px; color:#5f738c;">Power</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->power_sanctioned_kva }} KVA</strong>
                        </div>
                        @endif
                        @if($entry->water_source)
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f2f5;">
                            <span style="font-size:14px; color:#5f738c;">Water Source</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->water_source }}</strong>
                        </div>
                        @endif
                        @if($entry->fire_fighting_system)
                        <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f2f5;">
                            <span style="font-size:14px; color:#5f738c;">Fire System</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->fire_fighting_system }}</strong>
                        </div>
                        @endif
                        @if($entry->fire_noc)
                        <div style="display:flex; justify-content:space-between; padding:10px 0;">
                            <span style="font-size:14px; color:#5f738c;">Fire NOC</span>
                            <strong style="font-size:15px; color:#0b2c3d;">{{ $entry->fire_noc }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            {{-- Location --}}
            @if($entry->nearest_city || $entry->nearest_highway)
            <div style="background:#fff; padding:32px; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08); margin-bottom:32px;">
                <h2 style="font-size:24px; font-weight:700; color:#0b2c3d; margin:0 0 24px 0; padding-bottom:16px; border-bottom:2px solid #f0f2f5;">Location & Connectivity</h2>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
                    @if($entry->nearest_city)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Nearest City</span>
                        <strong style="font-size:16px; color:#0b2c3d;">{{ $entry->nearest_city }}</strong>
                    </div>
                    @endif
                    @if($entry->nearest_highway)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Highway</span>
                        <strong style="font-size:16px; color:#0b2c3d;">{{ $entry->nearest_highway }}</strong>
                    </div>
                    @endif
                    @if($entry->nearest_railway_station)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Railway Station</span>
                        <strong style="font-size:16px; color:#0b2c3d;">{{ $entry->nearest_railway_station }}</strong>
                    </div>
                    @endif
                    @if($entry->nearest_airport)
                    <div style="padding:16px; background:#f8f9fb; border-radius:8px;">
                        <span style="font-size:13px; color:#5f738c; display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px; font-weight:600;">Airport</span>
                        <strong style="font-size:16px; color:#0b2c3d;">{{ $entry->nearest_airport }}</strong>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Remarks --}}
            @if($entry->remarks)
            <div style="background:#fff; padding:32px; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08);">
                <h2 style="font-size:24px; font-weight:700; color:#0b2c3d; margin:0 0 20px 0; padding-bottom:16px; border-bottom:2px solid #f0f2f5;">Remarks</h2>
                <p style="font-size:16px; color:#5f738c; line-height:1.8; margin:0; white-space:pre-wrap;">{{ $entry->remarks }}</p>
            </div>
            @endif

        </div>

    </div>{{-- Close #sgdxp-page --}}

@endsection
