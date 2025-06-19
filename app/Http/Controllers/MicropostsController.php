<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
use App\Models\SearchLog;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Auth;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            // 全ての投稿を取得 (created_atで降順にソートし、ページネーション)
            $microposts = Micropost::with('user')->orderBy('created_at', 'desc')->paginate(10); // 修正

            // インプレッション数をインクリメント
            foreach ($microposts as $micropost) {
                $micropost->incrementImpressions();
                $micropost->save();
            }

            // トレンドキーワードランキングを取得
            $trendKeywords = SearchLog::select('keyword', DB::raw('count(*) as count'))
                ->orderBy('count', 'desc')
                ->groupBy('keyword')
                ->limit(5)
                ->get();

            // 直近100件の検索ログを取得
            $recentSearchLogsCount = SearchLog::limit(100)->count();

            $data = [
                'user' => $user,
                'microposts' => $microposts,
                'trendKeywords' => $trendKeywords,
                'recentSearchLogsCount' => $recentSearchLogsCount,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);

    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);

        return back();
    }

    public function destroy(string $id)
    {
        $micropost = Micropost::findOrFail($id);

        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return back()
                ->with('success', 'Delete Successful');
        }

        return back()
            ->with('Delete Failed');
    }

    public function favorite(Micropost $micropost)
    {
        \Auth::user()->favorites()->attach($micropost);
        return back();
    }

    public function unfavorite(Micropost $micropost)
    {
        \Auth::user()->favorites()->detach($micropost);
        return back();
    }

    /**
         * リプライを投稿するアクション。
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id リプライ先の投稿ID
         * @return \Illuminate\Http\RedirectResponse
    */
    public function reply(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);

        // リプライ先の投稿を取得
        $replyTo = Micropost::findOrFail($id);

        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        \Auth::user()->microposts()->create([
            'content' => $request->content,
            'reply_to' => $replyTo->id,
        ]);

        // リプライ一覧ページへリダイレクト
        return redirect()->route('microposts.showReplies', $replyTo->id);
    }

    /**
     * リプライ一覧ページを表示するアクション。
     *
     * @param  int  $id リプライ元の投稿ID
     * @return \Illuminate\Http\Response
     */
    public function showReplies($id)
    {
        // リプライ元の投稿を取得
        $micropost = Micropost::findOrFail($id);

        // インプレッション数をインクリメント
        $micropost->incrementImpressions();

        // リプライ一覧を作成日時の降順で取得
        $replies = $micropost->replies()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('microposts.showReplies', [
            'micropost' => $micropost,
            'microposts' => $replies, // リプライを $microposts として渡す
        ]);
    }
    
    /**
     * リポストするアクション。
     *
     * @param  int  $id リポスト元の投稿ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function repost($id)
    {
        // リポスト元の投稿を取得
        $repostFrom = Micropost::findOrFail($id);
        
        // 認証済みユーザーを取得
        if (\Auth::check()) {
            $user = \Auth::user();

            // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
            $user->microposts()->create([
                'content' => $repostFrom->content,
                'repost_from' => $repostFrom->id,
            ]);
            // 前のURLへリダイレクトさせる
            return back();
        }else {
            // 認証されていない場合は、ログインページへリダイレクト
            return redirect('/login');
        }
    }
}