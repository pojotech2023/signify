@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-11">
                <div class="card shadow-lg p-4 ms-4">
                    <div class="card-header d-flex align-items-center">
                        <h6 class="card-title mb-0 fw-bold me-2">
                            Job ID: {{ $task->job_id }} | Task Created By: {{ $task->CreatedBy->name }} | Task ID:
                            {{ $task->id }}
                        </h6>
                        <span class="op-7 fw-normal me-3">
                            {{ \Carbon\Carbon::parse($task->created_at)->format('M, d Y h:i A') }}
                        </span>

                        <div class="ms-auto">
                            @php
                                $status = $task->status ?? 'New';

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

                    {{-- SuperUser Task Form --}}

                    @if (in_array(session('role_name'), ['Admin', 'Superuser']))
                        <form action="{{ route('job-task-update', $task->id) }}" method="POST" enctype="multipart/form-data"
                            class="container">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="job_id" value="{{ $task->job_id ?? '' }}">
                            {{-- Task Name --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="task_name" class="fw-bold">Task Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="task_name"
                                            value="{{ $task->task_name }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Task Priority --}}
                            <div class="row align-items-center mt-5">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Task Priority</label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-control" name="task_priority">
                                        <option value="">Select Priority</option>
                                        <option value="High"
                                            {{ ($task->task_priority ?? '') === 'High' ? 'selected' : '' }}>
                                            High</option>
                                        <option value="Medium"
                                            {{ ($task->task_priority ?? '') === 'Medium' ? 'selected' : '' }}>Medium
                                        </option>
                                        <option value="Low"
                                            {{ ($task->task_priority ?? '') === 'Low' ? 'selected' : '' }}>
                                            Low</option>
                                    </select>

                                </div>
                            </div>

                            {{-- Entry Time --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Entry Time</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($task->entry_time)->format('d-m-Y H:i:s') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- Completion Expected By --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Completion Expected By</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="datetime-local" class="form-control" name="completion_expected_by"
                                        value="{{ $task->completion_expected_by ? \Carbon\Carbon::parse($task->completion_expected_by)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>


                            {{-- Description --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Description</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="4" name="description">{{ $task->description }}</textarea>
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
                                    <input type="file" name="attachments[]" id="attachments"
                                        accept=".webp,.jpg,.jpeg,.png" multiple>

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
                                    <input type="text" class="form-control" name="vendor_name"
                                        value="{{ $task->vendor_name }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="vendor_mobile"
                                        value="{{ $task->vendor_mobile }}">
                                </div>
                            </div>

                            {{-- Customer Details --}}
                            <div class="row align-items-center mt-4 border-bottom pb-3"
                                style="border-bottom: 1px solid #ebecec !important;">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Customer Details</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="customer_name"
                                        value="{{ $task->customer_name }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="customer_mobile"
                                        value="{{ $task->customer_mobile }}">
                                </div>
                            </div>

                            {{-- Executive User Form Prepopulate --}}
                            @if ($task->jobTaskAssign && $task->jobTaskAssign->jobExecutiveTask)
                                {{-- Header Row --}}
                                <div class="row py-2">
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
                                            <textarea id="remarks" class="form-control" rows="4" readonly>
                                             {{ old('remarks', $task->jobTaskAssign->jobExecutiveTask->remarks ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="address" class="fw-bold">Address</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" id="address" class="form-control fw-bold text-dark"
                                                readonly
                                                value="{{ old('address', $task->jobTaskAssign->jobExecutiveTask->address ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- End Date & Time --}}
                                <div class="row align-items-centerborder-bottom pb-3"
                                    style="border-bottom: 1px solid #ebecec !important;">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="end_date_time" class="fw-bold">End Date & Time</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="datetime-local" id="end_date_time"
                                                class="form-control fw-bold text-dark" readonly
                                                value="{{ old('end_date_time', $task->jobTaskAssign->jobExecutiveTask->end_date_time ? \Carbon\Carbon::parse($task->jobTaskAssign->jobExecutiveTask->end_date_time)->format('Y-m-d\TH:i') : '') }}">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Already Assigned Executive Name --}}
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
                                        <label for="internal_user_id" class="fw-bold">Re-Assign To</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-select form-control" id="internal_user_id"
                                            name="internal_user_id">
                                            <option value="" disabled selected>Select Re-Assignee</option>
                                            @foreach ($internal_user_list as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Status Change Superuser --}}
                                <div class="row align-items-center mt-5">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="status" class="fw-bold">Change Status</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-select form-control" name="status" id="status">
                                                <option value="">Select Status</option>
                                                <option value="On Hold">On Hold</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Canceled">Canceled</option>
                                                <option value="Re-Opened">Re-Opened</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                {{-- Submit Button --}}
                                <div class="col-lg-2 ms-auto">
                                    <button type="submit" class="btn btn-primary w-100"
                                        {{ $task->job->status === 'Completed' ? 'disabled' : '' }}>Submit</button>
                                </div>
                        </form>
                    @endif


                    {{-- Executive Task Form --}}
                    @if (in_array(session('role_name'), ['Accounts', 'PR', 'HR', 'R&D']))
                        {{-- Prepopulate Superuer Form --}}
                        <form action="#" class="container">

                            {{-- Task Name --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="task_name" class="fw-bold">Task Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $task->task_name }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- Task Priority --}}
                            <div class="row align-items-center mt-5">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Task Priority</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" value="{{ $task->task_priority }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- Entry Time --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Entry Time</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($task->entry_time)->format('d-m-Y H:i:s') }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- Completion Expected By --}}
                            <div class="row align-items-center mt-4">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Completion Expected By</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="datetime-local" class="form-control"
                                        value="{{ $task->completion_expected_by ? \Carbon\Carbon::parse($task->completion_expected_by)->format('Y-m-d\TH:i') : '' }}"
                                        readonly>
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
                                    <input type="text" class="form-control" readonly
                                        value="{{ $task->vendor_name }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $task->vendor_mobile }}">
                                </div>
                            </div>

                            {{-- Customer Details --}}
                            <div class="row align-items-center mt-4 border-bottom pb-3"
                                style="border-bottom: 1px solid #ebecec !important;">
                                <div class="col-lg-2">
                                    <label class="fw-bold">Customer Details</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $task->customer_name }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $task->customer_mobile }}">
                                </div>
                            </div>
                        </form>

                        {{-- Show empty form ONLY if task is NOT accepted in Executive Login --}}
                        @if (!$task->jobTaskAssign || !$task->jobTaskAssign->jobExecutiveTask)
                            <form action="{{ route('job-task-executive') }}" method="POST" class="container">
                                @csrf

                                {{-- Header Row --}}
                                <div class="row py-2"> <!-- Add padding above and below -->
                                    <div class="col-12">
                                        <h6 class="card-title mb-0 fw-bold">
                                            Filled By Executive/Others
                                        </h6>
                                    </div>
                                </div>

                                {{-- Pass Assigned User ID as Hidden Input --}}
                                <input type="hidden" name="task_assigned_user_id"
                                    value="{{ $task->jobTaskAssign->id }}">

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

                                {{-- Address --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="address" class="fw-bold">Address</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" id="address" name="address"
                                                class="form-control fw-bold text-dark" placeholder="Address" required>
                                        </div>
                                        @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- End Date & Time --}}
                                <div class="row align-items-center border-bottom pb-3"
                                    style="border-bottom: 1px solid #ebecec !important;">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="end_date_time" class="fw-bold">End Date & Time</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="datetime-local" id="end_date_time" name="end_date_time"
                                                class="form-control fw-bold text-dark" placeholder="Address" required>
                                        </div>
                                        @error('end_date_time')
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
                        {{-- Show form ONLY if task is accepted to executive for update --}}
                        @if ($task->jobTaskAssign && $task->jobTaskAssign->jobExecutiveTask)
                            <form
                                action="{{ route('job-task-executive.update', $task->jobTaskAssign->jobExecutiveTask->id) }}"
                                method="POST" class="container">
                                @csrf
                                @method('PATCH')

                                {{-- Header Row --}}
                                <div class="row py-2">
                                    <div class="col-12">
                                        <h6 class="card-title mb-0 fw-bold">
                                            Filled By Executive/Others
                                        </h6>
                                    </div>
                                </div>

                                {{-- Pass Assigned User ID as Hidden Input --}}
                                <input type="hidden" name="task_assigned_user_id"
                                    value="{{ $task->jobTaskAssign->id }}">

                                {{-- Pass Task ID as Hidden Input for status changes --}}
                                <input type="hidden" name="task_id" value="{{ $task->id }}">

                                {{-- Remarks --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="remarks" class="fw-bold">Remarks</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <textarea id="remarks" name="remarks" class="form-control" rows="4" placeholder="Enter remarks here...">
                                             {{ old('remarks', $task->jobTaskAssign->jobExecutiveTask->remarks ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="address" class="fw-bold">Address</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" id="address" name="address"
                                                class="form-control fw-bold text-dark" placeholder="Address"
                                                value="{{ old('address', $task->jobTaskAssign->jobExecutiveTask->address ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- End Date & Time --}}
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="end_date_time" class="fw-bold">End Date & Time</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="datetime-local" id="end_date_time" name="end_date_time"
                                                class="form-control fw-bold text-dark"
                                                value="{{ old('end_date_time', $task->jobTaskAssign->jobExecutiveTask->end_date_time ? \Carbon\Carbon::parse($task->jobTaskAssign->jobExecutiveTask->end_date_time)->format('Y-m-d\TH:i') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="row align-items-center mt-5 border-bottom pb-3"
                                    style="border-bottom: 1px solid #ebecec !important;">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="status" class="fw-bold">Change Status</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-select form-control" name="status" id="status">
                                                <option value="">Select Status</option>
                                                <option value="On Hold">On Hold</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Canceled">Canceled</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- Submit Button --}}
                                <div class="row align-items-center mt-4">
                                    <div class="col-lg-2 ms-auto mt-4">
                                        <button type="submit" class="btn btn-primary w-100"
                                            {{ $task->job->status === 'Completed' ? 'disabled' : '' }}>Submit</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
