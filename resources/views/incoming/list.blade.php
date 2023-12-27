@extends('layouts.admin')
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

<div class="px-4">
<div class="d-flex justify-content-between">
        <div class="form-group focused">
            <label class="form-control-label" for="status">Filter by:</label>
            <form action="{{ route('incoming.list') }}" method="GET" class="d-flex justify-content-between" >
                <div class="mr-2">
                    <select class="form-control mb-1" name="status" style="max-width: 10rem;">
                        <option value="">Filter by Status</option>
                        <option value="all">All</option>
                        <option value="published">Published</option>
                        <option value="rejected">Rejected</option>
                        <option value="pending">Pending</option>
                        <option value="require_revision">Require Revision</option>
                    </select>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <div class="mx-auto">
            @if(count($data) == 0)
            <h4 class="">There are no outgoing letters yet</h4>
            @else
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3" >
            @php
                $letter = $data->first();
                $lastNum = ($letter->status == 'published')
                                ? $letter->letter_number.'/'.$letter->classification_code.'/'.$letter->month.'/'.$letter->year
                                : '-';
            @endphp

            <!-- <div class="mb-2 mr-1">
                    <a class="text-to-copy btn btn-info mb-2" data-last-number="{{ $lastNum }}">
                        Copy Last Number
                    </a>
                </div> -->

            <div class="mb-2 ml-1">
                <a href="{{ route('incoming.create') }}" class="btn btn-primary">New Letter</a>
            </div>

        </div>
    </div>
    @foreach($data as $letter)
    @if($letter)
        <a href="{{ route('letter.show', $letter->id) }}" style="text-decoration: none; color: #000;">
            <x-letter-card
                :letter="$letter"
                :type="'incoming'"
            >
                <div class="text-right mt-2" style="text-transform: capitalize;">
                    <span class="badge badge-{{ $letter->status == 'published' ? 'primary' : ($letter->status == 'rejected' ? 'danger' : 'warning')}} mx-1" style="padding: 0.5rem;">{{ $letter->status == 'pending' ? 'waiting for review' : ($letter->status == 'require_revision' ? 'Revision Required' : $letter->status) }}</span>
                </div>
            </x-letter-card>
        </a>
        @endif
     @endforeach
    @endif
</div>
@push('script')
    <script>
        document.querySelectorAll('.text-to-copy').forEach(function(element) {
            element.addEventListener('click', function() {
                const textToCopy = this.getAttribute('data-last-number');
                if (textToCopy && textToCopy !== '-') {
                    navigator.clipboard.writeText(textToCopy)
                        .then(function() {
                            alert('Last number copied successfully!');
                        })
                        .catch(function(err) {
                            alert('Failed to copy last number: ' + err);
                        });
                } else {
                    alert('Last number is empty');
                }
            });
        });
    </script>
@endpush
@endsection
