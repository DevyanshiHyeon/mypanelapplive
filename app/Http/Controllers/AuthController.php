<?php

namespace App\Http\Controllers;

use App\Mail\SendOtp;
use App\Models\LoginRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function getotp()
    {
        return view('auth.verifyotp');
    }
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect('apps'); // Redirect to the homepage or any other appropriate page
        }
        $ipAddress = $request->getClientIp();
        $loginRecord = DB::table('login_records')->where('ip_address', $ipAddress)->first();
                        if ($loginRecord) {
                            DB::table('login_records')->where('ip_address', $ipAddress)->increment('login_count');
                        } else {
                            DB::table('login_records')->insert([
                                'ip_address' => $ipAddress,
                            ]);
                        }
        $loginRecord = LoginRecord::where('ip_address', $ipAddress)->first();
        if($loginRecord->is_block !== 1){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            try {
                $user = User::where('email', '=', $request->email)->firstOrFail();
                $ipAddress = $request->getClientIp();
                // $unblock_ip = LoginRecord::where('ip_address', $ipAddress)->where('is_block', 'false')->exists();
                if (isset($user)) {
                    if (Hash::check($request->password, $user->password)) {
                        $otp = rand(1000, 9999);
                        $data = [
                            'OTP' => $otp
                        ];
                        $user->update(['last_OTP' => $otp]);
                        Mail::to('hyeonsoft46@gmail.com')->send(new SendOtp($data));
                        $data = [
                            'email' => $request->email,
                            'password' => $request->password,
                        ];
                        session()->flash('message', 'OTP sent in Mail');
                        return view('auth.verifyotp', compact('data'));
                        // return redirect('getotp')->with(['success'=> 'OTP sent in Mail','password'=>$request->password]);
                    } else {
                        return back()->withErrors([
                            'email' => 'The provided credentials do not match our records.',
                        ])->onlyInput('email');
                    }
                }
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->onlyInput('email');
            }
        }else{
            return back()->withErrors([
                'email' => 'You are Restricted to Login.',
            ]);
        }

    }
    public function verifyOtp(Request $request)
    {
        try {
            $user = User::where('email', '=', 'gmail.xyz@apps.managelive')->firstOrFail();
            if ($request->otp == $user->last_OTP) {
                if (isset($request->password)) {
                    if (Auth::attempt(['email' => $user->email, 'password' => $request->password, 'last_OTP' => $request->otp])) {
                        $ipAddress = $request->getClientIp();
                        $loginRecord = DB::table('login_records')->where('ip_address', $ipAddress)->first();
                        if ($loginRecord) {
                            // IP address already exists in the login_records table
                            DB::table('login_records')->where('ip_address', $ipAddress)->increment('login_count');
                        } else {
                            // IP address doesn't exist in the login_records table
                            DB::table('login_records')->insert([
                                'ip_address' => $ipAddress,
                            ]);
                        }
                        $request->session()->regenerate();
                        return redirect('apps');
                    } else {
                        return redirect('getotp')->with(['error' => 'Provided OTP is Expire. Login First']);
                    }
                } else {
                    return 'Somethis Went Wrong';
                }
            } else {
                return redirect('getotp')->with(['error' => 'please Check OTP', 'password' => $request->password]);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return 'Somethis Went Wrong';
        }
    }
    public function resend_otp(Request $request)
    {
        $user = User::where('email', '=', $request->email)->firstOrFail();
        if (isset($user)) {
            $otp = rand(1000, 9999);
            $data = [
                'OTP' => $otp
            ];
            $user->update(['last_OTP' => $otp]);
            Mail::to('hyeonsoft46@gmail.com')->send(new SendOtp($data));
            $data = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            // session()->flash('message', 'OTP sent in Mail');
            return response(['message' => 'OTP sent in Mail']);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
