@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="mb-1">Core Location</h3>
            <p class="text-muted mb-0">Manage countries, states, districts, blocks and cities.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationModal">Add Location</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>Countries</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>States</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $country)
                                    <tr>
                                        <td>{{ $country->country_name }}</td>
                                        <td>{{ $country->country_code ?? '-' }}</td>
                                        <td>{{ $country->states_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">No countries found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>States</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>Districts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($states as $state)
                                    <tr>
                                        <td>{{ $state->state_name }}</td>
                                        <td>{{ $state->country->country_name ?? '-' }}</td>
                                        <td>{{ $state->districts->count() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">No states found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>Districts</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>State</th>
                                    <th>Blocks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($districts as $district)
                                    <tr>
                                        <td>{{ $district->district_name }}</td>
                                        <td>{{ $district->state->state_name ?? '-' }}</td>
                                        <td>{{ $district->blocks->count() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">No districts found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>Blocks</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>City Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blocks as $block)
                                    <tr>
                                        <td>{{ $block->block_name }}</td>
                                        <td>{{ $block->district->district_name ?? '-' }}</td>
                                        <td>{{ $block->cities->count() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">No blocks found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Cities</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Block</th>
                                    <th>Pincode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    <tr>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->state->state_name ?? '-' }}</td>
                                        <td>{{ $city->district->district_name ?? '-' }}</td>
                                        <td>{{ $city->block->block_name ?? '-' }}</td>
                                        <td>{{ $city->pincode ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">No cities found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('core.location.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Add Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Location Type</label>
                        <select name="type" class="form-select" required>
                            <option value="">Select</option>
                            <option value="country">Country</option>
                            <option value="state">State</option>
                            <option value="district">District</option>
                            <option value="block">Block</option>
                            <option value="city">City</option>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Code">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <select name="country_id" class="form-select">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State</label>
                            <select name="state_id" class="form-select">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">District</label>
                            <select name="district_id" class="form-select">
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Block</label>
                            <select name="block_id" class="form-select">
                                <option value="">Select Block</option>
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}">{{ $block->block_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Region</label>
                            <input type="text" name="region" class="form-control" placeholder="e.g. Asia">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" class="form-control" placeholder="Pincode">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Numeric Code</label>
                            <input type="text" name="numeric_code" class="form-control" placeholder="Numeric Code">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Effective Date</label>
                            <input type="date" name="effective_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State Type</label>
                            <input type="text" name="state_type" class="form-control" placeholder="State Type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Active</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
