<?php

namespace App\Services;

use App\Car;
use App\Repositories\CarRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarService
{
    protected $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * Get all cars with pagination and relations.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCars(): LengthAwarePaginator
    {
        return $this->carRepository->paginateCars();
    }

    /**
     * Create a new car.
     *
     * @param array $data
     * @return Car
     */
    public function createCar(array $data): Car
    {
        return $this->carRepository->createCar($data);
    }

    /**
     * Find a car by its model instance and load relations.
     *
     * @param Car $car
     * @return Car
     */
    public function findCar(Car $car): Car
    {
        return $this->carRepository->findCar($car);
    }

    /**
     * Update an existing car.
     *
     * @param Car $car
     * @param array $data
     * @return Car
     */
    public function updateCar(Car $car, array $data): Car
    {
        return $this->carRepository->updateCar($car, $data);
    }

    /**
     * Delete a car.
     *
     * @param Car $car
     * @return bool|null
     */
    public function deleteCar(Car $car): ?bool
    {
        return $this->carRepository->deleteCar($car);
    }
}
