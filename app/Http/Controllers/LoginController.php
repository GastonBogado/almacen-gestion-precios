<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class LoginController extends Controller
{   

    public function showLoginForm()
    {
        return view('login');
    }
    
    public function login(Request $request){
     $credenciales = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required']
     ]);
     $recordarme = $request->boolean('remember');

    if (Auth::attempt($credenciales, $recordarme)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas']);
        
    }

    public function logout(Request $request): RedirectResponse
{
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return redirect('/login');
}

}
