@extends('layouts.app')
@section('title', 'ZendoIndia - Find Your Perfect Property')
@section('content')
<!-- HERO BANNER -->
@if($heroSections->isNotEmpty())
@foreach($heroSections as $heroSection)
<section class="relative bg-zendo-navy min-h-screen flex flex-col items-center justify-center pt-20 text-center">
   <!-- Background Video -->
   <div class="absolute inset-0 z-0">
      @if($heroSection->video_path)
      <video class="w-full h-full object-cover brightness-75" autoplay muted loop playsinline
      @if($heroSection->poster_image) poster="{{ asset('storage/' . $heroSection->poster_image) }}" @endif>
      <source src="{{ asset('storage/' . $heroSection->video_path) }}" type="video/mp4">
      Your browser does not support the video tag.
      </video>
      @elseif($heroSection->poster_image)
      <img src="{{ asset('storage/' . $heroSection->poster_image) }}" alt="{{ $heroSection->title }}" class="w-full h-full object-cover brightness-75">
      @endif
   </div>
   <!-- Content Container -->
   <div class="relative z-10 max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <div>
         <h1 class="text-4xl sm:text-5xl lg:text-7xl font-heading text-white leading-tight animate-on-scroll fade-in-up hero-text-shadow"
            style="--animation-delay: 0.1s;">
            {{ $heroSection->title }} @if($heroSection->highlight_text)<span class="text-zendo-gold">{{ $heroSection->highlight_text }}</span>@endif
         </h1>
         @if($heroSection->description)
         <p class="mt-6 text-lg sm:text-xl text-gray-200 font-body max-w-2xl mx-auto animate-on-scroll fade-in-up hero-text-shadow"
            style="--animation-delay: 0.2s;">
            {{ $heroSection->description }}
         </p>
         @endif
      </div>
      <div class="w-full max-w-8xl mt-12 animate-on-scroll fade-in-up is-visible" style="--animation-delay: 0.3s;">
         <!-- Tabs (Dynamic Service Types) -->
         <div class="flex justify-center flex-wrap gap-2 mb-2">
            @forelse($serviceTypes as $index => $serviceType)
            <button type="button" 
               class="search-tab-button group relative px-8 py-3 rounded-t-lg font-highlight {{ $index === 0 ? 'active bg-white text-zendo-navy font-bold' : 'bg-gray-200/80 text-gray-700 font-semibold hover:bg-white hover:text-zendo-navy' }}" 
               data-tab="{{ $serviceType->slug }}" 
               style="border-color: rgb(11, 44, 61);">
               {{ $serviceType->name }}
               <div class="tab-pointer absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-t-8 border-t-white {{ $index === 0 ? '' : 'hidden' }}">
               </div>
            </button>
            @empty
            <!-- Fallback tabs if no service types -->
            <button type="button" class="search-tab-button group relative px-8 py-3 rounded-t-lg font-highlight bg-gray-200/80 text-gray-700 font-semibold hover:bg-white hover:text-zendo-navy" data-tab="buy" style="border-color: rgb(11, 44, 61);">
               Buy
               <div class="tab-pointer absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-t-8 border-t-white hidden">
               </div>
            </button>
            <button type="button" class="search-tab-button group relative px-8 py-3 rounded-t-lg backdrop-blur-sm font-highlight bg-gray-200/80 text-gray-700 font-semibold hover:bg-white hover:text-zendo-navy" data-tab="lease" style="border-color: rgb(11, 44, 61);">
               Lease
               <div class="tab-pointer absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-t-8 border-t-white hidden">
               </div>
            </button>
            <button type="button" class="search-tab-button group relative px-8 py-3 rounded-t-lg backdrop-blur-sm font-highlight active bg-white text-zendo-navy font-bold" data-tab="rental" style="border-color: rgb(11, 44, 61);">
               Rental
               <div class="tab-pointer absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-t-8 border-t-white">
               </div>
            </button>
            @endforelse
         </div>
         <!-- Form Card -->
         <div class="bg-white rounded-xl shadow-2xl">
            <div class="p-4 sm:p-6">
               <!-- Normal Search Form -->
               <form id="normalSearchForm">
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                     <!-- Type -->
                     <div class="w-full">
                        <label for="type" class="sr-only">Type</label>
                        <select id="type" name="type" class="w-full px-4 py-3 rounded-md border border-gray-300 focus:border-zendo-navy focus:ring-1 focus:ring-zendo-navy text-gray-700 font-body transition-colors">
                           <option selected="" disabled="">Select Type</option>
                           @forelse($propertyTypes as $propertyType)
                           <option value="{{ $propertyType->slug }}" data-service-types="{{ implode(',', $propertyType->serviceTypes->pluck('slug')->toArray()) }}">{{ $propertyType->name }}</option>
                           @empty
                           <option>House</option>
                           <option>Farmhouses</option>
                           <option>Agricultural Land</option>
                           <option>Warehouse</option>
                           <option>Warehouse Land</option>
                           <option>Office</option>
                           <option>Apartment</option>
                           <option>Villa</option>
                           <option>Single Room</option>
                           @endforelse
                        </select>
                     </div>
                     <!-- Location (Dropdown removed -> Text input) -->
                     <div class="w-full">
                        <label for="location" class="sr-only">Location</label>
                        <input type="text" id="location" name="location" placeholder="Enter Location (City / Area)" class="w-full px-4 py-3 rounded-md border border-gray-300 focus:border-zendo-navy focus:ring-1 focus:ring-zendo-navy text-gray-700 font-body transition-colors" autocomplete="off">
                     </div>
                     <!-- Button -->
                     <div class="w-full">
                        <button type="submit" class="w-full h-[52px] px-8 rounded-full transition-all transform hover:scale-105 shadow-lg font-highlight font-semibold text-lg btn-anim btn-light-bg">
                        Search
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
@endforeach
@endif
<!-- OUR MISSION SECTION -->
<section class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-24 bg-pattern-white animate-on-scroll fade-in-up">
   <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16 items-start">
      <!-- Left Content -->
      <div class="lg:col-span-2">
         <span class="section-subheading">About Us</span>
         <h2 class="font-heading text-zendo-navy">
            {{ $aboutUs->title ?? 'Our mission is to redefine real estate for our customers' }}
         </h2>
         <p class="text-lg font-medium text-gray-700 font-body mb-6">
            {{ $aboutUs->subtitle ?? 'ZENDO is one of the world\'s leading property agents. Our experience spans the globe.' }}
         </p>
         <p class="text-gray-600 font-body leading-relaxed">
            {{ $aboutUs->mission_text ?? 'where we believe in more than just selling properties – we envision a future where every individual\'s dreams of owning a home, securing an investment, or finding the perfect commercial space come true. Our mission is not just a statement, but a driving force that guides every decision and action we take. Since our inception, we have been committed to empowering people and building a brighter future through real estate excellence.' }}
         </p>
      </div>
      <!-- Right Checklist -->
      <div class="lg:col-span-1 bg-white p-8 rounded-lg shadow-xl border border-gray-100">
         <ul class="space-y-5">
            @if ($aboutUs && $aboutUs->checklist_items && count($aboutUs->checklist_items) > 0)
            @foreach ($aboutUs->checklist_items as $item)
            <li class="flex items-start">
               <svg class="flex-shrink-0 w-6 h-6 text-zendo-gold mr-3" xmlns="http://www.w3.org/2000/svg"
                  fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                     d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>
               <span class="font-body text-gray-700">{{ $item }}</span>
            </li>
            @endforeach
            @endif
         </ul>
      </div>
   </div>
   <!-- Stats Bar -->
   <div id="stats-bar" class="mt-20 pt-16 border-t border-gray-200">
      @if ($aboutUs && $aboutUs->stats && count($aboutUs->stats) > 0)
      <div class="grid grid-cols-1 md:grid-cols-{{ min(count($aboutUs->stats), 3) }} gap-10 text-center">
         @foreach ($aboutUs->stats as $stat)
         <div>
            <p class="text-5xl lg:text-6xl font-medium font-heading text-zendo-gold counter-value"
            data-target="{{ $stat['value'] ?? '0' }}"
            @if (isset($stat['prefix']) && !empty($stat['prefix'])) data-prefix="{{ $stat['prefix'] }}" @endif
            @if (isset($stat['suffix']) && !empty($stat['suffix'])) data-suffix="{{ $stat['suffix'] }}" @endif
            @if (strpos($stat['value'] ?? '', '.') !== false) data-decimals="1" @endif>0</p>
            <p class="mt-2 text-sm font-semibold uppercase tracking-wider font-body text-gray-600">
               {{ $stat['label'] ?? '' }}
            </p>
         </div>
         @endforeach
      </div>
      @endif
   </div>
