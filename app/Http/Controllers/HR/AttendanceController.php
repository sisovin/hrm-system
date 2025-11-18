<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->employee) {
            return back()->with('error', 'No employee record found for this user.');
        }

        Attendance::create([
            'employee_id' => $user->employee->id,
            'check_in_at' => now(),
            'status' => 'present',
        ]);

        return back()->with('success', 'Checked in.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $employee = $user->employee;
        if (! $employee) {
            return back()->with('error', 'No employee record found for this user.');
        }

        // Find latest open attendance record for today
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereNull('check_out_at')
            ->whereDate('check_in_at', now()->toDateString())
            ->latest()
            ->first();

        if (! $attendance) {
            return back()->with('error', 'No open check-in found for today.');
        }

        $attendance->update(['check_out_at' => now()]);
        return back()->with('success', 'Checked out.');
    }
}
