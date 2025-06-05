#!/bin/bash

path="/var/www/meshtv/storage/app/public/upload/apk"
#apk="holidayinn-1.0.4-E-final-armeabi-v7a-debug.apk"
#ipaddress="192.168.110.49"
ipaddress=$1
apk=$2
#result=$(adb connect 192.168.110.49) #for test
result=$(adb connect "$ipaddress" 2>&1)


installation_result=$(adb -s $ipaddress:5555 install "$path/$apk" 2>&1)

# Determine status based on output
if echo "$installation_result" | grep -q "Success"; then
    echo "Success"
else
    echo "$result"
    echo "$installation_result"
fi
    
    
    
  
