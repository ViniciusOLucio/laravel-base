<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest; // Importar o RegisterRequest
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Limpar caracteres não numéricos do CPF
        $cpf = preg_replace('/\D/', '', $request->cpf);

        // Verificar se o CPF já está cadastrado antes de criar o usuário
        if (User::where('cpf', $cpf)->exists()) {
            // Retornar o erro se o CPF já existe
            return back()->withErrors(['cpf' => 'Já existe um usuário cadastrado com esse CPF.'])->withInput();
        }

        // Criação do usuário após a validação bem-sucedida
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Disparar evento de usuário registrado
        event(new Registered($user));

        // Fazer login do usuário
        Auth::login($user);

        // Redirecionar para a página inicial ou dashboard
        return redirect(route('dashboard', absolute: false));
    }

}
