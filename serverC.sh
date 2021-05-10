#!/bin/bash
echo $1 $2 $3 >> err.txt
echo $LD_LIBRARY_PATH
echo `export LD_LIBRARY_PATH=/var/www/html/libraries/libxl/lib:/var/www/html/libraries/libxl/lib64`
echo $LD_LIBRARY_PATH
#./readxl $1 $2 $3 >> err.txt
