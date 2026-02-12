<?php

namespace App\Http\Controllers\API;

use App\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoryResource;
use App\Services\SubCategoryService; // New import
use Exception;

class SubCategoryController extends Controller
{
    protected $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_categories = $this->subCategoryService->paginateSubCategories();
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

        try {
            $sub_category = $this->subCategoryService->createSubCategory($request->all());
            return (new SubCategoryResource($sub_category));
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param   App\SubCategory $sub_category
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $sub_category)
    {
        $sub_category = $this->subCategoryService->findSubCategory($sub_category);
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
        try {
            $sub_category = $this->subCategoryService->updateSubCategory($sub_category, $request->all());
            return (new SubCategoryResource($sub_category))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   App\SubCategory $sub_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $sub_category)
    {
        try {
            $this->subCategoryService->deleteSubCategory($sub_category);
            return (new SubCategoryResource($sub_category))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
