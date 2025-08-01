<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Display a listing of the Tasks based on filters.
     * @return \Illuminate\Http\JsonResponse JSON response containing the paginated list of tasks.
    */
    public function index()
    {
        //check if filter status is provided
        $status = request()->query('status');
        
        //Check if due date is provided 
        $dueDateFrom = request()->query('due_date_from');
        $dueDateTo = request()->query('due_date_to');
        
        // Build the query to fetch tasks
        $query = Task::query();
        
        // If status filter is provided, apply it to the query
        if ($status) {
            $query->where('status', $status);
        }
        
        // If due date range is provided, apply it to the query
        if ($dueDateFrom && $dueDateTo) {
            $query->whereBetween('due_date', [$dueDateFrom, $dueDateTo]);
        } elseif ($dueDateFrom) {
            $query->where('due_date', '>=', $dueDateFrom);
        } elseif ($dueDateTo) {
            $query->where('due_date', '<=', $dueDateTo);
        }
        
        // Fetch the tasks with pagination
        $tasks = $query->paginate(10);
        
        // Return a response with the paginated tasks
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created Task in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress, completed',
            'due_date' => 'nullable|date'
        ]);
        
        // Create a new task using the validated data   
        $task = Task::create($validated);
        
        // Return a response with the created task
        return response()->json($task, 201);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validate the incoming request data
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,in-progress,completed',
            'due_date' => 'nullable|date'
        ]);
        
        // Find the task by ID
        $task = Task::findOrFail($id);
        
        // Check if the task exists
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        
        // Update the task with the validated data
        $task->update($validated);
        
        // Return a response with the updated task
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Find the task by ID
        $task = Task::findOrFail($id);
        
        // Check if the task exists
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }   
        
        // Delete the task
        $task->delete();
        
        // Return a response indicating successful deletion
        return response()->json(['message' => 'Task deleted successfully'], 204);
    }
}
