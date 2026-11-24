<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{  
   

    public function dashboard()
    {
        $student = auth()->user()->student;
        return view('student.dashboard.index', compact('student'));
    }

}
