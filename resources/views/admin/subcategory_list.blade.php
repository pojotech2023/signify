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
                    <li class="nav-item"><a href="#">Sub Category List</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Sub Category List</h4>
                                <button class="btn btn-primary btn-round ms-auto" id="addButton" data-bs-toggle="modal"
                                    data-bs-target="#addModal">
                                    <i class="fa fa-plus"></i> Add Sub Category
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
                                            <th>Sub Category</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subcategories as $index => $subcategory)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $subcategory->category->category ?? 'No Category' }}</td>
                                                <td>{{ $subcategory->sub_category }}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <button type="button"
                                                            class="btn btn-link btn-primary btn-lg editButton"
                                                            data-id="{{ $subcategory->id }}"
                                                            data-subcategory="{{ $subcategory->sub_category }}"
                                                            data-category-id="{{ $subcategory->category_id }}"
                                                            data-bs-toggle="modal" data-bs-target="#addModal">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-link btn-danger deleteButton"
                                                            data-bs-toggle="modal" data-id="{{ $subcategory->id }}"
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
                            <!--Add Modal -->
                            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold" id="modalTitle">Add Subcategory</span>
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
                                            <form id="subcategoryForm" action="{{ route('subcategory-store') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" id="subcategory_id" name="subcategory_id">
                                                <!-- Hidden ID for Edit -->

                                                <!-- Category Dropdown -->
                                                <div class="row align-items-center material-row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="category_id">Category</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <select id="category_id" name="category_id" class="form-control"
                                                                required>
                                                                <option value="">Select Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->category }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Subcategory Input -->
                                                <div class="row align-items-center material-row mt-3">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="sub_category">Subcategory</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group">
                                                            <input id="sub_category" name="sub_category" type="text"
                                                                class="form-control" placeholder="Enter Subcategory"
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
                                            <form id="deleteForm"
                                                action="{{ route('subcategory-delete', $subcategory->id) }}"
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
            const subcategoryForm = document.getElementById("subcategoryForm");
            const categorySelect = document.getElementById("category_id");
            const subcategoryInput = document.getElementById("sub_category");
            const subcategoryIdInput = document.getElementById("subcategory_id");
            const saveButton = document.getElementById("saveButton");

            // When clicking Add Subcategory button
            document.getElementById("addButton").addEventListener("click", function() {
                modalTitle.innerText = "Add Subcategory";
                subcategoryForm.action = "{{ route('subcategory-store') }}"; // Set form action
                saveButton.innerText = "Add";
                subcategoryIdInput.value = ""; // Clear hidden input
                subcategoryInput.value = ""; // Clear input field
                categorySelect.value = ""; // Reset dropdown
            });

            // When clicking Edit Subcategory button
            document.querySelectorAll(".editButton").forEach(button => {
                button.addEventListener("click", function() {
                    const subcategoryId = this.getAttribute("data-id");
                    const subcategoryName = this.getAttribute("data-subcategory");
                    const categoryId = this.getAttribute("data-category-id");

                    modalTitle.innerText = "Edit Subcategory";
                    subcategoryForm.action = "{{ route('subcategory-update') }}"; // Set form action
                    saveButton.innerText = "Update";
                    subcategoryIdInput.value = subcategoryId;
                    subcategoryInput.value = subcategoryName;
                    categorySelect.value = categoryId;
                });
            });
        });
        //Delete
        $(document).ready(function() {
            $('.deleteButton').click(function() {
                var id = $(this).data('id'); // Get ID from button
                var action = "{{ route('subcategory-delete', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', action);
            });
        });
    </script>
@endsection
