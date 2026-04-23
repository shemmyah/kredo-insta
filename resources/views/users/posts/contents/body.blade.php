{{-- clickable image --}}
<div class="container p-0">
    <a href="{{ route('post.show', $post->id) }}">
        {{-- もし画像（images）が空っぽでなければ表示する --}}
         @if ($post->images->isNotEmpty())
                <div id="carouselExample-{{ $post->id }}" class="carousel slide w-100" data-bs-ride="false">
                    <div class="carousel-inner">
                        @foreach ($post->images as $key => $image)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                {{-- storage/images/ファイル名 を参照 --}}
                                <img src="{{ asset('images/' . $image->image_path) }}" class="d-block w-100" alt="post image">
                            </div>
                        @endforeach
                    </div>
                    @if ($post->images->count() > 1)
                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#carouselExample-{{ $post->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button"
                            data-bs-target="#carouselExample-{{ $post->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    @endif
                </div>
            {{-- @else --}}
                {{-- 従来の1枚表示 --}}
                {{-- <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100"> --}}
            @endif
    </a>
</div>
<div class="card-body">
    {{-- heart button + no.of likes + categories --}}
    <div class="row align-items-center">
        <div class="col-auto">
            @if ($post->isLiked())
                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('like.store', $post->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </form>
            @endif
        </div>
        <div class="col-auto px-0">
            <span>{{ $post->likes->count() }}</span>
        </div>
        <div class="col text-end">
            @foreach ($post->categoryPost as $category_post)
                <div class="badge bg-secondary bg-opacity-50">
                    {{ $category_post->category->name }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- owner + description --}}
    <a href="{{ route('profile.show', $post->user->id) }}"
        class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
    &nbsp;
    <p class="d-inline fw-light">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M D, Y', strtotime($post->created_at)) }}</p>
    

    {{-- include comments here --}}
    @include('users.posts.contents.comments')
</div>