</section>
<!-- FEATURED PROPERTIES SECTION -->
<section class="bg-pattern-light py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Best Choice</span>
         <h2 class="font-heading text-zendo-navy">
            Featured Properties
         </h2>
         <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto">
            Handpicked properties by our team, just for you.
         </p>
      </div>
      <!-- Property Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 card-grid-container">
         @forelse($featuredProperties as $property)
         <!-- Property Card: {{ $property->title }} -->
         <div class="property-card card-item bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100">
            <!-- Image Container -->
            <div class="relative group overflow-hidden">
               <img src="{{ $property->main_image_url ?? asset('main/images/properties/default.png') }}" 
                  alt="{{ $property->title }}"
                  class="card-image w-full h-64 object-cover">
               <!-- Tags -->
               <div class="absolute top-4 left-4 flex space-x-2">
                  @if($property->propertyType)
                  <span class="px-3 py-1 rounded text-sm font-semibold bg-zendo-gold text-white">{{ $property->propertyType->name }}</span>
                  @endif
                  @if($property->is_featured)
                  <span class="px-3 py-1 rounded text-sm font-semibold bg-zendo-gold text-white">Featured</span>
                  @endif
               </div>
               <!-- Price -->
               <div class="absolute bottom-4 left-4">
                  <span class="text-2xl font-medium font-heading text-white bg-black/50 px-3 py-1 rounded">{{ $property->formatted_price }}</span>
               </div>
            </div>
            <!-- Card Content -->
            <div class="p-6">
               @if($property->propertyType)
               <span class="text-sm font-semibold font-body text-zendo-gold">{{ $property->propertyType->name }}</span>
               @endif
               <h3 class="mt-2 text-2xl font-medium font-heading text-zendo-navy hover:text-zendo-gold transition-colors">
                  <a href="{{ route('properties.show', $property->slug) }}">{{ $property->title }}</a>
               </h3>
               <p class="mt-2 flex items-center text-gray-600 font-body">
                  <img src="{{ asset('main/icons/location.svg') }}" alt="Location Icon" class="w-4 h-4 mr-2 flex-shrink-0">
                  @if($property->location)
                     {{ $property->location->name }}@if($property->city), {{ $property->city->name }}@endif
                  @elseif($property->city)
                     {{ $property->city->name }}
                  @else
                     {{ $property->address }}
                  @endif
               </p>
               <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-gray-700 font-body">
                  @if($property->bhk)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/single-bed.svg') }}" alt="Bed Icon" class="w-4 h-4 mr-1.5">
                     {{ $property->bhk->name }}
                  </span>
                  @endif
                  @if($property->specifications && $property->specifications->bathrooms)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/bathroom.svg') }}" alt="Bathroom Icon" class="w-4 h-4 mr-1.5">
                     Baths: {{ $property->specifications->bathrooms }}
                  </span>
                  @endif
                  @if($property->carpet_area)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/full-size.svg') }}" alt="Area Icon" class="w-4 h-4 mr-1.5">
                     {{ number_format($property->carpet_area) }} Sq Ft
                  </span>
                  @endif
               </div>
            </div>
         </div>
         @empty
         <!-- No Featured Properties -->
         <div class="col-span-full text-center py-12">
            <p class="text-gray-600 font-body text-lg">No featured properties available at the moment.</p>
         </div>
         @endforelse
      </div>
      <!-- View Properties Button -->
      <div class="text-center mt-12">
         <a href="{{ route('properties.index') }}"
            class="px-8 py-3 rounded-full transition-all transform hover:scale-105 inline-block font-highlight font-semibold shadow-lg btn-anim btn-light-bg">
         View Properties
         </a>
      </div>
   </div>
</section>
<!-- OUR SERVICES SECTION -->
<section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Top Row: Title & Text -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
         <div>
            <span class="section-subheading">Our Services</span>
            <h2 class="font-heading text-zendo-navy">
               We Provide Latest Properties For Our Valuable Clients.
            </h2>
         </div>
         <div>
            <p class="text-gray-600 font-body leading-relaxed">
               Huge number of properties available here for buy, sell and Rent. Also you find here co-living
               property so lots opportunity you have to choose here and enjoy huge discount.
            </p>
         </div>
      </div>
      <!-- Bottom Row: Image & Features -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
         <!-- Left Column (Image) -->
         <div class="relative flex items-center justify-center">
            <img src="{{ asset('main/images/zendo-india-about.webp') }}" alt="Zendo India Properties"
               class="relative z-10 w-full max-w-lg rounded-lg transition-transform duration-300 ease-in-out hover:scale-105">
         </div>
         <!-- Right Column (Features) -->
         <div>
            <!-- Feature List -->
            <ul class="space-y-6">
               @forelse($latestPropertiesFeatures as $feature)
               <!-- Feature: {{ $feature->title }} -->
               <li class="flex items-start">
                  <div
                     class="flex-shrink-0 w-16 h-16 bg-zendo-light-bg rounded-full flex items-center justify-center mr-5">
                     @if ($feature->icon)
                     <img src="{{ $feature->icon_url }}" alt="{{ $feature->title }} Icon"
                        class="w-8 h-8 text-zendo-gold">
                     @else
                     <div class="w-8 h-8 bg-zendo-gold rounded-full"></div>
                     @endif
                  </div>
                  <div>
                     <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-1">
                        {{ $feature->title }}
                     </h3>
                     <p class="text-gray-600 font-body leading-relaxed">{{ $feature->description }}</p>
                  </div>
               </li>
               @empty
               <!-- No features message -->
               <li class="flex items-start">
                  <div
                     class="flex-shrink-0 w-16 h-16 bg-zendo-light-bg rounded-full flex items-center justify-center mr-5">
                     <div class="w-8 h-8 bg-zendo-gold rounded-full"></div>
                  </div>
                  <div>
                     <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-1">No Services
                        Available
                     </h3>
                     <p class="text-gray-600 font-body leading-relaxed">Please add features with
                        'our-services' tag from admin panel.
                     </p>
                  </div>
               </li>
               @endforelse
            </ul>
         </div>
      </div>
   </div>
