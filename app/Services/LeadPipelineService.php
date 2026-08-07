<?php

namespace App\Services;

use App\Helpers\WorkingHours;
use App\Models\Lead;
use App\Models\LeadStageHistory;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Central service for creating / upserting a Lead entry whenever a
 * visitor submits any public-facing form (inquiry, property inquiry,
 * consultation).
 *
 * Key guarantees:
 *  - Wrapped in its own DB transaction.
 *  - Deduplicated by (phone, division) — silently skips if duplicate.
 *  - Division resolved from property FK when available, else from
 *    property_type string, else flagged needs_division_review = true.
 *  - Auto-assigns the least-loaded active SE in the division.
 *  - Sets the 4-working-hour contact SLA deadline on SE assignment.
 *  - Failure is swallowed + logged so public forms are NEVER blocked.
 */
class LeadPipelineService
{
    // ──────────────────────────────────────────────────────────────────────
    // Public entry points
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Called from InquiryController::store() — general inquiry form.
     * No property FK, division resolved from property_type string.
     */
    public function createFromGeneralInquiry(
        string  $name,
        string  $phone,
        ?string $email,
        ?string $propertyTypeString,
        ?string $message = null
    ): void {
        $division    = $this->divisionFromTypeString($propertyTypeString);
        $needsReview = $division === null;
        $division  ??= 'residential';

        $this->upsertLead(
            name:          $name,
            phone:         $phone,
            email:         $email,
            propertyId:    null,
            division:      $division,
            needsReview:   $needsReview,
            originTable:   'inquiries',
            qualification: $message,
        );
    }

    /**
     * Called from PropertyInquiryObserver::created() — property form.
     * Division resolved from property FK or PropertyEntry facility_type.
     */
    public function createFromPropertyInquiry(
        string  $name,
        string  $phone,
        ?string $email,
        ?int    $propertyId,
        ?string $propertyEntryCode = null,
        ?string $message = null
    ): void {
        // Try PropertyEntry first (new system), then Property (old system)
        if ($propertyEntryCode) {
            [$division, $needsReview] = $this->divisionFromPropertyEntry($propertyEntryCode);
        } else {
            [$division, $needsReview] = $this->divisionFromProperty($propertyId);
        }

        $this->upsertLead(
            name:          $name,
            phone:         $phone,
            email:         $email,
            propertyId:    $propertyId,
            division:      $division,
            needsReview:   $needsReview,
            originTable:   'property_inquiries',
            qualification: $message,
        );
    }

    /**
     * Called from ConsultationController::store() — consultation form.
     * Division resolved from property_type string + location hint.
     */
    public function createFromConsultation(
        string  $name,
        string  $phone,
        ?string $email,
        ?string $propertyTypeString,
        ?string $message = null,
        ?string $requirements = null
    ): void {
        $division    = $this->divisionFromTypeString($propertyTypeString);
        $needsReview = $division === null;
        $division  ??= 'residential';

        $notes = implode("\n", array_filter([$message, $requirements])) ?: null;

        $this->upsertLead(
            name:          $name,
            phone:         $phone,
            email:         $email,
            propertyId:    null,
            division:      $division,
            needsReview:   $needsReview,
            originTable:   'consultations',
            qualification: $notes,
        );
    }

    // ──────────────────────────────────────────────────────────────────────
    // Core upsert
    // ──────────────────────────────────────────────────────────────────────

