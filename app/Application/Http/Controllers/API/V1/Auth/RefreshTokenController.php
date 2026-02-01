<?php

namespace App\Application\Http\Controllers\API\V1\Auth;
use App\Application\Http\Controllers\Controller;
use App\Domain\User\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefreshTokenController extends Controller
{
    public function __construct(
        private AuthenticationService $authService
    ) {
    }

    /**
     * Refresh access token
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        try {
            $result = $this->authService->refreshToken(
                $request->input('refresh_token')
            );

            return response()->json([
                'message' => 'Token refreshed successfully',
                'data' => $result,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Invalid refresh token',
            ], 401);
        }
    }
}