</section>
<!--- commericial properties -->
<style>
   .commercial-showcase {
   background: #fbf8f2;
   padding: 70px 0;
   }
   .commercial-wrap {
   max-width: 1180px;
   margin: 0 auto;
   padding: 0 18px;
   display: grid;
   grid-template-columns: 1.05fr 0.95fr;
   gap: 34px;
   align-items: center;
   }
   .commercial-badge {
   display: inline-block;
   padding: 8px 14px;
   border-radius: 999px;
   background: rgba(11, 44, 61, 0.08);
   color: #0b2c3d;
   font-weight: 700;
   letter-spacing: 0.4px;
   font-size: 13px;
   border: 1px solid rgba(11, 44, 61, 0.14);
   }
   .commercial-title {
   margin: 14px 0 10px;
   font-size: 38px;
   line-height: 1.15;
   color: #0b2c3d;
   letter-spacing: -0.4px;
   }
   .commercial-title span {
   color: #b39359;
   font-weight: 800;
   }
   .commercial-subtitle {
   margin: 0 0 18px;
   color: rgba(11, 44, 61, 0.78);
   font-size: 16px;
   line-height: 1.7;
   max-width: 560px;
   }
   .commercial-points {
   display: grid;
   gap: 14px;
   margin: 18px 0 22px;
   }
   .point {
   display: grid;
   grid-template-columns: 14px 1fr;
   gap: 12px;
   padding: 14px 14px;
   border-radius: 16px;
   background: #fbf8f2;
   border: 1px solid rgba(179, 147, 89, 0.25);
   box-shadow: 0 14px 30px rgba(11, 44, 61, 0.06);
   }
   .point h4 {
   margin: 0;
   font-size: 15px;
   color: #0b2c3d;
   font-weight: 800;
   }
   .point p {
   margin: 6px 0 0;
   font-size: 14px;
   color: rgba(11, 44, 61, 0.72);
   line-height: 1.6;
   }
   .dot {
   width: 10px;
   height: 10px;
   margin-top: 5px;
   border-radius: 50%;
   background: #b39359;
   box-shadow: 0 10px 18px rgba(179, 147, 89, 0.35);
   }
   .commercial-cta {
   display: flex;
   gap: 12px;
   flex-wrap: wrap;
   margin-top: 10px;
   }
   .commercial-btn {
   display: inline-flex;
   align-items: center;
   justify-content: center;
   height: 46px;
   padding: 0 16px;
   border-radius: 14px;
   font-weight: 800;
   text-decoration: none;
   transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
   }
   .commercial-btn.primary {
   background: #0b2c3d;
   color: #fbf8f2;
   box-shadow: 0 16px 30px rgba(11, 44, 61, 0.22);
   }
   .commercial-btn.primary:hover {
   transform: translateY(-1px);
   box-shadow: 0 18px 34px rgba(11, 44, 61, 0.28);
   }
   .commercial-btn.ghost {
   background: transparent;
   color: #0b2c3d;
   border: 1px solid rgba(11, 44, 61, 0.22);
   }
   .commercial-btn.ghost:hover {
   transform: translateY(-1px);
   box-shadow: 0 14px 28px rgba(11, 44, 61, 0.10);
   }
   .commercial-trust {
   margin-top: 18px;
   display: grid;
   grid-template-columns: repeat(3, minmax(0, 1fr));
   gap: 12px;
   }
   .trust-item {
   padding: 14px 14px;
   border-radius: 16px;
   background: rgba(11, 44, 61, 0.04);
   border: 1px solid rgba(11, 44, 61, 0.10);
   }
   .trust-number {
   display: block;
   font-size: 18px;
   font-weight: 900;
   color: #b39359;
   line-height: 1.1;
   }
   .trust-label {
   display: block;
   margin-top: 6px;
   font-size: 13px;
   color: rgba(11, 44, 61, 0.75);
   font-weight: 700;
   }
   .commercial-gallery {
   position: relative;
   padding: 18px;
   border-radius: 24px;
   background: linear-gradient(135deg, rgba(11, 44, 61, 0.06), rgba(179, 147, 89, 0.08));
   border: 1px solid rgba(11, 44, 61, 0.10);
   box-shadow: 0 22px 50px rgba(11, 44, 61, 0.10);
   }
   .gallery-grid {
   display: grid;
   gap: 12px;
   grid-template-columns: 1.05fr 0.95fr;
   grid-template-rows: 160px 160px;
   }
   .g-item {
   display: block;
   border-radius: 18px;
   overflow: hidden;
   position: relative;
   border: 1px solid rgba(179, 147, 89, 0.25);
   box-shadow: 0 16px 34px rgba(11, 44, 61, 0.10);
   transform: translateZ(0);
   }
   .g-item img {
   width: 100%;
   height: 100%;
   object-fit: cover;
   display: block;
   transition: transform 0.35s ease;
   }
   .g-item::after {
   content: "";
   position: absolute;
   inset: 0;
   background: linear-gradient(180deg, rgba(11, 44, 61, 0.0), rgba(11, 44, 61, 0.22));
   opacity: 0.9;
   }
   .g-item:hover img {
   transform: scale(1.05);
   }
   .g1 {
   grid-row: 1 / span 2;
   }
   .g2 {
   grid-row: 1;
   }
   .g3 {
   grid-row: 2;
   }
   .g4 {
   grid-column: 1 / span 2;
   grid-row: 3;
   display: none;
   }
   .gallery-note {
   margin-top: 12px;
   padding: 12px 14px;
   border-radius: 16px;
   background: rgba(251, 248, 242, 0.8);
   border: 1px solid rgba(179, 147, 89, 0.25);
   color: rgba(11, 44, 61, 0.78);
   }
   .gallery-note strong {
   color: #0b2c3d;
   }
   /* Responsive */
   @media (max-width: 980px) {
   .commercial-wrap {
   grid-template-columns: 1fr;
   }
   .commercial-title {
   font-size: 32px;
   }
   .gallery-grid {
   grid-template-rows: 180px 180px;
   }
   }
   @media (max-width: 520px) {
   .commercial-showcase {
   padding: 54px 0;
   }
   .commercial-title {
   font-size: 28px;
   }
   .commercial-trust {
   grid-template-columns: 1fr;
   }
   .gallery-grid {
   grid-template-columns: 1fr;
   grid-template-rows: 180px 180px 180px;
   }
   .g1 {
   grid-row: auto;
   }
   }
</style>
<section class="commercial-showcase">
   <div class="commercial-wrap">
      @if($commercialSection)
      <div class="commercial-content">
         <p class="commercial-badge">{{ $commercialSection->badge }}</p>
         <h2 class="commercial-title">
            {!! $commercialSection->title !!}
         </h2>
         <p class="commercial-subtitle">
            {{ $commercialSection->subtitle }}
         </p>
         <div class="commercial-points">
            @foreach($commercialSection->formatted_points as $point)
            <div class="point">
               <div class="dot"></div>
               <div>
                  <h4>{{ $point['title'] }}</h4>
                  @if(!empty($point['description']))
                  <p>{{ $point['description'] }}</p>
                  @endif
               </div>
            </div>
            @endforeach
         </div>
         <div class="commercial-cta">
            <a href="{{ $commercialSection->primary_button_link }}" class="commercial-btn primary">
            {{ $commercialSection->primary_button_text }}
            </a>
            <a href="{{ $commercialSection->secondary_button_link }}" class="commercial-btn ghost">
            {{ $commercialSection->secondary_button_text }}
            </a>
         </div>
      </div>
      <div class="commercial-gallery">
         <div class="gallery-grid">
            @foreach($commercialSection->formatted_gallery_images as $index => $image)
            @php
            $gridClass = '';
            if($index === 0) $gridClass = 'g-item g1';
            elseif($index === 1) $gridClass = 'g-item g2';
            elseif($index === 2) $gridClass = 'g-item g3';
            else $gridClass = 'g-item g4';
            @endphp
            <a class="{{ $gridClass }}" href="#" aria-label="{{ $image['label'] }}">
            <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}">
            </a>
            @endforeach
         </div>
         <div class="gallery-note">
            <p><strong>{{ $commercialSection->gallery_note }}</strong></p>
         </div>
      </div>
      @else
      <!-- Fallback content if no commercial section is active -->
      <div class="commercial-content">
         <p class="commercial-badge">Commercial Expertise</p>
         <h2 class="commercial-title">
            Premium Commercial Properties — <span>Strategic & Business-Ready Spaces</span>
         </h2>
         <p class="commercial-subtitle">
            We also work on commercial real estate solutions — from office spaces to retail and investment-ready
            assets. This section highlights our commercial domain in a clean, informative way (without listings).
         </p>
         <div class="commercial-points">
            <div class="point">
               <div class="dot"></div>
               <div>
                  <h4>Offices & Corporate Spaces</h4>
               </div>
            </div>
            <div class="point">
               <div class="dot"></div>
               <div>
                  <h4>Retail, Showrooms & High-Street</h4>
               </div>
            </div>
            <div class="point">
               <div class="dot"></div>
               <div>
                  <h4>Warehousing & Industrial Units</h4>
               </div>
            </div>
         </div>
         <div class="commercial-cta">
            <a href="#contact" class="commercial-btn primary">Request Commercial Consultation</a>
            <a href="#projects" class="commercial-btn ghost">View Our Work</a>
         </div>
      </div>
      <div class="commercial-gallery">
         <div class="gallery-grid">
            <a class="g-item g1" href="#" aria-label="Commercial project image 1">
            <img src="{{ asset('main/images/commercial-1.png') }}" alt="Commercial property workspace interior">
            </a>
            <a class="g-item g2" href="#" aria-label="Commercial project image 2">
            <img src="{{ asset('main/images/commercial-2.png') }}" alt="Retail showroom commercial space">
            </a>
            <a class="g-item g3" href="#" aria-label="Commercial project image 3">
            <img src="{{ asset('main/images/commercial-3.png') }}" alt="Office building commercial exterior">
            </a>
            <a class="g-item g4" href="#" aria-label="Commercial project image 4">
            <img src="{{ asset('main/images/delhi.jpg') }}" alt="Warehouse industrial commercial unit">
            </a>
         </div>
         <div class="gallery-note">
            <p><strong>Gallery Preview:</strong> Offices • Retail • Industrial • Investment Spaces</p>
         </div>
      </div>
      @endif
   </div>
