<?php

namespace App\Http\Resources\API\STB\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StbLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        // Format created_at and updated_at if available
        $data['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $data['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;


        // Add the token if it exists in additional data
        // if ($this->additional && isset($this->additional['token'])) {
        //     $data['token'] = $this->additional['token'];
        // }
        
        return $data;
    }
}
