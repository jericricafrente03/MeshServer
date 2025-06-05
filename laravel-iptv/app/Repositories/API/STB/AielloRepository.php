<?php

namespace App\Repositories\API\STB;

use App\Interfaces\API\STB\IAielloRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Tv\TvChannel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AielloRepository implements IAielloRepository
{
    function toggleTvPower($request)
    {
        $data = $request->data;
        $device = $this->checkDevice($data);

        if ($device instanceof JsonResponse) {
            return $device;
        }

        if ($device) {
            $result = shell_exec(base_path()."/public/bash-scripts/adb_toggle_standby.sh '".$device->ip4_address."' '".$data['status']."'");
            $lines = explode("\n", trim($result));
    
            // Prepare the JSON-like response
            $response = [
                'result' => $lines[0] ?? 'Failed'
            ];
    
            if (!empty($lines[1])) {
                $response['reason'] = $lines[1];
            }
            
            return response()->json($response);
        } else {
            return response()->json([
                'result' => 'failed',
                'message' => 'Device not found.'
            ], 404);
        }
    }

    function volumeChange($request)
    {
        $data = $request->data;
        $device = $this->checkDevice($data);

        if ($device instanceof JsonResponse) {
            return $device;
        }

        if ($device) {
            $result = shell_exec(base_path()."/public/bash-scripts/adb_change_volume.sh '".$device->ip4_address."' '".$data['volume']."'");
            $lines = explode("\n", trim($result));
            
            // Prepare the JSON-like response
            $response = [
                'result' => $lines[0] ?? 'Failed',
            ];

            if (!empty($lines[1])) {
                $response['reason'] = $lines[1];
            }

            return response()->json($response);
        } else {
            return response()->json([
                'result' => 'failed',
                'message' => 'Device not found.'
            ], 404);
        }   
    }

    function getTvChannels($request)
    {   
        $data['result'] = 'Success';
        $data['data'] = TvChannel::where('is_enable', 1)
            ->select('id', 'channel', DB::raw('name as channel_title'))
            ->get()
            ->toArray();
        
        return response()->json($data);   
    }

    function openApplication($request)
    {
        $data = $request->data;
        $device = $this->checkDevice($data);

        // Check if the return is a JsonResponse (i.e. an error response)
        if ($device instanceof JsonResponse) {
            return $device;
        }

        // Proceed if device was found
        if ($device) {
            // Your logic here, e.g., open app on device
            
            switch ($data['application']) {
                case 'netflix':
                    $result = shell_exec(base_path()."/public/bash-scripts/aiello/adb_open_netflix.sh '".$device->ip4_address."'");
                    $lines = explode("\n", trim($result));
                    break;
                case 'spotify':
                    $result = shell_exec(base_path()."/public/bash-scripts/aiello/adb_open_spotify.sh '".$device->ip4_address."'");
                    $lines = explode("\n", trim($result));
                    break;
                case 'disney_plus':
                    $result = shell_exec(base_path()."/public/bash-scripts/aiello/adb_open_disney_plus.sh '".$device->ip4_address."'");
                    $lines = explode("\n", trim($result));
                    break;
                case 'youtube':
                    $result = shell_exec(base_path()."/public/bash-scripts/aiello/adb_open_youtube.sh '".$device->ip4_address."'");
                    $lines = explode("\n", trim($result));
                    break;
                case 'prime_video':
                    $result = shell_exec(base_path()."/public/bash-scripts/aiello/adb_open_prime_video.sh '".$device->ip4_address."'");
                    $lines = explode("\n", trim($result));
                    break;
                case 'home':
                    $result = shell_exec(base_path()."/public/bash-scripts/aiello/adb_open_home.sh '".$device->ip4_address."'");
                    $lines = explode("\n", trim($result));
                    break;
                case 'tv':
                    $commands = [
                        'aiello_opentvapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
    
                    $lines[0] = "Success";
                    break;
                case 'weather':
                    $commands = [
                        'aiello_openweatherapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
    
                    $lines[0] = "Success";
                    break;
                case 'message':
                    $commands = [
                        'aiello_openmessageapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
                    $lines[0] = "Success";
                    break;
                case 'hotel_info':
                    $commands = [
                        'aiello_hotelinfoapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
                    $lines[0] = "Success";
                    break;
                case 'air_media':
                    $commands = [
                        'aiello_openairmediaapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
                    $lines[0] = "Success";
                    break;
                case 'dining':
                    $commands = [
                        'aiello_opendiningapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
                    $lines[0] = "Success";
                    break;
                case 'billing':
                    $commands = [
                        'aiello_openbillingapp'
                    ];
            
                    $conditions = [
                        'or' => [
                            ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                        ],
                    ];
                    $lines[0] = "Success";
                    break;
                default:
                    $response = [
                        'result' => 'Failed',
                        'reason' => 'Invalid Application'
                    ];
                    return json_encode($response);
                    break;
            }

            if (!empty($commands)) {
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);
                });
            
                DB::commit();
            
                // Start the queue worker temporarily to process the job
                shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');
            }
            
            // Prepare the JSON-like response
            $response = [
                'result' => $lines[0] ?? 'Failed'
            ];
            
            // Check if the result is "bash arg: -p"
            if ($response['result'] === 'bash arg: -p') {
                $response['result'] = 'Success';
            }
            
            return response()->json($response);
        } else {
            return response()->json([
                'result' => 'failed',
                'message' => 'Device not found.'
            ], 404);
        }
    }

    function changeTvChannel($request)
    {
        $data = $request->data;
        $device = $this->checkDevice($data);

        if ($device instanceof JsonResponse) {
            return $device;
        }

        if ($device) {
            $commands = [
                'aiello_changetvchannel?channel='.$data['channel']
            ];
    
            $conditions = [
                'or' => [
                    ['field' => 'ip4_address', 'operator' => '=', 'value' => $device->ip4_address],
                ],
            ];

            $lines[0] = "Success";

            // Queue the job to be executed after commit
            DB::afterCommit(function () use ($commands, $conditions) {
                // Dispatch the job in the background
                SendDataToDevicesJob::dispatch($commands, $conditions);
            });
        
            DB::commit();
        
            // Start the queue worker temporarily to process the job
            shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');
            
            // Prepare the JSON-like response
            $response = [
                'result' => $lines[0] ?? 'Failed',
            ];

            if (!empty($lines[1])) {
                $response['reason'] = $lines[1];
            }

            return response()->json($response);
        } else {
            return response()->json([
                'result' => 'failed',
                'message' => 'Device not found.'
            ], 404);
        }   
    }

    private function checkDevice($data)
    {
        $areaId = $data['area_id'] ?? 1;
        
        $room = Room::where('name', $data['room'])->with('devices')->first();

        if (!$room) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Room not found.'
            ], 404);
        }

        if ($room->devices->isEmpty()) {
            return response()->json([
                'result' => 'failed',
                'message' => 'No devices found in this room.'
            ], 404);
        }

        $device = Device::where('room_id', $room->id)->where('area_id', $areaId)->first();

        return $device;
    }
}