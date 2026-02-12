<?php

namespace App\Http\Controllers\API;

use App\CarMade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarMadeResource;
use App\Services\CarMadeService;
use Exception;

class CarMadeController extends Controller
{
    protected $carMadeService;

    public function __construct(CarMadeService $carMadeService)
    {
        $this->carMadeService = $carMadeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car_mades = $this->carMadeService->paginateCarMades();
        return (CarMadeResource::collection($car_mades));
    }

    public function all() {
        $car_mades = $this->carMadeService->getAllCarMades();
        return (CarMadeResource::collection($car_mades));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|unique:car_mades"
        ]);
        try {
            $car_made = $this->carMadeService->createCarMade($request->all());
            return (new CarMadeResource($car_made))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CarMade  $car_made
     * @return \Illuminate\Http\Response
     */
    public function show(CarMade $car_made)
    {
        $car_made = $this->carMadeService->findCarMade($car_made);
        return (new CarMadeResource($car_made));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CarMade  $car_made
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarMade $car_made)
    {
        $this->validate($request, [
            "name" => "required|unique:car_mades,name,".$car_made->id
        ]);

        try {
            $car_made = $this->carMadeService->updateCarMade($car_made, $request->all());
            return (new CarMadeResource($car_made))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CarMade  $car_made
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarMade $car_made)
    {
        try {
            $this->carMadeService->deleteCarMade($car_made);
            return (new CarMadeResource($car_made))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
