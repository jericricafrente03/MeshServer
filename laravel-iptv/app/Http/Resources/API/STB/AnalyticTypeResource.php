<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticTypeResource extends JsonResource
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
        
        unset($data['created_at'], $data['updated_at'], $data['bg_color'], $data['color'], $data['icon']);

        return $data;
    }
}
