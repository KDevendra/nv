<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldDashboardController;
use App\Http\Controllers\FieldProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SalesExecutiveLeadController;
use App\Http\Controllers\ChiefCoordinatorLeadController;
use App\Http\Controllers\SupplyHeadLeadController;
use App\Http\Controllers\AdminLeadController;
use App\Http\Controllers\PublicSiteVisitController;
use App\Http\Controllers\FieldOfficer\PropertyEntryController as FieldOfficerPropertyEntryController;
use App\Http\Controllers\SupplyHead\PropertyEntryController as SupplyHeadPropertyEntryController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\User\UserDashboardController;
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
use App\Http\Controllers\Admin\AdvisoryPageController;
use App\Http\Controllers\Admin\OurClientController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ContactPageController;
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\TermsConditionController;
use App\Http\Controllers\Admin\SeoMetaController;
use App\Http\Controllers\Admin\VideoTourController;
use App\Http\Controllers\Admin\PropertyEntryReportController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\ZoneController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache cleared successfully!';
});
Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    return 'Optimization cache cleared!';
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/advisory-services', [HomeController::class, 'advisoryServices'])->name('advisory.services');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs.index');
Route::get('/blog/{blog:slug}', [HomeController::class, 'blogShow'])->name('blogs.show');
Route::get('/properties', [HomeController::class, 'properties'])->name('properties.index');
Route::get('/properties/{property:slug}', [HomeController::class, 'show'])->name('properties.show');
Route::get('/property-entries/{entry:code}', [HomeController::class, 'showEntry'])->name('property-entries.show');
Route::get('/properties/search', [HomeController::class, 'search'])->name('properties.search');
Route::get('/api/bhks-by-property-type', [HomeController::class, 'getBhksByPropertyType'])->name('api.bhks-by-property-type');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::post('/inquiries', [InquiryController::class, 'storePropertyInquiry'])->name('inquiries.store');
Route::post('/inquiries/check-submission', [InquiryController::class, 'checkSubmission'])->name('inquiries.checkSubmission');
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');

