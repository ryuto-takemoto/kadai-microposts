@extends('layouts.app')

@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <aside class="mt-4">
            {{-- ユーザー情報 --}}
            @include('users.card')
        </aside>
        <div class="sm:col-span-2">
            {{-- タブ --}}
            @include('users.navtabs', ['user' => $user])

            {{-- 投稿フォーム --}}
            @if (Auth::id() == $user->id) {{-- 自分のページでのみ投稿フォームを表示 --}}
                @include('microposts.form')
            @endif
            {{-- 投稿一覧 --}}
            @include('microposts.microposts', ['microposts' => $microposts])
        </div>
    </div>
@endsection