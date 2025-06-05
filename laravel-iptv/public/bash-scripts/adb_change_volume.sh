#!/bin/bash


ipaddress=$1
volume=$2

$(adb connect $1)
mutestatus=$(adb -s $1:5555 shell dumpsys audio | grep "Mute" | head -n 1 | awk '{print $2}')

if [ "$volume" == "up" ]; then
    if [ "$mutestatus" == "false" ]; then
        result=$(adb -s $1:5555 shell input keyevent 24)
        echo "Success"
        echo ""
    else
        echo "Failed"
        echo "Still in mute status"
    fi
fi

if [ "$volume" == "down" ]; then
    if [ "$mutestatus" == "false" ]; then
        result=$(adb -s $1:5555 shell input keyevent 25)
        echo "Success"
        echo ""
    else
        echo "Failed"
        echo "Still in mute status"
    fi
fi

if [ "$volume" == "mute" ]; then
    if [ "$mutestatus" == "false" ]; then
        result=$(adb -s $1:5555 shell input keyevent 164)
        echo "Success"
        echo ""
    else
        echo "Failed"
        echo "Already in mute status"
    fi
fi

if [ "$volume" == "unmute" ]; then
    if [ "$mutestatus" == "true" ]; then
        result=$(adb -s $1:5555 shell input keyevent 164)
        echo "Success"
        echo ""
    else
        echo "Failed"
        echo "Already in unmute status"
    fi
fi

echo "Failed"
# echo "$ipaddress"
# echo "$result"
    
