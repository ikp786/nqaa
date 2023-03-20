<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverProfile;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\UserProfileCollection;
use App\Mail\SentOtpEmail;
use App\Models\CouponCartMapping;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\ResponseWithHttpRequest;
use App\Models\User;
use App\Models\Version;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Str;
use Illuminate\Support\Facades\Storage;
use Validator;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
 use ResponseWithHttpRequest;
	
	// UNAUTHORIZED ACCESS
	public function unauthorized_access()
	{
		return $this->sendFailed('YOU ARE NOT UNAUTHORIZED TO ACCESS THIS URL, PLEASE LOGIN AGAIN', 401);
	}


	public function sendOtpViaSMS(Request $request)
{
    $otp = rand(1000, 9999); // Generate a random OTP
    $message = "Your OTP is: " . $otp;
	$request->phone_number = '+97455232137';
    $client = app(Client::class);
    $client->messages->create(
        $request->phone_number,
        [
            'from' => config('services.twilio.number'),
            'body' => $message
        ]
    );

    // Save the OTP to the database or session
    session()->put('otp', $otp);
return response()->json($otp);
    return redirect()->back()->with('success', 'OTP sent successfully!');

}

public function sendOtpViaEmail(Request $request)
{
	echo dataAgo();
    $otp = rand(1000, 9999); // Generate a random OTP
    $data = [        'otp' => $otp    ];
	$request->email = 'ibrahimkhan13kld@gmail.com';

$data2 = Mail::to($request->user())->send(new SentOtpEmail($data));
dd($data2);

    $data = Mail::send('emails.otp', $data, function($message) use ($request) {
        $message->to($request->email);
        $message->subject('OTP for Login');
    });
dd($data);
    // Save the OTP to the database or session
    session()->put('otp', $otp);

    return redirect()->back()->with('success', 'OTP sent successfully!');
}

