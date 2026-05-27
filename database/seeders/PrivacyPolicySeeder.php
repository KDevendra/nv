<?php

namespace Database\Seeders;

use App\Models\PrivacyPolicy;
use Illuminate\Database\Seeder;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrivacyPolicy::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Privacy Policy',
                'content' => '<h2>Introduction</h2>
<p>Welcome to ZendoIndia. We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>

<h2>Information We Collect</h2>
<p>We collect information that you provide directly to us when you:</p>
<ul>
    <li>Register for an account</li>
    <li>Submit an inquiry or consultation request</li>
    <li>Subscribe to our newsletter</li>
    <li>Contact us through our website</li>
    <li>Browse our property listings</li>
</ul>

<h3>Personal Information</h3>
<p>The personal information we may collect includes:</p>
<ul>
    <li>Name and contact information (email address, phone number)</li>
    <li>Property preferences and search history</li>
    <li>Communication preferences</li>
    <li>Any other information you choose to provide</li>
</ul>

<h2>How We Use Your Information</h2>
<p>We use the information we collect to:</p>
<ul>
    <li>Provide, maintain, and improve our services</li>
    <li>Process your inquiries and respond to your requests</li>
    <li>Send you property listings that match your preferences</li>
    <li>Communicate with you about our services</li>
    <li>Analyze usage patterns and improve user experience</li>
    <li>Comply with legal obligations</li>
</ul>

<h2>Information Sharing and Disclosure</h2>
<p>We do not sell, trade, or rent your personal information to third parties. We may share your information with:</p>
<ul>
    <li>Property developers and builders when you express interest in their properties</li>
    <li>Service providers who assist us in operating our website</li>
    <li>Legal authorities when required by law</li>
</ul>

<h2>Data Security</h2>
<p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure.</p>

<h2>Your Rights</h2>
<p>You have the right to:</p>
<ul>
    <li>Access your personal information</li>
    <li>Correct inaccurate information</li>
    <li>Request deletion of your information</li>
    <li>Opt-out of marketing communications</li>
    <li>Withdraw consent at any time</li>
</ul>

<h2>Cookies and Tracking Technologies</h2>
<p>We use cookies and similar tracking technologies to track activity on our website and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>

<h2>Third-Party Links</h2>
<p>Our website may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to read their privacy policies.</p>

<h2>Children\'s Privacy</h2>
<p>Our services are not directed to individuals under the age of 18. We do not knowingly collect personal information from children.</p>

<h2>Changes to This Privacy Policy</h2>
<p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date.</p>

<h2>Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us at:</p>
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
