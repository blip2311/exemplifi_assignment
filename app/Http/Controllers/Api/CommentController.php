<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class CommentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(string $taskId)
    {
        // Check if the task exists
        $task = Task::findOrFail($taskId);
        if (! $task) {
            return response()->json([
                'error' => 'Task not found'
            ], 404);
        }

        // Get the paginated comments associated with the task
        $comments = $task->comments()->paginate(10);
        
        // Return the comments associated with the task
        return response()->json($comments, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $taskId)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'author_name' => 'required|string|max:255'
        ]);

        // Check if the task exists
        $task = Task::findOrFail($taskId);
        if (! $task) {
            return response()->json([
                'error' => 'Task not found'
            ], 404);
        }

        // Store the new comment associated with the task
        $comment = $task->comments()->create($validated);

        // Return a response with the created comment
        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
