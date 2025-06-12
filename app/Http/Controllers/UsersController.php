<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        // ユーザー一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // ユーザー一覧ビューでそれを表示
        return view('users.index', [
            'users' => $users
        ]);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        // 特定のユーザーの投稿のみを取得 (created_atで降順にソートし、ページネーション)
        $microposts = $user->microposts()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'user' => $user,
            'microposts' => $microposts,
        ];

        return view('users.show', $data);
    }

    public function favorites(User $user)
    {
        $favorites = $user->favorites()->paginate(10);

        return view('users.favorites', [
            'user' => $user,
            'favorites' => $favorites,
        ]);
    }

    /**
     * ユーザーをフォローするアクション。
     *
     * @param  $id  フォローするユーザーのid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow($id)
    {
        // 認証済みユーザーを取得
        $user = Auth::user();

        // idが$idのユーザーをフォローする
        if ($user->id != $id) { // 自分自身をフォローしないようにする
            $user->follow($id);
        }

        // 前のページへリダイレクト
        return back();
    }

    /**
     * ユーザーをアンフォローするアクション。
     *
     * @param  $id  アンフォローするユーザーのid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow($id)
    {
        // 認証済みユーザーを取得
        $user = Auth::user();

        // idが$idのユーザーをアンフォローする
        $user->unfollow($id);

        // 前のページへリダイレクト
        return back();
    }

    /**
     * ユーザーのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザーのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
}
