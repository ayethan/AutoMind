<?php

namespace App\Services;

use App\Service;
use App\Repositories\ServiceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Get all services with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateServices(): LengthAwarePaginator
    {
        return $this->serviceRepository->paginateServices();
    }

    /**
     * Create a new service.
     *
     * @param array $data
     * @return Service
     */
    public function createService(array $data): Service
    {
        return $this->serviceRepository->createService($data);
    }

    /**
     * Find a service by its model instance.
     *
     * @param Service $service
     * @return Service
     */
    public function findService(Service $service): Service
    {
        return $this->serviceRepository->findService($service);
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
        return $this->serviceRepository->updateService($service, $data);
    }

    /**
     * Delete a service.
     *
     * @param Service $service
     * @return bool|null
     */
    public function deleteService(Service $service): ?bool
    {
        return $this->serviceRepository->deleteService($service);
    }
}
