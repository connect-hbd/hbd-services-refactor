<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use RuntimeException;


class DestinationController extends Controller
{

    
public function destinations(Request $request): View|JsonResponse
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

        $all = collect($allDestinations)->filter(fn ($item) => is_array($item));

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

        $destinations = $all
            ->when($search !== '', function (Collection $items) use ($search) {
                $needle = Str::lower($search);

                return $items->filter(function (array $destination) use ($needle) {
                    $searchable = [
                        $destination['name'] ?? '',
                        $destination['title'] ?? '',
                        $destination['country'] ?? '',
                        $destination['region'] ?? '',
                        $destination['description'] ?? '',
                        $destination['avgCost'] ?? '',
                        $destination['visaProcessing'] ?? '',
                        ...($destination['topUniversities'] ?? []),
                        ...($destination['popularFor'] ?? []),
                    ];

                    foreach ($searchable as $value) {
                        if (is_scalar($value) && Str::contains(Str::lower((string) $value), $needle)) {
                            return true;
                        }
                    }

                    return false;
                });
            })
            ->when($region !== '', fn (Collection $items) => $items->where('region', $region))
            ->sort(function (array $a, array $b) use ($sort) {
                if ($sort === 'popular') {
                    $comparison = (int) ($b['reviewsCount'] ?? 0) <=> (int) ($a['reviewsCount'] ?? 0);

                    // Use rating as a stable secondary sort when review counts match.
                    return $comparison !== 0
                        ? $comparison
                        : (float) ($b['rating'] ?? 0) <=> (float) ($a['rating'] ?? 0);
                }

                $comparison = (float) ($b['rating'] ?? 0) <=> (float) ($a['rating'] ?? 0);

                // Use review count as a stable secondary sort when ratings match.
                return $comparison !== 0
                    ? $comparison
                    : (int) ($b['reviewsCount'] ?? 0) <=> (int) ($a['reviewsCount'] ?? 0);
            })
            ->values();

        $data = [
            'destinations' => $destinations,
            'totalDestinations' => $all->count(),
            'search' => $search,
            'selectedRegion' => $region,
            'sort' => $sort,
            'regions' => $regions,
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('frontend.pages.destinations._results', $data)->render(),
                'count' => $destinations->count(),
                'total' => $all->count(),
            ]);
        }

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
