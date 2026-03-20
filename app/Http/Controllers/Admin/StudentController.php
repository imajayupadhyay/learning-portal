<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $query = User::where('role', 'student');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'is_approved' => (bool) $student->is_approved,
                'created_at' => $student->created_at->format('M d, Y'),
            ];
        });

        return Inertia::render('Admin/Students', [
            'students' => $students,
            'search' => $search,
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
            'role' => 'student',
            'password' => Hash::make($request->password),
            'is_approved' => true,
        ]);

        return back();
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $student->id,
            'password' => ['nullable', Password::min(8)],
        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $student->update(['password' => Hash::make($request->password)]);
        }

        return back();
    }

    public function approve(User $student)
    {
        if ($student->role !== 'student') {
            abort(403);
        }

        $student->update(['is_approved' => true]);

        return back();
    }

    public function reject(User $student)
    {
        if ($student->role !== 'student') {
            abort(403);
        }

        $student->update(['is_approved' => false]);

        return back();
    }

    public function destroy(User $student)
    {
        if ($student->role !== 'student') {
            abort(403);
        }

        $student->delete();

        return back();
    }
}
