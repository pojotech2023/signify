<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="container">
        <h1 class="my-4">Dashboard</h1>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Parent Mobile</th>
                    <th>Joining Date</th>
                    <th>Advance</th>
                    <th>Rent</th>
                    <th>Room No</th>
                    <th>Address</th>
                    <th>Action</th>
                    <!-- <th>Aadhar Card</th>
                <th>Company ID Card</th>
                <th>Signature</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->parent_mobile }}</td>
                    <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('d-m-Y') }}</td>
                    <td>{{ $user->advance }}</td>
                    <td>{{ $user->rent }}</td>
                    <td>{{ $user->room_no}}</td>
                    <td>{{ $user->address }}</td>
                    <td>
                        <a href="{{route('more',['id'=>$user->id])}}">More</a>
                        <i class="fas fa-trash text-danger delete-icon" data-id="{{ $user->id }}"
                            style="cursor: pointer; margin-left: 10px;" data-bs-toggle="modal" data-bs-target="#deleteModal"></i>

                    </td>

                    <!-- <td>
                        @if($user->aadhar_card_path)
                            <a href="{{ asset('storage/'.$user->aadhar_card_path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$user->aadhar_card_path) }}" width="100%" height="auto" alt="Aadhar">
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->company_id_card_path)
                            <a href="{{ asset('storage/'.$user->company_id_card_path) }}" target="_blank">View</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->signature_path)
                            <a href="{{ asset('storage/'.$user->signature_path) }}" target="_blank">View</a>
                        @else
                            N/A
                        @endif
                    </td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center">No data available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $applications->links('pagination::bootstrap-5') }}
        </div>

        <div class="modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer" style="display: block;">
                    <form id="deleteForm" action="{{ route('delete.application', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger mt-2">Delete</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete Confirmation Modal -->
        <!-- <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" action="{{ route('delete.application', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- JavaScript to Set Form Action Dynamically -->
        <script>
            $(document).ready(function() {
                $('.delete-icon').click(function() {
                    var id = $(this).data('id');
                    var action = "{{ route('delete.application', ':id') }}";
                    action = action.replace(':id', id);
                    $('#deleteForm').attr('action', action);
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>