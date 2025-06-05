<?php

namespace App\Repositories\API\STB\Hospitality;

use App\Http\Resources\API\STB\Hospitality\ServiceRequestResource;
use App\Interfaces\API\STB\Hospitality\IServiceRequestRepository;
use App\Models\General\Body\Hospitality\HospitalityService;

class ServiceRequestRepository implements IServiceRequestRepository
{
    public function getServiceRequests($request)
    {
        $data = HospitalityService::where('is_enable', 1)->get();

        return ServiceRequestResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}