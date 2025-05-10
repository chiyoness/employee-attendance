<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users with filtering options.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->with('roles')->paginate(10);

        return view('users', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'job' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // Remove image from validated data if not provided
        if (!$request->hasFile('image')) {
            unset($validated['image']);
        } else {
            // Store the new image
            $imagePath = $request->file('image')->store('profile-images', 'public');
            $validated['image'] = $imagePath;
            
            // Delete the old image if it exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
        }

        $user->update($validated);

        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully');
    }
}