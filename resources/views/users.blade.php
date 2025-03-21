@extends('welcome')

@section('content')
    @if ($users->count() > 0)
        @foreach ($users as $key => $user)
            @include('UserManagement.edit')
        @endforeach
    @endif
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h1 class="m-0"><i class="fas fa-users"></i> Users Management</h1>
                    </div>
                    <div class="col-md-4 text-md-right">
                        <a href="{{ route('users.export') }}" type="button" class="btn btn-secondary">
                            <i class="fas fa-copy"></i> Export Excel
                        </a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createUserModal">
                            <i class="fas fa-user-plus"></i> Add User
                        </button>
                        @include('UserManagement.create')
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0"><i class="fas fa-list"></i> User List</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('users') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <form id="bulkDeleteForm" action="{{ route('users.bulkDelete') }}" method="POST">
                        @csrf
                        @method('POST')
                        <table id="userTable" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->count() > 0)
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td><input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                    class="user-checkbox"></td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_number }}</td>
                                            <td>
                                                <span
                                                    class="badge
                                                    @if ($user->status == 'active') badge-success
                                                    @elseif($user->status == 'inactive') badge-secondary
                                                    @else badge-warning @endif">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#viewUserModal{{ $user->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button type="button" class="btn btn-info btn-sm edit-user-btn"
                                                    data-toggle="modal" data-target="#editUserModal{{ $user->id }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#deleteUserModal{{ $user->id }}">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                        @include('UserManagement.detail')
                                        @include('UserManagement.delete')
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No users found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $users->appends(request()->query())->links() }}
                        <button type="submit" id="bulkDeleteBtn" class="btn btn-danger mt-3" disabled>
                            <i class="fas fa-trash-alt"></i> Bulk Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <input type="hidden" id="sessionSuccess" value="{{ session('success') }}">
    <input type="hidden" id="sessionError" value="{{ session('errors') }}">
    <script>
        $(document).on("hidden.bs.modal", function() {
            $(".modal-backdrop").remove();
            $("body").removeClass("modal-open");
        });
        $(document).ready(function() {
            const selectAllCheckbox = $("#selectAll");
            const checkboxes = $(".user-checkbox");
            const bulkDeleteBtn = $("#bulkDeleteBtn");

            function updateBulkDeleteButton() {
                bulkDeleteBtn.prop("disabled", $(".user-checkbox:checked").length === 0);
            }

            selectAllCheckbox.on("change", function() {
                checkboxes.prop("checked", $(this).prop("checked"));
                updateBulkDeleteButton();
            });

            checkboxes.on("change", updateBulkDeleteButton);
        });

        $(".modal").on("show.bs.modal", function() {
            $(this).removeAttr("aria-hidden");
        });

        let successMessage = $("#sessionSuccess").val();
        let errorMessage = $("#sessionError").val();

        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: successMessage,
                showConfirmButton: true,
                confirmButtonText: 'Close',
                timer: 3000
            });
        }

        if (errorMessage) {
            let errorData = JSON.parse(errorMessage);
            let errorText = Object.values(errorData).flat().join("\n");
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorText,
                showConfirmButton: true,
                confirmButtonText: 'Close'
            });
        }
    </script>
@endsection
