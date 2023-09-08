<?php
require __DIR__ . "/library/external/vendor/autoload.php";

class Slack{
	private object $Property;

	public function __construct(?string $AuthorizationToken){
		$APIURL = "https://slack.com/api/";
		$cURL = curl_init();

		$this->Property = (object)get_defined_vars();

		//if(is_null($this->Property->URL))$this->Property->URL = "http://{$this->Property->Host}" . ($HTTPPort == 80 ? null : ":{$this->Property->HTTPPort}") . "";

		$this->Property->cURL = curl_init();
	}

	public function __destruct(){
		curl_close($this->Property->cURL);
	}

	public function SendMessage(string $Message, string $ChannelList, ?string $AuthorizationToken = null){
		if(is_null($AuthorizationToken))$AuthorizationToken = $this->Property->AuthorizationToken;

		curl_setopt($this->Property->cURL, CURLOPT_URL, "{$this->Property->APIURL}chat.postMessage");
		curl_setopt($this->Property->cURL, CURLOPT_RETURNTRANSFER, true); // Return the transfer as a string of the return value of curl_exec() instead of outputting it directly
		//curl_setopt($this->Property->cURL, CURLOPT_HEADER, true); // Include the header in the output
		curl_setopt($this->Property->cURL, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->Property->cURL, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->Property->cURL, CURLOPT_FOLLOWLOCATION, true);

		foreach(array_filter(explode(",", str_replace(" ", "", trim($ChannelList)))) as $Channel){
			curl_setopt($this->Property->cURL, CURLOPT_POSTFIELDS, [
				"token" => $AuthorizationToken, 
				"channel" => $Channel, 
				"text" => $Message, 
			]);
	
			$HTTPContent = curl_exec($this->Property->cURL);
			$HTTPResponseCode = (int)curl_getinfo($this->Property->cURL, CURLINFO_HTTP_CODE);
		}

		$Result = (object)[
			"Error" => [], 
			"Slack" => (object)[], 
		];

		if($HTTPResponseCode == 200){
			$Result->Slack->Response = json_decode($HTTPContent);

			if($Result->Slack->Response->ok){

			}
			else{ //! Slack API error
				$Result->Error[] = (object)["Code" => -99999, "Message" => "API error", ];
			}
		}
		else{ //! HTTP error
			$Result->Error[] = (object)["Code" => -99999, "Message" => "Server unreachable", ];
		}

		return $Result;
	}
}

class HostTest{
	const HTTP_STATUS_CODE_INFORMATION = [
		0 => ["Name" => "Unknown"], 
		200 => ["Name" => "Ok"], 
		301 => ["Name" => "Parmanent redirect"], 
		302 => ["Name" => "Temporary redirect"], 
		400 => ["Name" => "Bad request"], 
		401 => ["Name" => "Unauthorized"], 
		403 => ["Name" => "Forbidden"], 
		404 => ["Name" => "Not found"], 
		405 => ["Name" => "Method not allowed"], 
		500 => ["Name" => "Server error"], 
	];

	private object $Property;

	public function __construct(?string $Host = null, ?string $URL = null, ?int $ConnectionTimeout = 5, ?int $HTTPPort = 80){
		$cURL = curl_init();
		$this->Property = (object)get_defined_vars();

		if(is_null($this->Property->URL))$this->Property->URL = "http://{$this->Property->Host}" . ($HTTPPort == 80 ? null : ":{$this->Property->HTTPPort}") . "";
		if(is_null($this->Property->ConnectionTimeout))$this->Property->ConnectionTimeout = 5;
		if(is_null($this->Property->HTTPPort))$this->Property->HTTPPort = 80;

		$this->Property->cURL = curl_init();
	}

	public function __destruct(){
		curl_close($this->Property->cURL);
	}

