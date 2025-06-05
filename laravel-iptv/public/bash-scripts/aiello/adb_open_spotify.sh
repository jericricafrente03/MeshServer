#!/bin/bash


serverInterface="enp2s0"
meshtvPort="8084"

ipaddress=$1
# result=$(adb connect 192.168.124.23)

# result_current_focus=$(adb -s 192.168.124.23:5555 shell dumpsys window | grep "mCurrentFocus" | cut -d'/' -f1 | awk -F' ' '{print $NF}')

result=$(adb connect $1)
$(adb -s $1:5555 shell am start -n com.jeric.googletv/com.jeric.googletv.ui.main.MainActivity)
sleep 3
return=$(adb -s $1:5555 shell monkey -p com.spotify.tv.android -c android.intent.category.LAUNCHER 1)

echo "$return"