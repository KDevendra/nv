<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class PublicSiteVisitController extends Controller
{
    /**
     * Display site visit address for a valid single-use 24h token.
     * Consumes the token immediately on open.
     */
    public function show(string $token)
    {
        $lead = Lead::where('visit_link_token', $token)->first();

        if (!$lead) {
            return view('site_visit.show', [
                'expired' => true,
                'reason'  => 'Invalid site visit link. Please contact your coordinator for assistance.'
            ]);
        }

        if ($lead->visit_link_opened_at !== null) {
            return view('site_visit.show', [
                'expired' => true,
                'reason'  => 'This site visit link has already been opened and was valid for single-use only.'
            ]);
        }

        if ($lead->visit_link_expires_at === null || $lead->visit_link_expires_at->isPast()) {
            return view('site_visit.show', [
                'expired' => true,
                'reason'  => 'This site visit link expired after 24 hours. Please request a new link from your coordinator.'
            ]);
        }

        // Immediately consume token (single-use)
        $lead->consumeVisitLinkToken();

        $property = $lead->property;
        $propertyEntries = collect();

        if ($lead->options_shared_property_ids && is_array($lead->options_shared_property_ids)) {
            $codes = $lead->options_shared_property_ids;
            $propertyEntries = \App\Models\PropertyEntry::whereIn('code', $codes)->get();
        }

        if (!$property && $propertyEntries->isEmpty()) {
            $firstPe = \App\Models\PropertyEntry::first();
            if ($firstPe) {
                $propertyEntries = collect([$firstPe]);
            }
        }

        return view('site_visit.show', [
            'expired'         => false,
            'lead'            => $lead,
            'property'        => $property,
            'propertyEntries' => $propertyEntries,
        ]);
    }
}
