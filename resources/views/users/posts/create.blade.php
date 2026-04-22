{{-- 写真投稿画面 --}}
{{-- fileのとこ編集 --}}

@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(up to 3)</span>
            </label>

            @foreach ($all_categories as $category)
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="category[]" id="{{ $category->name }}"
                        value="{{ $category->id }}">
                    <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>
                </div>
            @endforeach
            {{-- Error --}}
            @error('category')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" cols="4" rows="3" class="form-control"
                placeholder="What's on your mind?">{{ old('description') }}</textarea>
        </div>
        @error('description')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

        <div class="mb-4">
            <label class="form-label fw-bold">Images (Up to 3)</label>

            <div class="mb-2">
                <span class="text-muted small">Image 1</span>
                <input type="file" name="image[]" class="form-control">
            </div>

            <div class="mb-2">
                <span class="text-muted small">Image 2</span>
                <input type="file" name="image[]" class="form-control">
            </div>

            <div class="mb-2">
                <span class="text-muted small">Image 3</span>
                <input type="file" name="image[]" class="form-control">
            </div>

            <div class="form-text">
                Max file size per image: 1048kb.
            </div>
        </div>
        {{-- 配列のエラーを表示するために変更 --}}
        @error('image.*')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        </div>

        <button type="submit" class="btn btn-primary px-5">Post</button>
    </form>
@endsection
