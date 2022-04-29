<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
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
        //Criterios de validación del registro
        $dataValidated = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:255',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($dataValidated->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $dataValidated->errors()
            ], 422);
        }

        $validatedData = $dataValidated->validated();
        $newTask = Task::create($validatedData);

        return response()->json([
            "message" => "success",
            "newTask" => $newTask
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //Validacion de los datos
        $dataValidated = Validator::make($request->all(), [
            'name' => 'string|min:5|max:255',
            'category_id' => 'integer|exists:categories,id',
        ]);

        if ($dataValidated->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $dataValidated->errors()
            ], 422);
        }

        $validatedData = $dataValidated->validated();
        $task->update($validatedData);

        return response()->json([
            "status" => "success",
            "message" => "Task updated successfully",
            "task" => $task
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            "status" => "success",
            "message" => "Task deleted successfully",
            "task" => $task
        ], 200);
    }
}
