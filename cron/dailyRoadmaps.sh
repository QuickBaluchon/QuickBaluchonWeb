#!/bin/bash

curl -X POST "http://localhost/api/roadmap"
date >> /var/www/html/cron/dailyRoadmapsLogs.txt