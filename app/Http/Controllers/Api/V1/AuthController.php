<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = Auth::getProvider()->retrieveByCredentials([$field => $credentials['login']]);

        if (! $user || ! Auth::validate([$field => $credentials['login'], 'password' => $credentials['password']])) {
            throw ValidationException::withMessages([
                'login' => ['Credenciais inválidas.'],
            ]);
        }

        abort_unless($user->is_active, 403, 'Usuário inativo.');

        $user->forceFill(['last_login_at' => now()])->save();
        $token = $user->createToken('mobile-app');

        return $this->success([
            'user' => $user->loadMissing(['company', 'partner', 'courier']),
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ], 'Login realizado com sucesso.');
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success(message: 'Logout realizado com sucesso.');
    }

    public function me(Request $request)
    {
        return $this->success(
            $request->user()?->loadMissing(['company', 'partner', 'courier']),
            'Usuário autenticado.'
        );
    }
}
