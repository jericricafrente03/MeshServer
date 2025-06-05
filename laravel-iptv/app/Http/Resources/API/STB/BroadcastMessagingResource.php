<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BroadcastMessagingResource extends JsonResource
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

        $data['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $data['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;

        unset(
            $data['device_category_id'], 
            $data['type_id'], 
            $data['broadcast_type_id'], 
            $data['img_thumbnail_uri'],
            $data['broadcast_type']['description'],
            $data['broadcast_type']['bg_color'],
            $data['broadcast_type']['color'],
            $data['broadcast_type']['icon'],
            $data['broadcast_type']['sort'],
            $data['broadcast_type']['category'],
            $data['broadcast_type']['created_at'],
            $data['broadcast_type']['updated_at'],
            $data['created_at'],
            $data['updated_at'],
            $data['deleted_at']
        );
        
        return $data;
    }
}
