@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="container">
    {{-- フォームの横幅を col-md-8 くらいに絞ることで、中央に配置し、幅を安定させます --}}
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="h3 mb-4">Create Post</h1>

            <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- Category Area --}}
                <div class="mb-4">
                    <label class="form-label d-block fw-bold">
                        Category <span class="text-muted fw-normal">(up to 3)</span>
                    </label>

                    @foreach ($all_categories as $category)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="category[]" id="category-{{ $category->id }}"
                                value="{{ $category->id }}">
                            <label for="category-{{ $category->id }}" class="form-check-label">{{ $category->name }}</label>
                        </div>
                    @endforeach
                    @error('category')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description Area --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Description</label>
                    {{-- class="form-control" が幅100%を維持してくれます --}}
                    <textarea name="description" id="description" rows="4" class="form-control"
                        placeholder="What's on your mind?">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Images Area --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Images <span class="text-muted fw-normal">(Up to 3)</span></label>

                    @for ($i = 1; $i <= 3; $i++)
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Image {{ $i }}</span>
                            <input type="file" name="image[]" class="form-control">
                        </div>
                    @endfor

                    <div class="form-text mt-1">
                        Max file size per image: 1048kb.
                    </div>
                    @error('image.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Button Area --}}
                {{-- d-grid と w-100 を使うことで、上のフォームと横幅がぴったり揃います --}}
                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection