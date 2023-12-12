@extends('layouts.admin')

@section('main-content')
<div class="mx-auto" style="max-width: 90%">
    <div class="w-100 d-flex justify-content-end mb-2">
        <a href="{{ route('incoming.create') }}" class="btn btn-primary">New Letter</a>
    </div>
    @foreach($data as $letter)
        <x-letter-card
            :letter="$letter"
            :type="'incoming'"
        />
    @endforeach
</div>
@endsection
