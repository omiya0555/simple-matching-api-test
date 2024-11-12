<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Redis; // オンライン状態の管理に用いる

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location',
        'profile_image',
    ];

    public function likesSent()
    {
        return $this->hasMany(Like::class, 'sender_id');
    }

    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'receiver_id');
    }

    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ユーザーのオンライン状態を更新（各種アクションの際に呼び出す）
    public function updateOnlineStatus()
    {
        $timestamp = now()->timestamp;
        Redis::hset('online_users', $this->id, $timestamp);
    }

    // ユーザーがオンラインかどうかを判定
    public function isOnline()
    {
        $lastActive = Redis::hget('online_users', $this->id);

        if ($lastActive) {
            $lastActive = (int) $lastActive;
            $inactiveTime = now()->timestamp - $lastActive;

            // 10分以内であればオンラインと判定
            // 下記の判定時間はサービスに定義する事も検討
            return $inactiveTime < 600;
        }
        return false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
