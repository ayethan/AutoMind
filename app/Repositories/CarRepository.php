<?php

namespace App\Repositories;

use App\Car;
use App\Utils\Helpers;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarRepository
{
    /**
     * Get all cars with pagination and relations.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCars(): LengthAwarePaginator
    {
        return Car::with("car_made", "customer")
            ->paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Create a new car.
     *
     * @param array $data
     * @return Car
     */
    public function createCar(array $data): Car
    {
        return Car::create($data);
    }

    /**
     * Find a car by its model instance and load relations.
     *
     * @param Car $car
     * @return Car
     */
    public function findCar(Car $car): Car
    {
        return $car->load(["car_made", "customer"]);
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
        $car->fill($data)->save();
        return $car;
    }

    /**
     * Delete a car.
     *
     * @param Car $car
     * @return bool|null
     */
    public function deleteCar(Car $car): ?bool
    {
        return $car->delete();
    }
}
