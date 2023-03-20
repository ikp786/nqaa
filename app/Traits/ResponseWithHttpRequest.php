<?php
namespace App\Traits;

use App\Models\Notification;
use Twilio\Rest\Client;

trait ResponseWithHttpRequest{


	protected function sendSuccess($message, $result = NULL)
    {
    	$response = [
            'ResponseCode'      => 200,
            'Status'            => True,
            'Message'           => $message,
        ];

        if(!empty($result)){
            $response['Data'] = $result;
        }
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailed($errorMessages = [], $code = 200)
    {
    	$response = [
            'ResponseCode'      => $code,
            'Status'            => False,
        ];


        if(!empty($errorMessages)){
            $response['Message'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    
    public function generateRandomString($length = 10)

    {

        $characters = '0123456789';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand(0, $charactersLength - 1)];

        }

        return $randomString . strtotime("now");

    }
    protected function sendSms($mobile,$body){
                $sid            = env('TWILIO_ACCOUNT_SID', "AC20c5099c3307efd5192ea579dfa3729b");
                $token          = env('TWILIO_AUTH_TOKEN', "bf4b325ae6d8a574c7a3b5d87a251e5f");
                $mServiceSid    = env('MESSAGING_SERVICE_SID','MG19c561070f6271ceadc05d241f6f4b24');
                $twilio = new Client($sid, $token);
                $message = $twilio->messages->create(
					$mobile,
					array(
						"messagingServiceSid" => $mServiceSid,
						"body" => $body
					)
				);
            return $message;
    }

    function sendWhatsappMessage($mobile,$body){

        $sid            = env('TWILIO_ACCOUNT_SID', "AC20c5099c3307efd5192ea579dfa3729b");
        $token          = env('TWILIO_AUTH_TOKEN', "bf4b325ae6d8a574c7a3b5d87a251e5f");
        $from_number    = env('TWILIO_WHATSAPP_NUMBER','+14155238886');
        $twilio         = new Client($sid, $token);
        $twilio = new Client($sid, $token);    
        $message = $twilio->messages
          ->create("whatsapp:".$mobile,
            array(
              "from" => "whatsapp:".$from_number,
              "body" => $body
            )
          );
          return $message;
    print($message->sid);

    die;
        $message        = $twilio->messages
      ->create("whatsapp:".$mobile,
        array(
          "from" => "whatsapp:+14155238886",
          "body" => $body
        )
      );
      return $message;
    }


    public function SendNotification($device_token, $title, $body, $user_id)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
			'Authorization: key=AAAAMhZ6hz4:APA91bHhKoaHrV2STpuGK3WxWY6e06iAa6vn28LZ9r9iAuq3cT2geqok4y7evY57rTAcdr0v-ccH1KIvKTykP6wFPKFf_nziS7AWB9-RhW_0uqrEKdagnyf0ZI0wLs2QkU5X8tCshLZr',
			'Content-Type: application/json',
		);
		$data = array(
			"to" => $device_token,
			"notification" =>
			array(
				"title" 			=> $title,
				"body"  			=> $body,
				"sound" 			=> 'default',
				'badge'             => '1', 
				'action_type'       => 'transfer',
			),
			"data" =>
			array(
				"title" 			=> $title,
				"body"  			=> $body,
				"sound" 			=> 'default',
				'badge'             => '1',
				'action_type'       => 'transfer',
			)
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$result = curl_exec($ch);
		curl_close($ch);
			$notification = new Notification();
			$notification->user_id 	= $user_id;
			$notification->title 	= $title;
			$notification->details  = $body;
			$notification->save();
		return $result;
	}
}