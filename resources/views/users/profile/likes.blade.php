@extends('layouts.app')

@section('title', 'Liked Posts')

@section('content')
    @include('users.profile.header')

    {{-- Show all liked posts here --}}
    <div class="row">
        @forelse ($liked_posts as $post)
            <div class="col-4 mb-4">
                <a href="{{ route('post.show', $post->id) }}">
                    @if ($post->images->isNotEmpty())
                        <img src="{{ asset('images/' . $post->images->first()->image_path) }}" 
                             alt="post id {{ $post->id }}" 
                             class="grid-img w-100">
                    @else
                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fa-solid fa-image text-white"></i>
                        </div>
                    @endif
                </a>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">No Liked Posts Yet.</p>
            </div>
        @endforelse
    </div>
@endsection