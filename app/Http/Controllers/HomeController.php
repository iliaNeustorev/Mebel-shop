<?php

namespace App\Http\Controllers;

use App\Jobs\Search;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function profile()
    {
        $user = Auth::user();
        return [
            'user' => $user,
            'addresses' => $user->addresses
        ];
    }

    public function search(){
        $search = request()->search;
        if($search == null){
            return response('Пустая строка', 422);
        }
         Search::dispatch($search);
        
    }
    
    
    public function profile_update (Request $request)
    {
            $request->validate([
                'name' => 'max:255|alpha_num',
                'email' => 'email',
            ]);

            $user = User::with('addresses')->find(auth()->id());
            $input = $request->all();
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->save();
            return $user;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60)
        ])->save();
        return response()->json([
            'ОК' => 'успешно',
        ], 200);
    }

    public function updateAvatar (Request $request) 
    {
        $request->validate([
            'picture' => 'image',
        ]);
        $user = User::find(auth()->id());
        $file = $request->file('picture');
        if($file) {
            $ext = $file->getClientOriginalExtension();
            $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
            $file->storeAs('public/img/users', $file_name);
            
            $user->picture = $file_name;
            $user->save();
            }
        return $user->picture;
    }

   

}
