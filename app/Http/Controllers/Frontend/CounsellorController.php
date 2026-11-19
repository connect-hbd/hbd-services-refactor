<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Counsellor;
use App\Models\CounsellorBooking;
use App\Models\Country;
use App\Models\hrm\Employee;
use Illuminate\Http\Request;

class CounsellorController extends Controller
{

public function index()
{
    $counsellors = Employee::query()
        ->select([
            'id',
            'user_id',
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'religion',
            'blood_group',
            'national_id',
            'address',
            'bio',
            'education',
            'institution',
            'experience_years',
            'languages',
            'expertise',
            'work_destinations',
        ])
       ->with([
        'user:id,name,email,phone,avatar',
        'currentEmployment:employments.id,employments.employee_id,employments.designation_id,employments.is_current',
        'currentEmployment.designation:id,name',
    ])
        ->latest()
        ->paginate(12);
        // Collect all destination country IDs
    $countryIds = $counsellors->getCollection()
        ->pluck('work_destinations')
        ->flatten()
        ->filter()
        ->unique()
        ->values();

    // Get country names
    $countries = Country::whereIn('id', $countryIds)
        ->pluck('name', 'id');

    // Replace destination IDs with country names
    $counsellors->getCollection()->transform(function ($counsellor) use ($countries) {
        $counsellor->work_destinations = collect($counsellor->work_destinations ?? [])
            ->map(fn ($countryId) => $countries[$countryId] ?? null)
            ->filter()
            ->values()
            ->all();

        return $counsellor;
    });

    // return $counsellors;

    return view('frontend.pages.counsellors.index', compact('counsellors'));
}

public function show(Employee $counsellor)
{
    $counsellor->loadMissing([
        'user:id,name,email,phone,avatar',
        'currentEmployment.designation:id,name,code',
    ]);

    $counsellorData = $this->counsellorDetailsData($counsellor);

    if (request()->expectsJson()) {
        return response()->json($counsellorData);
    }

    return view('frontend.pages.counsellors.counsellor-details', [
        'counsellor' => $counsellorData,
        'counsellorRecord' => $counsellor,
    ]);
}

/** Build the public counsellor details payload used by the details page/API. */
private function counsellorDetailsData(Employee $employee): array
{
    $employment = $employee->currentEmployment;
    $destinationIds = collect($employee->work_destinations ?? [])->filter();
    $destinationNames = Country::query()
        ->whereIn('id', $destinationIds)
        ->pluck('name', 'id');

    return [
        'id' => $employee->id,
        'user_id' => $employee->user_id,
        'first_name' => $employee->first_name,
        'last_name' => $employee->last_name,
        'bio' => $employee->bio,
        'education' => $employee->education,
        'institution' => $employee->institution,
        'experience_years' => $employee->experience_years,
        'languages' => $employee->languages ?? [],
        'expertise' => $employee->expertise ?? [],
        'work_destinations' => $destinationIds
            ->map(fn ($destinationId) => $destinationNames[$destinationId] ?? $destinationId)
            ->values()
            ->all(),
        'user' => $employee->user ? [
            'id' => $employee->user->id,
            'name' => $employee->user->name,
            'email' => $employee->user->email,
            'phone' => $employee->user->phone,
            'avatar' => $employee->user->avatar,
        ] : null,
        'current_employment' => $employment ? [
            'designation_id' => $employment->designation_id,
            'designation' => $employment->designation ? [
                'id' => $employment->designation->id,
                'name' => $employment->designation->name,
                'code' => $employment->designation->code,
            ] : null,
        ] : null,
    ];
}

   /**
     * Store counsellor booking.
     */
    public function bookingCounsellor(Request $request, Employee $counsellor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mail' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'service' => ['required', 'string', 'max:100'],

            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'appointment_time' => [
                'required',
                'date_format:H:i',
            ],
        ]);

        // Create booking here

        CounsellorBooking::create([
            'counsellor_id' => $counsellor->id,
            'name' => $validated['name'],
            'email' => $validated['mail'],
            'phone' => $validated['phone'],
            'service' => $validated['service'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('counsellor.details', $counsellor)
            ->with('success', 'Your counselling session has been booked successfully.');
    }

    public function bookingAppointment(Counsellor $counsellor)
    {
        return view('frontend.pages.counselling.index');
    }

  
}
