<?php

namespace App\Repositories;

use App\Interfaces\IUserHistoryLogRepository;
use App\Models\UserHistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class UserHistoryLogRepository implements IUserHistoryLogRepository
{
    private $history;
    private $user;
    private $auth;

    public function __construct(UserHistoryLog $history, AuthFactory $auth)
    {
        $this->history = $history;
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return $this|false|string
     */

    private function authFullName()
    {
        return $this->auth->user()->firstname .' '. $this->auth->user()->lastname;
    }

    private function authId()
    {
        return $this->auth->user()->id;
    }

    private function authUsername()
    {
        return $this->auth->user()->name;
    }

    public function store($data, $activityName)
    {   
        
        $activity = $this->authFullName() . " created " . $activityName . ' = ' . $data['id'];
        
        $this->prepareStatement($activity, json_encode($data), $this->authId(), $this->authUsername());
    
    }

    public function update($oldData, $data, $activityName)
    {   
       
        $activity = $this->authFullName() . " updated " . $activityName . ' = ' . $data['id'];
        $differences = ['from' => [], 'to' => []];
        
        // Loop through the old data and compare with the new data
        foreach ($oldData as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }
        
            if (array_key_exists($key, $data) && $value != $data[$key]) { // Loose comparison (non-strict)
                $differences['from'][$key] = $value; // old value
                $differences['to'][$key] = $data[$key]; // new value
            }
        }
        
        // Check if there are no differences (i.e., both "from" and "to" are empty)
        if (empty($differences['from']) && empty($differences['to'])) {
            return; // Exit early if there are no changes
        }

        $differences = json_encode($differences);
    
        $this->prepareStatement($activity, $differences, $this->authId(), $this->authUsername());

    }

    public function delete($id, $activityName)
    {   
        $activity = $this->authFullName() . " deleted " . $activityName . ' = ' . $id;
    
        $this->prepareStatement($activity, null, $this->authId(), $this->authUsername());  
    }

    public function customUpdate($oldData, $data, $activity)
    {   
        $activity = $this->authFullName() . " updated " . $activity . ".";
        $differences = ['from' => [], 'to' => []];
        
        // Loop through the old data and compare with the new data
        foreach ($oldData as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }
        
            if (array_key_exists($key, $data) && $value != $data[$key]) { // Loose comparison (non-strict)
                $differences['from'][$key] = $value; // old value
                $differences['to'][$key] = $data[$key]; // new value
            }
        }
        
        // Check if there are no differences (i.e., both "from" and "to" are empty)
        if (empty($differences['from']) && empty($differences['to'])) {
            return; // Exit early if there are no changes
        }

        $differences = json_encode($differences);

        $this->prepareStatement($activity, $differences, $this->authId(), $this->authUsername());

    }

    public function rbacToggle($id, $toggle, $data, $activityName)
    {   
        
        $activity = $this->authFullName() . " toggle " . $toggle . " " . $activityName . ' = ' . $id;
        
        $this->prepareStatement($activity, json_encode($data), $this->authId(), $this->authUsername());
    
    }

    public function raCheckOut($oldData, $data, $activityName)
    {   
        
        $activity = $this->authFullName() . " checked out " . $activityName . ' = ' . $data['id'];
        $differences = ['from' => [], 'to' => []];

        // Loop through the old data and compare with the new data
        foreach ($oldData as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }
        
            if (array_key_exists($key, $data) && $value != $data[$key]) { // Loose comparison (non-strict)
                $differences['from'][$key] = $value; // old value
                $differences['to'][$key] = $data[$key]; // new value
            }
        }
        
        // Check if there are no differences (i.e., both "from" and "to" are empty)
        if (empty($differences['from']) && empty($differences['to'])) {
            return; // Exit early if there are no changes
        }

        $differences = json_encode($differences);
        
        $this->prepareStatement($activity, $differences, $this->authId(), $this->authUsername());
    
    }

    public function themeRoom($oldData, $data, $activity)
    {   
        $activity = $this->authFullName() . " updated " . $activity . ".";
        $differences = ['from' => [], 'to' => []];
        // dd($data);
        // Loop through the old data and compare with the new data
        foreach ($oldData as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }
            $differences['from'][$key.'-room_id'] = $value['room_id']; // old value
        }

        foreach ($data as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }
            $differences['to'][$key.'-room_id'] = $value['room_id']; // old value
        }

        $differences = json_encode($differences);

        $this->prepareStatement($activity, $differences, $this->authId(), $this->authUsername());

    }

    public function prepareStatement($activity, $data = null, $id, $name)
    {
        try {
            DB::beginTransaction();
            
            $log = new UserHistoryLog();
            $log->activity = $activity;
            $log->description = $data;
            $log->user_id = $id;
            $log->username = $name;
            $log->ip_address = request()->ip();

            $log->save();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); 
        }
    }

    
    
}