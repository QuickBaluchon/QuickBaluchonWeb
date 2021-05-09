#!/bin/bash

curl -X POST "http://localhost/api/bill"
date >> dailyBillsLogs.txt