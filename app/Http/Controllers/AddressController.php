<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function addAddress (Request $request)
    {
        $request->validate([
            'new_address' => 'required|unique:addresses,address',
        ]);

        $user = User::find(auth()->id());
        if (request('main_new_address'))  {
            Address::where('user_id', $user->id)->update([
                'main' => false
            ]);
            $main_address = true;
        } else {
            $main_address = !$user->addresses->contains(function($address) {
                return $address->main == true;
            });
        }
        $address = new Address();
        $address->user_id = $user->id;
        $address->address = request('new_address');
        $address->main = $main_address;
        $address->save();
        $user->refresh();
        return $user->addresses;
    }

    public function del_address () 
    {
        $user = User::find(auth()->id());
        Address::find(request('address_id'))->delete();
        return $user->addresses;
    }

    public function updateMainAddress (Request $request) {

        $user = Auth::user();

            Validator::make($request->all(), [
                'main_address' => 'num'
            ]);
    
            $old_main_address = request('main_address');
            $address = Address::find($old_main_address);
            $address->main = true;
            $address->save();

            Address::where('user_id', $user->id)->where('id', '!=' , $address->id)->update([
                'main' => false
            ]);
        return $user->addresses;
       
    }
}
