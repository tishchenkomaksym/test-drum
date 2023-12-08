<?php
declare(strict_types=1);
namespace App\Services;

use App\Http\Requests\SortTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * @param TaskRequest $request
     * @return Task
     */
    public function createTask(TaskRequest $request): Task
    {
        $task = Task::make([
            'user_id' => auth()->id(),
            'status' => 'todo',
            'priority' => $request->priority,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if (isset($request->task_id)) {
            $parent = Task::find($request->task_id);
            $task->appendToNode($parent)->save();
        } else {
            $task->save();
        }

        return $task;
    }

    /**
     * @param Task $task
     * @param TaskRequest $request
     * @return bool
     */
    public function updateTask(Task $task, TaskRequest $request): bool
    {
        return $task->update([
            'priority' => $request->priority,
            'title' => $request->title,
            'description' => $request->description,
        ]);
    }

    /**
     * @param Task $task
     * @return JsonResponse
     */
    public function updateTaskComplete(Task $task): JsonResponse
    {
        foreach ($task->children()->get() as $child) {
            if ($child->status != 'done') {
                return response()->json([
                    'error' => 'Child task not done'
                ], Response::HTTP_FORBIDDEN);
            }
        }

        $task->update([
            'status' => 'done',
            'completed_at' => now()
        ]);

        return response()->json([
            'message' => 'Task completed successfully',
            'task' => $task
        ]);
    }

    /**
     * @param SortTaskRequest $request
     * @return LengthAwarePaginator
     */
    public function sortTasks(SortTaskRequest $request): LengthAwarePaginator
    {
        $tasks = Task::where('user_id', auth()->id());

        if ($request->has('status')) {
            $tasks->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $tasks->where('priority', $request->priority);
        }

        if ($request->has('title')) {
            $tasks->whereFullText(['title', 'description'], $request->title);
        }

        if ($request->has('description')) {
            $tasks->whereFullText(['title', 'description'], $request->description);
        }

        if ($request->has('sortByCreatedAt')) {
            $tasks->orderBy('created_at', strtoupper($request->sortByCreatedAt));
        }

        if ($request->has('sortByCompletedAt')) {
            $tasks->orderBy('completed_at', strtoupper($request->sortByCompletedAt));
        }

        if ($request->has('sortByPriority')) {
            $tasks->orderBy('priority', strtoupper($request->sortByPriority));
        }

        return $tasks->withDepth()->paginate(30);
    }
}
