<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SortTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TaskController extends Controller
{
    public function __construct(public TaskService $taskService)
    {}

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     description="Getting tasks",
     *     tags={"Task"},
     *     summary="tasks",
     *
     *
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="tasks", type="array",
     *                @OA\Items(
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="priority", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="copmleted_at", type="datetime"),
     *                 @OA\Property(property="created_at", type="datetime"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                 @OA\Property(property="depth", type="integer"),
     *                  )
     *              )
     *       )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'tasks' => Task::where('user_id', auth()->id())->defaultOrder()->withDepth()->paginate(30)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/task/sort",
     *     description="Sort tasks",
     *     tags={"Task"},
     *     summary="Sort task",
     *
     *      @OA\RequestBody(
     *       required=true,
     *       description="Pass params",
     *       @OA\JsonContent(
     *                @OA\Property(property="status", type="string"),
     *                @OA\Property(property="priority", type="integer"),
     *                @OA\Property(property="title", type="string"),
     *                @OA\Property(property="description", type="string"),
     *                @OA\Property(property="copmleted_at", type="datetime"),
     *                @OA\Property(property="created_at", type="datetime"),
     *                @OA\Property(property="sortByCreatedAt", type="string"),
     *                @OA\Property(property="sortByCompletedAt", type="string"),
     *                @OA\Property(property="sortByPriority", type="string"),
     *           )
     *      ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="tasks", type="array",
     *                @OA\Items(
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="priority", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="copmleted_at", type="datetime"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                  @OA\Property(property="depth", type="integer"),
     *                  )
     *              )
     *       )
     *     ),
     *
     *     @OA\Response(
     *          response="403",
     *          description="You are not authorized to access this page."
     *     ),
     * )
     */
    public function sortTask(SortTaskRequest $request): JsonResponse
    {
        $tasks = $this->taskService->sortTasks($request);

        return response()->json([
            'tasks' => $tasks
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/task/{id}",
     *     description="Get task",
     *     tags={"Task"},
     *     summary="task",
     *
     *
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="tasks", type="array",
     *                @OA\Items(
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="priority", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="copmleted_at", type="datetime"),
     *                 @OA\Property(property="created_at", type="datetime"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                 @OA\Property(property="depth", type="integer"),
     *                  )
     *              )
     *       )
     *     )
     * )
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'task' => $task
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/task",
     *     description="Sort tasks",
     *     tags={"Task"},
     *     summary="Sort task",
     *
     *      @OA\RequestBody(
     *       required=true,
     *       description="Pass params",
     *       @OA\JsonContent(
     *                @OA\Property(property="priority", type="integer"),
     *                @OA\Property(property="title", type="string"),
     *                @OA\Property(property="description", type="string"),
     *                @OA\Property(property="task_id", type="integer"),
     *           )
     *      ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="tasks", type="array",
     *                @OA\Items(
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="priority", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="copmleted_at", type="datetime"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                  @OA\Property(property="depth", type="integer"),
     *                  )
     *              )
     *       )
     *     )
     * )
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ]);
    }



    /**
     * @OA\Put(
     *     path="/api/task",
     *     description="Update task",
     *     tags={"Task"},
     *     summary="Update task",
     *
     *      @OA\RequestBody(
     *       required=true,
     *       description="Pass params",
     *       @OA\JsonContent(
     *                @OA\Property(property="priority", type="integer"),
     *                @OA\Property(property="title", type="string"),
     *                @OA\Property(property="description", type="string"),
     *                @OA\Property(property="task_id", type="integer"),
     *           )
     *      ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="tasks", type="array",
     *                @OA\Items(
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="priority", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="copmleted_at", type="datetime"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                  @OA\Property(property="depth", type="integer"),
     *                  )
     *              )
     *       )
     *     ),
     *      @OA\Response(
     *           response="403",
     *           description="This task already done"
     *      ),
     * )
     */
    public function update(Task $task, TaskRequest $request): JsonResponse
    {
        if ($task->status === 'done') {
            return response()->json([
                'error' => 'Task already done'
            ], Response::HTTP_FORBIDDEN);
        }
        $this->taskService->updateTask($task, $request);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/task/{id}/complete",
     *     description="Complete task",
     *     tags={"Task"},
     *     summary="Complete task",
     *
     *      @OA\RequestBody(
     *       required=true,
     *       description="Pass params",
     *       @OA\JsonContent(
     *                @OA\Property(property="priority", type="integer"),
     *                @OA\Property(property="title", type="string"),
     *                @OA\Property(property="description", type="string"),
     *                @OA\Property(property="task_id", type="integer"),
     *           )
     *      ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="tasks", type="array",
     *                @OA\Items(
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="priority", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="copmleted_at", type="datetime"),
     *                 @OA\Property(property="parent_id", type="integer"),
     *                  @OA\Property(property="depth", type="integer"),
     *                  )
     *              )
     *       )
     *     ),
     *           @OA\Response(
     *            response="403",
     *            description="Child task not done"
     *       ),
     *
     * )
     */
    public function completeTask(Task $task): JsonResponse
    {
        return $this->taskService->updateTaskComplete($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/task/{id}",
     *     description="Delete task",
     *     tags={"Task"},
     *     summary="Delete task",
     *
     *     @OA\Parameter(
     *        description="TaskId",
     *        in="path",
     *        name="TaskId",
     *        required=true,
     *        example="1, 2",
     *        @OA\Schema(type="string")
     *    ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="success"
     *     )
     * )
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(['success' => 'deleted'], Response::HTTP_OK);
    }
}
