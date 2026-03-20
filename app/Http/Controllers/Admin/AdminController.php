<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $admins = User::where('role', 'admin')->latest()->get()->map(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'created_at' => $admin->created_at->format('M d, Y'),
            ];
        });

        return Inertia::render('Admin/Admins', [
            'admins' => $admins,
            'currentAdminId' => $request->user()->id,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        return back();
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'password' => ['nullable', Password::min(8)],
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return back();
    }

    public function destroy(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(403);
        }

        if ($admin->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        $admin->delete();

        return back();
    }
}
