<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use OpenApi\Annotations as OA;

class LoginController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     description="Login user",
     *     tags={"Users"},
     *     summary="Login user",
     *
     *      @OA\RequestBody(
     *       required=true,
     *       description="Pass params",
     *       @OA\JsonContent(
     *          required={"password"},
     *                @OA\Property(property="email", type="string"),
     *                @OA\Property(property="password", type="string"),
     *           )
     *      ),
     *
     *     @OA\Response(
     *          response="200",
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
     *                      ),
     *                  )
     *              )
     *       )
     *     ),
     *
     *     @OA\Response(
     *          response="400",
     *          description="Invalid credentials"
     *     ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
        );

        if ($authenticate) {
            $user = User::where('email', $request['email'])->first();
            $token = $user->createToken( $user->email, ['*'], Carbon::now()->addDays(2))->plainTextToken;

            return response()->json([
                'status'        => 'success',
                'authorization' => [
                    'token' => $token,
                    'type'  => 'Bearer',
                ]
            ], Response::HTTP_OK );
        }


        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], Response::HTTP_UNAUTHORIZED);

    }

}
