<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPageSection;
use App\Models\OurClient;
use App\Models\TeamMember;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        // Create About Page Section (single record with all content)
        AboutPageSection::updateOrCreate(
            ['id' => 1],
            [
                'section_title' => 'Our Company',
                'section_subtitle' => 'Building Trust, Delivering Excellence',
                
                // Our Company Section
                'who_we_are_title' => 'Who We Are',
                'who_we_are_description' => 'A premier real estate advisory specializing in strategic warehouse leasing, industrial land, factories, and 3PL company tie-ups, as well as premium residential plots, flats, commercial land, shops, and agricultural land across India.',
                'who_we_are_icon' => null,
                
                'mission_title' => 'Our Mission',
                'mission_description' => 'To deliver transparent, efficient, and customer-centric real estate solutions that empower our clients across industrial, commercial, residential, and agricultural sectors to make informed property decisions.',
                'mission_icon' => null,
                
                'vision_title' => 'Our Vision',
                'vision_description' => 'To be the most trusted and innovative real estate platform in India, setting new standards in property consultation across all sectors—from warehousing to residential homes.',
                'vision_icon' => null,
                
                // Our Values Section
                'values_heading' => 'Our Core Values',
                'values_who_we_are' => 'At our core, we believe that complete transparency and absolute honesty are the pillars of lasting partnerships. ZENDO Private Limited operates with a commitment to integrity that ensures our clients can navigate the complexities of both corporate and retail real estate markets with total confidence. Our reputation is built on the fact that we consistently prioritize our clients\' interests above all else, providing them with accurate, unbiased information and data-driven insights. By combining professional expertise with a personalized touch, we transform complex transactions—from industrial warehousing and 3PL integrations to residential homes, retail shops, and agricultural tracts—into a seamless, high-value experience for every stakeholder involved.',
                'values_mission' => 'We strive for excellence in every aspect of our service across all property types. From warehouse leasing and factory setups to residential plots and agricultural land, we ensure the highest quality standards are maintained throughout your journey.',
                'values_vision' => 'We leverage cutting-edge technology and innovative solutions to make property search and transactions easier, faster, and more efficient for our clients—whether they\'re looking for industrial space, retail shops, or premium residential properties.',
                'values_teamwork' => 'Our clients are at the heart of everything we do. We listen to your needs across all sectors—industrial warehousing, 3PL tie-ups, commercial land, residential plots, or agricultural properties—and work tirelessly to exceed your expectations.',
                
                // Team Section
                'team_section_title' => 'Our Team',
                'team_section_heading' => 'Meet Our Expert Team',
                
                'is_active' => true,
            ]
        );

        // Create Sample Clients
        OurClient::create([
            'name' => 'DLF Limited',
            'logo' => 'clients/dlf.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        OurClient::create([
            'name' => 'Godrej Properties',
            'logo' => 'clients/godrej.png',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        OurClient::create([
            'name' => 'Prestige Group',
            'logo' => 'clients/prestige.png',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        OurClient::create([
            'name' => 'Sobha Limited',
            'logo' => 'clients/sobha.png',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        OurClient::create([
            'name' => 'Brigade Group',
            'logo' => 'clients/brigade.png',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        OurClient::create([
            'name' => 'Lodha Group',
            'logo' => 'clients/lodha.png',
            'sort_order' => 6,
            'is_active' => true,
        ]);

        // Create Sample Team Members
        TeamMember::create([
            'name' => 'Rajesh Kumar',
            'position' => 'Founder & CEO',
            'photo' => 'team/rajesh.jpg',
            'linkedin_url' => 'https://linkedin.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Priya Sharma',
            'position' => 'Head of Sales',
            'photo' => 'team/priya.jpg',
            'linkedin_url' => 'https://linkedin.com',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Amit Patel',
            'position' => 'Senior Property Consultant',
            'photo' => 'team/amit.jpg',
            'linkedin_url' => 'https://linkedin.com',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        TeamMember::create([
            'name' => 'Sneha Reddy',
            'position' => 'Marketing Manager',
            'photo' => 'team/sneha.jpg',
            'linkedin_url' => 'https://linkedin.com',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $this->command->info('About page data seeded successfully!');
    }
}
