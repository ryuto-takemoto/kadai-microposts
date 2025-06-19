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
                        
                        <div class="micropost-bubble">
                            <a href="{{ route('microposts.showReplies', $micropost->id) }}">
                                {{-- 投稿内容 --}}
                                <div class="mb-0">{!! nl2br(e($micropost->content)) !!}</div>
                            </a>

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
                                        </button>
                                    </form>
                                @endif

                                {{-- リポストボタン --}}
                                <form method="POST" action="{{ route('microposts.repost', $micropost->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm normal-case">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- インプレッション数 --}}
                                <span class="text-muted text-gray-500 impressions-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 3a1 1 0 000 1.5V5h14v-.5a1 1 0 000-1.5H3zM5.392 8.212a1 1 0 00-1.184 1.184l.26 1.56A1 1 0 005 12.44h1.5a1 1 0 00.94-.592l.26-1.56a1 1 0 00-1.184-1.184l-.26 1.56A1 1 0 017 11.56H5.5a1 1 0 01-.94.592l-.26 1.56a1 1 0 001.184 1.184l.26-1.56A1 1 0 015 12.44h1.5a1 1 0 00.94-.592l.26-1.56a1 1 0 00-1.184-1.184l-.26 1.56A1 1 0 017 11.56H5.5a1 1 0 01-.94.592l-.26 1.56a1 1 0 001.184 1.184l.26-1.56A1 1 0 015 12.44h1.5a1 1 0 00.94-.592l.26-1.56a1 1 0 00-1.184-1.184l-.26 1.56A1 1 0 017 11.56H5.5a1 1 0 01-.94.592l-.26 1.56a1 1 0 001.184 1.184l.26-1.56A1 1 0 015 12.44h1.5a1 1 0 00.94-.592l.26-1.56a1 1 0 00-1.184-1.184zM16.608 8.212a1 1 0 011.184 1.184l-.26 1.56a1 1 0 01-.94.592H14.5a1 1 0 01-.94-.592l-.26-1.56a1 1 0 011.184-1.184l.26 1.56a1 1 0 00.94-.592H16.5a1 1 0 01.94.592l.26 1.56a1 1 0 01-1.184 1.184l-.26-1.56a1 1 0 00-.94.592H14.5a1 1 0 01-.94-.592l-.26 1.56a1 1 0 011.184 1.184l.26-1.56a1 1 0 00.94-.592H16.5a1 1 0 01.94.592l.26 1.56a1 1 0 01-1.184 1.184l-.26-1.56a1 1 0 00-.94.592H14.5a1 1 0 01-.94-.592l-.26-1.56a1 1 0 011.184-1.184z" />
                                    </svg>
                                    {{ $micropost->impressions }}
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $microposts->links() }}
    @endif
</div>