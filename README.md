# HostMeow: Host availability checker
Periodically checks hosts if they are available and sends alerts to email & Slack channels when goes offline or comes back online.

## Installation
- **Slack**
	- Create App & deploy
	- Create channels
- **Application**
	- Rename `sample.configuration.php` to `configuration.php` and adjust with your configuration
		- Set `SMTP` configuration for mail send out
		- Set `HostAlertSlackAuthorizationToken` for Slack channel message
	- Rename `script/test/sample.http.csv` file to `http.csv` and adjust with host list
	- **CronJob**
		- Clone repository file to `/my/hostmeow/path`
		- Create a per minute cron job to execute `/my/hostmeow/path/test-http.php` script
			- Cron command: `php /my/hostmeow/path/test-http.php > /dev/null &`
			- Full Cron command: `* * * * * php /my/hostmeow/path/test-http.php > /dev/null &`
