@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Create Outgoing Letter</h1>

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

        <div class="col-lg-12 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Outgoing Letter</h6>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('incoming.store') }}" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="type" value="incoming">

                        <h6 class="heading-small text-muted mb-4">Letter Information</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="letter_number">Letter Number<span class="small text-danger">*</span></label>
                                        <input type="text" id="letter_number" class="form-control" name="letter_number">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="classification_code">Classification code<span class="small text-danger">*</span></label>
                                        <div>
                                            <select class="fstdropdown-select" style="text-transform: capitalize;" id="classification_code" name="classification_code">
                                                <option value="">Select</option>
                                                @foreach($classifications as $classification)
                                                    <option
                                                        value="{{ $classification->code }}"
                                                        @selected(old('classification_code') == $classification->code)>
                                                        {{ $classification->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="to">To</label>
                                        <select class="fstdropdown-select" id="to" name="to">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}" @selected(old('to') == $user->id)>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="letter_date">Letter date<span class="small text-danger">*</span></label>
                                        <input type="date" id="letter_date" class="form-control" name="letter_date">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="received_date">Received date<span class="small text-danger">*</span></label>
                                        <input type="date" id="received_date" class="form-control" name="received_date">
                                    </div>
                                </div>
                            </div> -->
                            <div class="">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="summary">Summary<span class="small text-danger"></span></label>
                                    <textarea id="summary" class="form-control" name="summary"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="note">Note<span class="small text-danger"></span></label>
                                        <input type="text" id="note" class="form-control" name="note">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="attachments">Attachment<span class="small text-danger">*</span></label>
                                        <input type="file" id="attachments" class="form-control" name="attachments[]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection