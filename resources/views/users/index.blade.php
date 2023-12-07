@extends('layouts.admin')
<!-- @push('scripts')
    <script>
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                event.preventDefault();
                document.getElementById('delete-form-' + userId).submit();
            }
        }
    </script>
@endpush -->
@section('main-content')
<div class="container">
        <div class="row">
            <div class="col-md-12">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
                <div class="d-flex justify-content-between ">
                    <div>
                        <h2>All Users</h2>
                    </div>
                    <div>
                        <a href="users/create">
                        <button type="button" class="btn btn-success">Create User +</button>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/profiles/' . $user->profile_photo) }}" class="img-fluid rounded-circle avatar font-weight-bold" alt="">
                                    @else
                                        <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ $user->name[0] }}"></figure>
                                    @endif
                                    <a href="{{ route('users.show', ['id' => $user->id]) }}">
                                    {{ Str::limit($user->name, 20) }} {{ Str::limit($user->last_name, 20) }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($user->email, 20) }}</td>
                                <td>{{ Str::limit($user->role, 20) }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-primary">Edit</a>
                                    <button class="btn btn-danger" onclick="if (confirm('Are you sure you want to delete this user?')) { event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit(); }">Delete</button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
