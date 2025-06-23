@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if (Auth::check())
            <div class="grid grid-cols-3 gap-4">
                {{-- 左部レーン --}}
                <div class="col-span-1 flex flex-col h-screen items-start"> {{-- items-start を追加 --}}
                    {{-- ロゴ --}}
                    <div class="flex justify-start items-start"> {{-- justify-start を追加 --}}
                        <h1><a class="btn btn-ghost normal-case text-xl" href="/">Microposts</a></h1>
                    </div>

                    {{-- メニュー --}}
                    <div class="flex flex-col items-start justify-center flex-grow"> {{-- items-start を追加 --}}
                        <a href="{{ route('dashboard') }}" class="btn btn-ghost normal-case">トップページ</a>
                        <a href="{{ route('users.show', Auth::id()) }}" class="btn btn-ghost normal-case">マイページ</a>
                        <a href="{{ route('search.index') }}" class="btn btn-ghost normal-case">検索ページ</a>
                        <a href="{{ route('users.index') }}" class="btn btn-ghost normal-case">ユーザー一覧</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost normal-case">ログアウト</button>
                        </form>
                    </div>

                    {{-- ユーザー情報 --}}
                    <div class="flex flex-col items-start justify-end"> {{-- items-start を追加 --}}
                        <a href="{{ route('users.show', Auth::id()) }}">
                            <div class="avatar mb-2">
                                <div class="w-12 rounded">
                                    <img src="{{ Gravatar::get(Auth::user()->email) }}" alt="avatar" />
                                </div>
                            </div>
                        </a>
                        <p class="text-left">{{ Auth::user()->name }}</p> {{-- text-left を追加 --}}
                    </div>
                </div>

                {{-- 中央レーン --}}
                <div class="col-span-1 flex flex-col">
                    {{-- タイムライン --}}
                    <div class="overflow-y-auto h-timeline"> {{-- 高さを固定し、スクロール可能にする --}}
                        @include('microposts.microposts', ['microposts' => $microposts])
                    </div>

                    {{-- 投稿フォーム --}}
                    <form method="POST" action="{{ route('microposts.store') }}" class="mt-4">
                        @csrf
                        <div class="flex">
                            <textarea rows="2" name="content" class="input input-bordered w-full" placeholder="いまどうしてる？"></textarea>
                            <button type="submit" class="btn btn-primary ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- 右部レーン --}}
                <div class="col-span-1 flex flex-col items-end">
                    {{-- トレンドキーワードランキング --}}
                    <div class="mb-4">
                        <h3>トレンドキーワードランキング</h3>
                        <ol class="list-decimal list-inside"> {{-- list-decimal list-inside を追加 --}}
                            @if (isset($trendKeywords) && count($trendKeywords) > 0)
                                @foreach ($trendKeywords as $keyword)
                                    <li class="mb-2 border-b border-gray-200 pb-2"> {{-- 順位ごとに区切り線を追加 --}}
                                        <div class="font-bold uppercase"> {{-- タイトルを太字、大文字にする --}}
                                            {{ $loop->iteration }}位 ({{ number_format($keyword->count / $recentSearchLogsCount * 100, 1) }}%)
                                        </div>
                                        <div class="text-sm"> {{-- 検索されたワードを本文として表示 --}}
                                            {!! nl2br(e($keyword->keyword)) !!}
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li>まだ検索されていません</li>
                            @endif
                        </ol>
                    </div>

                    {{-- 検索フォーム --}}
                    <form action="{{ route('search.index') }}" method="GET" class="flex">
                        <input type="text" name="keyword" placeholder="キーワード検索" class="input input-bordered w-full" />
                        <button type="submit" class="btn btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </button>
                    </form>
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