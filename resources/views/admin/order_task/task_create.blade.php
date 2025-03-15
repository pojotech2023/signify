@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- <div class="row">
            <div class="col-lg-10">
                <h3 class="text-center pb-4 mt-3">Lead Detail View</h3>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-11">
                <div class="card shadow-lg p-4 ms-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="card-title mb-0 fw-bold">
                            Task Created By: {{ auth('admin')->user()->name }} | Order ID: {{ $order->id }}
                        </h6>
                        <span class="op-7 ms-3 fw-normal">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('M, d Y h:i A') }}
                        </span>
                    </div>

                    <form action="{{ route('order-task-create') }}" method="POST" enctype="multipart/form-data"
                        class="container">
                        @csrf

                        <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">

                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="task_name" class="fw-bold">Task Name</label>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="task_name" required>
                                </div>
                                @error('task_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center mt-5">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="task_priority" class="fw-bold">Task Priority</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-select form-control" name="task_priority" id="filterStatus"
                                        required>
                                        <option value="">Select Priority</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                                @error('task_priority')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="completion_expected_by" class="fw-bold">Completion Expected By</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="datetime-local" name="completion_expected_by" id="completion_expected_by" required>
                                </div>
                                @error('completion_expected_by')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="description" class="fw-bold">Description</label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <textarea id="description" name="description" class="form-control" rows="4"
                                        placeholder="Enter description here..." required></textarea>
                                </div>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="attachments" class="fw-bold">Attachments</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <input type="file" name="attachments[]" id="attachments"
                                        accept=".webp,.jpg,.jpeg,.png" multiple required>
                                </div>
                                @error('attachments')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="vendor_name" class="fw-bold">Vendor Details</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" id="vendor_name" name="vendor_name"
                                        class="form-control fw-bold text-dark" placeholder="Name" required>
                                </div>
                                @error('vendor_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" id="vendor_mobile" name="vendor_mobile"
                                        class="form-control fw-bold text-dark" placeholder="Mobile Number" required>
                                </div>
                                @error('vendor_mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="customer_name" class="fw-bold">Customer Details</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" id="customer_name" name="customer_name"
                                        class="form-control fw-bold text-dark" placeholder="Name" required>
                                </div>
                                @error('customer_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="text" id="customer_mobile" name="customer_mobile"
                                        class="form-control fw-bold text-dark" placeholder="Mobile Number" required>
                                </div>
                                @error('customer_mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Assign To Executive --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="internal_user_id" class="fw-bold">Assign To</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-select form-control" name="internal_user_id" id="internal_user_id"
                                        required>
                                        <option value="">Select Assignee</option>
                                        @foreach ($internal_user_list as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 ms-auto">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById("todayDate");
            // Set default value to today's date
            let today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            // Allow users to change the date freely
            dateInput.addEventListener("change", function() {
                console.log("Selected Date:", dateInput.value);
            });
        });
    </script>
@endsection
