<?php

namespace App\Console\Commands;

use App\Exports\Reports\PharmacyProfitExport;
use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class TestPharmacyExport extends Command
{
    protected $signature = 'test:pharmacy-export';

    protected $description = 'Test pharmacy export with missing medicine';

    public function handle()
    {
        $this->info('Starting export test...');

        // Ensure we have a clinic
        $clinic = Clinic::first();
        if (! $clinic) {
            $clinic = Clinic::create([
                'name' => 'Test Clinic',
                'code' => 'TEST',
                'address_line_1' => 'Test Address',
                'city' => 'Test City',
                'country' => 'Test Country',
                'timezone' => 'UTC',
                'currency' => 'USD',
            ]);
        }

        // Create a sale with an item that has no medicine (simulate deleted medicine)
        // We can't easily set medicine_id to non-existent ID due to FK constraints usually,
        // unless FK is not enforced or we delete the medicine after.

        $medicine = Medicine::create([
            'clinic_id' => $clinic->id,
            'name' => 'Temp Medicine',
            'generic_name' => 'Temp Generic',
            'sku' => 'TMP-001',
            'price' => 10.00,
            'cost_price' => 5.00,
            'stock_quantity' => 100,
        ]);

        $patient = Patient::first() ?? Patient::create([
            'clinic_id' => $clinic->id,
            'name' => 'Test Patient',
            'patient_code' => 'P-TEST',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'contact_number' => '1234567890',
        ]);

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $sale = PharmacySale::create([
            'clinic_id' => $clinic->id,
            'prescription_id' => 99999, // Dummy
            'patient_id' => $patient->id,
            'sale_date' => now(),
            'total_amount' => 20.00,
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $item = PharmacySaleItem::create([
            'pharmacy_sale_id' => $sale->id,
            'medicine_id' => $medicine->id,
            'quantity' => 2,
            'unit_price' => 10.00,
            'unit_cost' => 5.00,
        ]);

        // Now delete the medicine to simulate the issue
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        $medicine->forceDelete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Refresh item relation
        $item->refresh();

        // Prepare data for export
        $data = [
            'saleItems' => collect([$item]),
            'totalRevenue' => 20.00,
            'totalCost' => 10.00,
            'netProfit' => 10.00,
            'startDate' => now()->startOfDay(),
            'endDate' => now()->endOfDay(),
        ];

        try {
            Excel::store(new PharmacyProfitExport($data), 'test_pharmacy_export.xlsx');
            $this->info('Export successful!');
        } catch (\Exception $e) {
            $this->error('Export failed: '.$e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
