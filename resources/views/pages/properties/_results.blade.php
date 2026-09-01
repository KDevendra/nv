<div class="apw-listTop">
    <div class="apw-listTopLeft">
        <h2 class="apw-listTitle">Property Listings</h2>
        <p class="apw-listSub">Showing {{ $properties->count() }} of {{ $properties->total() }} properties</p>
    </div>

    <div class="apw-listTopRight">
        <div class="apw-searchWrap">
            <span class="apw-searchSvg" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                    <circle cx="11" cy="11" r="6.5" stroke="#b39359" stroke-width="1.7" />
                    <path d="M16.2 16.2L21 21" stroke="#b39359" stroke-width="1.7" stroke-linecap="round" />
                </svg>
            </span>
            <input type="text" class="apw-search" placeholder="Search by project or location..."
                value="{{ request('search') }}"
                x-model="search"
                @keydown.enter.prevent="applyFilters()"
                @change="applyFilters()">
        </div>
    </div>
</div>

<!-- Inline Error State -->
<template x-if="error">
    <div class="apw-empty" style="margin-bottom: 14px;">
        <div class="apw-emptyBox" style="border-color: #ef4444; background-color: #fef2f2;">
            <h3 style="color: #991b1b; margin-top: 0;">Unable to load properties</h3>
            <p style="color: #b91c1c;">A network or server error occurred while retrieving properties.</p>
            <button type="button" class="apw-filterApply" @click="fetchResults(buildUrl(), false)" style="background-color: #dc2626; width: auto; display: inline-flex; margin: 0 auto;">
                Try Again
            </button>
        </div>
    </div>
</template>

<!-- Property Cards — property_entries only, type-aware -->
@if ($properties->count() > 0)
    <div class="apw-cardGrid">
        @foreach ($properties as $entry)
            @php
                $entryPhoto = $entry->photos->first();
                $entryImg = $entryPhoto
                    ? asset('images/property_photos/' . basename($entryPhoto->file_path))
                    : 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=70';
            @endphp
            <article class="apw-card">
                <div class="apw-cardMedia" style="background-image:url('{{ $entryImg }}');">
                    @if ($entry->construction_listing_status)
                        <span class="apw-tag apw-tagAlt">{{ $entry->construction_listing_status }}</span>
                    @endif
                </div>
                <div class="apw-cardBody">
                    <h3 class="apw-cardTitle">{{ $entry->public_title }}</h3>
                    <p class="apw-cardMeta">
                        <span class="apw-miniSvg" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none">
                                <path d="M12 21s7-5.2 7-11A7 7 0 1 0 5 10c0 5.8 7 11 7 11z"
                                    stroke="#b39359" stroke-width="1.7" />
                                <circle cx="12" cy="10" r="2.3" stroke="#b39359"
                                    stroke-width="1.7" />
                            </svg>
                        </span>
                        {{ $entry->public_detail_line }}
                    </p>
                    <div class="apw-cardRow">
                        <div class="apw-price">
                            <span class="apw-priceLabel">{{ $entry->public_price_label }}</span>
                            <span class="apw-priceVal">{{ $entry->public_price_value }}</span>
                        </div>
                        <div class="apw-ctaRow">
                            <a class="apw-btnOutline"
                                href="{{ route('property-entries.show', $entry->code) }}">View Details</a>
                        </div>
                    </div>
                    @if (count($entry->public_amenities))
                        <div class="apw-amenities">
                            @foreach ($entry->public_amenities as $amenity)
                                <span>{{ $amenity }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($properties->hasPages())
    <div class="mt-8 apw-pagination-wrap">
        {{ $properties->links() }}
    </div>
    @endif
@else
    <div class="apw-empty">
        <div class="apw-emptyBox">
            <div class="apw-emptySvg" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none">
                    <path d="M4 10.8l8-6 8 6V20a1.6 1.6 0 0 1-1.6 1.6H5.6A1.6 1.6 0 0 1 4 20v-9.2z"
                        stroke="#b39359" stroke-width="1.7" stroke-linejoin="round" />
                    <path d="M9.5 21.6V14h5v7.6" stroke="#b39359" stroke-width="1.7"
                        stroke-linecap="round" />
                </svg>
            </div>
            <h3>No matching properties found</h3>
            <p>Try changing location, budget, or property type — or reset all filters.</p>
            <button type="button" class="apw-filterApply"
                @click="resetFilters()" style="width: auto; display: inline-flex; margin: 0 auto;">Reset
                Filters</button>
        </div>
    </div>
@endif

<!-- CTA Strip -->
<div class="apw-ctaStrip" id="enquiry">
    <div class="apw-ctaLeft">
        <h3>Need help shortlisting the right home?</h3>
        <p>Share your requirement and we'll suggest best residential options in your preferred location.</p>
    </div>
    <div class="apw-ctaRight">
        <a class="apw-ctaBtn" href="{{ route('contact') }}">Get a Call Back</a>
    </div>
</div>
