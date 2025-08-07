<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Mail\ApprovalRequestMail;

class LoginController extends Controller
{
    // ------------------ Authentication ------------------

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_approved) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['Your account is not approved yet.']);
            }

            return $user->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return redirect()->back()->withErrors(['Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // ------------------ Registration ------------------

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'is_approved' => false,
        ]);

        // Notify admin (if needed)
        Mail::to('admin@example.com')->send(new ApprovalRequestMail($user));

        return redirect()->route('login')->with('message', 'Registration successful! Awaiting admin approval.');
    }

    // ------------------ Password Reset ------------------

    public function showForgotForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // ------------------ Dashboards ------------------

    public function adminDashboard()
    {
        $activeCount = User::where('is_approved', true)->count();
        $inactiveCount = User::where('is_approved', false)->count();

        return view('dashboard-admin', compact('activeCount', 'inactiveCount'));
    }

    public function userDashboard()
    {
        return view('dashboard');
    }

    // ------------------ Admin: User Management ------------------

    public function listUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('approved')) {
            $query->where('is_approved', $request->approved);
        }

        $users = $query->paginate(10);
        return view('list', compact('users'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return back()->with('success', 'User approved successfully.');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('show', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]));

        return redirect()->route('users.list')->with('success', 'User updated.');
    }

    public function confirmDeleteUser($id)
    {
        $user = User::findOrFail($id);
        return view('confirm-delete', compact('user'));
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.list')->with('success', 'User deleted.');
    }

    // ------------------ File Uploads ------------------

    public function showUploadForm()
    {
        return view('upload');
    }

    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $path = $request->file('file')->store('uploads');

        UploadedFile::create([
            'user_id' => Auth::id(),
            'original_name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function uploadedFiles()
    {
        $files = UploadedFile::paginate(10);
        return view('upload-list', compact('files'));
    }

    public function viewFile($id)
    {
        $file = UploadedFile::findOrFail($id);
        return response()->file(storage_path('app/' . $file->path));
    }

    public function deleteUploadedFile($id)
    {
        $file = UploadedFile::findOrFail($id);
        Storage::delete($file->path);
        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    // ------------------ AJAX Multiple File Upload ------------------

    public function ajaxUploadFiles(Request $request)
    {
        $files = $request->file('files');

        if (!$files || !is_array($files)) {
            return response()->json(['error' => 'No files uploaded'], 422);
        }

        $uploaded = [];

        foreach ($files as $file) {
            $path = $file->store('uploads');

            $record = UploadedFile::create([
                'user_id' => Auth::id(),
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
            ]);

            $uploaded[] = $record;
        }

        return response()->json(['success' => true, 'files' => $uploaded]);
    }

    public function showAjaxUploadForm()
    {
        return view('ajax-upload');
    }
}
