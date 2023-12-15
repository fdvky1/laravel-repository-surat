@extends('layouts.admin')

@section('main-content')
<div class="px-4">
    <div class="w-100 d-flex justify-content-end mb-2">
        <a href="{{ route('incoming.create') }}" class="btn btn-primary">New Letter</a>
    </div>
    @foreach($data as $letter)
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
    @endforeach
</div>
@endsection
