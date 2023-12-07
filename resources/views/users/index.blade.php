@extends('layouts.admin')

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
                        <button type="button" class="btn btn-primary">Create User +</button>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Profile Photo</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <a href="{{ route('users.show', ['id' => $user->id]) }}">
                                        {{ $user->name . ' ' . $user->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->profile_photo }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
