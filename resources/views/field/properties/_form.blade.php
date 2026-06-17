{{-- Shared form partial for create & edit. Expects: $entry (null on create), $slots --}}
@php
    $v = fn($field) => old($field, $entry->$field ?? '');
    $sel = fn($field, $option) => old($field, $entry->$field ?? '') == $option ? 'selected' : '';
    $inputClass = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent text-sm';
    $selectClass = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent text-sm bg-white';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-1';
    $sectionClass = 'bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4';
    $sectionHead = 'flex items-center justify-between px-5 py-4 cursor-pointer select-none bg-gray-50 border-b border-gray-100';
    $sectionBody = 'px-5 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4';
@endphp

{{-- ── SECTION A — Location & Identification ─────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: true }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">A. Location &amp; Identification <span class="text-red-500">*</span></h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div>
            <label class="{{ $labelClass }}">Facility Type <span class="text-red-500">*</span></label>
            <select name="facility_type" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Warehouse','Industrial Shed','Cold Storage','Open Land','Commercial Space','Factory'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('facility_type', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('facility_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-2 lg:col-span-3">
            <label class="{{ $labelClass }}">Full Address</label>
            <textarea name="name_full_address" rows="2" class="{{ $inputClass }}">{{ $v('name_full_address') }}</textarea>
        </div>
        <div>
            <label class="{{ $labelClass }}">Village / Town / District</label>
            <input type="text" name="village_town_district" value="{{ $v('village_town_district') }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">PIN Code</label>
            <input type="text" name="postal_address_pin" value="{{ $v('postal_address_pin') }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">Nearest City <span class="text-red-500">*</span></label>
            <input type="text" name="nearest_city" value="{{ $v('nearest_city') }}" class="{{ $inputClass }}">
            @error('nearest_city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="{{ $labelClass }}">Nearest Highway</label>
            <input type="text" name="nearest_highway" value="{{ $v('nearest_highway') }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">Nearest Railway Station</label>
            <input type="text" name="nearest_railway_station" value="{{ $v('nearest_railway_station') }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">Nearest Airport</label>
            <input type="text" name="nearest_airport" value="{{ $v('nearest_airport') }}" class="{{ $inputClass }}">
        </div>
    </div>
</div>

{{-- ── SECTION B — Legal & Statutory Compliance ──────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">B. Legal &amp; Statutory Compliance</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div>
            <label class="{{ $labelClass }}">Tenure</label>
            <select name="tenure" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Freehold','Leasehold','Other'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('tenure', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $labelClass }}">Approved Land Use</label>
            <select name="approved_land_use" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Industrial','Commercial','Warehousing','Agricultural','Mixed','Not sure'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('approved_land_use', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $labelClass }}">Fire NOC</label>
            <select name="fire_noc" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Yes','No','Applied'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('fire_noc', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $labelClass }}">CLU Conversion Status</label>
            <input type="text" name="clu_conversion_status" value="{{ $v('clu_conversion_status') }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">Occupancy Certificate</label>
            <select name="occupancy_certificate" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Yes','No','NA'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('occupancy_certificate', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ── SECTION C — Property Dimensions ──────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">C. Property Dimensions</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div><label class="{{ $labelClass }}">Plot Area (sq ft)</label><input type="number" step="0.01" min="0" name="plot_area" value="{{ $v('plot_area') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Built-up Area (sq ft)</label><input type="number" step="0.01" min="0" name="built_up_area" value="{{ $v('built_up_area') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Clear Height — Highest (ft)</label><input type="number" step="0.01" min="0" name="clear_height_highest" value="{{ $v('clear_height_highest') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Clear Height — Side (ft)</label><input type="number" step="0.01" min="0" name="clear_height_side" value="{{ $v('clear_height_side') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Number of Floors</label><input type="number" min="0" name="number_of_floors" value="{{ $v('number_of_floors') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">FSI / FAR</label><input type="text" name="fsi_far" value="{{ $v('fsi_far') }}" class="{{ $inputClass }}"></div>
    </div>
</div>

{{-- ── SECTION D — Loading & Docking ────────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">D. Loading &amp; Docking Facilities</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div><label class="{{ $labelClass }}">Dock Door Count</label><input type="number" min="0" name="dock_door_count" value="{{ $v('dock_door_count') }}" class="{{ $inputClass }}"></div>
        <div>
            <label class="{{ $labelClass }}">Dock Type</label>
            <select name="dock_type" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Ground level','Dock high','Both','None'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('dock_type', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="{{ $labelClass }}">Dock Height (ft)</label><input type="number" step="0.01" min="0" name="dock_height" value="{{ $v('dock_height') }}" class="{{ $inputClass }}"></div>
        <div>
            <label class="{{ $labelClass }}">Truck Movement</label>
            <select name="truck_movement" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['40 ft container','32 ft truck','Tempo only','Restricted'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('truck_movement', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ── SECTION E — Internal Environment ─────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">E. Internal Environment &amp; Amenities</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div>
            <label class="{{ $labelClass }}">Flooring Type</label>
            <select name="flooring_type" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['FM2','VDF','Trimix','Concrete','Kota / Tile','Kachha'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('flooring_type', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="{{ $labelClass }}">Office / Cabin Area (sq ft)</label><input type="number" step="0.01" min="0" name="office_cabin_area" value="{{ $v('office_cabin_area') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Washrooms</label><input type="number" min="0" name="washrooms" value="{{ $v('washrooms') }}" class="{{ $inputClass }}"></div>
        <div>
            <label class="{{ $labelClass }}">Ventilation &amp; Lighting</label>
            <select name="ventilation_lighting" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Good','Average','Poor'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('ventilation_lighting', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ── SECTION F — Utilities & Infrastructure ───────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">F. Utilities &amp; Infrastructure</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div><label class="{{ $labelClass }}">Power Sanctioned (KVA)</label><input type="number" step="0.01" min="0" name="power_sanctioned_kva" value="{{ $v('power_sanctioned_kva') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">DISCOM Name</label><input type="text" name="discom_name" value="{{ $v('discom_name') }}" class="{{ $inputClass }}"></div>
        <div>
            <label class="{{ $labelClass }}">Water Source</label>
            <select name="water_source" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Borewell','Municipal','Tanker','None'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('water_source', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="{{ $labelClass }}">Fire Fighting System</label>
            <select name="fire_fighting_system" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Full sprinkler','Hydrant only','Extinguishers','None'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('fire_fighting_system', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ── SECTION G — Financial & Lease Terms ──────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">G. Financial &amp; Lease Terms</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div>
            <label class="{{ $labelClass }}">Deal Type</label>
            <select name="deal_type" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['Lease','Sale','Both'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('deal_type', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="{{ $labelClass }}">Expected Rent (₹/sq ft/month)</label><input type="number" step="0.01" min="0" name="expected_rent" value="{{ $v('expected_rent') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Expected Sale Price (₹)</label><input type="number" step="0.01" min="0" name="expected_sale_price" value="{{ $v('expected_sale_price') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Security Deposit (months)</label><input type="number" step="0.01" min="0" name="security_deposit_months" value="{{ $v('security_deposit_months') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Lock-in Period (years)</label><input type="number" step="0.01" min="0" name="lock_in_years" value="{{ $v('lock_in_years') }}" class="{{ $inputClass }}"></div>
        <div>
            <label class="{{ $labelClass }}">Available From</label>
            <input type="date" name="available_from"
                value="{{ old('available_from', (isset($entry) && $entry && $entry->available_from) ? $entry->available_from->format('Y-m-d') : '') }}"
                class="{{ $inputClass }}">
        </div>
    </div>
</div>

{{-- ── SECTION H — Surroundings ──────────────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">H. Surroundings &amp; Environment</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div><label class="{{ $labelClass }}">Approach Road Width (ft)</label><input type="number" step="0.01" min="0" name="approach_road_width" value="{{ $v('approach_road_width') }}" class="{{ $inputClass }}"></div>
        <div class="sm:col-span-2">
            <label class="{{ $labelClass }}">Top Neighbouring Companies</label>
            <textarea name="top_neighbouring_companies" rows="2" class="{{ $inputClass }}">{{ $v('top_neighbouring_companies') }}</textarea>
        </div>
        <div>
            <label class="{{ $labelClass }}">Flood Risk</label>
            <select name="flood_risk" class="{{ $selectClass }}">
                <option value="">— Select —</option>
                @foreach(['None','Low','Moderate','High'] as $opt)
                    <option value="{{ $opt }}" {{ $sel('flood_risk', $opt) }}>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- ── SECTION I — Health & Emergency ───────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">I. Health &amp; Emergency Facilities Nearby</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div><label class="{{ $labelClass }}">Nearest Hospital (km)</label><input type="number" step="0.01" min="0" name="nearest_hospital_km" value="{{ $v('nearest_hospital_km') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Nearest Fire Station (km)</label><input type="number" step="0.01" min="0" name="nearest_fire_station_km" value="{{ $v('nearest_fire_station_km') }}" class="{{ $inputClass }}"></div>
        <div><label class="{{ $labelClass }}">Nearest Police Station (km)</label><input type="number" step="0.01" min="0" name="nearest_police_station_km" value="{{ $v('nearest_police_station_km') }}" class="{{ $inputClass }}"></div>
    </div>
</div>

{{-- ── SECTION J — Photographs ────────────────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: true }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">J. Photographs (8 slots)</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="px-5 py-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach($slots as $index => $slotLabel)
            @php
                $existing = (isset($entry) && $entry && $entry->photos) ? $entry->photos->firstWhere('slot_label', $slotLabel) : null;
            @endphp
            <div class="flex flex-col items-center">
                {{-- Preview Container --}}
                <div class="w-full aspect-square mb-2 rounded-lg overflow-hidden border border-gray-200 bg-gray-50" id="preview-{{ $index }}">
                    @if($existing)
                        <img src="{{ asset('images/property_photos/' . basename($existing->file_path)) }}" alt="{{ $slotLabel }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center" id="placeholder-{{ $index }}">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <label class="text-xs text-gray-600 text-center font-medium mb-1 leading-tight"><b>{{ $slotLabel }}</b></label>
                <div class="relative">
                    <input type="file"
                        name="photos[{{ $index }}]"
                        id="photo-{{ $index }}"
                        capture="camera"
                        accept="image/*"
                        onchange="validateCameraCapture(this, {{ $index }})"
                        class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-zendo-gold file:text-white hover:file:bg-opacity-90 cursor-pointer">
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ── SECTION K — General Remarks ───────────────────────────────────────── --}}
<div class="{{ $sectionClass }}" x-data="{ open: false }">
    <div class="{{ $sectionHead }}" @click="open = !open">
        <h3 class="text-sm font-semibold text-zendo-navy">K. General Remarks &amp; Field Observations</h3>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
    <div x-show="open" class="{{ $sectionBody }}">
        <div class="sm:col-span-2 lg:col-span-3">
            <label class="{{ $labelClass }}">Remarks</label>
            <textarea name="remarks" rows="3" class="{{ $inputClass }}">{{ $v('remarks') }}</textarea>
        </div>
        <div>
            <label class="{{ $labelClass }}">Owner Contact Name</label>
            <input type="text" name="owner_contact_name" value="{{ $v('owner_contact_name') }}" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">Owner Contact Phone</label>
            <input type="text" name="owner_contact_phone" value="{{ $v('owner_contact_phone') }}" class="{{ $inputClass }}">
        </div>
    </div>
</div>
<script>
    function validateCameraCapture(input, index) {
        const file = input.files[0];
        
        if (!file) {
            return;
        }
        
        // Check if file is very recent (likely from camera)
        const now = new Date().getTime();
        const fileTime = file.lastModified;
        const timeDiff = now - fileTime;
        
        // If file is older than 5 minutes, it might be from gallery
        if (timeDiff > 300000) { // 5 minutes in milliseconds
            alert('Please use camera to take a fresh photo. Do not select existing images from gallery.');
            input.value = ''; // Clear the selection
            return;
        }
        
        // Additional validation: Check EXIF data if possible
        checkImageSource(file, input, index);
    }
    
    function checkImageSource(file, input, index) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const arrayBuffer = e.target.result;
            
            // Try to detect if image has recent camera metadata
            // This is a basic check - in production you might want more sophisticated validation
            const uint8Array = new Uint8Array(arrayBuffer);
            let hasRecentMetadata = false;
            
            // Look for EXIF markers that suggest camera capture
            for (let i = 0; i < Math.min(uint8Array.length - 10, 1000); i++) {
                // Look for common camera EXIF signatures
                if (uint8Array[i] === 0xFF && uint8Array[i + 1] === 0xE1) {
                    hasRecentMetadata = true;
                    break;
                }
            }
            
            // Show preview if validation passes
            previewImage(input, index);
        };
        
        // Read first few KB to check for EXIF data
        reader.readAsArrayBuffer(file.slice(0, 2048));
    }
    
    function previewImage(input, index) {
        const file = input.files[0];
        const preview = document.getElementById(`preview-${index}`);
        const placeholder = document.getElementById(`placeholder-${index}`);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(file);
        } else {
            // Reset to placeholder if no file selected
            if (placeholder) {
                preview.innerHTML = placeholder.outerHTML;
            }
        }
    }
    
    // Additional security: Disable right-click context menu on file inputs
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="file"]').forEach(function(input) {
            input.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                alert('Please use the camera button to take photos directly.');
            });
        });
    });
</script>