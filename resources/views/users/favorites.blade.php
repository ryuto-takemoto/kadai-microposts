@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if (Auth::check())
            <div class="grid grid-cols-3 gap-4">
                {{-- 左部レーン --}}
                <div class="col-span-1 flex flex-col h-screen items-start">
                    {{-- ロゴ --}}
                    <div class="flex justify-start items-start">
                        <h1><a class="btn btn-ghost normal-case text-xl" href="/">Microposts</a></h1>
                    </div>

                    {{-- メニュー --}}
                    <div class="flex flex-col items-start justify-center flex-grow">
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
                    <div class="flex flex-col items-start justify-end">
                        <a href="{{ route('users.show', Auth::id()) }}">
                            <div class="avatar mb-2">
                                <div class="w-12 rounded">
                                    <img src="{{ Gravatar::get(Auth::user()->email) }}" alt="avatar" />
                                </div>
                            </div>
                        </a>
                        <p class="text-left">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                {{-- 中央レーン --}}
                <div class="col-span-1 flex flex-col">
                    {{-- お気に入り一覧 --}}
                    <div class="overflow-y-auto h-timeline"> {{-- 高さを固定し、スクロール可能にする --}}
                        @include('microposts.microposts', ['microposts' => $favorites])
                    </div>

                </div>

                {{-- 右部レーン --}}
                <div class="col-span-1 flex flex-col items-start">
                    {{-- ユーザー情報 --}}
                    @include('users.card', ['user' => $user])

                    {{-- タブ --}}
                    <div class="flex flex-col w-full">
                        @include('users.navtabs', ['user' => $user])
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
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection