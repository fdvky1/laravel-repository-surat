@extends('layouts.admin')

@push('script')
<script>
    $(document).ready(function () {
        $('.btn-edit').click(function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let lastName = $(this).data('last-name');
            let email = $(this).data('email');
            let role = $(this).data('role');

            $('#editModal #edit_name').val(name);
            $('#editModal #edit_last_name').val(lastName);
            $('#editModal #edit_email').val(email);
            $('#editModal #edit_role').val(role);
            $('#editModal form').attr('action', '/users/' + id);
        });
    });
</script>

@endpush
@section('main-content')


<div class="w-100 d-flex justify-content-end mb-2">
    <button data-toggle="modal" data-target="#createModal" class="btn btn-success">Create User +</button>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <div>
                <h2>All Users</h2>
            </div>
        </div>
        <div class="table-responsive">
        <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="align-middle">No</th>
                        <th class="align-middle">Name</th>
                        <th class="align-middle">Email</th>
                        <th class="align-middle">Role</th>
                        <!-- <th>Created At</th> -->
                        <th class="align-middle">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                @if ($user->profile_photo)
                                    <img src="{{ asset('storage/profiles/' . $user->profile_photo) }}" class="img-fluid rounded-circle avatar font-weight-boldk" alt="">
                                @else
                                    <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ $user->name[0] }}"></figure>
                                @endif
                                <a href="{{ route('users.show', ['id' => $user->id]) }}">
                                    {{ Str::limit($user->name, 20) }} {{ Str::limit($user->last_name, 20) }}
                                </a>
                            </td>
                            <td class="align-middle">{{ Str::limit($user->email, 20) }}</td>
                            <td class="align-middle">{{ Str::limit($user->role, 20) }}</td>
                            <!-- <td>{{ $user->created_at }}</td> -->
                            <td class="align-middle">
                                <button class="btn btn-primary btn-edit" data-toggle="modal" data-target="#editModal" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-last-name="{{ $user->last_name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                    Edit
                                </button>
                                <form class="d-inline" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-border-bottom-0">
                <tr>
                        <th class="align-middle">No</th>
                        <th class="align-middle">Name</th>
                        <th class="align-middle">Email</th>
                        <th class="align-middle">Role</th>
                        <!-- <th>Created At</th> -->
                        <th class="align-middle">Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


    <!-- Create Modal -->
    <div class="modal fade" id="createModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="post" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalTitle">Create Users</h5>
                    <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path stroke="currentColor" stroke-width="2" d="M3.146 3.146a.5.5 0 0 1 .708 0L8 7.293l4.146-4.147a.5.5 0 0 1 .708.708L8.707 8l4.147 4.146a.5.5 0 0 1-.708.708L8 8.707l-4.146 4.147a.5.5 0 0 1-.708-.708L7.293 8 3.146 3.854a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="name" name="name" />
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>
                    <!-- <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="password_confirmation" />
                    </div> -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select id="role" class="form-control" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="reviewer">Reviewer</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path stroke="currentColor" stroke-width="2" d="M3.146 3.146a.5.5 0 0 1 .708 0L8 7.293l4.146-4.147a.5.5 0 0 1 .708.708L8.707 8l4.147 4.146a.5.5 0 0 1-.708.708L8 8.707l-4.146 4.147a.5.5 0 0 1-.708-.708L7.293 8 3.146 3.854a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="edit_name" name="name" value="{{ old('name', $user->name) }}" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="edit_email" name="email" value="{{ old('email', $user->email) }}" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (optional):</label>
                        <input type="password" class="form-control" id="password" name="password" "/>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role:</label>
                        <select id="edit_role" class="form-control" name="role" required>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="reviewer" {{ old('role', $user->role) === 'reviewer' ? 'selected' : '' }}>Reviewer</option>
                            <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Pagination Section -->
<div class="row">
    <div class="col-md-12">
        {{ $users->links() }}
    </div>
</div>

@endsection





