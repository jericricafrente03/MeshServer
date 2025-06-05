#!/bin/bash


serverInterface="enp2s0"
meshtvPort="8084"

ipaddress=$1

result=$(adb connect $1)
$(adb -s $1:5555 shell am start -n com.jeric.googletv/com.jeric.googletv.ui.main.MainActivity)
sleep 3
return=$(adb -s $1:5555 shell monkey -p com.disneyplus.ph -c android.intent.category.LAUNCHER 1)

echo "$return"