<?php

namespace App\Repositories\API\STB;

use App\Events\General\Body\Devices\DevicesUpdated;
use App\Http\Resources\API\STB\Auth\StbLoginResource;
use App\Http\Resources\API\STB\Auth\StbRegisterResource;
use App\Http\Resources\API\STB\Auth\StbregistrationResource;
use App\Http\Resources\API\STB\GuestBillingResource;
use App\Interfaces\API\STB\IAuthRepository;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Guests\GuestBilling;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;
use App\Models\GeneralStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;
use Laravel\Sanctum\PersonalAccessToken;

class AuthRepository implements IAuthRepository
{   
    public function stb_authenticateDevice($data)
    {
        if(Auth::attempt(['name'=>$data['data']['name'], 'password'=>$data['data']['password']])){   
            Auth::user()->tokens()->delete();
            // $token = Auth::user()->createToken('user_token')->plainTextToken;
            // $token = Auth::user()->createToken('user_token', ['*'], now()->addDays(1))->plainTextToken;
            // return response()->json([ 'user' => Auth::user(), 'token' => $token ], 200);
            
            // Get the latest token ID in the database
            $latestTokenId = PersonalAccessToken::max('id');
            
            if ($latestTokenId >= 1000) {
                // Get the latest token ID
                PersonalAccessToken::truncate();
                // $latestTokenId = PersonalAccessToken::max('id');

                // // Delete expired tokens (keep the latest one)
                // PersonalAccessToken::whereNotNull('expires_at')
                //     ->where('expires_at', '<', now()) // Only expired tokens
                //     // ->where('id', '<', $latestTokenId) // Keep the last one
                //     ->delete();
               
                // // Reset AUTO_INCREMENT only if one token remains
                // if (PersonalAccessToken::count() == 0) {
                //     DB::statement("ALTER TABLE personal_access_tokens AUTO_INCREMENT = 1;");
                // }
            }        

            // for development
            // $expirationTime = now()->addSeconds(30)->toDateTimeString();
            // $token = Auth::user()->createToken('user_token', ['*'], now()->addSeconds(30))->plainTextToken;

            // for production
            $expirationMinutes = env('SANCTUM_EXPIRATION_BY_MINUTE', 1440); // Default to 1 day if not set
            $expirationTime = now()->addMinutes($expirationMinutes)->toDateTimeString();
            $token = Auth::user()->createToken('user_token', ['*'], now()->addMinutes($expirationMinutes))->plainTextToken;

            return StbLoginResource::make(Auth::user()) // Single resource instead of collection
            ->additional([
                    'token' => $token, // Include the token in the additional data
                    'token_expires_at' => $expirationTime, // Include expiration time
                    'result' => __('success'),
                    'message' => __('Successfully login!'),
                ])
            ->response()
            ->setStatusCode(201); // HTTP status 200 OK
        }
        else{
            return response()->json([
                'errors' => 'Invalid credentials or account not found.'
            ], 400);
        }
    }

    public function stb_time()
    {
        $data['time'] = str_replace("\n","",shell_exec("date +%m%d%H%M%y.%S"));

        // $device = Device::where('id', '1')->first();
        // $device->current_status = "Active";
        // $device->save();

        // event(new DevicesUpdated($device));

        $updatedDevices = Device::where('id','!=', '0')->get(); // Example for multiple devices
        
        foreach ($updatedDevices as $device) {
            $device->current_status = "Inactive"; // Or whatever status you want to set
            $device->save();
        }

        // Now fire the event for all updated devices
        event(new DevicesUpdated($updatedDevices)); // Pass the collection of updated devices


        return $data;
    }

