<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        if (env('APP_ENV') !== "scribe") {
            $newData = $data['language'];
            unset(
                $newData['created_at'], 
                $newData['updated_at'], 
                $newData['description'], 
                $newData['bg_color'], 
                $newData['color'], 
                $newData['icon'],
                $newData['sort'],
                $newData['category']
            );
        }
        else{
            $newData = [
                "id" => 75,
                "name" => "Arabic"
            ];
        }

        return $newData;
    }
}
