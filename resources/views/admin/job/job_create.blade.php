@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Job</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Job</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            @if (isset($job))
                                <h6 class="card-title mb-0 fw-bold me-2">
                                    Job ID: {{ $job->id }}
                                </h6>
                                <span class="op-7 fw-normal me-3">
                                    {{ \Carbon\Carbon::parse($job->created_at)->format('M, d Y h:i A') }}
                                </span>

                                {{-- Status Badge --}}
                                <div class="ms-auto">
                                    @php
                                        $status = $job->status ?? 'New';

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
                            @else
                                <h6 class="card-title mb-0 fw-bold">
                                    Job Creation Form
                                </h6>
                            @endif
                        </div>

                        <div class="card-body">

                            <!-- Blade alert for success -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                {{ session()->forget('success') }} {{-- Clear session --}}
                            @endif

                            <div class="row">
                                {{-- Job Form --}}
                                <form id="jobForm"
                                    action="{{ isset($job) ? route('job-update', $job->id) : route('job-store') }}"
                                    method="POST" enctype="multipart/form-data" class="container">
                                    @csrf
                                    @if (isset($job))
                                        @method('PATCH')
                                    @endif

                                    {{-- Admin only create and update Job values --}}
                                    @if (session('role_name') == 'Admin')
                                        <div class="row align-items-center mt-5">
                                            {{-- Job Name --}}
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="name">Job Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        placeholder="Enter Name"
                                                        value="{{ old('name', $job->name ?? '') }}">
                                                </div>
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Department --}}
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="role_id">Department</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <select id="role_id" name="role_id" class="form-control">
                                                        <option value="">Select Department</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role_id', $job->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->role_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('role_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Assign To User (only for new forms) --}}
                                        @if (!isset($job))
                                            <div class="row align-items-center mb-4">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="fw-bold">Assign To</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <select class="form-control form-select" name="assign_user_id"
                                                            id="assignSelect">
                                                            <option value="">Select Assignee</option>
                                                            @foreach ($admin_super_user as $user)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('assign_user_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Display Assigned User in Edit Mode --}}
                                        @if (isset($job) && isset($assignedUserName))
                                            <div class="row align-items-center mt-3">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="fw-bold">Assigned To:</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <p class="fw-bold text-dark">{{ $assignedUserName }}</p>
                                                    </div>
                                                </div>

                                                {{-- Reassign User (only in Edit Mode) --}}
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label class="fw-bold">Re-Assign To</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <select class="form-control form-select" name="reassign_user_id"
                                                            id="reassignSelect" {{ $reassignEnabled ? '' : 'disabled' }}>
                                                            <option value="">Select Assignee</option>
                                                            @foreach ($admin_super_user as $user)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    {{-- Superuser only view the job name and department --}}
                                    @if (session('role_name') == 'Superuser')
                                        <div class="row align-items-center mt-5">
                                            {{-- Job Name --}}
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="name">Job Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" id="name" class="form-control"
                                                        value="{{ old('name', $job->name ?? '') }}" readonly>
                                                </div>
                                            </div>

                                            {{-- Department --}}
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="role_id">Department</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        value="{{ $job->role->role_name ?? '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Status (both Admin and Superuser) --}}
                                    @if (isset($job))
                                        <div class="row align-items-center mt-5">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="status" class="fw-bold">Change Status</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-select form-control" name="status"
                                                        id="status">
                                                        <option value="">Select Status</option>
                                                        <option value="On Hold">On Hold</option>
                                                        <option value="Completed">Completed</option>
                                                        <option value="Canceled">Canceled</option>
                                                        <option value="Re-Opened">Re-Opened</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Submit and Cancel Buttons --}}
                                    <div class="card-action text-end mt-4">
                                        <button class="btn btn-success">Submit</button>
                                        <a href="{{ route('jobs-list') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Create Task --}}
            @if (session('role_name') === 'Superuser')
                @if (isset($job))
                    <div class="row mt-5">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <div class="d-flex gap-4 position-relative" style="margin-bottom: 30px;">
                                <a href="{{ route('job-task-form', $job->id) }}" class="btn btn-primary px-5 py-2"
                                    style="font-size: 1.2rem; min-width: 200px;">
                                    Create Task
                                </a>
                                {{-- <button type="button" class="btn btn-primary px-5 py-2"
                                    style="font-size: 1.2rem; min-width: 200px;" data-bs-toggle="modal"
                                    data-bs-target="#completeOrderModal" {{ $order->status === 'Completed' ? 'disabled' : '' }}>
                                    Complete Order
                                </button> --}}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Spinner -->
    <div class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary d-none" role="status" id="loadingSpinner">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- jQuery for Dynamic Fields -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/assets/js/aggregator.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alert = document.querySelector('.alert');
            const form = document.getElementById('jobForm');
            const spinner = document.getElementById('loadingSpinner');

            //Success alert handling
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    window.location.href = "{{ route('jobs-list') }}";
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
