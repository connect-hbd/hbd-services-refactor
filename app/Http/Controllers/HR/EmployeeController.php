<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\StoreEmployeeRequest;
use App\Traits\HandlesFiles;
use App\Http\Requests\HR\UpdateEmployeeAddressRequest;
use App\Http\Requests\HR\UpdateEmployeeBankRequest;
use App\Http\Requests\HR\UpdateEmployeeDocumentsRequest;
use App\Http\Requests\HR\UpdateEmployeeEmploymentRequest;
use App\Http\Requests\HR\UpdateEmployeeInformationRequest;
use App\Http\Requests\HR\UpdateEmployeeRequest;
use App\Http\Requests\HR\UpdateEmployeeSalaryRequest;
use App\Http\Requests\HR\UpdateEmploymentStatusRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\hrm\BankAccount;
use App\Models\hrm\Branch;
use App\Models\hrm\Department;
use App\Models\hrm\Designation;
use App\Models\hrm\Employee;
use App\Models\hrm\Employment;
use App\Models\hrm\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class EmployeeController extends Controller
{
    use HandlesFiles;

    /**
     * List employees with search and pagination.
     */
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
                'employments' => fn($query) => $query
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

        return view('backend.pages.HR.employees.index', compact('employees'));
    }

    /**
     * Show the create form.
     */
    public function create(string $role, Request $request)
    {
        $request->user()->can('employee.create') || abort(403);

        return view('backend.pages.HR.employees.create', array_merge($this->formOptions()));
    }

    /**
     * Store user, employee profile and first employment record.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {

                // Create User
                $user = User::createEmployee(
                    $this->prepareUserData($validated)
                );

                // Create Employee
                $employee = Employee::create(
                    array_merge(
                        $this->prepareEmployeeData($validated),
                        ['user_id' => $user->id]
                    )
                );

                // Create Employment
                $employee->employments()->create(
                    $this->prepareEmploymentData($validated, true)
                );

                // Record the employee's initial salary from their joining date.
                $employee->salaryHistories()->create([
                    'salary_amount' => $validated['salary_amount'],
                    'effective_from' => $validated['joining_date'],
                ]);
            });

            return redirect()
                ->route('role.employees.index', [
                    'role' => $request->route('role'),
                ])
                ->with('success', 'Employee created successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to create employee. Please try again.');
        }
    }

    /**
     * Show the edit form.
     */
    public function edit(string $role, string $employee, Request $request)
    {
        $request->user()->can('employee.edit') || abort(403);

        $employee = Employee::query()
            ->with([
                'user',
                'country',
                'city',
                'currentEmployment.branch',
                'currentEmployment.department',
                'currentEmployment.team',
                'currentEmployment.designation',
                'documents',
                'primaryBankAccount',
            ])
            ->findOrFail($employee);

        // Move any legacy public-disk files into public/uploads so existing
        // "View File" links keep working without a storage symlink.
        $this->normalizeDocumentPaths($employee);
        $employee->load('documents');

        return view('backend.pages.HR.employees.edit', array_merge($this->formOptions(), [
            'employee' => $employee,
            'bankAccount' => $employee->primaryBankAccount ?? $employee->bankAccounts()->first(),
            'currentSalary' => $employee->salaryHistories()
                ->orderByDesc('effective_from')
                ->orderByDesc('id')
                ->first(),
        ]));
    }

    /**
     * Full update (resource route). The edit screen uses the focused
     * section endpoints below; this keeps the resource action functional.
     */
    public function update(string $role, UpdateEmployeeRequest $request, string $employee)
    {
        $employee = Employee::query()
            ->with('user', 'currentEmployment')
            ->findOrFail($employee);

        $validated = $request->validated();

        return $this->persist($role, $employee, function () use ($employee, $validated) {
            $this->syncPersonalInformation($employee, $validated);
            $this->syncEmployment($employee, $validated);
            $this->syncEmploymentStatus($employee, $validated);
            $this->syncSalary($employee, $validated);
        }, 'Employee updated successfully.');
    }

    /**
     * Delete employee profile.
     *
     * This preserves the associated user account, as in your original code.
     */
    public function destroy(Request $request, string $role, string $employee)
    {
        $request->user()->can('employee.delete') || abort(403);

        $employee = Employee::findOrFail($employee);

        $employee->delete();

        return redirect()
            ->route('role.employees.index', [
                'role' => $role,
            ])
            ->with('success', 'Employee deleted successfully.');
    }

    // ------------------------------------------------------------------
    // Partial update endpoints
    // ------------------------------------------------------------------

    public function updateInformation(string $role, UpdateEmployeeInformationRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        return $this->persist(
            $role,
            $employee,
            fn() => $this->syncPersonalInformation($employee, $validated),
            'Personal information updated successfully.'
        );
    }

    public function updateAddress(string $role, UpdateEmployeeAddressRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        return $this->persist($role, $employee, function () use ($employee, $validated) {
            $employee->update($this->nullifyBlanks(
                array_intersect_key($validated, array_flip(['country_id', 'city_id', 'address'])),
                ['country_id', 'city_id', 'address']
            ));
        }, 'Address updated successfully.');
    }

    public function updateEmploymentStatus(string $role, UpdateEmploymentStatusRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        return $this->persist(
            $role,
            $employee,
            fn() => $this->syncEmploymentStatus($employee, $validated),
            'Employment status updated successfully.'
        );
    }

    public function updateEmployment(string $role, UpdateEmployeeEmploymentRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        return $this->persist(
            $role,
            $employee,
            fn() => $this->syncEmployment($employee, $validated),
            'Employment information updated successfully.'
        );
    }

    public function updateSalary(string $role, UpdateEmployeeSalaryRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        return $this->persist(
            $role,
            $employee,
            fn() => $this->syncSalary($employee, $validated),
            'Salary updated successfully.'
        );
    }

    public function updateBank(string $role, UpdateEmployeeBankRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        return $this->persist(
            $role,
            $employee,
            fn() => $this->syncBankAccount($employee, $validated),
            'Bank account updated successfully.'
        );
    }

    public function updateDocuments(string $role, UpdateEmployeeDocumentsRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        $documents = $validated['documents'] ?? [];
        $uploadedBy = $request->user()->id;

        return $this->persist(
            $role,
            $employee,
            fn() => $this->syncDocuments($employee, $documents, $uploadedBy),
            'Documents updated successfully.'
        );
    }

    // ------------------------------------------------------------------
    // Write helpers
    // ------------------------------------------------------------------

    /**
     * Run a section write inside a transaction with uniform error handling.
     */
    private function persist(string $role, Employee $employee, callable $callback, string $successMessage)
    {
        $editUrl = $this->editUrl($role, $employee);

        try {
            DB::transaction($callback);
        } catch (Throwable $e) {
            report($e);

            return redirect($editUrl)
                ->withInput()
                ->with('error', 'Unable to save changes. Please try again.');
        }

        return redirect($editUrl)->with('success', $successMessage);
    }

    /**
     * Always redirect to the edit screen with the role prefix intact.
     * `back()` is unreliable here because browsers often omit the Referer
     * header for these PUT requests, which would send flashes to "/".
     */
    private function editUrl(string $role, Employee $employee): string
    {
        return route('role.employees.edit', [
            'role' => $role,
            'employee' => $employee->id,
        ]);
    }

    /**
     * Personal details (employees table) plus the linked user account.
     */
    private function syncPersonalInformation(Employee $employee, array $validated): void
    {
        $fields = [
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'religion',
            'blood_group',
            'national_id',
            'emergency_contact_name',
            'emergency_contact_phone',
        ];

        $employee->update($this->nullifyBlanks(
            array_intersect_key($validated, array_flip($fields)),
            array_slice($fields, 2)
        ));

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $user = $employee->user;

        // Employees created without an account get one on their first profile save.
        if (! $user) {
            $user = User::createEmployee([
                'name' => $fullName,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => filled($validated['password'] ?? null)
                    ? $validated['password']
                    : Str::password(16),
                'status' => 'active',
            ]);

            $employee->update(['user_id' => $user->id]);

            return;
        }

        $userData = [
            'name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (filled($validated['password'] ?? null)) {
            $userData['password'] = $validated['password'];
        }

        $user->update($userData);
    }

    /**
     * Status changes also clear dates that no longer apply to the new status.
     */
    private function syncEmploymentStatus(Employee $employee, array $validated): void
    {
        $employment = $this->currentEmploymentFor($employee);
        $status = $validated['employment_status'];

        $employment->update([
            'employment_status' => $status,
            'probation_period_months' => $status === 'probation'
                ? ($validated['probation_period_months'] ?? null) : null,
            'probation_end_date' => $status === 'probation'
                ? ($validated['probation_end_date'] ?? null) : null,
            'resignation_date' => $status === 'resigned'
                ? ($validated['resignation_date'] ?? null) : null,
            'last_working_day' => in_array($status, ['resigned', 'terminated'], true)
                ? ($validated['last_working_day'] ?? null) : null,
        ]);
    }

    /**
     * Branch / department / team / designation and other job details.
     */
    private function syncEmployment(Employee $employee, array $validated): void
    {
        $employment = $this->currentEmploymentFor($employee);

        $fields = [
            'employee_code',
            'branch_id',
            'department_id',
            'team_id',
            'designation_id',
            'work_type',
            'joining_date',
            'weekend_days',
        ];

        $data = $this->nullifyBlanks(
            array_intersect_key($validated, array_flip($fields)),
            ['branch_id', 'department_id', 'team_id', 'designation_id', 'weekend_days']
        );

        if (array_key_exists('weekend_days', $data)) {
            $data['weekend_days'] = $this->cleanArray($data['weekend_days'] ?? []);
        }

        $employment->update($data);
    }

    /**
     * Salary history is append-only: a new amount starts a new dated period.
     */
    private function syncSalary(Employee $employee, array $validated): void
    {
        $effectiveFrom = Carbon::parse(
            $validated['effective_from'] ?? $validated['joining_date'] ?? now()->toDateString()
        )->toDateString();

        $effectiveTo = ! empty($validated['effective_to'])
            ? Carbon::parse($validated['effective_to'])->toDateString()
            : null;

        $attributes = [
            'salary_amount' => $validated['salary_amount'],
            'currency_code' => strtoupper($validated['currency_code'] ?? 'BDT'),
            'salary_type' => $validated['salary_type'] ?? 'monthly',
            'effective_from' => $effectiveFrom,
            'effective_to' => $effectiveTo,
            'remarks' => $validated['remarks'] ?? null,
        ];

        // Re-saving the same effective date updates that record (idempotent).
        $record = $employee->salaryHistories()
            ->whereDate('effective_from', $effectiveFrom)
            ->first();

        if ($record) {
            $record->update($attributes);

            return;
        }

        // Close the previous open-ended period the day before this one starts.
        $employee->salaryHistories()
            ->whereNull('effective_to')
            ->whereDate('effective_from', '<', $effectiveFrom)
            ->update(['effective_to' => Carbon::parse($effectiveFrom)->subDay()->toDateString()]);

        // Without an explicit end, stop just before the next dated period.
        if ($attributes['effective_to'] === null) {
            $nextEffectiveFrom = $employee->salaryHistories()
                ->whereDate('effective_from', '>', $effectiveFrom)
                ->min('effective_from');

            if ($nextEffectiveFrom) {
                $attributes['effective_to'] = Carbon::parse($nextEffectiveFrom)->subDay()->toDateString();
            }
        }

        $employee->salaryHistories()->create($attributes);
    }

    /**
     * Create or update the selected account and keep the primary flag unique.
     */
    private function syncBankAccount(Employee $employee, array $validated): void
    {
        $accountId = $validated['bank_account_id'] ?? null;

        /** @var BankAccount|null $account */
        $account = $accountId
            ? $employee->bankAccounts()->find($accountId)
            : ($employee->primaryBankAccount ?: $employee->bankAccounts()->first());

        $attributes = [
            'bank_name' => $validated['bank_name'],
            'bank_branch' => $validated['bank_branch'] ?? null,
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'routing_number' => $validated['routing_number'] ?? null,
            'is_primary' => (bool) ($validated['is_primary'] ?? false),
        ];

        // The database enforces a single primary account per employee.
        if ($attributes['is_primary']) {
            $employee->bankAccounts()
                ->where('is_primary', true)
                ->when($account, fn($query) => $query->whereKeyNot($account->id))
                ->update(['is_primary' => false]);
        }

        if ($account) {
            $account->update($attributes);

            return;
        }

        $employee->bankAccounts()->create($attributes);
    }

    /**
     * One-time migration for documents uploaded before HandlesFiles was used.
     * Those files live in storage/app/public; copy them into public/uploads
     * and rewrite the stored path so asset() URLs resolve correctly.
     */
    private function normalizeDocumentPaths(Employee $employee): void
    {
        try {
            foreach ($employee->documents as $document) {
                $path = $document->file_path;

                if (! $path || str_starts_with($path, 'uploads/')) {
                    continue;
                }

                $relative = ltrim($path, '/');
                $legacy = storage_path('app/public/' . $relative);
                $target = public_path('uploads/' . $relative);

                if (! File::exists($legacy)) {
                    continue;
                }

                File::ensureDirectoryExists(dirname($target));
                File::copy($legacy, $target);

                $document->update(['file_path' => 'uploads/' . $relative]);
            }
        } catch (Throwable $e) {
            report($e);
        }
    }

    /**
     * Re-uploading a document key replaces the file and removes the old one.
     */
    private function syncDocuments(Employee $employee, array $documents, int $uploadedBy): void
    {
        foreach (['cv', 'nid'] as $key) {
            $file = $documents[$key] ?? null;

            if (! $file) {
                continue;
            }

            // Read the file attributes while the temporary upload still exists.
            // HandlesFiles::replaceFile() moves the temp file onto the public
            // disk, after which getMimeType()/getSize() can no longer read it.
            $attributes = [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => $uploadedBy,
            ];

            $document = $employee->documents()->firstOrNew(['document_key' => $key]);
            $oldPath = $document->exists ? $document->file_path : null;

            $path = $this->replaceFile(
                $file,
                $oldPath,
                'employees/' . $employee->id . '/documents'
            );

            $document->fill($attributes + ['file_path' => $path])->save();
        }
    }

    /**
     * Fetch the active employment, creating a safe fallback when missing.
     */
    private function currentEmploymentFor(Employee $employee): Employment
    {
        $employment = $employee->currentEmployment()->first();

        if ($employment) {
            return $employment;
        }

        do {
            $code = 'EMP-' . strtoupper(Str::random(6));
        } while ($employee->employments()->where('employee_code', $code)->exists());

        return $employee->employments()->create([
            'employee_code' => $code,
            'joining_date' => now()->toDateString(),
            'employment_status' => 'probation',
            'is_current' => true,
        ]);
    }

    /**
     * Load shared dropdown options for create and edit forms.
     */
    private function formOptions(): array
    {
        return [
            'branches' => Branch::query()
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'is_active']),

            'departments' => Department::query()
                ->orderBy('name')
                ->get(['id', 'name', 'branch_id']),

            'teams' => Team::query()
                ->orderBy('name')
                ->get(['id', 'name', 'department_id']),

            'designations' => Designation::query()
                ->orderBy('name')
                ->get(['id', 'name']),

            'countries' => Country::query()->orderBy('name')->get(),

            'cities' => City::query()->orderBy('name')->get(),
        ];
    }

    /**
     * Map only fields that belong to the employees table.
     */
    private function prepareEmployeeData(array $validated): array
    {
        $fields = [
            'first_name',
            'last_name',
            'emergency_contact_name',
            'emergency_contact_phone',
        ];

        return array_intersect_key($validated, array_flip($fields));
    }

    private function prepareUserData(array $validated): array
    {
        return [
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'status' => 'active',
        ];
    }

    /**
     * Map only fields that belong to the employments table.
     */
    private function prepareEmploymentData(array $validated, bool $creating = false): array
    {
        $fields = [
            'employee_code',
            'branch_id',
            'department_id',
            'team_id',
            'designation_id',
            'work_type',
            'joining_date',
            'weekend_days',
            'employment_status',
            'probation_period_months',
            'probation_end_date',
            'resignation_date',
            'last_working_day',
        ];

        $data = array_intersect_key($validated, array_flip($fields));

        if (array_key_exists('weekend_days', $data)) {
            $data['weekend_days'] = $this->cleanArray($data['weekend_days'] ?? []);
        }

        if ($creating) {
            $data['is_current'] = true;
        }

        return $data;
    }

    /**
     * Trim, remove blank items and reindex array fields.
     */
    private function cleanArray(array $items): array
    {
        return array_values(array_filter(
            array_map(static fn($item) => is_string($item) ? trim($item) : $item, $items),
            static fn($item) => filled($item)
        ));
    }

    /**
     * Normalize empty form values to null for nullable columns.
     */
    private function nullifyBlanks(array $data, array $keys): array
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $data) && ($data[$key] === '' || $data[$key] === null)) {
                $data[$key] = null;
            }
        }

        return $data;
    }

   public function employeeProfile(Request $request)
{
    $employee = Employee::with('user')
        ->where('user_id', $request->user()->id)
        ->firstOrFail();


    return view('backend.pages.HR.employees.profile', array_merge(
        $this->formOptions(),
        [
            'employee' => $employee,
        ]
    ));
}
}
