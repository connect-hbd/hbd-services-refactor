<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settings)
    {
    }

    /**
     * Display application settings.
     */
    public function index()
    {
        return view('backend.pages.settings.index', ['companySettings' => $this
            ->settings
            ->group('company') , 'attendanceSettings' => $this
            ->settings
            ->group('attendance') , 'leaveSettings' => $this
            ->settings
            ->group('leave') , 'securitySettings' => $this
            ->settings
            ->group('security') , ]);
    }

    /**
     * Update application settings.
     */
    public function update(Request $request):
        JsonResponse
        {
            $validated = $request->validate([
            // Company
            'company_name' => ['required', 'string', 'max:191'], 'address' => ['nullable', 'string', 'max:5000'], 'phone' => ['nullable', 'string', 'max:50'], 'email' => ['nullable', 'email', 'max:191'], 'website' => ['nullable', 'url', 'max:191'],

            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', ],

            'favicon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg', 'max:1024', ],

            // Attendance
            'working_hours_start' => ['required', 'date_format:H:i', ],

            'working_hours_end' => ['required', 'date_format:H:i', ],

            'late_threshold_minutes' => ['required', 'integer', 'min:0', 'max:1440', ],

            'early_departure_threshold_minutes' => ['required', 'integer', 'min:0', 'max:1440', ],

            'weekend_days' => ['nullable', 'array'], 'weekend_days.*' => ['required', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', ]) , ],

            // Leave
            'max_consecutive_days' => ['required', 'integer', 'min:1', 'max:365', ],

            // Security
            'ip_whitelist_enabled' => ['required', 'boolean', ],

            'whitelist_ips' => ['nullable', 'array'], 'whitelist_ips.*' => ['required', 'ip', 'distinct', ], ]);

         $company = [
            'company_name' => [
                $validated['company_name'],
                'string',
                'Company Name',
                'Company identity name',
            ],
            'address' => [
                $validated['address'] ?? null,
                'string',
                'Company Address',
                'Company registered or office address',
            ],
            'phone' => [
                $validated['phone'] ?? null,
                'string',
                'Company Phone',
                'Company contact number',
            ],
            'email' => [
                $validated['email'] ?? null,
                'string',
                'Company Email',
                'Company contact email',
            ],
            'website' => [
                $validated['website'] ?? null,
                'string',
                'Company Website',
                'Official company website',
            ],
        ];

            foreach ($company as $key => [$value, $type, $label, $description])
            {
                $this
                    ->settings
                    ->set($key, $value, $type, ['group' => 'company', 'label' => $label, 'description' => $description, ]);
            }

           // Attendance settings
        $attendance = [
            'working_hours_start' => [
                $validated['working_hours_start'],
                'string',
                'Office Start Time',
            ],
            'working_hours_end' => [
                $validated['working_hours_end'],
                'string',
                'Office End Time',
            ],
            'late_threshold_minutes' => [
                $validated['late_threshold_minutes'],
                'integer',
                'Late Threshold',
            ],
            'early_departure_threshold_minutes' => [
                $validated['early_departure_threshold_minutes'],
                'integer',
                'Early Departure Threshold',
            ],
            'weekend_days' => [
                $validated['weekend_days'] ?? [],
                'json',
                'Weekend Days',
            ],
        ];
            foreach ($attendance as $key => [$value, $type, $label])
            {
                $this
                    ->settings
                    ->set($key, $value, $type, ['group' => 'attendance', 'label' => $label, ]);
            }

            // Leave settings
            $this
                ->settings
                ->set('max_consecutive_days', $validated['max_consecutive_days'], 'integer', ['group' => 'leave', 'label' => 'Maximum Consecutive Leave Days', ]);

            // Security settings
            $this
                ->settings
                ->set('ip_whitelist_enabled', (bool)$validated['ip_whitelist_enabled'], 'boolean', ['group' => 'security', 'label' => 'Enable IP Whitelist', ]);

              $this->settings->set(
            'whitelist_ips',
            $validated['whitelist_ips'] ?? [],
            'json',
            [
                'group' => 'security',
                'label' => 'Whitelisted IP Addresses',
            ]
        );
            // Upload company logo and favicon
            $uploadedFiles = [];

            foreach (['logo', 'favicon'] as $field)
            {
                if (!$request->hasFile($field))
                {
                    continue;
                }

                $oldPath = $this
                    ->settings
                    ->get($field);

                $path = $request->file($field)->store("company/{$field}", 'public');

                $this
                    ->settings
                    ->set($field, $path, 'string', ['group' => 'company', 'label' => ucfirst($field) , ]);

                $uploadedFiles[$field] = $path;

                if ($oldPath && Storage::disk('public')->exists($oldPath))
                {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            return response()->json(['success' => true, 'message' => 'Settings updated successfully.', 'files' => ['logo_url' => isset($uploadedFiles['logo']) ? Storage::disk('public')->url($uploadedFiles['logo']) : null,

            'favicon_url' => isset($uploadedFiles['favicon']) ? Storage::disk('public')->url($uploadedFiles['favicon']) : null, ], ]);
        }
    }
    
