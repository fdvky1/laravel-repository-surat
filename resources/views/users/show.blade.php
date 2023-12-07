@extends('layouts.admin')

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
                            <img id="preview_image" src="{{ asset('storage/profiles/' . Auth::user()->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded-circle font-weight-bold" style="object-fit: cover; width: 180px; height: 180px;">
                            @else
                                <figure class="rounded-circle avatar avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;" data-initial="{{ $user->name[0] }}"></figure>
                            @endif
                                <h5 class="mb-0 mt-2">{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-8 mt-5 ">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>User ID:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->id }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Last Name:</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $user->last_name }}
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
