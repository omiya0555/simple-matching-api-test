<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;

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
}
