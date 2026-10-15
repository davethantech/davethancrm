<?php
namespace App\\Http\\Controllers;
use App\\Models\\User;
use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\Auth;
use Illuminate\\Support\\Facades\\Hash;
class AuthController extends Controller {
    public function show() { return view('auth.login'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>'required|email','password'=>'required|string']);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) return back()->withErrors(['email'=>'The supplied credentials are invalid.'])->onlyInput('email');
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
    public function logout(Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('login'); }
    public function setupForm() { abort_if(User::query()->exists(), 404); return view('auth.setup'); }
    public function setup(Request $request) {
        abort_if(User::query()->exists(), 404);
        $data = $request->validate(['name'=>'required|string|max:120','email'=>'required|email|max:190|unique:users,email','password'=>'required|string|min:12|confirmed']);
        $user = User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password'])]);
        Auth::login($user); $request->session()->regenerate();
        return redirect()->route('dashboard');
    }
}