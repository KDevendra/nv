<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    /**
     * Store a newly created inquiry in storage.
     */
    public function store(Request $request)
    {
        // Check if this is a property-specific inquiry
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
            // Check if this is an AJAX request
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
            $inquiry = Inquiry::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'property_type' => $request->property_type,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your inquiry! We will get back to you soon.',
                    'data' => $inquiry
                ], 201);
            }

            return back()->with('success', 'Thank you for your inquiry! We will get back to you soon.');

        } catch (\Exception $e) {
            // Check if this is an AJAX request
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
     * Store a property-specific inquiry.
     */
    private function storePropertyInquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'nullable|exists:properties,id',
            'source' => 'nullable|string|max:50',
            'source_code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            // Check if this is an AJAX request
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
            // Get the current page visit ID from session
            $pageVisitId = $request->session()->get('current_page_visit_id');
            
            // Check if this visitor has already submitted an inquiry
            $existingInquiryQuery = \App\Models\PropertyInquiry::query();
            
            // Check by property_id if provided
            if ($request->filled('property_id')) {
                $existingInquiryQuery->where('property_id', $request->property_id);
            } else {
                // For property entries, check by source_code
                $existingInquiryQuery->where('message', 'like', '%' . $request->source_code . '%');
            }
            
            $existingInquiry = $existingInquiryQuery
                ->where(function($query) use ($request, $pageVisitId) {
                    $query->where('ip_address', $request->ip());
                    if ($pageVisitId) {
                        $query->orWhere('page_visit_id', $pageVisitId);
                    }
                })
                ->where('created_at', '>=', now()->subHours(24)) // Check last 24 hours
                ->exists();
            
            if ($existingInquiry) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already submitted an inquiry for this property.',
                        'already_submitted' => true
                    ], 200);
                }
                return back()->with('info', 'You have already submitted an inquiry for this property.');
            }
            
            \App\Models\PropertyInquiry::create([
                'property_id' => $request->property_id, // Will be NULL for property entries
                'page_visit_id' => $pageVisitId,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'message' => $request->message,
                'inquiry_type' => $request->input('inquiry_type', 'call_back'),
                'status' => 'pending',
                'ip_address' => $request->ip()
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your inquiry! We will contact you shortly.'
                ], 200);
            }

            return back()->with('success', 'Thank you for your inquiry! We will get back to you soon.');

        } catch (\Exception $e) {
            \Log::error('Property inquiry error: ' . $e->getMessage());
            
            // Check if this is an AJAX request
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
            $pageVisitId = $request->session()->get('current_page_visit_id');
            
            $hasSubmitted = \App\Models\PropertyInquiry::where('property_id', $request->property_id)
                ->where(function($query) use ($request, $pageVisitId) {
                    $query->where('ip_address', $request->ip());
                    if ($pageVisitId) {
                        $query->orWhere('page_visit_id', $pageVisitId);
                    }
                })
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

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
}
