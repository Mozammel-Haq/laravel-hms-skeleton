<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div
                    class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-3 py-2 rounded shadow-sm">
                    <div>
                        <h5 class="fw-bold mb-0 text-primary">Intelligent Patient Search</h5>
                        <p class="mb-0 small opacity-75">Search across all clinics to find existing records.</p>
                    </div>
                    <i class="ti ti-search fs-4"></i>
                </div>

                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="ti ti-search text-muted fs-14"></i></span>
                            <input type="text" id="global-search-input" class="form-control border-start-0 ps-0"
                                placeholder="Name, Phone, NID..." autofocus>
                            <button class="btn btn-primary px-3" id="search-btn">Search</button>
                        </div>
                    </div>
                </div>

                <div id="search-results-container" class="d-none">
                    <h5 class="mb-3 text-muted fw-bold d-flex align-items-center">
                        <i class="ti ti-list me-2"></i> Search Results
                        <span id="result-count" class="badge bg-primary ms-2 fs-12">0</span>
                    </h5>
                    <div class="table-responsive rounded border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Contact Info</th>
                                    <th>Identity</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="results-body">
                                <!-- Results will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="no-results" class="text-center py-5 d-none">
                    <div class="mb-3">
                        <i class="ti ti-user-off fs-1 text-muted opacity-50"></i>
                    </div>
                    <h5 class="text-muted">No patient found matching your search.</h5>
                    <p class="text-muted small">Try a different phone number or NID.</p>
                    <a href="{{ route('patients.create') }}" class="btn btn-outline-primary mt-2">
                        <i class="ti ti-plus me-1"></i> Create New Patient
                    </a>
                </div>

                <div id="search-loader" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Searching database...</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('global-search-input');
                const searchBtn = document.getElementById('search-btn');
                const resultsContainer = document.getElementById('search-results-container');
                const resultsBody = document.getElementById('results-body');
                const noResults = document.getElementById('no-results');
                const loader = document.getElementById('search-loader');
                const resultCount = document.getElementById('result-count');

                async function performSearch() {
                    const term = searchInput.value.trim();
                    if (term.length < 3) return;

                    // Reset UI
                    resultsContainer.classList.add('d-none');
                    noResults.classList.add('d-none');
                    loader.classList.remove('d-none');
                    resultsBody.innerHTML = '';

                    try {
                        const response = await fetch(
                            `{{ route('patients.search-api') }}?term=${encodeURIComponent(term)}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                        const data = await response.json();

                        loader.classList.add('d-none');

                        if (data.results && data.results.length > 0) {
                            resultsContainer.classList.remove('d-none');
                            resultCount.innerText = data.results.length;

                            data.results.forEach(item => {
                                const p = item.patient;
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:35px;height:35px">
                                            ${p.name.charAt(0)}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">${p.name}</div>
                                            <div class="small text-muted">${p.patient_code || 'N/A'}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i class="ti ti-phone me-1"></i> ${p.phone}</div>
                                    <div class="small text-muted"><i class="ti ti-mail me-1"></i> ${p.email || 'N/A'}</div>
                                </td>
                                <td>
                                    <div class="small">NID: ${p.nid_number || '-'}</div>
                                    <div class="small">Gender: ${p.gender}</div>
                                </td>
                                <td>
                                    ${p.is_linked
                                        ? '<span class="badge bg-success-subtle text-success text-capitalize">Linked to Clinic</span>'
                                        : '<span class="badge bg-warning-subtle text-warning text-capitalize">Other Clinic</span>'}
                                </td>
                                <td class="text-end">
                                    ${p.is_linked
                                        ? `<a href="{{ url('patients') }}/${p.id}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i> View Profile
                                                   </a>`
                                        : `<form action="{{ url('global-patient-link') }}/${p.id}" method="POST" class="d-inline">
                                                         @csrf
                                                         <button type="submit" class="btn btn-sm btn-primary">
                                                             <i class="ti ti-link"></i> Link to My Clinic
                                                         </button>
                                                        </form>`}
                                </td>
                            `;
                                resultsBody.appendChild(tr);
                            });
                        } else {
                            noResults.classList.remove('d-none');
                        }
                    } catch (error) {
                        console.error('Search failed:', error);
                        loader.classList.add('d-none');
                        alert('An error occurred during search.');
                    }
                }

                searchBtn.addEventListener('click', performSearch);
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') performSearch();
                });
            });
        </script>
    @endpush
</x-app-layout>
