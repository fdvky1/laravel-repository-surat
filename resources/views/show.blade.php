@extends('layouts.admin')
@if(Auth::user()->role != 'user')
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
            let status = "";
            const selectUser = document.querySelector('select[id="user"]');
            let users = [];
            $(document).on('click', '.btn-change-status', function () {
                status = $(this).data("status");
                document.querySelector('input[name="status"]').value = status;
                document.querySelector("#updateModalTitle").textContent = status == "rejected" ? "Reject" : status == "published" ? "Publish" : status == "require_revision" ? "Require Revision" : status
                if(selectUser){
                    document.querySelector('#select-container').classList[status == 'disposition' ? 'remove': 'add']('d-none');
                }
                document.querySelector('#warn-publish').classList[status == 'published' ? 'remove': 'add']('d-none');
            })
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
        <div class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" method="POST" action="{{ route('letter.status') }}">
                    @csrf
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
                        @if(count($users))
                        <div class="d-none mb-1" id="select-container">
                            <div class="form-group focused">
                                <label for="user" class="form-control-label">Select user<span class="small text-danger">*</span></label>
                                <select class="fstdropdown-select" style="text-transform: capitalize;" id="user">
                                    <option value="">Select</option>
                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}|{{ $user->fullname }}"
                                            @selected(old('id') == $user->id)>
                                            {{ $user->fullname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="badge-container" class="mb-2">
                        </div>
                        @endif
                        <div class="form-group focused">
                            <label class="form-control-label" for="note">Note<span class="small text-danger">*</span></label>
                            <textarea id="note" class="form-control" name="note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="text-danger d-none" id="warn-publish">*Once the letter is published it cannot be revised again</p>
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
                        <span class="badge badge-{{ $disposition->status == 'accept' ? 'success' : ($disposition->status == 'reject' ? 'danger' : 'warning')}} mx-1" style="padding: 0.5rem;">{{$disposition->user->fullname}}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @if(Auth::user()->role != 'user')
        <div>
            <p class="font-weight-bold mb-1">Change status</p>
            <div class="d-flex" style="gap: 0.25rem;">
                <button class="btn btn-change-status btn-primary" data-toggle="modal" data-target="#updateModal" data-status="published" @if(in_array($data->status,['published', 'rejected'])) {{'disabled'}} @endif>Publish</button>
                <button class="btn btn-change-status btn-secondary" data-toggle="modal" data-target="#updateModal" data-status="require_revision" @if(in_array($data->status,['published', 'rejected'])) {{'disabled'}} @endif>Require Revision</button>
                <button class="btn btn-change-status btn-info" data-toggle="modal" data-target="#updateModal" data-status="disposition" @if(in_array($data->status,['published', 'rejected'])) {{'disabled'}} @endif>Disposition</button>
                <button class="btn btn-change-status btn-danger" data-toggle="modal" data-target="#updateModal" data-status="rejected" @if(in_array($data->status,['published', 'rejected'])) {{'disabled'}} @endif>Reject</button>
            </div>
        </div>
        @endif
    </x-letter-card>

@endsection
