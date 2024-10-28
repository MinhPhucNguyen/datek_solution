<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddressesCollection;
use Illuminate\Http\Request;
use App\Models\UserAddresses;

class UserAddressesController extends Controller
{
    public function index(){
        $addresses = UserAddresses::all();  
        return new UserAddressesCollection($addresses);
    }

    public function getAddressesByUserId($id){
        $addresses = UserAddresses::where('user_id', $id)->get();
        return new UserAddressesCollection($addresses);
    }

    public function store(Request $request){
        $address = new UserAddresses();
        $address->user_id = $request->user_id;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->ward = $request->ward;
        $address->district = $request->district;

        $address->save();

        return response()->json(
            [
                'message' => 'Tạo địa chỉ thành công.',
                'address' => $address
            ],
            200
        );
    }

    public function edit($id){
        $address = UserAddresses::find($id);
        if(!$address){
            return response()->json(
                [
                    'message' => 'Không tìm thấy địa chỉ.'
                ],
                404
            );
        }

        return response()->json(
            [
                'address' => $address
            ],
            200
        );
    }

    public function update(Request $request, $id){
        $address = UserAddresses::find($id);
        if(!$address){
            return response()->json(
                [
                    'message' => 'Không tìm thấy địa chỉ.'
                ],
                404
            );
        }

        $address->update( $request->all() );

        return response()->json(
            [
                'message' => 'Cập nhật địa chỉ thành công.',
                'address' => $address
            ],
            200
        );
    }

    public function destroy($id){
        $address = UserAddresses::find($id);
        if(!$address){
            return response()->json(
                [
                    'message' => 'Không tìm thấy địa chỉ.'
                ],
                404
            );
        }

        $address->delete();

        return response()->json(
            [
                'message' => 'Xóa địa chỉ thành công.'
            ],
            200
        );
    }

    public function show($id){
        $address = UserAddresses::find($id);
        if(!$address){
            return response()->json(
                [
                    'message' => 'Không tìm thấy địa chỉ.'
                ],
                404
            );
        }

        return response()->json(
            [
                'address' => $address
            ],
            200
        );
    }

}
