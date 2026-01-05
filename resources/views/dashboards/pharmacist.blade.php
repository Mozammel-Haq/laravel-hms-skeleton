<x-app-layout>
                    <div class="content pb-0">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Pharmacist Dashboard</h4>
            <p>Pharmacy Operations</p>
        </div>
        <a href="{{ route('pharmacy.create') }}" class="btn btn-primary"><i class="ti ti-shopping-cart"></i> POS / New Sale</a>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Today's Sales</h5>
                    <h3>{{ \App\Models\PharmacySale::whereDate('sale_date', today())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Low Stock Items</h5>
                    <h3>0</h3> <!-- Need Inventory logic -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Sales</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Sales -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sale ID</th>
                                <th>Patient/Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic content here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
