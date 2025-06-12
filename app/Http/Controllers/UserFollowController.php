<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth; // Authファサードを追加

class UserFollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // 認証ミドルウェアを適用
    }
    /**
     * ユーザーをフォローするアクション。
     *
     * @param  $id  相手ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function store(string $id)
    {
        // 認証済みユーザー（閲覧者）が、 idのユーザーをフォローする
        \Auth::user()->follow(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * ユーザーをアンフォローするアクション。
     *
     * @param  $id  相手ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        // 認証済みユーザー（閲覧者）が、 idのユーザーをアンフォローする
        \Auth::user()->unfollow(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }
}
