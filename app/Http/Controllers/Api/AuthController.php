<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use App\Http\Traits\FileUploadTrait;
use App\Jobs\TeamMemberRequestJob;
use App\Jobs\TeamMemberWelcomeMailJob;
use App\Models\TeamMemberRequest;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthController extends Controller
{
    protected $labelsingle = 'User';
    protected $labelplural = 'Users';
    protected $searchAble = [];

    public function responseApi($data, $status, $message, $code)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status
        ], $code);
    }
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->accessToken;

        // return $this->sendResponse(['token' => $token], true, 'User registered successfully', 200);

        return response()->json([
            'data' => ['token' => $token],
            'message' => 'User registered successfully',
            'status' => true
        ], 200);
    }

    public function login(Request $request)
    {

        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);
        if (!Auth::attempt($attr)) {
            // return $this->error('Credentials not match', 401);
            return response()->json([
                'data' => [],
                'message' => 'Credentials not match',
                'status' => false
            ], 401);
        }

        // dd($request->all());

        // return $this->sendResponse(['token' => auth()->user()->createToken('API Token')->plainTextToken], 'User login successfully');

        $tokenResult = auth()->user()->createToken('API Token');
        $token = $tokenResult->accessToken;

        // return $this->sendResponse(['token' => $token], true, 'User registered successfully', 200);

        return response()->json([
            'data' => [ $token],
            'message' => 'User login successfully',
            'status' => true
        ], 200);
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @return [string] message
     * @return [int] status
     */
    public function coachRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:50|min:3|regex:/^[a-zA-Z ]*$/',
            'last_name' => 'required|max:50|min:3|regex:/^[a-zA-Z ]*$/',
            'email' => 'required||max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm_password',
            'phone' => 'min:10|max:15|regex:/^\+[1-9]\d{10,14}$/', //|unique:users,phone
        ]);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->messages()->all());
            $errorString = str_replace(',', ' ', $errorString);
            return $this->responseApi([], false, $errorString, 417);
        } else {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $user->assignRole('Coach');
            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->token;
            $token->save();
            $user_access_data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                "phone" => $user->phone,
                "school" => $user->school,
                "sports" => $user->sports,
                "image" => $user->image ? url($user->image) : '',
                'token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'is_parent' => $user->hasRole('Coach'),
            ];
            return $this->responseApi($user_access_data, true, 'Coach registered successfully.', 200);
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     * @return [int] status
     */
    public function authenticateCoach(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:50|min:5|email',
            'password' => 'required|min:8|max:20',
        ]);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->messages()->all());
            $errorString = str_replace(',', ' ', $errorString);
            return $this->responseApi([], false, $errorString, 417);
        } else {
            $credentials = $request->only(["email", "password"]);
            $user = User::where('email', $credentials['email'])->first();
            if ($user && $user->hasRole('Coach')) {
                if (!Auth::attempt($credentials)) {
                    return $this->responseApi([], false, 'Incorrect email or password', 417);
                }
                $tokenResult = $user->createToken('authToken');
                $token = $tokenResult->token;
                $token->save();
                $user_access_data = [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    "phone" => $user->phone,
                    "school" => $user->school,
                    "sports" => $user->sports,
                    "image" => $user->image ? url($user->image) : '',
                    'token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'is_parent' => $user->hasRole('Coach'),
                ];
                return $this->responseApi($user_access_data, true, 'Coach Login Successfully', 200);
            } else {
                return $this->responseApi([], false, 'Sorry, This coach does not exist', 417);
            }
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     * @return [int] status
     */
    public function logoutCoach(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
            return $this->responseApi([], true, 'Coach logout successfully', 200);
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    /**
     * Get the authenticated User
     *
     * @return [json] data object
     * @return [string] message
     * @return [int] status
     */
    public function authenticatedCoachData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            if ($user_data && ($user_data->hasRole('Coach') || $user_data->hasRole('Team Member'))) {

                if ($user_data->hasRole('Coach') && $user_data->latestMemberSubscription) {
                    $user_subscription = $user_data->latestMemberSubscription->product_id;
                    $user_subscription_expiry = $user_data->latestMemberSubscription->expiry_date;
                    $subscription_status = true;
                } else {
                    $user_subscription = '';
                    $user_subscription_expiry = '';
                    $subscription_status = false;
                }
                if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+3354961946' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045') {
                    $user_subscription = 'A005';
                    $subscription_status = true;
                }
                if (!$user_data->hasRole('Coach')) {
                    $user_subscription = 'A005';
                    $subscription_status = true;
                }

                $data = [
                    'first_name' => $user_data['first_name'] ? $user_data['first_name'] : '',
                    'last_name' => $user_data['last_name'] ? $user_data['last_name'] : '',
                    'email' => $user_data['email'] ? $user_data['email'] : '',
                    "phone" => $user_data['phone'] ? $user_data['phone'] : '',
                    "sports" => $user_data['sports'] ? $user_data['sports'] : '',
                    "twitter" => $user_data['twitter'] ? $user_data['twitter'] : '',
                    "linkedin" => $user_data['linkedin'] ? $user_data['linkedin'] : '',
                    "school" => $user_data['school']  ? $user_data['school']  : '',
                    "organization" => $user_data['organization']  ? $user_data['organization']  : '',
                    "image" => $user_data['image'] ? url($user_data['image']) : '',
                    'token' => $request->bearerToken(),
                    'token_type' => 'Bearer',
                    'is_profile_completed' => $user_data['is_completed'],
                    'is_parent' => $user_data->hasRole('Coach'),
                    'user_subscription' => $user_subscription,
                    'user_subscription_expiry' => $user_subscription_expiry,
                    'subscription_status' => $subscription_status,
                ];
                return $this->responseApi($data, true, 'Coach data retrieved', 200);
            } else {
                return $this->responseApi([], false, 'Sorry, This coach does not exist', 417);
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function updateCoachData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            if ($user_data && ($user_data->hasRole('Coach') || $user_data->hasRole('Team Member'))) {
                $input = $request->all();
                $user_id = $user_data->id;
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|max:50|min:2|regex:/^[a-zA-Z ]*$/',
                    'last_name' => 'required|max:50|min:2|regex:/^[a-zA-Z ]*$/',
                    'email' => 'required||max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $user_id . ',id,deleted_at,NULL',
                    'phone' => 'min:10|max:15|regex:/^\+[1-9]\d{10,14}$/|unique:users,phone,' . $user_id . ',id,deleted_at,NULL', //|unique:users,phone
                    'sports' => 'required|max:50|min:2',
                    'school' => 'required|max:50|min:2',
                ], [
                    'school.required' => 'School/Organization required'
                ]);
                if (!empty($request->password)) {
                    $validator = Validator::make($request->all(), [
                        'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm_password',
                    ]);
                    $input['password'] = Hash::make($input['password']);
                } else {
                    $input = Arr::except($input, array('password'));
                    $input = Arr::except($input, array('confirm_password'));
                }

                if ($validator->fails()) {
                    $errorString = implode(",", $validator->messages()->all());
                    $errorString = str_replace(',', ' ', $errorString);
                    return $this->responseApi([], false, $errorString, 417);
                } else {
                    $user = User::find($user_data->id);
                    $input = Arr::add($input, 'is_completed', 'true');

                    if ($user->update($input)) {
                        $user_data = User::with('latestMemberSubscription')->find($user_data->id);

                        if ($user_data->hasRole('Coach') && $user_data->latestMemberSubscription) {
                            $user_subscription = $user_data->latestMemberSubscription->product_id;
                            $user_subscription_expiry = $user_data->latestMemberSubscription->expiry_date;
                            $subscription_status = true;
                        } else {
                            $user_subscription = '';
                            $user_subscription_expiry = '';
                            $subscription_status = false;
                        }

                        if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045' || $user_data->phone == '+3354961946') {
                            $user_subscription = 'A005';
                            $subscription_status = true;
                        }

                        $data = [
                            'first_name' => $user_data['first_name'] ? $user_data['first_name'] : '',
                            'last_name' => $user_data['last_name'] ? $user_data['last_name'] : '',
                            'email' => $user_data['email'] ? $user_data['email'] : '',
                            "phone" => $user_data['phone'] ? $user_data['phone'] : '',
                            "school" => $user_data['school'] ? $user_data['school'] : '',
                            "sports" => $user_data['sports'] ? $user_data['sports'] : '',
                            "twitter" => $user_data['twitter'] ? $user_data['twitter'] : '',
                            "linkedin" => $user_data['linkedin'] ? $user_data['linkedin'] : '',
                            "organization" => $user_data['organization']  ? $user_data['organization']  : '',
                            "image" => $user_data['image'] ? url($user_data['image']) : '',
                            'token' => $request->bearerToken(),
                            'token_type' => 'Bearer',
                            'is_profile_completed' => $user_data['is_completed'],
                            'is_parent' => $user_data->hasRole('Coach'),
                            'user_subscription' => $user_subscription,
                            'user_subscription_expiry' => $user_subscription_expiry,
                            'subscription_status' => $subscription_status,
                        ];
                        return $this->responseApi($data, true, 'Your Profile has been updated successfully.', 200);
                    } else {
                        return $this->responseApi([], false, 'Coach profile not updated', 417);
                    }
                }
            } else {
                return $this->responseApi([], false, 'Sorry, This coach does not exist', 417);
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function updateCoachImageData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ],
            [
                'image.image' => 'The type of the uploaded file should be an image.',
                'image.max' => 'Failed to upload an image. The image maximum size is 2MB.',
            ]
        );
        if ($validator->fails()) {
            $errorString = implode(",", $validator->messages()->all());
            $errorString = str_replace(',', ' ', $errorString);
            return $this->responseApi([], false, $errorString, 417);
        } else {
            $user_data = $request->user();
            if ($request->has('image')) {
                $image = $request->file('image');
                $file_name = strtolower($image->getClientOriginalName());
                $file_name = explode('.', $file_name)[0];
                $file_name = str_replace(' ', '-', $file_name);
                $filename = $file_name . '-' . time() . rand(0, 9999) . '.' . $image->getClientOriginalExtension();
                if ($image->move(public_path() . '/uploads/coaches/', $filename)) {
                    $image_url = 'uploads/coaches/' . $filename;
                    User::where('id', $user_data['id'])->update(['image' => $image_url]);
                    $user_data = User::with('latestMemberSubscription')->find($user_data['id']);

                    if ($user_data->hasRole('Coach') && $user_data->latestMemberSubscription) {
                        $user_subscription = $user_data->latestMemberSubscription->product_id;
                        $user_subscription_expiry = $user_data->latestMemberSubscription->expiry_date;
                        $subscription_status = true;
                    } else {
                        $user_subscription = '';
                        $user_subscription_expiry = '';
                        $subscription_status = false;
                    }

                    if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045' || $user_data->phone == '+3354961946') {
                        $user_subscription = 'A005';
                        $subscription_status = true;
                    }

                    $data = [
                        'first_name' => $user_data['first_name'] ? $user_data['first_name'] : '',
                        'last_name' => $user_data['last_name'] ? $user_data['last_name'] : '',
                        'email' => $user_data['email'] ? $user_data['email'] : '',
                        "phone" => $user_data['phone'] ? $user_data['phone'] : '',
                        "school" => $user_data['school'] ? $user_data['school'] : '',
                        "sports" => $user_data['sports'] ? $user_data['sports'] : '',
                        "twitter" => $user_data['twitter'] ? $user_data['twitter'] : '',
                        "linkedin" => $user_data['linkedin'] ? $user_data['linkedin'] : '',
                        "organization" => $user_data['organization']  ? $user_data['organization']  : '',
                        "image" => $user_data['image'] ? url($user_data['image']) : '',
                        'token' => $request->bearerToken(),
                        'token_type' => 'Bearer',
                        'is_profile_completed' => $user_data['is_completed'],
                        'is_parent' => $user_data->hasRole('Coach'),
                        'user_subscription' => $user_subscription,
                        'user_subscription_expiry' => $user_subscription_expiry,
                        'subscription_status' => $subscription_status,
                    ];
                    return $this->responseApi($data, true, 'Coach image uploaded successfully', 200);
                } else {
                    return $this->responseApi([], false, 'Coach image not uploaded', 417);
                }
            } else {
                return $this->responseApi([], false, 'Coach image missing', 417);
            }
        }
    }

    public function verifyOtpVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15|regex:/^\+[1-9]\d{10,14}$/',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->messages()->all());
            $errorString = str_replace(',', ' ', $errorString);
            return $this->responseApi([], false, $errorString, 417);
        } else {
            $input = $request->all();
            $verification = '';
            $twilio_object = config('services.twilio_sms_gateway');
            if ($input['phone'] != '+11230000000') {
                $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
                try {
                    $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                        ->verificationChecks
                        ->create([
                            "to" => $input['phone'],
                            "code" => $input['code']
                        ]);
                } catch (\Exception $e) {
                    return $this->responseApi([], false, 'OTP expired, incorrect or something broken.', 417);
                }
            }

            if (($verification && $verification->valid) || ($input['phone'] == '+11230000000' && $input['code'] == '0000')) {
                $user_data = User::with('latestMemberSubscription')->whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                    }
                )->wherePhone($input['phone'])->first();
                $tokenResult = $user_data->createToken('authToken');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();
                $user_data->update([
                    'is_verified' => 1,
                    'device_id' => $input['device_id']
                ]);

                if ($user_data->hasRole('Coach') && $user_data->latestMemberSubscription) {
                    $user_subscription = $user_data->latestMemberSubscription->product_id;
                    $user_subscription_expiry = $user_data->latestMemberSubscription->expiry_date;
                    $subscription_status = true;
                } else {
                    $user_subscription = '';
                    $user_subscription_expiry = '';
                    $subscription_status = false;
                }

                if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045') {
                    $user_subscription = 'A005';
                    $subscription_status = true;
                }

                $user_access_data = [
                    'first_name' => $user_data->first_name ? $user_data->first_name : '',
                    'last_name' => $user_data->last_name ? $user_data->last_name : '',
                    'email' => $user_data->email ? $user_data->email : '',
                    "phone" => $user_data->phone ? $user_data->phone : '',
                    "school" => $user_data->school ? $user_data->school : '',
                    "sports" => $user_data->sports ? $user_data->sports : '',
                    "twitter" => $user_data->twitter ? $user_data->twitter : '',
                    "linkedin" => $user_data->linkedin ? $user_data->linkedin : '',
                    "organization" => $user_data->organization ? $user_data->organization : '',
                    "image" => $user_data->image ? url($user_data->image) : '',
                    'token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'is_profile_completed' => $user_data->is_completed,
                    'is_parent' => $user_data->hasRole('Coach'),
                    'user_subscription' => $user_subscription,
                    'user_subscription_expiry' => $user_subscription_expiry,
                    'subscription_status' => $subscription_status,
                ];
                return $this->responseApi($user_access_data, true, 'Coach Login Successfully', 200);
            } else {
                return $this->responseApi([], false, 'OTP expired or incorrect', 417);
            }
        }
    }

    public function sendOtpVerification(Request $request)
    {
        // Log::info($request);
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15|regex:/^\+[1-9]\d{10,14}$/',
        ]);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->messages()->all());
            $errorString = str_replace(',', ' ', $errorString);
            return $this->responseApi([], false, $errorString, 417);
        } else {
            $input = $request->only('phone', 'mode', 'device_id');
            if ($input['phone'] != '+11230000000') {
                $twilio_object = config('services.twilio_sms_gateway');
                $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
            }
            if (User::whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                }
            )->wherePhone($input['phone'])->exists() && $input['mode'] == 'signin') {
                if ($input['phone'] != '+11230000000') {
                    try {
                        $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                            ->verifications
                            ->create($input['phone'], "sms");
                    } catch (\Exception $e) {
                        return $this->responseApi([], false, 'Something went wrong. Please try again', 417);
                    }
                }
                $my_response = [
                    'phone' => $input['phone']
                ];
                return $this->responseApi($my_response, true, 'OTP sent', 200);
            } else if (User::whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                }
            )->wherePhone($input['phone'])->exists() && $input['mode'] == 'face-lock') {
                $user_data = User::with('latestMemberSubscription')->whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                    }
                )->wherePhone($input['phone'])->first();

                if ($user_data->hasRole('Coach') && $user_data->latestMemberSubscription) {
                    $user_subscription = $user_data->latestMemberSubscription->product_id;
                    $user_subscription_expiry = $user_data->latestMemberSubscription->expiry_date;
                    $subscription_status = true;
                } else {
                    $user_subscription = '';
                    $user_subscription_expiry = '';
                    $subscription_status = false;
                }
                if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045') {
                    $user_subscription = 'A005';
                    $subscription_status = true;
                }

                if ($user_data->device_id == $input['device_id']) {
                    $tokenResult = $user_data->createToken('authToken');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();
                    $user_data->update([
                        'is_verified' => 1,
                        'device_id' => $input['device_id']
                    ]);
                    $user_access_data = [
                        'first_name' => $user_data->first_name ? $user_data->first_name : '',
                        'last_name' => $user_data->last_name ? $user_data->last_name : '',
                        'email' => $user_data->email ? $user_data->email : '',
                        "phone" => $user_data->phone ? $user_data->phone : '',
                        "school" => $user_data->school ? $user_data->school : '',
                        "sports" => $user_data->sports ? $user_data->sports : '',
                        "twitter" => $user_data->twitter ? $user_data->twitter : '',
                        "linkedin" => $user_data->linkedin ? $user_data->linkedin : '',
                        "organization" => $user_data->organization ? $user_data->organization : '',
                        "image" => $user_data->image ? url($user_data->image) : '',
                        'token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'is_profile_completed' => $user_data->is_completed,
                        'is_parent' => $user_data->hasRole('Coach'),
                        'user_subscription' => $user_subscription,
                        'user_subscription_expiry' => $user_subscription_expiry,
                        'subscription_status' => $subscription_status,

                    ];
                    return $this->responseApi($user_access_data, true, 'Coach Login Successfully', 200);
                } else {
                    return $this->responseApi([], false, 'This user can\'t login with face id. Please login with phone number.', 417);
                }
            } else if (!User::whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                }
            )->wherePhone($input['phone'])->exists() && $input['mode'] == 'signup') {
                // $otp = $this->generateOtpNumber();
                if ($input['phone'] != '+11230000000') {
                    try {
                        $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                            ->verifications
                            ->create($input['phone'], "sms");
                    } catch (\Exception $e) {
                        return $this->responseApi([], false, 'Something went wrong. Please try again', 417);
                    }
                }

                $otp = 1234;
                $requestBody['msg'] = "Your OTP for AlphaScan verification is: " . $otp;
                $update_data = [
                    'otp' => $otp,
                    'is_verified' => 0,
                    'otp_expires' => Carbon::now()->addMinutes(2),
                    'password' => Hash::make(Str::random(8)),
                    'phone' => $input['phone'],
                    'device_id' => $input['device_id']
                ];
                $user = User::create($update_data);
                $user->assignRole('Coach');
                $my_response = [
                    'phone' => $user->phone
                ];
                return $this->responseApi($my_response, true, 'OTP sent', 200);
            } else if (!User::whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                }
            )->wherePhone($input['phone'])->exists()) {
                return $this->responseApi([], false, 'Not registered. Please sign-up', 417);
            } else {
                if ($input['mode'] == 'signup') {
                    $re_data = User::wherePhone($input['phone'])->first();
                    // dd($user_data);
                    if ($input['phone'] != '+11230000000' && $re_data->is_verified == 0) {
                        try {
                            $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                                ->verifications
                                ->create($input['phone'], "sms");
                        } catch (\Exception $e) {
                            return $this->responseApi([], false, 'Something went wrong. Please try again', 417);
                        }
                        $my_response = [
                            'phone' => $input['phone']
                        ];
                        return $this->responseApi($my_response, true, 'OTP sent', 200);
                    } else {
                        return $this->responseApi([], false, 'Already registered. Please sign-in', 417);
                    }
                }
            }
        }
    }

    public function verifyTeamMember(Request $request)
    {
        if ($request->user()) {
            $user = $request->user()->load(['children']);
            if (!$user->hasRole('Coach')) {
                return $this->responseApi([], false, 'You can\'t add your Team Member', 417);
            }
            $validator = Validator::make($request->all(), [
                'phone' => 'required|min:8|max:15|regex:/^\+[1-9]\d{10,14}$/',
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->messages()->all());
                $errorString = str_replace(',', ' ', $errorString);
                return $this->responseApi([], false, $errorString, 417);
            } else {
                $input = $request->only('phone', 'mode');
                $twilio_object = config('services.twilio_sms_gateway');
                $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
                if (!User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                    }
                )->wherePhone($input['phone'])->exists() && $input['mode'] == 'add-member') {
                    if ($user->children->count() > 2) {
                        return $this->responseApi([], false, 'Can\'t register more than 2 members', 417);
                    } else {
                        try {
                            $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                                ->verifications
                                ->create($input['phone'], "sms");
                        } catch (\Exception $e) {
                            return $this->responseApi([], false, 'Something went wrong. Please try again', 417);
                        }
                        $my_response = [
                            'phone' => $input['phone']
                        ];
                        return $this->responseApi($my_response, true, 'OTP sent', 200);
                    }
                } else {
                    return $this->responseApi([], false, 'This number can\'t be register as Team Member', 417);
                }
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function resendOtpVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15|regex:/^\+[1-9]\d{10,14}$/',
        ]);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->messages()->all());
            $errorString = str_replace(',', ' ', $errorString);
            return $this->responseApi([], false, $errorString, 417);
        } else {
            $input = $request->only('phone');
            $twilio_object = config('services.twilio_sms_gateway');
            $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
            try {
                $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                    ->verifications
                    ->create($input['phone'], "sms");
            } catch (\Exception $e) {
                return $this->responseApi([], false, 'Something went wrong. Please try again', 417);
            }
            $my_response = [
                'phone' => $input['phone']
            ];
            return $this->responseApi($my_response, true, 'OTP sent', 200);
        }
    }

    public function addTeamMember(Request $request)
    {
        if ($request->user()) {
            $user = $request->user()->load(['children']);
            if (!$user->hasRole('Coach')) {
                return $this->responseApi([], false, 'You can\'t add your Team Member', 417);
            }
            $validator = Validator::make($request->all(), [
                'phone' => 'required|min:8|max:15|regex:/^\+[1-9]\d{10,14}$/|unique:users,phone,NULL,id,deleted_at,NULL',
                'code' => 'required',
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->messages()->all());
                $errorString = str_replace(',', ' ', $errorString);
                return $this->responseApi([], false, $errorString, 417);
            } else {
                $input = $request->all();
                $twilio_object = config('services.twilio_sms_gateway');
                $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
                if (!User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                    }
                )->wherePhone($input['phone'])->exists()) {
                    $user_data = User::with('children')->find($user->id);
                    if ($user_data->children->count() >= 2) {
                        return $this->responseApi([], false, 'Can\'t register more than 2 members', 417);
                    } else {
                        // $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
                        // $verification = '';
                        // try {
                        //     $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                        //         ->verificationChecks
                        //         ->create([
                        //             "to" => $input['phone'],
                        //             "code" => $input['code']
                        //         ]);
                        // } catch (\Exception $e) {
                        //     return $this->responseApi([], false, 'OTP expired, incorrect or something broken.', 417);
                        // }
                        // if ($verification && $verification->valid) {

                        // } else {
                        //     return $this->responseApi([], false, 'OTP expired or incorrect', 417);
                        // }

                        // $twilio_client = new Client($twilio_object['twilio_sid'], $twilio_object['twilio_auth_token']);
                        // $verification = '';
                        // try {
                        //     // $verification = $twilio_client->verify->v2->services($twilio_object['twilio_verify_sid'])
                        //     //     ->verificationChecks
                        //     //     ->create([
                        //     //         "to" => $input['phone'],
                        //     //         "code" => $input['code']
                        //     //     ]);
                        //     $sms_body = 'https://alphasalliance.com \nWelcome to AlphaScan! You have been added by ' . $user_data->first_name . ' ' . $user_data->last_name . ' at ' . $user_data->school . '. To gain access, Please install and login in above application.';
                        //     $twilio_client->messages
                        //         ->create($input['phone'], ["body" => $sms_body]);
                        // } catch (\Exception $e) {
                        //     return $this->responseApi([], false, 'OTP expired, incorrect or something broken.', 417);
                        // }

                        $otp = 1234;
                        $requestBody['msg'] = "Your OTP for AlphaScan verification is: " . $otp;
                        $update_data = [
                            'otp' => $otp,
                            'is_verified' => 1,
                            'otp_expires' => Carbon::now()->addMinutes(2),
                            'password' => Hash::make(Str::random(8)),
                            'phone' => $input['phone'],
                            'parent_id' => $user->id,
                            'is_completed' => 'false',
                        ];
                        $member = User::create($update_data);
                        $role = Role::findByName('Team Member', 'web');
                        $member->assignRole($role);

                        $user_data = [
                            'first_name' => $user['first_name'] ? $user['first_name'] : '',
                            'last_name' => $user['last_name'] ? $user['last_name'] : '',
                            'email' => $user['email'] ? $user['email'] : '',
                            "phone" => $user['phone'] ? $user['phone'] : '',
                            "sports" => $user['sports'] ? $user['sports'] : '',
                            "twitter" => $user['twitter'] ? $user['twitter'] : '',
                            "linkedin" => $user['linkedin'] ? $user['linkedin'] : '',
                            "school" => $user['school']  ? $user['school']  : '',
                            "organization" => $user['organization']  ? $user['organization']  : '',
                            "image" => $user['image'] ? url($user['image']) : '',
                            'token' => $request->bearerToken(),
                            'token_type' => 'Bearer',
                            'is_profile_completed' => $user['is_completed'],
                            'is_parent' => $user->hasRole('Coach'),
                        ];
                        return $this->responseApi($user_data, true, 'Member added', 200);
                    }
                } else {
                    return $this->responseApi([], false, 'This number can\'t be register as Team Member', 417);
                }
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function addTeamMemberEmail(Request $request)
    {
        if ($request->user()) {
            $user = $request->user()->load(['children', 'latestMemberSubscription']);
            $user_data = User::with('children')->find($user->id);
            if (!$user->hasRole('Coach')) {
                return $this->responseApi([], false, 'You can\'t add your Team Member', 417);
            }

            if ($user_data->children->count() >= 2) {
                return $this->responseApi([], false, 'Can\'t register more than 2 members', 417);
            }

            if ($user_data->email == $request->email) {
                return $this->responseApi([], false, 'Can\'t add your email as your team member', 417);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required||max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/',
                'code' => 'required',
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->messages()->all());
                $errorString = str_replace(',', ' ', $errorString);
                return $this->responseApi([], false, $errorString, 417);
            } else {
                $input = $request->all();
                if (!User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Coach')->orWhere('name', 'Team Member');
                    }
                )->whereEmail($input['email'])->exists()) {
                    $otp = 1234;
                    $requestBody['msg'] = "Your OTP for AlphaScan verification is: " . $otp;

                    $user_uniid = Str::uuid()->toString();
                    $update_data = [
                        'user_uniid' => $user_uniid,
                        'otp' => $otp,
                        'is_verified' => 0,
                        'otp_expires' => Carbon::now()->addMinutes(2),
                        'password' => Hash::make(Str::random(8)),
                        'email' => $input['email'],
                        'parent_id' => $user->id,
                        'is_completed' => 'false',
                    ];

                    $member = User::create($update_data);
                    $role = Role::findByName('Team Member', 'web');

                    $member->assignRole($role);
                    $member_details = [
                        'member_link' => url('/team/confirm-account/' . $user_uniid),
                    ];

                    $queueData = [];
                    $queueData['email'] = $member->email;

                    $queueData['data'] = $member_details;
                    dispatch(new TeamMemberWelcomeMailJob($queueData));

                    if ($user->hasRole('Coach') && $user->latestMemberSubscription) {
                        $user_subscription = $user->latestMemberSubscription->product_id;
                        $user_subscription_expiry = $user->latestMemberSubscription->expiry_date;
                        $subscription_status = true;
                    } else {
                        $user_subscription = '';
                        $user_subscription_expiry = '';
                        $subscription_status = false;
                    }
                    if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045') {
                        $user_subscription = 'A005';
                        $subscription_status = true;
                    }

                    $user_data = [
                        'first_name' => $user['first_name'] ? $user['first_name'] : '',
                        'last_name' => $user['last_name'] ? $user['last_name'] : '',
                        'email' => $user['email'] ? $user['email'] : '',
                        "phone" => $user['phone'] ? $user['phone'] : '',
                        "sports" => $user['sports'] ? $user['sports'] : '',
                        "twitter" => $user['twitter'] ? $user['twitter'] : '',
                        "linkedin" => $user['linkedin'] ? $user['linkedin'] : '',
                        "school" => $user['school']  ? $user['school']  : '',
                        "organization" => $user['organization']  ? $user['organization']  : '',
                        "image" => $user['image'] ? url($user['image']) : '',
                        'token' => $request->bearerToken(),
                        'token_type' => 'Bearer',
                        'is_profile_completed' => $user['is_completed'],
                        'is_parent' => $user->hasRole('Coach'),
                        'user_subscription' => $user_subscription,
                        'user_subscription_expiry' => $user_subscription_expiry,
                        'subscription_status' => $subscription_status,
                    ];
                    return $this->responseApi($user_data, true, 'Member added', 200);
                } else if (User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Coach');
                    }
                )->whereEmail($input['email'])->exists()) {
                    $email_data = User::with('children')->whereEmail($input['email'])->first();
                    if ($email_data->children->count() > 0) {
                        return $this->responseApi([], false, 'This email can\'t be register as Team Member', 417);
                    } else {
                        $user_uniid = Str::uuid()->toString();
                        $update_data = [
                            'user_uniid' => $user_uniid,
                            'is_verified' => 0,
                            'otp_expires' => Carbon::now()->addMinutes(2),
                            'is_completed' => 'false',
                        ];
                        $email_data->update($update_data);

                        $request_uniid = Str::uuid()->toString();
                        $request_data = [
                            'request_uniid' => $request_uniid,
                            'user_id' => $user_uniid,
                            'coach_id' => $user->id,
                            'status' => 'Pending'
                        ];

                        $member_request = TeamMemberRequest::create($request_data);
                        $member_details = [
                            'member_link' => url('/team/confirm/' . $member_request->request_uniid),
                        ];

                        $queueData = [];
                        $queueData['email'] = $email_data->email;

                        $queueData['data'] = $member_details;
                        dispatch(new TeamMemberRequestJob($queueData));

                        if ($user->hasRole('Coach') && $user->latestMemberSubscription) {
                            $user_subscription = $user->latestMemberSubscription->product_id;
                            $user_subscription_expiry = $user->latestMemberSubscription->expiry_date;
                            $subscription_status = true;
                        } else {
                            $user_subscription = '';
                            $user_subscription_expiry = '';
                            $subscription_status = false;
                        }
                        if ($user_data->hasRole('Coach') && $user_data->phone == '+12013702430' || $user_data->phone == '+14016923048' || $user_data->phone == '+923214250045') {
                            $user_subscription = 'A005';
                            $subscription_status = true;
                        }
                        $user_data = [
                            'first_name' => $user['first_name'] ? $user['first_name'] : '',
                            'last_name' => $user['last_name'] ? $user['last_name'] : '',
                            'email' => $user['email'] ? $user['email'] : '',
                            "phone" => $user['phone'] ? $user['phone'] : '',
                            "sports" => $user['sports'] ? $user['sports'] : '',
                            "twitter" => $user['twitter'] ? $user['twitter'] : '',
                            "linkedin" => $user['linkedin'] ? $user['linkedin'] : '',
                            "school" => $user['school']  ? $user['school']  : '',
                            "organization" => $user['organization']  ? $user['organization']  : '',
                            "image" => $user['image'] ? url($user['image']) : '',
                            'token' => $request->bearerToken(),
                            'token_type' => 'Bearer',
                            'is_profile_completed' => $user['is_completed'],
                            'is_parent' => $user->hasRole('Coach'),
                            'user_subscription' => $user_subscription,
                            'user_subscription_expiry' => $user_subscription_expiry,
                            'subscription_status' => $subscription_status,
                        ];
                        return $this->responseApi($user_data, true, 'Member has been notified. Requested Member will appears in list once approved.', 200);
                    }
                } else if (User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'Team Member');
                    }
                )->whereEmail($input['email'])->exists()) {
                    return $this->responseApi([], false, 'This email already registered.', 417);
                }
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function fetchTeamMembers(Request $request)
    {
        if ($request->user()) {
            $user = $request->user()->load(['children']);

            if (!$user->hasRole('Coach')) {
                return $this->responseApi([], false, 'You can\'t have team member', 417);
            }
            $children_data = [];
            $user_data = User::with('children')->find($user->id);
            foreach ($user_data->children as $children) {
                $children_data[] = [
                    'id' => $children['id'],
                    'first_name' => $children['first_name'] ? $children['first_name'] : '',
                    'last_name' => $children['last_name'] ? $children['last_name'] : '',
                    'email' => $children['email'] ? $children['email'] : '',
                    "phone" => $children['phone'] ? $children['phone'] : '',
                    "school" => $children['school'] ? $children['school'] : '',
                    "sports" => $children['sports'] ? $children['sports'] : '',
                    "twitter" => $children['twitter'] ? $children['twitter'] : '',
                    "linkedin" => $children['linkedin'] ? $children['linkedin'] : '',
                    "organization" => $children['organization']  ? $children['organization']  : '',
                    "image" => $children['image'] ? url($children['image']) : '',
                    "is_verified" => $children['is_verified'] == 1 ? true : false,
                    'is_profile_completed' => $children['is_completed'],
                    'is_parent' => false,
                    'user_subscription' => '',
                    'user_subscription_expiry' => '',
                    'subscription_status' => false,
                ];
            }
            return $this->responseApi($children_data, true, 'Members fetched', 200);
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function deleteTeamMembers(Request $request, $id)
    {
        if ($request->user()) {
            $user = $request->user()->load(['children']);
            if (!$user->hasRole('Coach')) {
                return $this->responseApi([], false, 'You can\'t delete team member', 417);
            }

            $childCheck = User::where([
                ['id', '=', $id],
                ['parent_id', '=', $user->id]
            ])->exists();
            switch ($childCheck) {
                case (true):
                    $member = User::find($id);
                    $member->playersFile()->delete();
                    $member->playersChildFile()->delete();
                    $member->children()->delete();
                    $member->delete();
                    $children_data = [];
                    $user_data = User::with('children')->find($user->id);
                    foreach ($user_data->children as $children) {
                        $children_data[] = [
                            'id' => $children['id'],
                            'first_name' => $children['first_name'] ? $children['first_name'] : '',
                            'last_name' => $children['last_name'] ? $children['last_name'] : '',
                            'email' => $children['email'] ? $children['email'] : '',
                            "phone" => $children['phone'] ? $children['phone'] : '',
                            "school" => $children['school'] ? $children['school'] : '',
                            "sports" => $children['sports'] ? $children['sports'] : '',
                            "twitter" => $children['twitter'] ? $children['twitter'] : '',
                            "linkedin" => $children['linkedin'] ? $children['linkedin'] : '',
                            "organization" => $children['organization']  ? $children['organization']  : '',
                            "image" => $children['image'] ? url($children['image']) : '',
                            "is_verified" => $children['is_verified'] == 1 ? true : false,
                            'is_profile_completed' => $children['is_completed'],
                        ];
                    }
                    return $this->responseApi($children_data, true, 'Team member Deleted', 200);
                    break;
                case (false):
                    return $this->responseApi([], false, 'You can\'t delete this team member', 417);
                    break;
                default:
                    return $this->responseApi([], false, 'This team member can\'t be delete', 417);
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }

    public function deleteUserAccount(Request $request)
    {
        if ($request->user()) {
            $user = $request->user()->load(['children']);
            $childCheck = User::where('id', '=', $user->id)->exists();
            switch ($childCheck) {
                case (true):
                    $user->playersFile()->delete();
                    $user->playersChildFile()->delete();
                    $user->children()->delete();
                    $user->delete();
                    return $this->responseApi([], true, 'Account Deleted', 200);
                    break;
                case (false):
                    return $this->responseApi([], false, 'Incorrect User', 417);
                    break;
                default:
                    return $this->responseApi([], false, 'can\'t delete this account', 417);
            }
        } else {
            return $this->responseApi([], false, 'Coach token is missing', 401);
        }
    }
}
