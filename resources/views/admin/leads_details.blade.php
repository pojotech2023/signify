@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
                <h3 class="text-center pb-4 mt-3">Lead Detail View</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10">
                <div class="card shadow-lg p-4 ms-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="card-title mb-0 fw-bold">Lead ID: {{ $lead->id }}</h6>
                        <span class="op-7 ms-3 fw-normal">
                            {{ \Carbon\Carbon::parse($lead->created_at)->format('M, d Y h:i A') }}
                        </span>
                    </div>

                    <!-- Blade alert for success -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        {{ session()->forget('success') }} {{-- Clear session --}}
                    @endif

                    <form action="" method="POST" enctype="multipart/form-data" class="container">
                        @csrf
                        <div class="row align-items-center mt-5">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="category" class="fw-bold">Category</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->category->category }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->subcategory->sub_category }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="material" class="fw-bold">Material</label>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="form-group">
                                    @if (!empty($lead->material->main_img))
                                        <img src="{{ asset('storage/' . $lead->material->main_img) }}" alt="Material Image"
                                            class="img-fluid mt-2" style="max-width: 200px; border-radius: 5px;">
                                        <p class="mt-2 fw-bold">{{ $lead->material->material_name }}</p>
                                    @else
                                        <p class="text-muted mt-2">No image available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="shades" class="fw-bold">Shades</label>
                                </div>
                            </div>
                            <div class="col-lg-10 text-center">
                                <div class="form-group d-flex flex-wrap gap-3">
                                    @if ($lead->shade->isNotEmpty())
                                        @foreach ($lead->shade as $shade)
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $shade->selected_img ?? 'N/A') }}"
                                                    alt="Shade Image" class="img-fluid mt-2"
                                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                                                <p class="mt-2 fw-bold">{{ $shade->shade->shade_name ?? 'N/A' }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mt-2">No image available</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center mt-3">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="size" class="fw-bold">Size</label>
                                </div>
                            </div>

                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="width" class="fw-bold">Width</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->width }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="height" class="fw-bold">Height</label>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->height }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="unit" class="fw-bold">Unit</label>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->unit }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="location" class="fw-bold">Location</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->location }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="quantity" class="fw-bold">Quantity</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->quantity }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="email_id" class="fw-bold">Email</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->email_id }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="mobile_no" class="fw-bold">Mobile Number</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ $lead->mobile_no }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="design_service_need" class="fw-bold">Design Service Need</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ ucfirst($lead->design_service_need) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="how_heard" class="fw-bold">How did you hear?</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <input type="text" class="form-control fw-bold text-dark"
                                        value="{{ ucfirst($lead->how_heard) }}" readonly>
                                </div>
                            </div>
                        </div>

                        @if ($lead->how_heard === 'Others')
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="remarks" class="fw-bold">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group d-flex align-items-center gap-2">
                                        <input type="text" class="form-control fw-bold text-dark"
                                            value="{{ ucfirst($lead->remarks) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="site_image" class="fw-bold">Site Image</label>
                                </div>
                            </div>
                            <div class="col-lg-10 text-center">
                                <div class="form-group d-flex flex-wrap gap-3">
                                    @php
                                        $images = explode(',', $lead->site_image);
                                    @endphp
                                    @if (!empty($images))
                                        @foreach ($images as $image)
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Site Image"
                                                    class="img-fluid mt-2"
                                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mt-2">No image available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="design_attachment" class="fw-bold">Design Attachments</label>
                                </div>
                            </div>
                            <div class="col-lg-10 text-center">
                                <div class="form-group d-flex flex-wrap gap-3">
                                    @php
                                        $images = explode(',', $lead->design_attachment);
                                    @endphp
                                    @if (!empty($images))
                                        @foreach ($images as $image)
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Site Image"
                                                    class="img-fluid mt-2"
                                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mt-2">No image available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="reference_image" class="fw-bold">Reference Image</label>
                                </div>
                            </div>
                            <div class="col-lg-10 text-center">
                                <div class="form-group d-flex flex-wrap gap-3">
                                    @php
                                        $images = explode(',', $lead->reference_image);
                                    @endphp
                                    @if (!empty($images))
                                        @foreach ($images as $image)
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Site Image"
                                                    class="img-fluid mt-2"
                                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mt-2">No image available</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (session('role_name') === 'Admin')
                            <div class="row align-items-center mt-3">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="fw-bold">Assigned To:</label>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <p class="fw-bold text-dark">{{ $assignedUserName }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        {{-- Assign & Re-Assign To Super User --}}
        @if (session('role_name') === 'Admin')
            <div class="row">
                <div class="col-lg-10 ms-4">
                    <form id="assignForm" action="{{ route('lead-assign') }}" method="POST" class="p-4">
                        @csrf
                        <div class="row align-items-center mb-4">

                            {{-- Hidden field to store the aggregator_form ID --}}
                            <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                            {{-- Assign To SuperUser --}}
                            <div class="col-lg-2">
                                <label class="fw-bold">Assign To</label>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group w-100">
                                    <select class="form-control form-select" name="assign_user_id" id="assignSelect"
                                        {{ $assignEnabled ? '' : 'disabled' }}>
                                        <option value="">Select Assignee</option>
                                        @foreach ($admin_super_user as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Re-Assign --}}
                            <div class="col-lg-2">
                                <label class="fw-bold">Re-Assign To</label>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group w-100">
                                    <select class="form-control form-select" name="reassign_user_id" id="reassignSelect"
                                        {{ $reassignEnabled ? '' : 'disabled' }}>
                                        <option value="">Select Assignee</option>
                                        @foreach ($admin_super_user as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Create Task and Confirm Order --}}
        @if (session('role_name') === 'Superuser')
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-center">
                    <div class="d-flex gap-4 position-relative" style="margin-bottom: 30px;">
                        <a href="{{ route('task-form', $lead->id) }}" class="btn btn-primary px-5 py-2"
                            style="font-size: 1.2rem; min-width: 200px;">
                            Create Task
                        </a>
                        <button type="button" class="btn btn-primary px-5 py-2"
                            style="font-size: 1.2rem; min-width: 200px;" data-bs-toggle="modal"
                            data-bs-target="#confirmOrderModal" {{ $lead->status === 'Completed' ? 'disabled' : '' }}>
                            Confirm Order
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-labelledby="confirmOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmOrderModalLabel">Confirm Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to confirm this order? <br>
                    <strong>Lead ID:</strong> {{ $lead->id }}
                </div>
                <div class="modal-footer">
                    <form id="assignForm" action="{{ route('order-store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <button type="submit" class="btn btn-success">Yes, Confirm</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Spinner -->
    <div class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary d-none" role="status" id="loadingSpinner">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alert = document.querySelector('.alert');
            const form = document.getElementById('assignForm');
            const spinner = document.getElementById('loadingSpinner');

            //Success alert handling
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    window.location.href = "{{ route('leads-list') }}";
                }, 3000);
            }

            //Show spinner only on job form submission
            if (form && spinner) {
                form.addEventListener('submit', function(event) {
                    spinner.classList.remove('d-none'); //Show spinner
                });
            }
        });
    </script>

@endsection
