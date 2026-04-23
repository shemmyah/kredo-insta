@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @include('users.profile.header')

    {{-- Show all posts here --}}
    {{-- 投稿一覧を表示するエリア --}}
<div class="row">
    @forelse ($user->posts as $post) {{-- ここで $post を定義しています --}}
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
        {{-- 投稿が一つもない場合の表示 --}}
        <div class="col-12 text-center">
            <p class="text-muted">No Posts Yet.</p>
        </div>
    @endforelse
</div>
@endsection
