<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\Computer;
use App\Models\Task;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $teacherGroupIds = [];
        if ($user && $user->isTeacher()) {
            $teacherGroupIds = Group::where('teacher_id', $user->id)->pluck('id')->toArray();
        }
        
        if ($user && $user->isTeacher() && !empty($teacherGroupIds)) {
            $totalGroups = count($teacherGroupIds);
            $totalStudents = Student::whereIn('group_id', $teacherGroupIds)->count();
            $totalComputers = Computer::count();
            $damagedComputers = Computer::where('status', 'damaged')->count();
            
            $today = Carbon::today()->toDateString();
            $todayAttendances = Attendance::where('date', $today)->whereIn('group_id', $teacherGroupIds)->count();
            $presentToday = Attendance::where('date', $today)->where('status', 'present')->whereIn('group_id', $teacherGroupIds)->count();
            
            $pendingTasks = Task::whereIn('group_id', $teacherGroupIds)->where('due_date', '>=', $today)->count();
            
            $recentReports = \App\Models\ComputerReport::where('status', '!=', 'resolved')
                ->with('computer')
                ->orderBy('report_date', 'desc')
                ->limit(5)
                ->get();
        } else {
            $totalGroups = Group::count();
            $totalStudents = Student::count();
            $totalComputers = Computer::count();
            $damagedComputers = Computer::where('status', 'damaged')->count();
            
            $today = Carbon::today()->toDateString();
            $todayAttendances = Attendance::where('date', $today)->count();
            $presentToday = Attendance::where('date', $today)->where('status', 'present')->count();
            
            $pendingTasks = Task::where('due_date', '>=', $today)->count();
            
            $recentReports = \App\Models\ComputerReport::where('status', '!=', 'resolved')
                ->with('computer')
                ->orderBy('report_date', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('dashboard', compact(
            'totalGroups',
            'totalStudents',
            'totalComputers',
            'damagedComputers',
            'todayAttendances',
            'presentToday',
            'pendingTasks',
            'recentReports'
        ));
    }
}