    public function stb_register($request)
    {
        $api_key = $request['data']['api_key'];
        $mac_address = $request['data']['mac_address'];
        $room_number = $request['data']['room_number'];
        $version = $request['data']['version'];
        $class = __FUNCTION__;

        // Get the current time in microseconds
        $t = explode(" ", microtime());
        // Convert and format the date and time
        $debug1['date'] = date("m-d-y H:i:s", (int)$t[1]) . substr((string)$t[0], 1, 4);

        $api_key2 = md5(strtolower($mac_address) . env('SYSTEM_VERSION'));
        \Log::info('Register STB API KEY: '.$api_key2.'');
        
        if ($api_key === $api_key2) {
            $ip_address = request()->ip();

            // Check if the device exists by API key
            $result = Device::where('api_id', $api_key2)->first();

            if ($result === null) {
                // Check if the device exists by MAC address
                $resultByMac = Device::where('mac_address', trim($mac_address))->first();
                
                if ($resultByMac === null) {
                    // Register a new device
                    $result = new Device();
                    $result = $this->regNewStb($result, $request, $ip_address);
                } 
                else {
                    // Update existing device
                    $result = $this->regNewStb($resultByMac, $request, $ip_address);
                }
            }
            else{
                $last_id = $result->id;
                $data = Device::where('id', $last_id)->first();
                $result = $this->regNewStb($data, $request, $ip_address);
            }
            //create user for device
            $user = $this->userStbRegistration($result);
            
            //room and stb binding
            $room_result = Room::where('name', $room_number)->first();
            if ($room_result !== null) {
                $room_id = $room_result->id;
                $device_id = $result->id;
                
                $result = $this->roomStbRegistration($room_id, $device_id);
                $data = [];
                $result = 'success';
                $reasons = 'STB has been registered';
                $room_number = $room_number;//$room_number;
                $data['device_id'] = $device_id;
                $data['room'] = $room_number; //$room_number;
            	$data['username'] = $user->name;
                $data['version'] = $version;
                // $data['action'] = 'UPDATE';
                $data['class'] = $class;
            }
            else{
                $data = [];
                $result = 'failed';
                $reasons = 'Room is not existed';
                $room_number = $room_number;
                $data['version'] = $version;
                // $data['action'] = 'UPDATE';
                $data['class'] = $class;
            }

        }
        else{
            $data = [];
            $result= 'failed';
            $reasons = 'API key does not match';
            $room_number = $room_number;
            $data['version'] = $version;
            // $data['action'] = 'UPDATE';
            $data['class'] = $class;
        }

        return StbRegisterResource::make($data) // Single resource instead of collection
            ->additional([
                    'room_number' => $room_number, // Include the token in the additional data
                    'result' => $result,
                    'message' => $reasons,
                ])
            ->response()
            ->setStatusCode(201); // HTTP status 200 OK
        // return $data;
    }

    private function userStbRegistration($data)
    {   
        $user = User::where('name', $data->mac_address)->first();

        if ($user === null) {
            $user = User::create([
                'name' => $data->mac_address, 
                'firstname' => $data->mac_address,
                'lastname' => 'Device',
                'email' => $data->mac_address .'@bittelasia.com',
                'password' => Hash::make($data->api_id),
                'role_id' => 3,
                'initials_random_color' =>  rand(1, 10)
            ]);
        }

        $user = user::find($user->id);
        $device = Device::find($data->id);
        
        // Check if the device is already assigned to a room
        if ($device->user_id !== null) {
            $device->user_id = null;
        }
        
        // Assign the device to the room
        $device->user()->associate($user);
        $device->save();
        
        return $user;
    }

    private function regNewStb($result, $request, $ip_address)
    {   
        $result->mac_address = $request['data']['mac_address'];
        $result->os_version = Agent::platform();
        $result->time_activated = now(); // Use Carbon for current timestamp
        $result->last_action = 'register';
        $result->current_status = 'Active';
        $result->time_last_action = now(); // Use Carbon for current timestamp
        $result->api_id = $request['data']['api_key'];
        $result->ip4_address = $ip_address;
        $result->area_id = $request['data']['area_id'];

        // Save the new device instance
        $result->save();

        return $result;
    }

    private function roomStbRegistration($room_id, $device_id)
    {
        $room = Room::find($room_id);
        $device = Device::find($device_id);

        //Get Devices for a Room
        $room = Room::find($room_id);
        $devices = $room->devices; // Get all devices associated with the room

        //Get the Room for a Device:
        $device2 = Device::find($device_id);
        $room2 = $device->room; // Get the room associated with the device
        
        // Check if the device is already assigned to a room
        if ($device->room_id !== null) {
            // The device is already associated with a room, remove the association
            // $device->room()->dissociate(); // This removes the room_id from the device
            $device->room_id = null;
        }
        
        // Assign the device to the room
        $device->room()->associate($room);
        $device->save();
        
        return $device;
    }

    public function checkToken($request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'result' => 'failed',
                'message' => 'No token provided.'
            ], 200);
        }
        
        // Find the token record in database
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Invalid token.'
            ], 200);
        }
        
        // Check if token has an 'expires_at' field (if you're using expiration logic)
        if ($accessToken->expires_at && Carbon::parse($accessToken->expires_at)->isPast()) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Token has expired.'
            ], 200);
        }

        return response()->json([
            'result' => 'success',
            'message' => 'Token is valid.',
            'expires_at' => $accessToken->expires_at // Optional: Return expiry date if available
        ]);
    }
}