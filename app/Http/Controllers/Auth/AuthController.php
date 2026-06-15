<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        return $this->redirectIfAuthenticated() ?? view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => User::ROLE_CUSTOMER,
            'is_admin' => false,
        ]);

        // Attach any prior guest bookings made with this email.
        $user->bookings()->getQuery()
            ->whereNull('user_id')
            ->where('email', $user->email)
            ->update(['user_id' => $user->id]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('account.dashboard')
            ->with('success', 'Welcome aboard, ' . $user->name . '! Your account is ready.');
    }

    public function showLogin()
    {
        return $this->redirectIfAuthenticated() ?? view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        // Staff are routed to the admin panel; customers to their account.
        if (Auth::user()->isStaff()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('account.dashboard'))
            ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /** Redirect already-authenticated users away from guest-only auth pages. */
    private function redirectIfAuthenticated()
    {
        if (Auth::check()) {
            return Auth::user()->isStaff()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('account.dashboard');
        }

        return null;
    }
}
