<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Visit Details — ZendoIndia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Nunito+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'zendo-navy': '#0B2C3D', 'zendo-gold': '#B39359' },
                fontFamily: { heading: ['Forum','cursive'], body: ['"Nunito Sans"','sans-serif'] } } }
        }
    </script>
    <style>body { font-family: 'Nunito Sans', sans-serif; } h1,h2,h3 { font-family: 'Forum', cursive; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center p-4">
<div class="w-full max-w-lg">

    {{-- Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 text-zendo-navy font-heading text-2xl">
            <svg class="w-7 h-7 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            ZendoIndia
        </div>
        <p class="text-slate-500 text-sm mt-1">Site Visit Information</p>
    </div>

    @if($expired)
    {{-- Expired / already used --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h2 class="text-xl font-heading text-gray-800 mb-2">Link Not Valid</h2>
        <p class="text-gray-500 text-sm leading-relaxed">{{ $reason }}</p>
        <p class="mt-4 text-xs text-gray-400">Please contact your coordinator to request a new site-visit link.</p>
    </div>

    @else
    {{-- Valid — show property details --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-zendo-navy px-6 py-4">
            <h1 class="text-white font-heading text-xl">Your Site Visit Is Confirmed</h1>
            <p class="text-zendo-gold text-xs mt-1">This link has been opened. Details are shown below.</p>
        </div>

        <div class="p-6 space-y-5">

            @if($lead)
            <div class="bg-blue-50 rounded-xl p-4 text-sm">
                <div class="font-semibold text-blue-800 mb-1">Your Visit</div>
                <div class="text-blue-700">Lead reference: #{{ $lead->id }}</div>
                @if($lead->site_visit_scheduled_at)
                    <div class="text-blue-600 mt-1">Scheduled: {{ $lead->site_visit_scheduled_at->format('D, d M Y H:i') }}</div>
                @endif
            </div>
            @endif

            @if($property)
            <div>
                <h2 class="font-heading text-zendo-navy text-lg mb-3">{{ $property->title }}</h2>

                <div class="space-y-3 text-sm text-gray-700">
                    {{-- Address --}}
                    @if($property->address)
                    <div class="flex gap-3 items-start">
                        <div class="w-8 h-8 bg-zendo-gold/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Confirmed Address</div>
                            <div class="mt-0.5 font-semibold text-gray-900">{{ $property->address }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- City / Location --}}
                    <div class="flex gap-3 items-start">
                        <div class="w-8 h-8 bg-zendo-gold/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Location</div>
                            <div class="mt-0.5">
                                @if($property->location) {{ $property->location->name }}, @endif
                                @if($property->city) {{ $property->city->name }} @endif
                            </div>
                        </div>
                    </div>

                    {{-- Map link --}}
                    @if($property->latitude && $property->longitude)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $property->latitude }},{{ $property->longitude }}"
                       target="_blank" rel="noopener"
                       class="flex items-center gap-2 bg-blue-600 text-white rounded-xl px-4 py-3 text-sm font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Open in Google Maps
                    </a>
                    @elseif($property->map_embed_code)
                    <div class="rounded-xl overflow-hidden border border-gray-200 aspect-video">
                        {!! $property->map_embed_code !!}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Property type / BHK chips --}}
            <div class="flex flex-wrap gap-2">
                @if($property->propertyType)
                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">{{ $property->propertyType->name }}</span>
                @endif
                @if($property->city)
                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">{{ $property->city->name }}</span>
                @endif
            </div>

            @elseif(isset($propertyEntries) && $propertyEntries->isNotEmpty())
            <div class="space-y-4">
                @foreach($propertyEntries as $pe)
                    <div class="border border-gray-100 bg-slate-50/50 rounded-xl p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-semibold text-zendo-gold uppercase tracking-wide">{{ $pe->code ?: ('PE-' . $pe->id) }}</span>
                                <h3 class="font-heading text-zendo-navy text-lg leading-tight">{{ $pe->property_name ?: ('Property #' . ($pe->code ?: $pe->id)) }}</h3>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pe->facility_type ?: 'Warehouse' }}</span>
                        </div>

                        <div class="space-y-2.5 text-sm text-gray-700">
                            {{-- Full Address --}}
                            <div class="flex gap-3 items-start">
                                <div class="w-8 h-8 bg-zendo-gold/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Confirmed Address</div>
                                    <div class="mt-0.5 font-semibold text-gray-900">{{ $pe->name_full_address ?: 'Contact coordinator for gate directions.' }}</div>
                                </div>
                            </div>

                            {{-- City & Region --}}
                            <div class="flex gap-3 items-start">
                                <div class="w-8 h-8 bg-zendo-gold/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Location / City</div>
                                    <div class="mt-0.5 font-medium">{{ $pe->nearest_city ?: $pe->village_town_district ?: 'Raipur' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Property details are not available for this visit.</p>
            @endif

            <div class="border-t border-gray-100 pt-4 text-xs text-gray-400 text-center">
                This link was single-use and is now consumed. For assistance, contact your coordinator.
            </div>
        </div>
    </div>
    @endif

    <p class="text-center text-xs text-slate-400 mt-6">© {{ date('Y') }} ZendoIndia · Confidential</p>
</div>
</body>
</html>
