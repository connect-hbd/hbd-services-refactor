<?php

namespace App\Http\Requests\HR;

use App\Models\hrm\Employee;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared base for the employee "partial update" requests.
 *
 * Centralises authorization and employee resolution so each section request
 * only has to declare the rules for the fields it owns.
 */
abstract class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAny([
            'employee.edit',
            'employee.update',
            'employee.manage',
        ]) ?? false;
    }

    /**
     * Validation failures must return to this employee's edit screen.
     * The framework default relies on the Referer header, which browsers
     * often omit for these PUT requests (sending the errors to "/").
     */
    protected function getRedirectUrl()
    {
        $employee = $this->route('employee');
        $employeeId = $employee instanceof Employee ? $employee->id : $employee;

        if (! $employeeId) {
            return parent::getRedirectUrl();
        }

        return route('role.employees.edit', [
            'role' => $this->route('role'),
            'employee' => $employeeId,
        ]);
    }

    /**
     * Resolve the employee from the route (implicit binding) or its id.
     */
    protected function employee(): Employee
    {
        $employee = $this->route('employee');

        return $employee instanceof Employee
            ? $employee
            : Employee::query()->findOrFail($employee);
    }
}
