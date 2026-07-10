# Simple PowerShell script to transform accordion form to wizard
$file = "c:\work\project\laravel\nv\resources\views\field\properties\_form.blade.php"
$content = Get-Content $file -Raw -Encoding UTF8

Write-Host "Starting transformation..."

# Step 1: Replace top wizard progress bar (find the opening div and keep it, but replace what follows)
# Find: <div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 -mx-1 px-2 py-3 mb-4">
# Then find the closing </div> and replace everything between

$topOldStart = '<div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 -mx-1 px-2 py-3 mb-4">'
$topNewContent = @'
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

# Use regex to replace the old wizard progress section
$pattern = '(?s)<div id="wizard-progress"[^>]*>.*?</div>(?=\s*{{-- ══ A\. Location)'
$content = $content -replace $pattern, $topNewContent

Write-Host "Step 1: Replaced top wizard progress bar"

# Step 2: Replace each section wrapper
# We'll use a function to generate the new wrapper for each section
function Get-WizardStepHeader {
    param($step, $letter, $title, $key)
    
    return @"

<div class="wizard-step" data-step="$step" style="display:none">
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-sm font-semibold text-zendo-navy">$letter. $title</h3>
        {!! `$sec_errs('$key') > 0 ? '<span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">' . `$sec_errs('$key') . ' error(s)</span>' : '' !!}
    </div>
    <div
"@
}

# Section A: Location & Identification
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(true, ''A\. Location & Identification''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 0 'A' 'Location & Identification' 'A. Location & Identification')
Write-Host "Step 2A: Replaced section A"

# Section B: Legal & Statutory Compliance  
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''B\. Legal & Statutory Compliance''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 1 'B' 'Legal & Statutory Compliance' 'B. Legal & Statutory Compliance')
Write-Host "Step 2B: Replaced section B"

# Section C: Property Dimensions
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''C\. Property Dimensions''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 2 'C' 'Property Dimensions' 'C. Property Dimensions')
Write-Host "Step 2C: Replaced section C"

# Section D: Dock, Exit & Width Details
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''D\. Dock, Exit & Width Details''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 3 'D' 'Dock, Exit & Width Details' 'D. Dock, Exit & Width Details')
Write-Host "Step 2D: Replaced section D"

# Section E: Facility Details
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''E\. Facility Details''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 4 'E' 'Facility Details' 'E. Facility Details')
Write-Host "Step 2E: Replaced section E"

# Section F: Loading & Docking
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''F\. Loading & Docking''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 5 'F' 'Loading & Docking' 'F. Loading & Docking')
Write-Host "Step 2F: Replaced section F"

# Section G: Utilities & Infrastructure
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''G\. Utilities & Infrastructure''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 6 'G' 'Utilities & Infrastructure' 'G. Utilities & Infrastructure')
Write-Host "Step 2G: Replaced section G"

# Section H: Financial & Lease Terms
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''H\. Financial & Lease Terms''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 7 'H' 'Financial & Lease Terms' 'H. Financial & Lease Terms')
Write-Host "Step 2H: Replaced section H"

# Section I: Surroundings & Environment
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''I\. Surroundings & Environment''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 8 'I' 'Surroundings & Environment' 'I. Surroundings & Environment')
Write-Host "Step 2I: Replaced section I"

# Section J: Health & Emergency Nearby
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''J\. Health & Emergency Nearby''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 9 'J' 'Health & Emergency Nearby' 'J. Health & Emergency Nearby')
Write-Host "Step 2J: Replaced section J"

# Section K: Photographs
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''K\. Photographs''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 10 'K' 'Photographs' 'K. Photographs')
Write-Host "Step 2K: Replaced section K"

# Section L: General Remarks
$content = $content -replace '(?s)<div class="\{\{ \$sec \}\}" x-data="\{\{ \$sd\(false, ''L\. General Remarks''\) \}\}">.*?<div x-show="open"', (Get-WizardStepHeader 11 'L' 'General Remarks' 'L. General Remarks')
Write-Host "Step 2L: Replaced section L"

# Step 3: Add closing tags before each section comment (except first)
$sectionMarkers = @(
    '{{-- ══ B. Legal & Statutory Compliance',
    '{{-- ══ C. Property Dimensions',
    '{{-- ══ D. Docks, Levellers, Fire Exits',
    '{{-- ══ E. Facilities',
    '{{-- ══ F. Loading & Docking',
    '{{-- ══ G. Utilities & Infrastructure',
    '{{-- ══ H. Financial & Lease Terms',
    '{{-- ══ I. Surroundings & Environment',
    '{{-- ══ J. Health & Emergency Facilities',
    '{{-- ══ K. Photos',
    '{{-- ══ L. General Remarks'
)

foreach ($marker in $sectionMarkers) {
    $content = $content -replace [regex]::Escape($marker), ("`n</div></div>`n`n" + $marker)
}

Write-Host "Step 3: Added closing tags between sections"

# Step 4: Replace bottom nav bar
$bottomOldPattern = '(?s){{-- ═+\s+STEP WIZARD — BOTTOM NAV BAR.*?</div>\s*</div>'

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

$content = $content -replace $bottomOldPattern, $bottomNew

Write-Host "Step 4: Replaced bottom wizard nav bar"

# Step 5: Add final closing tags before the wizard nav
$content = $content -replace '{{-- ═+\s+STEP WIZARD — BOTTOM NAV BAR \(vanilla JS\)', ("`n</div></div>`n`n" + '{{-- ═══════════════════════════════════════════════════════════════
     STEP WIZARD — BOTTOM NAV BAR (vanilla JS)')

Write-Host "Step 5: Added final closing tags"

# Save the file
$content | Out-File -FilePath $file -Encoding UTF8 -NoNewline

Write-Host "`n✅ Transformation complete!"
Write-Host "`nNext steps:"
Write-Host "1. Remove duplicate button rows from create.blade.php"
Write-Host "2. Remove duplicate button rows from edit.blade.php"
Write-Host "3. Clear view cache: php artisan view:clear"
Write-Host "4. Test the wizard in browser"
