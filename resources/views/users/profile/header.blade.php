<div class="row">
    <div class="col-4 mb-5">
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                class="img-thumbnail rounded-circle d-block mx-auto avatar-lg" style="height: 10rem;">
        @else
            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
        @endif
    </div>
    <div class="col-8">
        <div class="row mb-3">
            <div class="col-auto">
                <h2 class="display-6 mb-0">{{ $user->name }}</h2>
            </div>
            <div class="col-auto p-2">
                @if (Auth::user()->id === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-bold">Edit
                        Profile</a>
                @else
                    <div class="d-flex align-items-center">
                        @if ($user->isFollowed())
                            <form action="{{ route('follow.destroy', $user->id) }}" method="post" class="me-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Unfollow</button>
                            </form>
                        @else
                            <form action="{{ route('follow.store', $user->id) }}" method="post" class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                            </form>
                        @endif
                        {{-- Message Button --}}
                        <a href="{{ route('chat', $user->id) }}" class="btn btn-outline-dark btn-sm fw-bold px-3 position-relative">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span class="ms-1 d-none d-md-inline">Message</span>

                            {{-- Show notification dot if there are unread messages --}}
                            @if(isset($unread_count) && $unread_count > 0)
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            @endif
                        </a>

                    </div>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-auto">
                <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                    <strong>{{ $user->posts->count() }}</strong> 
                    {{ $user->posts->count() == 1 ? 'post' : 'posts' }}
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.followers', $user->id) }}" class="text-decoration-none text-dark">
                    <strong>{{ $user->followers->count() }}</strong> 
                    {{ $user->followers->count() == 1 ? 'follower' : 'followers' }}
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.following', $user->id) }}" class="text-decoration-none text-dark">
                    <strong>{{ $user->following->count() }}</strong> following
                </a>
            </div>
        </div>
        <p class="fw-bold">{{ $user->introduction }}</p>
    </div>
</div>
