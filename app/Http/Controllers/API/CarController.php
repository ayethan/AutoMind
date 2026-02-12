<?php

namespace App\Http\Controllers\API;

use App\Car;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::with("car_made", "customer")
        ->paginate(Helpers::getValue('default-pagination'));
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

        $car = Car::create($request->all());
        return (new CarResource($car))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Car  Car $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        $car->load(["car_made", "customer"]);
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

        $car->fill($request->all())->save();
        return (new CarResource($car))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Car  Car $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return (new CarResource($car))->response()->setStatusCode(202);
    }
}
