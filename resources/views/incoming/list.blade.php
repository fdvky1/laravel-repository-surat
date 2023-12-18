@extends('layouts.admin')

@section('main-content')
<div class="mx-auto" style="max-width: 90%">
    <div class="w-100 d-flex justify-content-end mb-2">
        <a href="{{ route('incoming.create') }}" class="btn btn-primary">New Letter</a>
    </div>
    @foreach($data as $letter)
        <a href="{{ route('letter.show', $letter->id) }}" style="text-decoration: none; color: #000;">
            <x-letter-card
                :letter="$letter"
                :type="'incoming'"
            />
            <a class="dropdown-item"
                href="{{ route('incoming.edit', $letter->id)}}">
                Edit
            </a>
        </a>
    @endforeach
</div>
@endsection
