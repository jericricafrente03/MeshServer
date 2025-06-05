<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvChannelResource extends JsonResource
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
            $data['img_uri'] = $this->img_uri ? "{$systemUrl}{$this->img_uri}" : null;
        }
        
        unset($data['created_at'], $data['updated_at'], $data['is_enable'], $data['img_thumbnail_uri']);

        return $data;
    }
}
