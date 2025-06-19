<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'impressions', 'reply_to', 'repost_from']; // reply_to, repost_from を追加

    /**
     * この投稿を所有するユーザー。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }

    /**
     * リプライ先の投稿。（Micropostモデルとの関係を定義）
     */
    public function replyTo()
    {
        return $this->belongsTo(Micropost::class, 'reply_to');
    }

    /**
     * この投稿へのリプライ。（Micropostモデルとの関係を定義）
     */
    public function replies()
    {
        return $this->hasMany(Micropost::class, 'reply_to');
    }

    /**
     * リポスト元の投稿。（Micropostモデルとの関係を定義）
     */
    public function repostFrom()
    {
        return $this->belongsTo(Micropost::class, 'repost_from');
    }

    /**
     * この投稿のリポスト。（Micropostモデルとの関係を定義）
     */
    public function reposts()
    {
        return $this->hasMany(Micropost::class, 'repost_from');
    }

    /**
     * インプレッション数をインクリメントする。
     */
    public function incrementImpressions()
    {
        $this->impressions++;
        $this->save();
    }
}