</section>
<!-- CATEGORIES SECTION -->
<section id="categories-section"
   class="bg-gradient-to-br from-zendo-navy to-gray-900 py-24 animate-on-scroll fade-in-up">
   <div class="relative z-10 max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <span class="section-subheading-dark-bg">Categories</span>
      <h2 class="font-heading text-white">
         What You Are Looking For?
      </h2>
      <p class="text-lg text-gray-300 font-body max-w-3xl mx-auto mb-16">
         Selling a property is not just about placing a 'For Sale' sign in the front yard and hoping for the best. In
         today's competitive real estate market, a well-crafted listing is a powerful tool that can make all the
         difference in attracting potential buyers and ultimately.
      </p>
      <div
         class="category-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 card-grid-container">
         @forelse($categories as $category)
         <!-- Category Card: {{ $category->name }} -->
         <a href="{{ $category->link }}"
            class="category-card card-item flex flex-col items-center p-8 rounded-lg shadow-lg transition-all duration-300">
            <img src="{{ $category->icon_url }}" alt="{{ $category->name }} Icon"
               class="category-icon w-16 h-16 mb-4 text-zendo-gold transition-colors duration-300">
            <h3 class="text-xl font-medium font-heading text-white mb-2">{{ strtoupper($category->name) }}
            </h3>
            <p class="text-gray-300 font-body text-lg leading-relaxed">{{ $category->description }}</p>
         </a>
         @empty
         <!-- Fallback message if no categories -->
         <div class="col-span-full text-center py-12">
            <p class="text-gray-300 font-body text-lg">No categories available at the moment.</p>
         </div>
         @endforelse
      </div>
   </div>
</section>
<!-- CITIES GALLERY SECTION -->
<section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Find By Location</span>
         <h2 class="font-heading text-zendo-navy">
            Find Properties in These Cities
         </h2>
         <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto">
            Explore properties in India's fastest-growing regions.
         </p>
      </div>
      <!-- City Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
         @php
            $cityCount = $cities->count();
         @endphp
         
         @forelse($cities as $index => $city)
            @php
               // Determine grid span based on position
               // Pattern: 1 col, 2 cols, 2 cols, 1 col (repeating)
               $colSpan = 'col-span-1';
               $position = $index % 4;
               
               if ($position === 0) {
                  $colSpan = 'md:col-span-1';
               } elseif ($position === 1) {
                  $colSpan = 'md:col-span-2';
               } elseif ($position === 2) {
                  $colSpan = 'md:col-span-2';
               } else {
                  $colSpan = 'md:col-span-1';
               }
            @endphp
            
            <!-- City Card: {{ $city->name }} -->
            <a href="{{ $city->link }}"
               class="city-card group relative {{ $colSpan }} h-80 rounded-lg overflow-hidden shadow-lg">
               <img src="{{ $city->image_url }}" alt="Properties in {{ $city->name }}"
                  class="city-image w-full h-full object-cover">
               <div class="overlay absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
               <div class="absolute bottom-0 left-0 p-6">
                  <h3 class="text-2xl font-medium font-heading text-white">{{ $city->name }}</h3>
                  <p class="text-gray-200 font-body">{{ $city->formatted_property_count }}</p>
               </div>
            </a>
         @empty
         <!-- Fallback message if no cities -->
         <div class="col-span-full text-center py-12">
            <p class="text-gray-600 font-body text-lg">No cities available at the moment.</p>
         </div>
         @endforelse
      </div>
   </div>
</section>
<!-- POPULAR PROPERTIES SECTION -->
<section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Popular Properties</span>
         <h2 class="font-heading text-zendo-navy">
            Most Popular Properties
         </h2>
         <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto">
            Explore our most sought-after properties across different categories.
         </p>
      </div>
      <!-- Property Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 card-grid-container">
         @forelse($popularProperties as $property)
         <!-- Property Card: {{ $property->title }} -->
         <div class="property-card card-item bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100">
            <div class="relative group overflow-hidden">
               <img src="{{ $property->main_image_url ?? asset('main/images/properties/default.png') }}" 
                  alt="{{ $property->title }}"
                  class="card-image w-full h-64 object-cover">
               <div class="absolute top-4 left-4 flex space-x-2">
                  @if($property->propertyType)
                  <span class="px-3 py-1 rounded text-sm font-semibold bg-zendo-gold text-white">{{ $property->propertyType->name }}</span>
                  @endif
               </div>
               <div class="absolute bottom-4 left-4">
                  <span class="text-2xl font-medium font-heading text-white bg-black/50 px-3 py-1 rounded">{{ $property->formatted_price }}</span>
               </div>
            </div>
            <div class="p-6">
               @if($property->propertyType)
               <span class="text-sm font-semibold font-body text-zendo-gold">{{ $property->propertyType->name }}</span>
               @endif
               <h3 class="mt-2 text-2xl font-medium font-heading text-zendo-navy hover:text-zendo-gold transition-colors">
                  <a href="{{ route('properties.show', $property->slug) }}">{{ $property->title }}</a>
               </h3>
               <p class="mt-2 flex items-center text-gray-600 font-body">
                  <img src="{{ asset('main/icons/location.svg') }}" alt="Location Icon" class="w-4 h-4 mr-2 flex-shrink-0">
                  @if($property->location)
                     {{ $property->location->name }}@if($property->city), {{ $property->city->name }}@endif
                  @elseif($property->city)
                     {{ $property->city->name }}
                  @else
                     {{ $property->address }}
                  @endif
               </p>
               <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-gray-700 font-body">
                  @if($property->bhk)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/single-bed.svg') }}" alt="Bed Icon" class="w-4 h-4 mr-1.5">
                     {{ $property->bhk->name }}
                  </span>
                  @endif
                  @if($property->specifications && $property->specifications->bathrooms)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/bathroom.svg') }}" alt="Bathroom Icon" class="w-4 h-4 mr-1.5">
                     Baths: {{ $property->specifications->bathrooms }}
                  </span>
                  @endif
                  @if($property->carpet_area)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/full-size.svg') }}" alt="Area Icon" class="w-4 h-4 mr-1.5">
                     {{ number_format($property->carpet_area) }} Sq Ft
                  </span>
                  @endif
               </div>
            </div>
         </div>
         @empty
         <!-- No Popular Properties -->
         <div class="col-span-full text-center py-12">
            <p class="text-gray-600 font-body text-lg">No popular properties available at the moment.</p>
         </div>
         @endforelse
      </div>
      <!-- View Properties Button -->
      <div class="text-center mt-12">
         <a href="{{ route('properties.index') }}"
            class="px-8 py-3 rounded-full transition-all transform hover:scale-105 inline-block font-highlight font-semibold shadow-lg btn-anim btn-light-bg">
         View Properties
         </a>
      </div>
   </div>
