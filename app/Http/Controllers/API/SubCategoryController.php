<?php

namespace App\Http\Controllers\API;

use App\SubCategory;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoryResource;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_categories = SubCategory::with(["category"])->paginate(Helpers::getValue('default-pagination'));
        return (SubCategoryResource::collection($sub_categories));
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
            "name" => "required"
        ]);

        $sub_category = SubCategory::create($request->all());
        return (new SubCategoryResource($sub_category));
    }

    /**
     * Display the specified resource.
     *
     * @param   App\SubCategory $sub_category
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $sub_category)
    {
        $sub_category->load(["category"]);
        return (new SubCategoryResource($sub_category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   App\SubCategory $sub_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $sub_category)
    {
        $this->validate($request, [
            "name" => "required"  
        ]);
        $sub_category->fill($request->all())->save();

        return (new SubCategoryResource($sub_category))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   App\SubCategory $sub_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $sub_category)
    {
        $sub_category->delete();
        return (new SubCategoryResource($sub_category))->response()->setStatusCode(202);
    }
}
