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
                    <li class="nav-item"><a href="#">Category List</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Category List</h4>
                                <button class="btn btn-primary btn-round ms-auto" id="addButton" data-bs-toggle="modal"
                                    data-bs-target="#addModal">
                                    <i class="fa fa-plus"></i> Add Category
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Category</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $index => $category)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $category->category }}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <button type="button" class="btn btn-link btn-success uploadButton"
                                                            data-bs-toggle="modal" data-id="{{ $category->id }}"
                                                            data-bs-target="#uploadModal">
                                                            <i class="bi bi-upload"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-link btn-primary btn-lg editButton"
                                                            data-id="{{ $category->id }}"
                                                            data-category="{{ $category->category }}" data-bs-toggle="modal"
                                                            data-bs-target="#addModal">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-link btn-danger deleteButton"
                                                            data-bs-toggle="modal" data-id="{{ $category->id }}"
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
                            <!--Upload Modal -->
                            <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold" id="modalTitle">Upload Category Video</span>
                                            </h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <!-- Validation Errors Display -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="modal-body">
                                            <form id="categoryForm" action="" method="POST">
                                                @csrf
                                                <input type="hidden" id="category_id" name="category_id">
                                                <!-- Hidden ID for edit -->

                                                <div class="row align-items-center material-row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label for="category">Category Video</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <input id="video" name="video" type="file"
                                                                class="form-control" placeholder="Upload Video"
                                                                accept="video/*" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-0">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="saveButton">Upload</button>
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Add Modal -->
                            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold" id="modalTitle">Add Category</span>
                                            </h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <!-- Validation Errors Display -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="modal-body">
                                            <form id="categoryForm" action="{{ route('category-store') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" id="category_id" name="category_id">
                                                <!-- Hidden ID for edit -->

                                                <div class="row align-items-center material-row">
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="category">Category</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <div class="form-group">
                                                            <input id="category" name="category" type="text"
                                                                class="form-control" placeholder="Enter Category"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-0">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="saveButton">Add</button>
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                                aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this record?
                                        </div>
                                        <div class="modal-footer">
                                            <form id="deleteForm" action="{{ route('category-delete', $category->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Delete Confirmation Modal -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addModal = new bootstrap.Modal(document.getElementById("addModal"));
            const modalTitle = document.getElementById("modalTitle");
            const categoryForm = document.getElementById("categoryForm");
            const categoryInput = document.getElementById("category");
            const categoryIdInput = document.getElementById("category_id");
            const saveButton = document.getElementById("saveButton");

            // When clicking Add Category button
            document.getElementById("addButton").addEventListener("click", function() {
                modalTitle.innerText = "Add Category";
                categoryForm.action = "{{ route('category-store') }}";
                saveButton.innerText = "Add";
                categoryIdInput.value = "";
                categoryInput.value = "";
            });

            // When clicking Edit Category button
            document.querySelectorAll(".editButton").forEach(button => {
                button.addEventListener("click", function() {
                    const categoryId = this.getAttribute("data-id");
                    const categoryName = this.getAttribute("data-category");

                    modalTitle.innerText = "Edit Category";
                    categoryForm.action = "{{ route('category-update') }}";
                    saveButton.innerText = "Update";
                    categoryIdInput.value = categoryId;
                    categoryInput.value = categoryName;
                });
            });
        });
        $(document).ready(function() {
            $('.deleteButton').click(function() {
                var id = $(this).data('id');
                var action = "{{ route('category-delete', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', action);
            });
        });
    </script>
@endsection
