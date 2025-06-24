@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if (Auth::check())
            <div class="grid grid-cols-3 gap-4">
                
                @include('layouts.sidebar') {{-- サイドバーの共通化 --}}
                
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