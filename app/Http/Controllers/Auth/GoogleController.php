<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;


class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
    
            $user = Socialite::driver('google')->user();
     
            $finduser = User::where('email', $user->email)->first();
     
            if($finduser){
     
                Auth::login($finduser);
    
                return redirect()->route('user.dashboard');
     
            }else{

                
                $newUser = User::create([
                    'username' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => bcrypt('123456'),
                    'status' => 1,
                    'ev' => 1
                ]);

                $newUser->plain_password = '123456';
                $newUser->save();
    
                Auth::login($newUser);
     
                return redirect()->route('user.dashboard');
            }
    
        } catch (Exception $e) {
           return redirect()->route('user.login')->with('error','Something Went Wrong');
        }
    }
}
