#!/bin/bash

serverInterface="enp2s0"
meshtvPort="8084"

ipaddress=$1

result=$(adb connect $1)

reset_result=$(adb -s $1:5555 shell pm clear com.jeric.googletv)

echo "$ipaddress"
echo "$result"
echo "$reset_result"
