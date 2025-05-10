<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'job' => 'nullable|string|max:255',
            'role' => 'required|string|in:admin,employee',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile-images', 'public');
            $validated['image'] = $imagePath;
        }

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'job' => $validated['job'] ?? null,
            'image' => $validated['image'] ?? null,
        ]);

        // Assign the role
        $user->assignRole($validated['role']);

        return redirect()->route('users.show', $user)->with('success', 'User created successfully.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->getKey(),
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
            if ($user->getAttribute('image')) {
                Storage::disk('public')->delete($user->getAttribute('image'));
            }
        }

        $user->update($validated);

        return redirect()->route('users.show', $user->getKey())->with('success', 'User updated successfully');
    }    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Don't allow deleting your own account through this method
        if (auth()->id() === $user->getKey()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account through this method.');
        }

        // Delete user's profile image if exists
        if ($user->getAttribute('image')) {
            Storage::disk('public')->delete($user->getAttribute('image'));
        }

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}