<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DemographicsTrendSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Date' => $item->date,
                'New Patients' => $item->total,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'New Patients'];
    }

    public function title(): string
    {
        return 'New Patients Trend';
    }
}
