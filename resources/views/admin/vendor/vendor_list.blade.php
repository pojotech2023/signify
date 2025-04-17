@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Vendor</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Vendor</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Vendor List</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <h4 class="card-title mb-0">Vendor List</h4>
                                <form method="GET" action="{{ route('vendor-list') }}" class="d-flex align-items-center">
                                    <div class="input-group" style="width: 280px !important;">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="form-control" id="searchLeads"
                                            placeholder="Search Name, Mobile, District...">
                                    </div>
                                </form>
                            </div>
                            <button class="btn btn-primary btn-round ms-auto" id="addButton" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                <i class="fa fa-plus"></i> Add Vendor
                            </button>
                        </div>

                        <!-- Blade alert for success -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            {{ session()->forget('success') }} {{-- Clear session --}}
                        @endif

                        @if ($vendors->isEmpty())
                            <p class="text-center mt-3">No Vendors found. Please add a Vendor.</p>
                        @else
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>District</th>
                                                <th>Location</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vendors as $index => $vendor)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $vendor->name }}</td>
                                                    <td>{{ $vendor->mobile_no }}</td>
                                                    <td>{{ $vendor->district }}</td>
                                                    <td>{{ $vendor->location }}</td>
                                                    <td>
                                                        <div class="form-button-action">
                                                            <!-- Edit Button -->
                                                            <button type="button"
                                                                class="btn btn-link btn-primary btn-lg editButton"
                                                                data-id="{{ $vendor->id }}"
                                                                data-name="{{ $vendor->name }}"
                                                                data-mobile="{{ $vendor->mobile_no }}"
                                                                data-district="{{ $vendor->district }}"
                                                                data-location="{{ $vendor->location }}"
                                                                data-bs-toggle="modal" data-bs-target="#addModal">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button type="button"
                                                                class="btn btn-link btn-danger deleteButton"
                                                                data-id="{{ $vendor->id }}" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold" id="modalTitle">Add Vendor</span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="vendorForm" action="{{ route('vendor-store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="vendor_id" name="vendor_id">

                        <!-- Name -->
                        <div class="row align-items-center mb-3">
                            <div class="col-lg-2">
                                <label for="name">Name</label>
                            </div>
                            <div class="col-lg-10">
                                <input id="name" name="name" type="text" class="form-control"
                                    placeholder="Enter name" />
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="row align-items-center mb-3">
                            <div class="col-lg-2">
                                <label for="mobile_no">Mobile</label>
                            </div>
                            <div class="col-lg-10">
                                <input id="mobile" name="mobile_no" type="text" class="form-control"
                                    placeholder="Enter mobile number" />
                                @error('mobile_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- District -->
                        <div class="row align-items-center mb-3">
                            <div class="col-lg-2">
                                <label for="district">District</label>
                            </div>
                            <div class="col-lg-10">
                                <input id="district" name="district" type="text" class="form-control"
                                    placeholder="Enter district" />
                                @error('district')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="row align-items-center mb-3">
                            <div class="col-lg-2">
                                <label for="location">Location</label>
                            </div>
                            <div class="col-lg-10">
                                <textarea id="location" name="location" class="form-control" rows="2" placeholder="Enter location"></textarea>
                                @error('location')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-primary" id="saveButton">Add</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modalTitle = document.getElementById("modalTitle");
            const vendorForm = document.getElementById("vendorForm");
            const nameInput = document.getElementById("name");
            const mobileInput = document.getElementById("mobile");
            const districtInput = document.getElementById("district");
            const locationInput = document.getElementById("location");
            const vendorIdInput = document.getElementById("vendor_id");
            const saveButton = document.getElementById("saveButton");
            const spinner = document.getElementById("loadingSpinner");

            // Add vendor Button Click
            document.getElementById("addButton").addEventListener("click", function() {
                modalTitle.innerText = "Add Vendor";
                saveButton.innerText = "Add";
                vendorForm.action = "{{ route('vendor-store') }}";
                vendorIdInput.value = "";
                nameInput.value = "";
                mobileInput.value = "";
                districtInput.value = "";
                locationInput.value = "";
            });

            // Edit vendor Button Click
            document.querySelectorAll(".editButton").forEach(button => {
                button.addEventListener("click", function() {
                    const vendorId = this.getAttribute("data-id");
                    const vendorName = this.getAttribute("data-name");
                    const vendorMobile = this.getAttribute("data-mobile");
                    const vendorDistrict = this.getAttribute("data-district");
                    const vendorLocation = this.getAttribute("data-location");

                    modalTitle.innerText = "Edit Vendor";
                    saveButton.innerText = "Update";
                    vendorForm.action = "{{ route('vendor-update') }}";
                    vendorIdInput.value = vendorId;
                    nameInput.value = vendorName;
                    mobileInput.value = vendorMobile;
                    districtInput.value = vendorDistrict;
                    locationInput.value = vendorLocation;
                });
            });

            // Delete Button Click
            document.querySelectorAll(".deleteButton").forEach(button => {
                button.addEventListener("click", function() {
                    const vendorId = this.getAttribute("data-id");
                    const action = "{{ route('vendor-delete', ':id') }}".replace(':id',
                        vendorId);
                    document.getElementById("deleteForm").setAttribute("action", action);
                });
            });

            //Show Spinner and Disable Form on Submit
            vendorForm.addEventListener("submit", function() {
                spinner.classList.remove("d-none"); // Show spinner
                saveButton.disabled = true; // Disable button to prevent multiple clicks
            });

            //Auto-hide success alert after 3 seconds
            const successAlert = document.querySelector(".alert-success");
            if (successAlert) {
                setTimeout(() => {
                    successAlert.classList.remove("show");
                    successAlert.classList.add("fade");
                }, 3000);
            }

            //Clear validation error when modal is closed
            addModal.addEventListener('hidden.bs.modal', function() {
                // Clear form fields
                nameInput.value = "";
                mobileInput.value = "";
                districtInput.value = "";
                locationInput.value = "";

                // Remove error messages manually
                const errorMessages = addModal.querySelectorAll('.text-danger');
                errorMessages.forEach(el => el.remove());

                // Remove is-invalid class
                const errorInputs = addModal.querySelectorAll('.is-invalid');
                errorInputs.forEach(input => input.classList.remove('is-invalid'));
            });
        });
    </script>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('addModal'));
                myModal.show();
            });
        </script>
    @endif
@endsection
