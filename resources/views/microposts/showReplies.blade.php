@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>リプライ一覧</h1>

        {{-- リプライ元の投稿 --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $micropost->user->name }}</h5>
                <p class="card-text">{!! nl2br(e($micropost->content)) !!}</p>
                <span class="text-muted text-gray-500">Impressions: {{ $micropost->impressions }}</span> {{-- インプレッション数 --}}
            </div>
        </div>

        {{-- リプライ投稿フォーム --}}
        <form method="POST" action="{{ route('microposts.reply', $micropost->id) }}">
            @csrf
            <div class="form-group">
                <label for="content">リプライ内容:</label>
                <textarea name="content" id="content" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">リプライ</button>
        </form>

        {{-- リプライ一覧 --}}
        @if (isset($microposts) && count($microposts) > 0)
            <ul class="list-none">
                @foreach ($microposts as $micropost)
                    <li class="flex items-start gap-x-2 mb-4 border-b border-gray-200 py-2">
                        {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                        <div class="avatar">
                            <div class="w-12 rounded">
                                <img src="{{ Gravatar::get($micropost->user->email) }}" alt="" />
                            </div>
                        </div>
                        <div>
                            <div>
                                {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                                <a class="link link-hover text-info" href="{{ route('users.show', $micropost->user->id) }}">{{ $micropost->user->name }}</a>
                                <span class="text-muted text-gray-500">posted at {{ $micropost->created_at }}</span>
                            </div>
                            <div>
                                {{-- 投稿内容 --}}
                                <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                            </div>
                            <div>
                                {{-- インプレッション数 --}}
                                <span class="text-muted text-gray-500">Impressions: {{ $micropost->impressions }}</span>
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
@endsection