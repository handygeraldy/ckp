<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            "title" => "Login"
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $remember = $request->remember ? true : false;
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('index');
        } else {
            alert()->error('Login error', 'Periksa email dan password');
            return redirect(env("APP_URL") . 'login');
        }
        
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function gantiPassword()
    {
        return view('login.gantipassword', [
            "title" => "Ganti Password"
        ]);
    }

    public function postGantiPassword(Request $request)
    {
        $validatedData = $request->validate([
            'pass_lama' => 'min:6',
            'pass_baru' => 'min:6',
            'confirm_pass_baru' => 'min:6',
        ]);
        $credentials['email'] = auth()->user()->email;
        $credentials['password'] = $request->pass_lama;
        if (Auth::attempt($credentials)) {
            if ($validatedData['pass_baru'] == $validatedData['confirm_pass_baru']) {
                User::where('id', auth()->user()->id)
                    ->update([
                        'password' => bcrypt($validatedData['pass_baru']),
                    ]);
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                alert()->success('Berhasil ganti password', 'Silahkan login kembali');
                return redirect()->route('login');
            } else {
                alert()->error('Pastikan password baru dengan konfirmasi sesuai', '');
                return redirect(env("APP_URL") . 'gantipassword');
            }
        } else {
            alert()->error('Password lama salah', '');
            return redirect(env("APP_URL") . 'gantipassword');
        }
    }
}
