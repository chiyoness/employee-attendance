<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of active users.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeUsers()
    {
        $activeUsers = User::whereHas('attendance', function ($query) {
            $query->whereNull('check_out_time');
        })->get();

        return response()->view('active-users', compact('activeUsers'));
    }    /**
     * Record a check-in for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkIn(Request $request)
    {
        // Check if user already has an active check-in
        $activeAttendance = Attendance::where('user_id', Auth::id())
            ->whereNull('check_out_time')
            ->first();

        if ($activeAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You are already checked in.'
            ], 400);
        }

        // Create new attendance record
        Attendance::create([
            'user_id' => Auth::id(),
            'check_in_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful.'
        ]);
    }    /**
     * Record a check-out for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    public function checkOut(Request $request)
    {
        // Find the user's active check-in
        $activeAttendance = Attendance::where('user_id', Auth::id())
            ->whereNull('check_out_time')
            ->first();

        if (!$activeAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'No active check-in found.'
            ], 400);
        }

        // Update with check-out time
        $activeAttendance->update([
            'check_out_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful.'
        ]);
    }
      /**
     * Display attendance history for a specific user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function history(User $user)
    {
        // Check if the user is viewing their own history or is an admin
        if (Auth::id() !== $user->id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('attendances.history', compact('user', 'attendances'));
    }
}
