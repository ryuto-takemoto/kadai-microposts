@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        @if (Auth::check())
            <div class="grid grid-cols-3 gap-4">
                
                @include('layouts.sidebar') {{-- サイドバーの共通化 --}}
                {{-- 中央レーン --}}
                <div class="col-span-1 flex flex-col">
                    <h1 class="text-2xl font-bold mb-4">検索結果</h1>

                    <div class="overflow-y-auto h-timeline">
                        @if (isset($microposts) && count($microposts) > 0)
                            <p>{{ count($microposts) }}件の結果が見つかりました。</p>
                            {{-- 投稿一覧 --}}
                            @include('microposts.microposts', ['microposts' => $microposts])
                        @elseif (isset($microposts))
                            <p>該当する投稿は見つかりませんでした。</p>
                        @endif
                    </div>
                </div>

                {{-- 右部レーン --}}
                <div class="col-span-1 flex flex-col items-start">
                    <h2 class="text-lg font-bold mb-2">検索条件</h2>
                    <form action="{{ route('search.index') }}" method="GET" class="w-full">
                        <div class="mb-2">
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">キーワード:</label>
                            <input type="text" name="keyword" id="keyword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline input input-bordered" value="{{ $keyword ?? '' }}">
                        </div>

                        <div class="mb-2">
                            <label for="impressions_min" class="block text-gray-700 text-sm font-bold mb-2">インプレッション数 (以上):</label>
                            <input type="number" name="impressions_min" id="impressions_min" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline input input-bordered" value="{{ request('impressions_min') }}">
                        </div>
                        <div class="mb-2">
                            <label for="impressions_max" class="block text-gray-700 text-sm font-bold mb-2">インプレッション数 (以下):</label>
                            <input type="number" name="impressions_max" id="impressions_max" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline input input-bordered" value="{{ request('impressions_max') }}">
                        </div>

                        <div class="mb-2">
                            <label for="favorites_min" class="block text-gray-700 text-sm font-bold mb-2">いいね数 (以上):</label>
                            <input type="number" name="favorites_min" id="favorites_min" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline input input-bordered" value="{{ request('favorites_min') }}">
                        </div>
                        <div class="mb-2">
                            <label for="favorites_max" class="block text-gray-700 text-sm font-bold mb-2">いいね数 (以下):</label>
                            <input type="number" name="favorites_max" id="favorites_max" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline input input-bordered" value="{{ request('favorites_max') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">検索</button>
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
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection