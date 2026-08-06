<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Helpers\WorkingHours;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Artisan command: leads:migrate-legacy
 *
 * Reads existing inquiries, property_inquiries, and consultations tables,
 * maps them to the unified leads table, and deduplicates by (phone, division).
 *
 * Rows where division cannot be resolved are inserted with
 * needs_division_review = true.
 *
 * Legacy tables remain untouched (read-only).
 */
class MigrateLegacyLeads extends Command
{
    protected $signature   = 'leads:migrate-legacy
                                {--dry-run : Preview counts without writing}
                                {--chunk=200 : Records per batch}';

    protected $description = 'Migrate legacy inquiry/consultation records into the leads table.';

    private int $inserted  = 0;
    private int $skipped   = 0; // already exists by (phone, division)
    private int $flagged   = 0; // needs_division_review

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $chunk  = (int) $this->option('chunk');

        $this->info('Starting legacy lead migration' . ($dryRun ? ' [DRY RUN]' : '') . '...');

        $this->migrateInquiries($dryRun, $chunk);
        $this->migratePropertyInquiries($dryRun, $chunk);
        $this->migrateConsultations($dryRun, $chunk);

        $this->newLine();
        $this->table(
            ['Result', 'Count'],
            [
                ['Inserted',              $this->inserted],
                ['Skipped (duplicate)',   $this->skipped],
                ['Flagged (need review)', $this->flagged],
            ]
        );

        $this->info('Migration complete.');
        return self::SUCCESS;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Per-table migration
    // ──────────────────────────────────────────────────────────────────────

    private function migrateInquiries(bool $dryRun, int $chunk): void
    {
        $this->info('Processing inquiries...');
        $bar = $this->output->createProgressBar(DB::table('inquiries')->count());
        $bar->start();

        DB::table('inquiries')->orderBy('id')->chunk($chunk, function ($rows) use ($dryRun, $bar) {
            foreach ($rows as $row) {
                // General inquiries have no property_type → map to residential as default, but flag
                $division = $this->resolveInquiryDivision($row->property_type ?? null);
                $needsReview = ($division === null);
                $division = $division ?? 'residential';

                $this->upsertLead([
                    'division'            => $division,
                    'name'                => $row->name,
                    'phone'               => $row->phone,
                    'email'               => $row->email ?? null,
                    'origin_table'        => 'inquiries',
                    'origin_id'           => $row->id,
                    'needs_division_review' => $needsReview,
                ], $dryRun);

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
    }

    private function migratePropertyInquiries(bool $dryRun, int $chunk): void
    {
        $this->info('Processing property_inquiries...');
        $bar = $this->output->createProgressBar(DB::table('property_inquiries')->count());
        $bar->start();

        DB::table('property_inquiries')->orderBy('id')->chunk($chunk, function ($rows) use ($dryRun, $bar) {
            foreach ($rows as $row) {
                $division    = $this->resolveDivisionFromProperty($row->property_id ?? null);
                $needsReview = ($division === null);
                $division    = $division ?? 'residential';

                $this->upsertLead([
                    'division'            => $division,
                    'name'                => $row->name,
                    'phone'               => $row->phone,
                    'email'               => $row->email ?? null,
                    'property_id'         => $row->property_id ?? null,
                    'origin_table'        => 'property_inquiries',
                    'origin_id'           => $row->id,
                    'needs_division_review' => $needsReview,
                ], $dryRun);

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
    }

    private function migrateConsultations(bool $dryRun, int $chunk): void
    {
        $this->info('Processing consultations...');
        $bar = $this->output->createProgressBar(DB::table('consultations')->count());
        $bar->start();

        DB::table('consultations')->orderBy('id')->chunk($chunk, function ($rows) use ($dryRun, $bar) {
            foreach ($rows as $row) {
                $division    = $this->resolveConsultationDivision($row);
                $needsReview = ($division === null);
                $division    = $division ?? 'residential';

                $this->upsertLead([
                    'division'              => $division,
                    'name'                  => $row->name,
                    'phone'                 => $row->phone,
                    'email'                 => $row->email ?? null,
                    'qualification_notes'   => $row->message ?? null,
                    'origin_table'          => 'consultations',
                    'origin_id'             => $row->id,
                    'needs_division_review' => $needsReview,
                ], $dryRun);

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
    }

    // ──────────────────────────────────────────────────────────────────────
    // Upsert with dedup
    // ──────────────────────────────────────────────────────────────────────

    private function upsertLead(array $data, bool $dryRun): void
    {
        $phone    = $data['phone'] ?? '';
        $division = $data['division'];

        if (empty($phone)) {
            $this->skipped++;
            return;
        }

        // Deduplicate by (phone, division)
        $exists = DB::table('leads')
            ->where('phone', $phone)
            ->where('division', $division)
            ->withTrashed()
            ->exists();

        if ($exists) {
            $this->skipped++;
            return;
        }

        if ($data['needs_division_review']) {
            $this->flagged++;
        }

        if (!$dryRun) {
            DB::table('leads')->insert(array_merge([
                'stage'      => 'new_lead',
                'created_at' => now(),
                'updated_at' => now(),
            ], $data));
        }

        $this->inserted++;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Division resolution helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Map a legacy property_type string to a division.
     * Returns null if unresolvable.
     */
    private function resolveInquiryDivision(?string $propertyType): ?string
    {
        if (empty($propertyType)) {
            return null;
        }

        $type = strtolower(trim($propertyType));

        if (str_contains($type, 'warehouse') || str_contains($type, 'storage') || str_contains($type, 'industrial')) {
            return 'warehousing';
        }

        if (str_contains($type, 'commercial') || str_contains($type, 'office') || str_contains($type, 'shop') || str_contains($type, 'retail')) {
            return 'commercial';
        }

        if (str_contains($type, 'residential') || str_contains($type, 'apartment') || str_contains($type, 'villa') || str_contains($type, 'plot') || str_contains($type, 'house') || str_contains($type, 'flat')) {
            return 'residential';
        }

        return null;
    }

    /**
     * Look up the property's property_type and map to a division.
     */
    private function resolveDivisionFromProperty(?int $propertyId): ?string
    {
        if (!$propertyId) {
            return null;
        }

        $property = DB::table('properties')
            ->join('property_types', 'properties.property_type_id', '=', 'property_types.id')
            ->where('properties.id', $propertyId)
            ->select('property_types.name as type_name', 'property_types.category')
            ->first();

        if (!$property) {
            return null;
        }

        $category = strtolower($property->category ?? '');
        $name     = strtolower($property->type_name ?? '');

        if (str_contains($category, 'warehouse') || str_contains($name, 'warehouse') || str_contains($name, 'industrial')) {
            return 'warehousing';
        }

        if (str_contains($category, 'commercial') || str_contains($name, 'commercial') || str_contains($name, 'office')) {
            return 'commercial';
        }

        if (str_contains($category, 'residential') || str_contains($name, 'residential')) {
            return 'residential';
        }

        return null;
    }

    /**
     * Resolve division from a consultation row.
     * Checks property_type, then inquiry_type fields.
     */
    private function resolveConsultationDivision(object $row): ?string
    {
        $division = $this->resolveInquiryDivision($row->property_type ?? null);
        if ($division) {
            return $division;
        }

        $inquiryType = strtolower($row->inquiry_type ?? '');
        if (str_contains($inquiryType, 'warehouse')) {
            return 'warehousing';
        }
        if (str_contains($inquiryType, 'commercial')) {
            return 'commercial';
        }
        if (str_contains($inquiryType, 'residential') || str_contains($inquiryType, 'consultation')) {
            return 'residential';
        }

        return null;
    }
}
