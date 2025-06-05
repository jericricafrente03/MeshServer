#!/bin/bash

ipAddress=$1
macAddress=$2
LOGFILE=$3
storeDir=$4
dbHost=$5
dbUser=$6
dbPassword=$7
dbName=$8

LOCKFILE="/tmp/adb_screen_capture_$macAddress.lock"

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

#DB AUTHENTICATION
sql="mysql -D'$dbName' -u'$dbUser' -p'$dbPassword' -se "

# Echo datatime
echo "[$(date)] Screen Capture Script started."

connect=$(adb connect $ipAddress)
echo "Connecting.. $connect"

timestamp=$(date +"%Y%m%d%H%M%S")

# Define directory path
remoteDir="/sdcard/Gallery/ScreenCaptures"

# Remove the directory if it exists (including files inside)
adb -s $ipAddress:5555 shell "rm -rf $remoteDir"

# Run screencap and force output
captureResult=$(adb -s $ipAddress:5555 shell "mkdir -p $remoteDir && screencap /sdcard/Gallery/ScreenCaptures/${macAddress}${timestamp}.png && echo 'Success'" 2>&1)
echo "Screen Capture Result: $captureResult"

# Ensure the local storeDir exists
if [ ! -d "$storeDir" ]; then
    mkdir -p "$storeDir"
    echo "Created directory $storeDir"
fi

# Pull the file and capture output
pullResult=$(adb -s $ipAddress:5555 pull $remoteDir/${macAddress}${timestamp}.png $storeDir 2>&1)
echo "Pull Result: $pullResult"

# Check if the IP exists in adb_devices
sql_result="$sql \"SELECT rooms.name, devices.api_id FROM rooms JOIN devices ON devices.room_id = rooms.id WHERE devices.mac_address='$macAddress';\""
device=$(eval $sql_result)
read roomName apiId <<< "$device"

imageDir="/storage/upload/device_adb/screen_capture/${macAddress}${timestamp}.png"
insert_query="$sql \"INSERT INTO adb_files (ip4_address, mac_address, room, img_uri, type, created_at, updated_at) VALUES ('$ipAddress', '$macAddress', '$roomName', '$imageDir', 'image', NOW(), NOW());\""
eval $insert_query

# Log script completion
echo "[$(date)] Screen Capture Script completed."