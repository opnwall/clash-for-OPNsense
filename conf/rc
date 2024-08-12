#!/bin/sh
#
# $clash for OPNsense$

. /etc/rc.subr

name="clash"
rcvar=clash_enable

command=/usr/sbin/daemon
command_args="-f -P /var/run/clash.pid /root/clash/bin/clash -d /root/clash/conf"

pidfile=/var/run/${name}.pid

status_cmd=clash_status

: ${clash_enable="NO"}


clash_status()
{
         if [ -n "$rc_pid" ]; then
             echo "${name} is running as pid $rc_pid."
             return 0
         else
             echo "${name} is not running."
         fi
}

load_rc_config clash
run_rc_command $1
