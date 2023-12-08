<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use OpenApi\Annotations as OA;

class RegisterController extends Controller
{

    public function __construct( private readonly UserService $userService)
    {}


    /**
     * @OA\Post(
     *     path="/api/register",
     *     description="Register user",
     *     tags={"Users"},
     *     summary="Register user",
     *
     *      @OA\RequestBody(
     *       required=true,
     *       description="Pass params",
     *       @OA\JsonContent(
     *          required={"name", "email", "password"},
     *                @OA\Property(property="name", type="string"),
     *                @OA\Property(property="password", type="string"),
     *                @OA\Property(property="email", type="string"),
     *           )
     *      ),
     *
     *     @OA\Response(
     *          response="201",
     *          description="success",
     *          @OA\JsonContent(
     *           @OA\Property(property="auth", type="array",
     *                @OA\Items(
     *                      @OA\Property(property="status", type="integer"),
     *                      @OA\Property(property="authorization", type="array",
     *                        @OA\Items(
     *                            @OA\Property(property="token", type="string"),
     *                            @OA\Property(property="type", type="string", example="Bearer"),
     *                         )
     *                      )
     *                  )
     *              )
     *       )
     *     )
     *
     * )
     */
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $user  = $this->userService->create($request);
        $token = $user->createToken($user->email, ['*'], Carbon::now()->addDays(2))->plainTextToken;

        return response()->json( [
            'status'        => 'success',
            'authorization' => [
                'token' => $token,
                'type'  => 'Bearer',
            ]
        ], Response::HTTP_CREATED );

    }

}
