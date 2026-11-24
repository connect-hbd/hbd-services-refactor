<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Counsellor;
use App\Models\Country;
use App\Models\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class FrontendController extends Controller
{

  public function homePage()
{
    $events = Event::latest()->paginate(12);

    $destinationPath = resource_path('data/destinations.json');

    $destinationData = File::exists($destinationPath)
        ? json_decode(File::get($destinationPath), true)
        : [];

    $destinations = $destinationData['destinations'] ?? [];

    $destinations = array_filter($destinations, function ($destination) {
        return isset($destination['id'], $destination['name'], $destination['slug']);
    });

    $counsellors = Counsellor::query()
        ->with('user')
        ->where('is_active', true)
        ->latest()
        ->paginate(12);

    return view('frontend.pages.home.home', compact(
        'events',
        'destinations',
        'counsellors'
    ));
}

    public function aboutPage()
    {
        
        return view('frontend.pages.about.about');
    }

    public function contactPage()
    {
        return view('frontend.pages.contact');
    }
   
    public function registration()
    {
        return view('frontend.pages.register');
    }

      public function login()
    {
        return view('frontend.pages.login');
    }
    public function achieve(){
        return view('frontend.pages.achieve.achieve');
    }
     public function howWeWork(){
        return view('frontend.pages.how-we-works.index');
    }
    public function privacyPolicy(){
        return view('frontend.pages.privacy-policy');
    }
    public function termsConditions(){
        return view('frontend.pages.terms-conditions');
    }

    public function owner(){
        return view('frontend.pages.teams.owner');
    }

     public function digitalSolution(){
        return view('frontend.pages.digital-solutions.index');
    }

    //api form home hero page
     public function bookAppointment(){
         $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([ 'id','name']);
        // return $countries;
        return view('frontend.pages.api-forms.home-hero', compact('countries'));
    }

    
public function appointmentStore(Request $request)
{
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'phone' => 'required|string|max:30',
        'email' => 'required|email|max:150',
        'full_address' => 'required|string|max:255',

        'aq_exam_name' => 'required|string|max:100',
        'aq_college_name' => 'required|string|max:255',

        'is_attend_english_proficiency_test' =>
            'required|in:yes,will_sit',

        'ep_exam_name' =>
            'required_if:is_attend_english_proficiency_test,yes|nullable|string',

        'ep_overall' =>
            'required_if:is_attend_english_proficiency_test,yes|nullable|numeric|min:0|max:160',

        'ep_tentative_date' =>
            'required_if:is_attend_english_proficiency_test,will_sit|nullable|date|after_or_equal:today',

        'study_destinations' => 'nullable|array',

        'consent' => 'accepted',
    ]);

    $validator->after(function ($validator) use ($request) {
        $digits = preg_replace('/\D/', '', $request->phone ?? '');

        if (strlen($digits) < 11) {
            $validator->errors()->add(
                'phone',
                'Please enter a valid phone number with at least 11 digits.'
            );
        }
    });

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422);
    }

    // Academic qualifications
    $academicQualifications = [
        'exam_name' => $request->aq_exam_name,
        'institution_name' => $request->aq_college_name,
    ];

    // English proficiency
    $englishProficiency = [];

    if ($request->is_attend_english_proficiency_test === 'yes') {
        $englishProficiency = [
            'exam_name' => $request->ep_exam_name,
            'overall' => $request->ep_overall,
        ];
    }

    // API Payload
    $payload = [
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'phone' => $request->phone,
        'email' => $request->email,
        'full_address' => $request->full_address,

        'academicQualifications' => [
            $academicQualifications
        ],

        'english_proficiency' => $englishProficiency
            ? [$englishProficiency]
            : [],

        'is_attend_english_proficiency_test' =>
            $request->is_attend_english_proficiency_test,

        'tentative_exam_date' =>
            $request->ep_tentative_date,

        'insert_from' => 'Event Registration Form',

        'sources' => ['4'],
        'tags' => ['2'],
        'status' => 'Inactive',
    ];

    // Call external API
    try {

        $apiRequest = Http::acceptJson()
            ->asJson()
            ->timeout(30);

        $caBundle = config('services.lead_api.ca_bundle');
        if ($caBundle) {
            $apiRequest = $apiRequest->withOptions(['verify' => $caBundle]);
        }

        $response = $apiRequest->post(config('services.lead_api.url'), $payload);

        if (!$response->successful()) {

            Log::error('Lead API failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit registration. Please try again!',
            ], 502);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your registration has been submitted successfully!',
            'data' => $response->json(),
        ]);

    } catch (\Illuminate\Http\Client\ConnectionException $e) {

        Log::error('Lead API connection failed', [
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Unable to connect to the server. Please try again!',
        ], 502);

    } catch (\Throwable $e) {

        Log::error('Lead API error', [
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again!',
        ], 500);
    }
}

//  public function checkIn()
//     {
//         return view('backend.pages.auth.check-in');
//     }

}
