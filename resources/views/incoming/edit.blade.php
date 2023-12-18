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
                <form action="{{ route('incoming.edit', ['id' => $id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="incoming">

                        <h6 class="heading-small text-muted mb-4">Letter Information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="classification_code">Classification code<span class="small text-danger">*</span></label>
                                        <select class="fstdropdown-select" style="text-transform: capitalize;" id="classification_code" name="classification_code">
                                            <option value="">Select</option>
                                            @foreach($classifications as $classification)
                                                <option value="{{ $classification->code }}" {{ old('classification_code', $letter->classification_code) == $classification->code ? 'selected' : '' }}>
                                                    {{ $classification->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="letter_number">Letter Number<span class="small text-danger">*</span></label>
                                        <input type="text" id="letter_number" class="form-control" name="letter_number" value="{{ old('letter_number', $letter->letter_number) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="from">From</label>
                                        <input type="text" name="from" id="from" class="form-control" value="{{ old('from', $letter->from) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="letter_date">Letter date<span class="small text-danger">*</span></label>
                                        <input type="date" id="letter_date" class="form-control" name="letter_date" value="{{ old('letter_date', date('Y-m-d', strtotime($letter->letter_date))) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="received_date">Received date<span class="small text-danger">*</span></label>
                                        <input type="date" id="received_date" class="form-control" name="received_date" value="{{ old('received_date', date('Y-m-d', strtotime($letter->received_date))) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="regarding">Regarding<span class="small text-danger"></span></label>
                                    <textarea id="regarding" class="form-control" name="regarding">{{ old('regarding', $letter->regarding) }}</textarea>
                                </div>
                            </div>
                        </div>

                        @if($attachments->count() > 0)
                        <div class="col-lg-4">
                            <div class="form-group focused">
                                <label class="form-control-label">Existing Attachments:</label>
                                <ul>
                                    @foreach($attachments as $attachment)
                                        <li>
                                            <a href="{{ asset('storage/attachments/' . $attachment->filename) }}" target="_blank">{{ $attachment->filename }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif


                        <div class="col-lg-4">
                            <div class="form-group focused">
                                <label class="form-control-label" for="attachments">Add New Attachment</label>
                                <input type="file" id="attachments" class="form-control" name="attachments[]" multiple>
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
