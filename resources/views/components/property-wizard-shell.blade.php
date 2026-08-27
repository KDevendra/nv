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

<div class="max-w-5xl mx-auto space-y-4">
    <input type="hidden" name="wizard_step" id="wizard_step_input" value="{{ session('wizard_step', $currentStep ?? 0) }}">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm transition-all" role="alert">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg leading-none focus:outline-none">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm transition-all" role="alert">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-lg leading-none focus:outline-none">&times;</button>
        </div>
    @endif

    {{-- Top Horizontal Stepper --}}
    <div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm -mx-1 px-4 py-3 rounded-xl mb-4">
        {{-- Desktop Stepper: Circles connected by lines --}}
        <div class="flex items-center gap-1">
            @foreach($stepList as $i => $st)
                @php
                    $rawKey = is_array($st) ? ($st['key'] ?? '') : $st;
                    $ltr = ($rawKey === '✓' || $i === $totalSteps - 1 && str_contains(strtolower($st['title'] ?? ''), 'review')) ? '✓' : chr(65 + $i);
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
            {{-- Shown briefly when a locked step tab is clicked; the offending
                 fields get their own inline messages at the same time. --}}
            <p id="wiz-lock-msg" class="hidden mt-1 text-xs text-red-600 font-medium">
                Complete this step before continuing.
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
                    onclick="if(document.querySelector('form')) document.querySelector('form').noValidate=true; var inp=document.getElementById('wizard_step_input'); if(inp) inp.value = window.wizCurrent || 0;"
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

{{-- Per-field format/required validation with inline messages — shared by all
     13 dedicated wizards through this shell, and by the warehouse form
     through field/properties/_form.blade.php. --}}
<x-wizard-field-validation />

