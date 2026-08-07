<?php

namespace App\Observers;

use App\Models\PropertyInquiry;
use App\Services\LeadPipelineService;
use Illuminate\Support\Facades\Log;

/**
 * Fires after any PropertyInquiry record is created — from ANY source
 * (public form, admin panel, API, seeder, etc.) — and upserts a Lead
 * entry in the CRM pipeline.
 *
 * Using the `created` event (not `creating`) so the PI row already has
 * its ID and all FK relationships populated before we read them.
 */
class PropertyInquiryObserver
{
    public function created(PropertyInquiry $inquiry): void
    {
        // Skip seeded / admin-created records that have no phone
        // (pipeline cannot operate without a phone number)
        if (empty($inquiry->phone)) {
            return;
        }

        try {
            app(LeadPipelineService::class)->createFromPropertyInquiry(
                name:       $inquiry->name,
                phone:      $inquiry->phone,
                email:      $inquiry->email,
                propertyId: $inquiry->property_id,
                message:    $inquiry->message,
            );
        } catch (\Throwable $e) {
            // Never let an observer error surface to the end user
            Log::warning('PropertyInquiryObserver: lead upsert failed', [
                'property_inquiry_id' => $inquiry->id,
                'phone'               => $inquiry->phone,
                'error'               => $e->getMessage(),
            ]);
        }
    }
}
