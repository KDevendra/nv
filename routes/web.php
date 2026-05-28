<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\AboutUsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CommercialSectionController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ProjectStatusController;
use App\Http\Controllers\Admin\BhkController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\BuilderController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\PropertyInquiryController;
use App\Http\Controllers\Admin\PropertyPageSectionController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\WorkProcessController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\OurClientController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ContactPageController;
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\TermsConditionController;
use App\Http\Controllers\Admin\SeoMetaController;
use App\Http\Controllers\Admin\VideoTourController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs.index');
Route::get('/blog/{blog:slug}', [HomeController::class, 'blogShow'])->name('blogs.show');
Route::get('/properties', [HomeController::class, 'properties'])->name('properties.index');
Route::get('/properties/{property:slug}', [HomeController::class, 'show'])->name('properties.show');
Route::get('/properties/search', [HomeController::class, 'search'])->name('properties.search');
Route::get('/api/bhks-by-property-type', [HomeController::class, 'getBhksByPropertyType'])->name('api.bhks-by-property-type');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
// Calculator Routes
Route::get('/calculators/acre-to-bigha', [HomeController::class, 'acreToBigha'])->name('calculators.acre-to-bigha');
Route::get('/calculators/acre-to-hectare', [HomeController::class, 'acreToHectare'])->name('calculators.acre-to-hectare');
Route::get('/calculators/emi-calculator', [HomeController::class, 'emiCalculator'])->name('calculators.emi-calculator');
Route::get('/calculators/length-calculator', [HomeController::class, 'lengthCalculator'])->name('calculators.length-calculator');
Route::get('/calculators/acre-to-squaremeter', [HomeController::class, 'acreToSquareMeter'])->name('calculators.acre-to-squaremeter');
Route::get('/calculators/cent-to-square-feet', [HomeController::class, 'centToSquareFeet'])->name('calculators.cent-to-square-feet');
Route::get('/calculators/cent-to-square-meter', [HomeController::class, 'centToSquareMeter'])->name('calculators.cent-to-square-meter');
Route::get('/calculators/cm-to-mm', [HomeController::class, 'cmToMm'])->name('calculators.cm-to-mm');
Route::get('/calculators/cm-to-inches', [HomeController::class, 'cmToInches'])->name('calculators.cm-to-inches');
Route::get('/calculators/ft-to-cm', [HomeController::class, 'ftToCm'])->name('calculators.ft-to-cm');
Route::get('/calculators/ft-to-inches', [HomeController::class, 'ftToInches'])->name('calculators.ft-to-inches');
Route::get('/calculators/ft-to-mm', [HomeController::class, 'ftToMm'])->name('calculators.ft-to-mm');

// Cache clearing routes
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache cleared successfully!';
});

Route::get('/key-generate', function () {
    Artisan::call('key:generate');
    return 'APP_KEY generated';
});

Route::get('/optimize', function() {
    Artisan::call('optimize:clear');
    return 'Optimization cache cleared!';
});

Route::get('/storage-link', function() {
    Artisan::call('storage:link');
    return 'Storage link created!';
});

// Public inquiry submission
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::post('/inquiries/check-submission', [InquiryController::class, 'checkSubmission'])->name('inquiries.checkSubmission');

