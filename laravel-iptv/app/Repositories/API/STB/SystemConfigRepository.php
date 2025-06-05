<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\SystemConfigResource;
use App\Interfaces\API\STB\ISystemConfigRepository;
use App\Models\SystemSettings\SystemConfig;

class SystemConfigRepository implements ISystemConfigRepository
{
    public function getSystemConfig($request)
    {
        
        $config = SystemConfig::first();

        return SystemConfigResource::make($config)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}