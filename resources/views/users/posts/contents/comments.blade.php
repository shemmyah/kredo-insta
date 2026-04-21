<div class="mt-3">
    {{-- Show all comments here --}}
    @if ($post->comments->isNotEmpty())
        <hr>
        <ul class="list-group">
            @foreach ($post->comments->take(3) as $comment)
                <li class="list-group-item border-0 p-0 mb-2">
                    <a href="{{ route('profile.show', $comment->user->id) }}"
                        class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $comment->body }}</p>

                    <span
                        class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>



                    {{-- if the AUTH user is the OWNER, show delete btn --}}
                    @if (Auth::user()->id === $comment->user->id)
                        {{-- **Aimi rewrote** --}}
                        {{-- 1. Edit button --}}
                        &middot;
                        <button type="button" class="border-0 bg-transparent text-primary p-0 xsmall"
                            data-bs-toggle="modal" data-bs-target="#edit-comment-{{ $comment->id }}">
                            Edit
                        </button>

                        <form action="{{ route('comment.destroy', $comment->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            &middot;
                            <button type="submit"
                                class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                        </form>

                        {{-- 2. Modal for editing comment --}}
                        <div class="modal fade" id="edit-comment-{{ $comment->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('comment.update', $comment->id) }}" method="post">
                                        @csrf
                                        @method('PATCH')

                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Edit Comment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <textarea name="comment_body" rows="3" class="form-control" required>{{ $comment->body }}</textarea>
                                        </div>

                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach

            @if ($post->comments->count() > 3)
                <li class="list-group-item border-0 px-0 pt-0">
                    <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none small">
                        View all {{ $post->comments->count() }} comments
                    </a>
                </li>
            @endif
        </ul>
    @endif

    <form action="{{ route('comment.store', $post->id) }}" method="post">
        @csrf

        <div class="input-group">
            <textarea name="comment_body{{ $post->id }}" cols="30" rows="1" class="form-control form-control-sm"
                placeholder="Add a comment">{{ old('comment_body' . $post->id) }}</textarea>
            <button type="submit" class="btn btn-outline-secondary btn-sm" title="Post">
                <i class="fa-regular fa-paper-plane"></i>
            </button>
        </div>
        {{-- Error --}}
        @error('comment_body' . $post->id)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </form>
</div>
