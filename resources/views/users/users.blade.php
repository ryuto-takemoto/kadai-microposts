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
                    <h1 class="text-2xl font-bold mb-4">ユーザー一覧</h1>

                    <div class="overflow-y-auto h-timeline">
                        @if (isset($users))
                            <ul class="list-none">
                                @foreach ($users as $user)
                                    <li class="flex items-center gap-x-2 mb-4 border-b border-gray-200 pb-2">
                                        {{-- ユーザーのメールアドレスをもとにGravatarを取得して表示 --}}
                                        <div class="avatar">
                                            <div class="w-12 rounded">
                                                <img src="{{ Gravatar::get($user->email) }}" alt="" />
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                {{ $user->name }}
                                            </div>
                                            <div>
                                                {{-- ユーザー詳細ページへのリンク --}}
                                                <p><a class="link link-hover text-info" href="{{ route('users.show', $user->id) }}">View profile</a></p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            {{-- ページネーションのリンクを削除 --}}
                            {{-- {{ $users->links() }} --}}
                        @endif
                    </div>
                </div>

                {{-- 右部レーン --}}
                <div class="col-span-1">
                    {{-- 空白 --}}
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