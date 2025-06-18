<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $impressionsMin = $request->input('impressions_min');
        $impressionsMax = $request->input('impressions_max');
        $favoritesMin = $request->input('favorites_min');
        $favoritesMax = $request->input('favorites_max');

        $query = Micropost::query()->orderBy('created_at', 'desc');

        // キーワード検索
        if (!empty($keyword)) {
            $query->where('content', $keyword);
        }

        // インプレッション数の絞り込み
        if (!empty($impressionsMin)) {
            $query->where('impressions', '>=', $impressionsMin);
        }
        if (!empty($impressionsMax)) {
            $query->where('impressions', '<=', $impressionsMax);
        }

        // いいね数の絞り込み
        if (!empty($favoritesMin)) {
            // リレーションを使っていいね数を絞り込む
            $query->whereHas('favorites', function ($q) use ($favoritesMin) {
                $q->selectRaw('count(*) as total')
                    ->groupBy('micropost_id')
                    ->having('total', '>=', $favoritesMin);
            });
        }
        if (!empty($favoritesMax)) {
            // リレーションを使っていいね数を絞り込む
            $query->whereHas('favorites', function ($q) use ($favoritesMax) {
                $q->selectRaw('count(*) as total')
                    ->groupBy('micropost_id')
                    ->having('total', '<=', $favoritesMax);
            });
        }

        $microposts = $query->paginate(10);

        return view('search.index', [
            'microposts' => $microposts,
            'keyword' => $keyword,
            'impressions_min' => $impressionsMin,
            'impressions_max' => $impressionsMax,
            'favorites_min' => $favoritesMin,
            'favorites_max' => $favoritesMax,
        ]);
    }
}
