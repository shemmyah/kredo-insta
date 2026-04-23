@extends('layouts.app')

@section('title', 'Followers')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px;">
        @if($followers->isNotEmpty())
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <h2 class="text-muted text-center mb-5">Followers</h2>

                    @foreach ($followers as $follow)
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <a href="{{ route('profile.show', $follow->follower->id) }}">
                                    @if($follow->follower->avatar)
                                        <img src="{{ $follow->follower->avatar }}" alt="{{ $follow->follower->name }}" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                    @endif
                                </a>
                            </div>
                            <div class="col ps-0 text-truncate">
                                <a href="{{ route('profile.show', $follow->follower->id) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ $follow->follower->name }}
                                </a>
                            </div>
                            <div class="col-auto text-end">
                                {{-- Show the button if the user in the list is not AUTH user. --}}
                                @if($follow->follower->id !== Auth::user()->id)
                                    @if($follow->follower->isFollowed())
                                        <form action="{{ route('follow.destroy', $follow->follower->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.store', $follow->follower->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <h3 class="text-muted text-center">No Followers Yet</h3>
        @endif
    </div>
@endsection