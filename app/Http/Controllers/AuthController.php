<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\User;
use Twilio\Rest\Verify;
use Twilio\Rest\Client;
use Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','sendOTP','verifyOTP','sendOTPlogin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        error_log('in login');
    	$validator = Validator::make($request->all(), [
            
            
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        error_log('in register');
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'date_of_birth' => 'required',
            'mobile_number' => 'required',
            'gender' => 'required',
            'state' => 'required',
            'city' => 'required',
            'district' => 'required',
            'school' => 'required',
            'bio' => 'required',
            'class' => 'required',
            'address' => 'required',
            'school_board' => 'required',
            'facebook_link' => 'required',
            'instagram_link' => 'required',
            'interest' => 'required',
            'status' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required',
            
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function sendOTP(Request $request) {
        error_log('in sendotp');
        $phone_number = $request->mobile_number;
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        
        try {
            $twilio->verify->v2->services($twilio_verify_sid)
                ->verifications
                ->create($phone_number, "sms");
        } catch (\Exception $ex) {
            return response()->json(['error' => "OTP cannot be sent",$ex] );
        }

        return response()->json(['success' => "OTP has been sent"] );

    }

    public function verifyOTP(Request $request) {


        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($request->otp_code, array('to' => $request->mobile_number));

        if ($verification->valid) {

            $user = User::where('mobile_number', $request->mobile_number)->first();

            if ($user) {
                $insertOtp = User::where('mobile_number',$request->mobile_number)
                                    ->update(['firebase_id'=>$request->otp_code]);
            }

            return response()->json(['success' => "OTP has been verified", 'phone_number' => $request->mobile_number, 'code' => $request->otp_code] );
        }
        return response()->json(['phone_number' => $request->mobile_number, 'error' => 'Invalid verification code entered!', 'code' => $request->otp_code] );

    }

    public function sendOTPlogin(Request $request) {

        $phone_number = $request->mobile_number;
        $user = User::where('mobile_number', $request->mobile_number)->first();
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        if($user) {
            $twilio = new Client($twilio_sid, $token);

            try {
                $twilio->verify->v2->services($twilio_verify_sid)
                    ->verifications
                    ->create($phone_number, "sms");
            } catch (\Exception $ex) {
                return response()->json(['error' => "OTP cannot be sent",$ex] );
            }
    
            return response()->json(['success' => "OTP has been sent"] );
        }

        return response()->json(['error' => "mobilenumber not register", 'phone_number' => $request->mobile_number] );
        

    }


}