</section>
<!-- GET INQUIRY SECTION -->
<section id="inquiry-section" class="py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
         <span class="section-subheading-dark-bg">Get In Touch</span>
         <h2 class="font-heading text-white">
            Get Free Consultation
         </h2>
         <p class="text-lg text-gray-300 font-body max-w-2xl mx-auto">
            Ready to find your dream property? Get in touch with our experts for personalized assistance.
         </p>
      </div>
      <div class="max-w-4xl mx-auto">
         <form id="inquiryForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <!-- Name -->
            <div>
               <input type="text" name="name" placeholder="Your Full Name"
                  class="inquiry-form-input w-full" required>
            </div>
            <!-- Phone -->
            <div>
               <input type="tel" name="phone" placeholder="Your Phone Number"
                  class="inquiry-form-input w-full" required>
            </div>
            <!-- Email -->
            <div>
               <input type="email" name="email" placeholder="Your Email Address"
                  class="inquiry-form-input w-full" required>
            </div>
            <!-- Property Type -->
            <div>
               <select name="property_type" class="inquiry-form-input w-full">
                  <option value="">Property Type</option>
                  <option value="apartment">Apartment</option>
                  <option value="villa">Villa</option>
                  <option value="office">Office Space</option>
                  <option value="commercial">Commercial</option>
               </select>
            </div>
            <!-- Message -->
            <div class="md:col-span-2">
               <textarea name="message" placeholder="Tell us about your requirements..." rows="4"
                  class="inquiry-form-input w-full resize-none"></textarea>
            </div>
            <!-- Submit Button -->
            <div class="md:col-span-2 text-center">
               <button type="submit"
                  class="px-8 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 btn-anim btn-dark-bg">
               Send Inquiry
               </button>
               <!-- Response Message -->
               <div id="inquiryMessage" class="mt-4 hidden">
                  <div id="inquirySuccess"
                     class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                     <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="inquirySuccessText"></span>
                     </div>
                  </div>
                  <div id="inquiryError"
                     class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                     <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="inquiryErrorText"></span>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</section>
<!-- RENTAL & INVESTMENT PROPERTIES SECTION -->
<section class="bg-pattern-light py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Investment Opportunities</span>
         <h2 class="font-heading text-zendo-navy">
            Rental & Investment Properties
         </h2>
         <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto">
            Discover lucrative investment opportunities and rental properties with high returns.
         </p>
      </div>
      <!-- Property Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 card-grid-container">
         @forelse($rentalProperties as $property)
         <!-- Property Card: {{ $property->title }} -->
         <div class="property-card card-item bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100">
            <div class="relative group overflow-hidden">
               <img src="{{ $property->main_image_url ?? asset('main/images/properties/default.png') }}" 
                  alt="{{ $property->title }}"
                  class="card-image w-full h-64 object-cover">
               <div class="absolute top-4 left-4 flex space-x-2">
                  @if($property->propertyType)
                  <span class="px-3 py-1 rounded text-sm font-semibold bg-zendo-gold text-white">{{ $property->propertyType->name }}</span>
                  @endif
               </div>
               <div class="absolute bottom-4 left-4">
                  <span class="text-2xl font-medium font-heading text-white bg-black/50 px-3 py-1 rounded">{{ $property->formatted_price }}</span>
               </div>
            </div>
            <div class="p-6">
               @if($property->propertyType)
               <span class="text-sm font-semibold font-body text-zendo-gold">{{ $property->propertyType->name }}</span>
               @endif
               <h3 class="mt-2 text-2xl font-medium font-heading text-zendo-navy hover:text-zendo-gold transition-colors">
                  <a href="{{ route('properties.show', $property->slug) }}">{{ $property->title }}</a>
               </h3>
               <p class="mt-2 flex items-center text-gray-600 font-body">
                  <img src="{{ asset('main/icons/location.svg') }}" alt="Location Icon" class="w-4 h-4 mr-2 flex-shrink-0">
                  @if($property->location)
                     {{ $property->location->name }}@if($property->city), {{ $property->city->name }}@endif
                  @elseif($property->city)
                     {{ $property->city->name }}
                  @else
                     {{ $property->address }}
                  @endif
               </p>
               <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-gray-700 font-body">
                  @if($property->bhk)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/single-bed.svg') }}" alt="Bed Icon" class="w-4 h-4 mr-1.5">
                     {{ $property->bhk->name }}
                  </span>
                  @endif
                  @if($property->specifications && $property->specifications->bathrooms)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/bathroom.svg') }}" alt="Bathroom Icon" class="w-4 h-4 mr-1.5">
                     Baths: {{ $property->specifications->bathrooms }}
                  </span>
                  @endif
                  @if($property->carpet_area)
                  <span class="flex items-center text-sm">
                     <img src="{{ asset('main/icons/full-size.svg') }}" alt="Area Icon" class="w-4 h-4 mr-1.5">
                     {{ number_format($property->carpet_area) }} Sq Ft
                  </span>
                  @endif
               </div>
            </div>
         </div>
         @empty
         <!-- No Rental Properties -->
         <div class="col-span-full text-center py-12">
            <p class="text-gray-600 font-body text-lg">No rental or investment properties available at the moment.</p>
            <a href="{{ route('properties.index') }}" class="mt-4 inline-block px-6 py-2 rounded-full bg-zendo-gold text-white font-semibold hover:bg-zendo-navy transition-colors">
               View All Properties
            </a>
         </div>
         @endforelse
      </div>
   </div>
</section>
<!-- WHY CHOOSE US SECTION -->
<section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Our Services</span>
         <h2 class="font-heading text-zendo-navy">Why Choose Us
         </h2>
         <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto">
            We provide full service at every step.
         </p>
      </div>
      <!-- Feature Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 card-grid-container">
         @forelse($whyChooseUsFeatures as $feature)
         <!-- Feature Card: {{ $feature->title }} -->
         <div
            class="why-choose-card card-item bg-white rounded-lg shadow-xl p-8 text-center border border-gray-100">
            <div
               class="w-20 h-20 mx-auto bg-zendo-light-bg rounded-full flex items-center justify-center mb-6">
               @if ($feature->icon)
               <img src="{{ $feature->icon_url }}" alt="{{ $feature->title }} Icon"
                  class="w-10 h-10 text-zendo-gold">
               @else
               <div class="w-10 h-10 bg-zendo-gold rounded-full"></div>
               @endif
            </div>
            <h3 class="text-xl font-semibold font-heading text-zendo-navy mb-3">{{ $feature->title }}</h3>
            <p class="text-gray-600 font-body leading-relaxed">{{ $feature->description }}</p>
         </div>
         @empty
         <!-- No features message -->
         <div class="col-span-full text-center py-12">
            <p class="text-gray-600 font-body text-lg">No features available. Please add features with
               'why-choose-us' tag from admin panel.
            </p>
         </div>
         @endforelse
      </div>
   </div>
</section>
<!-- VIDEO TOUR SECTION -->
<section class="bg-pattern-light py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Main container for the section -->
      <div class="relative">
         <!-- Navy background box -->
         <div class="bg-zendo-navy rounded-lg shadow-xl lg:flex lg:items-center lg:p-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
               <!-- Left Column (Text) -->
               <div class="relative z-10 p-8 md:p-12 lg:p-16">
                  <span class="section-subheading-dark-bg">Take a video tour</span>
                  <h2 class="font-heading text-white">
                     Watch the video for taking your decision easily.
                  </h2>
                  <a href="#"
                     class="inline-flex items-center font-semibold font-highlight text-zendo-gold hover:text-white transition-colors group">
                     View all
                     <svg class="w-5 h-5 ml-2 transition-transform transform group-hover:translate-x-1"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                     </svg>
                  </a>
               </div>
               <!-- Right Column (Video/Image) -->
               <div class="relative z-10 p-4 md:p-8 lg:p-0 lg:-mr-12">
                  <!-- Decorative Dots -->
                  <div class="absolute top-0 right-4 lg:right-16 z-0 grid grid-cols-6 gap-2 w-24" x-data>
                     <template x-for="i in 36">
                        <span class="block w-2 h-2 bg-zendo-gold opacity-20 rounded-full"></span>
                     </template>
                  </div>
                  <!-- Video Thumbnail -->
                  <div class="relative rounded-r-lg overflow-hidden shadow-2xl z-10 w-full h-auto aspect-video">
                     <img src="{{ asset('main/images/properties/morden-villa.png') }}"
                        alt="Video Tour Thumbnail" class="w-full h-full object-cover">
                     <!-- Play Button -->
                     <a href="#" class="video-popup-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                           <path
                              d="M7 6.27734V17.7227C7 18.4239 7.74953 18.8412 8.38883 18.4633L18.1754 12.7407C18.7774 12.388 18.7774 11.612 18.1754 11.2593L8.38883 5.53671C7.74953 5.15881 7 5.57613 7 6.27734Z">
                           </path>
                        </svg>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- "Have a question?" text -->
      <div class="mt-5 text-center lg:text-left">
         <p class="text-lg font-body text-gray-700">
            Have a question? <a href="tel:+01234874854"
               class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors font-highlight">+01234
            874 854</a>
         </p>
      </div>
   </div>
