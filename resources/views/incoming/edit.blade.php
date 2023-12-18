@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Edit Incoming Letter</h1>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 order-lg-1">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Incoming Letter</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('letter.update', $letter->id) }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="incoming">
                        <h6 class="heading-small text-muted mb-4">Letter Information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="classification_code">Classification code<span class="small text-danger">*</span></label>
                                        <select class="fstdropdown-select" style="text-transform: capitalize;" id="classification_code" name="classification_code" required>
                                            <option value="">Select</option>
                                            @foreach($classifications as $classification)
                                                <option
                                                    value="{{ $classification->code }}"
                                                    {{ old('classification_code', $letter->classification_code) == $classification->code ? 'selected' : '' }}>
                                                    {{ $classification->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="letter_number">Letter Number<span class="small text-danger">*</span></label>
                                        <input type="text" id="letter_number" class="form-control" name="letter_number" value="{{ $letter->letter_number }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="from">From<span class="small text-danger">*</span></label>
                                        <input type="text" name="from" id="from" class="form-control" value="{{ $letter->from }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="letter_date">Letter date<span class="small text-danger">*</span></label>
                                        <input type="date" id="letter_date" class="form-control" name="letter_date" value="{{ date('Y-m-d', strtotime($letter->letter_date)) }}" required>                                    </div>
                                </div>
                                <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="received_date">Received date<span class="small text-danger">*</span></label>
                                    <input type="date" id="received_date" class="form-control" name="received_date" value="{{ date('Y-m-d', strtotime($letter->received_date)) }}" required>
                                </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="regarding">Regarding<span class="small text-danger">*</span></label>
                                    <textarea id="regarding" class="form-control" name="regarding" required>{{ $letter->regarding }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="note">Note</label>
                                        <input type="text" id="note" class="form-control" name="note" value="{{ $letter->note }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="attachments">Attachment</label>
                                        <input type="file" id="attachments" class="form-control" name="attachments[]" multiple>

                                        @if ($letter->attachments->count() > 0)
                                            <div class="mt-3">
                                                <p>Existing Attachments:</p>
                                                <ul>
                                                    @foreach($letter->attachments as $attachment)
                                                        @if (strpos($attachment->mime_type, 'image') !== false)
                                                            <li>
                                                                <a href="{{ asset('storage/attachments/' . $attachment->filename) }}" target="_blank">
                                                                    <img src="{{ asset('storage/attachments/' . $attachment->filename) }}" alt="{{ $attachment->filename }}" style="max-width: 100px; max-height: 100px;">
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a href="{{ asset('storage/attachments/' . $attachment->filename) }}" target="_blank">
                                                                    {{ $attachment->filename }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
