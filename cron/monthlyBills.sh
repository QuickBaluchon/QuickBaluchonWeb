#!/bin/bash

curl -X POST "http://localhost/api/bill"
date >> /var/www/html/cron/dailyBillsLogs.txt