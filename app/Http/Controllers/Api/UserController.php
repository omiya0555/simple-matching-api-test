<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * ユーザーの一覧を取得
     */
    public function index()
    {
        $users = User::select('users.id', 'users.name', 'user_icons.icon_path as profile_image', 'users.location', 'users.gender')
            ->leftJoin('user_icons', 'users.icon_id', '=', 'user_icons.id')
            ->get();
    
        return response()->json($users);
    }

    /**
     * 特定のユーザーの詳細情報を取得
     */
    public function show($id)
    {
        $user = User::leftJoin('user_icons', 'users.icon_id', '=', 'user_icons.id')
            ->where('users.id', $id)
            ->select('users.id', 'users.name', 'user_icons.icon_path as profile_image', 'users.location', 'users.gender', 'users.birth_date', 'users.bio', 'users.is_verified')
            ->firstOrFail();
    
        return response()->json($user);
    }

    /**
     * 特定のユーザー情報を更新
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $validatedData = $request->validate([
            'name'     => 'string|max:255',
            'icon_id'  => 'integer|exists:user_icons,id', // アイコンIDのバリデーションを追加
            'location' => 'string|max:255',
        ]);
    
        DB::beginTransaction();
    
        try {
            $user->update($validatedData);
    
            DB::commit();
            return response()->json(['message' => 'ユーザー更新成功', 'user' => $user], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'ユーザー更新失敗', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * オンラインユーザーのランダムリスト取得
     */
    public function getRandomOnlineUsers()
    {
        $onlineUsers = Redis::hkeys('online_users');
    
        if (empty($onlineUsers)) {
            return response()->json(['message' => 'オンラインのユーザーはいません'], 200);
        }
    
        $users = User::select('users.id', 'users.name', 'user_icons.icon_path as profile_image')
            ->leftJoin('user_icons', 'users.icon_id', '=', 'user_icons.id')
            ->whereIn('users.id', $onlineUsers)
            ->inRandomOrder()
            ->take(10)
            ->get();
    
        return response()->json($users);
    }

    /**
     * online user check :test
     */
    public function setOnlineUsers()
    {
        // IDが1から15のユーザーを取得
        $users = User::whereBetween('id', [25, 45])->get();

        // 各ユーザーのIDをRedisにオンラインユーザーとしてセット
        foreach ($users as $user) {
            Redis::hset('online_users', $user->id, now()->timestamp);
        }

        return response()->json(['message' => 'オンラインユーザーを設定しました'], 200);
    }
}
