#!/bin/sh

while :
do
	sleep 15
	wget --http-user=kuishinbo --http-passwd=kuishinbosys -p "http://192.168.0.202/SnapshotJPEG?Resolution=640*480" -P "/usr/local/www/apache24/data/kuishinbo/camera"
	sleep 15
done
