<?php

namespace App\Http\Controllers\API;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Services\ServiceService; // New import
use Exception;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = $this->serviceService->paginateServices();
        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $service = $this->serviceService->createService($request->all());
            return new ServiceResource($service);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $service = $this->serviceService->findService($service);
        return new ServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        try {
            $service = $this->serviceService->updateService($service, $request->all());
            return new ServiceResource($service);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        try {
            $this->serviceService->deleteService($service);
            return new ServiceResource($service);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