</section>
<!-- TESTIMONIALS SECTION -->
<section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Testimonial</span>
         <h2 class="font-heading text-zendo-navy">What Our Clients Say About Us
         </h2>
      </div>
      <!-- Carousel Wrapper -->
      <div class="relative">
         <div class="overflow-hidden">
            <div id="testimonial-carousel" class="testimonial-carousel -mx-4">
               @forelse($testimonials as $testimonial)
               <!-- Testimonial Slide -->
               <div class="testimonial-slide">
                  <div
                     class="bg-zendo-light-bg p-8 rounded-lg shadow-lg border border-gray-200 h-full flex flex-col justify-between">
                     <div>
                        <img src="{{ asset('main/icons/quote.svg') }}" alt="Quote Icon"
                           class="w-10 h-10 text-zendo-gold mb-4">
                        <p class="text-gray-700 font-body italic leading-relaxed mb-6">
                           "{{ $testimonial->content }}"
                        </p>
                     </div>
                     <div class="flex items-center mt-auto">
                        @if ($testimonial->avatar)
                        <img class="w-12 h-12 rounded-full mr-4 object-cover"
                           src="{{ Storage::url($testimonial->avatar) }}"
                           alt="{{ $testimonial->name }}">
                        @else
                        <div
                           class="w-12 h-12 rounded-full mr-4 bg-zendo-gold flex items-center justify-center">
                           <span class="text-white font-semibold text-sm">
                           {{ $testimonial->initials }}
                           </span>
                        </div>
                        @endif
                        <div>
                           <p class="font-semibold font-heading text-zendo-navy">
                              {{ $testimonial->name }}
                           </p>
                           <p class="text-sm text-gray-600 font-body">
                              {{ $testimonial->full_title }}
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
               @empty
               <p class="text-center text-gray-500">No testimonials found.</p>
               @endforelse
            </div>
         </div>
         <!-- Dots -->
         <div id="testimonial-dots" class="testimonial-dots flex justify-center space-x-2 mt-8">
            <!-- Dots will be generated here by JS -->
         </div>
      </div>
   </div>
</section>
<!-- BLOG SECTION -->
<section class="bg-pattern-light py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
         <span class="section-subheading">Our Recent Post</span>
         <h2 class="font-heading text-zendo-navy">Publish What We Think About Our Company Activities
         </h2>
      </div>
      <!-- Blog Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 card-grid-container">
         @forelse($blogs as $blog)
         <!-- Blog Card: {{ $blog->title }} -->
         <div class="blog-card card-item bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <a href="{{ route('blogs.show', $blog->slug) }}">
               <div class="overflow-hidden">
                  <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}"
                     class="card-image w-full h-48 object-cover">
               </div>
               <div class="p-6">
                  <h3
                     class="text-xl font-semibold font-heading text-zendo-navy hover:text-zendo-gold transition-colors mb-3">
                     {{ $blog->title }}
                  </h3>
                  <p class="text-gray-600 font-body text-sm leading-relaxed mb-4 line-clamp-3">
                     {{ $blog->excerpt }}
                  </p>
                  <div class="flex items-center text-xs text-gray-500 font-body">
                     <span>BY {{ strtoupper($blog->author ?? 'ADMIN') }}</span>
                     <span class="mx-2">|</span>
                     <span>{{ $blog->published_date ? $blog->published_date->format('F d, Y') : 'DRAFT' }}</span>
                  </div>
               </div>
            </a>
         </div>
         @empty

         @endforelse
      </div>
      
      <!-- View All Blogs Button -->
      <div class="text-center mt-12">
         <a href="{{ route('blogs.index') }}"
            class="px-8 py-3 rounded-full transition-all transform hover:scale-105 inline-block font-highlight font-semibold shadow-lg btn-anim btn-light-bg">
         View All Blogs
         </a>
      </div>
   </div>
</section>
<!-- LUXURY REAL ESTATE FORM -->
<section id="luxRealEstateForm" class="lux-real-section">
   <div class="lux-real-overlay"></div>
   <div class="lux-real-container">
      <!-- LEFT -->
      <div class="lux-real-left">
         <span class="lux-subheading">Premium Property</span>
         <h2 class="lux-main-heading">Find Your Dream Home in the City of Comfort</h2>
         <p class="lux-content">Luxury residences located in top neighbourhoods with world-class amenities and
            premium lifestyle experiences.
         </p>
         <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d" alt="property"
            class="lux-left-image">
      </div>
      <!-- RIGHT FORM -->
      <div class="lux-real-form">
         <h3><i class="fas fa-home"></i> Get Free Consultation</h3>
         <form id="luxuryInquiryForm">
            @csrf
            <div class="lux-double">
               <div>
                  <label><i class="fas fa-user"></i> Full Name</label>
                  <input type="text" name="name" placeholder="Enter your name" required>
               </div>
               <div>
                  <label><i class="fas fa-phone-alt"></i> Phone Number</label>
                  <input type="tel" name="phone" placeholder="+91" required>
               </div>
            </div>
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            <div class="lux-double">
               <div>
                  <label><i class="fas fa-map-marker-alt"></i> Location</label>
                  <input type="text" name="location" placeholder="City / Area">
               </div>
               <div>
                  <label><i class="fas fa-rupee-sign"></i> Budget Range</label>
                  <select name="property_type">
                     <option value="30-50 Lakhs">30-50 Lakhs</option>
                     <option value="50-70 Lakhs">50-70 Lakhs</option>
                     <option value="70 Lakhs - 1 Cr">70 Lakhs - 1 Cr</option>
                     <option value="Above 1 Cr">Above 1 Cr</option>
                  </select>
               </div>
            </div>
            <label><i class="fas fa-comment-dots"></i> Requirements</label>
            <textarea name="message" placeholder="Write your specific requirement"></textarea>
            <button type="submit" class="lux-submit-btn">Request Call Back</button>
            <!-- Response Message -->
            <div id="luxuryMessage" class="mt-4 hidden">
               <div id="luxurySuccess"
                  class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                  <div class="flex items-center">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     <span id="luxurySuccessText"></span>
                  </div>
               </div>
               <div id="luxuryError"
                  class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                  <div class="flex items-center">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     <span id="luxuryErrorText"></span>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</section>
