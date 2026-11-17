<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\hrm\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeProfileController extends Controller
{
      public function index(Request $request)
    {
        $request->user()->can('employee.list') || abort(403);

        $employees = Employee::query()
            ->with([
                'user',
                'country',
                'city',
                'currentEmployment.branch',
                'currentEmployment.department',
                'currentEmployment.team',
                'currentEmployment.designation',
                'employments' => fn ($query) => $query
                    ->latest('id')
                    ->select(['id', 'employee_id', 'employment_status']),
            ])
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $search = trim($request->input('search'));

                $query->where(function (Builder $q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                            $userQuery->where('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('employments', function (Builder $employmentQuery) use ($search) {
                            $employmentQuery->where('employee_code', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // return $employees;

        return view('backend.pages.employees.index', compact('employees'));
    }
}