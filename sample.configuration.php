<?php
error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
ini_set("error_log", __DIR__ . "/php.error.log");

$ApplicationName = "Host Meow";
$HostStatusCaptionOnline = "Online";
$HostStatusCaptionOffline = "Offline";
$MailFrom = ["alert@domain.tld", "HostMeow"]; // Should match the same SMTP domain
$HostHTTPStatusPath = __DIR__ . "/temp/host/http/status/";
$HostAlertSlackAuthorizationToken = "xxxx-xxxxxxxxxxxxx-xxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxx";
$HostAlertMessage = "{{ StatusCaption }} | {{ Title }} | {{ HTTPStatusName }}";
$HostAlertMessageSlack = "{{ StatusEmoji }} {{ StatusCaption }} 🌐 *<{{ URL }}|{{ Title }}>* ▶ {{ HTTPStatusName }}";
$ScriptPath = __DIR__ . "/script/";
$BasePath = __DIR__ . "/";

$SMTP = [
	"Host" => "mail.domain.tld", 
	"User" => "smtp@domain.tld", 
	"Password" => "SMTP MAILBOX PASSWORD", 
	"Security" => "SSL", // NULL, SSL, TLS
];
?>