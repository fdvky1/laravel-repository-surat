@extends('layouts.admin')

@section('main-content')

    <x-letter-card :letter="$data" :type="$type">
        <div class="mt-2">
            <hr/>
            <dl class="row mt-3">

                <dt class="col-sm-3">Letter date</dt>
                <dd class="col-sm-9">{{ $data->formatted_letter_date }}</dd>

                <dt class="col-sm-3">Received date</dt>
                <dd class="col-sm-9">{{ $data->formatted_received_date }}</dd>

                <dt class="col-sm-3">Letter number</dt>
                <dd class="col-sm-9">{{ $data->letter_number }}</dd>

                <dt class="col-sm-3">Code</dt>
                <dd class="col-sm-9">{{ $data->classification_code }}</dd>

                <dt class="col-sm-3">Classification</dt>
                <dd class="col-sm-9">{{ $data->classification->description }}</dd>

                <dt class="col-sm-3">Sender</dt>
                <dd class="col-sm-9">{{ $data->sender?->name }}</dd>

                <dt class="col-sm-3">Recipient</dt>
                <dd class="col-sm-9">{{ $data->recipient?->name }}</dd>

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $data->formatted_created_at }}</dd>

                <dt class="col-sm-3">Updated At</dt>
                <dd class="col-sm-9">{{ $data->formatted_updated_at }}</dd>
            </dl>
        </div>
    </x-letter-card>

@endsection
