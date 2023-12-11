@extends('layouts.admin')
@push('script')
    <script>
        $(document).on('click', '.btn-edit', function () {

            const id = $(this).data('id');

            $('#editModal form').attr('action', '{{ route('users.update', '') }}/' + id);

            $('#editModal input:hidden#id').val(id);

            $('#editModal input#code').val($(this).data('code'));

            $('#editModal input#description').val($(this).data('description'));

        });
    </script>
@endpush
@section('main-content')

<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $user->name }}'s Profile</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                            @if($user->profile_photo)
                            <img id="preview_image" src="{{ asset('storage/profiles/' . Auth::user()->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded-circle font-weight-bold" style="object-fit: cover; width: 150px; height: 150px;">
                            @else
                                <figure class="rounded-circle avatar avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;" data-initial="{{ $user->name[0] }}"></figure>
                            @endif
                                <!-- <h5 class="mb-0 mt-2">{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p> -->
                                <div class="col-md-12 mt-2">
                                    <div class="text-center">
                                        <button class="btn btn-info btn-sm btn-edit mr-2" data-target="#editModal">
                                            Edit
                                        </button>
                                        <form class="d-inline" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 mt-2 ">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <strong>User ID:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->id }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Name:</strong>
                                    </div>
                                    <div class="col-md-8">
                                    {{ Str::limit($user->name, 20) }} {{ Str::limit($user->last_name, 20) }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Email:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->email }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Role:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->role  }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Created at:</strong>
                                    </div>
                                    <div class="col-md-8">
                                    {{ $user->created_at }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
