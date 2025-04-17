@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header leads-page-header">
                <h3 class="fw-bold mb-3">Jobs</h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-8">
                    {{-- Filters Section: Search, Status Dropdown & Date Picker --}}
                    <form action="{{ route('filter-jobs-list') }}" method="POST" class="row mb-3">
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
                                <input type="date" class="form-control" id="filterDate" name="date">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-50">Filter</button>
                            </div>
                        </div>
                    </form>
                    {{-- Create Job for Button --}}
                    @if (session('role_name') === 'Admin')
                        <div class="row mb-3">
                            <div class="col text-end">
                                <a href="{{ route('jobcreation-form') }}" class="btn btn-primary btn-round ms-auto">
                                    <i class="fa fa-plus"></i> Create Job
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8">
                    @foreach ($jobs as $job)
                        <div class="card mt-3 job-card" data-route="{{ route('jobcreation-form', $job->id) }}"
                            onclick="redirectToLeadDetails(event, this)">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-8 d-flex align-items-center">
                                        <h6 class="card-title mb-0"> Job ID: {{ $job->id }}</h6>
                                        <span class="op-7 ms-3 fw-normal">
                                            {{ \Carbon\Carbon::parse($job->created_at)->format('M, d Y h:i A') }}
                                        </span>
                                    </div>
                                    <div class="col-4 text-end">
                                        @php
                                            $status = $job->status ?? 'New'; // Default status is 'New'

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
                                        <p><strong>Job Name:</strong>
                                            <span class="text-muted">{{ $job->name }}</span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Department:</strong>
                                            <span class="text-muted">{{ $job->role->role_name }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-end">
                                        <a href="{{ route('jobs-tasks', $job->id) }}"
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
    <script>
        //redirect to leads detail page
        function redirectToLeadDetails(event, card) {
            event.stopPropagation(); // Prevents unintended clicks
            // Remove styles from all cards
            document.querySelectorAll('.job-card').forEach(item => {
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
        //display current date in filter date
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById("filterDate");
            // Set default value to today's date
            let today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            // Allow users to change the date freely
            dateInput.addEventListener("change", function() {
                console.log("Selected Date:", dateInput.value);
            });
        });
    </script>

    <style>
        .job-card {
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            border: 2px solid #dee2e6;
            background: #fff;
            border-radius: 8px;
        }

        /* Hover Effect */
        .job-card:hover {
            transform: scale(1.02);
            border-color: #007bff;
            box-shadow: 0px 5px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
@endsection
