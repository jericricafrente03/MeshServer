<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Interfaces\General\SearchInterface;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private SearchInterface $repository;

    public function __construct(SearchInterface $repository)
    {
        $this->repository = $repository;
    }

    public function search($name, Request $request)
    {
        $call = str_replace('-', '', ucwords($name, '-'));
        $call = 'search'.$call;
        
        return $this->repository->$call($request);
    }
}
