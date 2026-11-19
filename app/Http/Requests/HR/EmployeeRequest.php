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
    /**
     * Managers (holding the employee.* permissions) may edit any employee.
     * An employee may only edit their own record.
     */
    public function authorize(): bool
    {
        return $this->canManageEmployees() || $this->isSelfService();
    }

    protected function canManageEmployees(): bool
    {
        return $this->user()?->canAny([
            'employee.edit',
            'employee.update',
            'employee.manage',
        ]) ?? false;
    }

    /**
     * True when the authenticated user owns the employee being edited.
     */
    protected function isSelfService(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        return $this->employee()->user_id === $user->id;
    }

    /**
     * Validation failures must return to the screen the form was submitted
     * from. The framework default relies on the Referer header, which browsers
     * often omit for these PUT requests (sending the errors to "/").
     */
    protected function getRedirectUrl()
    {
        $employee = $this->route('employee');
        $employeeId = $employee instanceof Employee ? $employee->id : $employee;

        if (! $employeeId) {
            return parent::getRedirectUrl();
        }

        return $this->redirectUrlFor($this->route('role'), $employeeId);
    }

    /**
     * Managers return to the admin edit screen; employees to their profile.
     */
    protected function redirectUrlFor(string $role, int|string $employeeId): string
    {
        if ($this->canManageEmployees()) {
            return route('role.employees.edit', [
                'role' => $role,
                'employee' => $employeeId,
            ]);
        }

        return route('role.employee.profile', ['role' => $role]);
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
