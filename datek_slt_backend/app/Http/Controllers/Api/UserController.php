<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserFormRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $paginate = 10;

        $getAllUsers = User::with('addresses')
            ->paginate($paginate);

        return new UserCollection($getAllUsers);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->phone = '+84' . substr($request->phone, 1);
        $user->password = Hash::make(trim($request->password));
        $user->confirm_password = $request->password == $request->confirm_password ? 'true' : 'false';
        $user->role_as = $request->role_as;

        $user->save();

        return response()->json(
            [
                'message' => 'Tạo người dùng thành công.',
                'user' => $user
            ],
            200
        );
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy người dùng.'
                ],
                404
            );
        }
        return response()->json(
            [
                'user' => $user
            ],
            200
        );
    }

    public function update(int $id, Request $request)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);
        if ($user) {
            $user->firstname = $validatedData['firstname'];
            $user->lastname = $validatedData['lastname'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            $user->phone = $validatedData['phone'];
            $user->role_as = $validatedData['role_as'];

            if (empty($validatedData['password']) && empty($validatedData['confirm_password'])) {
                unset($validatedData['password']);
                unset($validatedData['confirm_password']);
            } else {
                $user->password = Hash::make($validatedData['password']);
                $user->confirm_password = $validatedData['confirm_password'] == $validatedData['password'] ? 'true' : 'false';
            }
            $user->update();
            return response()->json([
                'message' => "Cập nhật người dùng thành công.",
            ], 200);
        } else {
            return response()->json([
                'message' => "Không tìm thấy người dùng."
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy người dùng.'
                ],
                404
            );
        }
        $user->delete();
        return response()->json(
            [
                'message' => 'Xóa người dùng thành công.'
            ],
            200
        );
    }
}
