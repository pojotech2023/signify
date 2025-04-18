@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Aggregator</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Aggregator</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Aggregator Form</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h4 class="card-title">Aggregator Form</h4>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-round"
                                        onclick="window.location.href='{{ route('category-list') }}'">
                                        Category
                                    </button>
                                    <button class="btn btn-primary btn-round"
                                        onclick="window.location.href='{{ route('subcategory-list') }}'">
                                        Sub Category
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <!-- Blade alert for success -->
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                {{ session()->forget('success') }} {{-- Clear session --}}
                            @endif

                            <div class="row">
                                <form id="aggregatorForm"
                                    action="{{ isset($material) ? route('material-update', $material->id) : route('aggregator-store') }}"
                                    method="POST" enctype="multipart/form-data" class="container">
                                    @csrf
                                    @if (isset($material))
                                        @method('PATCH') {{-- Ensures PATCH request for updates --}}
                                    @endif


                                    <h5 class="mt-4">Category</h5>
                                    <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="category_id">Category</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select id="category_id" name="category_id" class="form-control">
                                                    <option value="">Select Category</option>
                                                    {{-- @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->category }}
                                                        </option>
                                                    @endforeach --}}
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->category }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" id="hidden_category_id" name="category_id">
                                            </div>
                                            @error('category_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="subcategory">Sub Category</label>
                                            </div>
                                        </div>
                                        <!-- Subcategory Container -->
                                        <div class="col-lg-4">
                                            <div class="d-flex">
                                                <select id="subcategory" name="sub_category_id" class="form-control">
                                                    <option value="">Select Sub Category</option>
                                                </select>
                                                {{-- <input type="hidden" id="hidden_sub_category_id" name="sub_category_id"> --}}
                                            </div>
                                            @error('sub_category_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Material Name & Images -->
                                    <h5 class="mt-4">Materials</h5>
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="material_name">Material Name</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" name="material_name" class="form-control"
                                                    value="{{ old('material_name', $material->material_name ?? '') }}">
                                            </div>
                                            @error('material_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Main Image -->
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="main_img">Main Image</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="file" name="main_img" id="main_img" class="form-control">
                                                @if (isset($material) && $material->main_img)
                                                    <img src="{{ asset('storage/' . $material->main_img) }}" width="100"
                                                        class="mt-2">
                                                @endif
                                            </div>
                                            @error('main_img')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Sub Images -->
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="material_sub_img">Material Sub Images</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="file" name="material_sub_img[]" class="form-control"
                                                    multiple accept="image/*">
                                                @if (isset($material))
                                                    <div class="mt-2 d-flex flex-wrap">
                                                        @foreach (['sub_img1', 'sub_img2', 'sub_img3', 'sub_img4'] as $key)
                                                            @if (!empty($material->$key))
                                                                <div
                                                                    style="position: relative; display: inline-block; margin: 5px;">
                                                                    <img src="{{ asset('storage/' . $material->$key) }}"
                                                                        alt="Sub Image" width="70">

                                                                    <!-- Delete Icon -->
                                                                    <button type="button" class="delete-subimage"
                                                                        data-id="{{ $material->id }}"
                                                                        data-field="{{ $key }}"
                                                                        style="position: absolute; top: 0; right: 0; background: transparent; color: red; border: none; padding: 5px; cursor: pointer;">
                                                                        <i class="bi bi-trash3-fill"
                                                                            style="font-size: 16px;"></i>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            @error('material_sub_img')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Video -->
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="video">Video</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="file" name="video" id="video"
                                                    accept="video/mp4,video/mov">
                                                @if (isset($material) && $material->video)
                                                    <video width="150" height="100" controls class="mt-2">
                                                        <source src="{{ asset('storage/' . $material->video) }}"
                                                            type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @endif
                                            </div>
                                            @error('video')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Shades -->
                                    <h5 class="mt-4">Shades</h5>
                                    <div id="shades-container">
                                        @if (isset($material) && $material->shades->count() > 0)
                                            @foreach ($material->shades as $key => $shade)
                                                <div class="row align-items-center shade-row">
                                                    <input type="hidden" name="shade_id[]" value="{{ $shade->id }}">

                                                    <!-- Shade Name -->
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="shade_name">Shade Name</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <input type="text" name="shade_name[]"
                                                                class="form-control" value="{{ $shade->shade_name }}">
                                                        </div>
                                                    </div>

                                                    <!-- Shade Images -->
                                                    <div class="col-lg-2">
                                                        <div class="form-group">
                                                            <label for="shade_img">Shade Images (Max: 25)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="d-flex">
                                                            <input type="file" id="shade_img_{{ $key }}"
                                                                name="shade_img[{{ $key }}][]"
                                                                accept=".jpg,.jpeg,.png,.webp" multiple>
                                                            <button type="button" class="btn btn-success ms-2 add-shade">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        @error('shade_img')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Existing Images -->
                                                    <div class="col-lg-12">
                                                        @if ($shade->shadeImage->count())
                                                            <div class="mt-2 d-flex flex-wrap">
                                                                @foreach ($shade->shadeImage as $image)
                                                                    <div
                                                                        style="position: relative; display: inline-block; margin: 5px; width: 100px; height: 100px;">

                                                                        <img src="{{ asset('storage/' . $image->shade_img) }}"
                                                                            alt="Shade Image"
                                                                            style="width: 100%; height: 100%; object-fit: contain; border: 1px solid #ddd; border-radius: 5px;">

                                                                        <button type="button" class="delete-shadeimage"
                                                                            data-id="{{ $shade->id }}"
                                                                            data-path="{{ $image->shade_img }}"
                                                                            style="position: absolute; top: 0; right: 0; background: transparent; color: red; border: none; padding: 5px; cursor: pointer;">
                                                                            <i class="bi bi-trash3-fill"
                                                                                style="font-size: 16px;"></i>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- Add New Shade --}}
                                            <div class="row align-items-center shade-row">
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="shade_name">Shade Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <input type="text" name="shade_name[]" class="form-control"
                                                            placeholder="Enter Shade Name">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="shade_img">Shade Images (Max: 25)</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="d-flex">
                                                        <input type="file" name="shade_img[0][]"
                                                            accept=".jpg,.jpeg,.png,.webp" multiple>
                                                        <button type="button" class="btn btn-success ms-2 add-shade">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    @error('shade_img')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="card-action text-end">
                                        <button
                                            class="btn btn-success">{{ isset($material) ? 'Update' : 'Submit' }}</button>
                                        <button class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>
                                <!-- Hidden Form for Deleting Sub Images -->
                                <form id="deleteSubImageForm" method="POST"
                                    action="{{ route('material.deleteImage') }}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="id" id="deleteSubImageId">
                                    <input type="hidden" name="field" id="deleteSubImageField">
                                </form>
                                <!-- Hidden Form for Deleting Shade Images -->
                                <form id="deleteShadeImageForm" method="POST" action="{{ route('shade.deleteImage') }}"
                                    style="display: none;">
                                    @csrf
                                    <input type="hidden" name="shade_id" id="deleteShadeImageId">
                                    <input type="hidden" name="shade_img" id="deleteShadeImagePath">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Spinner -->
    <div class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary d-none" role="status" id="loadingSpinner">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- for Dynamic Fields -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{ asset('admin/assets/js/aggregator.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const categorySelect = document.getElementById("category_id");
            const subcategorySelect = document.getElementById("subcategory");
            const hiddenCategoryId = document.getElementById("hidden_category_id");

            var selectedCategoryId = "{{ isset($material) ? $material->category_id : '' }}";
            var selectedSubcategoryId = "{{ isset($material) ? $material->sub_category_id : '' }}";

            // Function to fetch subcategories based on category ID
            function fetchSubcategories(categoryId, preselectedSubcategory = null) {
                if (!categoryId) {
                    subcategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                    return;
                }

                subcategorySelect.innerHTML = '<option value="">Loading...</option>'; // Show loading state

                fetch(`/api/get-subcategories/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        subcategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                        if (data.data.length > 0) {
                            data.data.forEach(subcategory => {
                                let option = document.createElement("option");
                                option.value = subcategory.id;
                                option.textContent = subcategory.sub_category;
                                if (preselectedSubcategory && subcategory.id ==
                                    preselectedSubcategory) {
                                    option.selected = true; // Pre-select subcategory
                                }
                                subcategorySelect.appendChild(option);
                            });
                        } else {
                            subcategorySelect.innerHTML =
                                '<option value="">No Subcategories Available</option>';
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching subcategories:", error);
                        subcategorySelect.innerHTML = '<option value="">Error Loading</option>';
                    });
            }

            // Ensure hidden input is updated when page loads
            if (selectedCategoryId) {
                categorySelect.value = selectedCategoryId;
                hiddenCategoryId.value = selectedCategoryId; // Update hidden field
                fetchSubcategories(selectedCategoryId, selectedSubcategoryId); // Fetch subcategories on page load
            }

            // Update hidden field and fetch subcategories when category is changed
            categorySelect.addEventListener("change", function() {
                let categoryId = this.value;
                hiddenCategoryId.value = categoryId; // Store selected category ID
                fetchSubcategories(categoryId); // Fetch new subcategories
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-subimage").forEach(button => {
                button.addEventListener("click", function() {
                    let materialId = this.getAttribute("data-id");
                    let field = this.getAttribute("data-field");
                    document.getElementById("deleteSubImageId").value = materialId;
                    document.getElementById("deleteSubImageField").value = field;

                    document.getElementById("deleteSubImageForm").submit();
                });
            });
        });
        //Shade image delete
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-shadeimage").forEach(button => {
                button.addEventListener("click", function() {
                    let shadeId = this.getAttribute("data-id");
                    let shadeImg = this.getAttribute("data-path");
                    document.getElementById("deleteShadeImageId").value = shadeId;
                    document.getElementById("deleteShadeImagePath").value = shadeImg;
                    document.getElementById("deleteShadeImageForm").submit();
                });
            });
        });
        //Spinner and Alert
        document.addEventListener("DOMContentLoaded", function() {
            let alert = document.querySelector('.alert');
            const form = document.getElementById('aggregatorForm');
            const spinner = document.getElementById('loadingSpinner');

            //Success alert handling
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    window.location.href = "{{ route('material-list') }}";
                }, 3000);
            }

            //Show spinner only on job form submission
            if (form && spinner) {
                form.addEventListener('submit', function(event) {
                    spinner.classList.remove('d-none'); //Show spinner
                });
            }
        });
    </script>
@endsection
