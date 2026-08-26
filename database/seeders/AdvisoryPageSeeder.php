<?php

namespace Database\Seeders;

use App\Models\AdvisoryPageSection;
use Illuminate\Database\Seeder;

class AdvisoryPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdvisoryPageSection::updateOrCreate(
            ['id' => 1],
            [
                'page_title' => 'ZENDO Advisory Services - Warehouse Decisions Made Smarter | ZendoIndia',

                // Hero Section
                'hero_eyebrow' => 'ZENDO Advisory Services',
                'hero_title' => 'Enter Any Market. <span>Expand With Confidence.</span>',
                'hero_description' => 'ZENDO advises businesses entering or expanding across Indian markets — delivering the right space, logistics, feasibility, and a fully costed plan through one dedicated team.',
                'hero_note' => '— Advisory across India\'s key industrial markets.',
                'hero_btn1_text' => 'Explore Our Services',
                'hero_btn1_link' => '#services',
                'hero_btn2_text' => 'Speak to an Advisor',
                'hero_btn2_link' => '#contact',

                // Services Section
                'services_eyebrow' => 'What We Offer',
                'services_title' => 'Two Advisory Tracks. One Growth Partner.',
                'services_description' => 'Whether you\'re setting up in a new market or optimising an existing operation, ZENDO has an advisory built for you.',

                // ZENDO Select Track
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

                // ZENDO Upgrade Track
                'track2_title' => 'ZENDO Upgrade',
                'track2_tagline' => 'Optimise Space. Enhance Performance.',
                'track2_description' => 'For businesses with existing operations looking to improve performance and cost.',
                'track2_benefits' => [
                    'Space utilisation & layout efficiency review',
                    'Measurable leasing cost reduction',
                    'Rent benchmarking against live market data',
                    'Renewal & renegotiation advisory',
                ],

                // Why Choose Section
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

                // Final CTA Section
                'cta_eyebrow' => 'Get Started',
                'cta_title' => 'Speak to a ZENDO Advisor',
                'cta_phone_text' => 'Call: 7494-01-01-01',
                'cta_phone_link' => 'tel:+917494010101',
                'cta_note' => 'Our advisors are available Monday to Saturday, 9 AM – 7 PM.',
                'cta_btn1_text' => 'Call Now',
                'cta_btn1_link' => 'tel:+917494010101',
                'cta_btn2_text' => 'Email Us',
                'cta_btn2_link' => 'mailto:info@zendoindia.com',

                // Footnote
                'footnote_text' => 'A premium advisory service by ZendoIndia · Independent Advice · Market Intelligence · End-to-End Support',

                'is_active' => true,
            ]
        );

        if (isset($this->command)) {
            $this->command->info('Advisory page data seeded successfully!');
        }
    }
}
