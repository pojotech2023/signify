<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signify</title>
    <link href="images/logo/logo.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 24px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row shadow-lg rounded p-4">
           
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <a href="{{ route('login') }}" class="text-decoration-none text-dark">&lt; Back to Login</a>
                <h1 class="mt-3 text-uppercase">OTP Verification</h1>
                <p style="color: rgb(104, 104, 104)">An authentication code has been sent to your mobile number</p>
                <h5 class="mt-3 text-center text-uppercase">OTP Verification</h5>
                <p class="text-center mt-3">Enter the OTP sent to - <span class="fw-bold">+91-6385791245</span></p>
    
                <div class="d-flex justify-content-center my-3">
                    <input type="text" class="form-control text-center mx-1 otp-input" maxlength="1">
                    <input type="text" class="form-control text-center mx-1 otp-input" maxlength="1">
                    <input type="text" class="form-control text-center mx-1 otp-input" maxlength="1">
                    <input type="text" class="form-control text-center mx-1 otp-input" maxlength="1">
                </div>
    
                <p class="text-center mt-2">
                    Didn't receive a code? 
                    <a href="#" class="text-danger fw-bold">Resend</a>
                </p>
    
                <div class="text-center">
                    <a href="{{ route('form') }}" class="btn btn-primary w-100 mt-3">Verify</a>
                </div>
            </div>
    
            <div class="col-md-6 d-none d-md-block text-center">
                <img src="{{ asset('images/signify/otp.jpg') }}" alt="OTP Verification" class="img-fluid rounded">
            </div>
        </div>
    </div>
     <!-- Include Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>