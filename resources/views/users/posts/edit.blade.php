@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Category Area --}}
        <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(up to 3)</span>
            </label>

            @foreach ($all_categories as $category)
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="category[]" id="{{ $category->name }}"
                        value="{{ $category->id }}" {{ in_array($category->id, $selected_categories) ? 'checked' : '' }}>
                    <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>
                </div>
            @endforeach

            @error('category')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description Area --}}
        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" cols="4" rows="3" class="form-control"
                placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Images Area --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Images (Up to 3)</label>

            {{-- 1. 現在の画像プレビュー（比率維持しつつコンパクトに） --}}
            @if ($post->images->isNotEmpty())
                <div class="row mb-3 justify-content-center"> {{-- 中央寄せ --}}
                    @foreach ($post->images as $key => $image)
                        <div class="col-3 text-center"> {{-- col-4からcol-3に下げて少し小さく --}}
                            <span class="text-muted xsmall d-block mb-1">Current {{ $key + 1 }}</span>

                            <div class="d-flex align-items-center justify-content-center" style="height: 120px;">
                                {{-- 高さを制限 --}}
                                <img src="{{ asset('images/' . $image->image_path) }}" alt="post image"
                                    class="img-thumbnail"
                                    style="max-width: 100%; max-height: 100%; width: auto; height: auto;">
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr class="text-secondary opacity-25">
            @endif

            {{-- 3つの入力欄 --}}
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

            <div id="image-info" class="form-text">
                The acceptable formats are jpeg, jpg, png, and gif only. <br>
                Max file size per image: 1048kb.
            </div>

            @error('image.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- ボタン --}}
        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-warning">Save</button>
        </div>
    </form>
@endsection
