<?php

namespace App\Http\Resources\API\STB;

use App\Models\General\Body\Messages\RegularMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegularMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the related RegularMessage instance
        $regularMessage = RegularMessage::find($this->regular_message_id);

        $data = parent::toArray($request);
        if (env('APP_ENV') !== "scribe") {
            $data['from'] = $regularMessage?->from;
            $data['subject'] = $regularMessage?->subject;
            $data['body'] = $regularMessage?->body;
        }
        $data['created_at'] = $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
        $data['updated_at'] = $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;

        // If guest exists, remove created_at and updated_at from guest
        if (isset($data['status'])) {
            unset(
                $data['status']['created_at'], 
                $data['status']['updated_at'], 
                $data['status']['description'], 
                $data['status']['bg_color'], 
                $data['status']['color'], 
                $data['status']['icon'],
                $data['status']['sort'],
                $data['status']['category']
            );
        }

        return $data;
    }
}
