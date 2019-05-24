<?php
namespace controllers\http;

class JWT
{
	
	public function __construct()
	{
		#code ...
	}

	public static function SetJWT($request){

		$header = [
			'alg' => 'HS256',
			'typ' => 'JWT'
		];

		$header = base64_encode(json_encode($header));

		$key = "my-pass-key";
		$tokenId = base64_encode(md5($key));
		$issuedAt = time();
		$notBefore = $issuedAt + 10;
		$expire = $notBefore + 60;

		$serverName = $_SERVER['HTTP_HOST'];

		$payload = [
			'iat' => $issuedAt,
			'jti' => $tokenId,
			'iss' => $serverName,
			'nbt' => $notBefore,
			'exp' => $expire,
			'userId' => $request['userId'],
			'userName' => $request['userName'],
			'userEmail' => $request['userEmail']
		];

		$payload = base64_encode(json_encode($payload));

		$secret = sha1("my-secret-pass");

		$signature = base64_encode(hash_hmac("sha256", "$header.$payload", $secret, true));

		$jwt = "$header.$payload.$signature";

		
		header(
			"Authorization: Bearer $jwt"
		);

		return http_response_code(200);
		

	}

	public static function verJWT(){
		$token = apache_request_headers();

		if (isset($token['Authorization'])) {
			list($auth) = sscanf($token['Authorization'], "Bearer %s");

			$parts_jwt = explode('.', $auth);
			
			$header = $parts_jwt[0];
			$payload = $parts_jwt[1];
			$signature = $parts_jwt[2];

			$secret = sha1("my-secret-pass");

			$valid = base64_encode(hash_hmac("sha256", "$header.$payload", $secret, true));
			
			if ($signature == $valid) {
				http_response_code(202);
				return true;
			}else{
				http_response_code(401);
				return false;
			}	
		}else{
			http_response_code(401);
			return false;
		}
	}

}