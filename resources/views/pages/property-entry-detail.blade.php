@extends('layouts.website')

@section('title', 'Property Details - ' . ($entry->facility_type ?? 'Property'))
@section('description', Str::limit($entry->name_full_address ?? $entry->remarks ?? '', 160))

@section('content')
<div class="apw-container" style="padding-top:80px; padding-bottom:80px;">

    {{-- Header --}}
    <div style="margin-bottom:32px;">
        <h1 style="font-size:32px; font-weight:700; color:#0b2c3d; margin:0 0 12px 0;">{{ $entry->facility_type ?? 'Property Details' }}</h1>
        <p style="font-size:16px; color:#5f738c; margin:0; display:flex; align-items:center; gap:8px;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none">
                <path d="M12 21s7-5.2 7-11A7 7 0 1 0 5 10c0 5.8 7 11 7 11z" stroke="#b39359" stroke-width="1.7"/>
                <circle cx="12" cy="10" r="2.3" stroke="#b39359" stroke-width="1.7"/>
            </svg>
            {{ $entry->name_full_address }}
        </p>
    </div>

    <div style="display:grid; grid-template-columns:1fr 360px; gap:40px; align-items:start;">

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
