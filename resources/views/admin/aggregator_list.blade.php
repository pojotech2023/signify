@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Aggregator</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Aggregator</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Material & Shade List</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Material & Shade List</h4>
                            </div>
                        </div>

                        @if ($materials->isEmpty())
                            <p class="text-center mt-3"> No Materials and Shades found. Please add an Aggregator Form.</p>
                        @else
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Material Name</th>
                                                <th>Material Main Image</th>
                                                <th>Material Sub Image</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($materials as $index => $material)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $material->material_name }}</td>
                                                    <td>
                                                        <img src="{{ asset('storage/' . $material->main_img) }}"
                                                            alt="Main Image" width="100">
                                                    </td>
                                                    <td>
                                                        @for ($i = 1; $i <= 4; $i++)
                                                            @php
                                                                $fieldName = "sub_img$i";
                                                            @endphp
                                                            @if (!empty($material->$fieldName))
                                                                <img src="{{ asset('storage/' . $material->$fieldName) }}"
                                                                    alt="Sub Image {{ $i }}" width="70"
                                                                    height="70">
                                                            @endif
                                                        @endfor
                                                    </td>
                                                    <td>
                                                        <div class="form-button-action">
                                                            <a href="{{ route('aggregator-form', ['id' => $material->id]) }}"
                                                                class="btn btn-link btn-primary btn-lg">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-link btn-danger deleteButton"
                                                                data-id="{{ $material->id }}" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
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
    <!-- Delete Confirmation Modal (Outside Loop) -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- End of Delete Confirmation Modal -->

    <!-- JavaScript -->
    <script>
        document.querySelectorAll(".deleteButton").forEach(button => {
            button.addEventListener("click", function() {
                const categoryId = this.getAttribute("data-id");
                const action = "{{ route('material-delete', ':id') }}".replace(':id', categoryId);
                document.getElementById("deleteForm").setAttribute("action", action);
            });
        });
    </script>
@endsection
