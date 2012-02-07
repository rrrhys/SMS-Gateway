mysqldump -u root -p -h localhost -d sms_gateway > smsgateway.sql
hg addremove
hg commit
pause