<!-- FAQ SECTION -->
<section class="bg-pattern-white py-24 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
         <!-- Left: Accordion -->
         <div x-data="{ open: 1 }">
            <div class="flex justify-between items-center mb-8">
               <div>
                  <span class="section-subheading">Guest FAQs</span>
                  <h2 class="font-heading text-zendo-navy">Frequently Asked Questions</h2>
               </div>
            </div>
            <div class="space-y-4">
               @forelse($faqs as $index => $faq)
               <!-- FAQ Item {{ $index + 1 }} -->
               <div class="faq-item">
                  <button @click="open = (open === {{ $index + 1 }} ? null : {{ $index + 1 }})"
                     :aria-expanded="open === {{ $index + 1 }}" class="faq-question w-full text-left">
                     <span class="text-lg font-semibold">{{ $faq->question }}</span>
                     <svg class="faq-icon w-6 h-6" :class="{ 'rotate-45': open === {{ $index + 1 }} }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 6v12m6-6H6"></path>
                     </svg>
                  </button>
                  <div x-show="open === {{ $index + 1 }}" x-collapse x-cloak class="faq-answer">
                     <p class="font-body text-gray-600 px-6 pb-6 pt-4">{{ $faq->answer }}</p>
                  </div>
               </div>
               @empty
               <!-- Default FAQs if none exist in database -->
               <div class="faq-item">
                  <button @click="open = (open === 1 ? null : 1)" :aria-expanded="open === 1"
                     class="faq-question w-full text-left">
                     <span class="text-lg font-semibold">What facilities are available at the
                     property?</span>
                     <svg class="faq-icon w-6 h-6" :class="{ 'rotate-45': open === 1 }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 6v12m6-6H6"></path>
                     </svg>
                  </button>
                  <div x-show="open === 1" x-collapse x-cloak class="faq-answer">
                     <p class="font-body text-gray-600 px-6 pb-6 pt-4">Our properties typically include
                        high-speed Wi-Fi, modern kitchen appliances, and access to community amenities.
                     </p>
                  </div>
               </div>
               <div class="faq-item">
                  <button @click="open = (open === 2 ? null : 2)" :aria-expanded="open === 2"
                     class="faq-question w-full text-left">
                     <span class="text-lg font-semibold">Is breakfast included in the room rate?</span>
                     <svg class="faq-icon w-6 h-6" :class="{ 'rotate-45': open === 2 }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 6v12m6-6H6"></path>
                     </svg>
                  </button>
                  <div x-show="open === 2" x-collapse x-cloak class="faq-answer">
                     <p class="font-body text-gray-600 px-6 pb-6 pt-4">This depends on the specific property
                        listing. Please check the "Amenities" section on the property details page for more
                        information.
                     </p>
                  </div>
               </div>
               <div class="faq-item">
                  <button @click="open = (open === 3 ? null : 3)" :aria-expanded="open === 3"
                     class="faq-question w-full text-left">
                     <span class="text-lg font-semibold">Do you provide airport transfer services?</span>
                     <svg class="faq-icon w-6 h-6" :class="{ 'rotate-45': open === 3 }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 6v12m6-6H6"></path>
                     </svg>
                  </button>
                  <div x-show="open === 3" x-collapse x-cloak class="faq-answer">
                     <p class="font-body text-gray-600 px-6 pb-6 pt-4">We do not directly provide transfer
                        services, but we can recommend trusted local partners for your convenience.
                     </p>
                  </div>
               </div>
               <div class="faq-item">
                  <button @click="open = (open === 4 ? null : 4)" :aria-expanded="open === 4"
                     class="faq-question w-full text-left">
                     <span class="text-lg font-semibold">Is the hotel family-friendly?</span>
                     <svg class="faq-icon w-6 h-6" :class="{ 'rotate-45': open === 4 }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M12 6v12m6-6H6"></path>
                     </svg>
                  </button>
                  <div x-show="open === 4" x-collapse x-cloak class="faq-answer">
                     <p class="font-body text-gray-600 px-6 pb-6 pt-4">Many of our properties are
                        family-friendly. You can use the "Family-Friendly" filter in your search to find
                        suitable options for your stay.
                     </p>
                  </div>
               </div>
               @endforelse
            </div>
            <a href="#"
               class="px-5 py-2.5 mt-8 rounded-full font-highlight font-semibold shadow-lg transform hover:scale-105 btn-anim btn-light-bg sm:hidden inline-block">
            See More
            </a>
         </div>
         <!-- Right: Image -->
         <div class="hidden lg:block">
            <img src="{{ asset('main/images/faq-zendo.png') }}" alt="Helpful Staff"
               class="rounded-lg shadow-xl w-full h-full object-cover">
         </div>
      </div>
   </div>
</section>
<!-- LEAD FORM -->
<section class="bg-pattern-light py-16 animate-on-scroll fade-in-up">
   <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-lg shadow-xl p-8 md:p-12 flex flex-col md:flex-row justify-between items-center">
         <div>
            <h3 class="font-heading text-zendo-navy" style="font-size:30px;">ARE YOU LOOKING FOR A HOUSE?</h3>
            <p class="text-gray-600 font-body">We can help you realize your dream of a new home.</p>
         </div>
         <div class="mt-6 md:mt-0">
            <a href="{{ route('contact') }}"
               class="px-6 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 inline-block btn-anim btn-light-bg">Contact us
            </a>
         </div>
      </div>
   </div>
</section>
@endsection
@section('styles')
<style>
   /* Section */
   .lux-real-section {
   position: relative;
   background-image: url('{{ asset('main/images/bg/cta-bg.jpg') }}');
   background-size: cover;
   background-position: center;
   padding: 100px 40px;
   }
   /* Overlay */
   .lux-real-overlay {
   background: rgba(11, 44, 61, 0.7);
   position: absolute;
   width: 100%;
   height: 100%;
   top: 0;
   left: 0;
   }
   .lux-double {
   display: flex;
   gap: 20px;
   }
   .lux-double div {
   flex: 1;
   }
   /* Container */
   .lux-real-container {
   position: relative;
   max-width: 1200px;
   margin: auto;
   display: flex;
   gap: 50px;
   align-items: center;
   z-index: 5;
   }
   /* LEFT */
   .lux-real-left {
   flex: 1;
   color: white;
   }
   .lux-subheading {
   color: #C9A253;
   font-size: 18px;
   font-weight: 600;
   letter-spacing: 2px;
   }
   .lux-main-heading {
   font-size: 42px;
   color: white;
   margin: 15px 0;
   }
   .lux-content {
   font-size: 18px;
   max-width: 450px;
   color: #e4d6b4;
   }
   .lux-left-image {
   width: 100%;
   height: 350px;
   border-radius: 14px;
   margin-top: 35px;
   box-shadow: 0 0 30px rgba(0, 0, 0, .4);
   }
   /* RIGHT FORM */
   .lux-real-form {
   flex: 1;
   background: rgba(255, 255, 255, 0.08);
   padding: 40px;
   border-radius: 20px;
   border: 1px solid rgb(255 255 255 / 23%);
   backdrop-filter: blur(6px);
   box-shadow: 0 0 40px rgba(0, 0, 0, .3);
   }
   .lux-real-form h3 {
   color: white;
   font-size: 26px;
   margin-bottom: 25px;
   }
   .lux-real-form label {
   color: white;
   font-size: 15px;
   margin-bottom: 6px;
   display: block;
   }
   .lux-real-form input,
   .lux-real-form select {
   width: 100%;
   padding: 12px;
   border: none;
   outline: none;
   border-radius: 8px;
   margin-bottom: 22px;
   background: rgba(0, 0, 0, .35);
   color: white;
   }
   /* Button */
   .lux-submit-btn {
   width: 100%;
   padding: 14px;
   background: #b39359;
   border: none;
   border-radius: 50px;
   color: white;
   font-weight: 700;
   cursor: pointer;
   transition: 0.4s;
   }
   .lux-submit-btn:hover {
   background: white;
   color: #b39359;
   }
   .lux-real-form textarea {
   width: 100%;
   padding: 14px;
   background: rgba(0, 0, 0, .35);
   border: none;
   outline: none;
   border-radius: 8px;
   margin-bottom: 22px;
   color: white;
   min-height: 90px;
   resize: none;
   }
   /* Responsive */
   @media(max-width:900px) {
   .lux-real-container {
   flex-direction: column;
   }
   }
   /* Luxury form message styling */
   .lux-real-form #luxuryMessage .bg-green-100 {
   background: rgba(34, 197, 94, 0.1);
   border-color: rgba(34, 197, 94, 0.3);
   color: #16a34a;
   }
   .lux-real-form #luxuryMessage .bg-red-100 {
   background: rgba(239, 68, 68, 0.1);
   border-color: rgba(239, 68, 68, 0.3);
   color: #dc2626;
   }
   .lux-real-form #luxuryMessage svg {
   color: inherit;
   }