function test(Request $request){

	
	$url = 'https://skipcashtest.azurewebsites.net/api/checkout';

// Set your SkipCash API Client ID and Secret Key
$clientID = '35fe7230-0503-4dec-a6e0-fe61420dedbe';
$secretKey = 'cszniP5mMuoiQq4ZdwAUkAeSSHfZdpE2qHetB5/r9lomGQkS2bakcHW268uswASTw27WVIlzKgT2XY65lVNoAMrJFqpSUswAP1b6zOtmShcIyauNq8mLlJRObE+4c6pB9M4ITNCF8Fhh4FYImimjaoiUjFDlFuAMdPq94JBAip+KRdGl1GYtjxt58n9H4Dh/t1dDR+B7p0wwEBpr6/eg8lZkXxuidvLZTkKw3K5bHMsp+1g5z0uZ3fOAaAsaiR7hqumv48AHECyhbxzEUPdiMdyNytrvgttw6DkMPFig9+7p1o16m3YjpuRAeEAgE+Jl47OtXaT7spfzr3sfHKj67lFpI25VCV/nlBgnvzfJ3v1weaojdMj3QNh6zto2Db0zNyywQehY1ccXGYduFOEDhT6az7YocdnCrLBkyn0zFOfQ0KVheGFc/g+lX/pu43t0fSBmy3aUdI5VMacO7bF7tZokjR0wYShHX3iGyB/PeE/YpbGUTvj/Q0grnPNVZpCBUNeR8UdMrtqPdkdKvjjgow==';

// Set your request data
$data = array(
    'amount' => 19.90,
    'description' => 'Product description',
    'transaction_id' => 'Transaction123',
    'return_url' => 'https://aksasoftware.com/naqaa-water-delivery/public/admin'
);

// Convert the request data to JSON format
$json_data = json_encode($data);

// Generate the Authorization header value
$timestamp = time();
$string_to_sign = $timestamp . $clientID . $url . $json_data;
$signature = hash_hmac('sha256', $string_to_sign, $secretKey);
$authorization = $clientID . ':' . $signature . ':' . $timestamp;

// Set the HTTP headers for your request
$headers = array(
    'Authorization: ' . $authorization,
    'Content-Type: application/json',
    'Accept: application/json'
);

// Send the request with cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
dd($response);

if (!$response) {
    $error = curl_error($ch);
    // Handle the cURL error
    die('cURL error: ' . $error);
}

// Parse the response JSON data
$response_data = json_decode($response, true);

curl_close($ch);

dd($response,$response_data);
	die;

	$url = 'https://skipcashtest.azurewebsites.net/api/checkout';

// Set the necessary headers and API keys
$headers = array(
    'Content-Type: application/json',
    'x-client-id: 81c297b3-4663-4868-a5eb-8e5a2a2f0e0c',
    'Authorization: /P3LPhgVmpHMlWbFEd3Y40SY57TNK2Kxo8UtoKCsYgGpE9orTrJWgwWauDkADASl3Hpugp7WvGBkRnnva2nN0WtWsQdv9pbjAcDu42SVBgWm3Q+2llO6FPUWtJKkdO42ty9VIuyXdL2pP1tsn6PUp6INzvtRo/41+PbmWzwz0EZnEpYzyDCpzFhBpnezgfoEMf7mdxl+Djw2fYyIW94G+NS2QXdd2xuBM265nhbp30CvWE1DF1EmR3bpllHaZJpqdIdOUqpTmGi99wAIbIVc3n4zrAxdSM+hghMqp9+2lgoBGrLn6qLWcguLpn/AE5e2+qF/P2Uzv0FT3RA8O/l2Gr3FELDISEfAX41ubOT76XmLsqtGq/Wt+EYVjKg/oqQXugBQtWgZ76p+s+6UUOILew79FfyLoT96/+gZouSXCYjQaTOsb8/43h7RCGsE0DD2FgX6IR8EGULnRDpVSGLQgV6jgh6JZcm2DwGEop9wt+SKZIBlxhF8s/GSQZ3Qox3QWQy6uow5N+Pi4Yt7O+bKHg=='
);

// Set the data payload for the request
$data = array(
    'order_id' => '123456',
    'total_amount' => 100.00,
    'customer_name' => 'John Smith',
    'customer_phone' => '123-456-7890',
    'customer_email' => 'johnsmith@example.com',
    // additional data as needed
);

// Encode the data as a JSON string
$json_data = json_encode($data);

// Create a cURL request to send to SkipCash
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
$encodedSignature = base64_encode($signature);

// Send the request and get the response
$response = curl_exec($ch);

// Parse the JSON response
$result = json_decode($response, true);
dd($result);

// Handle any errors or exceptions
if ($result['status'] !== 'success') {
    // handle error
} else {
    // process payment success
}

// Close the cURL request
curl_close($ch);
die;
	$skipCashKeyId = '217af8a1-7fb7-4972-8879-e07b01f80685';
	$formData = [
		"uid" => Str::uuid()->toString(),
		"keyId" => $skipCashKeyId,
		"amount" => '19.90',
		"firstName" => 'Ibrahim',
		"lastName" => "khane",
		"phone" => "+97455433960",
		"email" => "khanebrahim643@gmail.com",
		"street" => "CA",
		"city" => "TempCity",
		"state" => "00",
		"country" => "00",
		"postCode" => "01238",
		"transactionId" => "6878jh78",
		"orderId" => "uhiu786"
	];

	// $query = http_build_query($formData, "", ",");
	$query = json_encode($formData);

	$skipCashSecretKey = 'P3LPhgVmpHMlWbFEd3Y40SY57TNK2Kxo8UtoKCsYgGpE9orTrJWgwWauDkADASl3Hpugp7WvGBkRnnva2nN0WtWsQdv9pbjAcDu42SVBgWm3Q+2llO6FPUWtJKkdO42ty9VIuyXdL2pP1tsn6PUp6INzvtRo/41+PbmWzwz0EZnEpYzyDCpzFhBpnezgfoEMf7mdxl+Djw2fYyIW94G+NS2QXdd2xuBM265nhbp30CvWE1DF1EmR3bpllHaZJpqdIdOUqpTmGi99wAIbIVc3n4zrAxdSM+hghMqp9+2lgoBGrLn6qLWcguLpn/AE5e2+qF/P2Uzv0FT3RA8O/l2Gr3FELDISEfAX41ubOT76XmLsqtGq/Wt+EYVjKg/oqQXugBQtWgZ76p+s+6UUOILew79FfyLoT96/+gZouSXCYjQaTOsb8/43h7RCGsE0DD2FgX6IR8EGULnRDpVSGLQgV6jgh6JZcm2DwGEop9wt+SKZIBlxhF8s/GSQZ3Qox3QWQy6uow5N+Pi4Yt7O+bKHg==';
	// $signature = hash_hmac('sha256', json_encode($query), $skipCashSecretKey);
	$signature = hash_hmac('sha256', $query, $skipCashSecretKey);

	$skipCashClientId = '81c297b3-4663-4868-a5eb-8e5a2a2f0e0c';
	$headers = [
		'Authorization: ' . base64_encode($signature),
		'Content-Type: application/json;charset=UTF-8',
		'x-client-id: ' . $skipCashClientId
	];
	
	$skipCashUrl = 'https://skipcashtest.azurewebsites.net';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $skipCashUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);


	$response = curl_exec($ch);
	curl_close($ch);