    private function upsertLead(
        string  $name,
        string  $phone,
        ?string $email,
        ?int    $propertyId,
        string  $division,
        bool    $needsReview,
        string  $originTable,
        ?string $qualification = null
    ): void {
        try {
            DB::transaction(function () use (
                $name, $phone, $email, $propertyId,
                $division, $needsReview, $originTable, $qualification
            ) {
                // ── Deduplicate ───────────────────────────────────────────
                $existing = Lead::withTrashed()
                    ->where('phone', $phone)
                    ->where('division', $division)
                    ->first();

                if ($existing) {
                    // Lead already exists — update contact info if blank and return
                    $updates = [];
                    if (!$existing->email && $email)         $updates['email']      = $email;
                    if (!$existing->property_id && $propertyId) $updates['property_id'] = $propertyId;
                    if ($qualification && !$existing->qualification_notes)
                        $updates['qualification_notes'] = $qualification;
                    if ($updates) {
                        $existing->update($updates);
                    }
                    return;
                }

                // ── Create new lead ───────────────────────────────────────
                $lead = Lead::create([
                    'division'              => $division,
                    'name'                  => $name,
                    'phone'                 => $phone,
                    'email'                 => $email,
                    'property_id'           => $propertyId,
                    'stage'                 => 'new_lead',
                    'qualification_notes'   => $qualification,
                    'origin_table'          => $originTable,
                    'needs_division_review' => $needsReview,
                ]);

                // ── Auto-assign least-loaded SE ───────────────────────────
                $se = User::where('role', 'sales_executive')
                    ->where('division', $division)
                    ->where('is_active', true)
                    ->withCount([
                        'assignedLeadsSE as active_se_count' => fn($q) =>
                            $q->whereIn('stage', Lead::SE_STAGES)->whereNull('side_state'),
                    ])
                    ->orderBy('active_se_count')
                    ->first();

                if ($se) {
                    $due = WorkingHours::addWorkingHours(null, 4);
                    $lead->update([
                        'assigned_se_id'      => $se->id,
                        'se_assigned_at'      => now(),
                        'sla_contact_due_at'  => $due,
                        'sla_contact_breached'=> false,
                    ]);
                }
            });

        } catch (\Throwable $e) {
            // Never block the public form — log and move on
            Log::warning('LeadPipelineService: lead upsert failed', [
                'phone'        => $phone,
                'division'     => $division,
                'origin_table' => $originTable,
                'error'        => $e->getMessage(),
            ]);
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // Division resolution helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Resolve division from a PropertyEntry code (new warehouse system).
     * Returns [$division, $needsReview].
     */
    private function divisionFromPropertyEntry(?string $code): array
    {
        if (!$code) {
            return ['residential', true];
        }

        $entry = \App\Models\PropertyEntry::where('code', $code)->first();
        if (!$entry || !$entry->facility_type) {
            return ['residential', true];
        }

        $facilityType = strtolower($entry->facility_type);
        $division = $this->classifyType($facilityType, $facilityType);

        return $division
            ? [$division, false]
            : ['residential', true];
    }

    /**
     * Resolve division from a property FK.
     * Returns [$division, $needsReview].
     */
    private function divisionFromProperty(?int $propertyId): array
    {
        if (!$propertyId) {
            return ['residential', true];
        }

        $property = Property::with('propertyType')->find($propertyId);
        if (!$property) {
            return ['residential', true];
        }

        $name     = strtolower($property->propertyType?->name     ?? '');
        $category = strtolower($property->propertyType?->category ?? '');

        $division = $this->classifyType($name, $category);

        return $division
            ? [$division, false]
            : ['residential', true];
    }

    /**
     * Resolve division from a free-text property_type string.
     * Returns null if unresolvable (caller should flag needs_division_review).
     */
    private function divisionFromTypeString(?string $typeString): ?string
    {
        if (!$typeString) {
            return null;
        }

        return $this->classifyType(strtolower($typeString), strtolower($typeString));
    }

    /**
     * Classify a (name, category) pair into warehousing / residential / commercial.
     */
    private function classifyType(string $name, string $category): ?string
    {
        $haystack = $name . ' ' . $category;

        if (str_contains($haystack, 'warehouse') ||
            str_contains($haystack, 'industrial') ||
            str_contains($haystack, 'storage')    ||
            str_contains($haystack, 'logistics')) {
            return 'warehousing';
        }

        if (str_contains($haystack, 'commercial') ||
            str_contains($haystack, 'office')     ||
            str_contains($haystack, 'retail')     ||
            str_contains($haystack, 'shop')) {
            return 'commercial';
        }

        if (str_contains($haystack, 'residential') ||
            str_contains($haystack, 'apartment')   ||
            str_contains($haystack, 'villa')        ||
            str_contains($haystack, 'flat')         ||
            str_contains($haystack, 'house')        ||
            str_contains($haystack, 'plot')) {
            return 'residential';
        }

        return null;
    }
}
