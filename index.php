<?php
error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
ini_set("error_log", __DIR__ . "/php.error.log");

require __DIR__ . "/library.php";
require __DIR__ . "/configuration.php";

if(!is_dir($HostHTTPStatusPath))mkdir($HostHTTPStatusPath, 0777, true);

$Slack = new Slack($HostAlertSlackAuthorizationToken);
$HostTest = new HostTest();

header("Content-type: text/plain");
print "" . PHP_EOL;
print "{$ApplicationName} 1.0 by Broken Arrow, SKJoy2001@GMail.Com" . PHP_EOL;
print "-----------------------------------------------------------" . PHP_EOL;

require "{$ScriptPath}" . strtolower(trim($_POST["Route"] ?? ($argv[1] ?? "Demo/Test"))) . ".php";

print PHP_EOL;
?>