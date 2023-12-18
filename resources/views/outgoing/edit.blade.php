@extends('layouts.admin')

@push('script')
<script src="https://cdn.tiny.cloud/1/y6wavuusx1p97c3is204dtbd5rujyn94wh8yh54sy0flklzf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    branding: false,
    selector: 'textarea[name="content"]',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
@endpush

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Edit Outgoing Letter</h1>

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
    <form method="POST" action="{{ route('outgoing.update', $letter->id) }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="outgoing">
        <h6 class="heading-small text-muted mb-4">Letter Information</h6>

        <div class="pl-lg-4">
            <!-- Field untuk edit -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="classification_code">Classification code<span class="small text-danger">*</span></label>
                        <select class="fstdropdown-select" style="text-transform: capitalize;" id="classification_code" name="classification_code">
                            <option value="">Select</option>
                            @foreach($classifications as $classification)
                                <option
                                    value="{{ $classification->code }}"
                                    @if(old('classification_code', $letter->classification_code) == $classification->code) selected @endif>
                                    {{ $classification->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="letter_date">Letter date<span class="small text-danger">*</span></label>
                        <input type="date" id="letter_date" class="form-control" name="letter_date" value="{{ old('letter_date', $letter->letter_date ? date('Y-m-d', strtotime($letter->letter_date)) : '') }}">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="to">To</label>
                        <input type="text" name="to" id="to" class="form-control" value="{{ old('to', $letter->to) }}">
                    </div>
                </div>
            </div>

            <div>
                <div class="form-group focused">
                    <label class="form-control-label" for="regarding">Regarding<span class="small text-danger"></span></label>
                    <textarea id="regarding" class="form-control" name="regarding">{{ old('regarding', $letter->regarding) }}</textarea>
                </div>
            </div>
            <div>
                <div class="form-group focused">
                    <label class="form-control-label" for="content">Content<span class="small text-danger"></span></label>
                    <textarea id="content" class="form-control" name="content">{{ old('content', $letter->content) }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group focused">
                        <label class="form-control-label" for="note">Note<span class="small text-danger"></span></label>
                        <input type="text" id="note" class="form-control" name="note" value="{{ old('note', $letter->note) }}">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group focused">
                        <label class="form-control-label" for="attachments">Attachment<span class="small text-danger">*</span></label>
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
