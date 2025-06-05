#!/bin/bash

#ipaddress="192.168.110.49"
ipaddress=$1
packageName=$2
#result=$(adb connect 192.168.110.49) #for test
connection_result=$(adb connect "$ipaddress" 2>&1)


result=$(adb -s $ipaddress:5555 uninstall "$packageName" 2>&1)

# Determine status based on output
if echo "$result" | grep -q "Success"; then
    echo "Success"
else
    echo "$connection_result"
    echo "$result"
fi
    
    
    
  
