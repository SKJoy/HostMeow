<?php
$HostListFile = __DIR__ . "/http.csv";

if(file_exists($HostListFile)){
	foreach(array_filter(explode(PHP_EOL, file_get_contents($HostListFile))) as $Host){
		$Host = trim($Host);
	
		if(substr($Host, 0, 1) != "#"){ //* Exclude comment line
			print substr($Host, 0, 76) . " ..." . PHP_EOL;
			exec("php {$BasePath}test-http-host.php " . base64_encode($Host) . " > /dev/null &");
		}
	}
}
else{
	print "Host list file '{$HostListFile}' not found!" . PHP_EOL;
}
?>