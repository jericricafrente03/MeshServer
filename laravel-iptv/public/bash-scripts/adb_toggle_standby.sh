#!/bin/bash


ipaddress=$1
status=$2

$(adb connect $1)

# touch /var/www/meshtv/storage/logs/adb_toggle_standby_log.txt
# echo "$1" >> /var/www/meshtv/storage/logs/adb_toggle_standby_log.txt
screen_state=$(adb -s $ipaddress:5555 shell dumpsys power | grep "Display Power: state=" | awk -F'=' '{print $2}')

if [ "$status" == "on" ]; then
    if [ "$screen_state" != "ON" ]; then
        result=$(adb -s $1:5555 shell input keyevent 26)
        echo "Success"
        echo ""
    else
        echo "Failed"
        echo "Status is already on."
    fi
fi

if [ "$status" == "off" ]; then
    if [ "$screen_state" == "ON" ]; then
        result=$(adb -s $1:5555 shell input keyevent 26)
        echo "Success"
        echo ""
    else
        echo "Failed"
        echo "Status is already off."
    fi
fi


# echo "$ipaddress"
# echo "$result"
    
