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
     * コントローラーで使用するためのエイリアス
     */
    public function originalPost()
    {
        return $this->belongsTo(Micropost::class, 'repost_from');
    }

    /**
     * リポスト元の投稿。（Micropostモデルとの関係を定義）
     * 既存のメソッド名を維持
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

    /**
     * この投稿がリポストかどうかを判定する
     */
    public function isRepost()
    {
        return !is_null($this->repost_from);
    }

    /**
     * 表示用のコンテンツを取得する（リポストの場合は元投稿のコンテンツ）
     */
    public function getDisplayContentAttribute()
    {
        return $this->isRepost() ? $this->originalPost->content : $this->content;
    }

    /**
     * 表示用のユーザーを取得する（リポストの場合は元投稿のユーザー）
     */
    public function getDisplayUserAttribute()
    {
        return $this->isRepost() ? $this->originalPost->user : $this->user;
    }

    /**
     * 表示用の投稿日時を取得する（リポストの場合は元投稿の日時）
     */
    public function getDisplayCreatedAtAttribute()
    {
        return $this->isRepost() ? $this->originalPost->created_at : $this->created_at;
    }

    /**
     * 表示用の投稿IDを取得する（リポストの場合は元投稿のID）
     */
    public function getDisplayPostIdAttribute()
    {
        return $this->isRepost() ? $this->originalPost->id : $this->id;
    }

    /**
     * 表示用の投稿オブジェクトを取得する（リポストの場合は元投稿）
     */
    public function getDisplayPostAttribute()
    {
        return $this->isRepost() ? $this->originalPost : $this;
    }

    /**
     * この投稿のリポスト数を取得する
     */
    public function getRepostCountAttribute()
    {
        return $this->reposts()->count();
    }

    /**
     * 指定されたユーザーがこの投稿をリポストしているかチェック
     */
    public function isRepostedBy($userId)
    {
        return $this->reposts()->where('user_id', $userId)->exists();
    }
}