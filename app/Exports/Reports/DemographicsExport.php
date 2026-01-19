<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DemographicsExport implements WithMultipleSheets
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
            new DemographicsSummarySheet($this->data),
            new DemographicsTrendSheet($this->data['newPatients']),
        ];
    }
}
