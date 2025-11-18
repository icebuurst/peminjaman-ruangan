<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('app');
    }

    public function login(Request $request)
    {
        \Log::info('Login attempt', [
            'email' => $request->email,
            'has_password' => !empty($request->password),
            'all_data' => $request->all()
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Credentials validated', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            \Log::info('Login successful', ['user_id' => Auth::id()]);
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => route('dashboard')
            ]);
        }

        \Log::warning('Login failed', ['email' => $credentials['email']]);
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'identity' => 'nullable|string|max:255',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            
            // Self-registration is ALWAYS peminjam role
            // Admin and Petugas must be created by admin via user management
            $validated['role'] = 'peminjam';
            
            // Set default identity if not provided
            if (empty($validated['identity'])) {
                $validated['identity'] = 'USER-' . time();
            }

            $user = User::create($validated);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'redirect' => route('dashboard')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar atau data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
