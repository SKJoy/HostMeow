# HostMeow: Host availability checker
Periodically checks hosts if they are available and sends alerts to email & Slack channels when goes offline or comes back online.

## Installation
- **Slack**
	- Create App & deploy
	- Create channels
- **Application**
	- Clone repository file to `/my/hostmeow/path` on your server
		- Command `git clone https://github.com/SKJoy/HostMeow.git /my/hostmeow/path`
	- Rename `/my/hostmeow/path/sample.configuration.php` file to `configuration.php` (or use the `install.sh` script) and modify as needed
		- Set `MailFrom` address as needed; domain should match the SMTP domain
		- Set `SMTP` configuration for mail send out
		- Set `HostAlertSlackAuthorizationToken` for Slack channel message
	- Rename `script/test/sample.http.csv` file to `http.csv` (or use the `install.sh` script) and modify the host list
	- **CronJob**: Per minute cron job to execute `/my/hostmeow/path/test-http.php` script
		- Command: `php /my/hostmeow/path/test-http.php > /dev/null &`
		- Full command: `* * * * * php /my/hostmeow/path/test-http.php > /dev/null &`
	- **Note**
		- Shell command execution permission is required for PHP
		- `/my/hostmeow/path/temp` path must remain WRITEable for PHP

## How to
- `install.sh` is not working: Use `chmod +x install.sh && ./install.sh` command
- **Check for error**: An error log `/my/hostmeow/path/error.php.log` file will be created upon any PHP error
- No **host scan** happenning
	- Check the `script/test/http.csv` file is exists with host list
	- Ensure the **CronJob** is set correctly
- Emails are not sent out
	- Check `SMTP` configuration
	- Ensure `SMTP` & `MailFrom` belongs to same domain
