<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddresses;

class UserAddressesController extends Controller
{
    public function index(){
        $addresses = UserAddresses::all();  
        return response()->json($addresses);
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

}
