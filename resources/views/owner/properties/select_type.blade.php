@extends('layouts.owner')
@section('title', 'Choose Property Type - Owner Portal')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-block px-2.5 py-0.5 bg-zendo-gold/20 text-zendo-gold text-xs font-semibold rounded-full uppercase tracking-wider">
                    Step 1 of Listing
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-heading text-zendo-navy font-semibold">Choose Property Type</h2>
            <p class="text-gray-500 text-sm mt-1">Select the classification of property you would like to list on ZendoIndia</p>
        </div>
        <a href="{{ route('owner.dashboard') }}"
            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    {{-- Group 1: Warehousing & Industrial --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
            <h3 class="text-lg font-heading font-bold text-zendo-navy flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                Warehousing & Industrial Logistics
            </h3>
            <span class="text-xs font-semibold px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-full">
                Primary Flow
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <a href="{{ route('owner.properties.create') }}"
                class="group bg-white rounded-xl border-2 border-zendo-navy/20 p-5 shadow-sm hover:shadow-md hover:border-zendo-navy transition-all flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-zendo-navy text-white text-[10px] font-mono font-bold px-2.5 py-1 rounded-bl-lg">
                    ZI-WH-
                </div>
                <div>
                    <div class="w-10 h-10 rounded-lg bg-zendo-navy/10 text-zendo-navy flex items-center justify-center mb-3 group-hover:bg-zendo-navy group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-zendo-navy group-hover:text-zendo-gold transition-colors">
                        Warehouse / Cold Storage / Industrial Plot
                    </h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Complete lettered wizard for logistics warehouses, cold storage sheds, industrial parks & open plots.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-semibold text-zendo-navy group-hover:text-zendo-gold">
                    <span>Continue with Warehouse Form</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    {{-- Group 2: Residential Properties --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
            <h3 class="text-lg font-heading font-bold text-zendo-navy flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Residential Properties
            </h3>
            <span class="text-xs text-gray-500 font-medium">5 Categories</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($residentialTypes as $type)
                <a href="{{ route('owner.properties.create-type', ['type' => $type['slug']]) }}"
                    class="group bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md hover:border-emerald-500 transition-all flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-emerald-50 text-emerald-700 border-b border-l border-emerald-200 text-[10px] font-mono font-bold px-2 py-0.5 rounded-bl-lg">
                        {{ $type['code_prefix'] }}
                    </div>
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-zendo-navy group-hover:text-emerald-700 transition-colors">
                            {{ $type['label'] }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                            {{ $type['description'] }}
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-semibold text-emerald-700">
                        <span>List Property</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Group 3: Commercial Properties --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-gray-200 pb-2">
            <h3 class="text-lg font-heading font-bold text-zendo-navy flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                Commercial & Institutional Properties
            </h3>
            <span class="text-xs text-gray-500 font-medium">8 Categories</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($commercialTypes as $type)
                <a href="{{ route('owner.properties.create-type', ['type' => $type['slug']]) }}"
                    class="group bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md hover:border-blue-500 transition-all flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-blue-50 text-blue-700 border-b border-l border-blue-200 text-[10px] font-mono font-bold px-2 py-0.5 rounded-bl-lg">
                        {{ $type['code_prefix'] }}
                    </div>
                    <div>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-zendo-navy group-hover:text-blue-700 transition-colors">
                            {{ $type['label'] }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed line-clamp-2">
                            {{ $type['description'] }}
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-semibold text-blue-700">
                        <span>List Property</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
