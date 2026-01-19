<x-app-layout>
    <div class="container-fluid">


        <div class="card border-0 mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Medicine Inventory</h4>
                        <p class="text-muted mb-0">Manage medicine catalog and stock</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pharmacy.medicines.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add Medicine
                        </a>
                        <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-package me-1"></i> Manage Batches
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Medicine Name</th>
                                <th>Generic Name</th>
                                <th>Manufacturer</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicines as $medicine)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $medicine->name }}</div>
                                        <div class="small text-muted">{{ $medicine->strength }}</div>
                                    </td>
                                    <td>{{ $medicine->generic_name ?? '-' }}</td>
                                    <td>{{ $medicine->manufacturer ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $medicine->dosage_form ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($medicine->price, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $medicine->status === 'active' ? 'success' : 'secondary' }}-subtle text-{{ $medicine->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($medicine->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm btn-light" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('pharmacy.medicines.edit', $medicine) }}">
                                                        <i class="ti ti-pencil me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('pharmacy.medicines.destroy', $medicine) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti ti-trash me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">No medicines found</div>
                                        <a href="{{ route('pharmacy.medicines.create') }}"
                                            class="btn btn-sm btn-primary mt-2">Add First Medicine</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $medicines->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
