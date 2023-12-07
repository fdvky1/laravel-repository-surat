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
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
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
                            </tr>
                        @endforeach
                    </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
