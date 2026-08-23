@props([
    'steps' => [],
    'currentStep' => 0,
    'title' => '',
    'formAction' => '',
    'isEdit' => false,
    'propertyType' => null,
])

@php
    $defaultSteps = [
        ['key' => 'A', 'title' => 'Location'],
        ['key' => 'B', 'title' => 'Legal'],
        ['key' => 'C', 'title' => 'Dimensions'],
        ['key' => 'D', 'title' => 'Docks'],
        ['key' => 'E', 'title' => 'Facilities'],
        ['key' => 'F', 'title' => 'Loading'],
        ['key' => 'G', 'title' => 'Utilities'],
        ['key' => 'H', 'title' => 'Financial'],
        ['key' => 'I', 'title' => 'Surroundings'],
        ['key' => 'J', 'title' => 'Emergency'],
        ['key' => 'K', 'title' => 'Photos'],
        ['key' => 'L', 'title' => 'Remarks'],
        ['key' => '✓', 'title' => 'Review'],
    ];

    $stepList = !empty($steps) ? $steps : $defaultSteps;
    $totalSteps = count($stepList);
@endphp

<div class="max-w-4xl mx-auto space-y-4">

    {{-- Top Horizontal Stepper --}}
    <div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm -mx-1 px-4 py-3 rounded-xl mb-4">
        {{-- Desktop Stepper: Circles connected by lines --}}
        <div class="flex items-center gap-1">
            @foreach($stepList as $i => $st)
                @php
                    $ltr = is_array($st) ? ($st['key'] ?? ($i + 1)) : $st;
                    $stTitle = is_array($st) ? ($st['title'] ?? '') : '';
                    $isActive = ($i == $currentStep);
                    $isPast = ($i < $currentStep);
                @endphp
                <div class="flex-1 flex items-center">
                    <button type="button"
                        onclick="if(typeof wizardGoTo === 'function') wizardGoTo({{ $i }})"
                        id="wiz-dot-{{ $i }}"
                        title="{{ $ltr === '✓' ? $stTitle : $ltr . '. ' . $stTitle }}"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all flex-shrink-0 border-2 wiz-dot
                            {{ $isActive ? 'bg-zendo-navy text-white border-zendo-navy ring-2 ring-zendo-gold/50' : ($isPast ? 'bg-green-500 text-white border-green-500' : 'bg-gray-100 text-gray-400 border-gray-200') }}"
                        data-step="{{ $i }}">
                        {{ $ltr }}
                    </button>
                    @if($i < $totalSteps - 1)
                        <div id="wiz-line-{{ $i }}" class="flex-1 h-0.5 mx-0.5 wiz-line {{ $isPast ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Active Step Label --}}
        <div class="mt-2 text-center">
            <p id="wiz-title" class="text-xs font-semibold text-zendo-navy">
                {{ $title }}
            </p>
        </div>
    </div>

    {{-- Form Shell & Main Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-4">
        {{-- Card Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy font-heading text-lg">
                {{ $title }}
            </h3>
            @if($propertyType)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    {{ strtoupper($propertyType) }}
                </span>
            @endif
        </div>

        {{-- Card Body / Step Content --}}
        <div class="p-5 sm:p-6">
            {{ $slot }}
        </div>
    </div>

    {{-- Bottom Sticky Navigation Bar --}}
    <div id="wizard-nav" class="sticky bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.06)] px-4 py-3 -mx-1 rounded-xl">
        <div class="flex items-center justify-between gap-3 max-w-3xl mx-auto">

            {{-- Previous Button --}}
            <button type="button" id="wiz-prev-btn"
                onclick="if(typeof wizardPrev === 'function') wizardPrev()"
                class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300 transition-colors hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Previous
            </button>

            <div class="flex items-center gap-2 flex-1 justify-end">
                {{-- Save Draft Button --}}
                <button type="submit" name="action" value="draft" formnovalidate
                    onclick="if(document.querySelector('form')) document.querySelector('form').noValidate=true"
                    class="px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                    Save Draft
                </button>

                {{-- Save & Next Button --}}
                <button type="button" id="wiz-next-btn"
                    onclick="if(typeof wizardNext === 'function') wizardNext()"
                    class="flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-zendo-navy hover:bg-opacity-90 transition-colors">
                    Save &amp; Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Submit Button --}}
                <button type="submit" id="wiz-submit-btn" name="action" value="submit" formnovalidate
                    style="display:none"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                    Submit Listing
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    if (typeof window.wizardGoTo === 'undefined') {
        window.wizCurrent = 0;
        window.wizardGoTo = function(s) {
            const steps = document.querySelectorAll('.wizard-step-content');
            if (!steps.length) return;
            const total = steps.length;
            if (s < 0 || s >= total) return;
            window.wizCurrent = s;

            steps.forEach((el, idx) => {
                el.style.display = (idx === s) ? 'block' : 'none';
            });

            document.querySelectorAll('.wiz-dot').forEach((dot, idx) => {
                dot.classList.remove('bg-zendo-navy', 'text-white', 'border-zendo-navy', 'bg-green-500', 'bg-gray-100', 'text-gray-400');
                if (idx === s) {
                    dot.classList.add('bg-zendo-navy', 'text-white', 'border-zendo-navy');
                } else if (idx < s) {
                    dot.classList.add('bg-green-500', 'text-white', 'border-green-500');
                } else {
                    dot.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200');
                }
            });

            document.querySelectorAll('.wiz-line').forEach((line, idx) => {
                line.classList.remove('bg-green-400', 'bg-gray-200');
                line.classList.add(idx < s ? 'bg-green-400' : 'bg-gray-200');
            });

            const prevBtn = document.getElementById('wiz-prev-btn');
            if (prevBtn) {
                prevBtn.disabled = (s === 0);
                prevBtn.classList.toggle('opacity-40', s === 0);
                prevBtn.classList.toggle('cursor-not-allowed', s === 0);
            }

            const nextBtn = document.getElementById('wiz-next-btn');
            const submitBtn = document.getElementById('wiz-submit-btn');
            if (nextBtn && submitBtn) {
                if (s === total - 1) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'inline-flex';
                } else {
                    nextBtn.style.display = 'inline-flex';
                    submitBtn.style.display = 'none';
                }
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        window.wizardNext = function() {
            window.wizardGoTo((window.wizCurrent || 0) + 1);
        };

        window.wizardPrev = function() {
            window.wizardGoTo((window.wizCurrent || 0) - 1);
        };
    }
</script>
