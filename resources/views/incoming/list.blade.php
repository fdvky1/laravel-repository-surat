@extends('layouts.admin')

@section('main-content')
<div class="mx-auto" style="max-width: 90%">
    @foreach($data as $letter)
        <x-letter-card
            :letter="$letter"
            :type="'incoming'"
        />
    @endforeach
</div>
@endsection
