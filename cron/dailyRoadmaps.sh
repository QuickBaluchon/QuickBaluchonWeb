#!/bin/bash

curl -X POST "http://localhost/api/roadmap"
date >> dailyRoadmapsLogs.txt