<?php

namespace App\Jobs;

use App\Models\General\Body\Devices\Device;
use Closure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDataToDevicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $commands;
    protected $conditions;

    /**
     * Create a new job instance.
     */
    public function __construct($commands, array $conditions = [])
    {
        $this->commands = $commands;
        $this->conditions = $conditions; // Store the condition
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Use the closure to build the query
        $query = Device::query();

        // Apply conditions dynamically
        $this->applyConditions($query, $this->conditions);

        $devices = $query->get();
        $ipAddresses = [];
        
        foreach ($devices as $device) {
            if (!in_array($device->ip4_address, $ipAddresses)) {
                $ipAddresses[] = $device->ip4_address;
            }
        }

        $logFile = storage_path('logs/laravel.log'); // or your custom log path
        if (file_exists($logFile)) {
            $lineCount = count(file($logFile));
    
            if ($lineCount > 10000) {
                // Clear the log file if it has more than 2000 lines
                file_put_contents($logFile, '');
            }
        }

        // Log all the collected IP addresses
        if (!empty($ipAddresses)) {
            \Log::info('IP addresses retrieved:', $ipAddresses);
        } else {
            \Log::info('No IP addresses retrieved for the given conditions.');
        }

        if (count($ipAddresses) > 0) {
            // You can instantiate the controller methods here
            $controller = new \App\Http\Controllers\General\JobController();
            
            $result = $controller->stbMultiCurlIpList($ipAddresses, $this->commands);
            $controller->stbMultiCurlPost($result['request_contents'], $result['urls']);
        }
    }

    protected function applyConditions(Builder $query, array $conditions): void
    {
        if (empty($conditions)) {
            // If conditions are empty, no filters are applied
            \Log::info('No conditions provided. Query will fetch all records.');
            return;
        }
    
        foreach ($conditions as $key => $condition) {
            if ($key === 'or' && is_array($condition)) {
                // Handle OR conditions
                $query->where(function ($subQuery) use ($condition) {
                    foreach ($condition as $orCondition) {
                        $subQuery->orWhere(
                            $orCondition['field'],
                            $orCondition['operator'],
                            $orCondition['value']
                        );
                    }
                });
            }
            elseif (isset($condition['has']) && isset($condition['relation']) && isset($condition['where'])) {
                // Handle `whereHas` conditions
                $query->whereHas($condition['relation'], function ($subQuery) use ($condition) {
                    foreach ($condition['where'] as $whereCondition) {
                        $subQuery->where($whereCondition['field'], $whereCondition['operator'], $whereCondition['value']);
                    }
                });
            } 
            elseif (isset($condition['or']) && is_array($condition['or'])) {
                // Handle "or" conditions
                $query->orWhere(function ($query) use ($condition) {
                    foreach ($condition['or'] as $orCondition) {
                        $query->where($orCondition['field'], $orCondition['operator'], $orCondition['value']);
                    }
                });
            } 
            elseif (isset($condition['field'], $condition['operator'], $condition['value'])) {
                // Handle regular conditions
                $query->where($condition['field'], $condition['operator'], $condition['value']);
            } 
            else {
                // Log or handle invalid conditions
                \Log::warning('Invalid condition format:', $condition);
            }
        }
    }
}
