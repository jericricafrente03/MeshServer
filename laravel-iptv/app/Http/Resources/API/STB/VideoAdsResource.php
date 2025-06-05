<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoAdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        // Get SYSTEM_URL from the environment
        $systemUrl = config('app.system_url');
        
        if (env('APP_ENV') !== "scribe") {
            $data['video_uri'] = $this->video_uri ? "{$systemUrl}{$this->video_uri}" : null;
        }

        $data['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $data['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
        
        return $data;
    }
}
