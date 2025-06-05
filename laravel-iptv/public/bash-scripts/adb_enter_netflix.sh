#!/bin/bash


serverInterface="enp2s0"
meshtvPort="8080"

ipaddress=$1
# result=$(adb connect 192.168.124.23)

# result_current_focus=$(adb -s 192.168.124.23:5555 shell dumpsys window | grep "mCurrentFocus" | cut -d'/' -f1 | awk -F' ' '{print $NF}')

result=$(adb connect $1)

result_current_focus=$(adb -s $1:5555 shell dumpsys window | grep "mCurrentFocus" | cut -d'/' -f1 | awk -F' ' '{print $NF}')

# if [ "$result_current_focus" == "com.google.android.gms" ]; then
#     # Execute key events to navigate.
#     adb -s 192.168.124.23:5555 shell input keyevent 20
#     adb -s 192.168.124.23:5555 shell input keyevent 66

#     # Re-check the focus after the key events, as it may have changed.
#     result_current_focus=$(adb -s $1:5555 shell dumpsys window | grep "mCurrentFocus" | cut -d'/' -f1 | awk -F' ' '{print $NF}')

#     # Check if result_current_focus is now equal to com.netflix.ninja
#     if [ "$result_current_focus" == "com.netflix.ninja" ]; then
#         # Pause for a short period to ensure the focus is correctly set.
#         sleep 1
#         # Execute key events for Netflix.
#         adb -s 192.168.124.23:5555 shell input keyevent 21
#         adb -s 192.168.124.23:5555 shell input keyevent 66
#         echo "Success"
#     else
#         echo "Current focus is not com.netflix.ninja, it is: $result_current_focus"
#     fi
# else
#     echo "Current focus is not com.google.android.gms, it is: $result_current_focus"
# fi

# Check if result_current_focus is now equal to com.netflix.ninja
if [ "$result_current_focus" == "com.netflix.ninja" ]; then
    # Pause for a short period to ensure the focus is correctly set.
    sleep 1
    # Execute key events for Netflix.
    adb -s $1:5555 shell input keyevent 21
    adb -s $1:5555 shell input keyevent 66
    echo "Success"
else
    echo "Current focus is not com.netflix.ninja, it is: $result_current_focus"
fi

# for debugging
    touch /var/www/meshtv/storage/logs/enter_netflix.txt
    echo "$result_current_focus" >> /var/www/meshtv/storage/logs/enter_netflix.txt
