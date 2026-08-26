<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryPageSection;
use Illuminate\Http\Request;

class AdvisoryPageController extends Controller
{
    public function edit()
    {
        $advisoryPage = AdvisoryPageSection::firstOrCreate(
            ['id' => 1],
            [
                'page_title' => 'ZENDO Advisory Services - Warehouse Decisions Made Smarter | ZendoIndia',
                'hero_eyebrow' => 'ZENDO Advisory Services',
                'hero_title' => 'Enter Any Market. <span>Expand With Confidence.</span>',
                'hero_description' => 'ZENDO advises businesses entering or expanding across Indian markets — delivering the right space, logistics, feasibility, and a fully costed plan through one dedicated team.',
                'hero_note' => '— Advisory across India\'s key industrial markets.',
                'hero_btn1_text' => 'Explore Our Services',
                'hero_btn1_link' => '#services',
                'hero_btn2_text' => 'Speak to an Advisor',
                'hero_btn2_link' => '#contact',
                'services_eyebrow' => 'What We Offer',
                'services_title' => 'Two Advisory Tracks. One Growth Partner.',
                'services_description' => 'Whether you\'re setting up in a new market or optimising an existing operation, ZENDO has an advisory built for you.',
                'track1_title' => 'ZENDO Select',
                'track1_tagline' => 'Plan Right. Enter Right.',
                'track1_description' => 'For businesses entering new markets or expanding into new locations.',
                'track1_benefits' => [
                    'Market & location selection guidance',
                    'Space sourcing across warehousing, commercial & industrial',
                    'In-house vs 3PL logistics modelling with break-even analysis',
                    'Feasibility read + fully costed setup and monthly plan',
                    'State incentives & compliance guidance',
                ],
                'track2_title' => 'ZENDO Upgrade',
                'track2_tagline' => 'Optimise Space. Enhance Performance.',
                'track2_description' => 'For businesses with existing operations looking to improve performance and cost.',
                'track2_benefits' => [
                    'Space utilisation & layout efficiency review',
                    'Measurable leasing cost reduction',
                    'Rent benchmarking against live market data',
                    'Renewal & renegotiation advisory',
                ],
                'why_eyebrow' => 'Why Choose ZENDO?',
                'why_title' => 'Advisory You Can Rely On',
                'why_items' => [
                    [
                        'title' => 'Independent Advice',
                        'description' => 'Unbiased guidance that puts your business first, never the landlord\'s.',
                    ],
                    [
                        'title' => 'One Dedicated Team',
                        'description' => 'A single point of contact directs every resource behind the scenes, private-client style.',
                    ],
                    [
                        'title' => 'Practical Industry Experience',
                        'description' => 'Real, on-ground warehousing expertise across India\'s key markets.',
                    ],
                    [
                        'title' => 'Faster Decision-Making',
                        'description' => 'Clear, data-backed recommendations so you move quickly and confidently.',
                    ],
                ],
                'cta_eyebrow' => 'Get Started',
                'cta_title' => 'Speak to a ZENDO Advisor',
                'cta_phone_text' => 'Call: 7494-01-01-01',
                'cta_phone_link' => 'tel:+917494010101',
                'cta_note' => 'Our advisors are available Monday to Saturday, 9 AM – 7 PM.',
                'cta_btn1_text' => 'Call Now',
                'cta_btn1_link' => 'tel:+917494010101',
                'cta_btn2_text' => 'Email Us',
                'cta_btn2_link' => 'mailto:info@zendoindia.com',
                'footnote_text' => 'A premium advisory service by ZendoIndia · Independent Advice · Market Intelligence · End-to-End Support',
                'is_active' => true,
            ]
        );

        return view('admin.advisory-page.edit', compact('advisoryPage'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'page_title' => 'nullable|string|max:255',
            'hero_eyebrow' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'hero_note' => 'nullable|string|max:255',
            'hero_btn1_text' => 'nullable|string|max:255',
            'hero_btn1_link' => 'nullable|string|max:255',
            'hero_btn2_text' => 'nullable|string|max:255',
            'hero_btn2_link' => 'nullable|string|max:255',
            'services_eyebrow' => 'nullable|string|max:255',
            'services_title' => 'nullable|string|max:255',
            'services_description' => 'nullable|string',
            'track1_title' => 'nullable|string|max:255',
            'track1_tagline' => 'nullable|string|max:255',
            'track1_description' => 'nullable|string',
            'track1_benefits' => 'nullable|array',
            'track1_benefits.*' => 'nullable|string',
            'track2_title' => 'nullable|string|max:255',
            'track2_tagline' => 'nullable|string|max:255',
            'track2_description' => 'nullable|string',
            'track2_benefits' => 'nullable|array',
            'track2_benefits.*' => 'nullable|string',
            'why_eyebrow' => 'nullable|string|max:255',
            'why_title' => 'nullable|string|max:255',
            'why_items' => 'nullable|array',
            'why_items.*.title' => 'nullable|string|max:255',
            'why_items.*.description' => 'nullable|string',
            'cta_eyebrow' => 'nullable|string|max:255',
            'cta_title' => 'nullable|string|max:255',
            'cta_phone_text' => 'nullable|string|max:255',
            'cta_phone_link' => 'nullable|string|max:255',
            'cta_note' => 'nullable|string|max:255',
            'cta_btn1_text' => 'nullable|string|max:255',
            'cta_btn1_link' => 'nullable|string|max:255',
            'cta_btn2_text' => 'nullable|string|max:255',
            'cta_btn2_link' => 'nullable|string|max:255',
            'footnote_text' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Filter out empty benefits and why items
        if (isset($validated['track1_benefits'])) {
            $validated['track1_benefits'] = array_values(array_filter($validated['track1_benefits'], fn($item) => !is_null($item) && trim($item) !== ''));
        } else {
            $validated['track1_benefits'] = [];
        }

        if (isset($validated['track2_benefits'])) {
            $validated['track2_benefits'] = array_values(array_filter($validated['track2_benefits'], fn($item) => !is_null($item) && trim($item) !== ''));
        } else {
            $validated['track2_benefits'] = [];
        }

        if (isset($validated['why_items'])) {
            $validated['why_items'] = array_values(array_filter($validated['why_items'], function ($item) {
                return !empty($item['title']) || !empty($item['description']);
            }));
        } else {
            $validated['why_items'] = [];
        }

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        $advisoryPage = AdvisoryPageSection::firstOrNew(['id' => 1]);
        $advisoryPage->fill($validated);
        $advisoryPage->save();

        return redirect()->route('admin.advisory-page.edit')
            ->with('success', 'Advisory page updated successfully!');
    }
}
