<?php

namespace App\Http\Controllers\Owner\PropertyEntry;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\PropertyEntry\ApartmentFlatStudioRequest;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryLog;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyFieldConfig;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ApartmentFlatStudioController extends Controller
{
    private const PHOTO_SLOTS = [
        0 => 'Interior / living room',
        1 => 'Bedrooms',
        2 => 'Kitchen & Dining',
        3 => 'Bathrooms',
        4 => 'Balcony / View',
        5 => 'Exterior / Tower facade',
        6 => 'Clubhouse / Amenities',
        7 => 'Floor Plan / Layout PDF or Image',
    ];

    public function index(Request $request): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $userId = auth()->id();
        $query = PropertyEntry::ofType('apartment_flat_studio')
            ->where('field_officer_id', $userId);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $entries = $query->latest()->paginate(15)->appends($request->query());

        $counters = [
            'total'     => PropertyEntry::ofType('apartment_flat_studio')->where('field_officer_id', $userId)->count(),
            'draft'     => PropertyEntry::ofType('apartment_flat_studio')->where('field_officer_id', $userId)->where('status', 'draft')->count(),
            'submitted' => PropertyEntry::ofType('apartment_flat_studio')->where('field_officer_id', $userId)->where('status', 'submitted')->count(),
            'verified'  => PropertyEntry::ofType('apartment_flat_studio')->where('field_officer_id', $userId)->where('status', 'verified')->count(),
            'recheck'   => PropertyEntry::ofType('apartment_flat_studio')->where('field_officer_id', $userId)->where('status', 'recheck')->count(),
            'rejected'  => PropertyEntry::ofType('apartment_flat_studio')->where('field_officer_id', $userId)->where('status', 'rejected')->count(),
        ];

