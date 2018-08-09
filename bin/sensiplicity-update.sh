#!/bin/sh
if [ -e /opt/sensiplicity/update_set.up ]
then
	echo "Attempting Update..."
	/bin/mv /opt/sensiplicity/etc/*.conf /opt/update/etc
	/bin/rm -rf /opt/old
	/bin/mv -f /opt/sensiplicity /opt/old 
	/bin/mv -f /opt/update /opt/sensiplicity
	chmod 4755 /opt/sensiplicity/bin/alarm-switch-daemon
	chmod 4755 /opt/sensiplicity/bin/sn-util-pi
	chmod 4755 /opt/sensiplicity/bin/halt_system

else
	echo "Not Attempting Update"
fi
