<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Micropost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (Auth::check()) {
            $user = Auth::user();
            dd($user); // 追加
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }

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

        if (Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return back()
                ->with('success', 'Delete Successful');
        }

        return back()
            ->with('Delete Failed');
    }
}