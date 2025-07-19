#!/bin/sh
echo ================================== >> crc.log
date +"%Y %B %d (%I:%M:%S)" >> crc.log
echo ================================== >> crc.log
./owner.sh
rm ./crc.log
php -f crc_sh.php >> crc.log
cat ./crc.log