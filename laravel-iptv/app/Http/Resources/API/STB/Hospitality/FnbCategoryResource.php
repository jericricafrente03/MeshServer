<?php

namespace App\Http\Resources\API\STB\Hospitality;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FnbCategoryResource extends JsonResource
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
        
        unset($data['created_at'], $data['updated_at'], $data['color']);

        return $data;
    }
}
