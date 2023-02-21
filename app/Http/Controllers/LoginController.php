<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $remember = true;
    //Tela inicial de login
    public function login(){

        //checka se user logado
        if (Auth::check()) {
            return redirect('/home');
        }

        return view('users.login');
    }
    //Autencticar Usuario
    public function authenticate(Request $request){

        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ],
        //mensagens:
        [
            'email.required' => 'Email é obrigatório!',
            'password.required' => 'Senha é obrigatória!'
        ]);

        // //Lembrar Usuario pelo cookie
        if (Auth::viaRemember()) {
            return redirect('/home')->with('message', 'Logado com sucesso');
        }

        //buscar usuario banco deixa passar se acha
        if(Auth::attempt($credentials,  $this->remember)){
            $request->session()->regenerate();

            return redirect('/home')->with('message', 'Logado com sucesso');
        }

        return back()->withErrors([
            'email' => 'As credenciais são inválidas',
        ])->onlyInput('email');
    }
    public function logout(Request $request){
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }
    //Tela de Admin
    // public function admin(){
        
    //     return view('livewire.admin.home-admin');
    // }
}
