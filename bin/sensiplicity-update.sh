#!/bin/sh
if [ -e /opt/sensiplicity/update_set.up ]
then
	echo "Attempting Update..."
	/usr/bin/git -C /opt/sensiplicity pull
	/bin/rm /opt/sensiplicity/update_set.up
else
	echo "Not Attempting Update"
fi
