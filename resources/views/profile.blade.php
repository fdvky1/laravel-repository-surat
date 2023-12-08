@extends('layouts.admin')

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileImage = document.getElementById('profileImage');
        const fileInput = document.getElementById('profile_photo');
        const submitButton = document.getElementById('submitPhoto');

        profileImage.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewImg = document.getElementById('preview_image')
                    if(previewImg){
                        previewImg.src = event.target.result;
                    }
                    document.getElementById('photoForm').submit();
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Profile') }}</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

    <div class="col-lg-4 order-lg-2">
    <div class="card shadow mb-4">
        <div class="card-body">

            <!-- Form for uploading photo (hidden) -->
            <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm" class="d-none">
                @csrf
                <input type="file" id="profile_photo" name="profile_photo" class="d-none">
                <button type="submit" id="submitPhoto" class="d-none">Upload</button>
            </form>

            <!-- Profile Image Display (clickable) -->
            <div class="card-profile-image mt-4 mx-auto" id="profileImage" style="position: relative; width: 180px; height: 180px; overflow: hidden; border-radius: 50%;">
                @if(Auth::user()->profile_photo != '')
                    <img id="preview_image" src="{{ asset('storage/profiles/' . Auth::user()->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded-circle font-weight-bold" style="object-fit: cover; width: 180px; height: 180px;">
                @else
                    <figure class="rounded-circle avatar avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;" data-initial="{{ Auth::user()->name[0] }}"></figure>
                @endif
                <div style="position: absolute; bottom: 0; width: 100%; height: 30%; background-color: rgba(0, 0, 0, 0.5); ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#FFF" style="opacity: 0.8; margin-top: 9px;" height="25" width="25" viewBox="0 0 512 512" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M149.1 64.8L138.7 96H64C28.7 96 0 124.7 0 160V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H373.3L362.9 64.8C356.4 45.2 338.1 32 317.4 32H194.6c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                </div>
            </div>
            <!-- Profile details -->
            <div class="text-center mt-4">
                <h5 class="font-weight-bold">{{ Auth::user()->fullName }}</h5>
                <p style="text-transform: capitalize;">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>
</div>


        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">My Account</h6>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <input type="hidden" name="_method" value="PUT">

                        <h6 class="heading-small text-muted mb-4">User information</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Name<span class="small text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name" placeholder="Name" value="{{ old('name', Auth::user()->name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="last_name">Last name</label>
                                        <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last name" value="{{ old('last_name', Auth::user()->last_name) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">Email address<span class="small text-danger">*</span></label>
                                        <input type="email" id="email" class="form-control" name="email" placeholder="example@example.com" value="{{ old('email', Auth::user()->email) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="current_password">Current password</label>
                                        <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Current password">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="new_password">New password</label>
                                        <input type="password" id="new_password" class="form-control" name="new_password" placeholder="New password">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="confirm_password">Confirm password</label>
                                        <input type="password" id="confirm_password" class="form-control" name="password_confirmation" placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
