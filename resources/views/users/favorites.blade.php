@extends('layouts.app')

@section('content')
    <h1>Favorites</h1>

    @if (count($favorites) > 0)
        <ul class="list-none">
            @foreach ($favorites as $micropost)
                <li class="mb-2">
                    <div class="flex items-center">
                        <p>{{ $micropost->content }}</p>
                        @if (Auth::user()->is_favoriting($micropost->id))
                            <form method="POST" action="{{ route('microposts.unfavorite', $micropost->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm">Unfavorite</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('microposts.favorite', $micropost->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Favorite</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p>No favorites yet.</p>
    @endif
@endsection