<?php

namespace App\Http\Controllers\SystemSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Requests\SystemSettings\SystemConfig\UpdateRequest;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\Country;
use App\Models\SystemSettings\SystemConfig;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SystemConfigController extends Controller
{
    protected $logHistoriesRepo;
    protected $activityName;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->middleware('permission:system_config.index');
        $this->activityName = 'system config';
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/systemSettings/systemConfig/index';    

        $systemConfig = SystemConfig::first();
        $countries = Country::get();
        $timeZones = TimeZone::get();

        $breadCrumb = ['System Settings', 'System Config'];
        return view($viewPath, compact('user', 'breadCrumb', 'countries', 'timeZones', 'systemConfig'));
    }

    public function update(UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $input = $request->all();   
            $sys = SystemConfig::first();
            $old_sys = SystemConfig::first();
            if($sys){
                $sys = $sys;
            }
            else{
                $sys = new SystemConfig();
            }
            $helper = new Helper;
            
            $sys->name = $helper->ProperNamingCase($input['name']);
            $sys->description = $input['description'];
            $sys->street = $helper->ProperNamingCase($input['street']);
            $sys->city = $helper->ProperNamingCase($input['city']);
            $sys->country_code = $input['country_code'];
            $sys->time_zone = $input['timezone'];
            $sys->currency = $input['currency'];
            $sys->website = $input['website'];
            $sys->email = $input['email'];
            $sys->lat = $input['lat'];
            $sys->lon = $input['lon'];
            $sys->welcome_message = $input['welcome_message'];
            $sys->max_idle = $input['max_idle'];
            if ($request->file('logo')) {
                $file = $request->file('logo');
                @unlink(public_path($sys->logo));
                $fileName = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/systemConfig/'), $fileName);

                $sys->logo = 'upload/systemConfig/' . $fileName;
            }
            $sys->save();

            if ($old_sys) {
                $oldDataArray = $old_sys->toArray();
                $newDataArray = $sys->toArray();

                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
            }
            
            // Prepare the commands
            $commands = [
                'get_system_config'
            ];

            $conditions = [
                //empty conditions to get all
            ];
            
            // Queue the job to be executed after commit
            DB::afterCommit(function () use ($commands, $conditions) {
                // Dispatch the job in the background
                SendDataToDevicesJob::dispatch($commands, $conditions);
            });
            
            DB::commit();

            // Start the queue worker temporarily to process the job
            shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');

            return redirect()->back()->with('status', 'Updated!');
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
        
    }
}
