<?php

Class Zoom {

    public function __construct()
    {
    	
    }

    public function auth( $clientId, $url)
    {
    	return 'https://zoom.us/oauth/authorize?response_type=code&client_id='.$clientId.'&redirect_uri='.$url;
    }

    public function token($clientId, $clientSecret, $code, $returnUrl)
    {
    	try {
			$url = 'https://zoom.us/oauth/token';
	        $request = 'grant_type=authorization_code&code='.$code.'&redirect_uri='.$returnUrl;
	        $ch          = curl_init($url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        		'Authorization: '."Basic ". base64_encode($clientId.':'.$clientSecret),
	                'Content-Type:application/x-www-form-urlencoded',
	                'Content-Length: ' . strlen($request)
	            ]
	        );

	        $response = json_decode(curl_exec($ch), true);
	        if(isset($response['reason'])) {
	        	return (object) [
	        		'status' => false,
	        		'message' => $response['reason']
	        	];
	        } else {
				return (object) [
	        		'status' => true,
	        		'data' => $response
	        	];
	        }
    	} catch(Exception $e) {
    		return (object) [
    			'status' => false,
    			'message' => $e->getMessage()
    		];
    	}
    }

    public function createMetting($clientId, $clientSecret, $tokenInfo, $array, $updateToken = false)
    {
    	try {
    		$url 		= 'https://zoom.us/v2/users/me/meetings';
	        $request 	= json_encode([
                "topic" 		=> $array['title'],
                "type" 			=> 1,
                "start_time" 	=> date('Y-m-d', strtotime($array['date'])) .'T'.date('H:i:s', strtotime($array['date'])),
                "duration" 		=> $array['duration'],
                "password" 		=> rand(9999, 999999)
	        ]);

	        $ch         = curl_init($url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        		'Authorization: '. 'Bearer ' . $tokenInfo['access_token'],
	                'Content-Type: application/json',
	                'Content-Length: ' . strlen($request)
	            ]
	        );

	        $response = json_decode(curl_exec($ch), true);
	        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	        if($httpCode == 201) {
	        	$response = [
	        		'status' 	=> true,
	        		'data' 		=> [
	        			'join_url' 		=> $response['join_url'],
	        			'password' 		=> $response['password'],
	        			'metting_id' 	=> $response['id']
	        		]
	        	];

	        	if($updateToken) {
	        		$response['update_token'] = $tokenInfo;
	        	}
	        	return (object) $response;
	        } else {
	        	if($response['code'] == 124) {
	        		if($updateToken == false) {
	        			$response = $this->refreshToken($clientId, $clientSecret, $tokenInfo['refresh_token']);
		        		if($response->status) {
		        			$response = (array) $response;
		        			$this->createMetting($clientId, $clientSecret, $response['data'], $array, true);
		        		} else {
		        			return $response;
		        		}
	        		} else {
	        			return (object) [
			        		'status' 	=> false,
			        		'message' 	=> 'Check client id & client secret'
			        	];
	        		}
	        		
	        	} else {
	        		return (object) [
		        		'status' 	=> false,
		        		'message' 	=> 'Intrnal error'
		        	];
	        	}
	        }
    	} catch(Exception $e) {
    		return (object) [
        		'status' 	=> false,
        		'message' 	=> $e->getMessage()
        	];
    	}
    }

    public function deleteMetting($clientId, $clientSecret, $tokenInfo, $mettingId, $updateToken = false)
    {
		try {
    		$url 		= 'https://api.zoom.us/v2/meetings/'.$mettingId;
	        $ch         = curl_init($url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        		'Authorization: '. 'Bearer ' . $tokenInfo['access_token'],
	                'Content-Type: application/json',
	            ]
	        );

	        $response = curl_exec($ch);
	        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	        if($httpCode == 204) {
	        	$response = [
	        		'status' 	=> true,
	        	];

	        	if($updateToken) {
	        		$response['update_token'] = $tokenInfo;
	        	}
	        	return (object) $response;
	        } else {
	        	if($httpCode == 404) {
	        		return (object) [
		        		'status' 	=> false,
		        		'message' 	=> 'Meeting ID not found'
		        	];
	        	} else {
	        		$response = json_decode($response, true);
	        		if(isset($response['code']) && $response['code'] == 124) {
	        			if($updateToken == false) {
		        			$response = $this->refreshToken($clientId, $clientSecret, $tokenInfo['refresh_token']);
			        		if($response->status) {
			        			$response = (array) $response;
			        			$this->deleteMetting($clientId, $clientSecret, $response, $mettingId, true);
			        		} else {
			        			return $response;
			        		}
		        		} else {
		        			return (object) [
				        		'status' 	=> false,
				        		'message' 	=> 'Check client id & client secret'
				        	];
		        		}
		        	} else {
		        		return (object) [
			        		'status' 	=> false,
			        		'message' 	=> 'Something wrong'
		        		];
		        	}
	        	}
	        }
    	} catch(Exception $e) {
    		return (object) [
        		'status' 	=> false,
        		'message' 	=> $e->getMessage()
        	];
    	}
    }

    public function refreshToken($clientId, $clientSecret, $refreshToken)
    {
    	try {
			$url 		= 'https://zoom.us/oauth/token';
	        $request 	= 'grant_type=refresh_token&refresh_token='.$refreshToken;
	        $ch         = curl_init($url);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
	                'Authorization: '.'Basic '. base64_encode($clientId.':'.$clientSecret),
	                'Content-Type:application/x-www-form-urlencoded',
	                'Content-Length: ' . strlen($request)
	            ]
	        );

	        $response = json_decode(curl_exec($ch), true);
	        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	        if($httpCode == 200) {
	        	return (object) [
	        		'status' 	=> true,
	        		'data' 		=> $response
	        	];
	        } else {
	        	return (object) [
	        		'status' => false,
	        		'message' => $response['reason']
	        	];
	        }
    	} catch(Exception $e) {
    		return (object) [
        		'status' => false,
        		'message' => $e->getMessage()
        	];
    	}
    }
}

