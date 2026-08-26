<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Store a newly created consultation in storage. Writes ONLY to leads table.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:255',
            'pincode'      => 'required|string|max:10',
            'property_type'=> 'nullable|string|max:100',
            'location'     => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:100',
            'message'      => 'nullable|string|max:1000',
            'requirements' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $lead = null;
            DB::transaction(function () use ($request, &$lead) {
                $division = $this->determineDivision(null, $request->property_type);

                $lead = Lead::where('phone', $request->phone)
                    ->where('division', $division)
                    ->first();

                if (!$lead) {
                    $se = User::getSalesExecutivesByDivision($division)->first();

                    $notes = implode("\n", array_filter([
                        $request->pincode ? "Pin Code: {$request->pincode}" : null,
                        $request->location ? "Location: {$request->location}" : null,
                        $request->budget_range ? "Budget: {$request->budget_range}" : null,
                        $request->requirements ? "Requirements: {$request->requirements}" : null,
                        $request->message ? "Message: {$request->message}" : null,
                    ]));

                    $lead = Lead::create([
                        'division' => $division,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'pincode' => $request->pincode,
                        'stage' => 'new_lead',
                        'assigned_se_id' => $se?->id,
                        'qualification_notes' => $notes,
                    ]);
                } else {
                    if ($request->filled('pincode')) {
                        $lead->pincode = $request->pincode;
                        $lead->save();
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your consultation request! We will get back to you soon.',
                'data'    => $lead
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Consultation submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    private function determineDivision(?int $propertyId = null, ?string $propertyType = null): string
    {
        if ($propertyType) {
            $str = strtolower($propertyType);
            if (str_contains($str, 'warehous')) {
                return 'warehousing';
            }
            if (str_contains($str, 'comm') || str_contains($str, 'office') || str_contains($str, 'shop')) {
                return 'commercial';
            }
        }

        return 'residential';
    }
}
