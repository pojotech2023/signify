@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header leads-page-header">
                <h3 class="fw-bold mb-3">Leads</h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-8">
                    {{-- Filters Section: Search, Status Dropdown & Date Picker --}}
                    <form id="filterform" action="{{ route('filter-leads-list') }}" method="POST" class="row mb-3">
                        @csrf
                        <div class="row mb-3">
                            {{-- <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i> 
                                    </span>
                                    <input type="text" class="form-control" id="searchLeads"
                                        placeholder="Search Leads...">
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="input-group w-100">
                                    <select class="form-select form-control" id="filterStatus" name="status">
                                        <option value="All">All</option>
                                        <option value="New">New</option>
                                        <option value="Assigned">Assigned</option>
                                        <option value="Inprogress">In Progress</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Re-Assigned">Re-Assigned</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Canceled">Canceled</option>
                                        <option value="Re-Opened">Re-Opened</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-50">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="row">
                <div class="col-12 col-md-8">
                    @foreach ($leads as $lead)
                        <div class="card mt-3 lead-card" data-route="{{ route('lead-details', $lead->id) }}"
                            onclick="redirectToLeadDetails(event, this)">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <h6 class="card-title mb-0">Lead ID: {{ $lead->id }}</h6>
                                        <span class="op-7 ms-3 fw-normal">
                                            {{ \Carbon\Carbon::parse($lead->created_at)->format('M, d Y h:i A') }}
                                        </span>
                                    </div>
                                    <div class="col-6 text-end">
                                        @php
                                            $status = $lead->status ?? 'New'; // Default status is 'New'

                                            $badgeClass = match ($status) {
                                                'New' => 'badge-info',
                                                'Assigned' => 'badge-success',
                                                'Inprogress' => 'badge-warning',
                                                'On Hold' => 'badge-danger',
                                                'Re-Assigned' => 'badge-primary',
                                                'Completed' => 'badge-success',
                                                'Canceled' => 'badge-danger',
                                                'Re-Opened' => 'badge-secondary',
                                                default => 'badge-light',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            {{ $status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Category:</strong>
                                            <span class="text-muted">{{ $lead->category->category }}</span>
                                        </p>
                                        <p><strong>Material:</strong>
                                            <span class="text-muted">{{ $lead->material->material_name }}</span>
                                        </p>
                                        <p><strong>Location:</strong>
                                            <span class="text-muted">{{ $lead->location }}</span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Sub Category:</strong>
                                            <span class="text-muted">{{ $lead->subcategory->sub_category }}</span>
                                        </p>
                                        <p><strong>Shade:</strong>
                                            <span class="text-muted">{{ $lead->shade->first()->shade->shade_name }}</span>
                                        </p>
                                        <p><strong>Mobile Number:</strong>
                                            <span class="text-muted">{{ $lead->mobile_no }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-end">
                                        <a href="{{ route('leads-tasks', $lead->id) }}"
                                            class="link-primary text-decoration-underline">
                                            View Task
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
        //redirect to leads detail page
        function redirectToLeadDetails(event, card) {
            event.stopPropagation(); // Prevents unintended clicks
            // Remove styles from all cards
            document.querySelectorAll('.lead-card').forEach(item => {
                item.classList.remove('selected-card');
            });
            // Add active class to clicked card
            card.classList.add('selected-card');
            // Redirect after small delay
            let route = card.getAttribute('data-route');
            if (route) {
                window.location.href = route;
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('filterform');
            const spinner = document.getElementById('loadingSpinner');

            //Show spinner only on filter submission
            if (form && spinner) {
                form.addEventListener('submit', function(event) {
                    spinner.classList.remove('d-none'); //Show spinner
                });
            }
        });
    </script>

    <style>
        .lead-card {
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            border: 2px solid #dee2e6;
            background: #fff;
            border-radius: 8px;
        }

        /* Hover Effect */
        .lead-card:hover {
            transform: scale(1.02);
            border-color: #007bff;
            box-shadow: 0px 5px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
@endsection
