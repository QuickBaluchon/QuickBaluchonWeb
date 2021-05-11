#!/bin/bash

date=`date -d '1 month ago' +%Y-%m`
java -jar /var/www/html/media/app/java_pa.jar $date
date >> /var/www/html/cron/dailyBillsLogs.txt