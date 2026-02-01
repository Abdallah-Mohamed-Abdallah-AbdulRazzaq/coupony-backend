<?php

namespace App\Application\Http\Controllers\API\V1\Auth;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\registerUserRequest;
use App\Application\Http\Resources\UserResource;
use App\Domain\Store\Models\Store;
use App\Domain\Store\Models\StoreVerification;
use App\Domain\User\Actions\RegisterUser;
use App\Domain\User\DTOs\UserData;
use App\Domain\User\Enums\OtpChannels;
use App\Domain\User\Enums\OtpPurposes;
use App\Domain\User\Listeners\SendWelcomeEmail;
use App\Domain\User\Models\User;
use App\Domain\User\Services\AuthenticationService;
use App\Domain\User\Services\OtpService;

class RegisterController extends Controller
{
    public function __construct(
        private RegisterUser $registerUser,
        private AuthenticationService $authService,
        private OtpService $otpService
    ) {
    }

    public function __invoke(registerUserRequest $request)
    {
        $context = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'currency' => $request->header('X-Currency', 'USD'),
            'device_name' => $request->input('device_name'),
            // 'referral_code' => $request->input('referral_code'),
        ];



        //         return response()->json([
        //             'message' => 'Seller upgrade request submitted. Verification pending.',
        //             'data' => [
        //                 'user' => new UserResource($existing->load('profile')),
        //                 'next' => [
        //                     'url' => route('store.create'),   // API endpoint to call
        //                     'method' => 'POST',               // HTTP method
        //                     'fields' => [
        //                         'name' => 'string',
        //                         'description' => 'string',
        //                         'subscription_tier' => 'free|basic|premium|enterprise',
        //                         'phone' => 'string',
        //                         'tax_id' => 'string',
        //                         'logo' => 'file',
        //                         'banner' => 'file',
        //                         'verification_docs' => [
        //                             'commercial_register' => 'file',
        //                             'tax_card' => 'file',
        //                             'id_card' => 'file',
        //                         ]
        //                     ]
        //                 ],
        //             ],
        //         ], 200);
        //     }
        // }
        $user = $this->registerUser->excute(
            UserData::fromRequest($request),
            context: $context
        );

        // Generate and send OTP
        try {
            $otp = $this->otpService->generateAndSend(
                user: $user,
                purpose: OtpPurposes::VERIFY_EMAIL->value,
                channel: OtpChannels::EMAIL->value
            );

            return response()->json([
                'message' => 'Registration successful. Please check your email for verification.',
                'data' => [
                    'expires_at' => $otp->expires_at->toIso8601String(),
                    'expires_in_minutes' => now()->diffInMinutes($otp->expires_at, false),
                    'channel' => OtpChannels::EMAIL->value,
                    'masked_recipient' => $this->otpService->maskRecipient($otp->phone_or_email, OtpChannels::EMAIL->value),
                ],
            ], 200);

        } catch (\Exception $e) {
            \Log::error('OTP Send Error', [
                'user_id' => $user->id,
                'purpose' => OtpPurposes::VERIFY_EMAIL->value,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }
    }
}
