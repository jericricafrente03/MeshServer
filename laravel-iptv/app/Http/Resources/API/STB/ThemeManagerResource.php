<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get SYSTEM_URL from the environment
        $systemUrl = config('app.system_url');
        $data = parent::toArray($request);
        if (env('APP_ENV') !== "scribe") {
            $data['bg_uri'] = $this->bg_uri ? "{$systemUrl}{$this->bg_uri}" : null;
        }
        $data['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $data['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
        unset($data['is_default']);
        // Check if it's Scribe (use fake data)
        
        if (env('APP_ENV') !== "scribe") {
            $systemUrl = config('app.system_url');
            // Add `zones` relationship and include fields from `theme_zones`
            $data['zones'] = $this->zones ? $this->zones->map(function ($zone) use($systemUrl) {
                $themeZone = $zone->theme_zones->where('theme_id', $this->id)->first();
                
                return [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'bg_uri' => $themeZone->bg_uri ? "{$systemUrl}{$themeZone->bg_uri}" : null,
                    'text_color' => $themeZone->text_color ?? null,
                    'active_text_color' => $themeZone->active_text_color ?? null,
                ];
            }) : [];
            // Add `applications` relationship
            $data['applications'] = $this->theme_applications
                ->filter(function ($themeApplication) {
                    return $themeApplication->is_enable == 1; // Filter only enabled applications
                })
                ->map(function ($themeApplication) use($systemUrl) {
                    $application = $themeApplication->application;

                    return [
                        'id' => $application->id,
                        'name' => $application->name,
                        'method' => $application->method,
                        'icon' => $themeApplication->icon ? "{$systemUrl}{$themeApplication->icon}" : null,
                        'active_icon' => $themeApplication->active_icon ? "{$systemUrl}{$themeApplication->active_icon}" : null,
                        'text_color' => $themeApplication->text_color ?? null,
                        'active_text_color' => $themeApplication->active_text_color ?? null,
                        'order_no' => $themeApplication->order_no,
                    ];
                })->values(); // Re-index the array to remove numeric keys
        }
        return $data;
    }

}
