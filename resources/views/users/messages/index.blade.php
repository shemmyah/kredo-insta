@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h4 class="mb-4 fw-bold">Messages</h4>
            <div class="list-group shadow-sm">
                @forelse($rooms as $room)
                    @php
                        $another_user = ($room->user_one_id == auth()->id()) ? $room->userTwo : $room->userOne;
                        $latest_message = $room->messages->first();

                        $unread_in_room = $room->messages->where('is_read', false)->where('sender_id', '!=', auth()->id())->count();
                    @endphp

                    <a href="{{ url('/chat/' . $another_user->id) }}" class="list-group-item list-group-item-action py-3 border-0 border-bottom" style="{{ $unread_in_room > 0 ? 'background-color: #e2e8f0' : ''}}">
                        <div class="d-flex align-items-center">
                            @if($another_user->avatar)
                                <img src="{{ $another_user->avatar }}" class="rounded-circle avatar-md me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary fs-1 me-3"></i>
                            @endif
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark">{{ $another_user->name }}</h6>
                                    <small class="text-muted">{{ $latest_message ? $latest_message->created_at->diffForHumans() : '' }}</small>
                                </div>
                                <p class="mb-0 text-muted small text-truncate" style="max-width: 300px;">
                                    {{ $latest_message ? $latest_message->body : 'No messages yet' }}
                                </p>
                            </div>

                            {{-- 未読がある場合のバッジ --}}
                            @php
                                $unread_in_room = $room->messages->where('is_read', false)->where('sender_id', '!=', auth()->id())->count();
                            @endphp
                            @if($unread_in_room > 0)
                                <span class="badge bg-success rounded-pill">{{ $unread_in_room }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="list-group-item text-center py-5">
                        <i class="fa-regular fa-comment-dots fs-1 text-muted mb-3"></i>
                        <p class="text-muted mb-0">No messages yet.</p>
                        <small class="text-secondary">Start a conversation from a user's profile!</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection