@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">User Creation</h3>
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
                        <a href="#">User Creation</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h4 class="card-title">User Creation Form</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form
                                    action="{{ isset($internal_user) ? route('usercreation-update', $internal_user->id) : route('usercreation-store') }}"
                                    method="POST" enctype="multipart/form-data" class="container">
                                    @csrf

                                    @if (isset($internal_user))
                                        @method('PATCH') {{-- Ensures PATCH request for updates --}}
                                    @endif

                                    <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" id="name" name="name" class="form-control"
                                                    placeholder="Enter Name"  value="{{ old('name', $internal_user->name ?? '') }}">
                                            </div>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="email_id">Email ID</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="email" id="email_id" name="email_id" class="form-control"
                                                    placeholder="Enter Email ID"  value="{{ old('email_id', $internal_user->email_id ?? '') }}">
                                            </div>
                                            @error('email_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="password" id="password" name="password" class="form-control"
                                                    placeholder="Enter Password">
                                            </div>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" class="form-control"
                                                    placeholder="Confirm Password">
                                            </div>
                                            @error('password_confirmation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="mobile_no">Mobile Number</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" id="mobile_no" name="mobile_no" class="form-control"
                                                    placeholder="Enter Mobile Number" value="{{ old('mobile_no', $internal_user->mobile_no ?? '') }}">
                                            </div>
                                            @error('mobile_no')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="role_id">User Type</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select id="role_id" name="role_id" class="form-control">
                                                    <option value="">Select Role</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" {{ old('role_id', $internal_user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                            {{ $role->role_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('role_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="designation">Designation</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" id="designation" name="designation"
                                                    class="form-control" placeholder="Enter Your Designation" value="{{ old('designation', $internal_user->designation ?? '') }}">
                                            </div>
                                            @error('designation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-action text-end">
                                        <button class="btn btn-success">Submit</button>
                                        <button class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery for Dynamic Fields -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/assets/js/aggregator.js') }}"></script>
@endsection
