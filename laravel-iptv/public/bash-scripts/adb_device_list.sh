#!/bin/bash
LOGFILE=$5

LOCKFILE="/tmp/adb_device_list.lock"

# Check if another instance is running
if [ -e "$LOCKFILE" ]; then
    echo "[$(date)] Script is already running. Exiting..." >> "$LOGFILE"
    exit 1
fi

# Create the lock file
touch "$LOCKFILE"

# Ensure the lock file is removed when the script exits or errors
trap 'rm -f "$LOCKFILE"; exit 1' EXIT ERR

# Check if the log file exists, if not create it and set permissions
if [ ! -f "$LOGFILE" ]; then
    touch "$LOGFILE"
    chmod 666 "$LOGFILE"
fi

# Redirect all output (stdout and stderr) to the log file
exec >> "$LOGFILE" 2>&1

# Echo datatime
echo "[$(date)] Device List Script started."

serverInterface=$1
scriptAdbDeviceStatusPath=$2
package=$3
serverIp=$4
dbHost=$6
dbUser=$7
dbPassword=$8
dbName=$9
allowedModels=${10}
echo ${10}

# Get ServerIP base on the serverInterface
serverIP=$(ifconfig $serverInterface | grep "inet " | awk '{print $2}')
# Get ServerSubnet
subnet=$(ip -o -f inet addr show | awk '/scope global/ {print $4}' | grep $serverIP)

# Get active IPs in the subnet
mapfile -t active < <(nmap -sP $subnet | awk '/is up/ {print up}; {gsub (/\(|\)/,""); up = $NF}')

# Loop through each active IP and call another script
for IP in "${active[@]}"
    do  
        # Capture the output from the second script
        result=$(bash "$scriptAdbDeviceStatusPath" "$IP" "$package" "$serverInterface" "$serverIp" "$dbHost" "$dbUser" "$dbPassword" "$dbName" "$allowedModels")

        # Print or process the result
        echo "Result from $IP: $result"
done   
  
# Log script completion
echo "[$(date)] Device List Script completed."