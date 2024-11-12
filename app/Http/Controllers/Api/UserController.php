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
        $users = User::select('id', 'name', 'profile_image', 'location', 'gender')->get();
        return response()->json($users);
    }

    /**
     * 特定のユーザーの詳細情報を取得
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'id'            => $user->id,
            'name'          => $user->name,
            'profile_image' => $user->profile_image,
            'location'      => $user->location,
            'gender'        => $user->gender,
            'birth_date'    => $user->birth_date,
            'bio'           => $user->bio,
            'is_verified'   => $user->is_verified,
        ]);
    }

    /**
     * 特定のユーザー情報を更新
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name'          => 'string|max:255',
            'profile_image' => 'string|max:255',
            'location'      => 'string|max:255',
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
    
        // オンラインユーザーのIDリストから10人をランダムに取得
        $users = User::whereIn('id', $onlineUsers)
                     ->inRandomOrder()  // ランダム並び替え
                     ->take(10)         // 10人まで取得
                     ->get(['id', 'name', 'profile_image']); 
    
        return response()->json($users);
    }

    /**
     * online user check :test
     */
    public function setOnlineUsers()
    {
        // IDが1から15のユーザーを取得
        $users = User::whereBetween('id', [1, 15])->get();

        // 各ユーザーのIDをRedisにオンラインユーザーとしてセット
        foreach ($users as $user) {
            Redis::hset('online_users', $user->id, now()->timestamp);
        }

        return response()->json(['message' => 'オンラインユーザーを設定しました'], 200);
    }
}
