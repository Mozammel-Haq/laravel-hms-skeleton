<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SummaryExport implements WithMultipleSheets
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new SummarySheet($this->data),
            new IncomeTrendSheet($this->data['incomeTrend']),
        ];
    }
}
