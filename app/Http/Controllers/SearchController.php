<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
use App\Models\SearchLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $impressionsMin = $request->input('impressions_min');
        $impressionsMax = $request->input('impressions_max');
        $favoritesMin = $request->input('favorites_min');
        $favoritesMax = $request->input('favorites_max');

        // 検索ログを保存
        if (!empty($keyword)) {
            SearchLog::create([
                'keyword' => $keyword,
                'user_id' => Auth::id(), // ログインしていなければ null
            ]);
        }

        $query = Micropost::query()->orderBy('created_at', 'desc');

        // キーワード検索
        if (!empty($keyword)) {
            $query->where('content', 'like', '%' . $keyword . '%'); // 修正
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

        // トレンドキーワードランキングを取得
        $trendKeywords = SearchLog::select('keyword', DB::raw('count(*) as count'))
            ->orderBy('count', 'desc')
            ->groupBy('keyword')
            ->limit(5)
            ->get();

        // 直近100件の検索ログを取得
        $recentSearchLogsCount = SearchLog::limit(100)->count(); // 修正

        $data = [
            'microposts' => $microposts,
            'keyword' => $keyword,
            'impressions_min' => $impressionsMin,
            'impressions_max' => $impressionsMax,
            'favorites_min' => $favoritesMin,
            'favorites_max' => $favoritesMax,
            'trendKeywords' => $trendKeywords,
            'recentSearchLogsCount' => $recentSearchLogsCount,
        ];

        return view('search.index', $data);
    }
}