<?php

namespace App\Http\Controllers\API;

use App\Car;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Controller;
use App\Services\CarService;
use Exception;

class CarController extends Controller
{
    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = $this->carService->paginateCars();
        return (CarResource::collection($cars));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateddata = $request->validate([
            "car_no" => "required|unique:cars"
        ]);

        try {
            $car = $this->carService->createCar($request->all());
            return (new CarResource($car))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Car  Car $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        $car = $this->carService->findCar($car);
        return (new CarResource($car));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Car  Car $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
        $this->validate($request, [
            "car_no" => "required|unique:cars,car_no,".$car->id
        ]);

        try {
            $car = $this->carService->updateCar($car, $request->all());
            return (new CarResource($car))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Car  Car $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        try {
            $this->carService->deleteCar($car);
            return (new CarResource($car))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
