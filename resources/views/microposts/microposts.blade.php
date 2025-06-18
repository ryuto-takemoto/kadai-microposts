<div class="mt-4">
    @if (isset($microposts))
        <ul class="list-none">
            @foreach ($microposts as $micropost)
                <li class="flex items-start gap-x-2 mb-4 border-b border-gray-200 py-2"> {{-- 投稿ごとに仕切りを追加 --}}
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
                        <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                        </div>
                        <div>
                            @if (Auth::id() == $micropost->user_id)
                                {{-- 投稿削除ボタンのフォーム --}}
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
                                    <button type="submit" class="btn btn-success btn-sm normal-case">Unfavorite</button>
                                </form>
                            @else
                                {{-- お気に入り追加ボタンのフォーム --}}
                                <form method="POST" action="{{ route('microposts.favorite', $micropost->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm normal-case">Favorite</button>
                                </form>
                            @endif

                            {{-- インプレッション数 --}}
                            <span class="text-muted text-gray-500">Impressions: {{ $micropost->impressions }}</span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $microposts->links() }}
    @endif
</div>