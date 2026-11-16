<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use RuntimeException;


class DestinationController extends Controller
{

    
    public function destinations(Request $request): View
    {
        $file = resource_path('data/destinations.json');

        abort_unless(File::exists($file), 404, 'Destination data file not found.');

        $allDestinations = json_decode(File::get($file), true);

        if (! is_array($allDestinations)) {
            throw new RuntimeException('Invalid destinations.json: expected a JSON array.');
        }

        $search = trim((string) $request->query('search', ''));
        $region = trim((string) $request->query('region', ''));
        $sort = (string) $request->query('sort', 'rating');

        if (! in_array($sort, ['rating', 'popular'], true)) {
            $sort = 'rating';
        }

        $source = $allDestinations['destinations'] ?? $allDestinations;
        $all = collect($source)
            ->filter(fn ($item) => is_array($item))
            ->map(fn (array $item) => array_merge($item, [
                'country' => $item['country'] ?? $item['name'] ?? '',
                'region' => $item['region'] ?? $item['location'] ?? '',
                'reviewsCount' => $item['reviewsCount'] ?? $item['review_count'] ?? 0,
            ]))
            ->values();

        $regions = $all
            ->pluck('region')
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->unique()
            ->sort()
            ->values();

        // Ignore an invalid region rather than returning a confusing empty result.
        if ($region !== '' && ! $regions->contains($region)) {
            $region = '';
        }

        $data = [
            // Send the complete dataset once; search, filters, and sorting run in Alpine.
            'destinations' => $all->values(),
            'totalDestinations' => $all->count(),
            'search' => $search,
            'selectedRegion' => $region,
            'sort' => $sort,
            'regions' => $regions,
        ];

        return view('frontend.pages.destinations.index', $data);
    }

public function show($slug)
{
    $detailsPath = resource_path('data/destination-details.json');

    if (!File::exists($detailsPath)) {
        abort(404);
    }

    $destinationData = json_decode(
        File::get($detailsPath),
        true
    );

    // Find destination by slug
    $destination = collect($destinationData['destinations'] ?? [])
        ->firstWhere('slug', $slug);

    if (!$destination) {
        abort(404);
    }

    // return $destination;

    return view('frontend.pages.destinations.details', compact('destination')
    );
}
}
