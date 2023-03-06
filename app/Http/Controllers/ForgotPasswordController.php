<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        // Form submit email
        return view('auth.forgetPassword');
    }

    // Olah email yg dikirim
    public function submitEmailForm(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:users',
        ]);
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgetPassword', ['token'=>$token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return "Email terkirim";
        
    }

    // Membuka form inputan password baru
    public function showResetPasswordForm($token)
    {
        return view('auth.resetPassword',
            ['token' => $token]
        );
    }

    // Mengirim data password baru ke database
    public function submitResetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
            'password' => ['required', Password::min(8)],
            'tokens'=>'required'
        ]);
        if ($validator->fails())
        {
            return $validator->errors();
        }


        $updatePassword = DB::table('password_resets')
                            ->where([
                                'email' => $request->email,
                                'token' => $request->tokens
                            ])->first();

        if (!$updatePassword) {
            // return back()->withInput()->with('error', 'Invalid token!');
            return "gagal, token salah";
        };

        $user = User::where('email', $request->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        // return "berhasil ganti password";
        return redirect()->route('loginView')->with('status', "Reset Password Succesfully");
        // return redirect('/login')->with('message', 'Your password has been changed!');
    }

};