// Public consultation submission
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['verified', 'permission'])->name('dashboard');
    
    // Dashboard Analytics API
    Route::get('/api/dashboard/visitor-analytics', [DashboardController::class, 'getVisitorAnalytics'])->middleware('permission')->name('dashboard.analytics');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('permission')->group(function () {
        Route::resource('users', UserController::class);

        // Role Permissions
        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::put('role-permissions/{role}', [RolePermissionController::class, 'update'])->name('role-permissions.update');
        Route::resource('testimonials', TestimonialController::class);
        Route::patch('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::resource('faqs', FaqController::class);
        Route::patch('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
        Route::resource('features', FeatureController::class);
        Route::patch('features/{feature}/toggle-status', [FeatureController::class, 'toggleStatus'])->name('features.toggle-status');
        Route::resource('about-us', AboutUsController::class)->parameters(['about-us' => 'aboutUs']);
        Route::patch('about-us/{aboutUs}/toggle-status', [AboutUsController::class, 'toggleStatus'])->name('about-us.toggle-status');
        Route::resource('categories', CategoryController::class);
        Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::resource('cities', CityController::class);
        Route::patch('cities/{city}/toggle-status', [CityController::class, 'toggleStatus'])->name('cities.toggle-status');
        
        // Commercial Sections
        Route::resource('commercial-sections', CommercialSectionController::class);
        Route::patch('commercial-sections/{commercialSection}/toggle-status', [CommercialSectionController::class, 'toggleStatus'])->name('commercial-sections.toggle-status');
        
        // Hero Sections
        Route::resource('hero-sections', HeroSectionController::class);
        Route::patch('hero-sections/{heroSection}/toggle-status', [HeroSectionController::class, 'toggleStatus'])->name('hero-sections.toggle-status');
        
        // Service Types
        Route::resource('service-types', ServiceTypeController::class);
        Route::patch('service-types/{serviceType}/toggle-status', [ServiceTypeController::class, 'toggleStatus'])->name('service-types.toggle-status');
        
        // Property Types
        Route::resource('property-types', PropertyTypeController::class);
        Route::patch('property-types/{propertyType}/toggle-status', [PropertyTypeController::class, 'toggleStatus'])->name('property-types.toggle-status');
        
        // Locations
        Route::resource('locations', LocationController::class);
        Route::patch('locations/{location}/toggle-status', [LocationController::class, 'toggleStatus'])->name('locations.toggle-status');
        
        // Project Statuses
        Route::resource('project-statuses', ProjectStatusController::class);
        Route::patch('project-statuses/{projectStatus}/toggle-status', [ProjectStatusController::class, 'toggleStatus'])->name('project-statuses.toggle-status');
        
        // BHKs
        Route::resource('bhks', BhkController::class);
        Route::patch('bhks/{bhk}/toggle-status', [BhkController::class, 'toggleStatus'])->name('bhks.toggle-status');
        
        // Builders
        Route::resource('builders', BuilderController::class);
        Route::patch('builders/{builder}/toggle-status', [BuilderController::class, 'toggleStatus'])->name('builders.toggle-status');
        Route::patch('builders/{builder}/toggle-verified', [BuilderController::class, 'toggleVerified'])->name('builders.toggle-verified');
        
        // Amenities
        Route::resource('amenities', AmenityController::class);
        Route::patch('amenities/{amenity}/toggle-status', [AmenityController::class, 'toggleStatus'])->name('amenities.toggle-status');
        
        // Properties
        Route::resource('properties', PropertyController::class);
        Route::patch('properties/{property}/toggle-status', [PropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
        Route::patch('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
        Route::patch('properties/{property}/toggle-verified', [PropertyController::class, 'toggleVerified'])->name('properties.toggle-verified');
        Route::delete('properties/images/{image}', [PropertyController::class, 'deleteImage'])->name('properties.delete-image');
        // Trash
        Route::get('properties-trash', [PropertyController::class, 'trash'])->name('properties.trash');
        Route::patch('properties-trash/{id}/restore', [PropertyController::class, 'restore'])->name('properties.restore');
        Route::delete('properties-trash/{id}/force-delete', [PropertyController::class, 'forceDelete'])->name('properties.force-delete');
        
        // Property Page Sections
        Route::resource('property-page-sections', PropertyPageSectionController::class);
        Route::patch('property-page-sections/{propertyPageSection}/toggle-status', [PropertyPageSectionController::class, 'toggleStatus'])->name('property-page-sections.toggle-status');
        Route::delete('property-page-sections/{propertyPageSection}/delete-image', [PropertyPageSectionController::class, 'deleteImage'])->name('property-page-sections.delete-image');
        
        // Property Inquiries
        Route::get('property-inquiries', [PropertyInquiryController::class, 'index'])->name('property-inquiries.index');
        Route::get('property-inquiries/{propertyInquiry}', [PropertyInquiryController::class, 'show'])->name('property-inquiries.show');
        Route::patch('property-inquiries/{propertyInquiry}/status', [PropertyInquiryController::class, 'updateStatus'])->name('property-inquiries.update-status');
        Route::delete('property-inquiries/{propertyInquiry}', [PropertyInquiryController::class, 'destroy'])->name('property-inquiries.destroy');
        
        // Inquiries management
        Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.update-status');
        Route::delete('inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
        
        // Consultations management
        Route::get('consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
        Route::get('consultations/{consultation}', [AdminConsultationController::class, 'show'])->name('consultations.show');
        Route::patch('consultations/{consultation}/status', [AdminConsultationController::class, 'updateStatus'])->name('consultations.update-status');
        Route::delete('consultations/{consultation}', [AdminConsultationController::class, 'destroy'])->name('consultations.destroy');
        
        // Work Processes (How We Work)
        Route::resource('work-processes', WorkProcessController::class);
        Route::patch('work-processes/{workProcess}/toggle-status', [WorkProcessController::class, 'toggleStatus'])->name('work-processes.toggle-status');
        
        // About Page Section
        Route::get('about-page', [AboutPageController::class, 'edit'])->name('about-page.edit');
        Route::put('about-page', [AboutPageController::class, 'update'])->name('about-page.update');
        
        // Our Clients
        Route::resource('our-clients', OurClientController::class);
        
        // Team Members
        Route::resource('team-members', TeamMemberController::class);
        
        // Contact Page Section
        Route::get('contact-page', [ContactPageController::class, 'edit'])->name('contact-page.edit');
        Route::put('contact-page', [ContactPageController::class, 'update'])->name('contact-page.update');
        
        // Contact Info
        Route::resource('contact-info', ContactInfoController::class);
        
        // Blog/News
        Route::resource('blogs', BlogController::class);
        Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload-image');
        
        // Privacy Policy (Single Page)
        Route::get('privacy-policy', [PrivacyPolicyController::class, 'edit'])->name('privacy-policy.edit');
        Route::put('privacy-policy', [PrivacyPolicyController::class, 'update'])->name('privacy-policy.update');

        // Terms & Conditions (Single Page)
        Route::get('terms-and-conditions', [TermsConditionController::class, 'edit'])->name('terms-and-conditions.edit');
        Route::put('terms-and-conditions', [TermsConditionController::class, 'update'])->name('terms-and-conditions.update');

        // SEO Meta
        Route::resource('seo-metas', SeoMetaController::class);

        // Video Tour Section
        Route::get('video-tour', [VideoTourController::class, 'edit'])->name('video-tour.edit');
        Route::put('video-tour', [VideoTourController::class, 'update'])->name('video-tour.update');
    });
});

require __DIR__.'/auth.php';
