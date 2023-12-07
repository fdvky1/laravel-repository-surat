@props(['type'])
<div class="card mb-4 p-2">
    <div class="card-header pb-0">
        <div class="d-flex justify-content-between flex-column flex-sm-row">
            <div class="card-title">
                <h5 class="text-nowrap mb-0 fw-bold">{{ $letter->letter_number }} | {{ $letter->classification?->code }}</h5>
                <small class="text-black">
                    {{ $type == 'incoming' ? $letter->sender?->name : $letter->recipient?->name }}
                </small>
            </div>
            <div class="card-title d-flex flex-row">
                <div class="d-inline-block mx-2 text-end text-black">
                    <small class="d-block text-secondary">Letter date</small>
                    {{ $letter->formatted_letter_date }}
                </div>
                <div class="dropdown d-inline-block">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="dropdown-{{ $type }}-{{ $letter->id }}" aria-haspopup="true" aria-expanded="true">
                        <i class="fas fa-ellipsis-vertical fa-fw fa-lg"></i>
                    </a>
                    
                    @if($type == 'incoming')
                        <div class="dropdown-menu dropdown-menu-end"
                             aria-labelledby="dropdown-{{ $type }}-{{ $letter->id }}">
                            @if(!\Illuminate\Support\Facades\Route::is('*.show'))
                                <a class="dropdown-item"
                                   href="{{ route('letter.show', $letter->id) }}">View Details</a>
                            @endif
                            <form action="{{ route('letter.delete', $letter->id) }}" class="d-inline"
                                  method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item cursor-pointer ">
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="dropdown-menu dropdown-menu-end"
                             aria-labelledby="dropdown-{{ $type }}-{{ $letter->id }}">
                            @if(!\Illuminate\Support\Facades\Route::is('*.show'))
                                <a class="dropdown-item"
                                   href="{{ route('letter.show', $letter->id) }}">View Details</a>
                            @endif
                            <form action="{{ route('letter.delete', $letter->id) }}" class="d-inline"
                                  method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item cursor-pointer btn-delete">
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <hr>
        <p>{{ $letter->summary }}</p>
        <div class="d-flex justify-content-between flex-column flex-sm-row">
            <small class="text-secondary">{{ $letter->note }}</small>
            @if(count($letter->attachments))
                <div>
                    @foreach($letter->attachments as $attachment)
                        <a href="{{ $attachment->path_url }}" target="_blank">
                            @if($attachment->extension == 'pdf')
                                <i class="bx fas fa-file-pdf display-6 cursor-pointer text-primary"></i>
                            @elseif(in_array($attachment->extension, ['jpg', 'jpeg']))
                                <i class="bx fas fa-file-image display-6 cursor-pointer text-primary"></i>
                            @elseif($attachment->extension == 'png')
                                <i class="bx far fa-file-image display-6 cursor-pointer text-primary"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        {{ $slot }}
    </div>
</div>
