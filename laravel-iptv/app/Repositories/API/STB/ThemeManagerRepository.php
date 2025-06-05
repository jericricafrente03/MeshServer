<?php

namespace App\Repositories\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\STB\ThemeManagerResource;
use App\Interfaces\API\STB\IThemeManagerRepository;
use App\Models\General\Body\Guests\Room;
use App\Models\SystemSettings\ThemeManager\Theme;
use App\Models\SystemSettings\ThemeManager\ThemeRoom;

class ThemeManagerRepository extends Controller implements IThemeManagerRepository
{
    public function getTheme($request)
    {
        $data = $request;

        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            return response()->json([
                'data' => [],
                'result' => 'failed',
                'message' => 'Record not found.'
            ], 404);
        } 
        
        $roomTheme = ThemeRoom::whereHas('room', function ($query) use ($data) {
                $query->where('name', $data['room']);
            })->first();

        if($roomTheme){
            $theme = Theme::find($roomTheme->theme_id);
        }
        else{
            $theme = Theme::where('is_default', 1)->first();
        }

        
        // $recipients = MessageRecipient::where('room_id', $room->id)
        //     ->where('status_id', '!=', 34)
        //     ->get();

        // foreach ($recipients as $recipient) {
        //     if ($recipient->status_id == 31) {
        //         $recipient->update(['status_id' => 32]);
        //     }
        // }
        
        return ThemeManagerResource::make($theme)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}