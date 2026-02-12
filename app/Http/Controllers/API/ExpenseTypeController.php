<?php

namespace App\Http\Controllers\API;

use App\ExpenseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseTypeResource;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expense_types = ExpenseType::paginate(config('tinyerp.default-pagination'));
        return ExpenseTypeResource::collection($expense_types);
    }

    public function all() {
        $expense_types = ExpenseType::all();
        return ExpenseTypeResource::collection($expense_types);
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

        $expense_type = ExpenseType::create($request->all());
        return (new ExpenseTypeResource($expense_type))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense_type
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseType $expense_type)
    {
        return (new ExpenseTypeResource($expense_type));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseType $expense_type)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $expense_type->fill($request->all())->save();
        return (new ExpenseTypeResource($expense_type))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseType $expense_type)
    {
        $expense_type->delete();
        return (new ExpenseTypeResource($expense_type))->response()->setStatusCode(202);
    }
}
