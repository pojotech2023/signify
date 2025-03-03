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
                            <div class="row">
                                <form action="{{ route('aggregator-store') }}" method="POST" enctype="multipart/form-data"
                                    class="container">
                                    @csrf
                                    <h5 class="mt-4">Category</h5>
                                    <div class="row align-items-center mt-5">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="category_id">Category</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select id="category_id" name="category_id" class="form-select">
                                                    <option value="" selected disabled>Select Category</option>
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
                                    <!-- Material Name & Image -->
                                    <h5 class="mt-4">Materials</h5>
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="material_name">Material Name</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <input type="text" name="material_name" class="form-control">
                                            </div>
                                            @error('material_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="material_main_img">Material Main Image</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="d-flex">
                                                <input type="file" name="material_main_img"
                                                    accept=".webp,.jpg,.jpeg,.png" class="form-control">
                                            </div>
                                            @error('material_main_img')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="material_sub_img">Material Sub Image</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="d-flex">
                                                <input class="form-control" type="file" id="material_sub_img"
                                                    name="material_sub_img[]" accept=".webp,.jpg,.jpeg,.png" multiple>
                                            </div>
                                            @error('material_sub_img')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Shade Name & Image -->
                                    <h5 class="mt-4">Shades</h5>
                                    <div id="shades-container">
                                        <div class="row align-items-center shade-row">
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="shade_name">Shade Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" name="shade_name[]" class="form-control">
                                                </div>
                                                @error('shade_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label for="shade_img">Shade Image</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="d-flex">
                                                    <input type="file" name="shade_img[]"
                                                        accept=".web,.jpg,.jpeg,.png" class="form-control">
                                                    <button type="button" class="btn btn-success ms-2 add-shade"><i
                                                            class="fas fa-plus"></i></button>
                                                </div>
                                                @error('shade_img')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-action text-end">
                                        <button class="btn btn-success">Submit</button>
                                        <button class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery for Dynamic Fields -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/assets/js/aggregator.js') }}"></script>
@endsection
