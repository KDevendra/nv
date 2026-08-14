<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed all the content
        $this->call([
            PermissionSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            FeatureSeeder::class,
            AboutUsSeeder::class,
            CategorySeeder::class,
            CitySeeder::class,
            InquirySeeder::class,
            ConsultationSeeder::class,
            HeroSectionSeeder::class,
            ServiceTypeSeeder::class,
            PropertyTypeSeeder::class,
            AboutPageSeeder::class,
            ContactPageSeeder::class,
            TermsConditionSeeder::class,
            PropertyPageSectionSeeder::class,
            AdvisoryPageSeeder::class,
        ]);
    }
}
