<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    /**
     * Store a newly created inquiry in storage. Writes ONLY to leads table.
     */
    public function store(Request $request)
    {
        if ($request->has('property_id')) {
            return $this->storePropertyInquiry($request);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'property_type' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
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

                    $lead = Lead::create([
                        'division' => $division,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'stage' => 'new_lead',
                        'assigned_se_id' => $se?->id,
                        'qualification_notes' => $request->message,
                    ]);
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your inquiry! We will get back to you soon.',
                    'data' => $lead
                ], 201);
            }

            return back()->with('success', 'Thank you for your inquiry! We will get back to you soon.');

        } catch (\Exception $e) {
            \Log::error('Inquiry creation error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ], 500);
            }
            
            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * Store a property-specific inquiry. Writes ONLY to leads table.
     */
    public function storePropertyInquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'nullable|exists:properties,id',
            'property_entry_code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $wasLoggedInInitially = Auth::check();
            $userWasCreated = false;
            $lead = null;

            DB::transaction(function () use ($request, &$userWasCreated, &$lead) {
                // 1. User account registration/login
                $user = Auth::user();
                if (!$user) {
                    $existingUser = null;
                    if ($request->filled('phone')) {
                        $existingUser = User::where('phone', $request->phone)->first();
                    }
                    if (!$existingUser && $request->filled('email')) {
                        $existingUser = User::where('email', $request->email)->first();
                    }

                    if ($existingUser) {
                        $user = $existingUser;
                    } else {
                        $user = User::create([
                            'name' => $request->name,
                            'email' => $request->email ?: null,
                            'phone' => $request->phone,
                            'password' => Hash::make($request->phone ?: 'password123'),
                            'role' => 'user',
                            'email_verified_at' => $request->filled('email') ? now() : null,
                        ]);
                        $userWasCreated = true;
                    }

                    Auth::login($user);
                }

                // 2. Create Lead strictly in leads table
                $division = $this->determineDivision($request->property_id, null);

                $lead = Lead::where('phone', $request->phone)
                    ->where('division', $division)
                    ->first();

                if (!$lead) {
                    $se = User::getSalesExecutivesByDivision($division)->first();

                    $lead = Lead::create([
                        'division' => $division,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'property_id' => $request->property_id ?: null,
                        'stage' => 'new_lead',
                        'assigned_se_id' => $se?->id,
                        'qualification_notes' => $request->message,
                    ]);
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your inquiry! We will contact you shortly.',
                    'user_created' => $userWasCreated,
                    'logged_in' => Auth::check(),
                    'reload_required' => !$wasLoggedInInitially && Auth::check(),
                    'data' => $lead
                ], 200);
            }

            return back()->with('success', 'Thank you for your inquiry! We will get back to you soon.');

        } catch (\Exception $e) {
            \Log::error('Property inquiry error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ], 500);
            }
            
            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * Check if visitor has already submitted inquiry for a property
     */
    public function checkSubmission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid property ID'
            ], 422);
        }

        try {
            $division = $this->determineDivision($request->property_id, null);
            $user = Auth::user();

            $hasSubmitted = false;
            if ($user && $user->phone) {
                $hasSubmitted = Lead::where('phone', $user->phone)
                    ->where('division', $division)
                    ->where('property_id', $request->property_id)
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'has_submitted' => $hasSubmitted
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking submission status'
            ], 500);
        }
    }

    private function determineDivision(?int $propertyId = null, ?string $propertyType = null): string
    {
        if ($propertyId) {
            $property = Property::with('propertyType')->find($propertyId);
            if ($property) {
                $typeName = strtolower($property->propertyType?->name ?? '');
                $category = strtolower($property->propertyType?->category ?? '');
                if (str_contains($typeName, 'warehous') || str_contains($category, 'warehous')) {
                    return 'warehousing';
                }
                if (str_contains($typeName, 'comm') || str_contains($typeName, 'office') || str_contains($typeName, 'shop') || str_contains($category, 'comm')) {
                    return 'commercial';
                }
                return 'residential';
            }
        }

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