	public function HTTP(?string $URL = null, ?string $AcceptableHTTPStatusCode = "200, 301", ?int $ConnectionTimeout = 5){
		if(is_null($URL))$URL = $this->Property->URL;
		if(is_null($AcceptableHTTPStatusCode))$AcceptableHTTPStatusCode = "200, 301";
		if(is_null($ConnectionTimeout))$ConnectionTimeout = $this->Property->ConnectionTimeout;

		curl_setopt($this->Property->cURL, CURLOPT_URL, trim($URL));
		curl_setopt($this->Property->cURL, CURLOPT_CONNECTTIMEOUT, $ConnectionTimeout); // Seconds to wait for connection; 0 = Indefinitely
		curl_setopt($this->Property->cURL, CURLOPT_TIMEOUT, 30); // Maximum seconds to keep connection
		curl_setopt($this->Property->cURL, CURLOPT_RETURNTRANSFER, true); // Return the transfer as a string of the return value of curl_exec() instead of outputting it directly
		curl_setopt($this->Property->cURL, CURLOPT_HEADER, true); // Include the header in the output
		curl_setopt($this->Property->cURL, CURLOPT_NOBODY, true); // Do not receive body
		curl_setopt($this->Property->cURL, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->Property->cURL, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->Property->cURL, CURLOPT_FOLLOWLOCATION, true); // Follow HTTP redirections

		$HTTPHeader = (object)[];

		foreach(explode(PHP_EOL, curl_exec($this->Property->cURL)) as $RawHTTPHeader){
			$RawHTTPHeader = trim($RawHTTPHeader);

			if($RawHTTPHeader){
				$HTTPHeaderKVPSeparatorPosition = strpos($RawHTTPHeader, ":");

				if($HTTPHeaderKVPSeparatorPosition === false){
					$HTTPHeader->{$RawHTTPHeader} = null;
				}
				else{
					$HTTPHeaderName = trim(substr($RawHTTPHeader, 0, $HTTPHeaderKVPSeparatorPosition));
					$HTTPHeaderValue = trim(substr($RawHTTPHeader, $HTTPHeaderKVPSeparatorPosition + 1));
		
					$HTTPHeader->{$HTTPHeaderName} = $HTTPHeaderValue;
				}
			}
		}

		$HTTPStatusCode = (int)curl_getinfo($this->Property->cURL, CURLINFO_HTTP_CODE);
		$HTTPStatusCodeInformation = (object)(self::HTTP_STATUS_CODE_INFORMATION[$HTTPStatusCode] ?? ["Name" => "Unknown", ]);
		$HostOnline = in_array($HTTPStatusCode, array_filter(explode(",", str_replace(" ", "", trim($AcceptableHTTPStatusCode)))));

		if(false)if(!$HostOnline)var_dump([
			"HostOnline" => $HostOnline, 
			"HTTPStatusCode" => $HTTPStatusCode, 
			"HTTPStatusCodeInformation" => $HTTPStatusCodeInformation, 
			"HTTPHeader" => $HTTPHeader, 
		]);

		return (object)[
			"Online" => $HostOnline, 
			"Code" => $HTTPStatusCode, 
			"CodeInformation" => $HTTPStatusCodeInformation, 
			"Header" => $HTTPHeader, 
		];
	}
}

function SendMail(
	string|array $ToAddress, 
	string $Subject, 
	string|array $From = null, 
	?string $Message = null, 
	?string $BodyStyle = null, 
	?string $MainStyle = null, 
	?array $SMTP = null, 
	?bool $HTML = true, 
){
	//return false;
	
	if(!is_array($ToAddress))$ToAddress = [$ToAddress];
	if(!is_array($From))$From = [$From, null];
	if(is_null($HTML))$HTML = true;

	$PHPMailer = new \PHPMailer\PHPMailer\PHPMailer();
	$PHPMailer->isHTML($HTML);         
	$PHPMailer->setFrom($From[0], $From[1]); // $PHPMailer->setFrom('hostmeow@pagla.net', 'HostMeow'); // Set sender of the mail
	$PHPMailer->Subject = $Subject;
	$PHPMailer->Body    = $Message;

	foreach($ToAddress as $ThisToAddress)foreach(explode(",", str_replace(" ", "", $ThisToAddress)) as $ThisThisToAddress)$PHPMailer->addAddress($ThisThisToAddress);
	//$PHPMailer->addAttachment('url', 'filename');    // Name is optional                        
	
	if(is_array($SMTP)){
		$SMTP["Debug"] = (bool)($SMTP["Debug"] ?? null);
		$SMTP["Host"] = $SMTP["Host"] ?? "localhost";
		$SMTP["User"] = $SMTP["User"] ?? null;
		$SMTP["Password"] = $SMTP["Password"] ?? null;
		$SMTP["Security"] = strtolower($SMTP["Security"] ?? null);

		$SMTP["Port"] = $SMTP["Port"] ?? (
			$SMTP["Security"] == "tls" ? 587 : (
				$SMTP["Security"] == "ssl" ? 465 : 
					25
			)
		);
		
		if($SMTP["Debug"])$PHPMailer->SMTPDebug = 2; // Enable verbose debug output
		$PHPMailer->isSMTP(); // Set mailer to use SMTP
		$PHPMailer->Host       = $SMTP["Host"];
		$PHPMailer->SMTPAuth   = (bool)$SMTP["User"];
		$PHPMailer->Username   = $SMTP["User"];
		$PHPMailer->Password   = $SMTP["Password"];
		$PHPMailer->SMTPSecure = $SMTP["Security"];
		$PHPMailer->Port       = $SMTP["Port"];
	}

	//print "PHPMailer: Mail sent to:"; var_dump($ToAddress, $SMTP);
	return $PHPMailer->send();
}
?>