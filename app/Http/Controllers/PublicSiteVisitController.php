<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

/**
 * Public single-use site-visit address reveal.
 *
 * Route : GET /site-visit/{token}
 * Auth  : None — the token IS the auth mechanism.
 *
 * On first access:
 *  - Validates token expiry (24h) and unopened status.
 *  - Renders the property address + location details.
 *  - Immediately marks the token as opened (consumed / single-use).
 *
 * Subsequent access returns a 410 Gone with a clear message.
 */
class PublicSiteVisitController extends Controller
{
    /**
     * GET /site-visit/{token}
     */
    public function show(string $token)
    {
        /** @var Lead|null $lead */
        $lead = Lead::where('site_visit_token', $token)->first();

        // Token not found at all
        if (!$lead) {
            abort(404, 'This site-visit link is not valid.');
        }

        // Already consumed
        if ($lead->site_visit_token_opened_at !== null) {
            return response()->view('site_visit.show', [
                'expired'   => true,
                'reason'    => 'This link has already been opened. For security, site-visit links are single-use only.',
                'lead'      => null,
                'property'  => null,
            ], 410);
        }

        // Expired (24h window passed)
        if (!$lead->isSiteVisitTokenValid()) {
            return response()->view('site_visit.show', [
                'expired'   => true,
                'reason'    => 'This site-visit link has expired (valid for 24 hours). Please request a new link.',
                'lead'      => null,
                'property'  => null,
            ], 410);
        }

        // Valid — consume the token immediately before rendering
        $lead->consumeSiteVisitToken();

        // Load the property with address details
        $lead->load('property.city', 'property.location', 'property.propertyType');
        $property = $lead->property;

        return view('site_visit.show', [
            'expired'  => false,
            'reason'   => null,
            'lead'     => $lead,
            'property' => $property,
        ]);
    }
}
