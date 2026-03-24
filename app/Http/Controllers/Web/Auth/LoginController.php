<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (! Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password'], 'is_active' => true], $request->boolean('remember'))) {
            return back()
                ->withErrors(['login' => 'Credenciais inválidas.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        $request->user()->update([
            'last_login_at' => now(),
        ]);

        $destination = match ($request->user()->role->value) {
            'admin' => route('admin.dashboard'),
            'partner' => route('partner.portal'),
            default => route('login'),
        };

        return redirect()->intended($destination);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
