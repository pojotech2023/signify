<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="utf-8" />
    <title>Signify</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link href="images/logo/icms.png" rel="icon">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugin.min.css" rel="stylesheet">
    <link href="css/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="css/darkmode.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">
    <style>
        .selectable-image {
            cursor: pointer;
            transition: transform 0.2s ease;
            border: 3px solid transparent;
        }

        .selectable-image:hover {
            transform: scale(1.05);
        }

        .selectable-image.selected {
            border-color: rgb(63 111 218);
            box-shadow: 0 0 10px rgb(63 111 218);
        }

    </style>

</head>

<body>

    @include('header')

    <section class="service-section-app  dark-bg2 pb-5 mt100">
        <form action="{{ route('aggregatorform') }}" method="POST" enctype="multipart/form-data" class="container">
            <h3 class="text-center pb-4">AGGREGATOR FORM</h3>
            @csrf
            <div class="row align-items-center mt-5">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="category">Choose Category</label>
                    </div>
                </div>
                <div class="col-lg-5">
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
                <div class="col-lg-5">
                    <div class="form-group">
                        <select id="subcategory" name="sub_category_id" class="form-select">
                            <option value="" selected disabled>Select Sub Category</option>
                        </select>
                        <input type="hidden" id="hidden_sub_category_id" name="sub_category_id">
                    </div>
                    @error('sub_category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="material-section" style="display: none;">
                <input type="hidden" name="material_id" id="hidden_material_id">
                <div class="row align-items-center mt-4">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="material">Select Material</label>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="card border rounded-3 p-3">
                            <div id="material-images" class="row">
                                <!-- Material images will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hover Popup for Sub Images -->
            {{-- <div id="image-popup" class="popup-container"
                style="display:none; position: absolute; background: rgba(255, 255, 255, 0.9); padding: 10px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); z-index: 1000;">
                <div id="popup-images" class="d-flex flex-wrap"></div>
            </div> --}}

            <div id="shades-section" style="display: none;">
                <input type="hidden" name="shade_id" id="hidden_shade_id">
                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="shades">Shades</label>
                        </div>
                    </div>
                    <div class="col-lg-10 mt-3">
                        <div class="card border rounded-3 p-3">
                            <div class="d-flex flex-wrap" id="shade-images">
                                <!-- Shades images will be dynamically shown here -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center mt-3">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="size">Size</label>
                    </div>
                </div>

                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="width">Width</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <input type="tel" id="width" name="width" class="form-control">
                    </div>
                    @error('width')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="height">Height</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <input type="tel" id="height" name="height" class="form-control">
                    </div>
                    @error('height')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-1">
                    <div class="form-group">
                        <label for="unit">Unit</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <select id="unit" name="unit" class="form-control">
                            <option value="">Select Unit</option>
                            <option value="cm">Cm</option>
                            <option value="inch">Inch</option>
                            <option value="ft">Ft</option>
                            <option value="mm">Mm</option>
                            <option value="m">M</option>
                        </select>
                    </div>
                    @error('unit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="location">Location</label>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <input type="text" id="location" name="location" class="form-control">
                    </div>
                    @error('location')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <input type="text" id="quantity" name="quantity" class="form-control">
                    </div>
                    @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="design_service_need">Design Service Need</label>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group d-flex align-items-center gap-2">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="designYes" name="design_service_need" value="yes"
                                class="form-check-input" style="padding:10px">
                            <label for="designYes" class="form-check-label" style="margin-top: 0.5rem;">Yes</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input type="radio" id="designNo" name="design_service_need" value="no"
                                class="form-check-input" style="padding:10px">
                            <label for="designNo" class="form-check-label" style="margin-top: 0.5rem;">No</label>
                        </div>
                    </div>
                    @error('design_service_need')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="email_id">Email</label>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group d-flex align-items-center gap-2">
                        <input type="email" id="email" name="email_id" class="form-control">
                    </div>
                    @error('email_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="site_image">Site Image</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group d-flex align-items-center gap-2">
                        <input type="file" id="site_image" name="site_image[]" accept=".pdf,.jpg,.jpeg,.png"
                            multiple>
                    </div>
                    @error('site_image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="design_attachment">Design Attachments</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group d-flex align-items-center gap-2">
                        <input type="file" id="design_attachment" name="design_attachment[]"
                            accept=".pdf,.jpg,.jpeg,.png" multiple>
                    </div>
                    @error('design_attachment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="reference_image">Reference Image</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group d-flex align-items-center gap-2">
                        <input type="file" id="reference_image" name="reference_image[]"
                            accept=".pdf,.jpg,.jpeg,.png" multiple>
                    </div>
                    @error('reference_image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex gap-5 justify-content-center mt-5">
                <button type="submit" class="btn btn-primary submit">Submit</button>
            </div>
        </form>
    </section>

    @include('footer')

    <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/plugin.min.js"></script>
    <script src="js/dark-mode.js"></script>

    <script src="js/main.js"></script>
    {{-- <script src="js/aggregator_form.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('admin/assets/js/aggregator.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            @endif
        });
    </script>
</body>

</html>
