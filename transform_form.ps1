$file = "c:\work\project\laravel\nv\resources\views\field\properties\_form.blade.php"
$c = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)

Write-Host "Starting wizard transformation..."

# ── 1. Replace top wizard progress bar ──────────────────────────────────────
$topOld = [regex]::Escape(@'
{{-- ═══════════════════════════════════════
     STEP WIZARD — TOP PROGRESS BAR
     ═══════════════════════════════════════ --}}
<div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 -mx-1 px-2 py-3 mb-4">
'@)

$topNew = @'
{{-- ═══════════════════════════════════════
     STEP WIZARD — TOP PROGRESS BAR
     ═══════════════════════════════════════ --}}
<div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 -mx-1 px-2 py-3 mb-4">
    <div class="max-w-5xl mx-auto flex items-center gap-2">
        <div id="wizard-step-0" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-zendo-navy text-white flex items-center justify-center text-xs font-bold">A</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-1" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">B</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-2" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">C</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-3" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">D</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-4" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">E</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-5" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">F</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-6" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">G</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-7" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">H</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-8" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">I</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-9" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">J</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-10" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">K</div>
            <div class="flex-1 h-0.5 bg-gray-300"></div>
        </div>
        <div id="wizard-step-11" class="wizard-step-indicator flex-1 flex items-center">
            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs font-bold">L</div>
        </div>
    </div>
</div>
'@

if ($c -match $topOld) {
    $c = $c -replace $topOld, $topNew
    Write-Host "✓ Replaced top wizard progress bar"
} else {
    Write-Host "✗ Could not find top wizard progress bar pattern"
}

# ── 2. Define section replacements ──────────────────────────────────────────
$sections = @(
    @{ Step=0; Letter='A'; Title='Location & Identification'; Key='A. Location & Identification' }
    @{ Step=1; Letter='B'; Title='Legal & Statutory Compliance'; Key='B. Legal & Statutory Compliance' }
    @{ Step=2; Letter='C'; Title='Property Dimensions'; Key='C. Property Dimensions' }
    @{ Step=3; Letter='D'; Title='Dock, Exit & Width Details'; Key='D. Dock, Exit & Width Details' }
    @{ Step=4; Letter='E'; Title='Facility Details'; Key='E. Facility Details' }
    @{ Step=5; Letter='F'; Title='Loading & Docking'; Key='F. Loading & Docking' }
    @{ Step=6; Letter='G'; Title='Utilities & Infrastructure'; Key='G. Utilities & Infrastructure' }
    @{ Step=7; Letter='H'; Title='Financial & Lease Terms'; Key='H. Financial & Lease Terms' }
    @{ Step=8; Letter='I'; Title='Surroundings & Environment'; Key='I. Surroundings & Environment' }
    @{ Step=9; Letter='J'; Title='Health & Emergency Nearby'; Key='J. Health & Emergency Nearby' }
    @{ Step=10; Letter='K'; Title='Photographs'; Key='K. Photographs' }
    @{ Step=11; Letter='L'; Title='General Remarks'; Key='L. General Remarks' }
)

# Replace each section's accordion wrapper with wizard-step wrapper
foreach ($sec in $sections) {
    $n = $sec.Step
    $letter = $sec.Letter
    $title = $sec.Title
    $key = $sec.Key
    
    # Build the old pattern (5 lines of accordion header)
    # Pattern: <div class="{{ $sec }}" x-data="{{ $sd(false, 'KeyTitle') }}">
    #              <div class="{{ $sh }}" @click="open=!open" :style="...">
    #                  <h3 ...>Letter. Title</h3>
    #                  {!! $counter !!}
    #              </div>
    #              <div x-show="open" ...>
    
    # We'll use a simpler regex that matches the section opening pattern
    $oldPattern = "(?s)<div class=`"\{\{ \`$sec \}\}`" x-data=`"\{\{ \`$sd\(false, '$key'\) \}\}`">.+?<div x-show=`"open`""
    
    $newWrapper = @"

<div class="wizard-step" data-step="$n" style="display:none">
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-sm font-semibold text-zendo-navy">$letter. $title</h3>
        {!! \$sec_errs('$key') > 0 ? '<span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">' . \$sec_errs('$key') . ' error(s)</span>' : '' !!}
    </div>
    <div
"@
    
    if ($c -match $oldPattern) {
        $c = $c -replace $oldPattern, $newWrapper
        Write-Host "✓ Replaced section $letter ($title)"
    } else {
        Write-Host "✗ Could not find section $letter pattern"
    }
}

# ── 3. Replace bottom nav Alpine block with vanilla JS nav ──────────────────
$bottomOld = [regex]::Escape(@'
{{-- ═══════════════════════════════════════════════════════════════
     STEP WIZARD — BOTTOM NAV BAR (new, non-invasive)
     Keep this inside the same <form> that wraps the sections above.
     ═══════════════════════════════════════════════════════════════ --}}
<div x-data="propertyWizard()" x-init="init()"
     class="sticky bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.04)] px-4 py-3 mt-6 -mx-1">
    <div class="flex items-center justify-between gap-2 max-w-3xl mx-auto">

        {{-- Previous --}}
        <button type="button"
            @click="prev()"
            :disabled="current === 0"
            :class="current === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-50'"
            class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Previous
        </button>

        <div class="flex items-center gap-2 flex-1 justify-end">

            {{-- Save Draft — always visible, submits form as-is (no client validation) --}}
            <button type="submit"
                name="save_mode" value="draft"
                formnovalidate
                class="px-4 py-2.5 rounded-lg text-sm font-semibold text-zendo-navy border border-zendo-navy/30 bg-white hover:bg-gray-50 transition-colors">
                Save Draft
            </button>

            {{-- Save & Next — visible on all steps except the last --}}
            <button type="button"
                x-show="current < steps.length - 1"
                @click="next()"
                class="flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-zendo-navy hover:bg-opacity-90 transition-colors">
                Save &amp; Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Submit to Office — visible only on last step, real submit --}}
            <button type="submit"
                x-show="current === steps.length - 1"
                name="save_mode" value="submit"
                class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                Submit to Office
            </button>
        </div>
    </div>
</div>
'@)


$bottomNew = @'
{{-- ═══════════════════════════════════════════════════════════════
     STEP WIZARD — BOTTOM NAV BAR (vanilla JS)
     ═══════════════════════════════════════════════════════════════ --}}
<div id="wizard-nav" class="sticky bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.04)] px-4 py-3 mt-6 -mx-1">
    <div class="flex items-center justify-between gap-2 max-w-3xl mx-auto">

        {{-- Previous --}}
        <button type="button"
            id="wizard-prev-btn"
            onclick="wizardPrev()"
            disabled
            class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300 transition-colors opacity-40 cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Previous
        </button>

        <div class="flex items-center gap-2 flex-1 justify-end">

            {{-- Save Draft — always visible, submits form as-is (no client validation) --}}
            <button type="submit"
                name="save_mode" value="draft"
                formnovalidate
                class="px-4 py-2.5 rounded-lg text-sm font-semibold text-zendo-navy border border-zendo-navy/30 bg-white hover:bg-gray-50 transition-colors">
                Save Draft
            </button>

            {{-- Save & Next — visible on all steps except the last --}}
            <button type="button"
                id="wizard-next-btn"
                onclick="wizardNext()"
                class="flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-zendo-navy hover:bg-opacity-90 transition-colors">
                Save &amp; Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Submit to Office — visible only on last step, real submit --}}
            <button type="submit"
                id="wizard-submit-btn"
                name="save_mode" value="submit"
                style="display:none"
                class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                Submit to Office
            </button>
        </div>
    </div>
</div>

<script>
// Wizard controller — vanilla JS
let wizardCurrentStep = 0;
const wizardTotalSteps = 12;

function wizardGoTo(step) {
    // Hide all steps
    document.querySelectorAll('.wizard-step').forEach(el => el.style.display = 'none');
    
    // Show target step
    const targetStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
    if (targetStep) {
        targetStep.style.display = 'block';
        wizardCurrentStep = step;
        
        // Update progress bar
        document.querySelectorAll('.wizard-step-indicator').forEach((el, idx) => {
            const circle = el.querySelector('div:first-child');
            const line = el.querySelector('div:last-child');
            
            if (idx < step) {
                // Completed
                circle.classList.remove('bg-gray-300', 'bg-zendo-navy');
                circle.classList.add('bg-green-500');
                if (line) line.classList.remove('bg-gray-300');
                if (line) line.classList.add('bg-green-500');
            } else if (idx === step) {
                // Current
                circle.classList.remove('bg-gray-300', 'bg-green-500');
                circle.classList.add('bg-zendo-navy');
                if (line) line.classList.remove('bg-green-500');
                if (line) line.classList.add('bg-gray-300');
            } else {
                // Not reached
                circle.classList.remove('bg-zendo-navy', 'bg-green-500');
                circle.classList.add('bg-gray-300');
                if (line) line.classList.remove('bg-green-500');
                if (line) line.classList.add('bg-gray-300');
            }
        });
        
        // Update button states
        const prevBtn = document.getElementById('wizard-prev-btn');
        const nextBtn = document.getElementById('wizard-next-btn');
        const submitBtn = document.getElementById('wizard-submit-btn');
        
        if (prevBtn) {
            if (step === 0) {
                prevBtn.disabled = true;
                prevBtn.classList.add('opacity-40', 'cursor-not-allowed');
                prevBtn.classList.remove('hover:bg-gray-50');
            } else {
                prevBtn.disabled = false;
                prevBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                prevBtn.classList.add('hover:bg-gray-50');
            }
        }
        
        if (nextBtn && submitBtn) {
            if (step === wizardTotalSteps - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'block';
            } else {
                nextBtn.style.display = 'flex';
                submitBtn.style.display = 'none';
            }
        }
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function wizardNext() {
    if (wizardCurrentStep < wizardTotalSteps - 1) {
        wizardGoTo(wizardCurrentStep + 1);
    }
}

function wizardPrev() {
    if (wizardCurrentStep > 0) {
        wizardGoTo(wizardCurrentStep - 1);
    }
}

// Initialize wizard on page load
document.addEventListener('DOMContentLoaded', function() {
    wizardGoTo(0);
});
</script>
'@

if ($c -match $bottomOld) {
    $c = $c -replace $bottomOld, $bottomNew
    Write-Host "✓ Replaced bottom wizard nav bar"
} else {
    Write-Host "✗ Could not find bottom wizard nav bar pattern"
}

# ── 4. Add closing </div></div> tags for each wizard-step ──────────────────
# Need to close each section's content div + wizard-step div
# Pattern: find each </div>{{-- ══ SectionName ══ --}} and add </div></div> before it

# Actually, simpler approach: find the comment markers that separate sections
# and add </div></div> before each one (except the first section)

$sectionComments = @(
    '{{-- ══ B. Legal & Statutory Compliance ═══════════════════════════════════════════════════════════════ --}}'
    '{{-- ══ C. Property Dimensions (plot, built-up, clear height, FSI, etc.) ══════════════════════════════ --}}'
    '{{-- ══ D. Docks, Levellers, Fire Exits, Canopy & Road Widths ════════════ --}}'
    '{{-- ══ E. Facilities (offices, canteen, washrooms, STP, etc.) ══════════ --}}'
    '{{-- ══ F. Loading & Docking ══════════════════════════════════════════════════ --}}'
    '{{-- ══ G. Utilities & Infrastructure ═══════════════════════════════════════ --}}'
    '{{-- ══ H. Financial & Lease Terms ════════════════════════════════════════════ --}}'
    '{{-- ══ I. Surroundings & Environment ═══════════════════════════════════════ --}}'
    '{{-- ══ J. Health & Emergency Facilities Nearby ══════════════════════════════ --}}'
    '{{-- ══ K. Photos ══════════════════════════════════════════════════════════ --}}'
    '{{-- ══ L. General Remarks ═════════════════════════════════════════════════ --}}'
)

foreach ($comment in $sectionComments) {
    $escaped = [regex]::Escape($comment)
    $c = $c -replace $escaped, ("</div></div>`n`n" + $comment)
}

# Add closing tags at the very end (before the wizard nav bar)
$beforeNav = [regex]::Escape('{{-- ═══════════════════════════════════════════════════════════════
     STEP WIZARD — BOTTOM NAV BAR (vanilla JS)')
$c = $c -replace $beforeNav, ("</div></div>`n`n" + '{{-- ═══════════════════════════════════════════════════════════════
     STEP WIZARD — BOTTOM NAV BAR (vanilla JS)')

Write-Host "✓ Added closing tags for wizard steps"

# ── 5. Save the transformed file ──────────────────────────────────────────
[System.IO.File]::WriteAllText($file, $c, [System.Text.Encoding]::UTF8)
Write-Host ""
Write-Host "✅ Transformation complete! File saved to: $file"
Write-Host ""
Write-Host "Next steps:"
Write-Host "1. Remove duplicate button rows from create.blade.php"
Write-Host "2. Remove duplicate button rows from edit.blade.php"
Write-Host "3. Clear view cache: php artisan view:clear"
Write-Host "4. Test the wizard in browser"
