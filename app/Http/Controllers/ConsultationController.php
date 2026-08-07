<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\LeadPipelineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    /**
     * Store a newly created consultation in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email|max:255',
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
            $consultation = Consultation::create([
                'inquiry_type' => 'consultation',
                'source'       => 'website',
                'name'         => $request->name,
                'phone'        => $request->phone,
                'email'        => $request->email,
                'property_type'=> $request->property_type,
                'location'     => $request->location,
                'budget_range' => $request->budget_range,
                'message'      => $request->message,
                'requirements' => $request->requirements,
                'status'       => 'pending',
                'priority'     => 'medium',
            ]);

            // ── Feed the CRM pipeline ─────────────────────────────────────
            app(LeadPipelineService::class)->createFromConsultation(
                name:               $request->name,
                phone:              $request->phone,
                email:              $request->email,
                propertyTypeString: $request->property_type,
                message:            $request->message,
                requirements:       $request->requirements,
            );

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your consultation request! We will get back to you soon.',
                'data'    => $consultation
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
