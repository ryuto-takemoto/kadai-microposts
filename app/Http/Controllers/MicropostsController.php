<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
//use Illuminate\Support\Facades\Auth;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            //$microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            // 全ての投稿を取得 (created_atで降順にソートし、ページネーション)
            $microposts = Micropost::with('user')->orderBy('created_at', 'desc')->paginate(10);

            // インプレッション数をインクリメント
            foreach ($microposts as $micropost) {
                $micropost->impressions++;
                $micropost->save();
            }

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
            return view('dashboard', $data);
        }
        else {
            return redirect('/login'); // ログインしていなければログインページへリダイレクト
        }
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

    public function reply(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);

        // リプライ先の投稿を取得
        $replyTo = Micropost::findOrFail($id);

        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->microposts()->create([
            'content' => $request->content,
            'reply_to' => $replyTo->id,
        ]);

        // 前のURLへリダイレクトさせる
        return back();
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