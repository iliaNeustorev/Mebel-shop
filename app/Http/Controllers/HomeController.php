<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $categories = Category::get();
        $data = [
            'user' => $user,
            'categories' => $categories, 
            'title' => 'Главная',
            'show_title' => true
        ];
        return view('home', $data);
    }

     
    public function profile()
    {
        $user = Auth::user();
        return view('profile',[
            'title' => 'Личный кабинет',
            'user' => $user
        ]);
    }

    public function profile_update (Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'picture' => 'required|mimes:jpg,bmp,png'
        // ]);

        // $errors = $validator->errors();

        // if ($validator->fails()) {
        //     {
        //         dd($errors->all());
        //     }
        // } else 
        // {
            $request->validate([
                'file' => 'image',
                'name' => 'max:255|alpha_num',
                'email' => 'email',
                'password' => 'nullable|confirmed|min:8',
                'new_address' => 'unique:addresses,address',
            ]);


            $user = User::find(auth()->id());

            $file = $request->file('file');
            $input = $request->all();
           if ($input['password']) {
                $current_password = $input['current_password'];
                if (!Hash::check($current_password, request()->user()->password)) {
                   session()->flash('currentPasswordError');
                   return back();
                } else {
                   $user->password = Hash::make($input['password']);
                   $user->save();
                }
           }

            if($file) {
            $ext = $file->getClientOriginalExtension();
            $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
            $file->storeAs('public/img/users', $file_name);
            
            $user->picture = $file_name;
            }
            if (isset($input['main_address'])) {
            
                $address = Address::find($input['main_address']);
                $address->main = true;
                $address->save();
    
                Address::where('user_id', $user->id)->where('id', '!=' , $address->id)->update([
                    'main' => false
                ]);
            }
           
            if ($input['new_address']) {
                
                if ($input['main_new_address'] === 'да')  {
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
                $address->address = $input['new_address'];
                $address->main = $main_address;
                $address->save();
            
            }
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->save();
            return [$user->addresses];
    }
    public function updateAvatar(Request $request) {
        $user = User::find(auth()->id());
        $file = $request->file('file');
        if($file) {
            $ext = $file->getClientOriginalExtension();
            $file_name = time(). mt_rand(1000, 9999) . '.' . $ext;
            $file->storeAs('public/img/users', $file_name);
            
            $user->picture = $file_name;
            $user->save();
            }
    }
    public function del_address () 
    {
        $user = User::find(auth()->id());
        Address::find(request('address_id'))->delete();
        return [$user->addresses];
    }
    
}
