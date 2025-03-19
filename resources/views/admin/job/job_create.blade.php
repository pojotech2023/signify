@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Job Creation</h3>
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
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h4 class="card-title">Job Creation Form</h4>
                            </div>
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
                                <form action="{{ isset($job) ? route('job-update', $job->id) : route('job-store') }}"
                                    method="POST" enctype="multipart/form-data" class="container">
                                    @csrf

                                    @if (isset($job))
                                        @method('PATCH') {{-- Ensures PATCH request for updates --}}
                                    @endif

                                    <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="name">Job Name</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" id="name" name="name" class="form-control"
                                                    placeholder="Enter Name" value="{{ old('name', $job->name ?? '') }}">
                                            </div>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
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
                                                            {{ $role->role_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('role_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-action text-end">
                                        <button class="btn btn-success">Submit</button>
                                        {{-- <button class="btn btn-danger">Cancel</button> --}}
                                        <a href="{{ route('jobs-list') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Create Task --}}
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
        </div>
    </div>

    <!-- jQuery for Dynamic Fields -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/assets/js/aggregator.js') }}"></script>
@endsection
