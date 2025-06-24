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
                                        {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                                        <div class="avatar">
                                            <div class="w-12 rounded">
                                                <img src="{{ Gravatar::get($reply->user->email) }}" alt="" />
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                                                <a class="link link-hover text-info" href="{{ route('users.show', $reply->user->id) }}">{{ $reply->user->name }}</a>
                                                <span class="text-muted text-gray-500">posted at {{ $reply->created_at }}</span>
                                            </div>
                                            <div>
                                                {{-- 投稿内容 --}}
                                                <p class="mb-0">{!! nl2br(e($reply->content)) !!}</p>
                                            </div>
                                            <div>
                                                {{-- インプレッション数 --}}
                                                <span class="text-muted text-gray-500">Impressions: {{ $reply->impressions }}</span>
                                            </div>
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
                    {{-- リプライ元の投稿表示 --}}
                    <div class="card bg-base-100 shadow-xl mb-4">
                        <div class="card-body">
                            <h3 class="card-title text-lg">元の投稿</h3>
                            <div class="flex items-start gap-x-2">
                                {{-- 元投稿者のアバター --}}
                                <div class="avatar">
                                    <div class="w-12 rounded">
                                        <img src="{{ Gravatar::get($micropost->user->email) }}" alt="" />
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <a class="link link-hover text-info font-semibold" href="{{ route('users.show', $micropost->user->id) }}">{{ $micropost->user->name }}</a>
                                        <span class="text-muted text-gray-500 text-sm ml-2">{{ $micropost->created_at }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <p class="text-base">{!! nl2br(e($micropost->content)) !!}</p>
                                    </div>
                                    <div>
                                        <span class="text-muted text-gray-500 text-sm">Impressions: {{ $micropost->impressions }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 前のページに戻るボタン --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg">ナビゲーション</h3>
                            <div class="card-actions">
                                <button onclick="history.back()" class="btn btn-outline btn-primary w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                    </svg>
                                    前のページに戻る
                                </button>
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