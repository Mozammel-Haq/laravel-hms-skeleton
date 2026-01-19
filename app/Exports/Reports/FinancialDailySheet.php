<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FinancialDailySheet implements FromCollection, WithHeadings, WithTitle
{
    protected $daily;

    public function __construct($daily)
    {
        $this->daily = $daily;
    }

    public function collection()
    {
        return $this->daily->map(function ($item) {
            return [
                'Date' => $item->date,
                'Total Revenue' => $item->total,
                'Paid Amount' => $item->paid_amount,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Total Revenue', 'Paid Amount'];
    }

    public function title(): string
    {
        return 'Daily Revenue';
    }
}