dd($response);
	$data = json_decode($response);



	$skipCashKeyId = '217af8a1-7fb7-4972-8879-e07b01f80685';
	// dd($skipCashKeyId);die;
	$formData = [
            "uid" => Str::uuid()->toString(),
            "keyId" => $skipCashKeyId,
            "amount" => number_format(10, 2),
            "firstName" => 'Ibrahim',
            "lastName" => 'Khan',
            "phone" => '9602625743',
            "email" => 'khanebrahim643@gmail.com',
            "street" => "CA",
            "city" => "TempCity",
            "state" => "00",
            "country" => "00",
            "postCode" => "01238",
            "transactionId" => '12321',
            "orderId" => '23456'
        ];

        $query = http_build_query($formData, "", ",");
		$skipCashSecretKey = 'P3LPhgVmpHMlWbFEd3Y40SY57TNK2Kxo8UtoKCsYgGpE9orTrJWgwWauDkADASl3Hpugp7WvGBkRnnva2nN0WtWsQdv9pbjAcDu42SVBgWm3Q+2llO6FPUWtJKkdO42ty9VIuyXdL2pP1tsn6PUp6INzvtRo/41+PbmWzwz0EZnEpYzyDCpzFhBpnezgfoEMf7mdxl+Djw2fYyIW94G+NS2QXdd2xuBM265nhbp30CvWE1DF1EmR3bpllHaZJpqdIdOUqpTmGi99wAIbIVc3n4zrAxdSM+hghMqp9+2lgoBGrLn6qLWcguLpn/AE5e2+qF/P2Uzv0FT3RA8O/l2Gr3FELDISEfAX41ubOT76XmLsqtGq/Wt+EYVjKg/oqQXugBQtWgZ76p+s+6UUOILew79FfyLoT96/+gZouSXCYjQaTOsb8/43h7RCGsE0DD2FgX6IR8EGULnRDpVSGLQgV6jgh6JZcm2DwGEop9wt+SKZIBlxhF8s/GSQZ3Qox3QWQy6uow5N+Pi4Yt7O+bKHg==';
        $signature = hash_hmac('sha256', json_encode($query), $skipCashSecretKey);

		$skipCashClientId = 'a26a5f7f-20ff-401d-8846-70d5bfa6c366';
        $headers = [
            'Authorization: ' . base64_encode($signature),
            'Content-Type: application/json;charset=UTF-8',
            'x-client-id: ' . $skipCashClientId
        ];
        
		$skipCashUrl = 'https://skipcashtest.azurewebsites.net/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $skipCashUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

		dd($response);

		die;
	$formData = [
		"uid" => Str::uuid()->toString(),
		"keyId" => $skipCashKeyId,
		"amount" => number_format($order->total_amount, 2),
		"firstName" => $order->user->first_name,
		"lastName" => $order->user->last_name,
		"phone" => $order->user->phone_number,
		"email" => $order->user->email,
		"street" => "CA",
		"city" => "TempCity",
		"state" => "00",
		"country" => "00",
		"postCode" => "01238",
		"transactionId" => $order->reference_no,
		"orderId" => $order->id
	];

	$query = http_build_query($formData, "", ",");

	$signature = hash_hmac('sha256', json_encode($query), $skipCashSecretKey);

	$headers = [
		'Authorization: ' . base64_encode($signature),
		'Content-Type: application/json;charset=UTF-8',
		'x-client-id: ' . $skipCashClientId
	];
	

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $skipCashUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

	$response = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($response);

	die;
