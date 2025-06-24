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
            // 全ての投稿を取得（リポスト情報も含む）
            $microposts = Micropost::with(['user', 'originalPost.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // インプレッション数をインクリメント（元投稿に対して）
            foreach ($microposts as $micropost) {
                if ($micropost->repost_from) {
                    // リポストの場合は元投稿のインプレッションを増加
                    $micropost->originalPost->incrementImpressions();
                    $micropost->originalPost->save();
                } else {
                    // 通常投稿の場合はそのままインクリメント
                    $micropost->incrementImpressions();
                    $micropost->save();
                }
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
        // リポストの場合は元投稿をお気に入りに追加
        $targetPost = $micropost->repost_from ? $micropost->originalPost : $micropost;
        \Auth::user()->favorites()->attach($targetPost);
        return back();
    }

    public function unfavorite(Micropost $micropost)
    {
        // リポストの場合は元投稿をお気に入りから削除
        $targetPost = $micropost->repost_from ? $micropost->originalPost : $micropost;
        \Auth::user()->favorites()->detach($targetPost);
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
        // リプライ元の投稿を取得（リポストの場合は元投稿を取得）
        $micropost = Micropost::with(['user', 'originalPost.user'])->findOrFail($id);
        
        // 表示対象の投稿を決定（リポストの場合は元投稿）
        $targetPost = $micropost->repost_from ? $micropost->originalPost : $micropost;

        // インプレッション数をインクリメント
        $targetPost->incrementImpressions();
        $targetPost->save();

        // リプライ一覧を作成日時の降順で取得（元投稿に対するリプライ）
        $replies = $targetPost->replies()->with(['user', 'originalPost.user'])->orderBy('created_at', 'desc')->paginate(10);

        return view('microposts.showReplies', [
            'micropost' => $targetPost, // 元投稿を渡す
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
        
        // リポストの場合は元投稿を取得
        $originalPost = $repostFrom->repost_from ? Micropost::findOrFail($repostFrom->repost_from) : $repostFrom;
        
        // 認証済みユーザーを取得
        if (\Auth::check()) {
            $user = \Auth::user();

            // 既に同じ投稿をリポストしていないかチェック
            $existingRepost = $user->microposts()
                ->where('repost_from', $originalPost->id)
                ->first();

            if ($existingRepost) {
                return back()->with('error', '既にリポストしています');
            }

            // 自分の投稿はリポストできない
            if ($originalPost->user_id === $user->id) {
                return back()->with('error', '自分の投稿はリポストできません');
            }

            // リポストを作成（contentは空にして、元投稿のIDのみ保存）
            $user->microposts()->create([
                'content' => '', // リポストの場合はcontentは空
                'repost_from' => $originalPost->id,
            ]);
            
            return back()->with('success', 'リポストしました');
        } else {
            // 認証されていない場合は、ログインページへリダイレクト
            return redirect('/login');
        }
    }

    /**
     * 重複リポストチェック用のヘルパーメソッド
     */
    private function hasUserReposted($userId, $postId)
    {
        return Micropost::where('user_id', $userId)
            ->where('repost_from', $postId)
            ->exists();
    }
}