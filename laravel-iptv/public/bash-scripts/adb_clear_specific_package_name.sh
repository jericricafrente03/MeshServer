#!/bin/bash


serverInterface="enp2s0"
meshtvPort="8084"

ipaddress=$1
packagename=$2
# result=$(adb connect 192.168.124.23)

# result_clear_cache=$(adb -s 192.168.124.23:5555 shell pm clear $packagename)
result=$(adb connect $1)

result_clear_cache=$(adb -s $1:5555 shell pm clear $packagename)

# com.netflix.ninja

# for debugging
    touch /var/www/org_holidayInn/debug/clearcache_debug.txt
    echo "$result" >> /var/www/org_holidayInn/debug/clearcache_debug.txt
    echo "$result_clear_cache" >> /var/www/org_holidayInn/debug/clearcache_debug.txt
    echo "$ipaddress" >> /var/www/org_holidayInn/debug/clearcache_debug.txt

    echo "$ipaddress"
    echo "$result"
    echo "$result_clear_cache"
# # Debug directory and file paths
# debug_dir="/var/www/org_holidayInn/debug"
# debug_file="$debug_dir/clearcache_debug.txt"

# # Ensure the debug directory exists; if not, create it
# if [ ! -d "$debug_dir" ]; then
#     mkdir -p "$debug_dir"
# fi

# # Ensure we can touch the debug file, and if successful, append debug information
# if touch "$debug_file"; then
#     # Append debugging information
#     echo "$result" >> "$debug_file"
#     echo "$result_clear_cache" >> "$debug_file"
#     echo "$ipaddress" >> "$debug_file"
#     echo "Debug information appended successfully."
# else
#     echo "Failed to write to $debug_file. Check file permissions and path."
# fi