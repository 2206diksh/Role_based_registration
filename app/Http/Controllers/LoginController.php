<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Mail\NewLoginNotification;
use App\Mail\ApprovalRequestMail;
use App\Mail\ApprovalNotification;

class LoginController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('login');
    }

    // Show register form
    public function showRegister()
    {
        return view('register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required|in:user,admin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->role         = $request->role;
        $user->is_approved  = $request->role === 'admin' ? 1 : 0;
        $user->save();

        if ($user->role === 'user') {
            $adminEmail = 'dikshethasriss@gmail.com'; // or use env('ADMIN_EMAIL')
            Mail::to($adminEmail)->send(new ApprovalRequestMail($user));
        }

        return redirect()->route('login')->with('success', 'Registered successfully! Please login.');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_approved == 0 && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['Your account is pending admin approval.']);
            }

            // Notify admin about login
            Mail::to('dikshethasriss@gmail.com')->send(new NewLoginNotification($user));

            return $user->role === 'admin'
                ? redirect()->route('admin.dashboard')->with('message', 'You are logged in as admin!')
                : redirect()->route('user.dashboard')->with('message', 'You are logged in successfully!');
        }

        return back()->withErrors(['Invalid credentials.']);
    }

    // Admin dashboard
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $pendingApprovals = User::where('is_approved', 0)->get();
        return view('dashboard-admin', compact('totalUsers', 'pendingApprovals'));
    }

    // Approve user
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = 1;
        $user->save();

        $details = [
            'message' => 'Your account has been approved. You can now log in.',
            'login_url' => route('login'),
        ];

        Mail::to($user->email)->send(new ApprovalNotification($user, $details));

        return redirect()->route('admin.dashboard')->with('success', 'User approved and email sent!');
    }

    // User dashboard
    public function userDashboard()
    {
        return view('dashboard');
    }

    // Show forgot password form
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Show reset password form
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request('email')]);
    }

    // Handle password reset submission
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successful! Please login.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
