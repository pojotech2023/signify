@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Customer</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Customer</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Profile List</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <h4 class="card-title mb-0">Customer Profile List</h4>
                                <form method="GET" action="{{ route('profile-list') }}" class="d-flex align-items-center">
                                    <div class="input-group" style="width: 330px !important;">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="form-control" id="searchLeads"
                                            placeholder="Search Name, Mobile, Company Name...">
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if ($users->isEmpty())
                            <p class="text-center mt-3">No Vendors found. Please add a Vendor.</p>
                        @else
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Mobile Number</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Company Name</th>
                                                <th>Designation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $index => $user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->mobile_no }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->gender }}</td>
                                                    <td>{{ $user->company_name }}</td>
                                                    <td>{{ $user->designation }}</td>
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
