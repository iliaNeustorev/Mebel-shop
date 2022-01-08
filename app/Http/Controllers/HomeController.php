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
                'picture' => 'image',
                'name' => 'max:255|alpha_num',
                'email' => 'email',
                'password' => 'confirmed',
            ]);


            $user = User::find(auth()->id());
            
            $file = $request->file('picture');
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

            $main_address = $input['flexRadioDefault'];
            $old = Address::where('main', true)->first();
                if (isset($old) && $old->address != $main_address) {
                    $old->main = false;
                    $old->save();
                }
                Address::where('address', $main_address)->update(['main' => true]);

            if ($input['new_address']) {

                $address = new Address();
                $address->user_id = $user->id;
                $address->address = $input['new_address'];
                $address->main = false;
                $address->save();
            }

            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->save();
            session()->flash('profileUpdated');
            return back();
    }

    public function del_address (Request $request) 
    {
        $address_id = $request->input('address_id');
        Address::find($address_id)->delete();
        return back();
    }
    

}
