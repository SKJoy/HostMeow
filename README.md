# HostMeow: Host availability checker
Periodically checks hosts if they are available and sends alerts to email & Slack channels when goes offline or comes back online.

## Installation
- **Slack**
	- Create App & deploy
	- Create channels
- **Application**
	- Clone repository file to `/my/hostmeow/path` on your server
	- Rename `/my/hostmeow/path/sample.configuration.php` file to `configuration.php` and modify as needed
		- Set `SMTP` configuration for mail send out
		- Set `HostAlertSlackAuthorizationToken` for Slack channel message
	- Rename `script/test/sample.http.csv` file to `http.csv` and modify the host list
	- **CronJob**: Per minute cron job to execute `/my/hostmeow/path/test-http.php` script
		- Cron command: `php /my/hostmeow/path/test-http.php > /dev/null &`
		- Full Cron command: `* * * * * php /my/hostmeow/path/test-http.php > /dev/null &`
	- **Note**
		- Shell command execution permission is required for PHP
		- `/my/hostmeow/path/temp` path must remain WRITEable for PHP

## How to
- **Check for error**: An error log `/my/hostmeow/path/error.php.log` file will be created upon any PHP error
- No **host scan** happenning
	- Check the `script/test/http.csv` file is exists with host list
	- Ensure the **CronJob** is set correctly
