<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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

        $selected_role = request('selected_role', 'all');

        $search = request('search', '');

        $sort_direction = request('sort_direction', 'desc');
        if (!in_array($sort_direction, ['asc', 'desc'])) {
            $sort_direction = 'desc';
        }

        $sort_field = request('sort_field', 'created_at');
        if (!in_array($sort_field, ['id', 'fullname', 'username', 'email', 'phone'])) {
            $sort_field = 'created_at';
        }
        $getAllUsers = User::search(trim($search))
            ->when($selected_role !== 'all', function ($query) use ($selected_role) {
                $query->where('role_as', $selected_role);
            })
            ->when($sort_field, function ($query) use ($sort_direction, $sort_field) {
                if ($sort_field == 'fullname') {
                    $query->orderBy('firstname', $sort_direction)
                        ->orderBy('lastname', $sort_direction);
                } else {
                    $query->orderBy($sort_field, $sort_direction);
                }
            })
            ->paginate($paginate);

        return new UserCollection($getAllUsers);
    }

    /**
     * Get all id of user
     * @return \Illuminate\Support\Collection
     */
    public function selectAllUser()
    {
        return User::pluck('id');
    }

    public function getUserById($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng.'
            ], 404);
        }

        return response()->json(
            [
                'user' => $user
            ],
            200
        );
    }

    public function createUser(UserFormRequest $request)
    {
        $validatedInputs = $request->validated();

        $user = new User();
        $user->firstname = $validatedInputs['firstname'];
        $user->lastname = $validatedInputs['lastname'];
        $user->name = $validatedInputs['firstname'] . ' ' . $validatedInputs['lastname'];
        $user->email = $validatedInputs['email'];
        $user->address = $validatedInputs['address'];
        $user->gender = $request->gender ?? 1;
        $user->phone = $validatedInputs['phone'];
        $user->password = Hash::make(trim($validatedInputs['password']));
        $user->confirm_password = $validatedInputs['password'] == $validatedInputs['confirm_password'] ? 'true' : 'false';
        $user->role_as = $validatedInputs['role_as'];

        $user->save();

        return response()->json(
            [
                'message' => 'Tạo người dùng thành công.',
                'user' => $user
            ],
            200
        );
    }

    public function editUser($id, UserFormRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);
        if ($user) {
            $user->firstname = $validatedData['firstname'];
            $user->lastname = $validatedData['lastname'];
            $user->name = $validatedData['firstname'] . ' ' . $validatedData['lastname'];
            $user->email = $validatedData['email'];
            $user->address = $validatedData['address'];
            $user->gender = $request->gender ?? 1;
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

    public function update(int $id, UserFormRequest $request)
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

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(
            [
                'message' => 'Xóa người dùng thành công.'
            ],
            200
        );
    }

    public function deleteMultiUser($users)
    {
        $usersIdArray = explode(',', $users);
        $users = User::whereIn('id', $usersIdArray)->get();
        foreach ($users as $user) {
            $user->delete();
        }
        return response()->json([
            'message' => 'Xóa ' . count($usersIdArray) . ' người dùng thành công.'
        ], 200);
    }

    public function getUserAddresses($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng.'
            ], 404);
        }
        return response()->json([
            'addresses' => $user->shippingAddresses
        ], 200);
    }
}
