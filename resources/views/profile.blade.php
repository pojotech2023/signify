<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Signify</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#4302b2">

    <link href="images/logo/logo.png" rel="icon">
    <link href="css/swiper.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugin.min.css" rel="stylesheet">
    <link href="css/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="css/darkmode.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .profile {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .profile-card {
            height: 100%;
            width: 100%;
        }

        /* .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        } */
    </style>
</head>

<body>
    @include('header')

    <section class="profile-page pad-tb mt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Left Side: Profile Image -->
                <div class="col-lg-6 contact2dv">
                    <div class="info-wrapr">
                        <div class="text-center mb-4">
                            <!-- Profile Image -->
                            <img src="./images/signify/profile1.webp" alt="User Image" class="profile-img">
                        </div>
                    </div>
                </div>

                <!-- Right Side: Profile Form -->
                <div class="col-lg-6 m-mt30 pr30 pl30">
                    <div class="common-heading text-l">
                        <h2 class="mt0 mb0">Update Profile</h2>
                        <p class="mb60 mt10">Please update your details below</p>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                        <div class="mb-3 row">
                            <label for="name" class="col-sm-4 col-form-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $user->name) }}" placeholder="Enter name">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="mobile_no" class="col-sm-4 col-form-label">Mobile</label>
                            <div class="col-sm-8">
                                <input type="tel" class="form-control" id="mobile_no"
                                value="{{ old('mobile_no', $user->mobile_no) }}" placeholder="Enter mobile number" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" placeholder="Enter email">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Gender</label>
                            <div class="col-sm-8 pt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="male"
                                        value="Male"  {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}
                                        style="padding:5px">
                                    <label class="form-check-label" for="male" style="margin-top: 0.2rem;">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="female"
                                        value="Female" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}
                                        style="padding:5px">
                                    <label class="form-check-label" for="female" style="margin-top: 0.2rem;">Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="company_name" class="col-sm-4 col-form-label">Company Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                value="{{ old('company_name', $user->company_name) }}" placeholder="Enter company name">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="designation" class="col-sm-4 col-form-label">Designation</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="designation" name="designation" 
                                value="{{ old('designation', $user->designation) }}" placeholder="Enter designation">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary w-50">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    @include('footer')

</body>

</html>
