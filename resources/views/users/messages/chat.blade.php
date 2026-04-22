@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                {{-- Display the recipient's name --}}
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fa-regular fa-comments me-2 text-primary"></i>
                        {{ $to_user->name }}
                    </h5>
                </div>

                {{-- Message area --}}
                <div class="card-body overflow-auto" style="height: 500px; background-color: #f0f2f5;">
                    @foreach($messages as $message)
                        {{-- Align right for current user's messages, left for others --}}
                        <div class="d-flex mb-3 {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="p-3 shadow-sm {{ $message->sender_id == auth()->id() ? 'bg-primary text-white rounded-start-pill rounded-top-pill' : 'bg-white text-dark rounded-end-pill rounded-top-pill' }}" style="max-width: 75%;">
                                <p class="mb-1">{{ $message->body }}</p>
                                <small class="d-block text-end {{ $message->sender_id == auth()->id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7rem;">
                                    {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Message input form --}}
                <div class="card-footer bg-white p-3 border-top">
                    <form action="{{ route('message.store', $room->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="body" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Type a message..." required autocomplete="off" autofocus>
                            <button type="submit" class="btn btn-primary rounded-circle ms-2" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection