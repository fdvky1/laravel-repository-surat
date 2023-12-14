@extends('layouts.admin')
@if(Auth::user()->role != 'user')
@push('script')
<script>
    const status = document.querySelector('[name="status"]');
    status.value = "{{ $data-> status }}"
    const note = document.querySelector('[name="note"]');
    const btnSubmit = document.querySelector('#btn-submit');
    const selectUser = document.querySelector('select[id="user"]');
    let users = [];

    status.onchange = (e) => {
        if(selectUser){
            document.querySelector('#select-container').classList[e.target.value == 'disposition' ? 'remove': 'add']('d-none');
        }
        note.classList[e.target.value == 'published' ? 'add' : 'remove']("d-none");
        btnSubmit.disabled = e.target.value == '';
    }

    if(selectUser){
        selectUser.onchange = (e) => {
            const [id, name] = e.target.value.split("|");
            if(users.length > 0 && users.find(v => v.id === id)) return;
            e.target.value = '';
            users.push({
                id,
                name
            })
            document.querySelector('#badge-container').insertAdjacentHTML('beforeend', `<input type="hidden" name="selected_users[]" id="in-user-${id}" value="${id}"><span id="user-${id}" class="badge badge-primary mx-1" style="padding: 0.5rem;">${name} <button class="btn btn-none text-light" style="padding: 0px;" type="button" onClick="deleteSelectedUser('${id}')">x</button></span>`);
        }
    }

    function deleteSelectedUser(id){
        users = users.filter(v => v.id != id);
        document.querySelector(`#user-${id}`).remove();
        document.querySelector(`#in-user-${id}`).remove();
    }
</script>
@endpush
@endif

@section('main-content')

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
        @if(Auth::user()->role != 'user')
        <div>
            <form method="POST" action="{{ route('letter.status') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="letter_id" value="{{ $data->id }}">
                <div class="form-group focused">
                    <label class="form-control-label" for="status">Change Status</label>
                    <div class="d-sm-flex" style="gap: 0.5rem;">
                        <select class="form-control mb-1" name="status" style="max-width: 10rem;" id="status" @if(in_array($data->status,['published', 'rejected', 'disposition'])) {{'disabled'}} @endif>
                            <option value="">Select Status</option>
                            <option value="published">Publish</option>
                            <option value="require_revision">Require Revision</option>
                            <option value="rejected">Rejected</option>
                            <option value="disposition">Disposition</option>
                        </select>
                        @if(count($users))
                        <div class="d-none mb-1" style="min-width: 15rem;" id="select-container">
                            <select class="fstdropdown-select" style="text-transform: capitalize;" id="user">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option
                                        value="{{ $user->id }}|{{ $user->fullname }}"
                                        @selected(old('id') == $user->id)>
                                        {{ $user->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <input type="text" name="note" id="note" class="form-control d-none mb-1" style="max-width: 13rem;" placeholder="Note">
                        <button type="submit" class="btn btn-primary" id="btn-submit" disabled>save</button>
                    </div>
                </div>
                <div id="badge-container">
                </div>
            </form>
        </div>
        @endif
    </x-letter-card>

@endsection
