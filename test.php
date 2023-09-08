<?php
require __DIR__ . "/library.php";
require __DIR__ . "/configuration.php";

var_dump($argv);
print "Hello, World!" . PHP_EOL;

SendMail("SKJoy2001@GMail.Com", "HostMeow alert test", $MailFrom, "This is a test alert", null, null, $SMTP);
?>