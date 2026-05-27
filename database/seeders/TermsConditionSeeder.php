<?php

namespace Database\Seeders;

use App\Models\TermsAndCondition;
use Illuminate\Database\Seeder;

class TermsConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TermsAndCondition::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Terms and Conditions',
                'content' => '<h2>Introduction</h2>
<p>Welcome to ZendoIndia. By accessing or using our website, you agree to be bound by these Terms and Conditions. Please read them carefully.</p>

<h2>Use of the Site</h2>
<p>By using our site, you represent that you are at least the age of majority in your state or province of residence, or that you are the age of majority in your state or province of residence and have given us your consent to allow any of your minor dependents to use this site.</p>

<h2>Intellectual Property</h2>
<p>The content, features, and functionality on this site are owned by ZendoIndia and are protected by international copyright, trademark, patent, trade secret, and other intellectual property or proprietary rights laws.</p>

<h2>User Conduct</h2>
<p>Users agree not to engage in any of the following prohibited activities:</p>
<ul>
    <li>Violating any applicable law or regulation</li>
    <li>Uploading viruses or malicious code</li>
    <li>Interfering with the operation of the site</li>
</ul>

<h2>Disclaimer of Warranties</h2>
<p>THE SITE IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT ANY WARRANTIES OF ANY KIND.</p>

<h2>Limitation of Liability</h2>
<p>IN NO EVENT SHALL ZENDOINDIA BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL OR PUNITIVE DAMAGES.</p>

<h2>Changes to Terms</h2>
<p>We may modify these Terms at any time. Changes will be posted on this page with an updated effective date.</p>

<h2>Contact Information</h2>
<p>If you have any questions about these Terms, please contact us at:</p>
<ul>
    <li>Email: info@zendoindia.com</li>
    <li>Phone: 74-94-01-01-01</li>
    <li>Address: Room No 1, Plot No 1, Ground Floor, Vatika Primrose Avenue, Sector-82, Gurugram, Haryana - 122012</li>
</ul>',
                'effective_date' => now(),
                'last_updated' => now()
            ]
        );
    }
}
