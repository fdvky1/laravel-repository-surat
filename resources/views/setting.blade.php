@extends('layouts.admin')

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const companyPhoto = document.getElementById('companyPhoto');
        const fileInput = document.getElementById('company_photo');
        const submitButton = document.getElementById('submitPhoto');

        companyPhoto.addEventListener('click', function() {
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
    <h1 class="h3 mb-4 text-gray-800">Setting</h1>

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

                    <form action="{{ route('setting.update_photo') }}" method="POST" enctype="multipart/form-data" id="photoForm" class="d-none">
                        @csrf
                        <input type="file" id="company_photo" name="company_photo" class="d-none">
                        <button type="submit" id="submitPhoto" class="d-none">Upload</button>
                    </form>

                    <div class="card-profile-image mt-4 mx-auto" id="companyPhoto" style="position: relative; width: 180px; height: 180px; overflow: hidden;">
                        <img id="preview_image" src="{{ asset('storage/' . $data->image_name) }}" alt="Company Photo" class="img-fluid font-weight-bold" style="object-fit: cover; width: 180px; height: 180px;">
                        <div style="position: absolute; bottom: 0; width: 100%; height: 30%; background-color: rgba(0, 0, 0, 0.5); ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#FFF" style="opacity: 0.8; margin-top: 9px;" height="25" width="25" viewBox="0 0 512 512" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M149.1 64.8L138.7 96H64C28.7 96 0 124.7 0 160V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H373.3L362.9 64.8C356.4 45.2 338.1 32 317.4 32H194.6c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">App setting</h6>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('setting.update') }}" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">

                        <h6 class="heading-small text-muted mb-4">Letterhead setting</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="header">Header<span class="small text-danger">*</span></label>
                                        <input type="text" id="header" class="form-control" name="header" placeholder="Header" value="{{ old('header', $data->header) }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="subheader">Subheader<span class="small text-danger">*</span></label>
                                        <input type="text" id="subheader" class="form-control" name="subheader" placeholder="Subheader" value="{{ old('subheader', $data->subheader) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="address">Address<span class="small text-danger">*</span></label>
                                        <input type="text" id="address" class="form-control" name="address" value="{{ old('address', $data->address) }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="contact">Contact<span class="small text-danger">*</span></label>
                                        <input type="text" id="contact" class="form-control" name="contact" value="{{ old('contact', $data->contact) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="position_name">Position Name<span class="small text-danger">*</span></label>
                                        <input type="text" id="position_name" class="form-control" name="position_name" value="{{ old('position_name', $data->position_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="name">Name<span class="small text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $data->name) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-right">
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