$dfd = Str::uuid()->toString();
$skipCashKeyId = '42c24160-8126-48c9-b966-6b10feae3cae';
	$formData = [
		"uid" => Str::uuid()->toString(),
		"keyId" => $skipCashKeyId,
		"amount" => number_format(10, 2),
		"firstName" => 'Ibrahim',
		"lastName" => 'Khan',
		"phone" => '9602625743',
		"email" => 'khanebrahim643@gmail.com',
		"street" => "CA",
		"city" => "TempCity",
		"state" => "00",
		"country" => "00",
		"postCode" => "01238",
		"transactionId" => '12321',
		"orderId" => '23456'
	];
	dd($formData);
$response = Http::withToken('ooL8oRKdGs/724CNVv5U9H8+0lvkAjSEiU+xiPQTlzdj3e8PyTYoZxPhKZ3bz0zzqZcvqoZLhwFJmQoF70rjATIER7C8w03kobPh1SZjjZfETME/bWLIl52Kqvr0m5w6jtjIdlydzilJ4lHImvo3mZ+7nklnIqaECB8Hy+ujMHuIVFhwrLlO+KIaJg0FwI4MD4Av44MQtMpLYQk2FgS6AWGsu4Q22QlgzD4OQcG/zpp6dlgovDTeuOJK2d5IUgsGtRxZj1vOcNOpXaCEgufgztA8dYrnTJ6le/jxS5nYJyiZ1rZOEloOTgIQ81caHJfhf7mh5GLb8O5Twp6Jl9ZgfGPSbfNVv6PXIdcFw5zbXqNQ4r5eWRm/naLO2D+1pt4s50GLaVlDlvRO2JkDw3NHDOyvHgt7n6ZM5ctP/ME787tTrM9Pv75Ph8JWclQ/MMSIQisbqvSvp0nNyw6thX5j9qOuInsG62HxaxsN+7NhN/AAQcOq7mJxIps+doOA/rgL7JHxo5RO+UMI/zsTnLtX7w==')->post('https://aksasoftware.com/naqaa-water-delivery/public/api/profile',$formData);
	// $response = Http::get('http://example.com');

	dd($response->json());




	die;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.tap.company/v2/merchant",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"display_name\":\"goPay\",\"business_id\":\"bus_jICJ50019921aGRG11Ja103291\",\"business_entity_id\":\"ent_QTFo56019921OSHN11Bv10y128\",\"wallet_id\":\"wal_ervV36191622EGrv11LV105316\",\"charge_currenices\":[\"KWD\"]}",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer 42c24160-8126-48c9-b966-6b10feae3cae",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


