@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>検索結果</h1>

        {{-- 基本検索フォーム --}}
        <form action="{{ route('search.index') }}" method="GET">
            <div class="form-group">
                <label for="keyword">キーワード:</label>
                <input type="text" name="keyword" id="keyword" class="form-control input input-bordered" value="{{ $keyword ?? '' }}">
            </div>

            {{-- インプレッション数の絞り込み --}}
            <div class="form-group">
                <label for="impressions_min">インプレッション数 (以上):</label>
                <input type="number" name="impressions_min" id="impressions_min" class="form-control input input-bordered" value="{{ request('impressions_min') }}">
            </div>
            <div class="form-group">
                <label for="impressions_max">インプレッション数 (以下):</label>
                <input type="number" name="impressions_max" id="impressions_max" class="form-control input input-bordered" value="{{ request('impressions_max') }}">
            </div>

            {{-- いいね数の絞り込み --}}
            <div class="form-group">
                <label for="favorites_min">いいね数 (以上):</label>
                <input type="number" name="favorites_min" id="favorites_min" class="form-control input input-bordered" value="{{ request('favorites_min') }}">
            </div>
            <div class="form-group">
                <label for="favorites_max">いいね数 (以下):</label>
                <input type="number" name="favorites_max" id="favorites_max" class="form-control input input-bordered" value="{{ request('favorites_max') }}">
            </div>

            <button type="submit" class="btn btn-primary">検索</button>
        </form>

        @if (isset($microposts) && count($microposts) > 0)
            <p>{{ count($microposts) }}件の結果が見つかりました。</p>
            {{-- 投稿一覧 --}}
            @include('microposts.microposts', ['microposts' => $microposts])
        @elseif (isset($microposts))
            <p>該当する投稿は見つかりませんでした。</p>
        @endif
    </div>
@endsection