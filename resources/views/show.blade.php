@extends('layouts.admin')

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

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $data->formatted_created_at }}</dd>

                <dt class="col-sm-3">Updated At</dt>
                <dd class="col-sm-9">{{ $data->formatted_updated_at }}</dd>
            </dl>
        </div>
    </x-letter-card>

@endsection