die;

	$skipCashKeyId = 'af8e42b4-d27d-4089-8300-af56a7580b09';
	$formData = [
            "uid" => Str::uuid()->toString(),
            "keyId" => $skipCashKeyId,
            "amount" => number_format(10, 2),
            "firstName" => 'Ibrahim',
            "lastName" => 'Khan',
            "phone" => '9602625743',
            "email" => 'khanebrahim643@gmail.com',
            "street" => "CA",
            "city" => "TempCity",
            "state" => "00",
            "country" => "00",
            "postCode" => "01238",
            "transactionId" => '12321',
            "orderId" => '23456'
        ];

        $query = http_build_query($formData, "", ",");
		$skipCashSecretKey = '4rV0hwk6odGn94iYKjNsvNGYcneoDE+uJg4U7WJkyUQYE2zzMW77yExL+Orqzqi2ZME5WiMkgwYl5v8Oi/eaPNp7d+y2X1IKmIR71OUzbT/sS3OAjt2m6KAQq/mK18l5xV8yMA2+ZrefhcKuWeBUkO/5EENMsZsY2Nf7Ag6cUPb9wRA/IA8XxP9Sa9uujvplKDshvtFG3HCMt/g8ze4tYqyO7u0Dz8xjhZoCEXdo6Dwozhq4lvBPdFO2E6ZcZoxdUIilQiU2ann99S/o14MUGNfxHY3wajJoqLeP32Md3Kr2tRRBwOi2d932ls4eGigS6WF/qa/eTiOo9jw0q6UfQKmQTf7IwVIIR/E4ykwpNXGnPrspVsAtot43GF+P6seGnmAgN9SGR6sgH6v7X8axAAHYYGU/Y6nnuOygrtLeaiGLZ0Qc9Smc7PQNdh3EGiiKyaQgijJWXyZHx5/MGreaQKjZURICvAiIM/HnUHnRpU6azShvoDMy8ULvlCpOL0mqT9MshXpVWzKSsxMEPQC6VA==';
        $signature = hash_hmac('sha256', json_encode($query), $skipCashSecretKey);

		$skipCashClientId = 'a26a5f7f-20ff-401d-8846-70d5bfa6c366';
        $headers = [
            'Authorization: ' . base64_encode($signature),
            'Content-Type: application/json;charset=UTF-8',
            'x-client-id: ' . $skipCashClientId
        ];
        
		$skipCashUrl = 'https://skipcashtest.azurewebsites.net/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $skipCashUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

		dd($data);

}

	public function loginOtpVerify(Request $request)
	{
		$error_message = 	[
			// 'mobile.unique'  	              => 'mobile has been already taken',
			'mobile.required'            	  => 'Mobile should be required',
			'user_id.required'			 	  => 'User Id should be required',
			'otp.required'					  => 'OTP should be required',
		];
		$rules = [
			// 'mobile'                          => 'required|min:10|max:10',
			'user_id'						  => 'required|integer|exists:users,id',
			'otp'						      => 'required|integer',
			'device_token'					  => 'required'
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {

			\DB::beginTransaction();
			$user_detail = User::find($request->user_id);
			
			if ($request->otp != $user_detail->otp) {
				return $this->sendFailed("wrong otp", 200);
			}			
			$udpat = User::find($user_detail->id)
			->update(['device_token' => $request->device_token]);
			Auth::loginUsingId($user_detail->id);
			
			// $access_token = auth()->user()->createToken(auth()->user()->mobile)->accessToken;
            $access_token = $user_detail->createToken("API TOKEN")->plainTextToken;

			// dd($access_token);
			$access_token = explode('|',$access_token)[1];

			// auth()->user()->fill($request->only(['device_token']))->save();
			
			// dd($udpat);
			\DB::commit();
			return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['access_token' => $access_token, 'profile_data' => new UserProfileCollection(auth()->user())]);
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	// CREATE ACCOUNT API
	public function login(Request $request)
	{
		$error_message = 	[			
			'mobile.required'            	  => 'Mobile should be required',			
		];
		$rules = [
			'mobile'                          => 'required|min:6|max:15'			
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}

		try {
			\DB::beginTransaction();			
			$user = User::updateOrCreate(
				$request->only('mobile')
			);
			$verifaction_otp = rand(1000, 9999);
			$user->otp = $verifaction_otp;
			$user->type = 'User';

			$sid    = 'AC20c5099c3307efd5192ea579dfa3729b';//env('TWILIO_ACCOUNT_SID', "AC20c5099c3307efd5192ea579dfa3729b");
                $token  = 'bf4b325ae6d8a574c7a3b5d87a251e5f';//env('TWILIO_AUTH_TOKEN', "bf4b325ae6d8a574c7a3b5d87a251e5f");
                $twilio = new Client($sid, $token);
				// $from_phone_number = '+919509036779';
				// $to_phone_number = '+919602625743';
				// $message = $twilio->messages->create(
				// 	'whatsapp:' . $to_phone_number,
				// 	array(
				// 		'from' => 'whatsapp:' . $from_phone_number,
				// 		'body' => "hello dear"
				// 	)
				// );
				// dd($message);

                $message = $twilio->messages->create(
					"+974".$request->mobile,
					array(
						"messagingServiceSid" => "MG19c561070f6271ceadc05d241f6f4b24",
						"body" => "One time password for Naqq is ".$verifaction_otp
					)
				);
			$user->save();
			\DB::commit();
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['user_id' => $user->id, 'otp' => $verifaction_otp]);
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}
	public function getUserProfile()
	{
		return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['profile_data' => new UserProfileCollection(auth()->user())]);
	}

	public function getDriverProfile()
	{
		$delivery_charge                = Setting::value('deliver_charge');
		return $this->sendSuccess('LOGGED IN SUCCESSFULLY', ['profile_data' => new DriverProfile(auth()->user())]);
	}

	public function sentRegisterOtp(Request $request)
	{
		$error_message = 	[
			'mobile.required'  	=> 'Mobile address should be required',
		];
		$rules = [
			'mobile'       		=> 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$verifaction_otp = rand(1000, 9999);
			$email_data = ['otp' => $verifaction_otp];
			// \Mail::to($request->email_address)->send(new \App\Mail\LoginOtp($email_data));
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['verifaction_otp' => $verifaction_otp, 'mobile' => $request->mobile]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function reSentOtpMobileUpdate(Request $request)
	{
		$error_message = 	[
			'mobile.required'  	=> 'Mobile address should be required',
		];
		$rules = [
			'mobile' => 'required|unique:users,mobile,' . auth()->user()->id,
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$mobileExist = User::where('id', '!=', auth()->user()->id)->where(['role' => auth()->user()->role, 'mobile' => $request->mobile])->count();
		if ($mobileExist > 0) {
			return $this->sendFailed("Mobile number has been already taken", 200);
		}
		try {
			$verifaction_otp = rand(1000, 9999);
			Self::send_sms_otp($request->mobile, $verifaction_otp);
			$user = auth()->user();
			$user->otp = $verifaction_otp;
			$user->save();
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['verifaction_otp' => $verifaction_otp, 'mobile' => $request->mobile]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function registerReSentOtp(Request $request)
	{
		$error_message = 	[
			// 'mobile.unique'  	              => 'mobile has been already taken',
			'mobile.required'            	  => 'Mobile should be required',
			'user_id.required'			 	  => 'User Id should be required',
		];
		$rules = [
			'mobile'                          => 'required|min:10|max:10|exists:users,mobile',
			'user_id'						  => 'required|integer|exists:users,id',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$user_detail = User::find($request->user_id);
		if ($user_detail->mobile != $request->mobile) {
			return $this->sendFailed("Mobile number does not exist", 200);
		}
		try {
			$verifaction_otp = rand(1000, 9999);
			// Self::send_sms_otp($request->mobile, $verifaction_otp);
			$user_detail->otp = $verifaction_otp;
			$user_detail->save();
			Self::send_sms_otp($request->mobile, $verifaction_otp);
			\DB::commit();
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['user_id' => $user_detail->id, 'otp' => $verifaction_otp]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function updateMobile(Request $request)
	{
		$error_message = 	[
			'mobile.required'  	=> 'Mobile should be required',
			'OTP.required'  	=> 'OTP  should be required',
		];
		$rules = [
			'mobile'            => 'required|min:10|max:10',
			'otp'               => 'required|min:4|max:4',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		$mobileExist = User::where('id', '!=', auth()->user()->id)->where(['role' => auth()->user()->role, 'mobile' => $request->mobile])->count();
		if ($mobileExist > 0) {
			return $this->sendFailed("Mobile number has been already taken", 200);
		}

		if (auth()->user()->otp != $request->otp) {
			return $this->sendFailed("wrong otp", 200);
		}

		try {
			\DB::beginTransaction();
			$user_details = auth()->user();
			$user_details->mobile = $request->mobile;
			$user_details->otp = null;
			$user_details->save();
			\DB::commit();
			return $this->sendSuccess('MOBILE NUMBER UPDATE SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	// UPDATE PROFILE
	public function updateUserProfile(Request $request)
	{
		$error_message = 	[
			'name.required'    	 => 'Name should be required',
			'email.required'	 => 'Email address should be required',
			'address.required'	 => 'Address should be required',
			'email.unique'  	 => 'Email address has been taken',
			'profile_pic.mimes'  => 'Profile photo format jpg,jpeg,png',
			'profile_pic.max'    => 'Profile photo max size 2 MB',
			'dob.required'		 => 'Date Of Birth should be required.',
			
		];
		$rules = [
			'name'            => 'required|max:30',			
			'mobile'          => 'required|min:6|max:13',
			
		];
		if (!empty($request->profile_pic)) {
			$rules['profile_pic'] = 'mimes:jpg,jpeg,png|max:2000';
		}
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}

		$user_details = auth()->user();
		$user_details->fill($request->only('name', 'mobile'));
		// if($request->hasFile('profile_pic')){
		// 	$filename = time() . '.' . $request->profile_pic->extension();            
		// 	$request->profile_pic->move(public_path('user_images'), $filename);
		// 	$user_details->profile_pic = $filename;
		// }
		$user_details->save();
		return $this->sendSuccess('PROFILE UPDATE SUCCESSFULLY');


		try {
			\DB::beginTransaction();
			$user_details = auth()->user();
			// $user_details->fill($request->only('name', 'email', 'address', 'dob', 'address'));
			if (!empty($request->file('profile_pic'))) {
				if (Storage::disk('public')->exists('user_images/' . $user_details->user_pic_name)) {
					Storage::disk('public')->delete('user_images/' . $user_details->user_pic_name);
				}
				$user_pic = time() . '_' . rand(1111, 9999) . '.' . $request->file('profile_pic')->getClientOriginalExtension();
				$request->file('profile_pic')->storeAs('user_images', $user_pic, 'public');
				$request['profile_pic'] = $user_pic;
				$user_details->profile_pic = $user_pic;
			}
			$user_details->save();
			\DB::commit();
			if ($request->mobile == auth()->user()->mobile) {
				// $mobileVerify = array('mobile_verify_status' => 1);
				return $this->sendSuccess('PROFILE UPDATE SUCCESSFULLY');
			} else {
				// $otp = rand(1000, 9999);
				// Self::send_sms_otp($request->mobile, $otp);
				// $user = auth()->user();
				// $verifaction_otp = $otp;
				// $user->otp = $verifaction_otp;
				// $user->save();
				// $mobileVerify = array('mobile_verify_status' => 0, 'otp' => $otp);
				return $this->sendSuccess('PROFILE UPDATE SUCCESSFULLY');
			}
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function forgot_password(Request $request)
	{
		$error_message = 	[
			'email.required'    => 'Email address should be required',
			'email.exists'      => 'WE COULD NOT FOUND ANY EMAIL'
		];
		$rules = [
			'email'       		=> 'required|email|exists:users,email',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$user_detail = User::where('email', $request->email)->first();
			if (!isset($user_detail)) {
				return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT', 200);
			}
			$verifaction_otp = rand(1000, 9999);
			$email_data = ['user_name' => $user_detail->first_name, 'verifaction_otp' => $verifaction_otp];
			\Mail::to($user_detail->email)->send(new \App\Mail\ForgotPassword($email_data));
			return $this->sendSuccess('OTP SENT SUCCESSFULLY', ['user_id' => $user_detail->id, 'verifaction_otp' => $verifaction_otp, 'email' => $user_detail->email]);
		} catch (\Throwable $e) {
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function reset_password(Request $request)
	{
		$error_message = 	[
			'id.required'  		=> 'Id should be required',
			'password.required' => 'Password should be required',
		];
		$rules = [
			'id'        		=> 'required|numeric|exists:users,id',
			'password'      	=> 'required',
		];
		$validator = Validator::make($request->all(), $rules, $error_message);
		if ($validator->fails()) {
			return $this->sendFailed($validator->errors()->first(), 200);
		}
		try {
			$user_detail = User::find($request->id);
			if (!isset($user_detail)) {
				return $this->sendFailed('WE COULD NOT FOUND ANY ACCOUNT', 200);
			}
			\DB::beginTransaction();
			$user_detail->password = Hash::make($request->user_password);
			$user_detail->save();
			\DB::commit();
			return $this->sendSuccess('PASSWORD UPDATED SUCCESSFULLY');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

	public function send_sms_otp($mobile_number, $verification_otp)
	{
		// 		return;
		// die;
		// $opt_url = "https://2factor.in/API/V1/fd9c6a99-19d7-11ec-a13b-0200cd936042/SMS/" . $mobile_number . "/" . $verification_otp . "/OTP_TAMPLATE";
		//$opt_url = "https://2factor.in/API/V1/786547ea-bbc8-11ec-9c12-0200cd936042/SMS/" . $mobile_number . "/" . $verification_otp . "/OTP_TAMPLATE";
		$opt_url = "https://2factor.in/API/V1/eaf9b2b6-d5b4-11ec-9c12-0200cd936042/SMS/" . $mobile_number . "/" . $verification_otp . "/FinalTemplate";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $opt_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_PROXYPORT, "80");

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		// echo $result;die;
		return;
	}


	public function sendsms2factorotp($numbers, $otp)
	{

		/*   phone = '+918949529301';
        $otp = '7777'; */

		$curl = curl_init();

		curl_setopt_array($curl, array(

			CURLOPT_URL => 'https://2factor.in/API/V1/786547ea-bbc8-11ec-9c12-0200cd936042/SMS/+' . $numbers . '/' . $otp . 'otptemplate',

			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_TIMEOUT => 30,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "{}",

		));

		$response = curl_exec($curl);

		$err = curl_error($curl);

		$respons = json_decode($response, true);
		// print_r($respons); exit;
		return $respons;
		// echo "<pre>";
		// print_r($respons['Status']);

		curl_close($curl);
	}
	public function changeOnlineStatus()
	{
		try {
			\DB::beginTransaction();
			$change = User::find(auth()->user()->id)->update(['online_status' => auth()->user()->online_status == 'Online' ? 'Offline' : 'Online']);
			\DB::commit();
			return $this->sendSuccess('Online Status change succssfully');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}


	/*
        |--------------------------------------------------------------------------
        | GET NOTIFICATION LIST
        |--------------------------------------------------------------------------
        */
		function getNotification()
		{
			try {
				$notification_list     = auth()->user()->notification()->orderBy('id', 'desc')->get();
				if (count($notification_list) == 0) {
					return $this->sendFailed('NOTIFICATION NOT FOUND', 200);
				}
				return $this->sendSuccess('NOTIFICATION GET SUCCESSFULLY', NotificationCollection::collection($notification_list));
			} catch (\Throwable $e) {
				return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
			}
		}
	public function logout()
	{
		try {
			\DB::beginTransaction();
			auth()->user()->carts()->delete();
			CouponCartMapping::where('user_id', auth()->user()->id)->delete();
			auth()->user()->token()->revoke();
			\DB::commit();
			return $this->sendSuccess('Logout succssfully');
		} catch (\Throwable $e) {
			\DB::rollback();
			return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
		}
	}

    public function deleteMyAccount()
    {
        try {
            DB::beginTransaction();
            auth()->user()->update([
                'name'                => 'Deleted User',
                'email'				=> 'Deleted User',
				'password'			=> 'Deleted User',
				'unique_id' 		=> 'Deleted User',
				'social_media_id'	=> 'Deleted User',
				'mobile' 			=> 'Deleted User',        
				'profile_pic' 		=> 'Deleted User',
				'device_token' 		=> 'Deleted User',
            ]);
            auth()->user()->delete();

            // $user = auth()->user()->token();
            // $user->revoke();
            DB::commit();
            return $this->sendSuccess('YOUR ACCOUNT PERMANENTLY DELETE SUCCESSFULLY');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendFailed($th->getMessage() . ' On line ' . $th->getLine(), 400);
        }
    }

	function version(){
		$version = Version::first();
		return $this->sendSuccess('VERSION GET SUCESS',['version' => @$version->version]);
	}
}
