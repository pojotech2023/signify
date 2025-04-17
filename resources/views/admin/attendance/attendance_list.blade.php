@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Attendance</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Attendance</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Attendance Management</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="card-title">Attendance Management</h4>
                        </div>

                        @if ($attendances->isEmpty())
                            <p class="text-center mt-3">No Vendors found. Please add a Vendor.</p>
                        @else
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>User Name</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Location</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attendances as $index => $attendance)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $attendance->internalUser->name }}</td>
                                                    <td>{{ $attendance->date }}</td>
                                                    <td>{{ $attendance->time }}</td>
                                                    <td>{{ $attendance->location }}</td>
                                                    <td>{{ $attendance->lat }}</td>
                                                    <td>{{ $attendance->long }}</td>
                                                    <td>{{ $attendance->type }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>   
@endsection
