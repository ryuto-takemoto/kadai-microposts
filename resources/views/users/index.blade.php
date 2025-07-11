@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if (Auth::check())
            <div class="grid grid-cols-3 gap-4">
                {{-- 左部レーン --}}
                @include('layouts.sidebar') {{-- サイドバーの共通化 --}}

                {{-- 中央レーン --}}
                <div class="col-span-1 flex flex-col">
                    <h1 class="text-2xl font-bold mb-4">ユーザー一覧</h1>

                    <div class="overflow-y-auto h-timeline">
                        @include('users.users', ['users' => $users]) {{-- 全ユーザーの情報を表示 --}}
                    </div>
                </div>

                {{-- 右部レーン --}}
                <div class="col-span-1">
                    {{-- 空白 --}}
                </div>
            </div>
        @else
            @include('layouts.guest') {{-- ゲストユーザー向けの表示 --}}
        @endif
    </div>
@endsection