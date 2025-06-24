<div class="mt-4">
    @if (isset($microposts))
        <ul class="list-none">
            @foreach ($microposts as $micropost)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img src="{{ Gravatar::get($micropost->user->email) }}" alt="" />
                        </div>
                    </div>
                    <div>
                        <div>
                            {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                            <a class="link link-hover text-info" href="{{ route('users.show', $micropost->user->id) }}">{{ $micropost->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $micropost->created_at }}</span>
                        </div>
                        
                        <a href="{{ route('microposts.showReplies', $micropost->id) }}">
                            <div class="micropost-bubble" style="width: 330px;"> {{-- 固定の横幅を指定 --}}
                                {{-- 投稿内容 --}}
                                <div class="mb-0">{!! nl2br(e($micropost->content)) !!}</div>

                                <div class="flex flex-wrap gap-2 mt-2">
                                    {{-- 投稿削除ボタンのフォーム --}}
                                    @if (Auth::id() == $micropost->user_id && Route::currentRouteName() == 'users.show')
                                        <form method="POST" action="{{ route('microposts.destroy', $micropost->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-sm normal-case" 
                                                onclick="return confirm('Delete id = {{ $micropost->id }} ?')">Delete</button>
                                        </form>
                                    @endif
                                    {{-- リプライ数 --}}
                                    <span class="text-muted text-gray-500 impressions-icon flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="sh-5 w-5 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                        </svg>
                                        {{ $micropost->replies()->count() }}
                                    </span>
                                    {{-- リポストボタン --}}
                                    <form method="POST" action="{{ route('microposts.repost', $micropost->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost btn-sm normal-case">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- お気に入りボタン --}}
                                    @if (Auth::user()->is_favoriting($micropost->id))
                                        {{-- お気に入り解除ボタンのフォーム --}}
                                        <form method="POST" action="{{ route('microposts.unfavorite', $micropost->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm normal-case favorited">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="red" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                {{ $micropost->favorites()->count() }}
                                            </button>
                                        </form>
                                    @else
                                        {{-- お気に入り追加ボタンのフォーム --}}
                                        <form method="POST" action="{{ route('microposts.favorite', $micropost->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-sm normal-case">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                {{ $micropost->favorites()->count() }}
                                            </button>
                                        </form>
                                    @endif

                                    {{-- インプレッション数 --}}
                                    <span class="text-muted text-gray-500 impressions-icon flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="sh-5 w-5 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                        </svg>
                                        {{ $micropost->impressions }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $microposts->links() }}
    @endif
</div>