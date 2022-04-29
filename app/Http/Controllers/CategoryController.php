<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::with('tasks')->get();
        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Criterios de validación
        $dataValidated = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:5|max:255',
        ]);

        if ($dataValidated->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $dataValidated->errors()
            ], 422);
        }

        $validatedData = $dataValidated->validated();
        $newCategory = Category::create($validatedData);
        return response()->json([
            "status" => "success",
            "newCategory" => $newCategory
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
        return response()->json([
            'status' => 'success',
            'category' => $category::with('tasks')->whereIn('id', $category)->get(),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

        $dataValidated = Validator::make($request->all(), [
            'name' => 'string|min:3|max:255',
            'description' => 'string|min:5|max:255',
        ]);

        if ($dataValidated->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $dataValidated->errors()
            ], 422);
        }

        $validatedData = $dataValidated->validated();
        $category->update($validatedData);
        return response()->json([
            'status' => 'success',
            "message" => "Category updated successfully",
            'category' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        $category->tasks()->each(function ($task) {
            $task->delete();
        });

        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
            'category' => $category
        ], 200);
    }
}
