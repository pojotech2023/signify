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
                            Task Created By: {{ auth('admin')->user()->name }} | Lead ID: {{ $lead->id }}
                        </h6>
                        <span class="op-7 ms-3 fw-normal">
                            {{ \Carbon\Carbon::parse($lead->created_at)->format('M, d Y h:i A') }}
                        </span>
                    </div>

                    <form action="{{ route('task-create') }}" method="POST" enctype="multipart/form-data" class="container">
                        @csrf

                        <input type="hidden" name="lead_id" value="{{ $lead->id ?? '' }}">

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
                                        <option value="New">High</option>
                                        <option value="Assigned">Small</option>
                                        <option value="Inprogress">Medium</option>
                                        {{-- <option value="Re-Assigned">Re-Assigned</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Canceled">Canceled</option>
                                        <option value="Re-Opened">Re-Opened</option> --}}
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
                                    <label for="entry_time" class="fw-bold">Entry Time</label>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="entry_time" required>
                                </div>
                                @error('entry_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="delivery_needed_by" class="fw-bold">Delivery Needed By</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-select form-control" id="delivery_needed_by"
                                        name="delivery_needed_by" required>
                                        <option value="">Select Vendor</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="New">Sample</option>
                                        <option value="Assigned">Sample1</option>
                                        {{-- <option value="Inprogress">In Low</option>
                                        <option value="Re-Assigned">Re-Assigned</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Canceled">Canceled</option>
                                        <option value="Re-Opened">Re-Opened</option> --}}
                                    </select>
                                </div>
                                @error('delivery_needed_by')
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

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="start_date" class="fw-bold">Start Date</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="start_date" id="todayDate"
                                        required>
                                </div>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="end_date" class="fw-bold">End Date</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="end_date" id="todayDate" required>
                                </div>
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center mt-4 border-bottom pb-3"
                            style="border-bottom: 1px solid #ebecec !important;">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="whatsapp_message" class="fw-bold">Whatsapp Forward</label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <i class="fab fa-whatsapp text-success fs-4"></i>
                                    <span class="fw-bold text-dark">WhatsApp Message</span>
                                </div>
                                <input type="text" name="whatsapp_message" class="form-control mt-2">
                                @error('whatsapp_message')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="executive_id" class="fw-bold">Assign To</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-select form-control" name="executive_id" id="executive_id"
                                        required>
                                        <option value="">Select Assignee</option>
                                        @foreach ($executive_list as $executive)
                                            <option value="{{ $executive->id }}">{{ $executive->name }}</option>
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
