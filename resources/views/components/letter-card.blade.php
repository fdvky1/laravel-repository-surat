@props(['type', 'disposition'])
<div class="card mb-4 p-2">
    <div class="card-header pb-0">
        <div class="d-flex justify-content-between flex-column flex-sm-row">
            <div class="card-title">
                <h5 class="text-nowrap mb-0 fw bold ">
                    @if($type == 'incoming')
                        {{ $letter->letter_number }}
                    @else
                    {{ $letter->status == 'published' ? $letter->letter_number : '-' }}/{{ $letter->classification_code }}/{{ $letter->month }}/{{ $letter->year }}
                    @endif
                </h5>
                <small class="text-black">
                    {{ $type == 'incoming' ? $letter->from : $letter->to }}
                </small>
            </div>
            <div class="card-title d-flex flex-row">
                <div class="d-inline-block mx-2 text-end text-black">
                    <small class="d-block text-secondary">Letter date</small>
                    {{ $letter->formatted_letter_date }}
                </div>
                <div class="dropdown d-inline-block">
                    @if(!Route::is('*.show') || $letter->created_by == Auth::user()->id)
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="dropdown-{{ $type }}-{{ $letter->id }}" aria-haspopup="true" aria-expanded="true">
                        <i class="fas fa-ellipsis-vertical fa-fw fa-lg"></i>
                    </a>
                    @endif

                    <div class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="dropdown-{{ $type }}-{{ $letter->id }}">
                        @if(!Route::is('*.show'))
                            <a class="dropdown-item"
                                href="{{ route(Route::is('dispositions.*') ? 'dispositions.show' : 'letter.show', $letter->id) }}">View Details</a>
                        @endif
                        @if($letter->created_by == Auth::user()->id)
                        <a class="dropdown-item"
                        href="{{ $letter->type === 'incoming' ? route('incoming.update', $letter->id) : route('outgoing.edit', $letter->id) }}">Edit</a>
                            <form action="{{ route('letter.delete', $letter->id) }}" class="d-inline"
                                    method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item cursor-pointer btn-delete">
                                    <span>Delete</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <hr>
        <p>{{ $letter->regarding }}</p>
        <div class="d-flex justify-content-between flex-column flex-sm-row">
            <small class="text-secondary">{{ $letter->note }}</small>
            <div>
                @if($type == 'outgoing')
                    <a href="{{ route('outgoing.print', $letter->id) }}" target="_blank">
                        <i class="bx fa-xl fas fa-file-pdf display-6 cursor-pointer text-primary"></i>
                    </a>
                @endif
                @if(count($letter->attachments))
                    @foreach($letter->attachments as $attachment)
                        <a href="{{ $attachment->path_url }}" target="_blank">
                            @if($attachment->extension == 'pdf')
                                <i class="bx fa-xl fas fa-file-pdf display-6 cursor-pointer text-primary"></i>
                            @elseif(in_array($attachment->extension, ['jpg', 'jpeg']))
                                <i class="bx fa-xl fas fa-file-image display-6 cursor-pointer text-primary"></i>
                            @elseif($attachment->extension == 'png')
                                <i class="bx fa-xl far fa-file-image display-6 cursor-pointer text-primary"></i>
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        {{ $slot }}
    </div>
</div>
