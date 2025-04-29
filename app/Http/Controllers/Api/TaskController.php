<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return 1;

        $task = Task::latest()->get();
        return response()->json([
            'status' => 1,
            'data' => $task,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => (object) [],
            ], 422);
        }

        $task = Task::create($validator->validated());

        return response()->json([
            'status' => 1,
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'status' => 0,
                'message' => 'Task not found.',
                'data' => (object) [],
            ], 404);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Task fetch successfully.',
            'data' => $task,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first(),
                'data' => (object) [],
            ], 422);
        }

        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Task not found.',
                    'data' => (object) [],
                ], 404);
            }

            $task->update($validator->validated());
            return response()->json([
                'status' => 1,
                'message' => 'Task updated successfully.',
                'data' => $task,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'status' => 0,
                'message' => 'Task not found.',
                'data' => (object) [],
            ], 404);
        }
        $task->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Task deleted successfully.',
            'data' => $task,
        ], 200);
    }
}
