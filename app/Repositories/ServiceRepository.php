<?php

namespace App\Repositories;

use App\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceRepository
{
    /**
     * Get all services with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateServices(): LengthAwarePaginator
    {
        return Service::paginate(config('tinyerp.default-pagination'));
    }

    /**
     * Create a new service.
     *
     * @param array $data
     * @return Service
     */
    public function createService(array $data): Service
    {
        return Service::create($data);
    }

    /**
     * Find a service by its model instance.
     *
     * @param Service $service
     * @return Service
     */
    public function findService(Service $service): Service
    {
        return $service;
    }

    /**
     * Update an existing service.
     *
     * @param Service $service
     * @param array $data
     * @return Service
     */
    public function updateService(Service $service, array $data): Service
    {
        $service->fill($data)->save();
        return $service;
    }

    /**
     * Delete a service.
     *
     * @param Service $service
     * @return bool|null
     */
    public function deleteService(Service $service): ?bool
    {
        return $service->delete();
    }
}
