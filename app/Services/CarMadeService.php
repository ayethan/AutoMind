<?php

namespace App\Services;

use App\CarMade;
use App\Repositories\CarMadeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarMadeService
{
    protected $carMadeRepository;

    public function __construct(CarMadeRepository $carMadeRepository)
    {
        $this->carMadeRepository = $carMadeRepository;
    }

    /**
     * Get all car makes with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCarMades(): LengthAwarePaginator
    {
        return $this->carMadeRepository->paginateCarMades();
    }

    /**
     * Get all car makes.
     *
     * @return Collection
     */
    public function getAllCarMades(): Collection
    {
        return $this->carMadeRepository->getAllCarMades();
    }

    /**
     * Create a new car make.
     *
     * @param array $data
     * @return CarMade
     */
    public function createCarMade(array $data): CarMade
    {
        return $this->carMadeRepository->createCarMade($data);
    }

    /**
     * Find a car make by its model instance.
     *
     * @param CarMade $carMade
     * @return CarMade
     */
    public function findCarMade(CarMade $carMade): CarMade
    {
        return $this->carMadeRepository->findCarMade($carMade);
    }

    /**
     * Update an existing car make.
     *
     * @param CarMade $carMade
     * @param array $data
     * @return CarMade
     */
    public function updateCarMade(CarMade $carMade, array $data): CarMade
    {
        return $this->carMadeRepository->updateCarMade($carMade, $data);
    }

    /**
     * Delete a car make.
     *
     * @param CarMade $carMade
     * @return bool|null
     */
    public function deleteCarMade(CarMade $carMade): ?bool
    {
        return $this->carMadeRepository->deleteCarMade($carMade);
    }
}
