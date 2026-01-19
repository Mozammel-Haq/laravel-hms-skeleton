<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class IncomeTrendSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $trend;

    public function __construct($trend)
    {
        $this->trend = $trend;
    }

    public function collection()
    {
        return $this->trend->map(function ($item) {
            return [
                'Date' => $item->date,
                'Income' => $item->total,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Income'];
    }

    public function title(): string
    {
        return 'Income Trend';
    }
}
