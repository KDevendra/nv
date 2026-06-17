<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PropertyEntriesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected Collection $entries;
    protected int $rowIndex = 0;

    public function __construct(Collection $entries)
    {
        $this->entries = $entries;
    }

    public function collection(): Collection
    {
        return $this->entries;
    }

    public function title(): string
    {
        return 'Property Entry Report';
    }

    public function headings(): array
    {
        return [
            '#',
            'Entry Code',
            'Supply Head',
            'Field Officer',
            'Facility Type',
            'Nearest City',
            'Village / Town / District',
            'Tenure',
            'Plot Area (sq ft)',
            'Built-up Area (sq ft)',
            'Clear Height — Highest (ft)',
            'No. of Floors',
            'Dock Doors',
            'Dock Type',
            'Flooring Type',
            'Power Sanctioned (KVA)',
            'Water Source',
            'Fire Fighting System',
            'Deal Type',
            'Expected Rent (₹/sqft/mo)',
            'Expected Sale Price (₹)',
            'Available From',
            'Approach Road Width (ft)',
            'Flood Risk',
            'Owner Name',
            'Owner Phone',
            'Status',
            'Submitted At',
            'Reviewed At',
            'Remarks',
        ];
    }

    public function map($entry): array
    {
        $this->rowIndex++;

        return [
            $this->rowIndex,
            $entry->code,
            $entry->supplyHead?->name ?? '—',
            $entry->fieldOfficer?->name ?? '—',
            $entry->facility_type ?? '—',
            $entry->nearest_city ?? '—',
            $entry->village_town_district ?? '—',
            $entry->tenure ?? '—',
            $entry->plot_area,
            $entry->built_up_area,
            $entry->clear_height_highest,
            $entry->number_of_floors,
            $entry->dock_door_count,
            $entry->dock_type ?? '—',
            $entry->flooring_type ?? '—',
            $entry->power_sanctioned_kva,
            $entry->water_source ?? '—',
            $entry->fire_fighting_system ?? '—',
            $entry->deal_type ?? '—',
            $entry->expected_rent,
            $entry->expected_sale_price,
            $entry->available_from?->format('d M Y'),
            $entry->approach_road_width,
            $entry->flood_risk ?? '—',
            $entry->owner_contact_name ?? '—',
            $entry->owner_contact_phone ?? '—',
            ucfirst($entry->status),
            $entry->submitted_at?->format('d M Y, H:i'),
            $entry->reviewed_at?->format('d M Y, H:i'),
            $entry->remarks ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Row 1 = column headings: dark navy background, white bold text
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0B2C3D'],
                ],
            ],
        ];
    }
}
