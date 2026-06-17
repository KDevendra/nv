<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Map route name patterns to permission keys (module.action).
     * Checked in order — first match wins.
     */
    private const ROUTE_MAP = [
        // Dashboard
        'dashboard'                             => 'dashboard.view',

        // Role Permissions (super_admin only — handled by users.* permission)
        'admin.role-permissions.index'          => 'users.view',
        'admin.role-permissions.update'         => 'users.edit',

        // Users
        'admin.users.index'                     => 'users.view',
        'admin.users.show'                      => 'users.view',
        'admin.users.create'                    => 'users.create',
        'admin.users.store'                     => 'users.create',
        'admin.users.edit'                      => 'users.edit',
        'admin.users.update'                    => 'users.edit',
        'admin.users.destroy'                   => 'users.delete',

        // Properties
        'admin.properties.index'                => 'properties.view',
        'admin.properties.show'                 => 'properties.view',
        'admin.properties.create'               => 'properties.create',
        'admin.properties.store'                => 'properties.create',
        'admin.properties.edit'                 => 'properties.edit',
        'admin.properties.update'               => 'properties.edit',
        'admin.properties.destroy'              => 'properties.delete',
        'admin.properties.toggle-status'        => 'properties.edit',
        'admin.properties.toggle-featured'      => 'properties.edit',
        'admin.properties.toggle-verified'      => 'properties.edit',
        'admin.properties.delete-image'         => 'properties.edit',

        // Blogs
        'admin.blogs.index'                     => 'blogs.view',
        'admin.blogs.show'                      => 'blogs.view',
        'admin.blogs.create'                    => 'blogs.create',
        'admin.blogs.store'                     => 'blogs.create',
        'admin.blogs.edit'                      => 'blogs.edit',
        'admin.blogs.update'                    => 'blogs.edit',
        'admin.blogs.destroy'                   => 'blogs.delete',
        'admin.blogs.upload-image'              => 'blogs.edit',

        // Testimonials
        'admin.testimonials.index'              => 'testimonials.view',
        'admin.testimonials.show'               => 'testimonials.view',
        'admin.testimonials.create'             => 'testimonials.create',
        'admin.testimonials.store'              => 'testimonials.create',
        'admin.testimonials.edit'               => 'testimonials.edit',
        'admin.testimonials.update'             => 'testimonials.edit',
        'admin.testimonials.destroy'            => 'testimonials.delete',
        'admin.testimonials.toggle-status'      => 'testimonials.edit',

        // FAQs
        'admin.faqs.index'                      => 'faqs.view',
        'admin.faqs.show'                       => 'faqs.view',
        'admin.faqs.create'                     => 'faqs.create',
        'admin.faqs.store'                      => 'faqs.create',
        'admin.faqs.edit'                       => 'faqs.edit',
        'admin.faqs.update'                     => 'faqs.edit',
        'admin.faqs.destroy'                    => 'faqs.delete',
        'admin.faqs.toggle-status'              => 'faqs.edit',

        // Features
        'admin.features.index'                  => 'features.view',
        'admin.features.show'                   => 'features.view',
        'admin.features.create'                 => 'features.create',
        'admin.features.store'                  => 'features.create',
        'admin.features.edit'                   => 'features.edit',
        'admin.features.update'                 => 'features.edit',
        'admin.features.destroy'                => 'features.delete',
        'admin.features.toggle-status'          => 'features.edit',

        // About Us
        'admin.about-us.index'                  => 'about-us.view',
        'admin.about-us.show'                   => 'about-us.view',
        'admin.about-us.create'                 => 'about-us.create',
        'admin.about-us.store'                  => 'about-us.create',
        'admin.about-us.edit'                   => 'about-us.edit',
        'admin.about-us.update'                 => 'about-us.edit',
        'admin.about-us.destroy'                => 'about-us.delete',
        'admin.about-us.toggle-status'          => 'about-us.edit',

        // Categories
        'admin.categories.index'                => 'categories.view',
        'admin.categories.show'                 => 'categories.view',
        'admin.categories.create'               => 'categories.create',
        'admin.categories.store'                => 'categories.create',
        'admin.categories.edit'                 => 'categories.edit',
        'admin.categories.update'               => 'categories.edit',
        'admin.categories.destroy'              => 'categories.delete',
        'admin.categories.toggle-status'        => 'categories.edit',

        // Cities
        'admin.cities.index'                    => 'cities.view',
        'admin.cities.show'                     => 'cities.view',
        'admin.cities.create'                   => 'cities.create',
        'admin.cities.store'                    => 'cities.create',
        'admin.cities.edit'                     => 'cities.edit',
        'admin.cities.update'                   => 'cities.edit',
        'admin.cities.destroy'                  => 'cities.delete',
        'admin.cities.toggle-status'            => 'cities.edit',

        // Commercial Sections
        'admin.commercial-sections.index'       => 'commercial-sections.view',
        'admin.commercial-sections.show'        => 'commercial-sections.view',
        'admin.commercial-sections.create'      => 'commercial-sections.create',
        'admin.commercial-sections.store'       => 'commercial-sections.create',
        'admin.commercial-sections.edit'        => 'commercial-sections.edit',
        'admin.commercial-sections.update'      => 'commercial-sections.edit',
        'admin.commercial-sections.destroy'     => 'commercial-sections.delete',
        'admin.commercial-sections.toggle-status' => 'commercial-sections.edit',

        // Hero Sections
        'admin.hero-sections.index'             => 'hero-sections.view',
        'admin.hero-sections.show'              => 'hero-sections.view',
        'admin.hero-sections.create'            => 'hero-sections.create',
        'admin.hero-sections.store'             => 'hero-sections.create',
        'admin.hero-sections.edit'              => 'hero-sections.edit',
        'admin.hero-sections.update'            => 'hero-sections.edit',
        'admin.hero-sections.destroy'           => 'hero-sections.delete',
        'admin.hero-sections.toggle-status'     => 'hero-sections.edit',

        // Service Types
        'admin.service-types.index'             => 'service-types.view',
        'admin.service-types.show'              => 'service-types.view',
        'admin.service-types.create'            => 'service-types.create',
        'admin.service-types.store'             => 'service-types.create',
        'admin.service-types.edit'              => 'service-types.edit',
        'admin.service-types.update'            => 'service-types.edit',
        'admin.service-types.destroy'           => 'service-types.delete',
        'admin.service-types.toggle-status'     => 'service-types.edit',

        // Property Types
        'admin.property-types.index'            => 'property-types.view',
        'admin.property-types.show'             => 'property-types.view',
        'admin.property-types.create'           => 'property-types.create',
        'admin.property-types.store'            => 'property-types.create',
        'admin.property-types.edit'             => 'property-types.edit',
        'admin.property-types.update'           => 'property-types.edit',
        'admin.property-types.destroy'          => 'property-types.delete',
        'admin.property-types.toggle-status'    => 'property-types.edit',

        // Locations
        'admin.locations.index'                 => 'locations.view',
        'admin.locations.show'                  => 'locations.view',
        'admin.locations.create'                => 'locations.create',
        'admin.locations.store'                 => 'locations.create',
        'admin.locations.edit'                  => 'locations.edit',
        'admin.locations.update'                => 'locations.edit',
        'admin.locations.destroy'               => 'locations.delete',
        'admin.locations.toggle-status'         => 'locations.edit',

        // Project Statuses
        'admin.project-statuses.index'          => 'project-statuses.view',
        'admin.project-statuses.show'           => 'project-statuses.view',
        'admin.project-statuses.create'         => 'project-statuses.create',
        'admin.project-statuses.store'          => 'project-statuses.create',
        'admin.project-statuses.edit'           => 'project-statuses.edit',
        'admin.project-statuses.update'         => 'project-statuses.edit',
        'admin.project-statuses.destroy'        => 'project-statuses.delete',
        'admin.project-statuses.toggle-status'  => 'project-statuses.edit',

        // BHKs
        'admin.bhks.index'                      => 'bhks.view',
        'admin.bhks.show'                       => 'bhks.view',
        'admin.bhks.create'                     => 'bhks.create',
        'admin.bhks.store'                      => 'bhks.create',
        'admin.bhks.edit'                       => 'bhks.edit',
        'admin.bhks.update'                     => 'bhks.edit',
        'admin.bhks.destroy'                    => 'bhks.delete',
        'admin.bhks.toggle-status'              => 'bhks.edit',

        // Builders
        'admin.builders.index'                  => 'builders.view',
        'admin.builders.show'                   => 'builders.view',
        'admin.builders.create'                 => 'builders.create',
        'admin.builders.store'                  => 'builders.create',
        'admin.builders.edit'                   => 'builders.edit',
        'admin.builders.update'                 => 'builders.edit',
        'admin.builders.destroy'                => 'builders.delete',
        'admin.builders.toggle-status'          => 'builders.edit',
        'admin.builders.toggle-verified'        => 'builders.edit',

        // Amenities
        'admin.amenities.index'                 => 'amenities.view',
        'admin.amenities.show'                  => 'amenities.view',
        'admin.amenities.create'                => 'amenities.create',
        'admin.amenities.store'                 => 'amenities.create',
        'admin.amenities.edit'                  => 'amenities.edit',
        'admin.amenities.update'                => 'amenities.edit',
        'admin.amenities.destroy'               => 'amenities.delete',
        'admin.amenities.toggle-status'         => 'amenities.edit',

        // Property Page Sections
        'admin.property-page-sections.index'    => 'property-page-sections.view',
        'admin.property-page-sections.create'   => 'property-page-sections.create',
        'admin.property-page-sections.store'    => 'property-page-sections.create',
        'admin.property-page-sections.edit'     => 'property-page-sections.edit',
        'admin.property-page-sections.update'   => 'property-page-sections.edit',
        'admin.property-page-sections.destroy'  => 'property-page-sections.delete',
        'admin.property-page-sections.toggle-status'  => 'property-page-sections.edit',
        'admin.property-page-sections.delete-image'   => 'property-page-sections.edit',

        // Property Inquiries (read-only)
        'admin.property-inquiries.index'        => 'property-inquiries.view',
        'admin.property-inquiries.show'         => 'property-inquiries.view',
        'admin.property-inquiries.update-status'=> 'property-inquiries.view',
        'admin.property-inquiries.destroy'      => 'property-inquiries.view',

        // Inquiries (read-only)
        'admin.inquiries.index'                 => 'inquiries.view',
        'admin.inquiries.show'                  => 'inquiries.view',
        'admin.inquiries.update-status'         => 'inquiries.view',
        'admin.inquiries.destroy'               => 'inquiries.view',

        // Consultations (read-only)
        'admin.consultations.index'             => 'consultations.view',
        'admin.consultations.show'              => 'consultations.view',
        'admin.consultations.update-status'     => 'consultations.view',
        'admin.consultations.destroy'           => 'consultations.view',

        // Work Processes
        'admin.work-processes.index'            => 'work-processes.view',
        'admin.work-processes.show'             => 'work-processes.view',
        'admin.work-processes.create'           => 'work-processes.create',
        'admin.work-processes.store'            => 'work-processes.create',
        'admin.work-processes.edit'             => 'work-processes.edit',
        'admin.work-processes.update'           => 'work-processes.edit',
        'admin.work-processes.destroy'          => 'work-processes.delete',
        'admin.work-processes.toggle-status'    => 'work-processes.edit',

        // About Page (single page)
        'admin.about-page.edit'                 => 'about-page.view',
        'admin.about-page.update'               => 'about-page.edit',

        // Our Clients
        'admin.our-clients.index'               => 'our-clients.view',
        'admin.our-clients.create'              => 'our-clients.create',
        'admin.our-clients.store'               => 'our-clients.create',
        'admin.our-clients.edit'                => 'our-clients.edit',
        'admin.our-clients.update'              => 'our-clients.edit',
        'admin.our-clients.destroy'             => 'our-clients.delete',

        // Team Members
        'admin.team-members.index'              => 'team-members.view',
        'admin.team-members.create'             => 'team-members.create',
        'admin.team-members.store'              => 'team-members.create',
        'admin.team-members.edit'               => 'team-members.edit',
        'admin.team-members.update'             => 'team-members.edit',
        'admin.team-members.destroy'            => 'team-members.delete',

        // Contact Page (single page)
        'admin.contact-page.edit'               => 'contact-page.view',
        'admin.contact-page.update'             => 'contact-page.edit',

        // Contact Info
        'admin.contact-info.index'              => 'contact-info.view',
        'admin.contact-info.create'             => 'contact-info.create',
        'admin.contact-info.store'              => 'contact-info.create',
        'admin.contact-info.edit'               => 'contact-info.edit',
        'admin.contact-info.update'             => 'contact-info.edit',
        'admin.contact-info.destroy'            => 'contact-info.delete',

        // Privacy Policy (single page)
        'admin.privacy-policy.edit'             => 'privacy-policy.view',
        'admin.privacy-policy.update'           => 'privacy-policy.edit',

        // Terms & Conditions (single page)
        'admin.terms-and-conditions.edit'       => 'terms-and-conditions.view',
        'admin.terms-and-conditions.update'     => 'terms-and-conditions.edit',

        // Dashboard analytics API
        'dashboard.analytics'                   => 'dashboard.view',

        // Property Entry Report (admin)
        'admin.property-entry-report.index'     => 'dashboard.view',
        'admin.property-entry-report.export'    => 'dashboard.view',
        'admin.property-entry-report.show'      => 'dashboard.view',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Must be authenticated
        if (! $user) {
            return redirect()->route('login');
        }

        $routeName = $request->route()?->getName();

        // No route name — let it pass
        if (! $routeName || ! isset(self::ROUTE_MAP[$routeName])) {
            return $next($request);
        }

        $permission = self::ROUTE_MAP[$routeName];

        if (! $user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }

            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
