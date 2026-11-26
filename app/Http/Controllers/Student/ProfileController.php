<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentReferralRequest;
use App\Http\Requests\Student\UpdateAcademicInformationRequest;
use App\Http\Requests\Student\UpdateImmigrationHistoryRequest;
use App\Http\Requests\Student\UpdatePersonalInformationRequest;
use App\Http\Requests\Student\UpdateStudyPlanRequest;
use App\Http\Requests\Student\UpdateWorkExperienceRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\student\StudentEmail;
use App\Models\student\StudentEnglishProficiency;
use App\Models\student\StudentPhone;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\HandlesFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use HandlesFiles;

    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    // =========================================================================
    // Basic Profile (avatar / password)
    // =========================================================================

    public function profile(Request $request)
    {
        return view('student.index');
    }

    public function accountSettings(Request $request)
    {
        $user = $request->user();

        return view('student.settings', compact('user'));
    }

    public function accountSettingUpdate(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'avatar'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'current_password' => ['required_with:password'],
            'password'         => ['nullable', 'min:8', 'confirmed'],
        ]);

        $data = [];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->replaceFile(
                $request->file('avatar'),
                $user->avatar,
                'users'
            );
        }

        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $data['password'] = bcrypt($request->password);
        }

        if (! empty($data)) {
            $user->update($data);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    // =========================================================================
    // Study abroad wizard
    // =========================================================================

    public function studyAbroad(Request $request)
    {
        $user    = $request->user();
        $student = $user->student;

        abort_if(! $student, 404, 'Student profile not found.');

        // ── Step 1 data ──────────────────────────────────────────────────────
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $cities = City::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'country_id']);

        $currentAddress   = $student->addresses()->where('type', 'current')->first();
        $permanentAddress = $student->addresses()->where('type', 'permanent')->first();

        $sameAddress = $currentAddress
            && $permanentAddress
            && $currentAddress->address    === $permanentAddress->address
            && $currentAddress->city_id    === $permanentAddress->city_id
            && $currentAddress->country_id === $permanentAddress->country_id
            && $currentAddress->post_code  === $permanentAddress->post_code;

        // ── Step 2 data ──────────────────────────────────────────────────────
        $academicInformation = $student->academicInformation;

        // ── Step 3 data ──────────────────────────────────────────────────────
        $studyPlan = $student->studyPlan()->with('choices')->first();

        // ── Step 4 data ──────────────────────────────────────────────────────
        $workExperiences = $student->workExperiences()->get();

        // ── Step 5 data ──────────────────────────────────────────────────────
        $immigrationRefusals = $student->immigrationRefusals()->get();

        // ── Step 6 data ──────────────────────────────────────────────────────
        $referral = $student->referral;

        $data = [
            'referral_id'   => $referral?->id,
            'check_student' => $referral
                ? (object) [
                    'known_by'         => $referral->known_by,
                    'known_by_details' => $referral->known_by_details,
                ]
                : null,
        ];

        // ── Active step (driven by the query string after each save) ─────────
        $activeStep = (int) $request->query('step', $student->profile_step ?? 1);
        $activeStep = max(1, min(6, $activeStep));

        return view('student.edit', compact(
            'student',
            'countries',
            'cities',
            'currentAddress',
            'permanentAddress',
            'sameAddress',
            'academicInformation',
            'studyPlan',
            'workExperiences',
            'immigrationRefusals',
            'referral',
            'data',
            'activeStep',
        ));
    }

    public function englishProficiency(Request $request)
    {
        return view('student.hla');
    }

    public function dashboard()
    {
        $student = auth()->user()->student;

        return view('student.dashboard', compact('student'));
    }

    // =========================================================================
    // Step 1 — Personal Information
    // =========================================================================

    public function updatePersonalInformation(
        UpdatePersonalInformationRequest $request
    ): RedirectResponse {
        $student = $request->user()->student;
        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validated();
        $sameAddress = (bool) ($validated['same_address'] ?? false);

        DB::transaction(function () use ($student, $request, $validated, $sameAddress) {
            // User account fields (only when actually submitted / changed).
            $userChanges = $this->changedOnly(
                $request->user(),
                Arr::only($validated, ['name', 'phone'])
            );
            if ($userChanges !== []) {
                $request->user()->update($userChanges);
            }

            // Student fields.
            $studentChanges = $this->changedOnly($student, Arr::only($validated, [
                'date_of_birth',
                'gender',
                'marital_status',
                'passport_number',
                'passport_issue_date',
                'passport_expiry_date',
            ]));
            if ($studentChanges !== []) {
                $student->update($studentChanges);
            }

            $currentAddress = $student->addresses()->where('type', 'current')->first();

            $currentData = $this->mapOnlyPresent($validated, [
                'current_address'    => 'address',
                'current_city_id'    => 'city_id',
                'current_country_id' => 'country_id',
                'current_post_code'  => 'post_code',
            ]);
            $this->syncAddress($student, 'current', $currentData);

            if ($sameAddress) {
                // Mirror the current address without ever clearing a stored
                // value that was not part of this request.
                $permanentData = [];
                foreach (['address', 'city_id', 'country_id', 'post_code'] as $field) {
                    if (array_key_exists($field, $currentData)) {
                        $permanentData[$field] = $currentData[$field];
                    } elseif ($currentAddress) {
                        $permanentData[$field] = $currentAddress->{$field};
                    }
                }
            } else {
                $permanentData = $this->mapOnlyPresent($validated, [
                    'permanent_address'    => 'address',
                    'permanent_city_id'    => 'city_id',
                    'permanent_country_id' => 'country_id',
                    'permanent_post_code'  => 'post_code',
                ]);
            }
            $this->syncAddress($student, 'permanent', $permanentData);

            if (array_key_exists('alt_phone', $validated)) {
                $this->syncAlternativePhones($student, $validated['alt_phone'] ?? []);
            }

            if (array_key_exists('alt_email', $validated)) {
                $this->syncAlternativeEmails($student, $validated['alt_email'] ?? []);
            }

            if (array_key_exists('destination_id', $validated)) {
                $this->syncDestinations($student, $validated['destination_id'] ?? []);
            }
        });

        $student->advanceStepTo(1);

        return redirect()->route('student.study-abroad', ['step' => 2]);
    }

    // =========================================================================
    // Step 2 — Academic Information (+ English Proficiency)
    // =========================================================================

    public function updateAcademicInformation(
        UpdateAcademicInformationRequest $request
    ): RedirectResponse {
        $student = $request->user()->student;
        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validated();

        DB::transaction(function () use ($student, $validated) {
            $academic = $student->academicInformation()
                ->firstOrNew(['student_id' => $student->id]);

            $changes = $this->changedOnly($academic, Arr::only($validated, [
                'highest_degree',
                'highest_institution',
                'highest_grad_date',
                'highest_gpa',
                'highest_max_gpa',
                'education_history',
            ]));

            if ($changes !== [] || ! $academic->exists) {
                $academic->fill($changes)->save();
            }

            if (! array_key_exists('status', $validated)) {
                return;
            }

            $proficiency = $student->englishProficiency()
                ->firstOrNew(['student_id' => $student->id]);

            $proficiencyData = ['status' => $validated['status']];
            if (array_key_exists('planned_exam_name', $validated)) {
                $proficiencyData['exam_name'] = $validated['planned_exam_name'];
            }
            if (array_key_exists('exam_date', $validated)) {
                $proficiencyData['exam_date'] = $validated['exam_date'];
            }

            $proficiencyChanges = $this->changedOnly($proficiency, $proficiencyData);
            if ($proficiencyChanges !== [] || ! $proficiency->exists) {
                $proficiency->fill($proficiencyData)->save();
            }

            if ($validated['status'] === 'yes' && array_key_exists('exams', $validated)) {
                $this->syncEnglishExams($proficiency, $validated['exams'] ?? []);
            }
        });

        $student->advanceStepTo(2);

        return redirect()->route('student.study-abroad', ['step' => 3]);
    }

    // =========================================================================
    // Step 3 — Study Plan
    // =========================================================================

    public function updateStudyPlan(
        UpdateStudyPlanRequest $request
    ): RedirectResponse {
        $student = $request->user()->student;
        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validated();

        DB::transaction(function () use ($student, $validated) {
            $plan = $student->studyPlan()
                ->firstOrNew(['student_id' => $student->id]);

            $degreeChanges = $this->changedOnly(
                $plan,
                Arr::only($validated, ['target_degree'])
            );
            if ($degreeChanges !== [] || ! $plan->exists) {
                $plan->fill($degreeChanges)->save();
            }

            if (! array_key_exists('choices', $validated)) {
                return;
            }

            $choices = $validated['choices'] ?? [];
            $existing = $plan->choices()->orderBy('sort_order')->get();

            foreach ($choices as $index => $choice) {
                $data = [
                    'sort_order' => $index,
                    'program'    => $choice['program'],
                    'country'    => $choice['country'],
                    'start_date' => $choice['start_date'],
                    'summary'    => $choice['summary'] ?? null,
                ];

                $model = $existing->get($index);
                if ($model) {
                    $changes = $this->changedOnly($model, $data);
                    if ($changes !== []) {
                        $model->update($changes);
                    }
                } else {
                    $plan->choices()->create($data);
                }
            }

            // Only remove rows the user actually deleted.
            $existing->slice(count($choices))->each->delete();
        });

        $student->advanceStepTo(3);

        return redirect()->route('student.study-abroad', ['step' => 4]);
    }

    // =========================================================================
    // Step 4 — Work Experience
    // =========================================================================

    public function updateWorkExperience(
        UpdateWorkExperienceRequest $request
    ): RedirectResponse {
        $student = $request->user()->student;
        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validated();

        DB::transaction(function () use ($student, $validated) {
            if (($validated['status'] ?? null) === 'no') {
                // An explicit "No work experience" answer clears the entries.
                $student->workExperiences()->delete();

                return;
            }

            if (! array_key_exists('roles', $validated)) {
                return;
            }

            $roles = $validated['roles'] ?? [];
            $existing = $student->workExperiences()->orderBy('sort_order')->get();

            foreach ($roles as $index => $role) {
                $isCurrent = (bool) ($role['is_current'] ?? false);

                $data = [
                    'status'       => 'yes',
                    'sort_order'   => $index,
                    'title'        => $role['title'],
                    'organization' => $role['organization'],
                    'start_date'   => $role['start_date'],
                    'end_date'     => $isCurrent ? null : ($role['end_date'] ?? null),
                    'is_current'   => $isCurrent,
                    'description'  => $role['description'] ?? null,
                ];

                $model = $existing->get($index);
                if ($model) {
                    $changes = $this->changedOnly($model, $data);
                    if ($changes !== []) {
                        $model->update($changes);
                    }
                } else {
                    $student->workExperiences()->create($data);
                }
            }

            $existing->slice(count($roles))->each->delete();
        });

        $student->advanceStepTo(4);

        return redirect()->route('student.study-abroad', ['step' => 5]);
    }

    // =========================================================================
    // Step 5 — Immigration History
    // =========================================================================

    public function updateImmigrationHistory(
        UpdateImmigrationHistoryRequest $request
    ): RedirectResponse {
        $student = $request->user()->student;
        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validated();

        DB::transaction(function () use ($student, $request, $validated) {
            $existing = $student->immigrationRefusals()->orderBy('sort_order')->get();

            if (($validated['status'] ?? null) === 'no') {
                // An explicit "No visa refusal" answer removes the records
                // and their uploaded letters.
                foreach ($existing as $refusal) {
                    $this->deleteFile($refusal->document_path);
                    $refusal->delete();
                }

                return;
            }

            if (! array_key_exists('refusals', $validated)) {
                return;
            }

            $refusals = $validated['refusals'] ?? [];
            $byId = $existing->keyBy('id');
            $keptIds = [];

            foreach ($refusals as $index => $refusal) {
                $record = null;

                if (! empty($refusal['id']) && $byId->has((int) $refusal['id'])) {
                    $record = $byId->get((int) $refusal['id']);
                } else {
                    $record = $existing->first(
                        fn ($item) => (int) $item->sort_order === $index
                            && ! in_array($item->id, $keptIds, true)
                    );
                }

                $data = [
                    'status'      => 'yes',
                    'sort_order'  => $refusal['sort_order'] ?? $index,
                    'country'     => $refusal['country'],
                    'refusal_date'=> $refusal['refusal_date'] ?? null,
                    'grounds'     => $refusal['grounds'] ?? null,
                ];

                if ($request->hasFile("refusals.{$index}.document")) {
                    $file = $request->file("refusals.{$index}.document");
                    $directory = "students/{$student->id}/immigration";

                    $data['document_path'] = $record?->document_path
                        ? $this->replaceFile($file, $record->document_path, $directory)
                        : $this->uploadFile($file, $directory);
                    $data['document_name'] = $file->getClientOriginalName();
                }

                if ($record) {
                    $changes = $this->changedOnly($record, $data);
                    if ($changes !== []) {
                        $record->update($changes);
                    }
                    $keptIds[] = $record->id;
                } else {
                    $created = $student->immigrationRefusals()->create($data);
                    $keptIds[] = $created->id;
                }
            }

            foreach ($existing as $refusal) {
                if (! in_array($refusal->id, $keptIds, true)) {
                    $this->deleteFile($refusal->document_path);
                    $refusal->delete();
                }
            }
        });

        $student->advanceStepTo(5);

        return redirect()->route('student.study-abroad', ['step' => 6]);
    }

    // =========================================================================
    // Step 6 — Referral (final submit)
    // =========================================================================

    public function updateReferralHistory(StudentReferralRequest $request): RedirectResponse
    {
        $student = $request->user()->student;
        abort_if(! $student, 404, 'Student profile not found.');

        $validated = $request->validated();

        $data = Arr::only($validated, ['known_by', 'known_by_details', 'employee', 'expo']);
        $data['permission'] = in_array(
            strtolower((string) ($validated['permission'] ?? '')),
            ['1', 'true', 'granted', 'yes', 'on'],
            true
        );

        $referral = $student->referral()->firstOrNew(['student_id' => $student->id]);
        $changes = $this->changedOnly($referral, $data);

        if ($changes !== [] || ! $referral->exists) {
            $referral->fill($data)->save();
        }

        $student->advanceStepTo(6);

        // Only the final step flashes a message and leaves the wizard.
        return redirect()
            ->route('student.student-dashboard')
            ->with('success', 'Your information saved successfully.');
    }

    // =========================================================================
    // Helpers — update only what actually changed
    // =========================================================================

    /**
     * Keep only attributes whose value differs from the persisted one.
     * Missing keys are never added, so untouched fields cannot be wiped.
     */
    private function changedOnly(Model $model, array $attributes): array
    {
        $changed = [];

        foreach ($attributes as $key => $value) {
            $current = $model->getAttribute($key);

            if ($current instanceof \Carbon\CarbonInterface) {
                $current = $current->toDateString();
            }

            if (is_array($current) || is_array($value)) {
                $isSame = $current == $value;
            } elseif (is_numeric($current) && is_numeric($value) && $current !== '' && $value !== '') {
                $isSame = (float) $current === (float) $value;
            } else {
                $isSame = (string) ($current ?? '') === (string) ($value ?? '');
            }

            if (! $isSame) {
                $changed[$key] = $value;
            }
        }

        return $changed;
    }

    /**
     * Map only the request keys that are actually present.
     */
    private function mapOnlyPresent(array $source, array $map): array
    {
        $result = [];

        foreach ($map as $input => $output) {
            if (array_key_exists($input, $source)) {
                $result[$output] = $source[$input];
            }
        }

        return $result;
    }

    private function syncAddress($student, string $type, array $data): void
    {
        if ($data === []) {
            return;
        }

        $address = $student->addresses()->firstOrNew(['type' => $type]);

        if (! $address->exists) {
            $address->fill($data)->save();

            return;
        }

        $changes = $this->changedOnly($address, $data);
        if ($changes !== []) {
            $address->update($changes);
        }
    }

    private function syncAlternativePhones($student, array $phones): void
    {
        $existing = $student->phones()->orderBy('sort_order')->get()->keyBy('sort_order');

        foreach ($phones as $slot => $entry) {
            $value = $entry['value'] ?? null;
            $record = $existing->get($slot);

            if (blank($value)) {
                // The final UI does not prefill these inputs. An empty value
                // therefore means "not edited", not "delete".
                continue;
            }

            // Respect the global unique constraint without exposing another
            // student's phone number.
            $conflict = StudentPhone::where('phone', $value)
                ->where('student_id', '!=', $student->id)
                ->exists();
            if ($conflict) {
                continue;
            }

            $data = [
                'phone'       => $value,
                // An unchecked checkbox is not submitted at all, so presence
                // means the user ticked it (regardless of its HTML value).
                'is_whatsapp' => array_key_exists('is_whatsapp', $entry),
                'sort_order'  => $slot,
            ];

            if ($record) {
                $changes = $this->changedOnly($record, $data);
                if ($changes !== []) {
                    $record->update($changes);
                }
            } else {
                $student->phones()->create($data);
            }
        }
    }

    private function syncAlternativeEmails($student, array $emails): void
    {
        $existing = $student->emails()->orderBy('sort_order')->get()->keyBy('sort_order');

        foreach ($emails as $slot => $value) {
            $record = $existing->get($slot);

            if (blank($value)) {
                // See syncAlternativePhones(): never wipe an unedited value.
                continue;
            }

            $conflict = StudentEmail::where('email', $value)
                ->where('student_id', '!=', $student->id)
                ->exists();
            if ($conflict) {
                continue;
            }

            $data = ['email' => $value, 'sort_order' => $slot];

            if ($record) {
                $changes = $this->changedOnly($record, $data);
                if ($changes !== []) {
                    $record->update($changes);
                }
            } else {
                $student->emails()->create($data);
            }
        }
    }

    private function syncDestinations($student, array $ids): void
    {
        if ($ids === [] && $student->destinations()->exists()) {
            // The multi-select is not pre-populated by the approved UI, so an
            // empty submission means "not edited" rather than "clear all".
            return;
        }

        $resolved = [];

        foreach ($ids as $value) {
            if (blank($value)) {
                continue;
            }

            // The UI currently uses fixed option ids; accept either a real
            // country id or a country name and skip anything unresolvable.
            $country = Country::query()->find($value)
                ?? Country::query()->where('name', $value)->first();

            if ($country) {
                $resolved[] = $country->id;
            }
        }

        $student->destinations()->sync(array_values(array_unique($resolved)));
    }

    private function syncEnglishExams(StudentEnglishProficiency $proficiency, array $exams): void
    {
        $existing = $proficiency->exams()->orderBy('id')->get();

        foreach ($exams as $index => $exam) {
            $scores = $exam['scores'] ?? [];

            $data = [
                'exam_name'        => $exam['exam_name'] ?? null,
                'year'             => $exam['year'] ?? null,
                'overall_score'    => $scores['overall'] ?? $scores['total'] ?? null,
                'listening_score'  => $scores['listening'] ?? null,
                'reading_score'    => $scores['reading'] ?? null,
                'writing_score'    => $scores['writing'] ?? null,
                'speaking_score'   => $scores['speaking'] ?? null,
                'scores'           => $scores,
            ];

            $model = $existing->get($index);
            if ($model) {
                $changes = $this->changedOnly($model, $data);
                if ($changes !== []) {
                    $model->update($changes);
                }
            } else {
                $proficiency->exams()->create($data);
            }
        }

        $existing->slice(count($exams))->each->delete();
    }
}
