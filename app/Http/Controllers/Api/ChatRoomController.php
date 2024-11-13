<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatRoom;

class ChatRoomController extends Controller
{
    /**
     * ユーザーが属するチャットルームの一覧を取得
     * マッチした人とのチャットルーム一覧
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // ユーザーが参加しているチャットルームを取得
        // usersとのリレーションでユーザー情報も取得
        $chatRooms = ChatRoom::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('users:id,name,profile_image')->get();

        return response()->json($chatRooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
