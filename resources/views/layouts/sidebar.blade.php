<div class="col-span-1 flex flex-col h-screen items-start p-4 bg-base-100 rounded-box shadow-md">
    {{-- ロゴ --}}
    <div class="flex justify-start items-center mb-6">
        <a class="btn btn-ghost normal-case text-xl" href="/">Microposts</a>
    </div>

    {{-- メニュー --}}
    <div class="flex flex-col items-start justify-center flex-grow w-full">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost normal-case w-full justify-start hover:bg-base-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            トップページ
        </a>
        <a href="{{ route('users.show', Auth::id()) }}" class="btn btn-ghost normal-case w-full justify-start hover:bg-base-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            マイページ
        </a>
        <a href="{{ route('search.index') }}" class="btn btn-ghost normal-case w-full justify-start hover:bg-base-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            検索ページ
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-ghost normal-case w-full justify-start hover:bg-base-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            ユーザー一覧
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost normal-case w-full justify-start hover:bg-base-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                </svg>
                ログアウト
            </button>
        </form>
    </div>

    {{-- ユーザー情報 --}}
    <div class="flex flex-col items-start justify-end w-full mb-4">
        <a href="{{ route('users.show', Auth::id()) }}" class="mb-2">
            <div class="avatar">
                <div class="w-12 rounded">
                    <img src="{{ Gravatar::get(Auth::user()->email) }}" alt="avatar" />
                </div>
            </div>
        </a>
        <p class="text-left">{{ Auth::user()->name }}</p>
    </div>
</div>