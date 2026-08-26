@extends('layouts.admin')

@section('title', 'Edit Advisory Page')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Advisory Page Content</h2>
                <a href="{{ route('advisory.services') }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    View Advisory Page
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mx-6 mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.advisory-page.update') }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Meta / Page Title -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO & Page Title</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Browser Window Title</label>
                    <input type="text" name="page_title" value="{{ old('page_title', $advisoryPage->page_title ?? '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                </div>
            </div>

            <!-- Hero Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Hero Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hero Eyebrow</label>
                        <input type="text" name="hero_eyebrow" value="{{ old('hero_eyebrow', $advisoryPage->hero_eyebrow ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hero Note / Subline</label>
                        <input type="text" name="hero_note" value="{{ old('hero_note', $advisoryPage->hero_note ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title (HTML supported, e.g. &lt;span&gt;gold text&lt;/span&gt;)</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $advisoryPage->hero_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hero Description</label>
                        <textarea name="hero_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('hero_description', $advisoryPage->hero_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button 1 Text</label>
                        <input type="text" name="hero_btn1_text" value="{{ old('hero_btn1_text', $advisoryPage->hero_btn1_text ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button 1 Link</label>
                        <input type="text" name="hero_btn1_link" value="{{ old('hero_btn1_link', $advisoryPage->hero_btn1_link ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button 2 Text</label>
                        <input type="text" name="hero_btn2_text" value="{{ old('hero_btn2_text', $advisoryPage->hero_btn2_text ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button 2 Link</label>
                        <input type="text" name="hero_btn2_link" value="{{ old('hero_btn2_link', $advisoryPage->hero_btn2_link ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Services Section Header -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Services Section Header</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Services Eyebrow</label>
                        <input type="text" name="services_eyebrow" value="{{ old('services_eyebrow', $advisoryPage->services_eyebrow ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Services Title</label>
                        <input type="text" name="services_title" value="{{ old('services_title', $advisoryPage->services_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Services Description</label>
                        <textarea name="services_description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('services_description', $advisoryPage->services_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Track 1: ZENDO Select -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Track 1: ZENDO Select</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Track Title</label>
                        <input type="text" name="track1_title" value="{{ old('track1_title', $advisoryPage->track1_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Track Tagline</label>
                        <input type="text" name="track1_tagline" value="{{ old('track1_tagline', $advisoryPage->track1_tagline ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Track Description</label>
                        <input type="text" name="track1_description" value="{{ old('track1_description', $advisoryPage->track1_description ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefits List</label>
                    <div id="track1-benefits-container" class="space-y-2">
                        @php
                            $track1Benefits = old('track1_benefits', $advisoryPage->track1_benefits ?? []);
                            if (empty($track1Benefits)) { $track1Benefits = ['']; }
                        @endphp
                        @foreach ($track1Benefits as $index => $benefit)
                            <div class="flex items-center gap-2">
                                <input type="text" name="track1_benefits[]" value="{{ $benefit }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <button type="button" onclick="removeRow(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addBenefitRow('track1-benefits-container', 'track1_benefits[]')"
                        class="mt-3 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                        + Add Benefit
                    </button>
                </div>
            </div>

            <!-- Track 2: ZENDO Upgrade -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Track 2: ZENDO Upgrade</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Track Title</label>
                        <input type="text" name="track2_title" value="{{ old('track2_title', $advisoryPage->track2_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Track Tagline</label>
                        <input type="text" name="track2_tagline" value="{{ old('track2_tagline', $advisoryPage->track2_tagline ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Track Description</label>
                        <input type="text" name="track2_description" value="{{ old('track2_description', $advisoryPage->track2_description ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefits List</label>
                    <div id="track2-benefits-container" class="space-y-2">
                        @php
                            $track2Benefits = old('track2_benefits', $advisoryPage->track2_benefits ?? []);
                            if (empty($track2Benefits)) { $track2Benefits = ['']; }
                        @endphp
                        @foreach ($track2Benefits as $index => $benefit)
                            <div class="flex items-center gap-2">
                                <input type="text" name="track2_benefits[]" value="{{ $benefit }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <button type="button" onclick="removeRow(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addBenefitRow('track2-benefits-container', 'track2_benefits[]')"
                        class="mt-3 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                        + Add Benefit
                    </button>
                </div>
            </div>

            <!-- Why Choose ZENDO -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Why Choose ZENDO Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Why Eyebrow</label>
                        <input type="text" name="why_eyebrow" value="{{ old('why_eyebrow', $advisoryPage->why_eyebrow ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Why Section Title</label>
                        <input type="text" name="why_title" value="{{ old('why_title', $advisoryPage->why_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Why Choose Cards (4 Grid Items)</label>
                    <div id="why-items-container" class="space-y-4">
                        @php
                            $whyItems = old('why_items', $advisoryPage->why_items ?? []);
                            if (empty($whyItems)) { $whyItems = [['title' => '', 'description' => '']]; }
                        @endphp
                        @foreach ($whyItems as $index => $item)
                            <div class="why-item-card p-4 border border-gray-200 rounded-lg bg-gray-50 relative space-y-2">
                                <button type="button" onclick="removeRow(this.closest('.why-item-card'))"
                                    class="absolute top-2 right-2 px-2.5 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded hover:bg-red-200">
                                    Remove Card
                                </button>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Card Title</label>
                                    <input type="text" name="why_items[{{ $index }}][title]" value="{{ $item['title'] ?? '' }}"
                                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Card Description</label>
                                    <input type="text" name="why_items[{{ $index }}][description]" value="{{ $item['description'] ?? '' }}"
                                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addWhyItemRow()"
                        class="mt-3 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                        + Add Card Item
                    </button>
                </div>
            </div>

            <!-- Final CTA Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Final CTA Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CTA Eyebrow</label>
                        <input type="text" name="cta_eyebrow" value="{{ old('cta_eyebrow', $advisoryPage->cta_eyebrow ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CTA Title</label>
                        <input type="text" name="cta_title" value="{{ old('cta_title', $advisoryPage->cta_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Display Text</label>
                        <input type="text" name="cta_phone_text" value="{{ old('cta_phone_text', $advisoryPage->cta_phone_text ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Link (e.g. tel:+917494010101)</label>
                        <input type="text" name="cta_phone_link" value="{{ old('cta_phone_link', $advisoryPage->cta_phone_link ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">CTA Note / Availability</label>
                        <input type="text" name="cta_note" value="{{ old('cta_note', $advisoryPage->cta_note ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Call Button Text</label>
                        <input type="text" name="cta_btn1_text" value="{{ old('cta_btn1_text', $advisoryPage->cta_btn1_text ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Call Button Link</label>
                        <input type="text" name="cta_btn1_link" value="{{ old('cta_btn1_link', $advisoryPage->cta_btn1_link ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
                        <input type="text" name="cta_btn2_text" value="{{ old('cta_btn2_text', $advisoryPage->cta_btn2_text ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Link</label>
                        <input type="text" name="cta_btn2_link" value="{{ old('cta_btn2_link', $advisoryPage->cta_btn2_link ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Footnote Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Footnote Section</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Footnote Content (HTML / Links supported)</label>
                    <textarea name="footnote_text" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('footnote_text', $advisoryPage->footnote_text ?? '') }}</textarea>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $advisoryPage->is_active ?? true) ? 'checked' : '' }}
                    class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (Visible on public website)</label>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-2.5 bg-zendo-navy text-white font-semibold rounded-lg hover:bg-opacity-90 transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function removeRow(element) {
        element.closest('div').remove();
    }

    function addBenefitRow(containerId, inputName) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" name="${inputName}" value=""
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            <button type="button" onclick="removeRow(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                &times;
            </button>
        `;
        container.appendChild(div);
    }

    let whyItemIndex = {{ count(old('why_items', $advisoryPage->why_items ?? [])) }};
    function addWhyItemRow() {
        const container = document.getElementById('why-items-container');
        const div = document.createElement('div');
        div.className = 'why-item-card p-4 border border-gray-200 rounded-lg bg-gray-50 relative space-y-2';
        div.innerHTML = `
            <button type="button" onclick="removeRow(this.closest('.why-item-card'))"
                class="absolute top-2 right-2 px-2.5 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded hover:bg-red-200">
                Remove Card
            </button>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Card Title</label>
                <input type="text" name="why_items[${whyItemIndex}][title]" value=""
                    class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Card Description</label>
                <input type="text" name="why_items[${whyItemIndex}][description]" value=""
                    class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>
        `;
        container.appendChild(div);
        whyItemIndex++;
    }
</script>
@endsection