        return view('owner.properties.index', compact('entries', 'counters'));
    }

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $property = null;
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = [];
        $propertyType = 'apartment_flat_studio';

        return view('owner.properties.apartment-flat-studio.create', compact('property', 'slots', 'fieldConfigs', 'fieldRemarks', 'propertyType'));
    }

    public function store(ApartmentFlatStudioRequest $request): RedirectResponse
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        abort_if(auth()->user()->role !== 'owner', 403);

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');
        $data = $request->validated();

        $data = $this->calculateDerivedFields($data);

        $entry = PropertyEntry::create(array_merge($data, [
            'property_type'    => 'apartment_flat_studio',
            'field_officer_id' => auth()->id(),
            'status'           => $isDraft ? 'draft' : 'submitted',
            'submitted_at'     => $isDraft ? null : now(),
            'area_unit'        => $request->input('area_unit', 'sq_ft'),
        ]));

        $this->handlePhotos($entry, $request);

        if ($isDraft) {
            return redirect()->route('owner.properties.apartment-flat-studio.edit', $entry)
                ->with('success', 'Draft saved successfully.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        PropertyEntryLog::logAction($entry, 'submitted', null, 'submitted');

        return redirect()->route('owner.dashboard')
            ->with('success', 'Apartment/Flat/Studio property listing submitted successfully. Code: ' . $entry->code);
    }

    public function show(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        abort_if($property->property_type !== 'apartment_flat_studio', 404);

        $property->load('photos');
        $slots = self::PHOTO_SLOTS;

        return view('owner.properties.show', compact('property', 'slots'));
    }

    public function edit(PropertyEntry $property): View|RedirectResponse
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        abort_if($property->property_type !== 'apartment_flat_studio', 404);

        if (!$property->isEditable()) {
            return redirect()->route('owner.properties.show', $property)
                ->with('error', 'This property entry cannot be edited.');
        }

        $property->load(['photos', 'fieldReviews']);
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();

        $correctFields = $property->fieldReviews->where('is_correct', true)->pluck('field_name')->toArray();
        $incorrectFields = $property->fieldReviews->where('is_correct', false)->pluck('field_name')->toArray();
        $fieldRemarks = $property->fieldReviews->whereNotNull('remark')->pluck('remark', 'field_name')->toArray();
        $propertyType = 'apartment_flat_studio';

        return view('owner.properties.apartment-flat-studio.create', compact(
            'property', 'slots', 'fieldConfigs', 'correctFields', 'incorrectFields', 'fieldRemarks', 'propertyType'
        ));
    }

    public function update(ApartmentFlatStudioRequest $request, PropertyEntry $property): RedirectResponse
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        abort_if($property->property_type !== 'apartment_flat_studio', 404);

        if (!$property->isEditable()) {
            return redirect()->route('owner.properties.show', $property)
                ->with('error', 'This property entry cannot be edited.');
        }

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');
        $data = $request->validated();

        $data = $this->calculateDerivedFields($data);

        if ($isDraft) {
            $property->update($data);
            $this->handlePhotos($property, $request);

            return redirect()->route('owner.properties.apartment-flat-studio.edit', $property)
                ->with('success', 'Draft saved successfully.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        $property->update(array_merge($data, [
            'status'                => 'submitted',
            'submitted_at'          => now(),
            'supply_head_viewed_at' => null,
        ]));

        $this->handlePhotos($property, $request);

        PropertyEntryLog::logAction($property, 'resubmitted', null, 'submitted');

        return redirect()->route('owner.dashboard')
            ->with('success', 'Apartment/Flat/Studio listing updated and submitted successfully. Code: ' . $property->code);
    }

    private function calculateDerivedFields(array $data): array
    {
        // Calculate price_per_sqft
        if (!empty($data['expected_sale_price']) && !empty($data['carpet_area']) && $data['carpet_area'] > 0) {
            $data['price_per_sqft'] = round($data['expected_sale_price'] / $data['carpet_area'], 2);
        }

        // Calculate rent_range_band
        if (!empty($data['expected_rent'])) {
            $data['rent_range_band'] = $this->formatBand($data['expected_rent']);
        }

        // Calculate sale_price_band
        if (!empty($data['expected_sale_price'])) {
            $data['sale_price_band'] = $this->formatBand($data['expected_sale_price']);
        }

        // Calculate rental_income_band
        if (!empty($data['current_monthly_rent_received'])) {
            $data['rental_income_band'] = $this->formatBand($data['current_monthly_rent_received']);
        }

        // Calculate rental_yield_roi
        if (!empty($data['expected_sale_price']) && !empty($data['current_monthly_rent_received']) && $data['expected_sale_price'] > 0) {
            $data['rental_yield_roi'] = round(($data['current_monthly_rent_received'] * 12 / $data['expected_sale_price']) * 100, 1);
        }

        $data['field_officer_name'] = auth()->user()?->name ?? 'System Officer';
        $data['field_verified'] = false;
        $data['inspection_submission_date'] = $data['inspection_submission_date'] ?? now()->format('Y-m-d');

        return $data;
    }

    private function formatBand(float|int $amount): ?string
    {
        if ($amount <= 0) {
            return null;
        }
        if ($amount < 100000) {
            return '₹' . number_format($amount / 1000, 0) . 'K';
        }
        if ($amount < 10000000) {
            return '₹' . number_format($amount / 100000, 2) . ' Lac';
        }
        return '₹' . number_format($amount / 10000000, 2) . ' Cr';
    }

    private function handlePhotos(PropertyEntry $entry, Request $request): void
    {
        $manager = new ImageManager(new Driver());

        for ($i = 0; $i < 8; $i++) {
            $inputName = "photo_{$i}";
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $slotLabel = self::PHOTO_SLOTS[$i] ?? "Photo {$i}";

                $filename = 'prop_' . $entry->id . '_slot' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('images/property_photos');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $img = $manager->read($file->getRealPath());
                    $img->scale(width: 1200);
                    $img->save($destinationPath . '/' . $filename, quality: 80);
                } else {
                    $file->move($destinationPath, $filename);
                }

                $existingPhoto = PropertyEntryPhoto::where('property_entry_id', $entry->id)
                    ->where('slot_label', $slotLabel)
                    ->first();

                if ($existingPhoto) {
                    if ($existingPhoto->file_path && file_exists(public_path($existingPhoto->file_path))) {
                        @unlink(public_path($existingPhoto->file_path));
                    }
                    $existingPhoto->update([
                        'file_path' => 'images/property_photos/' . $filename,
                    ]);
                } else {
                    PropertyEntryPhoto::create([
                        'property_entry_id' => $entry->id,
                        'slot_label'        => $slotLabel,
                        'file_path'         => 'images/property_photos/' . $filename,
                    ]);
                }
            }
        }
    }
}
