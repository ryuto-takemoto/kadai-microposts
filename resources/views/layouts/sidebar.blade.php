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