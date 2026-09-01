<?php

namespace App\Http\Controllers;

use App\Models\AboutPageSection;
use App\Models\AdvisoryPageSection;
use App\Models\AboutUs;
use App\Models\Amenity;
use App\Models\Bhk;
use App\Models\Blog;
use App\Models\Builder;
use App\Models\Category;
use App\Models\City;
use App\Models\CommercialSection;
use App\Models\ContactInfo;
use App\Models\ContactPageSection;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\HeroSection;
use App\Models\Location;
use App\Models\OurClient;
use App\Models\PrivacyPolicy;
use App\Models\TermsAndCondition;
use App\Models\ProjectStatus;
use App\Models\Property;
use App\Models\PropertyPageSection;
use App\Models\PropertyType;
use App\Models\ServiceType;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\VideoTourSection;
use App\Models\WorkProcess;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::active()->ordered()->get();
        $testimonials = Testimonial::active()->ordered()->get();
        $faqs = Faq::active()->ordered()->get();
        $features = Feature::active()->ordered()->get();
        $whyChooseUsFeatures = Feature::active()->byTag('our-services')->ordered()->get();
        $latestPropertiesFeatures = Feature::active()->byTag('why-choose-us')->ordered()->get();
        $aboutUs = AboutUs::getActive();
        $categories = Category::active()->ordered()->get();
        $cities = City::active()->ordered()->get();
        $commercialSection = CommercialSection::getActive();
        $serviceTypes = ServiceType::active()->ordered()->with('propertyTypes')->get();
        $propertyTypes = PropertyType::active()->ordered()->get();
        $featuredProperties = Property::with(['propertyType', 'bhk', 'city', 'location', 'projectStatus', 'mainImage'])->active()->published()->featured()->latest('published_at')->limit(3)->get();
        $popularProperties = Property::with(['propertyType', 'bhk', 'city', 'location', 'projectStatus', 'mainImage', 'specifications'])->active()->published()->orderBy('views_count', 'desc')->limit(3)->get();
        $rentalProperties = Property::with(['propertyType', 'bhk', 'city', 'location', 'projectStatus', 'mainImage', 'specifications'])->active()->published()->latest('published_at')->limit(3)->get();
        $blogs = Blog::active()->published()->ordered()->limit(4)->get();
        $videoTour = VideoTourSection::getActive();
        $serviceTypeMapping = [];
        foreach ($serviceTypes as $serviceType) {
            $serviceTypeMapping[$serviceType->slug] = $serviceType->propertyTypes->pluck('slug')->toArray();
        }
        return view('pages.home', compact('heroSections', 'testimonials', 'faqs', 'features', 'whyChooseUsFeatures', 'latestPropertiesFeatures', 'aboutUs', 'categories', 'cities', 'commercialSection', 'serviceTypes', 'propertyTypes', 'serviceTypeMapping', 'featuredProperties', 'popularProperties', 'rentalProperties', 'blogs', 'videoTour'));
    }

    public function search(Request $request)
    {
        return redirect()->route('home')->with('message', 'Search functionality coming soon!');
    }

    public function properties(Request $request)
    {
        // property_entries is the sole source for this page — no legacy
        // `properties` table involved. Publicly visible = admin-approved
        // AND toggled on for the website (scopePubliclyVisible), which is
        // deliberately independent of the wizard's internal review status.
        $query = \App\Models\PropertyEntry::with(['photos'])->publiclyVisible();

        $selectedPropertyTypeKey = $request->filled('property_type_slug') ? $request->property_type_slug : null;
        if ($selectedPropertyTypeKey) {
            if ($selectedPropertyTypeKey === 'warehouse' || $selectedPropertyTypeKey === 'warehousing') {
                $query->where(function ($q) {
                    $q->where('property_type', 'warehouse')
                      ->orWhere('facility_type', 'Warehouse')
                      ->orWhere('facility_type', 'like', '%warehouse%');
                });
            } elseif ($selectedPropertyTypeKey === 'residential') {
                $residentialTypes = ['apartment_flat_studio', 'house_villa_farmhouse', 'builder_floor', 'residential_plot_land', 'service_apartment_pg'];
                $query->where(function ($q) use ($residentialTypes) {
                    $q->whereIn('property_type', $residentialTypes)
                      ->orWhere('facility_type', 'like', '%residential%');
                });
            } elseif ($selectedPropertyTypeKey === 'commercial') {
                $commercialTypes = [
                    'office_space', 'retail_shop_showroom', 'sez_eou_stpi_unit',
                    'factory_manufacturing_industrial', 'commercial_institutional_land',
                    'agricultural_farm_land', 'multi_tenant_building', 'hotel_resort_guesthouse_banquet'
                ];
                $query->where(function ($q) use ($commercialTypes) {
                    $q->whereIn('property_type', $commercialTypes)
                      ->orWhere('facility_type', 'like', '%commercial%')
                      ->orWhere('facility_type', 'Commercial Space')
                      ->orWhere('facility_type', 'Industrial Shed')
                      ->orWhere('facility_type', 'Factory');
                });
            } else {
                $query->where(function ($q) use ($selectedPropertyTypeKey) {
                    $q->where('property_type', $selectedPropertyTypeKey)
                      ->orWhere('facility_type', 'like', "%{$selectedPropertyTypeKey}%");
                });
            }
        }

        if ($request->filled('city')) {
            $query->where(function ($q) use ($request) {
                $q->where('city', $request->city)->orWhere('nearest_city', $request->city);
            });
        }

        if ($request->filled('locality')) {
            $query->where('locality_broad_area', $request->locality);
        }

        if ($request->filled('construction_status')) {
            $query->where('construction_listing_status', $request->construction_status);
        }

        if ($request->filled('builder')) {
            $query->where('builder_developer_name', $request->builder);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('property_name', 'like', "%{$s}%")
                    ->orWhere('city', 'like', "%{$s}%")
                    ->orWhere('nearest_city', 'like', "%{$s}%")
                    ->orWhere('locality_broad_area', 'like', "%{$s}%")
                    ->orWhere('facility_type', 'like', "%{$s}%")
                    ->orWhere('code', 'like', "%{$s}%");
            });
        }

        // property_entries has no views_count column (that was legacy-only),
        // so "popular" isn't offered — newest-submitted is the only sort.
        $query->orderByDesc('submitted_at');

        $properties = $query->paginate(12)->withQueryString();

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->boolean('partial')) {
            return view('pages.properties._results', compact('properties'));
        }

        // Every filter's option list is scoped to the same publiclyVisible
        // gate, so a dropdown can never offer a value with zero live rows —
        // it also respects whichever other filters are already active, so
        // City doesn't offer a city with no results for the selected type.
        $filterBase = fn () => \App\Models\PropertyEntry::query()->publiclyVisible();

        $cities = $filterBase()
            ->selectRaw('COALESCE(city, nearest_city) as label')
            ->whereRaw('COALESCE(city, nearest_city) is not null')
            ->distinct()->orderBy('label')->pluck('label');

        $localities = $filterBase()
            ->whereNotNull('locality_broad_area')
            ->when($request->filled('city'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('city', $request->city)->orWhere('nearest_city', $request->city);
            }))
            ->distinct()->orderBy('locality_broad_area')->pluck('locality_broad_area');

        $propertyTypeOptions = $filterBase()
            ->where(function ($q) {
                $q->whereNotNull('property_type')->orWhereNotNull('facility_type');
            })
            ->get(['property_type', 'facility_type'])
            ->map(function ($entry) {
                if ($entry->property_type) {
                    return $entry->property_type;
                }
                if ($entry->facility_type) {
                    $fac = strtolower($entry->facility_type);
                    if (str_contains($fac, 'warehouse')) return 'warehouse';
                    if (str_contains($fac, 'commercial')) return 'office_space';
                    if (str_contains($fac, 'factory') || str_contains($fac, 'industrial')) return 'factory_manufacturing_industrial';
                }
                return null;
            })
            ->filter()
            ->unique()
            ->map(fn ($key) => ['key' => $key, 'label' => config("property_types.types.{$key}.label", ucfirst(str_replace('_', ' ', $key)))])
            ->sortBy('label')->values();

        $constructionStatuses = $filterBase()
            ->whereNotNull('construction_listing_status')
            ->distinct()->orderBy('construction_listing_status')->pluck('construction_listing_status');

        $builders = $filterBase()
            ->whereNotNull('builder_developer_name')
            ->distinct()->orderBy('builder_developer_name')->pluck('builder_developer_name');

        $selectedPropertyType = $selectedPropertyTypeKey
            ? ['key' => $selectedPropertyTypeKey, 'label' => config("property_types.types.{$selectedPropertyTypeKey}.label", $selectedPropertyTypeKey)]
            : null;

        // Property page sections (carousel/perspective/intro) still key off
        // the old PropertyType model — unrelated to the listing data source,
        // left as-is per the "don't touch what isn't broken" scope of this fix.
        $carouselSection = null;
        $perspectiveSection = null;
        $introSection = null;

        $legacyType = $selectedPropertyTypeKey ? PropertyType::where('slug', str_replace('_', '-', $selectedPropertyTypeKey))->first() : null;
        if ($legacyType) {
            $carouselSection = $legacyType->carouselSection()->active()->first();
            $perspectiveSection = $legacyType->perspectiveSection()->active()->first();
            $introSection = $legacyType->introSection()->active()->first();
        } else {
            $residentialType = PropertyType::where('category', 'residential')->active()->first();
            if ($residentialType) {
                $carouselSection = $residentialType->carouselSection()->active()->first();
                $perspectiveSection = $residentialType->perspectiveSection()->active()->first();
                $introSection = $residentialType->introSection()->active()->first();
            }
            if (!$carouselSection) {
                $carouselSection = PropertyPageSection::where('section_key', 'carousel_section')->active()->first();
            }
            if (!$perspectiveSection) {
                $perspectiveSection = PropertyPageSection::where('section_key', 'perspective_section')->active()->first();
            }
            if (!$introSection) {
                $introSection = PropertyPageSection::where('section_key', 'intro_section')->active()->first();
            }
        }

        $workProcesses = WorkProcess::active()->ordered()->get();

        return view('pages.properties', compact(
            'properties', 'cities', 'localities', 'propertyTypeOptions', 'constructionStatuses', 'builders',
            'workProcesses', 'carouselSection', 'perspectiveSection', 'introSection', 'selectedPropertyType'
        ));
    }

    public function showEntry(\App\Models\PropertyEntry $entry)
    {
        $entry->load(['photos']);
        
        // Get field configurations
        $fieldConfigs = \App\Models\PropertyFieldConfig::allKeyed();
        
        // Check if current user has submitted inquiry for this entry
        $userHasSubmittedInquiry = false;
        $isInWishlist = false;
        
        if (auth()->check()) {
            $userHasSubmittedInquiry = \App\Models\PropertyInquiry::where('property_entry_code', $entry->code)
                ->where('user_id', auth()->id())
                ->exists();
            
            // Check if in wishlist
            $isInWishlist = \App\Models\PropertyWishlist::isInWishlist(
                auth()->id(), 
                null, 
                $entry->code
            );
        }
        
        return view('pages.property-entry-detail', compact('entry', 'fieldConfigs', 'userHasSubmittedInquiry', 'isInWishlist'));
    }

    public function show(Property $property)
    {
        $property->incrementViews();
        $property->load([
            'propertyType',
            'bhk',
            'city',
            'location',
            'projectStatus',
            'builder',
            'images',
            'amenities',
            'specifications',
            'faqs' => function ($query) {
                $query->active()->ordered();
            }
        ]);

        // Load builder relationships separately to avoid issues
        if ($property->builder) {
            try {
                $property->builder->load(['amenities', 'projectStatuses']);
            } catch (\Exception $e) {
                // Silently fail if relationship not available
            }
        }

        // Check if in wishlist
        $isInWishlist = false;
        if (auth()->check()) {
            $isInWishlist = \App\Models\PropertyWishlist::isInWishlist(
                auth()->id(), 
                $property->id, 
                null
            );
        }

        $similarProperties = Property::with(['propertyType', 'bhk', 'city', 'mainImage'])
            ->active()->published()
            ->where('id', '!=', $property->id)
            ->where('property_type_id', $property->property_type_id)
            ->where('city_id', $property->city_id)
            ->limit(3)->get();

        return view('pages.property-detail', compact('property', 'similarProperties', 'isInWishlist'));
    }

    public function advisoryServices()
    {
        $advisoryPage = AdvisoryPageSection::getActive();
        return view('pages.advisory-services', compact('advisoryPage'));
    }

    public function about()
    {
        $aboutPage = AboutPageSection::getActive();
        $clients = OurClient::active()->ordered()->get();
        $teamMembers = TeamMember::active()->ordered()->get();
        return view('pages.about', compact('aboutPage', 'clients', 'teamMembers'));
    }

    public function contact()
    {
        $banner = ContactPageSection::getByKey('banner');
        $contactSection = ContactPageSection::getByKey('contact_section');
        $inquirySection = ContactPageSection::getByKey('inquiry_section');
        $contactInfos = ContactInfo::active()->ordered()->get();
        return view('pages.contact', compact('banner', 'contactSection', 'inquirySection', 'contactInfos'));
    }

    public function blogs()
    {
        $blogs = Blog::active()->published()->ordered()->paginate(12);
        return view('pages.blogs', compact('blogs'));
    }

    public function blogShow(Blog $blog)
    {
        $blog->increment('views');
        $relatedBlogs = Blog::active()->published()->where('id', '!=', $blog->id)->when($blog->category, function ($query) use ($blog) {
            $query->where('category', $blog->category);
        })->ordered()->limit(3)->get();
        $recentBlogs = Blog::active()->published()->where('id', '!=', $blog->id)->latest('published_date')->limit(5)->get();
        return view('pages.blog-detail', compact('blog', 'relatedBlogs', 'recentBlogs'));
    }

    public function privacyPolicy()
    {
        $policy = PrivacyPolicy::getActive();
        return view('pages.privacy-policy', compact('policy'));
    }

    /**
     * Display the terms and conditions page.
     */
    public function termsAndConditions()
    {
        $terms = TermsAndCondition::getActive();
        return view('pages.terms-and-conditions', compact('terms'));
    }

    public function acreToBigha()
    {
        return view('pages.calculators.acre-to-bigha');
    }

    public function acreToHectare()
    {
        return view('pages.calculators.acre-to-hectare');
    }

    public function emiCalculator()
    {
        return view('pages.calculators.emi-calculator');
    }

    public function lengthCalculator()
    {
        return view('pages.calculators.length-calculator');
    }

    public function acreToSquareMeter()  { return view('pages.calculators.acre-to-squaremeter'); }
    public function centToSquareFeet()   { return view('pages.calculators.cent-to-square-feet'); }
    public function centToSquareMeter()  { return view('pages.calculators.cent-to-square-meter'); }
    public function cmToMm()             { return view('pages.calculators.cm-to-mm'); }
    public function cmToInches()         { return view('pages.calculators.cm-to-inches'); }
    public function ftToCm()             { return view('pages.calculators.ft-to-cm'); }
    public function ftToInches()         { return view('pages.calculators.ft-to-inches'); }
    public function ftToMm()             { return view('pages.calculators.ft-to-mm'); }

    /**
     * Get BHKs for a specific property type (AJAX endpoint)
     */
    public function getBhksByPropertyType(Request $request)
    {
        $propertyTypeId = $request->input('property_type_id');
        
        if ($propertyTypeId) {
            $propertyType = PropertyType::find($propertyTypeId);
            if ($propertyType) {
                $bhks = $propertyType->bhks()->active()->ordered()->get(['id', 'name']);
            } else {
                $bhks = [];
            }
        } else {
            $bhks = Bhk::active()->ordered()->get(['id', 'name']);
        }
        
        return response()->json([
            'success' => true,
            'bhks' => $bhks,
            'count' => $bhks->count()
        ]);
    }
}
