<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Micropost;

class FavoritesController extends Controller
{
    /**
     * 投稿をお気に入りに追加するアクション。
     *
     * @param  $id  お気に入りに追加する投稿のid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function favorite($id)
    {
        // 認証済みユーザーを取得
        $user = Auth::user();

        // idが$idの投稿をお気に入りに追加する
        $micropost = Micropost::findOrFail($id);
        if (!$user->is_favoriting($micropost->id)) {
            $user->favorite($micropost->id);
        }

        // 前のページへリダイレクト
        return back();
    }

    /**
     * 投稿のお気に入りを解除するアクション。
     *
     * @param  $id  お気に入りを解除する投稿のid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfavorite($id)
    {
        // 認証済みユーザーを取得
        $user = Auth::user();

        // idが$idの投稿をお気に入りを解除する
        $micropost = Micropost::findOrFail($id);
        if ($user->is_favoriting($micropost->id)) {
            $user->unfavorite($micropost->id);
        }

        // 前のページへリダイレクト
        return back();
    }
}