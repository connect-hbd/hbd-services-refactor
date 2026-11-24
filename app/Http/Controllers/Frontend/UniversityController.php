<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUniversityRequest;
use App\Http\Requests\UpdateUniversityRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\University;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UniversityController extends Controller
{
    public function __construct(private readonly UniversityRepositoryInterface $universities) {}

   public function index(Request $request)
{
    $jsonPath = resource_path('data/destination-details.json');

    if (!file_exists($jsonPath)) {
        abort(404, 'Destination JSON file not found.');
    }

    $jsonData = json_decode(file_get_contents($jsonPath), true);

    $destinations = collect($jsonData['destinations'] ?? []);

    // Get all universities with original JSON fields only
    $universities = $destinations->flatMap(function ($destination) {
        return $destination['universities'] ?? [];
    })->values();

    // Search
    if ($request->filled('search')) {
        $search = strtolower(trim($request->search));

        $universities = $universities->filter(function ($university) use ($search) {
            return str_contains(strtolower($university['name'] ?? ''), $search)
                || str_contains(strtolower($university['short_name'] ?? ''), $search)
                || str_contains(strtolower($university['city'] ?? ''), $search)
                || str_contains(strtolower($university['description'] ?? ''), $search);
        })->values();
    }

    // Country filter
    if ($request->filled('country')) {
        $country = $destinations->firstWhere('slug', $request->country);

        $universities = $universities->filter(function ($university) use ($country, $destinations) {
            if (!$country) {
                return false;
            }

            return in_array(
                $university['id'] ?? null,
                collect($country['universities'] ?? [])->pluck('id')->all()
            );
        })->values();
    }

    $countries = $destinations->pluck('name')->values();

    // return $universities;

    return view('frontend.pages.universities.index', compact(
        'universities',
        'countries'
    ));
}


    public function show(University $university)
    {
        

        $university->load(['country', 'city', 'campuses', 'courses', 'intakes']);

        // return $university;

        return view('frontend.pages.universities.show');
    }

}
