<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 1rem;
            background-color: #f9f9f9;
        }

        form {

            margin: 0 auto;
            padding: 1.5rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .signature-area {
            display: none;
        }

        @media (max-width: 768px) {
            .signature-area {
                display: block;
            }
        }

        button {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

<div class="container">

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Aadhar Card Front</th>
                <th>Aadhar Card Back</th>
                <th>Employee Image</th>
                <th>Company ID Card</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>
                        @if($application->aadhar_front_path)
                            <a href="{{ asset('storage/' .$application->aadhar_front_path) }}" target="_blank">
                                <img src="{{ asset('storage/' .$application->aadhar_front_path) }}" width="100%" height="100px" style="object-fit: cover;" alt="Aadhar Front">
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($application->aadhar_back_path)
                            <a href="{{ asset('storage/' .$application->aadhar_back_path) }}" target="_blank">
                                <img src="{{ asset('storage/' .$application->aadhar_back_path) }}" width="100%" height="100px" style="object-fit: cover;" alt="Aadhar Back">
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($application->employee_img_path)
                            <a href="{{ asset('storage/' .$application->employee_img_path) }}" target="_blank">
                                <img src="{{ asset('storage/' .$application->employee_img_path) }}" width="100%" height="100px" style="object-fit: cover;" alt="Employee Image">
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($application->company_id_card_path)
                            <a href="{{ asset('storage/' .$application->company_id_card_path) }}" target="_blank">
                            <img src="{{ asset('storage/' .$application->company_id_card_path) }}" width="100%" height="100px" style="object-fit: cover;" alt="company id">
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($application->signature_path)
                            <a href="{{ asset('storage/' .$application->signature_path) }}" target="_blank">
                            <img src="{{ asset('storage/' .$application->signature_path) }}" width="100%" height="100px" style="object-fit: cover;" alt="signature">
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            
            </tbody>
    </table>
</div>

</body>

</html>