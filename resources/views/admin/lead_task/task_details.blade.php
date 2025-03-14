@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-11">
                <div class="card shadow-lg p-4 ms-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="card-title mb-0 fw-bold">
                            Task Created By: {{ $task->CreatedBy->name }} | Task ID: {{ $task->id }}
                        </h6>
                        <span class="op-7 ms-3 fw-normal">
                            {{ \Carbon\Carbon::parse($task->created_at)->format('M, d Y h:i A') }}
                        </span>
                    </div>

                    <form action="#" enctype="multipart/form-data" class="container">
                        @csrf

                        {{-- Task Priority --}}
                        <div class="row align-items-center mt-5">
                            <div class="col-lg-2">
                                <label class="fw-bold">Task Priority</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $task->task_priority }}" readonly>
                            </div>
                        </div>

                        {{-- Entry Time --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <label class="fw-bold">Entry Time</label>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" value="{{ $task->entry_time }}" readonly>
                            </div>
                        </div>

                        {{-- Delivery Needed By --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <label class="fw-bold">Delivery Needed By</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $task->delivery_needed_by }}" readonly>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <label class="fw-bold">Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" readonly>{{ $task->description }}</textarea>
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="attachments" class="fw-bold">Attachments</label>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                @if (!empty($task->attachments))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach (explode(',', $task->attachments) as $attachment)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $attachment) }}" alt="Attachment"
                                                    class="img-thumbnail"
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                                <a href="{{ asset('storage/' . $attachment) }}" target="_blank"
                                                    class="stretched-link"></a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No attachments available.</p>
                                @endif
                            </div>
                        </div>


                        {{-- Vendor Details --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <label class="fw-bold">Vendor Details</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $task->vendor_name }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $task->vendor_mobile }}" readonly>
                            </div>
                        </div>

                        {{-- Customer Details --}}
                        <div class="row align-items-center mt-4">
                            <div class="col-lg-2">
                                <label class="fw-bold">Customer Details</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $task->customer_name }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" value="{{ $task->customer_mobile }}" readonly>
                            </div>
                        </div>

                        {{-- Start Date & End Date --}}
                        <div class="row align-items-center mt-4 border-bottom pb-3"
                            style="border-bottom: 1px solid #ebecec !important;">
                            <div class="col-lg-2">
                                <label class="fw-bold">Start Date</label>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" value="{{ $task->start_date }}" readonly>
                            </div>
                            <div class="col-lg-2">
                                <label class="fw-bold">End Date</label>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" value="{{ $task->end_date }}" readonly>
                            </div>
                        </div>

                        {{-- WhatsApp Forward --}}
                        {{-- <div class="row align-items-center mt-4 border-bottom pb-3">
                            <div class="col-lg-2">
                                <label class="fw-bold">WhatsApp Forward</label>
                            </div>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" value="{{ $task->whatsapp_message }}" readonly>
                            </div>
                        </div> --}}

                        {{--Executive Task Form Prepopulate--}}
                        @if ($task->assignExecutive && $task->assignExecutive->executiveTask)
                            {{-- Header Row --}}
                            <div class="row py-2"> <!-- Add padding above and below -->
                                <div class="col-12">
                                    <h6 class="card-title mb-0 fw-bold">
                                        Filled By Executive/Others
                                    </h6>
                                </div>
                            </div>

                            {{-- Remarks --}}
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="remarks" class="fw-bold">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <textarea class="form-control" rows="4" readonly>{{ optional($task->assignExecutive->executiveTask)->remarks }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Geo Tag --}}
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="latitude" class="fw-bold">Geo Tag</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control fw-bold text-dark"
                                            value="{{ optional($task->assignExecutive->executiveTask)->geo_latitude }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control fw-bold text-dark"
                                            value="{{ optional($task->assignExecutive->executiveTask)->geo_longitude }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="row align-items-center mt-5">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Status</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"
                                        value="{{ optional($task->assignExecutive->executiveTask)->status }}" readonly>
                                </div>
                            </div>
                        @endif
                    </form>

                    {{-- Show empty form ONLY if task is NOT accepted in Executive Login--}}
                    @if (!$task->assignExecutive || !$task->assignExecutive->executiveTask)
                        @if (in_array(session('role_name'), ['Accounts', 'PR', 'HR', 'R&D']))
                            <form action="{{ route('task-executive') }}" method="POST" class="container">
                                @csrf

                                {{-- Header Row --}}
                                <div class="row py-2"> <!-- Add padding above and below -->
                                    <div class="col-12">
                                        <h6 class="card-title mb-0 fw-bold">
                                            Filled By Executive/Others
                                        </h6>
                                    </div>
                                </div>

                                {{-- Pass Executive ID as Hidden Input --}}
                                <input type="hidden" name="assigned_executive_id"
                                    value="{{ $task->assignExecutive->id }}">

                                {{-- Remarks --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="remarks" class="fw-bold">Remarks</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <textarea id="remarks" name="remarks" class="form-control" rows="4" placeholder="Enter remarks here..."
                                                required></textarea>
                                        </div>
                                        @error('remarks')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Geo Tag --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="latitude" class="fw-bold">Geo Tag</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" id="geo_latitude" name="geo_latitude"
                                                class="form-control fw-bold text-dark" placeholder="Latitude" required>
                                        </div>
                                        @error('geo_latitude')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" id="geo_longitude" name="geo_longitude"
                                                class="form-control fw-bold text-dark" placeholder="Longitude" required>
                                        </div>
                                        @error('geo_longitude')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="row align-items-center mt-5 border-bottom pb-3"
                                    style="border-bottom: 1px solid #ebecec !important;">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="status" class="fw-bold">Status</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-select form-control" name="status" id="status"
                                                required>
                                                <option value="">Select Status</option>
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
                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Submit Button --}}
                                <div class="row align-items-center mt-4">
                                    <div class="col-lg-2 ms-auto mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    @endif

                    {{--Superuser is Re-Assign to Executive--}}
                    @if (session('role_name') === 'Superuser')
                        <form action="{{ route('reassign-executive') }}" method="POST">
                            @csrf

                            {{-- Pass Task ID as Hidden Input --}}
                            <input type="hidden" name="task_id" value="{{ $task->id }}">

                            {{--Already Assigned Executive Name--}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="fw-bold">Assigned To:</label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <p class="fw-bold text-dark">{{ $assignedExecutiveName }}</p>
                                    </div>
                                </div>

                                {{-- Re-Assign --}}
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="executive_id" class="fw-bold">Re-Assign To</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-select form-control" id="executive_id" name="executive_id">
                                            <option value="" disabled selected>Select Re-Assignee</option>
                                            @foreach ($executive_list as $executive)
                                                <option value="{{ $executive->id }}">{{ $executive->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="col-lg-2 ms-auto">
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
