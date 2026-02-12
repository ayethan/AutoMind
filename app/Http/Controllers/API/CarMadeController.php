<?php

namespace App\Http\Controllers\API;

use App\CarMade;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarMadeResource;

class CarMadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car_mades = CarMade::paginate(Helpers::getValue('default-pagination'));
        return (CarMadeResource::collection($car_mades));
    }

    public function all() {
        $car_mades = CarMade::all();
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
        $car_made = CarMade::create($request->all());
        return (new CarMadeResource($car_made))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CarMade  $car_made
     * @return \Illuminate\Http\Response
     */
    public function show(CarMade $car_made)
    {
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

        $car_made->fill($request->all())->save();
        return (new CarMadeResource($car_made))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CarMade  $car_made
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarMade $car_made)
    {
        $car_made->delete();
        return (new CarMadeResource($car_made))->response()->setStatusCode(202);
    }
}
