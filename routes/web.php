<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\TeacherScheduleController;
use App\Http\Controllers\RecessController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register/{type}', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/{type}', [RegisterController::class, 'register'])->name('register');

// Password Reset Routes
Route::get('password/request', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    Route::resource('users', UserController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('students', StudentController::class);

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendances.report');

    Route::get('/computers', [ComputerController::class, 'index'])->name('computers.index');
    Route::get('/computers/create', [ComputerController::class, 'create'])->name('computers.create');
    Route::post('/computers', [ComputerController::class, 'store'])->name('computers.store');
    Route::get('/computers/{computer}', [ComputerController::class, 'show'])->name('computers.show');
    Route::get('/computers/{computer}/edit', [ComputerController::class, 'edit'])->name('computers.edit');
    Route::put('/computers/{computer}', [ComputerController::class, 'update'])->name('computers.update');
    Route::delete('/computers/{computer}', [ComputerController::class, 'destroy'])->name('computers.destroy');

    Route::get('/computers/reports', [ComputerController::class, 'reportIndex'])->name('computers.reports');
    Route::get('/computers/reports/create', [ComputerController::class, 'reportCreate'])->name('computers.reports.create');
    Route::post('/computers/reports', [ComputerController::class, 'reportStore'])->name('computers.reports.store');
    Route::post('/computers/reports/{report}/resolve', [ComputerController::class, 'reportResolve'])->name('computers.reports.resolve');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');
    Route::post('/tasks/submissions/{submission}/grade', [TaskController::class, 'grade'])->name('tasks.submissions.grade');

    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/tasks', [StudentDashboardController::class, 'tasks'])->name('student.tasks');
    Route::get('/student/attendance', [StudentDashboardController::class, 'attendance'])->name('student.attendance');
    Route::post('/student/tasks/{task}/submit', [StudentDashboardController::class, 'submitTask'])->name('student.tasks.submit');

    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('/schedules/fixed-teachers', [ScheduleController::class, 'fixedTeachers'])->name('schedules.fixed-teachers');
    Route::post('/schedules/fixed-teachers', [ScheduleController::class, 'storeFixedTeacher'])->name('schedules.fixed-teachers.store');
    Route::delete('/schedules/fixed-teachers/{fixedTeacher}', [ScheduleController::class, 'destroyFixedTeacher'])->name('schedules.fixed-teachers.destroy');
    Route::get('/schedules/print', [ScheduleController::class, 'printSchedule'])->name('schedules.print');
    Route::get('/schedules/group/{groupId}', [ScheduleController::class, 'getScheduleByGroup'])->name('schedules.group');
    
    // Time Slots
    Route::get('/schedules/time-slots', [TimeSlotController::class, 'index'])->name('schedules.time-slots');
    Route::post('/schedules/time-slots', [TimeSlotController::class, 'store'])->name('schedules.time-slots.store');
    Route::put('/schedules/time-slots/{timeSlot}', [TimeSlotController::class, 'update'])->name('schedules.time-slots.update');
    Route::delete('/schedules/time-slots/{timeSlot}', [TimeSlotController::class, 'destroy'])->name('schedules.time-slots.destroy');
    Route::post('/schedules/time-slots/init', [TimeSlotController::class, 'initializeDefaults'])->name('schedules.time-slots.init');
    
    // Teacher Schedule (individual teacher view)
    Route::get('/teacher/schedule', [TeacherScheduleController::class, 'index'])->name('teacher-schedules.index');
    Route::get('/teacher/schedule/print', [TeacherScheduleController::class, 'print'])->name('teacher-schedules.print');
    
    // Recesses (for schedule creation)
    Route::post('/schedules/recesses', [RecessController::class, 'store'])->name('schedules.recesses.store');
    Route::delete('/schedules/recesses/{recess}', [RecessController::class, 'destroy'])->name('schedules.recesses.destroy');
});