<script>
    // Shared across all 13 dedicated property-type wizards. Only ever defined
    // once — the apartment-flat-studio view (and possibly others) ships its
    // own copy of wizardGoTo/wizardNext/wizardPrev ahead of this include, in
    if (typeof window.wizardValidateStep === 'undefined') {
        window.wizardValidateStep = function(stepIndex) {
            const steps = document.querySelectorAll('.wizard-step-content');
            const panel = steps[stepIndex];
            if (!panel) return true;

            let fieldValidationPassed = true;
            if (window.ZendoFieldValidation) {
                fieldValidationPassed = window.ZendoFieldValidation.validateContainer(panel, true);
            }

            const fields = panel.querySelectorAll('[required], input, select, textarea');
            const invalidFields = [];

            for (const field of fields) {
                if (field.disabled || field.offsetParent === null) continue;
                if (!field.checkValidity() || (field.hasAttribute('required') && (!field.value || !field.value.toString().trim()))) {
                    invalidFields.push(field);
                }
            }

            if (!fieldValidationPassed || invalidFields.length > 0) {
                const firstInvalid = invalidFields[0] || panel.querySelector('.border-red-500, .wiz-field-err');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    try { firstInvalid.focus({ preventScroll: true }); } catch (e) {}
                }

                const fieldNames = [];
                invalidFields.forEach(el => {
                    let labelText = '';
                    const parent = el.closest('div');
                    if (parent) {
                        const lbl = parent.querySelector('label');
                        if (lbl) labelText = lbl.textContent.replace('*', '').trim();
                    }
                    if (!labelText) labelText = el.getAttribute('placeholder') || el.getAttribute('name') || 'Required field';
                    if (labelText && !fieldNames.includes(labelText)) fieldNames.push(labelText);
                });

                if (typeof window.flashLockMessage === 'function') {
                    window.flashLockMessage(fieldNames.slice(0, 3).join(', '));
                }

                return false;
            }

            return true;
        };

        window.wizardValidateAll = function() {
            const total = document.querySelectorAll('.wizard-step-content').length;
            for (let i = 0; i < total; i++) {
                window.wizardGoTo(i);
                if (!window.wizardValidateStep(i)) return false;
            }
            return true;
        };
    }

    if (typeof window.wizardGoTo === 'undefined') {
        window.wizCurrent = 0;
        window.wizardGoTo = function(s) {
            const steps = document.querySelectorAll('.wizard-step-content');
            if (!steps.length) return;
            const total = steps.length;
            if (s < 0 || s >= total) return;
            window.wizCurrent = s; const stepInput = document.getElementById('wizard_step_input'); if (stepInput) stepInput.value = s;

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
            const cur = window.wizCurrent || 0;
            if (!window.wizardValidateStep(cur)) return;
            window.wizardGoTo(cur + 1);
        };

        window.wizardPrev = function() {
            window.wizardGoTo((window.wizCurrent || 0) - 1);
        };
    }

    (function () {
        if (window.__wizTabGateInstalled) return;
        window.__wizTabGateInstalled = true;

        function totalSteps() {
            return document.querySelectorAll('.wizard-step-content').length;
        }

        function current() {
            return window.wizCurrent || 0;
        }

        function frontier() {
            if (typeof window.wizFrontier !== 'number') window.wizFrontier = current();
            return window.wizFrontier;
        }

        function bumpFrontier() {
            if (current() > frontier()) window.wizFrontier = current();
        }

        window.flashLockMessage = function(fieldNamesStr) {
            var msg = document.getElementById('wiz-lock-msg');
            if (!msg) return;
            if (fieldNamesStr) {
                msg.innerHTML = '⚠️ <span class="font-bold">Please complete required field(s):</span> <span class="text-red-700 font-semibold">' + fieldNamesStr + '</span>';
            } else {
                msg.textContent = 'Complete this step before continuing.';
            }
            msg.classList.remove('hidden');
            clearTimeout(window.__wizLockMsgTimer);
            window.__wizLockMsgTimer = setTimeout(function () {
                msg.classList.add('hidden');
            }, 6000);
        };

        function isReachable(target) {
            return target <= frontier() || target === current() + 1;
        }

        window.wizardRefreshTabLocks = function () {
            document.querySelectorAll('.wiz-dot').forEach(function (dot) {
                var target = parseInt(dot.getAttribute('data-step'), 10);
                if (isNaN(target)) return;
                var locked = !isReachable(target) && target !== current();
                dot.classList.toggle('cursor-not-allowed', locked);
                dot.classList.toggle('opacity-50', locked);
                dot.setAttribute('aria-disabled', locked ? 'true' : 'false');
                if (locked) {
                    dot.setAttribute('title', 'Complete the earlier steps first');
                }
            });
        };

        document.addEventListener('click', function (e) {
            var dot = e.target.closest ? e.target.closest('.wiz-dot') : null;
            if (!dot) return;

            var target = parseInt(dot.getAttribute('data-step'), 10);
            if (isNaN(target)) return;

            var cur = current();
            if (target === cur) return;                 // no-op
            if (target <= frontier()) return;           // already reached — allow, incl. backward

            var blocked = false;
            if (target > cur + 1) {
                blocked = true;                          // must advance one step at a time
            } else if (typeof window.wizardValidateStep === 'function'
                       && !window.wizardValidateStep(cur)) {
                blocked = true;                          // next step, but this one isn't valid
            }

            if (blocked) {
                e.preventDefault();
                e.stopPropagation();                     // inline onclick never fires
                flashLockMessage();
                // wizardValidateStep already rendered the inline field errors
                // when it ran above; for the "too far ahead" case run it now so
                // the user still sees what is actually holding them back.
                if (target > cur + 1 && typeof window.wizardValidateStep === 'function') {
                    window.wizardValidateStep(cur);
                }
            }
        }, true);

        // Submit is a real <button type="submit">, so this has to run on click
        // (before the browser starts submitting) rather than on a form "submit"
        // listener — by the time "submit" fires it's too late to swap the
        // visible step without the in-flight submission going through anyway.
        document.addEventListener('DOMContentLoaded', function () {
            const submitBtn = document.getElementById('wiz-submit-btn');
            if (submitBtn && !submitBtn.dataset.wizGuarded) {
                submitBtn.dataset.wizGuarded = '1';
                submitBtn.addEventListener('click', function (e) {
                    if (typeof window.wizardValidateAll === 'function' && !window.wizardValidateAll()) {
                        e.preventDefault();
                    }
                });
            }

            // Each per-type view defines its own window.wizardGoTo inline in
            // its own scripts section, which the layout renders after this
            // block — so by DOMContentLoaded that override is final and safe
            // to wrap. (Do not write Blade directive names in these comments;
            // Blade compiles them even inside JS comments.)
            // Wrapping (rather than redefining) keeps each view's own dot/
            // select2/scroll behaviour intact while tracking the frontier.
            var inner = window.wizardGoTo;
            if (typeof inner === 'function' && !inner.__wizWrapped) {
                var wrapped = function (s) {
                    var r = inner.apply(this, arguments);
                    bumpFrontier();
                    window.wizardRefreshTabLocks();
                    return r;
                };
                wrapped.__wizWrapped = true;
                window.wizardGoTo = wrapped;
            }

            bumpFrontier();
            window.wizardRefreshTabLocks();
        });
    })();
</script>
