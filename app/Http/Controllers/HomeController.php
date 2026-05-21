<?php

namespace App\Http\Controllers;

use App\Models\AboutPageSection;
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
        $serviceTypeMapping = [];
        foreach ($serviceTypes as $serviceType) {
            $serviceTypeMapping[$serviceType->slug] = $serviceType->propertyTypes->pluck('slug')->toArray();
        }
        return view('pages.home', compact('heroSections', 'testimonials', 'faqs', 'features', 'whyChooseUsFeatures', 'latestPropertiesFeatures', 'aboutUs', 'categories', 'cities', 'commercialSection', 'serviceTypes', 'propertyTypes', 'serviceTypeMapping', 'featuredProperties', 'popularProperties', 'rentalProperties', 'blogs'));
    }

    public function search(Request $request)
    {
        return redirect()->route('home')->with('message', 'Search functionality coming soon!');
    }

    public function properties(Request $request)
    {
        $query = Property::with(['propertyType', 'bhk', 'city', 'location', 'projectStatus', 'builder', 'mainImage'])->active()->published();
        
        $selectedPropertyType = null;
        
        if ($request->filled('city_id')) {
            $query->filterByCity($request->city_id);
        }
        if ($request->filled('location_id')) {
            $query->filterByLocation($request->location_id);
        }
        if ($request->filled('property_type_id')) {
            $query->filterByPropertyType($request->property_type_id);
            $selectedPropertyType = PropertyType::find($request->property_type_id);
        }
        if ($request->filled('property_type_slug')) {
            $propertyType = PropertyType::where('slug', $request->property_type_slug)->first();
            if ($propertyType) {
                $query->filterByPropertyType($propertyType->id);
                $selectedPropertyType = $propertyType;
            }
        }
        if ($request->filled('bhk_id')) {
            $query->filterByBhk($request->bhk_id);
        }
        if ($request->filled('project_status_id')) {
            $query->filterByProjectStatus($request->project_status_id);
        }
        if ($request->filled('builder_id')) {
            $query->filterByBuilder($request->builder_id);
        }
        $selectedBuilder = $request->filled('builder_id')
            ? Builder::with(['amenities', 'projectStatuses'])->find($request->builder_id)
            : null;        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->filterByPriceRange($request->min_price, $request->max_price);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }
        $properties = $query->paginate(12)->withQueryString();
        $cities = City::active()->ordered()->get();
        $locations = Location::active()->ordered()->get();
        $propertyTypes = PropertyType::active()->ordered()->get();
        
        // Filter BHKs based on selected property type
        if ($selectedPropertyType) {
            $bhks = $selectedPropertyType->bhks()->active()->ordered()->get();
        } else {
            $bhks = Bhk::active()->ordered()->get();
        }
        
        $projectStatuses = ProjectStatus::active()->ordered()->get();
        $builders = Builder::active()->verified()->ordered()->get();
        $workProcesses = WorkProcess::active()->ordered()->get();
        
        // Get property page sections based on selected property type
        $carouselSection = null;
        $perspectiveSection = null;
        $introSection = null;
        
        if ($selectedPropertyType) {
            // Load sections for the specific property type
            $carouselSection = $selectedPropertyType->carouselSection()->active()->first();
            $perspectiveSection = $selectedPropertyType->perspectiveSection()->active()->first();
            $introSection = $selectedPropertyType->introSection()->active()->first();
        } else {
            // Default: try to load residential sections first, then any available
            $residentialType = PropertyType::where('category', 'residential')->active()->first();
            if ($residentialType) {
                $carouselSection = $residentialType->carouselSection()->active()->first();
                $perspectiveSection = $residentialType->perspectiveSection()->active()->first();
                $introSection = $residentialType->introSection()->active()->first();
            }
            
            // Fallback to any available sections if residential not found
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
        
        return view('pages.properties', compact('properties', 'cities', 'locations', 'propertyTypes', 'bhks', 'projectStatuses', 'builders', 'workProcesses', 'carouselSection', 'perspectiveSection', 'introSection', 'selectedBuilder', 'selectedPropertyType'));
    }

    public function show(Property $property)
    {
        $property->incrementViews();
        $property->load(['propertyType', 'bhk', 'city', 'location', 'projectStatus', 'builder.amenities', 'builder.projectStatuses', 'images', 'amenities', 'specifications', 'faqs' => function ($query) {
            $query->active()->ordered();
        }]);
        $similarProperties = Property::with(['propertyType', 'bhk', 'city', 'mainImage'])->active()->published()->where('id', '!=', $property->id)->where('property_type_id', $property->property_type_id)->where('city_id', $property->city_id)->limit(3)->get();
        return view('pages.property-detail', compact('property', 'similarProperties'));
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
        return view('pages.blog-detail', compact('blog', 'relatedBlogs'));
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
