@extends('layouts.admin')
@push('script')
<script>
    const status = document.querySelector('[name="status"]');
    status.value = "{{ $disposition->status == 'pending' ? '' : $disposition->status }}"
    const note = document.querySelector('[name="note"]');
    const btnSubmit = document.querySelector('#btn-submit');
    let users = [];

    status.onchange = (e) => {
        note.classList[e.target.value == '' ? 'add' : 'remove']("d-none");
        note.required = e.target.value == 'require_revision';
        btnSubmit.disabled = e.target.value == '';
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
                <ul style="margin-left: -2rem; list-style-type: none;">
                    @foreach($data->notes as $note)
                        <li>{{$note->user->full_name}}: {{$note->note}}</li>
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
            <form method="POST" action="{{ route('dispositions.update', $disposition->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="letter_id" value="{{$data->id}}">
                <div class="form-group focused">
                    <label class="form-control-label" for="status">Select Action</label>
                    <div class="d-sm-flex" style="gap: 0.5rem;">
                        <select class="form-control mb-1" name="status" style="max-width: 10rem;" id="status" @if($disposition->status != 'pending') {{'disabled'}} @endif>
                            <option value="">Select</option>
                            <option value="accept">Approve</option>
                            <option value="require_revision">Require Revision</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <input type="text" name="note" id="note" class="form-control d-none mb-1" style="max-width: 13rem;" placeholder="Note">
                        <button type="submit" class="btn btn-primary" id="btn-submit" disabled>save</button>
                    </div>
                </div>
            </form>
        </div>
    </x-letter-card>

@endsection
