<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(Helpers::getValue('default-pagination'));
        return (CategoryResource::collection($categories));
    }

    /**
     * Display all listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all() 
    {
        $categories = Category::with(["sub_categories"])->get();
        return (CategoryResource::collection($categories));
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
            DB::beginTransaction();
            $category = Category::create($request->all());
            if(is_array($request->sub_categories)) {
                $category->sub_categories()->createMany($request->sub_categories);
            }
            DB::commit();
            return (new CategoryResource($category));
        } catch(Exception $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return (new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            "name" => "required"
        ]);

        $category->fill($request->all())->save();
        return (new CategoryResource($category))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return (new CategoryResource($category))->response()->setStatusCode(202);
    }
}
