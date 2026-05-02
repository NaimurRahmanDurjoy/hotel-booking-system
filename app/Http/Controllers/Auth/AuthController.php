<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('auth.register');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        if ($request->expectsJson()) {
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'message' => 'Registration successful', 
                'user' => $user,
                'token' => $token
            ], 201);
        }

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('auth.login');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            
            $user = Auth::user();

            if ($request->expectsJson()) {
                $token = $user->createToken('auth-token')->plainTextToken;
                
                $redirect = url('/');
                if ($user->isAdmin()) $redirect = route('admin.dashboard');
                elseif ($user->isManager()) $redirect = route('manager.dashboard');

                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token,
                    'redirect' => $redirect
                ]);
            }

            // Role-based redirection
            if ($user->isAdmin()) return redirect()->intended(route('admin.dashboard'));
            if ($user->isManager()) return redirect()->intended(route('manager.dashboard'));
            return redirect()->intended('/');
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect('/');
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function getApiToken(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json(['token' => $token]);
    }
}