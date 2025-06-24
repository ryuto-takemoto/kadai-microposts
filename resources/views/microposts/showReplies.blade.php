@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if (Auth::check())
            <div class="grid grid-cols-3 gap-4">
                {{-- 左部レーン --}}
                @include('layouts.sidebar') {{-- サイドバーの共通化 --}}
                
                {{-- 中央レーン --}}
                <div class="col-span-1 flex flex-col">
                    {{-- リプライ一覧 --}}
                    <div class="overflow-y-auto h-timeline"> {{-- 高さを固定し、スクロール可能にする --}}
                        @if (isset($microposts) && count($microposts) > 0)
                            <ul class="list-none">
                                @foreach ($microposts as $reply)
                                    <li class="flex items-start gap-x-2 mb-4 border-b border-gray-200 py-2">
                                        {{-- リポスト対応の表示データ設定 --}}
                                        @php
                                            $displayUser = $reply->repost_from ? $reply->originalPost->user : $reply->user;
                                            $displayContent = $reply->repost_from ? $reply->originalPost->content : $reply->content;
                                            $displayCreatedAt = $reply->repost_from ? $reply->originalPost->created_at : $reply->created_at;
                                            $displayPostId = $reply->repost_from ? $reply->originalPost->id : $reply->id;
                                            $displayPost = $reply->repost_from ? $reply->originalPost : $reply;
                                        @endphp

                                        {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                                        <div class="avatar">
                                            <div class="w-12 rounded">
                                                <img src="{{ Gravatar::get($displayUser->email) }}" alt="" />
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            {{-- リポスト情報表示 --}}
                                            @if ($reply->repost_from)
                                                <div class="text-gray-500 text-sm mb-1 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                    <a class="link link-hover text-info" href="{{ route('users.show', $reply->user->id) }}">{{ $reply->user->name }}</a>
                                                    がリポストしました
                                                    <span class="ml-1">{{ $reply->created_at }}</span>
                                                </div>
                                            @endif

                                            <div>
                                                {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                                                <a class="link link-hover text-info" href="{{ route('users.show', $displayUser->id) }}">{{ $displayUser->name }}</a>
                                                <span class="text-muted text-gray-500">posted at {{ $displayCreatedAt }}</span>
                                            </div>
                                            
                                            {{-- クリック可能な投稿エリア --}}
                                            <a href="{{ route('microposts.showReplies', $displayPostId) }}" class="block hover:bg-gray-50 rounded p-2 -m-2 transition-colors">
                                                <div class="micropost-content">
                                                    {{-- 投稿内容 --}}
                                                    <div class="mb-2">{!! nl2br(e($displayContent)) !!}</div>

                                                    {{-- アクションボタン群 --}}
                                                    <div class="flex flex-wrap gap-2 mt-2" onclick="event.preventDefault(); event.stopPropagation();">
                                                        {{-- リプライ数 --}}
                                                        <span class="text-muted text-gray-500 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-1">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                                            </svg>
                                                            {{ $displayPost->replies()->count() }}
                                                        </span>

                                                        {{-- リポストボタン --}}
                                                        <form method="POST" action="{{ route('microposts.repost', $displayPostId) }}" class="inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-ghost btn-sm normal-case flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                                </svg>
                                                                <span class="text-sm">{{ $displayPost->repost_count }}</span>
                                                            </button>
                                                        </form>

                                                        {{-- お気に入りボタン --}}
                                                        @if (Auth::user()->is_favoriting($displayPostId))
                                                            <form method="POST" action="{{ route('microposts.unfavorite', $displayPostId) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-ghost btn-sm normal-case favorited flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="red" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                                    </svg>
                                                                    <span class="text-sm">{{ $displayPost->favorites()->count() }}</span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('microposts.favorite', $displayPostId) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-ghost btn-sm normal-case flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                                    </svg>
                                                                    <span class="text-sm">{{ $displayPost->favorites()->count() }}</span>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        {{-- インプレッション数 --}}
                                                        <span class="text-muted text-gray-500 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-1">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                                            </svg>
                                                            {{ $displayPost->impressions }}
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
                        @else
                            <p>リプライはまだありません。</p>
                        @endif
                    </div>

                    {{-- リプライ投稿フォーム --}}
                    <form method="POST" action="{{ route('microposts.reply', $micropost->id) }}" class="mt-4">
                        @csrf
                        <div class="flex">
                            <textarea rows="2" name="content" class="input input-bordered w-full" placeholder="リプライ内容"></textarea>
                            <button type="submit" class="btn btn-primary ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- 右部レーン --}}
                <div class="col-span-1 flex flex-col">
                    {{-- リプライ元の投稿表示（クリック可能） --}}
                    <div class="card bg-base-100 shadow-xl mb-4">
                        <div class="card-body">
                            <h3 class="card-title text-lg">元の投稿</h3>
                            {{-- クリック可能な元投稿エリア --}}
                            @if ($micropost->reply_to)
                                {{-- リプライの場合は、さらに上の階層へのリンク --}}
                                <a href="{{ route('microposts.showReplies', $micropost->reply_to) }}" class="block hover:bg-gray-50 rounded p-2 -m-2 transition-colors">
                            @else
                                {{-- 元投稿の場合はタイムラインへのリンク --}}
                                <a href="{{ route('dashboard') }}" class="block hover:bg-gray-50 rounded p-2 -m-2 transition-colors">
                            @endif
                                <div class="flex items-start gap-x-2">
                                    {{-- 元投稿者のアバター --}}
                                    <div class="avatar">
                                        <div class="w-12 rounded">
                                            <img src="{{ Gravatar::get($micropost->user->email) }}" alt="" />
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="mb-2">
                                            <span class="link link-hover text-info font-semibold">{{ $micropost->user->name }}</span>
                                            <span class="text-muted text-gray-500 text-sm ml-2">{{ $micropost->created_at }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <p class="text-base">{!! nl2br(e($micropost->content)) !!}</p>
                                        </div>
                                        <div class="flex gap-4 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 mr-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                                </svg>
                                                {{ $micropost->replies()->count() }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 mr-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                                </svg>
                                                {{ $micropost->impressions }}
                                            </span>
                                        </div>
                                        {{-- 階層表示 --}}
                                        <div class="mt-2 text-xs text-blue-600">
                                            @if ($micropost->reply_to)
                                                クリックして上の階層へ →
                                            @else
                                                クリックしてタイムラインへ →
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- 前のページに戻るボタン --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg">ナビゲーション</h3>
                            <div class="card-actions space-y-2">
                                <button onclick="history.back()" class="btn btn-outline btn-primary w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                    </svg>
                                    前のページに戻る
                                </button>
                                
                                {{-- タイムラインに戻るボタン --}}
                                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-secondary w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                    タイムラインに戻る
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="prose hero bg-base-200 mx-auto max-w-full rounded">
                <div class="hero-content text-center my-10">
                    <div class="max-w-md mb-10">
                        <h2>Welcome to the Microposts</h2>
                        {{-- ユーザー登録ページへのリンク --}}
                        <a class="btn btn-primary btn-lg normal-case" href="{{ route('register') }}">Sign up now!</a>
                        <a class="btn btn-primary btn-lg normal-case" href="{{ route('login') }}">Log in now!</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection