@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header leads-page-header">
                <h3 class="fw-bold mb-3">Jobs - Tasks</h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-8">
                    {{-- Filters Section: Search, Status Dropdown & Date Picker --}}
                    <form id="filterform" method="POST" action="{{ route('filter-job-tasks', $job_id) }}">
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
                                <input type="date" class="form-control" id="filterDate" name="date"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="row">
                <div class="col-12 col-md-8">
                    @foreach ($job_tasks as $job_task)
                        <div class="card mt-3 task-card" data-route="{{ route('job-task-details', $job_task->id) }}"
                            onclick="redirectToLeadDetails(event, this)">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <h6 class="card-title mb-0">Task ID: {{ $job_task->id }}</h6>
                                        <span class="op-7 ms-3 fw-normal">
                                            {{ \Carbon\Carbon::parse($job_task->created_at)->format('M, d Y h:i A') }}
                                        </span>
                                    </div>
                                    <div class="col-6 text-end">
                                        @php
                                            $status = $job_task->status ?? 'New';

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
                                    <!-- First row: Task Priority and Entry Time -->
                                    <div class="col-md-6">
                                        <p><strong>Task Priority:</strong>
                                            <span class="text-muted">{{ $job_task->task_priority }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Entry Time:</strong>
                                            <span class="text-muted">{{ $job_task->entry_time }}</span>
                                        </p>
                                    </div>

                                    <!-- Second row: Full Description -->
                                    <div class="col-md-12">
                                        <p><strong>Description:</strong>
                                            <span class="text-muted">{{ $job_task->description }}</span>
                                        </p>
                                    </div>

                                    <!-- Third row: Vendor Details -->
                                    <div class="col-md-6">
                                        <p><strong>Vendor Name:</strong>
                                            <span class="text-muted">{{ $job_task->vendor_name }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Vendor Mobile:</strong>
                                            <span class="text-muted">{{ $job_task->vendor_mobile }}</span>
                                        </p>
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
        function redirectToLeadDetails(event, card) {
            event.stopPropagation(); // Prevents unintended clicks
            // Remove styles from all cards
            document.querySelectorAll('.task-card').forEach(item => {
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
        .task-card {
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            border: 2px solid #dee2e6;
            background: #fff;
            border-radius: 8px;
        }

        /* Hover Effect */
        .task-card:hover {
            transform: scale(1.02);
            border-color: #007bff;
            box-shadow: 0px 5px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
@endsection
