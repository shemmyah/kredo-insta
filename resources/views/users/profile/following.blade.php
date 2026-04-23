@extends('layouts.app')
 
@section('title', 'Profile')
 
@section('content')
    @include('users.profile.header')

    {{-- Show all posts here --}}
    <div style="margin-top:100px;">
        @if($following->isNotEmpty())
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <h2 class="text-muted text-center mb-5">Following</h2>

                    @foreach ($following as $follow)
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <a href="{{ route('profile.show', $follow->following->id) }}">
                                    @if ($follow->following->avatar)
                                        <img src="{{ $follow->following->avatar}}" alt="{{ $follow->following->name}}" class="rounded-circle avatar-sm">    
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                    @endif
                                </a>
                            </div>
                            <div class="col ps-3 text-truncate">
                                <a href="{{ route('profile.show', $follow->following->id)}}" class="text-decoration-none text-dark fw-bold">
                                    {{ $follow->following->name}}
                                </a>
                            </div>
                            <div class="col-auto text-end">
                                @if ($follow->following->id !== Auth::user()->id)
                                    @if($follow->following->isFollowed())
                                    <form action="{{ route('follow.destroy', $follow->following->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                                    </form>
                                    @else
                                    <form action="{{ route('follow.store', $follow->following->id)}}" method="post">
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
            <h3 class="text-muted text-center">No Following Yet</h3>
        @endif
    </div>
@endsection
 