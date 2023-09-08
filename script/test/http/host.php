<?php
$Host = str_getcsv(base64_decode($argv[1] ?? ""));

if(isset($Host[5])){
	$TemplateField = [
		"{{ URL }}", 
		"{{ StatusCaption }}", 
		"{{ StatusEmoji }}", 
		"{{ Title }}", 
		"{{ HTTPStatusCode }}", 
		"{{ StatusChangeCaption }}", 
		"{{ StatusChangeCounter }}", 
		"{{ HTTPStatusName }}", 
	];

	$HostTitle = trim($Host[0]);
	$HostURL = strtolower(trim($Host[1]));
	$HostAcceptableHTTPStatusCode = $Host[2];
	$HostStatusConfirmationCount = (int)$Host[3]; // Status needs to be confirmed N times
	$HostAlertEmail = $Host[4];
	$HostAlertSlackChannel = $Host[5];
	
	if(substr($HostURL, 0, 7) != "http://" && substr($HostURL, 0, 8) != "https://")$HostURL = "http://{$HostURL}";
	$HostDomain = strtoupper(parse_url($HostURL)["host"]);
	if(!$HostTitle)$HostTitle = $HostDomain;
	if(!$HostStatusConfirmationCount)$HostStatusConfirmationCount = 5;

	$HostStatus = $HostTest->HTTP($HostURL, $HostAcceptableHTTPStatusCode, 10);
	
	$LastHostStatusFile = $HostHTTPStatusPath .  str_replace(str_split(":/\\"), "-", $HostURL) . ".json";
	$LastHostStatus = file_exists($LastHostStatusFile) ? json_decode(file_get_contents($LastHostStatusFile)) : (object)["Online" => true, "StatusChangeCounter" => 0, ];
	$LastHostStatus->StatusChangeCounter = $LastHostStatus->Online == $HostStatus->Online ? 1 : $LastHostStatus->StatusChangeCounter + 1; // Increase change counter for status change
	$HostStatusChanged = $LastHostStatus->StatusChangeCounter == $HostStatusConfirmationCount;

	print PHP_EOL . "{$HostTitle}: ";
	
	if($HostStatusChanged){
		$LastHostStatus->Online = $HostStatus->Online;

		$HostStatusCaption = $HostStatus->Online ? $HostStatusCaptionOnline : $HostStatusCaptionOffline;
		$HostStatusEmoji = $HostStatus->Online ? "✅" : "🛑"; // ⭕✅⛔❌❎❓❗🔴🛑✋🌐🔻🖐😡🚩🚨
		$HostStatusColor = $HostStatus->Online ? "Lime" : "Red";
		$HostStatusChangeCaption = $HostStatusChanged ? "Change" : "As is";

		$TemplateFieldData = [
			$HostURL, 
			$HostStatusCaption, 
			$HostStatusEmoji, 
			$HostTitle, 
			$HostStatus->Code, 
			$HostStatusChangeCaption, 
			$LastHostStatus->StatusChangeCounter, 
			$HostStatus->CodeInformation->Name, 
		];
		
		$HostStatusMessage = str_replace($TemplateField, $TemplateFieldData, $HostAlertMessage); //? Prepare message from template
		$HostStatusMessageSlack = str_replace($TemplateField, $TemplateFieldData, $HostAlertMessageSlack); //? Prepare message from template

		$MailSent = SendMail($HostAlertEmail, $HostStatusMessage, $MailFrom, "
			<h1 style=\"border-bottom: 1px Silver dotted; padding-bottom: 0.5em; color: Cyan; font-size: 125%;\">{$ApplicationName}: HTTP</h1>

			<div style=\"margin-bottom: 0.5em; border-bottom: 1px Silver dotted; padding-bottom: 0.5em;\">
				<span style=\"display: inline-block; width: 7em; font-weight: bold;\">Status:</span><span style=\"display: inline-block; color: {$HostStatusColor};\">" . strtoupper($HostStatusCaption) . "</span>
				<span style=\"display: inline-bock;\">(<span style=\"display: inline-bock; font-weight: bold;\">{$HostStatus->Code}</span>:<span style=\"display: inline-bock; margin-left: 0.5em; font-weight: bold;\">{$HostStatus->CodeInformation->Name}</span>)</span>
			</div>

			<div style=\"margin-bottom: 0.5em; border-bottom: 1px Silver dotted; padding-bottom: 0.5em;\"><span style=\"display: inline-block; width: 7em; font-weight: bold;\">URL:</span><a href=\"{$HostURL}\" style=\"display: inline-block; color: Yellow; text-decoration: none;\">{$HostURL}</a></div>
			<div style=\"margin-bottom: 0.5em; border-bottom: 1px Silver dotted; padding-bottom: 0.5em;\"><span style=\"display: inline-block; width: 7em; font-weight: bold;\">Time:</span><span style=\"display: inline-block;\">" . date("r"). "</span></div>
		", "background-color: Black; color: White; padding: 1.5em; font-family: Consolas, Verdana, Tahoma, Arial; font-size: 16px; line-height: 1.62;", "background-color: Black; color: White; padding: 1.5em;", $SMTP);

		print "	---	Alert email " . ($MailSent ? "sent to" : "failed for") . " {$HostAlertEmail}" . PHP_EOL;

		$Slack->SendMessage($HostStatusMessageSlack, $HostAlertSlackChannel, $HostAlertSlackAuthorizationToken);

		print $HostStatusMessage . PHP_EOL;
		print "	---	Slack message: " . $HostStatusMessageSlack . PHP_EOL;
	}
	else{
		//if($LastHostStatus->StatusChangeCounter > $HostStatusConfirmationCount)$LastHostStatus->StatusChangeCounter = $HostStatusConfirmationCount + 1; // Prevent change count overflow that are already confirmed N times
	}

	file_put_contents($LastHostStatusFile, json_encode($LastHostStatus));
}
?>