@extends('layouts.admin')

@section('main-content')
<div class="px-4">
    @foreach($dispositions as $disposition)
        <a href="{{ route('dispositions.show', $disposition->letter_id) }}" style="text-decoration: none; color: #000;">
            <x-letter-card
                :letter="$disposition->letter"
                :type="$disposition->letter->type"
                :disposition="true"
            >
                <div class="text-right mt-2">
                    <span class="badge badge-{{ $disposition->status == 'accept' ? 'primary' : ($disposition->status == 'reject' ? 'danger' : 'warning')}} mx-1" style="padding: 0.5rem;">{{$disposition->status == 'reject' ? 'Rejected' : ($disposition->status == 'pending' ? 'Waiting Your Feedback' : 'Reviewed')}}</span>
                </div>
            </x-letter-card>
        </a>
    @endforeach
</div>
@endsection
