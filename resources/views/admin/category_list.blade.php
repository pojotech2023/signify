@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Aggregator</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Aggregator</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Category List</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="card-title">Category List</h4>
                            <button class="btn btn-primary btn-round ms-auto" id="addButton" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                <i class="fa fa-plus"></i> Add Category
                            </button>
                        </div>

                        @if ($categories->isEmpty())
                            <p class="text-center mt-3">No Categories found. Please add a Category.</p>
                        @else
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
                                                            <!-- Edit Button -->
                                                            <button type="button" class="btn btn-link btn-primary btn-lg editButton"
                                                                data-id="{{ $category->id }}" 
                                                                data-category="{{ $category->category }}"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#addModal">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button type="button" class="btn btn-link btn-danger deleteButton"
                                                                data-id="{{ $category->id }}" 
                                                                data-bs-toggle="modal"
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

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold" id="modalTitle">Add Category</span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Validation Errors -->
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
                    <form id="categoryForm" action="{{ route('category-store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="category_id" name="category_id"> 

                        <div class="row align-items-center">
                            <div class="col-lg-2">
                                <label for="category">Category</label>
                            </div>
                            <div class="col-lg-10">
                                <input id="category" name="category" type="text" class="form-control" placeholder="Enter Category" required />
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-primary" id="saveButton">Add</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modalTitle = document.getElementById("modalTitle");
            const categoryForm = document.getElementById("categoryForm");
            const categoryInput = document.getElementById("category");
            const categoryIdInput = document.getElementById("category_id");
            const saveButton = document.getElementById("saveButton");

            // Add Category Button Click
            document.getElementById("addButton").addEventListener("click", function () {
                modalTitle.innerText = "Add Category";
                saveButton.innerText = "Add";
                categoryForm.action = "{{ route('category-store') }}";
                categoryIdInput.value = "";
                categoryInput.value = "";
            });

            // Edit Category Button Click
            document.querySelectorAll(".editButton").forEach(button => {
                button.addEventListener("click", function () {
                    const categoryId = this.getAttribute("data-id");
                    const categoryName = this.getAttribute("data-category");

                    modalTitle.innerText = "Edit Category";
                    saveButton.innerText = "Update";
                    categoryForm.action = "{{ route('category-update') }}";
                    categoryIdInput.value = categoryId;
                    categoryInput.value = categoryName;
                });
            });

            // Delete Button Click
            document.querySelectorAll(".deleteButton").forEach(button => {
                button.addEventListener("click", function () {
                    const categoryId = this.getAttribute("data-id");
                    const action = "{{ route('category-delete', ':id') }}".replace(':id', categoryId);
                    document.getElementById("deleteForm").setAttribute("action", action);
                });
            });
        });
    </script>
@endsection
