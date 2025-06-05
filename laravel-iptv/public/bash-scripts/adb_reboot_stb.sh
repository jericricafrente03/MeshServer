#!/bin/bash

serverInterface="enp2s0"
meshtvPort="8084"

ipaddress=$1

result=$(adb connect $1)

reboot_result=$(adb -s $1:5555 shell reboot)

echo "$ipaddress"
echo "$result"
echo "$reboot_result"
