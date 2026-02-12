<?php

namespace App\Repositories;

use App\CarMade;
use App\Utils\Helpers;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarMadeRepository
{
    /**
     * Get all car makes with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateCarMades(): LengthAwarePaginator
    {
        return CarMade::paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Get all car makes.
     *
     * @return Collection
     */
    public function getAllCarMades(): Collection
    {
        return CarMade::all();
    }

    /**
     * Create a new car make.
     *
     * @param array $data
     * @return CarMade
     */
    public function createCarMade(array $data): CarMade
    {
        return CarMade::create($data);
    }

    /**
     * Find a car make by its model instance.
     *
     * @param CarMade $carMade
     * @return CarMade
     */
    public function findCarMade(CarMade $carMade): CarMade
    {
        return $carMade;
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
        $carMade->fill($data)->save();
        return $carMade;
    }

    /**
     * Delete a car make.
     *
     * @param CarMade $carMade
     * @return bool|null
     */
    public function deleteCarMade(CarMade $carMade): ?bool
    {
        return $carMade->delete();
    }
}
