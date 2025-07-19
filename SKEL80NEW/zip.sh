#!/bin/sh
chown -R admin:admin .
ver=`cat ver`
new="${ver%.*}.$((${ver##*.}+1))"
sed -i "s/$ver/$new/g" ./ver
./owner.sh
./crc.sh
zip -r -9 ../skeleton80.$new.zip *