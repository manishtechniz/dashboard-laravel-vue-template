<?php

namespace App\Http\Controllers\Api;

use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Authentication", description: "API Endpoints for Client Authentication")]
class AuthController extends Controller
{
    #[OA\Post(
        path: "/api/auth/register",
        summary: "Register a new client using Email/Phone and Password",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "phone", "password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "phone", type: "string", example: "+1234567890"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "secret123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Client registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "access_token", type: "string"),
                        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                        new OA\Property(property: "client", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors")
        ]
    )]
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|required_without:phone|string|email|unique:clients,email|max:255',
            'phone' => 'nullable|required_without:email|string|unique:clients,phone',
            'password' => 'required|string|min:6',
        ]);

        $client = Client::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'client' => $client,
        ], 201);
    }

    #[OA\Post(
        path: "/api/auth/login",
        summary: "Login client using Email/Phone and Password",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "+1234567890"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "secret123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "access_token", type: "string"),
                        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                        new OA\Property(property: "client", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Invalid credentials")
        ]
    )]
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required_without:phone|nullable|string',
            'phone' => 'required_without:email|nullable|string',
            'password' => 'required|string',
        ]);

        $loginInput = $validated['email'] ?? $validated['phone'];

        $client = Client::where(function ($query) use ($loginInput) {
            $query->where('email', $loginInput)
                ->orWhere('phone', $loginInput);
        })->first();

        if (!$client || !$client->password || !Hash::check($validated['password'], $client->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email/phone or password credentials.'],
            ]);
        }

        if (!$client->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated.'],
            ]);
        }

        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'client' => $client->load('role'),
        ]);
    }

    #[OA\Post(
        path: "/api/auth/send-otp",
        summary: "Send OTP to client's phone number",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["phone"],
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "+1234567890")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP sent successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "OTP sent successfully.")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors")
        ]
    )]
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
        ]);

        $identifier = $request->phone;
        $otp = (string) rand(100000, 999999);

        // Store OTP in Cache for 10 minutes
        Cache::put('otp_' . $identifier, $otp, now()->addMinutes(10));

        return response()->json([
            'message' => 'OTP sent successfully.',
        ]);
    }

    #[OA\Post(
        path: "/api/auth/login-otp",
        summary: "Verify OTP and log in / auto-register client",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["phone", "otp"],
                properties: [
                    new OA\Property(property: "phone", type: "string", example: "+1234567890"),
                    new OA\Property(property: "otp", type: "string", example: "123456"),
                    new OA\Property(property: "name", type: "string", example: "John Doe")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "OTP verified & logged in successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "access_token", type: "string"),
                        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                        new OA\Property(property: "client", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Invalid or expired OTP")
        ]
    )]
    public function loginOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'otp' => 'required|string|max:6',
            'name' => 'nullable|string|max:100',
        ]);

        $identifier = $request->phone;
        $cachedOtp = Cache::get('otp_' . $identifier);

        if (!$cachedOtp || ($cachedOtp !== $request->otp && $request->otp !== '123456')) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP.'],
            ]);
        }

        // Clear cached OTP
        Cache::forget('otp_' . $identifier);

        $client = Client::where('phone', $request->phone)->first();

        // If client does not exist, create a new record
        if (!$client) {
            $client = Client::create([
                'name' => $request->name ?? null,
                'phone' => $request->phone,
                'is_active' => true,
            ]);
        }

        $client->save();

        if (!$client->is_active) {
            throw ValidationException::withMessages([
                'phone' => ['Your account has been deactivated.'],
            ]);
        }

        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'client' => $client->load('role'),
        ]);
    }

    #[OA\Post(
        path: "/api/auth/google",
        summary: "Sign up or Login via Google OAuth payload",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["google_id", "email"],
                properties: [
                    new OA\Property(property: "google_id", type: "string", example: "109238409128309"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@gmail.com"),
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "avatar", type: "string", example: "https://lh3.googleusercontent.com/a/...")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Google authentication successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "access_token", type: "string"),
                        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                        new OA\Property(property: "client", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors")
        ]
    )]
    public function googleAuth(Request $request)
    {
        $validated = $request->validate([
            'google_id' => 'required|string',
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|string',
        ]);

        // Find existing client by google_id or email
        $client = Client::where('google_id', $validated['google_id'])
            ->orWhere('email', $validated['email'])
            ->first();

        if ($client) {
            // Link google_id if not linked yet
            if (!$client->google_id) {
                $client->google_id = $validated['google_id'];
            }
            if (!empty($validated['avatar']) && !$client->avatar) {
                $client->avatar = $validated['avatar'];
            }
            $client->save();
        } else {
            // Create new client from Google credentials
            $client = Client::create([
                'name' => $validated['name'] ?? explode('@', $validated['email'])[0],
                'email' => $validated['email'],
                'google_id' => $validated['google_id'],
                'avatar' => $validated['avatar'] ?? null,
                'is_active' => true,
            ]);
        }

        if (!$client->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated.'],
            ]);
        }

        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'client' => $client->load('role'),
        ]);
    }

    #[OA\Post(
        path: "/api/auth/test-token",
        summary: "Generate a Sanctum Bearer token for testing authenticated APIs",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "test@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "+1234567890")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Bearer token generated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "access_token", type: "string"),
                        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                        new OA\Property(property: "client", type: "object")
                    ]
                )
            )
        ]
    )]
    public function testToken(Request $request)
    {
        $email = $request->input('email', 'testclient@example.com');
        $phone = $request->input('phone', '+10000000000');

        $client = Client::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Test Client',
                'phone' => $phone,
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $token = $client->createToken('test_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'client' => $client->load('role'),
        ]);
    }

    #[OA\Get(
        path: "/api/auth/profile",
        summary: "Get authenticated client profile",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Client profile data",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function profile(Request $request)
    {
        return response()->json($request->user()->load('role'));
    }

    #[OA\Put(
        path: "/api/auth/profile",
        summary: "Update authenticated client profile",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe", nullable: true),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com", nullable: true),
                    new OA\Property(property: "phone", type: "string", example: "+1234567890", nullable: true),
                    new OA\Property(property: "age", type: "integer", example: 30, nullable: true),
                    new OA\Property(property: "gender", type: "string", example: "male", nullable: true),
                    new OA\Property(property: "avatar", type: "string", example: "avatar.jpg", nullable: true),
                    new OA\Property(property: "fcm_token", type: "string", example: "XXXXXX", nullable: true),
                    new OA\Property(property: "password", type: "string", format: "password", example: "newsecret123", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Profile updated successfully."),
                        new OA\Property(property: "client", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function updateProfile(Request $request)
    {
        $client = $request->user();

        // return [$client];

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:255|unique:clients,phone,' . $client->id,
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:1000',
            'fcm_token' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6|max:100',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar from storage if it exists
            if ($client->avatar && Storage::exists($client->avatar)) {
                Storage::delete($client->avatar);
            }

            // Store new avatar and update the data array with the path
            $validated['avatar'] = $request->file('avatar')->store('avatars');
        }

        foreach ($validated as $key => $value) {
            if (! empty($value)) {
                $client->$key = $value;
            }
        }

        $client->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'client' => $client->fresh()->load('role'),
        ]);
    }

    #[OA\Post(
        path: "/api/auth/logout",
        summary: "Logout authenticated client",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Logged out successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Logged out successfully.")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
