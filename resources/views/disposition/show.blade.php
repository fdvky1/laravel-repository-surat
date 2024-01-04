@extends('layouts.admin')
@push('script')
<script src="https://cdn.tiny.cloud/1/y6wavuusx1p97c3is204dtbd5rujyn94wh8yh54sy0flklzf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      branding: false,
      height: 300,
      selector: 'textarea[name="note"]',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
<script>
    const status = document.querySelector('select[name="status"]');
    status.value = "{{ $disposition->status == 'pending' ? '' : $disposition->status }}"
    
    status.onchange = (e) => {
        document.querySelector('#btn-save').disabled = e.target.value == ''
        document.querySelector('input[name="status"]').value = e.target.value;
        document.querySelector("#updateModalTitle").textContent = e.target.value == "accept" ? "Approve" : e.target.value == "require_revision" ? "Require Revision" : "Reject"
    }
</script>
@endpush

@section('main-content')

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

    <x-letter-card :letter="$data" :type="$data->type">
        <div class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" method="POST" action="{{ route('dispositions.update', $disposition->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="letter_id" value="{{ $data->id }}">
                    <input type="hidden" name="status">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalTitle" style="text-transform: capitalize;">updateModalTitle</h5>
                        <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path stroke="currentColor" stroke-width="2" d="M3.146 3.146a.5.5 0 0 1 .708 0L8 7.293l4.146-4.147a.5.5 0 0 1 .708.708L8.707 8l4.147 4.146a.5.5 0 0 1-.708.708L8 8.707l-4.146 4.147a.5.5 0 0 1-.708-.708L7.293 8 3.146 3.854a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group focused">
                            <label class="form-control-label" for="note">Note<span class="small text-danger">*</span></label>
                            <textarea id="note" class="form-control" name="note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <p class="text-danger">*Once submitted you cannot change it again</p> --}}
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-2">
            <hr/>
            <dl class="row mt-3">

                <dt class="col-sm-3">Letter date</dt>
                <dd class="col-sm-9">{{ $data->formatted_letter_date }}</dd>

                @if($data->type == 'incoming')
                <dt class="col-sm-3">Received date</dt>
                <dd class="col-sm-9">{{ $data->formatted_received_date }}</dd>
                @endif

                <dt class="col-sm-3">Letter number</dt>
                @if($data->type == 'incoming')
                <dd class="col-sm-9">{{ $data->letter_number }}</dd>
                @else
                <dd class="col-sm-9">{{ $data->status == 'published' ? $data->letter_number : '-' }}/{{ $data->classification_code }}/{{ $data->month }}/{{ $data->year }}</dd>
                @endif

                <dt class="col-sm-3">Code</dt>
                <dd class="col-sm-9">{{ $data->classification_code }}</dd>

                <dt class="col-sm-3">Classification</dt>
                <dd class="col-sm-9">{{ $data->classification->description }}</dd>

                @if($data->type == 'incoming')
                <dt class="col-sm-3">From</dt>
                <dd class="col-sm-9">{{ $data->from }}</dd>
                @else
                <dt class="col-sm-3">To</dt>
                <dd class="col-sm-9">{{ $data->to }}</dd>
                @endif

                <dt class="col-sm-3">Created By</dt>
                <dd class="col-sm-9">{{ $data->user->name }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9" style="text-transform: capitalize;">{{ $data->status == 'pending' ? 'waiting for review' : ($data->status == 'require_revision' ? 'Revision Required' : $data->status) }}</dd>

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $data->formatted_created_at }}</dd>

                <dt class="col-sm-3">Updated At</dt>
                <dd class="col-sm-9">{{ $data->formatted_updated_at }}</dd>
            </dl>
            @if(count($data->notes))
            <div class="mb-2">
                <p class="mb-1">Notes:</p>
                <ul style="padding-left: 0.25rem; list-style-type: none; max-width: max-content;">
                    @foreach($data->notes as $note)
                        <li class="mb-2" style="background-color: #f1f5f9; padding-left: 0.25rem; padding-right: 0.25rem; border-radius: 0.25rem;">
                            <div>
                                <p class="font-weight-bold mb-1">{{$note->user->full_name}}</p>
                                <div style="" class="ml-1">
                                    {!!$note->note!!}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($data->status == 'disposition')
            <div class="mb-2">
                <p class="mb-1">Waiting approval from:</p>
                <div>
                    @foreach($data->dispositions as $disposition)
                        <span class="badge badge-{{ $disposition->status == 'accept' ? 'primary' : ($disposition->status == 'reject' ? 'danger' : 'warning')}} mx-1" style="padding: 0.5rem;">{{$disposition->user->fullname}}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div>
            <div class="form-group focused">
                <label class="form-control-label" for="status">Select Action</label>
                <div class="d-sm-flex" style="gap: 0.5rem;">
                    <select class="form-control mb-1" name="status" style="max-width: 10rem;" id="status" @if($disposition->status != 'pending') {{'disabled'}} @endif>
                        <option value="">Select</option>
                        <option value="accept">Approve</option>
                        <option value="require_revision">Require Revision</option>
                        <option value="reject">Reject</option>
                    </select>
                    <button type="button" class="btn btn-primary" id="btn-save" disabled data-toggle="modal" data-target="#updateModal">save</button>
                </div>
            </div>
        </div>
    </x-letter-card>

@endsection
