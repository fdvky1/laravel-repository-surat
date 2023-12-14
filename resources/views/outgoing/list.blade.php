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

<div class="mx-auto" style="max-width: 90%">
    <div class="w-100 d-flex justify-content-end mb-2">
        <a href="{{ route('outgoing.create') }}" class="btn btn-primary">New Letter</a>
    </div>
    @foreach($data as $letter)
    <a href="{{ route('letter.show', $letter->id) }}" style="text-decoration: none; color: #000;">
        <x-letter-card
            :letter="$letter"
            :type="'outgoing'"
        />
    </a>
    @endforeach
</div>
@endsection
