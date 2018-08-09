#!/bin/sh

#Check to see if update flag file exists
if [ -e /opt/sensiplicity/update_set.up ]
then
	echo "Attempting Update..."
	#Move config file into updated folder
	/bin/mv /opt/sensiplicity/etc/*.conf /opt/update/etc
	#Remove old Sensiplcity folder
	/bin/rm -rf /opt/old
	#Make current Sensiplicity folder old 
	/bin/mv -f /opt/sensiplicity /opt/old 
	#Make update folder the current running Sensiplicity version
	/bin/mv -f /opt/update /opt/sensiplicity
	#Set sticky bits so that these can be run as root
	chmod 4755 /opt/sensiplicity/bin/alarm-switch-daemon
	chmod 4755 /opt/sensiplicity/bin/sn-util-rpi
	chmod 4755 /opt/sensiplicity/bin/halt_system

else
	echo "Not Attempting Update"
fi