</style>
@endsection
@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
   const tabButtons = document.querySelectorAll(".search-tab-button");
   const normalForm = document.getElementById("normalSearchForm");
   const globalForm = document.getElementById("globalSearchForm");
   
   // ---- Active tab UI + pointer
   function setActiveTab(btn) {
       tabButtons.forEach(b => {
           b.classList.remove("active", "bg-white", "text-zendo-navy", "font-bold");
           b.classList.add("bg-gray-200/80", "text-gray-700", "font-semibold");
   
           const pointer = b.querySelector(".tab-pointer");
           if (pointer) pointer.classList.add("hidden");
       });
   
       btn.classList.add("active", "bg-white", "text-zendo-navy", "font-bold");
       btn.classList.remove("bg-gray-200/80", "text-gray-700", "font-semibold");
   
       const pointer = btn.querySelector(".tab-pointer");
       if (pointer) pointer.classList.remove("hidden");
   }
   
   // ---- Show correct form
   function toggleForms(tab) {
       if (tab === "global") {
           if (normalForm) normalForm.classList.add("hidden");
           if (globalForm) globalForm.classList.remove("hidden");
       } else {
           if (globalForm) globalForm.classList.add("hidden");
           if (normalForm) normalForm.classList.remove("hidden");
       }
   }
   
   // ---- Tab click
   tabButtons.forEach(btn => {
       btn.addEventListener("click", () => {
           const tab = btn.dataset.tab;
           setActiveTab(btn);
           toggleForms(tab);
           filterPropertyTypes(tab);
       });
   });
   
   // ---- Filter property types based on selected service type
   function filterPropertyTypes(serviceTypeSlug) {
       const typeSelect = document.getElementById("type");
       if (!typeSelect) return;
       
       const options = typeSelect.querySelectorAll("option");
       let hasVisibleOptions = false;
       
       options.forEach(option => {
           // Skip the placeholder option
           if (option.disabled && option.selected) {
               option.style.display = '';
               return;
           }
           
           // Skip fallback options (those without data-service-types attribute)
           if (!option.hasAttribute('data-service-types')) {
               option.style.display = '';
               return;
           }
           
           const serviceTypes = option.getAttribute('data-service-types');
           
           // Show option if it's mapped to the selected service type
           if (serviceTypes && serviceTypes.split(',').includes(serviceTypeSlug)) {
               option.style.display = '';
               hasVisibleOptions = true;
           } else {
               option.style.display = 'none';
           }
       });
       
       // Reset selection to placeholder
       typeSelect.selectedIndex = 0;
   }
   
   // Initialize property types filter on page load
   document.addEventListener('DOMContentLoaded', function() {
       const activeTab = document.querySelector(".search-tab-button.active");
       if (activeTab) {
           const tab = activeTab.dataset.tab;
           filterPropertyTypes(tab);
       }
   });
   
   normalForm.addEventListener("submit", function(e) {
       e.preventDefault();
   
       const purpose = document.querySelector(".search-tab-button.active")?.dataset.tab || "buy";
       const type = document.getElementById("type").value;
       const location = document.getElementById("location").value;
   
       const params = new URLSearchParams();
       
       // Add property type filter if selected
       if (type && type !== "Select Type") {
           params.append("property_type_slug", type);
       }
       
       // Add location search if entered
       if (location && location.trim() !== "") {
           params.append("search", location.trim());
       }
       
       // Add service type filter
       if (purpose) {
           params.append("service_type", purpose);
       }
   
       // Redirect to properties listing page with filters
       window.location.href = `/properties?${params.toString()}`;
   });
   
   // ---- Global Form submit (ONLY keyword + purpose=global)
   if (globalForm) {
       globalForm.addEventListener("submit", function(e) {
           e.preventDefault();
       
           const keyword = document.getElementById("global-search").value.trim();
           const params = new URLSearchParams();
       
           if (keyword) params.append("q", keyword);
           params.append("purpose", "global");
       
           // ✅ change this URL to your global search result page (or same listing)
           window.location.href = `/properties/search?${params.toString()}`;
       });
   }
   
   // ---- Inquiry Form Submission
   document.getElementById('inquiryForm').addEventListener('submit', function(e) {
       e.preventDefault();
   
       const formData = new FormData(this);
       const submitButton = this.querySelector('button[type="submit"]');
       const originalText = submitButton.textContent;
   
       // Hide previous messages
       hideInquiryMessages();
   
       // Show loading state
       submitButton.textContent = 'Sending...';
       submitButton.disabled = true;
   
       fetch('{{ route('consultations.store') }}', {
               method: 'POST',
               body: formData,
               headers: {
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                       'content')
               }
           })
           .then(response => response.json())
           .then(data => {
               if (data.success) {
                   // Show success message
                   showInquiryMessage('success', data.message);
                   // Reset form
                   this.reset();
               } else {
                   // Show error message
                   showInquiryMessage('error', data.message || 'Something went wrong. Please try again.');
               }
           })
           .catch(error => {
               console.error('Error:', error);
               showInquiryMessage('error', 'Something went wrong. Please try again.');
           })
           .finally(() => {
               // Reset button state
               submitButton.textContent = originalText;
               submitButton.disabled = false;
           });
   });
   
   // ---- Luxury Inquiry Form Submission
   document.getElementById('luxuryInquiryForm').addEventListener('submit', function(e) {
       e.preventDefault();
   
       const formData = new FormData(this);
       const submitButton = this.querySelector('button[type="submit"]');
       const originalText = submitButton.textContent;
   
       // Hide previous messages
       hideLuxuryMessages();
   
       // Show loading state
       submitButton.textContent = 'Sending...';
       submitButton.disabled = true;
   
       fetch('{{ route('consultations.store') }}', {
               method: 'POST',
               body: formData,
               headers: {
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                       'content')
               }
           })
           .then(response => response.json())
           .then(data => {
               if (data.success) {
                   // Show success message
                   showLuxuryMessage('success', data.message);
                   // Reset form
                   this.reset();
               } else {
                   // Show error message
                   showLuxuryMessage('error', data.message || 'Something went wrong. Please try again.');
               }
           })
           .catch(error => {
               console.error('Error:', error);
               showLuxuryMessage('error', 'Something went wrong. Please try again.');
           })
           .finally(() => {
               // Reset button state
               submitButton.textContent = originalText;
               submitButton.disabled = false;
           });
   });
   
   // Helper functions for inquiry form messages
   function showInquiryMessage(type, message) {
       const messageContainer = document.getElementById('inquiryMessage');
       const successDiv = document.getElementById('inquirySuccess');
       const errorDiv = document.getElementById('inquiryError');
       const successText = document.getElementById('inquirySuccessText');
       const errorText = document.getElementById('inquiryErrorText');
   
       messageContainer.classList.remove('hidden');
   
       if (type === 'success') {
           successText.textContent = message;
           successDiv.classList.remove('hidden');
           errorDiv.classList.add('hidden');
       } else {
           errorText.textContent = message;
           errorDiv.classList.remove('hidden');
           successDiv.classList.add('hidden');
       }
   
       // Auto-hide after 5 seconds
       setTimeout(() => {
           hideInquiryMessages();
       }, 5000);
   }
   
   function hideInquiryMessages() {
       const messageContainer = document.getElementById('inquiryMessage');
       const successDiv = document.getElementById('inquirySuccess');
       const errorDiv = document.getElementById('inquiryError');
   
       messageContainer.classList.add('hidden');
       successDiv.classList.add('hidden');
       errorDiv.classList.add('hidden');
   }
   
   // Helper functions for luxury form messages
   function showLuxuryMessage(type, message) {
       const messageContainer = document.getElementById('luxuryMessage');
       const successDiv = document.getElementById('luxurySuccess');
       const errorDiv = document.getElementById('luxuryError');
       const successText = document.getElementById('luxurySuccessText');
       const errorText = document.getElementById('luxuryErrorText');
   
       messageContainer.classList.remove('hidden');
   
       if (type === 'success') {
           successText.textContent = message;
           successDiv.classList.remove('hidden');
           errorDiv.classList.add('hidden');
       } else {
           errorText.textContent = message;
           errorDiv.classList.remove('hidden');
           successDiv.classList.add('hidden');
       }
   
       // Auto-hide after 5 seconds
       setTimeout(() => {
           hideLuxuryMessages();
       }, 5000);
   }
   
   function hideLuxuryMessages() {
       const messageContainer = document.getElementById('luxuryMessage');
       const successDiv = document.getElementById('luxurySuccess');
       const errorDiv = document.getElementById('luxuryError');
   
       messageContainer.classList.add('hidden');
       successDiv.classList.add('hidden');
       errorDiv.classList.add('hidden');
   }
</script>
@endsection