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
    <div class="w-100 d-flex justify-content-end mb-2">
        <a href="{{ route('outgoing.create') }}" class="btn btn-primary">New Letter</a>
    </div>
    @if(count($data) == 0)
    <p class="text-center">there is outgoing letter yet</p>
    @endif
    @foreach($data as $letter)
    <a href="{{ route('letter.show', $letter->id) }}" style="text-decoration: none; color: #000;">
        <x-letter-card
            :letter="$letter"
            :type="'outgoing'"
        >
            <div class="text-right mt-2" style="text-transform: capitalize;">
                <span class="badge badge-{{ $letter->status == 'published' ? 'primary' : ($letter->status == 'rejected' ? 'danger' : 'warning')}} mx-1" style="padding: 0.5rem;">{{ $letter->status == 'pending' ? 'waiting for review' : ($letter->status == 'require_revision' ? 'Revision Required' : $letter->status) }}</span>
            </div>
        </x-letter-card>
    </a>
    @endforeach
</div>
@endsection