// ── Public single-use site-visit link (no auth required — token is the key) ──
Route::get('/site-visit/{token}', [PublicSiteVisitController::class, 'show'])->name('site-visit.show')->name('leads.visit_link');
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


Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['verified', 'permission'])->name('dashboard');
    Route::prefix('user')->name('user.')->middleware('verified')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/inquiries', [UserDashboardController::class, 'inquiries'])->name('inquiries');
        Route::get('/inquiries/{inquiry}', [UserDashboardController::class, 'showInquiry'])->name('inquiries.show');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
        Route::post('/wishlist/toggle', [UserDashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');
    });
    Route::get('/api/dashboard/visitor-analytics', [DashboardController::class, 'getVisitorAnalytics'])->middleware('permission')->name('dashboard.analytics');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/field/dashboard', [FieldDashboardController::class, 'index'])->name('field.dashboard');
    Route::get('/channel-partner/dashboard', [\App\Http\Controllers\ChannelPartner\DashboardController::class, 'index'])->name('channel_partner.dashboard');
    Route::get('/owner/dashboard', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/field/profile', [FieldProfileController::class, 'edit'])->name('field.profile.edit');
    Route::patch('/field/profile', [FieldProfileController::class, 'update'])->name('field.profile.update');
    Route::put('/field/profile/password', [FieldProfileController::class, 'updatePassword'])->name('field.profile.password');
    Route::prefix('field')->name('field.')->group(function () {
        Route::resource('properties', FieldOfficerPropertyEntryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        Route::get('location/reverse-geocode', [FieldOfficerPropertyEntryController::class, 'reverseGeocode'])->name('location.reverse-geocode');
    });
    Route::prefix('owner')->name('owner.')->group(function () {
        Route::resource('properties', \App\Http\Controllers\Owner\PropertyEntryController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        Route::get('location/reverse-geocode', [\App\Http\Controllers\Owner\PropertyEntryController::class, 'reverseGeocode'])->name('location.reverse-geocode');
    });
    Route::prefix('supply-head')->name('supplyhead.')->group(function () {
        Route::get('properties', [SupplyHeadPropertyEntryController::class, 'index'])->name('properties.index');
        Route::get('properties/create', [SupplyHeadPropertyEntryController::class, 'create'])->name('properties.create');
        Route::post('properties', [SupplyHeadPropertyEntryController::class, 'store'])->name('properties.store');
        Route::get('location/reverse-geocode', [SupplyHeadPropertyEntryController::class, 'reverseGeocode'])->name('location.reverse-geocode');
        Route::get('properties/{property}/edit', [SupplyHeadPropertyEntryController::class, 'edit'])->name('properties.edit');
        Route::put('properties/{property}', [SupplyHeadPropertyEntryController::class, 'update'])->name('properties.update');
        Route::get('properties/{property}', [SupplyHeadPropertyEntryController::class, 'show'])->name('properties.show');
        Route::post('properties/{property}/action', [SupplyHeadPropertyEntryController::class, 'action'])->name('properties.action');
        Route::post('properties/{property}/toggle-resubmit', [SupplyHeadPropertyEntryController::class, 'toggleResubmit'])->name('properties.toggle-resubmit');
        Route::post('properties/{property}/review-field', [SupplyHeadPropertyEntryController::class, 'reviewField'])->name('properties.review-field');
        Route::post('properties/{property}/mark-all-correct', [SupplyHeadPropertyEntryController::class, 'markAllCorrect'])->name('properties.mark-all-correct');
        Route::post('properties/{property}/mark-all-incorrect', [SupplyHeadPropertyEntryController::class, 'markAllIncorrect'])->name('properties.mark-all-incorrect');
        Route::post('properties/{property}/undo-all-correct', [SupplyHeadPropertyEntryController::class, 'undoAllCorrect'])->name('properties.undo-all-correct');
    });
    Route::prefix('admin')->name('admin.')->middleware('permission')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
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
        Route::resource('commercial-sections', CommercialSectionController::class);
        Route::patch('commercial-sections/{commercialSection}/toggle-status', [CommercialSectionController::class, 'toggleStatus'])->name('commercial-sections.toggle-status');
        Route::resource('hero-sections', HeroSectionController::class);
        Route::patch('hero-sections/{heroSection}/toggle-status', [HeroSectionController::class, 'toggleStatus'])->name('hero-sections.toggle-status');
        Route::resource('service-types', ServiceTypeController::class);
        Route::patch('service-types/{serviceType}/toggle-status', [ServiceTypeController::class, 'toggleStatus'])->name('service-types.toggle-status');
        Route::resource('property-types', PropertyTypeController::class);
        Route::patch('property-types/{propertyType}/toggle-status', [PropertyTypeController::class, 'toggleStatus'])->name('property-types.toggle-status');
        Route::resource('locations', LocationController::class);
        Route::patch('locations/{location}/toggle-status', [LocationController::class, 'toggleStatus'])->name('locations.toggle-status');
        Route::resource('project-statuses', ProjectStatusController::class);
        Route::patch('project-statuses/{projectStatus}/toggle-status', [ProjectStatusController::class, 'toggleStatus'])->name('project-statuses.toggle-status');
        Route::resource('bhks', BhkController::class);
        Route::patch('bhks/{bhk}/toggle-status', [BhkController::class, 'toggleStatus'])->name('bhks.toggle-status');
        Route::resource('builders', BuilderController::class);
        Route::patch('builders/{builder}/toggle-status', [BuilderController::class, 'toggleStatus'])->name('builders.toggle-status');
        Route::patch('builders/{builder}/toggle-verified', [BuilderController::class, 'toggleVerified'])->name('builders.toggle-verified');
        Route::resource('amenities', AmenityController::class);
        Route::patch('amenities/{amenity}/toggle-status', [AmenityController::class, 'toggleStatus'])->name('amenities.toggle-status');
        Route::resource('properties', PropertyController::class);
        Route::patch('properties/{property}/toggle-status', [PropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
        Route::patch('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
        Route::patch('properties/{property}/toggle-verified', [PropertyController::class, 'toggleVerified'])->name('properties.toggle-verified');
        Route::delete('properties/images/{image}', [PropertyController::class, 'deleteImage'])->name('properties.delete-image');
        Route::get('properties-trash', [PropertyController::class, 'trash'])->name('properties.trash');
        Route::patch('properties-trash/{id}/restore', [PropertyController::class, 'restore'])->name('properties.restore');
        Route::delete('properties-trash/{id}/force-delete', [PropertyController::class, 'forceDelete'])->name('properties.force-delete');
        Route::resource('property-page-sections', PropertyPageSectionController::class);
        Route::patch('property-page-sections/{propertyPageSection}/toggle-status', [PropertyPageSectionController::class, 'toggleStatus'])->name('property-page-sections.toggle-status');
        Route::delete('property-page-sections/{propertyPageSection}/delete-image', [PropertyPageSectionController::class, 'deleteImage'])->name('property-page-sections.delete-image');
        Route::get('property-inquiries', [PropertyInquiryController::class, 'index'])->name('property-inquiries.index');
        Route::get('property-inquiries/{propertyInquiry}', [PropertyInquiryController::class, 'show'])->name('property-inquiries.show');
        Route::patch('property-inquiries/{propertyInquiry}/status', [PropertyInquiryController::class, 'updateStatus'])->name('property-inquiries.update-status');
        Route::delete('property-inquiries/{propertyInquiry}', [PropertyInquiryController::class, 'destroy'])->name('property-inquiries.destroy');
        Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.update-status');
        Route::delete('inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
        Route::get('consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
        Route::get('consultations/{consultation}', [AdminConsultationController::class, 'show'])->name('consultations.show');
        Route::patch('consultations/{consultation}/status', [AdminConsultationController::class, 'updateStatus'])->name('consultations.update-status');
        Route::delete('consultations/{consultation}', [AdminConsultationController::class, 'destroy'])->name('consultations.destroy');
        Route::resource('work-processes', WorkProcessController::class);
        Route::patch('work-processes/{workProcess}/toggle-status', [WorkProcessController::class, 'toggleStatus'])->name('work-processes.toggle-status');
        Route::get('about-page', [AboutPageController::class, 'edit'])->name('about-page.edit');
        Route::put('about-page', [AboutPageController::class, 'update'])->name('about-page.update');
        Route::get('advisory-page', [AdvisoryPageController::class, 'edit'])->name('advisory-page.edit');
        Route::put('advisory-page', [AdvisoryPageController::class, 'update'])->name('advisory-page.update');
        Route::resource('our-clients', OurClientController::class);
        Route::resource('team-members', TeamMemberController::class);
        Route::get('contact-page', [ContactPageController::class, 'edit'])->name('contact-page.edit');
        Route::put('contact-page', [ContactPageController::class, 'update'])->name('contact-page.update');
        Route::resource('contact-info', ContactInfoController::class);
        Route::resource('blogs', BlogController::class);
        Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload-image');
        Route::get('privacy-policy', [PrivacyPolicyController::class, 'edit'])->name('privacy-policy.edit');
        Route::put('privacy-policy', [PrivacyPolicyController::class, 'update'])->name('privacy-policy.update');
        Route::get('terms-and-conditions', [TermsConditionController::class, 'edit'])->name('terms-and-conditions.edit');
        Route::put('terms-and-conditions', [TermsConditionController::class, 'update'])->name('terms-and-conditions.update');
        Route::resource('seo-metas', SeoMetaController::class);
        Route::get('video-tour', [VideoTourController::class, 'edit'])->name('video-tour.edit');
        Route::put('video-tour', [VideoTourController::class, 'update'])->name('video-tour.update');
        Route::get('property-entry-report', [PropertyEntryReportController::class, 'index'])->name('property-entry-report.index');
        Route::get('property-entry-report/export', [PropertyEntryReportController::class, 'export'])->name('property-entry-report.export');
        Route::get('property-entry-report/{entry}', [PropertyEntryReportController::class, 'show'])->name('property-entry-report.show');
        Route::post('property-entry-report/{entry}/toggle-website', [PropertyEntryReportController::class, 'toggleWebsite'])->name('property-entry-report.toggle-website');
        Route::post('property-entry-report/{entry}/admin-approve', [PropertyEntryReportController::class, 'adminApprove'])->name('property-entry-report.admin-approve');
        Route::post('property-entry-report/{entry}/admin-reject', [PropertyEntryReportController::class, 'adminReject'])->name('property-entry-report.admin-reject');
        Route::get('wishlist-report', [\App\Http\Controllers\Admin\WishlistReportController::class, 'index'])->name('wishlist-report.index');
        Route::resource('regions', RegionController::class);
        Route::patch('regions/{region}/toggle-status', [RegionController::class, 'toggleStatus'])->name('regions.toggle-status');
        Route::resource('areas', AreaController::class);
        Route::patch('areas/{area}/toggle-status', [AreaController::class, 'toggleStatus'])->name('areas.toggle-status');
        Route::get('areas-by-region', [AreaController::class, 'getByRegion'])->name('areas.by-region');
        Route::resource('zones', ZoneController::class)->except(['show']);
        Route::patch('zones/{zone}/toggle-status', [ZoneController::class, 'toggleStatus'])->name('zones.toggle-status');
    });

    // CSRF Token refresh route for long forms
    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });

    // ── Sales Executive Lead Pipeline (Panel 1) ───────────────────────────
    Route::prefix('se')->name('se.')->group(function () {
        Route::get('leads', [SalesExecutiveLeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [SalesExecutiveLeadController::class, 'show'])->name('leads.show');
        Route::patch('leads/{lead}', [SalesExecutiveLeadController::class, 'update'])->name('leads.update');
        Route::post('leads/{lead}/log-contact', [SalesExecutiveLeadController::class, 'logContact'])->name('leads.log-contact');
        Route::post('leads/{lead}/qualify', [SalesExecutiveLeadController::class, 'qualify'])->name('leads.qualify');
        Route::post('leads/{lead}/share-options', [SalesExecutiveLeadController::class, 'shareOptions'])->name('leads.share-options');
        Route::post('leads/{lead}/confirm-interest', [SalesExecutiveLeadController::class, 'confirmInterest'])->name('leads.confirm-interest');
        Route::post('leads/{lead}/handover', [SalesExecutiveLeadController::class, 'handover'])->name('leads.handover');
        Route::post('leads/{lead}/hold', [SalesExecutiveLeadController::class, 'hold'])->name('leads.hold');
        Route::post('leads/{lead}/resume', [SalesExecutiveLeadController::class, 'resume'])->name('leads.resume');
        Route::post('leads/{lead}/defer', [SalesExecutiveLeadController::class, 'defer'])->name('leads.defer');
        Route::post('leads/{lead}/lost', [SalesExecutiveLeadController::class, 'markLost'])->name('leads.lost');
    });

    // ── Chief Coordinator Lead Pipeline (Panel 2) ─────────────────────────
    Route::prefix('cc')->name('cc.')->group(function () {
        Route::get('leads', [ChiefCoordinatorLeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [ChiefCoordinatorLeadController::class, 'show'])->name('leads.show');
        Route::post('leads/{lead}/request-feasibility', [ChiefCoordinatorLeadController::class, 'requestFeasibility'])->name('leads.request-feasibility');
        Route::post('leads/{lead}/generate-site-visit-link', [ChiefCoordinatorLeadController::class, 'generateSiteVisitLink'])->name('leads.generate-site-visit-link');
        Route::post('leads/{lead}/site-visit-feedback', [ChiefCoordinatorLeadController::class, 'siteVisitFeedback'])->name('leads.site-visit-feedback');
        Route::post('leads/{lead}/negotiate', [ChiefCoordinatorLeadController::class, 'negotiate'])->name('leads.negotiate');
        Route::post('leads/{lead}/close-deal', [ChiefCoordinatorLeadController::class, 'closeDeal'])->name('leads.close-deal');
        Route::post('leads/{lead}/hold', [ChiefCoordinatorLeadController::class, 'hold'])->name('leads.hold');
        Route::post('leads/{lead}/resume', [ChiefCoordinatorLeadController::class, 'resume'])->name('leads.resume');
        Route::post('leads/{lead}/defer', [ChiefCoordinatorLeadController::class, 'defer'])->name('leads.defer');
        Route::post('leads/{lead}/lost', [ChiefCoordinatorLeadController::class, 'markLost'])->name('leads.lost');
    });

    // ── Supply Head Feasibility Relay (Panel 3) ───────────────────────────
    Route::prefix('sh')->name('sh.')->group(function () {
        Route::get('leads', [SupplyHeadLeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [SupplyHeadLeadController::class, 'show'])->name('leads.show');
        Route::post('leads/{lead}/respond', [SupplyHeadLeadController::class, 'respond'])->name('leads.respond');
    });

    // ── Admin Lead Management (cross-division) ────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('permission')->group(function () {
        Route::get('leads', [AdminLeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [AdminLeadController::class, 'show'])->name('leads.show');
        Route::post('leads/{lead}/assign-cc', [AdminLeadController::class, 'assignCC'])->name('leads.assign-cc');
        Route::post('leads/{lead}/assign-se', [AdminLeadController::class, 'assignSE'])->name('leads.assign-se');
        Route::post('leads/{lead}/override-stage', [AdminLeadController::class, 'overrideStage'])->name('leads.override-stage');
        Route::post('leads/{lead}/resolve-division', [AdminLeadController::class, 'resolveDivision'])->name('leads.resolve-division');
        Route::post('leads/{lead}/hold', [AdminLeadController::class, 'hold'])->name('leads.hold');
        Route::post('leads/{lead}/resume', [AdminLeadController::class, 'resume'])->name('leads.resume');
        Route::post('leads/{lead}/lost', [AdminLeadController::class, 'markLost'])->name('leads.lost');
        Route::delete('leads/{lead}', [AdminLeadController::class, 'destroy'])->name('leads.destroy');
    });
});

// CSRF Token refresh route for long forms
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('auth');

// Public Expiring Site Visit Link (single-use, 24h)
Route::get('/site-visit/{token}', [\App\Http\Controllers\PublicSiteVisitController::class, 'show'])->name('site_visit.show');
Route::get('/site-visit/{token}', [\App\Http\Controllers\PublicSiteVisitController::class, 'show'])->name('leads.visit_link');

require __DIR__ . '/auth.php';
